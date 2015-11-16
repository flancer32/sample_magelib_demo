<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Context;

use Flancer32\Lib\Context;

include_once(__DIR__ . '/../phpunit_bootstrap.php');

class DbAdapter_FunctionalTest extends \PHPUnit_Framework_TestCase {

    public function test_constructor() {
        /** @var  $ctx \Flancer32\Lib\Context */
        $ctx = Context::instance();
        $obm = $ctx->getObjectManager();
        $obj = $obm->get('Flancer32\Lib\Context\IDbAdapter');
        $this->assertTrue($obj instanceof \Flancer32\Lib\Context\IDbAdapter);
    }
}