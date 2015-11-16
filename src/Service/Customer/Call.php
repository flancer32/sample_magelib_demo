<?php
/**
 * Copyright (c) 2015, Praxigento
 * All rights reserved.
 */
namespace Flancer32\Lib\Service\Customer;

use Flancer32\Lib\Context;
use Flancer32\Lib\Entity\Bonus\Type as BonuseType;

/**
 * User: Alex Gusev <alex@flancer64.com>
 */
class Call {

    /** @var  \Magento_Db_Adapter_Pdo_Mysql|\Magento\Framework\DB\Adapter\Pdo\Mysql */
    protected $_conn;
    /** @var \Mage_Core_Model_Resource|\Magento\Framework\App\Resource */
    protected $_resource;

    /**
     * Call constructor.
     */
    public function __construct(\Flancer32\Lib\Context\IDbAdapter $dba) {
        $this->_conn = $dba->getDefaultConnection();
        $this->_resource = $dba->getResource();
    }

    /**
     * Sample method to call from M1 & M2 modules.
     *
     * @param $request
     *
     * @return mixed
     */
    public function operation($request) {
        $result = $request + 2;
        return $result;
    }

    public function dbInsert($request) {
        $tbl = $this->_resource->getTableName(BonuseType::NAME);
        $this->_conn->insert($tbl, $request);
        $result = $this->_conn->lastInsertId($tbl);
        return $result;
    }

    public function dbUpdate($id, $bind) {
        $tbl = $this->_resource->getTableName(BonuseType::NAME);
        /* filter by primary key */
        $where = $this->_conn->quoteInto(BonuseType::ATTR_ID . '=?', $id);
        $result = $this->_conn->update($tbl, $bind, $where);
        return $result;
    }

    public function dbSelect($byId) {
        $result = null;
        $tbl = $this->_resource->getTableName(BonuseType::NAME);
        $query = $this->_conn->select();
        $cols = '*';
        $query->from($tbl, $cols);
        $query->where(BonuseType::ATTR_ID . '=:id');
        $sql = (string)$query;
        $data = $this->_conn->fetchRow($query, [ 'id' => $byId ]);
        if($data) {
            $result = $data;
        }
        return $result;
    }

    public function dbDelete($byId) {
        $tbl = $this->_resource->getTableName(BonuseType::NAME);
        /* filter by primary key */
        $where = $this->_conn->quoteInto(BonuseType::ATTR_ID . '=?', $byId);
        $result = $this->_conn->delete($tbl, $where);
        return $result;
    }
}