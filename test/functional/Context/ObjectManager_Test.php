<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Context;

use Flancer32\Lib\Context;

include_once(__DIR__ . '/../phpunit_bootstrap.php');

class ObjectManager_FunctionalTest extends \PHPUnit_Framework_TestCase {

    public function test_constructor() {
        /** @var  $ctx \Flancer32\Lib\Context */
        $ctx = Context::instance();
        $obm = $ctx->getObjectManager();
        $this->assertNotNull($obm);
        $this->assertTrue($obm instanceof \Magento\Framework\ObjectManagerInterface);
    }
}