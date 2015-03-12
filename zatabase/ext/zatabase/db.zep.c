
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_operators.h>
#include <Zend/zend_exceptions.h>
#include <Zend/zend_interfaces.h>

#include "kernel/main.h"
#include "kernel/memory.h"
#include "kernel/fcall.h"
#include "kernel/object.h"
#include "kernel/operators.h"
#include "ext/spl/spl_exceptions.h"
#include "kernel/exception.h"


/*
 * Database
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */
/**
* ZataBase\db
* $db = new ZataBase\db([__DIR__ . '/database']);
*/
ZEPHIR_INIT_CLASS(ZataBase_Db) {

	ZEPHIR_REGISTER_CLASS_EX(ZataBase, Db, zatabase, db, zatabase_di_injectable_ce, zatabase_db_method_entry, 0);

	return SUCCESS;

}

/**
 * Constructor
 */
PHP_METHOD(ZataBase_Db, __construct) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *config, *di, *storage, *_0, *_1 = NULL, *_2, *_3 = NULL, *_4 = NULL, *_5 = NULL, *_6 = NULL, *_7;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &config);



	ZEPHIR_INIT_VAR(storage);
	object_init_ex(storage, zatabase_storage_adapter_file_ce);
	ZEPHIR_OBS_VAR(_0);
	zephir_read_property(&_0, config, SL("databaseDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, storage, "__construct", NULL, _0);
	zephir_check_call_status();
	ZEPHIR_OBS_VAR(_2);
	zephir_read_property(&_2, config, SL("tablesDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(&_1, storage, "isdir", NULL, _2);
	zephir_check_call_status();
	if (!(zephir_is_true(_1))) {
		ZEPHIR_OBS_VAR(_3);
		zephir_read_property(&_3, config, SL("tablesDir"), PH_NOISY_CC);
		ZEPHIR_CALL_METHOD(NULL, storage, "adddir", NULL, _3);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_VAR(di);
	object_init_ex(di, zatabase_di_ce);
	ZEPHIR_CALL_METHOD(NULL, di, "__construct", NULL);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_4);
	ZVAL_STRING(_4, "config", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_5);
	ZVAL_BOOL(_5, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _4, config, _5);
	zephir_check_temp_parameter(_4);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_4);
	ZVAL_STRING(_4, "storage", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_5);
	ZVAL_BOOL(_5, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _4, storage, _5);
	zephir_check_temp_parameter(_4);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_4);
	object_init_ex(_4, zatabase_execute_ce);
	if (zephir_has_constructor(_4 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _4, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_5);
	ZVAL_STRING(_5, "execute", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_6);
	ZVAL_BOOL(_6, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _5, _4, _6);
	zephir_check_temp_parameter(_5);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_5);
	object_init_ex(_5, zatabase_schema_ce);
	ZEPHIR_OBS_NVAR(_3);
	zephir_read_property(&_3, config, SL("tablesDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, _5, "__construct", NULL, _3);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_6);
	ZVAL_STRING(_6, "schema", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_7);
	ZVAL_BOOL(_7, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _6, _5, _7);
	zephir_check_temp_parameter(_6);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(NULL, this_ptr, "setdi", NULL, di);
	zephir_check_call_status();
	ZEPHIR_MM_RESTORE();

}

/**
 * Alias of Execute\Insert
 *
 * @param string tableName
 */
PHP_METHOD(ZataBase_Db, insert) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *tableName_param = NULL, *_1;
	zval *tableName = NULL, *_0 = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &tableName_param);

	if (unlikely(Z_TYPE_P(tableName_param) != IS_STRING && Z_TYPE_P(tableName_param) != IS_NULL)) {
		zephir_throw_exception_string(spl_ce_InvalidArgumentException, SL("Parameter 'tableName' must be a string") TSRMLS_CC);
		RETURN_MM_NULL();
	}

	if (likely(Z_TYPE_P(tableName_param) == IS_STRING)) {
		zephir_get_strval(tableName, tableName_param);
	} else {
		ZEPHIR_INIT_VAR(tableName);
		ZVAL_EMPTY_STRING(tableName);
	}


	ZEPHIR_INIT_VAR(_0);
	ZEPHIR_INIT_NVAR(_0);
	ZVAL_STRING(_0, "execute", 1);
	ZEPHIR_OBS_VAR(_1);
	zephir_read_property_zval(&_1, this_ptr, _0, PH_NOISY_CC);
	ZEPHIR_RETURN_CALL_METHOD(_1, "insert", NULL, tableName);
	zephir_check_call_status();
	RETURN_MM();

}

/**
 * Alias of Execute\Select
 *
 * @param array parameters
 */
PHP_METHOD(ZataBase_Db, select) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *tableName_param = NULL, *_1;
	zval *tableName = NULL, *_0 = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &tableName_param);

	if (unlikely(Z_TYPE_P(tableName_param) != IS_STRING && Z_TYPE_P(tableName_param) != IS_NULL)) {
		zephir_throw_exception_string(spl_ce_InvalidArgumentException, SL("Parameter 'tableName' must be a string") TSRMLS_CC);
		RETURN_MM_NULL();
	}

	if (likely(Z_TYPE_P(tableName_param) == IS_STRING)) {
		zephir_get_strval(tableName, tableName_param);
	} else {
		ZEPHIR_INIT_VAR(tableName);
		ZVAL_EMPTY_STRING(tableName);
	}


	ZEPHIR_INIT_VAR(_0);
	ZEPHIR_INIT_NVAR(_0);
	ZVAL_STRING(_0, "execute", 1);
	ZEPHIR_OBS_VAR(_1);
	zephir_read_property_zval(&_1, this_ptr, _0, PH_NOISY_CC);
	ZEPHIR_RETURN_CALL_METHOD(_1, "select", NULL, tableName);
	zephir_check_call_status();
	RETURN_MM();

}

/**
 * Alias of Execute\Delete
 *
 * @param array parameters
 */
PHP_METHOD(ZataBase_Db, delete) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *tableName_param = NULL, *_1;
	zval *tableName = NULL, *_0 = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &tableName_param);

	if (unlikely(Z_TYPE_P(tableName_param) != IS_STRING && Z_TYPE_P(tableName_param) != IS_NULL)) {
		zephir_throw_exception_string(spl_ce_InvalidArgumentException, SL("Parameter 'tableName' must be a string") TSRMLS_CC);
		RETURN_MM_NULL();
	}

	if (likely(Z_TYPE_P(tableName_param) == IS_STRING)) {
		zephir_get_strval(tableName, tableName_param);
	} else {
		ZEPHIR_INIT_VAR(tableName);
		ZVAL_EMPTY_STRING(tableName);
	}


	ZEPHIR_INIT_VAR(_0);
	ZEPHIR_INIT_NVAR(_0);
	ZVAL_STRING(_0, "execute", 1);
	ZEPHIR_OBS_VAR(_1);
	zephir_read_property_zval(&_1, this_ptr, _0, PH_NOISY_CC);
	ZEPHIR_RETURN_CALL_METHOD(_1, "delete", NULL, tableName);
	zephir_check_call_status();
	RETURN_MM();

}

