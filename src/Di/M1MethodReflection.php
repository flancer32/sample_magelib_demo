<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Di;


class M1MethodReflection extends \Zend\Code\Reflection\MethodReflection {
    /**
     * Get all method parameter reflection objects
     *
     * @return ParameterReflection[]
     */
    public function getParameters() {
        $phpReflections = parent::getParameters();
        $zendReflections = [ ];
        while($phpReflections && ($phpReflection = array_shift($phpReflections))) {
            $instance = new M1ParameterReflection(
                [ $this->getDeclaringClass()->getName(), $this->getName() ],
                $phpReflection->getName()
            );
            $zendReflections[] = $instance;
            unset($phpReflection);
        }
        unset($phpReflections);

        return $zendReflections;
    }
}