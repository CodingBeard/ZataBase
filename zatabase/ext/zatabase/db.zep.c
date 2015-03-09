
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
#include "kernel/string.h"
#include "kernel/fcall.h"
#include "kernel/object.h"
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

	zval *_8 = NULL, *_13 = NULL;
	int ZEPHIR_LAST_CALL_STATUS;
	zval *config_param = NULL, *di, *conf, *_0, *_1 = NULL, *_2 = NULL, *_3, *_4 = NULL, *_5 = NULL, *_6 = NULL, *_7 = NULL, *_9, *_10 = NULL, *_11, *_12, *_14, *_15, *_16;
	zval *config = NULL, *_17;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &config_param);

	config = config_param;



	ZEPHIR_INIT_VAR(conf);
	ZEPHIR_INIT_VAR(_0);
	zephir_json_encode(_0, &(_0), config, 0  TSRMLS_CC);
	zephir_json_decode(conf, &(conf), _0, 0  TSRMLS_CC);
	ZEPHIR_INIT_VAR(di);
	object_init_ex(di, zatabase_di_ce);
	ZEPHIR_CALL_METHOD(NULL, di, "__construct", NULL);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_1);
	ZVAL_STRING(_1, "config", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_2);
	ZVAL_BOOL(_2, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _1, conf, _2);
	zephir_check_temp_parameter(_1);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_1);
	object_init_ex(_1, zatabase_storage_adapter_file_ce);
	ZEPHIR_OBS_VAR(_3);
	zephir_read_property(&_3, conf, SL("databaseDir"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, _1, "__construct", NULL, _3);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_2);
	ZVAL_STRING(_2, "storage", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_4);
	ZVAL_BOOL(_4, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _2, _1, _4);
	zephir_check_temp_parameter(_2);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_2);
	object_init_ex(_2, zatabase_execute_ce);
	if (zephir_has_constructor(_2 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _2, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_4);
	ZVAL_STRING(_4, "execute", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_5);
	ZVAL_BOOL(_5, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _4, _2, _5);
	zephir_check_temp_parameter(_4);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_4);
	object_init_ex(_4, zatabase_schema_ce);
	if (zephir_has_constructor(_4 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _4, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_5);
	ZVAL_STRING(_5, "schema", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_6);
	ZVAL_BOOL(_6, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _5, _4, _6);
	zephir_check_temp_parameter(_5);
	zephir_check_call_status();
	ZEPHIR_INIT_NVAR(_5);
	object_init_ex(_5, zatabase_traverser_ce);
	if (zephir_has_constructor(_5 TSRMLS_CC)) {
		ZEPHIR_CALL_METHOD(NULL, _5, "__construct", NULL);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_6);
	ZVAL_STRING(_6, "traverser", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_INIT_VAR(_7);
	ZVAL_BOOL(_7, 1);
	ZEPHIR_CALL_METHOD(NULL, di, "set", NULL, _6, _5, _7);
	zephir_check_temp_parameter(_6);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(NULL, this_ptr, "setdi", NULL, di);
	zephir_check_call_status();
	ZEPHIR_INIT_VAR(_8);
	ZEPHIR_INIT_NVAR(_8);
	ZVAL_STRING(_8, "storage", 1);
	ZEPHIR_OBS_VAR(_9);
	zephir_read_property_zval(&_9, this_ptr, _8, PH_NOISY_CC);
	ZEPHIR_OBS_VAR(_11);
	zephir_read_property(&_11, conf, SL("schema"), PH_NOISY_CC);
	ZEPHIR_OBS_VAR(_12);
	zephir_read_property(&_12, _11, SL("definitionFile"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(&_10, _9, "isfile", NULL, _12);
	zephir_check_call_status();
	if (!(zephir_is_true(_10))) {
		ZEPHIR_INIT_VAR(_13);
		ZEPHIR_INIT_NVAR(_13);
		ZVAL_STRING(_13, "storage", 1);
		ZEPHIR_OBS_VAR(_14);
		zephir_read_property_zval(&_14, this_ptr, _13, PH_NOISY_CC);
		ZEPHIR_OBS_VAR(_15);
		zephir_read_property(&_15, conf, SL("schema"), PH_NOISY_CC);
		ZEPHIR_OBS_VAR(_16);
		zephir_read_property(&_16, _15, SL("definitionFile"), PH_NOISY_CC);
		ZEPHIR_INIT_NVAR(_6);
		ZEPHIR_INIT_VAR(_17);
		array_init_size(_17, 6);
		ZEPHIR_INIT_NVAR(_7);
		ZVAL_STRING(_7, "name", 1);
		zephir_array_fast_append(_17, _7);
		ZEPHIR_INIT_NVAR(_7);
		ZVAL_STRING(_7, "columns", 1);
		zephir_array_fast_append(_17, _7);
		ZEPHIR_INIT_NVAR(_7);
		ZVAL_STRING(_7, "increment", 1);
		zephir_array_fast_append(_17, _7);
		ZEPHIR_INIT_NVAR(_7);
		ZVAL_STRING(_7, "relationships", 1);
		zephir_array_fast_append(_17, _7);
		zephir_json_encode(_6, &(_6), _17, 0  TSRMLS_CC);
		ZEPHIR_CALL_METHOD(NULL, _14, "setfile", NULL, _16, _6);
		zephir_check_call_status();
	}
	ZEPHIR_MM_RESTORE();

}

