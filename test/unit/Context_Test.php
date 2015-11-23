<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib;
include_once(__DIR__ . '/phpunit_bootstrap.php');

class Context_UnitTest extends \Flancer32\Lib\Test\BaseTestCase {

    public function test_singleton() {
        /* get singleton instance */
        $ctx = Context::instance();
        $this->assertTrue($ctx instanceof \Flancer32\Lib\Context);
    }

    public function test_reset() {
        /* get singleton instance */
        $ctx = Context::instance();
        $hashBefore = spl_object_hash($ctx);
        /* reset context */
        $ctx->reset();
        /* get mock as singleton instance */
        $afterReset = Context::instance();
        $hashAfter = spl_object_hash($afterReset);
        $this->assertTrue($afterReset instanceof \Flancer32\Lib\Context);
        $this->assertNotEquals($hashBefore, $hashAfter);
    }

    public function test_getClassname_mage1() {
        /* Create context mock */
        $mockCtx = $this
            ->getMockBuilder('Flancer32\Lib\Context')
            ->setMethods([ 'isMage2' ])
            ->getMock();
        // self::instance()->isMage2()
        $mockCtx
            ->expects($this->once())
            ->method('isMage2')
            ->will($this->returnValue(false));
        Context::set($mockCtx);
        /* get mock as singleton instance */
        $ctx = Context::instance();
        $clazz = $ctx->getClassname('Magento\Framework\DB\Adapter\Pdo\Mysql');
        $this->assertEquals('Magento_Db_Adapter_Pdo_Mysql', $clazz);
    }

    public function test_getClassname_mage2() {
        /* Create context mock */
        $mockCtx = $this
            ->getMockBuilder('Flancer32\Lib\Context')
            ->setMethods([ 'isMage2' ])
            ->getMock();
        // self::instance()->isMage2()
        $mockCtx
            ->expects($this->once())
            ->method('isMage2')
            ->will($this->returnValue(true));
        Context::set($mockCtx);
        /* get mock as singleton instance */
        $ctx = Context::instance();
        $clazz = $ctx->getClassname('Magento\Framework\DB\Adapter\Pdo\Mysql');
        $this->assertEquals('Magento\Framework\DB\Adapter\Pdo\Mysql', $clazz);
    }


    /**
     * Reset context after each test (clean up context mocks).
     */
    protected function tearDown() {
        Context::reset();
    }

}