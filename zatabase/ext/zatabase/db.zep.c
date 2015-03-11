
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
#include "kernel/concat.h"
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
	zval *config, *di, *storage, *_0, *_1, *_2, *_3 = NULL, *_4, *_5 = NULL, *_6 = NULL, *_7 = NULL, *_8 = NULL, *_9;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &config);

	ZEPHIR_SEPARATE_PARAM(config);


	ZEPHIR_INIT_VAR(storage);
	object_init_ex(storage, zatabase_storage_adapter_file_ce);
	ZEPHIR_OBS_VAR(_0);
	zephir_read_property(&_0, config, SL("databaseDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, storage, "__construct", NULL, _0);
	zephir_check_call_status();
	ZEPHIR_OBS_VAR(_1);
	zephir_read_property(&_1, config, SL("tablesDir"), PH_NOISY_CC);
	ZEPHIR_INIT_VAR(_2);
	ZEPHIR_CONCAT_VS(_2, _1, "Schema");
	zephir_update_property_zval(config, SL("definitionFile"), _2 TSRMLS_CC);
	ZEPHIR_OBS_VAR(_4);
	zephir_read_property(&_4, config, SL("tablesDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(&_3, storage, "isdir", NULL, _4);
	zephir_check_call_status();
	if (!(zephir_is_true(_3))) {
		ZEPHIR_OBS_VAR(_5);
		zephir_read_property(&_5, config, SL("tablesDir"), PH_NOISY_CC);
		ZEPHIR_CALL_METHOD(NULL, storage, "adddir", NULL, _5);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_VAR(di);
	object_init_ex(di, zatabase_di_ce);
	ZEPHIR_CALL_METHOD(NULL, di, "__construct", NULL);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_6);
	ZVAL_STRING(_6, "config", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_7);
	ZVAL_BOOL(_7, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _6, config, _7);
	zephir_check_temp_parameter(_6);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_6);
	ZVAL_STRING(_6, "storage", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_7);
	ZVAL_BOOL(_7, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _6, storage, _7);
	zephir_check_temp_parameter(_6);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_6);
	object_init_ex(_6, zatabase_execute_ce);
	if (zephir_has_constructor(_6 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _6, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_7);
	ZVAL_STRING(_7, "execute", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_8);
	ZVAL_BOOL(_8, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _7, _6, _8);
	zephir_check_temp_parameter(_7);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_7);
	object_init_ex(_7, zatabase_schema_ce);
	ZEPHIR_OBS_NVAR(_5);
	zephir_read_property(&_5, config, SL("definitionFile"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, _7, "__construct", NULL, _5);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_8);
	ZVAL_STRING(_8, "schema", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_9);
	ZVAL_BOOL(_9, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _8, _7, _9);
	zephir_check_temp_parameter(_8);
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

