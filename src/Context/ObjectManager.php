<?php
/**
 * M1 wrapper for Zend Dependency Injection compatible with Magento 2 DI. Is used in M1 only.
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Context;


use Flancer32\Lib\Context;

class ObjectManager extends \Zend\Di\Di implements Context\IObjectManager {


    /**
     * ObjectManager constructor.
     */
    public function __construct(
        \Zend\Di\DefinitionList $definitions = null,
        \Zend\Di\InstanceManager $instanceManager = null,
        \Zend\Di\Config $config = null
    ) {
        $defs = new \Zend\Di\DefinitionList(new \Flancer32\Lib\Di\M1RuntimeDefinition());
        parent::__construct($defs, $instanceManager, $config);
    }

    public function addSharedInstance($instance, $classOrAlias) {
        $this->instanceManager()->addSharedInstance($instance, $classOrAlias);
    }

    public function create($type, array $arguments = [ ]) {
        $m1Type = Context::getClassname($type);
        if($m1Type[0] == '\\') {
            $m1Type = substr($m1Type, 1, strlen($m1Type) - 1);
        }
        $result = $this->newInstance($m1Type, $arguments, $isShared = false);
        return $result;
    }

}