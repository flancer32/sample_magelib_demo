<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Di;


class M1ClassReflection extends \Zend\Code\Reflection\ClassReflection {
    /**
     * Return method reflection by name
     *
     * @param  string $name
     *
     * @return MethodReflection
     */
    public function getMethod($name) {
        $method = new M1MethodReflection($this->getName(), parent::getMethod($name)->getName());
        return $method;
    }
}