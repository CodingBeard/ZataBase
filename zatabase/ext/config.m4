PHP_ARG_ENABLE(zatabase, whether to enable zatabase, [ --enable-zatabase   Enable Zatabase])

if test "$PHP_ZATABASE" = "yes"; then

	

	if ! test "x" = "x"; then
		PHP_EVAL_LIBLINE(, ZATABASE_SHARED_LIBADD)
	fi

	AC_DEFINE(HAVE_ZATABASE, 1, [Whether you have Zatabase])
	zatabase_sources="zatabase.c kernel/main.c kernel/memory.c kernel/exception.c kernel/hash.c kernel/debug.c kernel/backtrace.c kernel/object.c kernel/array.c kernel/extended/array.c kernel/string.c kernel/fcall.c kernel/require.c kernel/file.c kernel/operators.c kernel/concat.c kernel/variables.c kernel/filter.c kernel/iterator.c kernel/exit.c zatabase/db.zep.c
	zatabase/db/exception.zep.c
	zatabase/di.zep.c
	zatabase/di/exception.zep.c
	zatabase/di/injectable.zep.c
	zatabase/di/injectionawareinterface.zep.c
	zatabase/di/service.zep.c
	zatabase/di/service/builder.zep.c
	zatabase/di/serviceinterface.zep.c
	zatabase/diinterface.zep.c
	zatabase/exception.zep.c
	zatabase/execute.zep.c
	zatabase/execute/condition.zep.c
	zatabase/execute/condition/between.zep.c
	zatabase/execute/condition/equals.zep.c
	zatabase/execute/condition/exception.zep.c
	zatabase/execute/condition/lessthan.zep.c
	zatabase/execute/condition/like.zep.c
	zatabase/execute/condition/morethan.zep.c
	zatabase/execute/condition/within.zep.c
	zatabase/execute/delete.zep.c
	zatabase/execute/exception.zep.c
	zatabase/execute/insert.zep.c
	zatabase/execute/querytype.zep.c
	zatabase/execute/results.zep.c
	zatabase/execute/select.zep.c
	zatabase/helper/arraytoobject.zep.c
	zatabase/helper/filehandler.zep.c
	zatabase/schema.zep.c
	zatabase/schema/exception.zep.c
	zatabase/storage/adapter/file.zep.c
	zatabase/storage/exception.zep.c
	zatabase/table.zep.c
	zatabase/table/column.zep.c
	zatabase/table/exception.zep.c "
	PHP_NEW_EXTENSION(zatabase, $zatabase_sources, $ext_shared,, )
	PHP_SUBST(ZATABASE_SHARED_LIBADD)

	old_CPPFLAGS=$CPPFLAGS
	CPPFLAGS="$CPPFLAGS $INCLUDES"

	AC_CHECK_DECL(
		[HAVE_BUNDLED_PCRE],
		[
			AC_CHECK_HEADERS(
				[ext/pcre/php_pcre.h],
				[
					PHP_ADD_EXTENSION_DEP([zatabase], [pcre])
					AC_DEFINE([ZEPHIR_USE_PHP_PCRE], [1], [Whether PHP pcre extension is present at compile time])
				],
				,
				[[#include "main/php.h"]]
			)
		],
		,
		[[#include "php_config.h"]]
	)

	AC_CHECK_DECL(
		[HAVE_JSON],
		[
			AC_CHECK_HEADERS(
				[ext/json/php_json.h],
				[
					PHP_ADD_EXTENSION_DEP([zatabase], [json])
					AC_DEFINE([ZEPHIR_USE_PHP_JSON], [1], [Whether PHP json extension is present at compile time])
				],
				,
				[[#include "main/php.h"]]
			)
		],
		,
		[[#include "php_config.h"]]
	)

	CPPFLAGS=$old_CPPFLAGS

	PHP_INSTALL_HEADERS([ext/zatabase], [php_ZATABASE.h])

fi
