# Execution context

Library can be used in 3 context:

* Magento 1
* Magento 2
* PHPUnits

There is [context](../src/Context.php) 
class that provides access to the application ObjectManager in runtime:
 
    /** @var  $conn Zend_Db_Adapter_Pdo_Mysql */
    $conn = Context::get()->getConnection();
    $conn->beginTransaction();
    ...
    $conn->rollBack(); 


Context is a singleton with a methods to get/set application resources:


    class Context {
        ...
        private $_connection;
        private static $_instance;
        ...
        public static function  get() {
            if(is_null(self::$_instance)) {
                self::$_instance = new Context();
            }
            return self::$_instance;
        }
        public function setConnection(\Zend_Db_Adapter_Pdo_Mysql $val) {}
        public function getConnection() {}
        ...
    }