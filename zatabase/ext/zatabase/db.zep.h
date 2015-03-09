
extern zend_class_entry *zatabase_db_ce;

ZEPHIR_INIT_CLASS(ZataBase_Db);

PHP_METHOD(ZataBase_Db, __construct);

ZEND_BEGIN_ARG_INFO_EX(arginfo_zatabase_db___construct, 0, 0, 1)
	ZEND_ARG_ARRAY_INFO(0, parameters, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(zatabase_db_method_entry) {
	PHP_ME(ZataBase_Db, __construct, arginfo_zatabase_db___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
  PHP_FE_END
};
