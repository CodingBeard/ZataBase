
/* This file was generated automatically by Zephir do not modify it! */

#ifndef PHP_ZATABASE_H
#define PHP_ZATABASE_H 1

#ifdef PHP_WIN32
#define ZEPHIR_RELEASE 1
#endif

#include "kernel/globals.h"

#define PHP_ZATABASE_NAME        "zatabase"
#define PHP_ZATABASE_VERSION     "0.0.1"
#define PHP_ZATABASE_EXTNAME     "zatabase"
#define PHP_ZATABASE_AUTHOR      "Tim Marshall"
#define PHP_ZATABASE_ZEPVERSION  "0.6.0a"
#define PHP_ZATABASE_DESCRIPTION "A database system written in Zephir"



ZEND_BEGIN_MODULE_GLOBALS(zatabase)

	int initialized;

	/* Memory */
	zephir_memory_entry *start_memory; /**< The first preallocated frame */
	zephir_memory_entry *end_memory; /**< The last preallocate frame */
	zephir_memory_entry *active_memory; /**< The current memory frame */

	/* Virtual Symbol Tables */
	zephir_symbol_table *active_symbol_table;

	/** Function cache */
	HashTable *fcache;

	/* Cache enabled */
	unsigned int cache_enabled;

	/* Max recursion control */
	unsigned int recursive_lock;

	/* Global constants */
	zval *global_true;
	zval *global_false;
	zval *global_null;
	
ZEND_END_MODULE_GLOBALS(zatabase)

#ifdef ZTS
#include "TSRM.h"
#endif

ZEND_EXTERN_MODULE_GLOBALS(zatabase)

#ifdef ZTS
	#define ZEPHIR_GLOBAL(v) TSRMG(zatabase_globals_id, zend_zatabase_globals *, v)
#else
	#define ZEPHIR_GLOBAL(v) (zatabase_globals.v)
#endif

#ifdef ZTS
	#define ZEPHIR_VGLOBAL ((zend_zatabase_globals *) (*((void ***) tsrm_ls))[TSRM_UNSHUFFLE_RSRC_ID(zatabase_globals_id)])
#else
	#define ZEPHIR_VGLOBAL &(zatabase_globals)
#endif

#define ZEPHIR_API ZEND_API

#define zephir_globals_def zatabase_globals
#define zend_zephir_globals_def zend_zatabase_globals

extern zend_module_entry zatabase_module_entry;
#define phpext_zatabase_ptr &zatabase_module_entry

#endif
