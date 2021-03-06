<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Service\Customer;


class Call_UnitTest extends \PHPUnit_Framework_TestCase {

    public function test_operation() {
        $mockDbAdapter = $this
            ->getMockBuilder('Flancer32\Lib\Context\IDbAdapter')
            ->getMock();
        $call = new Call($mockDbAdapter);
        $resp = $call->operation(2);
        $this->assertEquals(4, $resp);
    }

}