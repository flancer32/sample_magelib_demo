<?php
/**
 * Copyright (c) 2015, Praxigento
 * All rights reserved.
 */

/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib;

/**
 * Library's context
 * Class Context
 * @package Flancer32\Lib
 */
class Context {
    const TYPE_MAGE_1 = 'M1';
    const TYPE_MAGE_2 = 'M2';
    const TYPE_TEST = 'TEST_UNIT';
    private $_contextType = self::TYPE_TEST;
    /**
     * @var \Zend_Db_Adapter_Pdo_Mysql
     */
    private $_connection;
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
        if(class_exists('Mage')) {
            $this->_contextType = self::TYPE_MAGE_1;
        } elseif(false) {
            /* TODO: change condition for M2 context */
            $this->_contextType = self::TYPE_MAGE_2;
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
    public function getConnection() {
        if(!$this->_connection instanceof \Zend_Db_Adapter_Abstract) {
            if($this->_contextType == self::TYPE_MAGE_1) {
                $this->_connection = \Mage::getSingleton('core/resource')->getConnection('core_write');
            } else {
                throw new \Exception('Database connection is not set in the context. You should setup connection manually in PHPUnit tests.');
            }
        }
        return $this->_connection;
    }

    /**
     * Compose table name for entity name.
     *
     * @param $entityName
     *
     * @return string
     */
    public function getTableName($entityName) {
        $result = 'm1_' . $entityName;
        return $result;
    }
}