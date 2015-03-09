
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
#include "kernel/array.h"
#include "kernel/object.h"
#include "kernel/operators.h"
#include "kernel/string.h"
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

	/**
	 * Location of the auth file
	 * @var string
	 */
	zend_declare_property_string(zatabase_db_ce, SL("authFile"), ".auth", ZEND_ACC_PROTECTED TSRMLS_CC);

	return SUCCESS;

}

/**
 * Constructor
 * @param array parameters
 */
PHP_METHOD(ZataBase_Db, __construct) {

	zval *_6 = NULL, *_9 = NULL;
	int ZEPHIR_LAST_CALL_STATUS;
	zval *parameters_param = NULL, *di, *_0, *_1, *_2 = NULL, *_3 = NULL, *_4 = NULL, *_5 = NULL, *_7, *_8 = NULL, *_10;
	zval *parameters = NULL, *_11;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &parameters_param);

	parameters = parameters_param;



	ZEPHIR_INIT_VAR(di);
	object_init_ex(di, zatabase_di_ce);
	ZEPHIR_CALL_METHOD(NULL, di, "__construct", NULL);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_0);
	object_init_ex(_0, zatabase_storage_adapter_file_ce);
	zephir_array_fetch_long(&_1, parameters, 0, PH_NOISY | PH_READONLY, "zatabase/db.zep", 37 TSRMLS_CC);
	ZEPHIR_CALL_METHOD(NULL, _0, "__construct", NULL, _1);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_2);
	ZVAL_STRING(_2, "storage", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_3);
	ZVAL_BOOL(_3, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _2, _0, _3);
	zephir_check_temp_parameter(_2);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_2);
	object_init_ex(_2, zatabase_schema_ce);
	if (zephir_has_constructor(_2 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _2, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_3);
	ZVAL_STRING(_3, "schema", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_4);
	ZVAL_BOOL(_4, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _3, _2, _4);
	zephir_check_temp_parameter(_3);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_3);
	object_init_ex(_3, zatabase_traverser_ce);
	if (zephir_has_constructor(_3 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _3, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_4);
	ZVAL_STRING(_4, "traverser", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_5);
	ZVAL_BOOL(_5, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _4, _3, _5);
	zephir_check_temp_parameter(_4);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(NULL, this_ptr, "setdi", NULL, di);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_6);
	ZEPHIR_INIT_NVAR(_6);
	ZVAL_STRING(_6, "storage", 1);
	ZEPHIR_OBS_VAR(_7);
	zephir_read_property_zval(&_7, this_ptr, _6, PH_NOISY_CC);
	ZEPHIR_INIT_NVAR(_4);
	ZVAL_STRING(_4, "tables", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_CALL_METHOD(&_8, _7, "isfile", NULL, _4);
	zephir_check_temp_parameter(_4);
	zephir_check_call_status();
	if (!(zephir_is_true(_8))) {
		ZEPHIR_INIT_VAR(_9);
		ZEPHIR_INIT_NVAR(_9);
		ZVAL_STRING(_9, "storage", 1);
		ZEPHIR_OBS_VAR(_10);
		zephir_read_property_zval(&_10, this_ptr, _9, PH_NOISY_CC);
		ZEPHIR_INIT_NVAR(_4);
		ZEPHIR_INIT_VAR(_11);
		array_init_size(_11, 6);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "name", 1);
		zephir_array_fast_append(_11, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "columns", 1);
		zephir_array_fast_append(_11, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "increment", 1);
		zephir_array_fast_append(_11, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "relationships", 1);
		zephir_array_fast_append(_11, _5);
		zephir_json_encode(_4, &(_4), _11, 0  TSRMLS_CC);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "tables", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_CALL_METHOD(NULL, _10, "setfile", NULL, _5, _4);
		zephir_check_temp_parameter(_5);
		zephir_check_call_status();
	}
	ZEPHIR_MM_RESTORE();

}

