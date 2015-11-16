<?php
namespace Flancer32\Lib\Di;

use Zend\Code\Annotation\AnnotationCollection;
use Zend\Code\Reflection;
use Zend\Di\Di;

/**
 * User: Alex Gusev <alex@flancer64.com>
 */
class M1RuntimeDefinition extends \Zend\Di\Definition\RuntimeDefinition {
    /**
     * @param string $class
     * @param bool   $forceLoad
     */
    protected function processClass($class, $forceLoad = false) {
        if(!isset($this->processedClass[$class]) || $this->processedClass[$class] === false) {
            $this->processedClass[$class] = (array_key_exists($class, $this->classes) && is_array($this->classes[$class]));
        }

        if(!$forceLoad && $this->processedClass[$class]) {
            return;
        }

        $strategy = $this->introspectionStrategy; // localize for readability

        /** @var $rClass \Zend\Code\Reflection\ClassReflection */
        $rClass = new M1ClassReflection($class);
        $className = $rClass->getName();
        $matches = null; // used for regex below

        // setup the key in classes
        $this->classes[$className] = [
            'supertypes'   => [ ],
            'instantiator' => null,
            'methods'      => [ ],
            'parameters'   => [ ]
        ];

        $def = &$this->classes[$className]; // localize for brevity

        // class annotations?
        if($strategy->getUseAnnotations() == true) {
            $annotations = $rClass->getAnnotations($strategy->getAnnotationManager());

            if(($annotations instanceof AnnotationCollection)
               && $annotations->hasAnnotation('Zend\Di\Definition\Annotation\Instantiator')
            ) {
                // @todo Instantiator support in annotations
            }
        }

        $rTarget = $rClass;
        $supertypes = [ ];
        do {
            $supertypes = array_merge($supertypes, $rTarget->getInterfaceNames());
            if(!($rTargetParent = $rTarget->getParentClass())) {
                break;
            }
            $supertypes[] = $rTargetParent->getName();
            $rTarget = $rTargetParent;
        } while(true);

        $def['supertypes'] = array_keys(array_flip($supertypes));

        if($def['instantiator'] === null) {
            if($rClass->isInstantiable()) {
                $def['instantiator'] = '__construct';
            }
        }

        if($rClass->hasMethod('__construct')) {
            $def['methods']['__construct'] = Di::METHOD_IS_CONSTRUCTOR; // required
            $this->processParams($def, $rClass, $rClass->getMethod('__construct'));
        }

        foreach($rClass->getMethods(Reflection\MethodReflection::IS_PUBLIC) as $rMethod) {
            $methodName = $rMethod->getName();

            if($rMethod->getName() === '__construct' || $rMethod->isStatic()) {
                continue;
            }

            if($strategy->getUseAnnotations() == true) {
                $annotations = $rMethod->getAnnotations($strategy->getAnnotationManager());

                if(($annotations instanceof AnnotationCollection)
                   && $annotations->hasAnnotation('Zend\Di\Definition\Annotation\Inject')
                ) {
                    // use '@inject' and search for parameters
                    $def['methods'][$methodName] = Di::METHOD_IS_EAGER;
                    $this->processParams($def, $rClass, $rMethod);
                    continue;
                }
            }

            $methodPatterns = $this->introspectionStrategy->getMethodNameInclusionPatterns();

            // matches a method injection pattern?
            foreach($methodPatterns as $methodInjectorPattern) {
                preg_match($methodInjectorPattern, $methodName, $matches);
                if($matches) {
                    $def['methods'][$methodName] = Di::METHOD_IS_OPTIONAL; // check ot see if this is required?
                    $this->processParams($def, $rClass, $rMethod);
                    continue 2;
                }
            }

            // method
            // by annotation
            // by setter pattern,
            // by interface
        }

        $interfaceInjectorPatterns = $this->introspectionStrategy->getInterfaceInjectionInclusionPatterns();

        // matches the interface injection pattern
        /** @var $rIface \ReflectionClass */
        foreach($rClass->getInterfaces() as $rIface) {
            foreach($interfaceInjectorPatterns as $interfaceInjectorPattern) {
                preg_match($interfaceInjectorPattern, $rIface->getName(), $matches);
                if($matches) {
                    foreach($rIface->getMethods() as $rMethod) {
                        if(($rMethod->getName() === '__construct') || !count($rMethod->getParameters())) {
                            // constructor not allowed in interfaces
                            // Don't call interface methods without a parameter (Some aware interfaces define setters in ZF2)
                            continue;
                        }
                        $def['methods'][$rMethod->getName()] = Di::METHOD_IS_AWARE;
                        $this->processParams($def, $rClass, $rMethod);
                    }
                    continue 2;
                }
            }
        }
    }
}