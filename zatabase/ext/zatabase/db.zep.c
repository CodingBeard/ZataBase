
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
#include "kernel/object.h"
#include "kernel/memory.h"
#include "kernel/exception.h"
#include "kernel/fcall.h"
#include "kernel/operators.h"
#include "kernel/string.h"
#include "kernel/array.h"
#include "ext/spl/spl_exceptions.h"
#include "kernel/concat.h"


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
* $db = new ZataBase\db(new Zatabase\Storage\File(__DIR__ . '/database'), []);
*/
ZEPHIR_INIT_CLASS(ZataBase_Db) {

	ZEPHIR_REGISTER_CLASS(ZataBase, Db, zatabase, db, zatabase_db_method_entry, 0);

	/**
	 * Storage adapter
	 * @var Storage\StorageInterface
	 */
	zend_declare_property_null(zatabase_db_ce, SL("storage"), ZEND_ACC_PROTECTED TSRMLS_CC);

	/**
	 * Traverser
	 * @var Traverser
	 */
	zend_declare_property_null(zatabase_db_ce, SL("traverser"), ZEND_ACC_PROTECTED TSRMLS_CC);

	/**
	 * Location of the auth file
	 * @var string
	 */
	zend_declare_property_string(zatabase_db_ce, SL("authFile"), ".auth", ZEND_ACC_PROTECTED TSRMLS_CC);

	return SUCCESS;

}

/**
 * Storage adapter
 * @var Storage\StorageInterface
 */
PHP_METHOD(ZataBase_Db, setStorage) {

	zval *storage;

	zephir_fetch_params(0, 1, 0, &storage);



	zephir_update_property_this(this_ptr, SL("storage"), storage TSRMLS_CC);

}

/**
 * Storage adapter
 * @var Storage\StorageInterface
 */
PHP_METHOD(ZataBase_Db, getStorage) {


	RETURN_MEMBER(this_ptr, "storage");

}

/**
 * Traverser
 * @var Traverser
 */
PHP_METHOD(ZataBase_Db, setTraverser) {

	zval *traverser;

	zephir_fetch_params(0, 1, 0, &traverser);



	zephir_update_property_this(this_ptr, SL("traverser"), traverser TSRMLS_CC);

}

/**
 * Traverser
 * @var Traverser
 */
PHP_METHOD(ZataBase_Db, getTraverser) {


	RETURN_MEMBER(this_ptr, "traverser");

}

/**
 * Constructor
 * @param Storage\File adapter
 * @param array parameters
 */
PHP_METHOD(ZataBase_Db, __construct) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *parameters = NULL, *_4;
	zval *adapter, *parameters_param = NULL, *_0, *_1 = NULL, *_2 = NULL, *_3, *_5 = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 2, 0, &adapter, &parameters_param);

	parameters = parameters_param;



	if (!(zephir_instance_of_ev(adapter, zatabase_storage_adapter_file_ce TSRMLS_CC))) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(spl_ce_InvalidArgumentException, "Parameter 'adapter' must be an instance of 'ZataBase\\Storage\\Adapter\\File'", "", 0);
		return;
	}
	zephir_update_property_this(this_ptr, SL("storage"), adapter TSRMLS_CC);
	_0 = zephir_fetch_nproperty_this(this_ptr, SL("storage"), PH_NOISY_CC);
	ZEPHIR_INIT_VAR(_2);
	ZVAL_STRING(_2, "tables", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_CALL_METHOD(&_1, _0, "isfile", NULL, _2);
	zephir_check_temp_parameter(_2);
	zephir_check_call_status();
	if (!(zephir_is_true(_1))) {
		_3 = zephir_fetch_nproperty_this(this_ptr, SL("storage"), PH_NOISY_CC);
		ZEPHIR_INIT_NVAR(_2);
		ZEPHIR_INIT_VAR(_4);
		array_init_size(_4, 6);
		ZEPHIR_INIT_VAR(_5);
		ZVAL_STRING(_5, "name", 1);
		zephir_array_fast_append(_4, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "columns", 1);
		zephir_array_fast_append(_4, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "increment", 1);
		zephir_array_fast_append(_4, _5);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "relationships", 1);
		zephir_array_fast_append(_4, _5);
		zephir_json_encode(_2, &(_2), _4, 0  TSRMLS_CC);
		ZEPHIR_INIT_NVAR(_5);
		ZVAL_STRING(_5, "tables", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_CALL_METHOD(NULL, _3, "setfile", NULL, _5, _2);
		zephir_check_temp_parameter(_5);
		zephir_check_call_status();
	}
	ZEPHIR_INIT_NVAR(_5);
	object_init_ex(_5, zatabase_traverser_ce);
	_3 = zephir_fetch_nproperty_this(this_ptr, SL("storage"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(NULL, _5, "__construct", NULL, _3);
	zephir_check_call_status();
	zephir_update_property_this(this_ptr, SL("traverser"), _5 TSRMLS_CC);
	ZEPHIR_MM_RESTORE();

}

/**
 * Create a table
 */
PHP_METHOD(ZataBase_Db, createTable) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *table, *_0 = NULL, *_1, *_2, *_3 = NULL, *_4, *_5;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &table);



	if (!(zephir_instance_of_ev(table, zatabase_table_ce TSRMLS_CC))) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(spl_ce_InvalidArgumentException, "Parameter 'table' must be an instance of 'ZataBase\\Table'", "", 0);
		return;
	}
	ZEPHIR_OBS_VAR(_1);
	zephir_read_property(&_1, table, SL("name"), PH_NOISY_CC);
	ZEPHIR_CALL_METHOD(&_0, this_ptr, "gettable", NULL, _1);
	zephir_check_call_status();
	if (!(zephir_is_true(_0))) {
		_2 = zephir_fetch_nproperty_this(this_ptr, SL("storage"), PH_NOISY_CC);
		ZEPHIR_INIT_VAR(_3);
		ZVAL_STRING(_3, "tables", ZEPHIR_TEMP_PARAM_COPY);
		ZEPHIR_CALL_METHOD(NULL, _2, "appendline", NULL, _3, table);
		zephir_check_temp_parameter(_3);
		zephir_check_call_status();
	} else {
		ZEPHIR_INIT_NVAR(_3);
		object_init_ex(_3, zatabase_exception_ce);
		ZEPHIR_OBS_VAR(_4);
		zephir_read_property(&_4, table, SL("name"), PH_NOISY_CC);
		ZEPHIR_INIT_VAR(_5);
		ZEPHIR_CONCAT_SVS(_5, "Table: '", _4, "' already exists.");
		ZEPHIR_CALL_METHOD(NULL, _3, "__construct", NULL, _5);
		zephir_check_call_status();
		zephir_throw_exception_debug(_3, "zatabase/db.zep", 64 TSRMLS_CC);
		ZEPHIR_MM_RESTORE();
		return;
	}
	ZEPHIR_MM_RESTORE();

}

/**
 * Instance a table from the tables' file
 */
PHP_METHOD(ZataBase_Db, getTable) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *name_param = NULL, *row = NULL, *_0, *_1 = NULL, *_2, *_3, *_4, *_5, *_6;
	zval *name = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &name_param);

	if (unlikely(Z_TYPE_P(name_param) != IS_STRING && Z_TYPE_P(name_param) != IS_NULL)) {
		zephir_throw_exception_string(spl_ce_InvalidArgumentException, SL("Parameter 'name' must be a string") TSRMLS_CC);
		RETURN_MM_NULL();
	}

	if (likely(Z_TYPE_P(name_param) == IS_STRING)) {
		zephir_get_strval(name, name_param);
	} else {
		ZEPHIR_INIT_VAR(name);
		ZVAL_EMPTY_STRING(name);
	}


	_0 = zephir_fetch_nproperty_this(this_ptr, SL("traverser"), PH_NOISY_CC);
	ZEPHIR_INIT_VAR(_1);
	ZVAL_STRING(_1, "tables", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_CALL_METHOD(NULL, _0, "settable", NULL, _1);
	zephir_check_temp_parameter(_1);
	zephir_check_call_status();
	_2 = zephir_fetch_nproperty_this(this_ptr, SL("traverser"), PH_NOISY_CC);
	ZEPHIR_INIT_NVAR(_1);
	ZVAL_STRING(_1, "name", ZEPHIR_TEMP_PARAM_COPY);
	ZEPHIR_CALL_METHOD(&row, _2, "findrow", NULL, _1, name);
	zephir_check_temp_parameter(_1);
	zephir_check_call_status();
	if (zephir_is_true(row)) {
		object_init_ex(return_value, zatabase_table_ce);
		ZEPHIR_OBS_VAR(_3);
		zephir_read_property(&_3, row, SL("name"), PH_NOISY_CC);
		ZEPHIR_OBS_VAR(_4);
		zephir_read_property(&_4, row, SL("columns"), PH_NOISY_CC);
		ZEPHIR_OBS_VAR(_5);
		zephir_read_property(&_5, row, SL("increment"), PH_NOISY_CC);
		ZEPHIR_OBS_VAR(_6);
		zephir_read_property(&_6, row, SL("relationships"), PH_NOISY_CC);
		ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, _3, _4, _5, _6);
		zephir_check_call_status();
		RETURN_MM();
	} else {
		RETURN_MM_BOOL(0);
	}

}

