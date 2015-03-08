
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
ZEPHIR_INIT_CLASS(ZataBase_db) {

	ZEPHIR_REGISTER_CLASS(ZataBase, db, zatabase, db, zatabase_db_method_entry, 0);

	return SUCCESS;

}

PHP_METHOD(ZataBase_db, __construct) {

	int ZEPHIR_LAST_CALL_STATUS;
	zephir_nts_static zephir_fcall_cache_entry *_1 = NULL, *_2 = NULL, *_4 = NULL;
	zval *directory_param = NULL, *_0 = NULL, _3;
	zval *directory = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &directory_param);

	if (unlikely(Z_TYPE_P(directory_param) != IS_STRING && Z_TYPE_P(directory_param) != IS_NULL)) {
		zephir_throw_exception_string(spl_ce_InvalidArgumentException, SL("Parameter 'directory' must be a string") TSRMLS_CC);
		RETURN_MM_NULL();
	}

	if (likely(Z_TYPE_P(directory_param) == IS_STRING)) {
		zephir_get_strval(directory, directory_param);
	} else {
		ZEPHIR_INIT_VAR(directory);
		ZVAL_EMPTY_STRING(directory);
	}


	ZEPHIR_CALL_FUNCTION(&_0, "is_dir", &_1, directory);
	zephir_check_call_status();
	if (!(zephir_is_true(_0))) {
		ZEPHIR_CALL_FUNCTION(NULL, "mkdir", &_2, directory);
		zephir_check_call_status();
		ZEPHIR_SINIT_VAR(_3);
		ZVAL_LONG(&_3, 770);
		ZEPHIR_CALL_FUNCTION(NULL, "chmod", &_4, directory, &_3);
		zephir_check_call_status();
	}
	ZEPHIR_MM_RESTORE();

}

