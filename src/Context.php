<?php
/**
 * Execution environment context (Magento 1 / Magento 2).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib;

class Context {
    /**
     * Type of the execution environment.
     */
    const TYPE_MAGE_1 = 'M1';
    const TYPE_MAGE_2_CLI = 'M2_CLI';
    const TYPE_MAGE_2_WEB = 'M2_WEB';
    const TYPE_TEST_M1 = 'TEST_M1';
    const TYPE_TEST_M2 = 'TEST_M2';
    /**
     * Flag for current execution context type.
     * @var string
     */
    private $_contextType;
    /**
     * Itself. Singleton.
     *
     * @var Context
     */
    private static $_instance;
    /**
     * Cached Object Manager.
     *
     * @var  Context\ObjectManager
     */
    private $_objectManager;

    /**
     * Context constructor.
     *
     * @param \Zend_Db_Adapter_Pdo_Mysql $_connection
     */
    public function __construct() {
        /* analyze current runtime environment Magento 1 | Magento 2 */
        if(class_exists('\Mage', false)) {
            /* this is Magento 1 */
            $this->_contextType = self::TYPE_MAGE_1;
        } elseif(
            isset($GLOBALS['bootstrap']) &&
            ($GLOBALS['bootstrap'] instanceof \Magento\Framework\App\Bootstrap)
        ) {
            if(
                isset($GLOBALS['app']) &&
                ($GLOBALS['app'] instanceof \Flancer32\Test\App)
            ) {
                /* this is Magento 2 functional/integration tests  */
                $this->_contextType = self::TYPE_TEST_M2;
            } else {
                /* this is Magento 2 web application  */
                $this->_contextType = self::TYPE_MAGE_2_WEB;
            }
        } elseif(
            isset($GLOBALS['application']) &&
            ($GLOBALS['application'] instanceof \Magento\Framework\Console\Cli)
        ) {
            /* this is Magento 2 CLI application (./htdocs/bin/magento) */
            $this->_contextType = self::TYPE_MAGE_2_CLI;
        } elseif(defined('IS_M1_TESTS')) {
            $this->_contextType = self::TYPE_TEST_M1;
        } elseif(defined('IS_M2_TESTS')) {
            $this->_contextType = self::TYPE_TEST_M2;
        }
    }

    /**
     * Get singleton instance.
     *
     * @return Context
     */
    public static function  instance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new Context();
        }
        return self::$_instance;
    }

    /**
     * Set test unit instance with mocked methods.
     *
     * @param Context $instance
     */
    public static function  set(Context $instance) {
        self::$_instance = $instance;
    }

    /**
     * Reset instance.
     */
    public static function  reset() {
        self::$_instance = null;
    }

    /**
     * 'true' if execution context is Magento 2 application (web, CLI or test).
     *
     * @return bool
     */
    public function isMage2() {
        $result =
            ($this->_contextType == self::TYPE_MAGE_2_CLI) ||
            ($this->_contextType == self::TYPE_MAGE_2_WEB) ||
            ($this->_contextType == self::TYPE_TEST_M2);
        return $result;
    }

    /**
     * @return Context\ObjectManager
     */
    public function getObjectManager() {
        if(is_null($this->_objectManager)) {
            if($this->isMage2()) {
                /** \Magento\Framework\ObjectManager\ObjectManager */
                $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            } else {
                $this->_objectManager = new Context\ObjectManager();
                $resource = \Mage::getModel('core/resource');
                $dbAdapter = new DbAdapter($resource);
                $this->_objectManager->addSharedInstance($dbAdapter, 'Flancer32\Core\Lib\Context\IDbAdapter');
            }
        }
        return $this->_objectManager;
    }

    public static function getClassname($m2class) {
        $result = $m2class;
        if(!self::instance()->isMage2()) {
            $m1class = Context\ClassMap::getM1Class($m2class);
            if(!is_null($m1class)) {
                $result = $m1class;
            }
        }
        return $result;
    }
}