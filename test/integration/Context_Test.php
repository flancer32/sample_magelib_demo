<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib;
include_once(__DIR__ . '/phpunit_bootstrap.php');

class Context_FunctionalTest extends \PHPUnit_Framework_TestCase {

    public function test_singleton() {
        /* get singleton instance */
        $ctx = Context::instance();
        $this->assertTrue($ctx instanceof \Flancer32\Lib\Context);
    }
}