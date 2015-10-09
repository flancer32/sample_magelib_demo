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
    public function operation($request) {
        $result = $request + 2;
        return $result;
    }

    public function dbInsert($request) {
        $ctx = Context::get();
        /** @var  $conn \Zend_Db_Adapter_Pdo_Mysql */
        $conn = $ctx->getConnection();
        $tbl = $ctx->getTableName(BonuseType::NAME);
        $conn->insert($tbl, $request);
        $result = $conn->lastInsertId($tbl);
        return $result;
    }

    public function dbUpdate($id, $bind) {
        $ctx = Context::get();
        /** @var  $conn \Zend_Db_Adapter_Pdo_Mysql */
        $conn = $ctx->getConnection();
        $tbl = $ctx->getTableName(BonuseType::NAME);
        /* filter by primary key */
        $where = $conn->quoteInto(BonuseType::ATTR_ID . '=?', $id);
        $result = $conn->update($tbl, $bind, $where);
        return $result;
    }

    public function dbSelect($byId) {
        $result = null;
        $ctx = Context::get();
        /** @var  $conn \Zend_Db_Adapter_Pdo_Mysql */
        $conn = $ctx->getConnection();
        $tbl = $ctx->getTableName(BonuseType::NAME);
        $query = $conn->select();
        $cols = '*';
        $query->from($tbl, $cols);
        $query->where(BonuseType::ATTR_ID . '=:id');
        $sql = (string)$query;
        $data = $conn->fetchRow($query, array( 'id' => $byId ));
        if($data) {
            $result = $data;
        }
        return $result;
    }

    public function dbDelete($byId) {
        $ctx = Context::get();
        /** @var  $conn \Zend_Db_Adapter_Pdo_Mysql */
        $conn = $ctx->getConnection();
        $tbl = $ctx->getTableName(BonuseType::NAME);
        /* filter by primary key */
        $where = $conn->quoteInto(BonuseType::ATTR_ID . '=?', $byId);
        $result = $conn->delete($tbl, $where);
        return $result;
    }
}