
extern zend_class_entry *zatabase_db_ce;

ZEPHIR_INIT_CLASS(ZataBase_Db);

PHP_METHOD(ZataBase_Db, setStorage);
PHP_METHOD(ZataBase_Db, getStorage);
PHP_METHOD(ZataBase_Db, setTraverser);
PHP_METHOD(ZataBase_Db, getTraverser);
PHP_METHOD(ZataBase_Db, __construct);
PHP_METHOD(ZataBase_Db, createTable);
PHP_METHOD(ZataBase_Db, getTable);

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_setstorage, 0, 0, 1)
	ZEND_ARG_INFO(0, storage)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_settraverser, 0, 0, 1)
	ZEND_ARG_INFO(0, traverser)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, adapter, ZataBase\\Storage\\Adapter\\File, 0)
	ZEND_ARG_ARRAY_INFO(0, parameters, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_createtable, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, table, ZataBase\\Table, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_gettable, 0, 0, 1)
	ZEND_ARG_INFO(0, name)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(zatabase_db_method_entry) {
	PHP_ME(ZataBase_Db, setStorage, arginfo_zatabase_db_setstorage, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, getStorage, NULL, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, setTraverser, arginfo_zatabase_db_settraverser, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, getTraverser, NULL, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, __construct, arginfo_zatabase_db___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(ZataBase_Db, createTable, arginfo_zatabase_db_createtable, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, getTable, arginfo_zatabase_db_gettable, ZEND_ACC_PUBLIC)
  PHP_FE_END
};
