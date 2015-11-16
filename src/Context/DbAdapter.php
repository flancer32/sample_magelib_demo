<?php
/**
 * Wrapper for version specific implementation of the common interface:
 *  - Mage_Core_Model_Resource
 *  - Magento\Framework\App\Resource
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Context;


use Flancer32\Lib\Config;
use Flancer32\Lib\Context;

class DbAdapter implements IDbAdapter {

    /** @var  \Mage_Core_Model_Resource|\Magento\Framework\App\Resource */
    private $_resource;
    /** @var  \Magento_Db_Adapter_Pdo_Mysql|\Magento\Framework\DB\Adapter\Pdo\Mysql */
    private $_defaulConnection;

    /**
     * Resource constructor.
     *
     * Don't set type for $m1Resource, it crashes the M2 ObjectManager (there is no \Mage_Core_Model_Resource)
     *
     * @param \Mage_Core_Model_Resource            $m1Resource
     * @param \Magento\Framework\App\Resource|null $m2Resource
     */
    public function __construct(
        $m1Resource = null,
        \Magento\Framework\App\Resource $m2Resource = null
    ) {
        if(!is_null($m1Resource)) {
            $this->_resource = $m1Resource;
            $this->_defaulConnection = $this->_resource->getConnection(Config::DEFAULT_WRITE_RESOURCE);
        } else {
            if(!is_null($m2Resource)) {
                /** @var  _resource \Magento\Framework\App\Resource */
                $this->_resource = $m2Resource;
                $this->_defaulConnection = $this->_resource->getConnection(Config::DEFAULT_WRITE_RESOURCE);
            }
        }
    }

    /**
     * Get default database connector (core_write).
     *
     * @return \Magento_Db_Adapter_Pdo_Mysql|\Magento\Framework\DB\Adapter\Pdo\Mysql
     */
    public function getDefaultConnection() {
        return $this->_defaulConnection;
    }

    /**
     * @return  \Mage_Core_Model_Resource|\Magento\Framework\App\Resource
     */
    public function getResource() {
        return $this->_resource;
    }


}