<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Service\Customer;

use Flancer32\Lib\Context;

include_once(__DIR__ . '/../../phpunit_bootstrap.php');

class Call_Test extends \PHPUnit_Framework_TestCase {

    /**
     * This is fake test. Launch it from debugger to trace lib functionality.
     */
    public function test_dbSelect() {
        $call = Context::instance()->getObjectManager()->create('Flancer32\Lib\Service\Customer\Call');
        $resp = $call->dbSelect(PHP_INT_MAX);
        $this->assertNull($resp);
    }
}