<?php
/**
 * Copyright (c) 2015, Praxigento
 * All rights reserved.
 */

/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Service\Customer;


class Call_UnitTest extends \PHPUnit_Framework_TestCase {

    public function test_operation() {
        $call = new Call();
        $resp = $call->operation(2);
        $this->assertEquals(4, $resp, "A-a-a-a!!! Sum operation is broken on this platform!");
    }

}