
extern zend_class_entry *zatabase_db_ce;

ZEPHIR_INIT_CLASS(ZataBase_Db);

PHP_METHOD(ZataBase_Db, __construct);
PHP_METHOD(ZataBase_Db, createTable);
PHP_METHOD(ZataBase_Db, deleteTable);
PHP_METHOD(ZataBase_Db, alterTable);
PHP_METHOD(ZataBase_Db, insert);
PHP_METHOD(ZataBase_Db, select);
PHP_METHOD(ZataBase_Db, delete);
PHP_METHOD(ZataBase_Db, update);

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, config, ZataBase\\Helper\\ArrayToObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_createtable, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, table, ZataBase\\Table, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_deletetable, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_altertable, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_insert, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_select, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_delete, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db_update, 0, 0, 1)
	ZEND_ARG_INFO(0, tableName)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(zatabase_db_method_entry) {
	PHP_ME(ZataBase_Db, __construct, arginfo_zatabase_db___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(ZataBase_Db, createTable, arginfo_zatabase_db_createtable, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, deleteTable, arginfo_zatabase_db_deletetable, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, alterTable, arginfo_zatabase_db_altertable, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, insert, arginfo_zatabase_db_insert, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, select, arginfo_zatabase_db_select, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, delete, arginfo_zatabase_db_delete, ZEND_ACC_PUBLIC)
	PHP_ME(ZataBase_Db, update, arginfo_zatabase_db_update, ZEND_ACC_PUBLIC)
  PHP_FE_END
};
