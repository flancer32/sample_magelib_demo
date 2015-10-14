<?php
/**
 * Copyright (c) 2015, Praxigento
 * All rights reserved.
 */

/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Service\Customer;


class Call_DvlpTest extends \PHPUnit_Framework_TestCase {

    public function test_operation() {
        $call = new Call();
        $resp = $call->dbInsert(array());
        $this->assertNotNull($resp);
    }
}