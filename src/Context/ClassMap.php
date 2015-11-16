<?php
/**
 * Map M2 classes to itself in M2 context or to M1 classes in M1 context.
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Context;


class ClassMap {
    private static $_cache = [ ];

    private static $_tmpMap = [
        'Magento\Framework\Stdlib\DateTime\DateTime' => 'Mage_Core_Model_Date',
        'Magento\Framework\App\Resource'             => 'Mage_Core_Model_Resource',
        'Magento\Framework\DB\Adapter\Pdo\Mysql'     => 'Magento_Db_Adapter_Pdo_Mysql'
    ];

    public static function getM1Class($m2Class) {
        $result = trim($m2Class);
        if(isset(self::$_cache[$result])) {
            /* use cached M1 class name */
            $result = self::$_cache[$result];
        } elseif(isset(self::$_tmpMap[$result])) {
            /* cache resolved M1 class name (or unresolved M2 itself ) */
            $result = self::$_cache[$result] = self::$_tmpMap[$result];
        }
        return $result;
    }
}