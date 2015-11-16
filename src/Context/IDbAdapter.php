<?php
/**
 * Interface for database adapter implementation.
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Context;


interface IDbAdapter {
    /**
     * Get default database connector (core_write).
     *
     * @return \Magento_Db_Adapter_Pdo_Mysql|\Magento\Framework\DB\Adapter\Pdo\Mysql
     */
    public function getDefaultConnection();

    /**
     * @return  \Mage_Core_Model_Resource|\Magento\Framework\App\Resource
     */
    public function getResource();
}
