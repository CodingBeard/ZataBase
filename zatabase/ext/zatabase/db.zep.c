
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
#include "kernel/exception.h"
#include "ext/spl/spl_exceptions.h"
#include "kernel/memory.h"


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
* $db = new ZataBase\db([
* 'database' => '/path/to/folder/',
* 'user' => 'User',
* 'pass' => 'Pass',
* ]);
*/
ZEPHIR_INIT_CLASS(ZataBase_Db) {

	ZEPHIR_REGISTER_CLASS(ZataBase, Db, zatabase, db, zatabase_db_method_entry, 0);

	/**
	 * Storage adapter
	 * @var storage Storage\StorageInterface
	 */
	zend_declare_property_null(zatabase_db_ce, SL("storage"), ZEND_ACC_PROTECTED TSRMLS_CC);

	/**
	 * Location of the auth file
	 * @var authFile string
	 */
	zend_declare_property_string(zatabase_db_ce, SL("authFile"), ".auth", ZEND_ACC_PROTECTED TSRMLS_CC);

	return SUCCESS;

}

PHP_METHOD(ZataBase_Db, __construct) {

	zval *parameters = NULL;
	zval *adapter, *parameters_param = NULL;

	zephir_fetch_params(0, 2, 0, &adapter, &parameters_param);

	parameters = parameters_param;



	if (!(zephir_is_instance_of(adapter, SL("ZataBase\\Storage\\StorageInterface") TSRMLS_CC))) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STRW(spl_ce_InvalidArgumentException, "Parameter 'adapter' must be an instance of 'ZataBase\\Storage\\StorageInterface'", "", 0);
		return;
	}
	zephir_update_property_this(this_ptr, SL("storage"), adapter TSRMLS_CC);

}

PHP_METHOD(ZataBase_Db, getStorage) {


	RETURN_MEMBER(this_ptr, "storage");

}

