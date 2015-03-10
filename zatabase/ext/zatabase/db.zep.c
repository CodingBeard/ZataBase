
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
#include "kernel/array.h"
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

	zephir_fcall_cache_entry *_13 = NULL;
	zval *_9;
	int ZEPHIR_LAST_CALL_STATUS;
	zval *config, *di, *storage, *_0, *_1, *_2, *_3, *_4 = NULL, *_5, *_6 = NULL, *_7 = NULL, *_8, *_10 = NULL, *_11 = NULL, *_12 = NULL;

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
	ZEPHIR_OBS_VAR(_2);
	zephir_read_property(&_2, config, SL("definitionName"), PH_NOISY_CC);
	ZEPHIR_INIT_VAR(_3);
	ZEPHIR_CONCAT_VV(_3, _1, _2);
	zephir_update_property_zval(config, SL("definitionFile"), _3 TSRMLS_CC);
	ZEPHIR_OBS_VAR(_5);
	zephir_read_property(&_5, config, SL("definitionFile"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(&_4, storage, "isfile", NULL, _5);
	zephir_check_call_status();
	if (!(zephir_is_true(_4))) {
		ZEPHIR_OBS_VAR(_6);
		zephir_read_property(&_6, config, SL("definitionFile"), PH_NOISY_CC);
		ZEPHIR_INIT_VAR(_7);
		object_init_ex(_7, zatabase_table_ce);
		ZEPHIR_OBS_VAR(_8);
		zephir_read_property(&_8, config, SL("definitionName"), PH_NOISY_CC);
		ZEPHIR_INIT_VAR(_9);
		array_init_size(_9, 6);
		ZEPHIR_INIT_VAR(_10);
		object_init_ex(_10, zatabase_table_column_ce);
		ZEPHIR_INIT_VAR(_11);
		ZVAL_STRING(_11, "name", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_INIT_VAR(_12);
		ZVAL_LONG(_12, 2);
		ZEPHIR_CALL_METHOD(NULL, _10, "__construct", &_13, _11, _12);
		zephir_check_temp_parameter(_11);
		zephir_check_call_status();
		zephir_array_fast_append(_9, _10);
		ZEPHIR_INIT_NVAR(_10);
		object_init_ex(_10, zatabase_table_column_ce);
		ZEPHIR_INIT_NVAR(_11);
		ZVAL_STRING(_11, "columns", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_INIT_NVAR(_12);
		ZVAL_LONG(_12, 5);
		ZEPHIR_CALL_METHOD(NULL, _10, "__construct", &_13, _11, _12);
		zephir_check_temp_parameter(_11);
		zephir_check_call_status();
		zephir_array_fast_append(_9, _10);
		ZEPHIR_INIT_NVAR(_10);
		object_init_ex(_10, zatabase_table_column_ce);
		ZEPHIR_INIT_NVAR(_11);
		ZVAL_STRING(_11, "increment", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_INIT_NVAR(_12);
		ZVAL_LONG(_12, 1);
		ZEPHIR_CALL_METHOD(NULL, _10, "__construct", &_13, _11, _12);
		zephir_check_temp_parameter(_11);
		zephir_check_call_status();
		zephir_array_fast_append(_9, _10);
		ZEPHIR_INIT_NVAR(_10);
		object_init_ex(_10, zatabase_table_column_ce);
		ZEPHIR_INIT_NVAR(_11);
		ZVAL_STRING(_11, "relationships", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_INIT_NVAR(_12);
		ZVAL_LONG(_12, 5);
		ZEPHIR_CALL_METHOD(NULL, _10, "__construct", &_13, _11, _12);
		zephir_check_temp_parameter(_11);
		zephir_check_call_status();
		zephir_array_fast_append(_9, _10);
		ZEPHIR_CALL_METHOD(NULL, _7, "__construct", NULL, _8, _9);
		zephir_check_call_status();
		ZEPHIR_CALL_METHOD(NULL, storage, "setfile", NULL, _6, _7);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_VAR(di);
	object_init_ex(di, zatabase_di_ce);
	ZEPHIR_CALL_METHOD(NULL, di, "__construct", NULL);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_7);
	ZVAL_STRING(_7, "config", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_10);
	ZVAL_BOOL(_10, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _7, config, _10);
	zephir_check_temp_parameter(_7);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_7);
	ZVAL_STRING(_7, "storage", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_10);
	ZVAL_BOOL(_10, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _7, storage, _10);
	zephir_check_temp_parameter(_7);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_7);
	object_init_ex(_7, zatabase_execute_ce);
	if (zephir_has_constructor(_7 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _7, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_10);
	ZVAL_STRING(_10, "execute", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_11);
	ZVAL_BOOL(_11, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _10, _7, _11);
	zephir_check_temp_parameter(_10);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_10);
	object_init_ex(_10, zatabase_schema_ce);
	ZEPHIR_OBS_NVAR(_6);
	zephir_read_property(&_6, config, SL("definitionFile"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, _10, "__construct", NULL, _6);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_11);
	ZVAL_STRING(_11, "schema", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_NVAR(_12);
	ZVAL_BOOL(_12, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _11, _10, _12);
	zephir_check_temp_parameter(_11);
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

