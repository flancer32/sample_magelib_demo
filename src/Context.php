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
    const TYPE_MAGE_2 = 'M2';
    const TYPE_TEST = 'TEST_UNIT';
    /**
     * Flag for current execution context type.
     * @var string
     */
    private $_contextType = self::TYPE_TEST;
    /**
     * Saved database connection.
     * @var \Zend_Db_Adapter_Pdo_Mysql
     */
    private $_connection;
    /**
     * Schema setup for M2 to get table names in CLI mode (./bin/magento as Symfony CLI app)
     * @var \Magento\Framework\Setup\SchemaSetupInterface
     */
    private $_setupSchema;
    /** @var \Magento\Framework\ObjectManagerInterface */
    private $_m2ObjectManager;
    /** @var  \Magento\Framework\App\Resource */
    private $_m2Resource;
    /**
     * Resource setup for M1 to get table names.
     * @var \Mage_Core_Model_Resource_Setup
     */
    private $_setupResource;
    /**
     * Itself. Singleton.
     *
     * @var Context
     */
    private static $_instance;

    /**
     * Context constructor.
     *
     * @param \Zend_Db_Adapter_Pdo_Mysql $_connection
     */
    public function __construct() {
        /* analyze current runtime environment Magento 1 | Magento 2 | TestUnits (default) */
        if(class_exists('\Mage', false)) {
            $this->_contextType = self::TYPE_MAGE_1;
            $resource = \Mage::getModel('core/resource_setup');
            $this->setSetupResource($resource);
        } elseif(
            isset($GLOBALS['bootstrap']) &&
            ($GLOBALS['bootstrap'] instanceof \Magento\Framework\App\Bootstrap)
        ) {
            $this->_contextType = self::TYPE_MAGE_2;
            /** @var  $bootstrap \Magento\Framework\App\Bootstrap */
            $bootstrap = $this->getBootstrap();
            $this->_m2ObjectManager = $bootstrap->getObjectManager();
            $this->_m2Resource = $this->_m2ObjectManager->get('\Magento\Framework\App\Resource');
        }
    }

    /**
     * Get singleton instance.
     *
     * @return Context
     */
    public static function  get() {
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

    public function isMage2() {
        $result = ($this->_contextType == self::TYPE_MAGE_2);
        return $result;
    }

    /**
     * Setup database connection.
     *
     * @param \Zend_Db_Adapter_Pdo_Mysql $val
     */
    public function setConnection(\Zend_Db_Adapter_Pdo_Mysql $val) {
        $this->_connection = $val;
    }

    /**
     * Connection to database.
     * @return \Zend_Db_Adapter_Pdo_Mysql
     */
    public function getConnection($type = 'core_write') {
        if(!$this->_connection instanceof \Zend_Db_Adapter_Abstract) {
            if($this->_contextType == self::TYPE_MAGE_1) {
                $this->_connection = \Mage::getSingleton('core/resource')->getConnection($type);
            } elseif($this->_contextType == self::TYPE_MAGE_2) {
                $this->_connection = $this->_m2Resource->getConnection($type);
            } else {
                throw new \Exception('Database connection is not set in the context. You should setup connection manually in PHPUnit tests.');
            }
        }
        return $this->_connection;
    }

    /**
     * @return \Magento\Framework\App\Bootstrap
     */
    private function getBootstrap() {
        $result = $GLOBALS['bootstrap'];
        return $result;
    }

    public function getObjectManager() {
        $result = null;
        if($this->_contextType == self::TYPE_MAGE_2) {
            $result = $this->getBootstrap()->getObjectManager();
        } else {
        }
        return $result;
    }

    /**
     * Compose table name for entity name.
     *
     * @param $entityName
     *
     * @return string
     */
    public function getTableName($entityName) {
        if($this->_setupResource) {
            /* M1 setup */
            $result = $this->_setupResource->getTable($entityName);
        } elseif($this->_setupSchema) {
            /* M2 setup/CLI*/
            $result = $this->_setupSchema->getTable($entityName);
        } elseif($this->_m2Resource) {
            /* M2 tests/app */
            $result = $this->_m2Resource->getTableName($entityName);
        } else {
            $result = $entityName;
        }
        return $result;
    }

    /**
     * Set schema setup object in Symfony CLI app.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $val
     */
    public function setSetupSchema(\Magento\Framework\Setup\SchemaSetupInterface $val) {
        $this->_setupSchema = $val;
        $this->_connection = $val->getConnection();
    }

    /**
     * Set resource setup object in M1 app.
     *
     * @param \Mage_Core_Model_Resource_Setup $val
     */
    public function setSetupResource(\Mage_Core_Model_Resource_Setup $val) {
        $this->_setupResource = $val;
        $this->_connection = $val->getConnection();
    }
}