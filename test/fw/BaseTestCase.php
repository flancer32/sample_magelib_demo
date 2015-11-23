<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Test;


use Flancer32\Lib\Context;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * Use M1 Class Name Mapper to get appropriate mock.
     *
     * @param string $className
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    public function getMockBuilder($className) {
        $mappedClassName = Context::getClassname($className);
        return parent::getMockBuilder($mappedClassName);
    }

    protected function _mockDbAdapter($mockResource, $mockConnection) {
        $result = $this
            ->getMockBuilder('Flancer32\Lib\Context\IDbAdapter')
            ->disableOriginalConstructor()
            ->setMethods([ 'getDefaultConnection', 'getResource' ])
            ->getMock();
        $result
            ->expects($this->any())
            ->method('getDefaultConnection')
            ->will($this->returnValue($mockConnection));
        $result
            ->expects($this->any())
            ->method('getResource')
            ->will($this->returnValue($mockResource));
        return $result;
    }
}