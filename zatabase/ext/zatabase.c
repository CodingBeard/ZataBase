
/* This file was generated automatically by Zephir do not modify it! */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>

#if PHP_VERSION_ID < 50500
#include <locale.h>
#endif

#include "php_ext.h"
#include "zatabase.h"

#include <ext/standard/info.h>

#include <Zend/zend_operators.h>
#include <Zend/zend_exceptions.h>
#include <Zend/zend_interfaces.h>

#include "kernel/main.h"
#include "kernel/fcall.h"
#include "kernel/memory.h"



zend_class_entry *zatabase_di_injectionawareinterface_ce;
zend_class_entry *zatabase_di_serviceinterface_ce;
zend_class_entry *zatabase_diinterface_ce;
zend_class_entry *zatabase_di_injectable_ce;
zend_class_entry *zatabase_exception_ce;
zend_class_entry *zatabase_execute_querytype_ce;
zend_class_entry *zatabase_execute_condition_ce;
zend_class_entry *zatabase_db_ce;
zend_class_entry *zatabase_db_exception_ce;
zend_class_entry *zatabase_di_ce;
zend_class_entry *zatabase_di_exception_ce;
zend_class_entry *zatabase_di_service_builder_ce;
zend_class_entry *zatabase_di_service_ce;
zend_class_entry *zatabase_execute_ce;
zend_class_entry *zatabase_execute_condition_between_ce;
zend_class_entry *zatabase_execute_condition_equals_ce;
zend_class_entry *zatabase_execute_condition_exception_ce;
zend_class_entry *zatabase_execute_condition_lessthan_ce;
zend_class_entry *zatabase_execute_condition_like_ce;
zend_class_entry *zatabase_execute_condition_morethan_ce;
zend_class_entry *zatabase_execute_condition_within_ce;
zend_class_entry *zatabase_execute_delete_ce;
zend_class_entry *zatabase_execute_exception_ce;
zend_class_entry *zatabase_execute_insert_ce;
zend_class_entry *zatabase_execute_results_ce;
zend_class_entry *zatabase_execute_select_ce;
zend_class_entry *zatabase_helper_arraytoobject_ce;
zend_class_entry *zatabase_schema_ce;
zend_class_entry *zatabase_schema_exception_ce;
zend_class_entry *zatabase_storage_adapter_file_ce;
zend_class_entry *zatabase_storage_exception_ce;
zend_class_entry *zatabase_table_ce;
zend_class_entry *zatabase_table_column_ce;
zend_class_entry *zatabase_table_exception_ce;

ZEND_DECLARE_MODULE_GLOBALS(zatabase)

static PHP_MINIT_FUNCTION(zatabase)
{
#if PHP_VERSION_ID < 50500
	char* old_lc_all = setlocale(LC_ALL, NULL);
	if (old_lc_all) {
		size_t len = strlen(old_lc_all);
		char *tmp  = calloc(len+1, 1);
		if (UNEXPECTED(!tmp)) {
			return FAILURE;
		}

		memcpy(tmp, old_lc_all, len);
		old_lc_all = tmp;
	}

	setlocale(LC_ALL, "C");
#endif

	ZEPHIR_INIT(ZataBase_Di_InjectionAwareInterface);
	ZEPHIR_INIT(ZataBase_DiInterface);
	ZEPHIR_INIT(ZataBase_Di_ServiceInterface);
	ZEPHIR_INIT(ZataBase_Di_Injectable);
	ZEPHIR_INIT(ZataBase_Exception);
	ZEPHIR_INIT(ZataBase_Execute_QueryType);
	ZEPHIR_INIT(ZataBase_Execute_Condition);
	ZEPHIR_INIT(ZataBase_Db);
	ZEPHIR_INIT(ZataBase_Db_Exception);
	ZEPHIR_INIT(ZataBase_Di);
	ZEPHIR_INIT(ZataBase_Di_Exception);
	ZEPHIR_INIT(ZataBase_Di_Service);
	ZEPHIR_INIT(ZataBase_Di_Service_Builder);
	ZEPHIR_INIT(ZataBase_Execute);
	ZEPHIR_INIT(ZataBase_Execute_Condition_Between);
	ZEPHIR_INIT(ZataBase_Execute_Condition_Equals);
	ZEPHIR_INIT(ZataBase_Execute_Condition_Exception);
	ZEPHIR_INIT(ZataBase_Execute_Condition_LessThan);
	ZEPHIR_INIT(ZataBase_Execute_Condition_Like);
	ZEPHIR_INIT(ZataBase_Execute_Condition_MoreThan);
	ZEPHIR_INIT(ZataBase_Execute_Condition_Within);
	ZEPHIR_INIT(ZataBase_Execute_Delete);
	ZEPHIR_INIT(ZataBase_Execute_Exception);
	ZEPHIR_INIT(ZataBase_Execute_Insert);
	ZEPHIR_INIT(ZataBase_Execute_Results);
	ZEPHIR_INIT(ZataBase_Execute_Select);
	ZEPHIR_INIT(ZataBase_Helper_ArrayToObject);
	ZEPHIR_INIT(ZataBase_Schema);
	ZEPHIR_INIT(ZataBase_Schema_Exception);
	ZEPHIR_INIT(ZataBase_Storage_Adapter_File);
	ZEPHIR_INIT(ZataBase_Storage_Exception);
	ZEPHIR_INIT(ZataBase_Table);
	ZEPHIR_INIT(ZataBase_Table_Column);
	ZEPHIR_INIT(ZataBase_Table_Exception);

#if PHP_VERSION_ID < 50500
	setlocale(LC_ALL, old_lc_all);
	free(old_lc_all);
#endif
	return SUCCESS;
}

#ifndef ZEPHIR_RELEASE
static PHP_MSHUTDOWN_FUNCTION(zatabase)
{

	zephir_deinitialize_memory(TSRMLS_C);

	return SUCCESS;
}
#endif

/**
 * Initialize globals on each request or each thread started
 */
static void php_zephir_init_globals(zend_zatabase_globals *zephir_globals TSRMLS_DC)
{
	zephir_globals->initialized = 0;

	/* Memory options */
	zephir_globals->active_memory = NULL;

	/* Virtual Symbol Tables */
	zephir_globals->active_symbol_table = NULL;

	/* Cache Enabled */
	zephir_globals->cache_enabled = 1;

	/* Recursive Lock */
	zephir_globals->recursive_lock = 0;


}

static PHP_RINIT_FUNCTION(zatabase)
{

	zend_zatabase_globals *zephir_globals_ptr = ZEPHIR_VGLOBAL;

	php_zephir_init_globals(zephir_globals_ptr TSRMLS_CC);
	//zephir_init_interned_strings(TSRMLS_C);

	zephir_initialize_memory(zephir_globals_ptr TSRMLS_CC);

	return SUCCESS;
}

static PHP_RSHUTDOWN_FUNCTION(zatabase)
{

	

	zephir_deinitialize_memory(TSRMLS_C);
	return SUCCESS;
}

static PHP_MINFO_FUNCTION(zatabase)
{
	php_info_print_box_start(0);
	php_printf("%s", PHP_ZATABASE_DESCRIPTION);
	php_info_print_box_end();

	php_info_print_table_start();
	php_info_print_table_header(2, PHP_ZATABASE_NAME, "enabled");
	php_info_print_table_row(2, "Author", PHP_ZATABASE_AUTHOR);
	php_info_print_table_row(2, "Version", PHP_ZATABASE_VERSION);
	php_info_print_table_row(2, "Powered by Zephir", "Version " PHP_ZATABASE_ZEPVERSION);
	php_info_print_table_end();


}

static PHP_GINIT_FUNCTION(zatabase)
{
	php_zephir_init_globals(zatabase_globals TSRMLS_CC);
}

static PHP_GSHUTDOWN_FUNCTION(zatabase)
{

}


zend_function_entry php_zatabase_functions[] = {
ZEND_FE_END

};

zend_module_entry zatabase_module_entry = {
	STANDARD_MODULE_HEADER_EX,
	NULL,
	NULL,
	PHP_ZATABASE_EXTNAME,
	php_zatabase_functions,
	PHP_MINIT(zatabase),
#ifndef ZEPHIR_RELEASE
	PHP_MSHUTDOWN(zatabase),
#else
	NULL,
#endif
	PHP_RINIT(zatabase),
	PHP_RSHUTDOWN(zatabase),
	PHP_MINFO(zatabase),
	PHP_ZATABASE_VERSION,
	ZEND_MODULE_GLOBALS(zatabase),
	PHP_GINIT(zatabase),
	PHP_GSHUTDOWN(zatabase),
	NULL,
	STANDARD_MODULE_PROPERTIES_EX
};

#ifdef COMPILE_DL_ZATABASE
ZEND_GET_MODULE(zatabase)
#endif
