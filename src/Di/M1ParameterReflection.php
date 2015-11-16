<?php
namespace Flancer32\Lib\Di;

use Flancer32\Lib\Context\ClassMap;
use Zend\Code\Reflection\ClassReflection;

/**
 * User: Alex Gusev <alex@flancer64.com>
 */
class M1ParameterReflection extends \Zend\Code\Reflection\ParameterReflection {

    public function getClass() {
        try {
            $phpReflection = parent::getClass();
        } catch(\Exception $e) {
            $m2class = $e->getMessage();
            $m2class = str_replace('Class ', '', $m2class);
            $m2class = str_replace('does not exist', '', $m2class);
            $m1class = ClassMap::getM1Class($m2class);
            $phpReflection = new \ReflectionClass($m1class);
        }
        if($phpReflection === null) {
            return;
        }

        $zendReflection = new ClassReflection($phpReflection->getName());
        unset($phpReflection);

        return $zendReflection;
    }
}