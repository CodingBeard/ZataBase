srcdir = /home/tim/github/ZataBase/zatabase/ext
builddir = /home/tim/github/ZataBase/zatabase/ext
top_srcdir = /home/tim/github/ZataBase/zatabase/ext
top_builddir = /home/tim/github/ZataBase/zatabase/ext
EGREP = /bin/grep -E
SED = /bin/sed
CONFIGURE_COMMAND = './configure' '--enable-zatabase'
CONFIGURE_OPTIONS = '--enable-zatabase'
SHLIB_SUFFIX_NAME = so
SHLIB_DL_SUFFIX_NAME = so
ZEND_EXT_TYPE = zend_extension
RE2C = re2c
AWK = gawk
shared_objects_zatabase = zatabase.lo kernel/main.lo kernel/memory.lo kernel/exception.lo kernel/hash.lo kernel/debug.lo kernel/backtrace.lo kernel/object.lo kernel/array.lo kernel/extended/array.lo kernel/string.lo kernel/fcall.lo kernel/require.lo kernel/file.lo kernel/operators.lo kernel/concat.lo kernel/variables.lo kernel/filter.lo kernel/iterator.lo kernel/exit.lo zatabase/db.lo zatabase/db/exception.lo zatabase/di.lo zatabase/di/exception.lo zatabase/di/injectable.lo zatabase/di/injectionawareinterface.lo zatabase/di/service.lo zatabase/di/service/builder.lo zatabase/di/serviceinterface.lo zatabase/diinterface.lo zatabase/exception.lo zatabase/execute.lo zatabase/execute/complexresults.lo zatabase/execute/condition.lo zatabase/execute/condition/between.lo zatabase/execute/condition/equals.lo zatabase/execute/condition/exception.lo zatabase/execute/condition/lessthan.lo zatabase/execute/condition/like.lo zatabase/execute/condition/morethan.lo zatabase/execute/condition/within.lo zatabase/execute/delete.lo zatabase/execute/exception.lo zatabase/execute/insert.lo zatabase/execute/querytype.lo zatabase/execute/results.lo zatabase/execute/select.lo zatabase/execute/update.lo zatabase/helper/arraytoobject.lo zatabase/helper/csv.lo zatabase/helper/exception.lo zatabase/helper/filehandler.lo zatabase/schema.lo zatabase/schema/alter.lo zatabase/schema/exception.lo zatabase/storage/adapter/file.lo zatabase/storage/exception.lo zatabase/table.lo zatabase/table/column.lo zatabase/table/exception.lo zatabase/table/relation.lo zatabase/table/relations/belongsto.lo zatabase/table/relations/hasone.lo zatabase/0__closure.lo zatabase/1__closure.lo zatabase/2__closure.lo
PHP_PECL_EXTENSION = zatabase
ZATABASE_SHARED_LIBADD =
PHP_MODULES = $(phplibdir)/zatabase.la
PHP_ZEND_EX =
all_targets = $(PHP_MODULES) $(PHP_ZEND_EX)
install_targets = install-modules install-headers
prefix = /usr
exec_prefix = $(prefix)
libdir = ${exec_prefix}/lib
prefix = /usr
phplibdir = /home/tim/github/ZataBase/zatabase/ext/modules
phpincludedir = /usr/include/php5
CC = gcc
CFLAGS = -O2 -fvisibility=hidden -Wparentheses -flto -DZEPHIR_RELEASE=1
CFLAGS_CLEAN = $(CFLAGS)
CPP = gcc -E
CPPFLAGS = -DHAVE_CONFIG_H
CXX =
CXXFLAGS =
CXXFLAGS_CLEAN = $(CXXFLAGS)
EXTENSION_DIR = /usr/lib/php5/20121212
PHP_EXECUTABLE = /usr/bin/php
EXTRA_LDFLAGS =
EXTRA_LIBS =
INCLUDES = -I/usr/include/php5 -I/usr/include/php5/main -I/usr/include/php5/TSRM -I/usr/include/php5/Zend -I/usr/include/php5/ext -I/usr/include/php5/ext/date/lib
LFLAGS =
LDFLAGS =
SHARED_LIBTOOL =
LIBTOOL = $(SHELL) $(top_builddir)/libtool
SHELL = /bin/bash
INSTALL_HEADERS = ext/zatabase/php_ZATABASE.h
mkinstalldirs = $(top_srcdir)/build/shtool mkdir -p
INSTALL = $(top_srcdir)/build/shtool install -c
INSTALL_DATA = $(INSTALL) -m 644

DEFS = -DPHP_ATOM_INC -I$(top_builddir)/include -I$(top_builddir)/main -I$(top_srcdir)
COMMON_FLAGS = $(DEFS) $(INCLUDES) $(EXTRA_INCLUDES) $(CPPFLAGS) $(PHP_FRAMEWORKPATH)

all: $(all_targets) 
	@echo
	@echo "Build complete."
	@echo "Don't forget to run 'make test'."
	@echo

build-modules: $(PHP_MODULES) $(PHP_ZEND_EX)

build-binaries: $(PHP_BINARIES)

libphp$(PHP_MAJOR_VERSION).la: $(PHP_GLOBAL_OBJS) $(PHP_SAPI_OBJS)
	$(LIBTOOL) --mode=link $(CC) $(CFLAGS) $(EXTRA_CFLAGS) -rpath $(phptempdir) $(EXTRA_LDFLAGS) $(LDFLAGS) $(PHP_RPATHS) $(PHP_GLOBAL_OBJS) $(PHP_SAPI_OBJS) $(EXTRA_LIBS) $(ZEND_EXTRA_LIBS) -o $@
	-@$(LIBTOOL) --silent --mode=install cp $@ $(phptempdir)/$@ >/dev/null 2>&1

libs/libphp$(PHP_MAJOR_VERSION).bundle: $(PHP_GLOBAL_OBJS) $(PHP_SAPI_OBJS)
	$(CC) $(MH_BUNDLE_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS) $(LDFLAGS) $(EXTRA_LDFLAGS) $(PHP_GLOBAL_OBJS:.lo=.o) $(PHP_SAPI_OBJS:.lo=.o) $(PHP_FRAMEWORKS) $(EXTRA_LIBS) $(ZEND_EXTRA_LIBS) -o $@ && cp $@ libs/libphp$(PHP_MAJOR_VERSION).so

install: $(all_targets) $(install_targets)

install-sapi: $(OVERALL_TARGET)
	@echo "Installing PHP SAPI module:       $(PHP_SAPI)"
	-@$(mkinstalldirs) $(INSTALL_ROOT)$(bindir)
	-@if test ! -r $(phptempdir)/libphp$(PHP_MAJOR_VERSION).$(SHLIB_DL_SUFFIX_NAME); then \
		for i in 0.0.0 0.0 0; do \
			if test -r $(phptempdir)/libphp$(PHP_MAJOR_VERSION).$(SHLIB_DL_SUFFIX_NAME).$$i; then \
				$(LN_S) $(phptempdir)/libphp$(PHP_MAJOR_VERSION).$(SHLIB_DL_SUFFIX_NAME).$$i $(phptempdir)/libphp$(PHP_MAJOR_VERSION).$(SHLIB_DL_SUFFIX_NAME); \
				break; \
			fi; \
		done; \
	fi
	@$(INSTALL_IT)

install-binaries: build-binaries $(install_binary_targets)

install-modules: build-modules
	@test -d modules && \
	$(mkinstalldirs) $(INSTALL_ROOT)$(EXTENSION_DIR)
	@echo "Installing shared extensions:     $(INSTALL_ROOT)$(EXTENSION_DIR)/"
	@rm -f modules/*.la >/dev/null 2>&1
	@$(INSTALL) modules/* $(INSTALL_ROOT)$(EXTENSION_DIR)

install-headers:
	-@if test "$(INSTALL_HEADERS)"; then \
		for i in `echo $(INSTALL_HEADERS)`; do \
			i=`$(top_srcdir)/build/shtool path -d $$i`; \
			paths="$$paths $(INSTALL_ROOT)$(phpincludedir)/$$i"; \
		done; \
		$(mkinstalldirs) $$paths && \
		echo "Installing header files:          $(INSTALL_ROOT)$(phpincludedir)/" && \
		for i in `echo $(INSTALL_HEADERS)`; do \
			if test "$(PHP_PECL_EXTENSION)"; then \
				src=`echo $$i | $(SED) -e "s#ext/$(PHP_PECL_EXTENSION)/##g"`; \
			else \
				src=$$i; \
			fi; \
			if test -f "$(top_srcdir)/$$src"; then \
				$(INSTALL_DATA) $(top_srcdir)/$$src $(INSTALL_ROOT)$(phpincludedir)/$$i; \
			elif test -f "$(top_builddir)/$$src"; then \
				$(INSTALL_DATA) $(top_builddir)/$$src $(INSTALL_ROOT)$(phpincludedir)/$$i; \
			else \
				(cd $(top_srcdir)/$$src && $(INSTALL_DATA) *.h $(INSTALL_ROOT)$(phpincludedir)/$$i; \
				cd $(top_builddir)/$$src && $(INSTALL_DATA) *.h $(INSTALL_ROOT)$(phpincludedir)/$$i) 2>/dev/null || true; \
			fi \
		done; \
	fi

PHP_TEST_SETTINGS = -d 'open_basedir=' -d 'output_buffering=0' -d 'memory_limit=-1'
PHP_TEST_SHARED_EXTENSIONS =  ` \
	if test "x$(PHP_MODULES)" != "x"; then \
		for i in $(PHP_MODULES)""; do \
			. $$i; $(top_srcdir)/build/shtool echo -n -- " -d extension=$$dlname"; \
		done; \
	fi; \
	if test "x$(PHP_ZEND_EX)" != "x"; then \
		for i in $(PHP_ZEND_EX)""; do \
			. $$i; $(top_srcdir)/build/shtool echo -n -- " -d $(ZEND_EXT_TYPE)=$(top_builddir)/modules/$$dlname"; \
		done; \
	fi`
PHP_DEPRECATED_DIRECTIVES_REGEX = '^(magic_quotes_(gpc|runtime|sybase)?|(zend_)?extension(_debug)?(_ts)?)[\t\ ]*='

test: all
	@if test ! -z "$(PHP_EXECUTABLE)" && test -x "$(PHP_EXECUTABLE)"; then \
		INI_FILE=`$(PHP_EXECUTABLE) -d 'display_errors=stderr' -r 'echo php_ini_loaded_file();' 2> /dev/null`; \
		if test "$$INI_FILE"; then \
			$(EGREP) -h -v $(PHP_DEPRECATED_DIRECTIVES_REGEX) "$$INI_FILE" > $(top_builddir)/tmp-php.ini; \
		else \
			echo > $(top_builddir)/tmp-php.ini; \
		fi; \
		INI_SCANNED_PATH=`$(PHP_EXECUTABLE) -d 'display_errors=stderr' -r '$$a = explode(",\n", trim(php_ini_scanned_files())); echo $$a[0];' 2> /dev/null`; \
		if test "$$INI_SCANNED_PATH"; then \
			INI_SCANNED_PATH=`$(top_srcdir)/build/shtool path -d $$INI_SCANNED_PATH`; \
			$(EGREP) -h -v $(PHP_DEPRECATED_DIRECTIVES_REGEX) "$$INI_SCANNED_PATH"/*.ini >> $(top_builddir)/tmp-php.ini; \
		fi; \
		TEST_PHP_EXECUTABLE=$(PHP_EXECUTABLE) \
		TEST_PHP_SRCDIR=$(top_srcdir) \
		CC="$(CC)" \
			$(PHP_EXECUTABLE) -n -c $(top_builddir)/tmp-php.ini $(PHP_TEST_SETTINGS) $(top_srcdir)/run-tests.php -n -c $(top_builddir)/tmp-php.ini -d extension_dir=$(top_builddir)/modules/ $(PHP_TEST_SHARED_EXTENSIONS) $(TESTS); \
		TEST_RESULT_EXIT_CODE=$$?; \
		rm $(top_builddir)/tmp-php.ini; \
		exit $$TEST_RESULT_EXIT_CODE; \
	else \
		echo "ERROR: Cannot run tests without CLI sapi."; \
	fi

clean:
	find . -name \*.gcno -o -name \*.gcda | xargs rm -f
	find . -name \*.lo -o -name \*.o | xargs rm -f
	find . -name \*.la -o -name \*.a | xargs rm -f 
	find . -name \*.so | xargs rm -f
	find . -name .libs -a -type d|xargs rm -rf
	rm -f libphp$(PHP_MAJOR_VERSION).la $(SAPI_CLI_PATH) $(SAPI_CGI_PATH) $(SAPI_MILTER_PATH) $(SAPI_LITESPEED_PATH) $(SAPI_FPM_PATH) $(OVERALL_TARGET) modules/* libs/*

distclean: clean
	rm -f Makefile config.cache config.log config.status Makefile.objects Makefile.fragments libtool main/php_config.h main/internal_functions_cli.c main/internal_functions.c stamp-h sapi/apache/libphp$(PHP_MAJOR_VERSION).module sapi/apache_hooks/libphp$(PHP_MAJOR_VERSION).module buildmk.stamp Zend/zend_dtrace_gen.h Zend/zend_dtrace_gen.h.bak Zend/zend_config.h TSRM/tsrm_config.h
	rm -f php5.spec main/build-defs.h scripts/phpize
	rm -f ext/date/lib/timelib_config.h ext/mbstring/oniguruma/config.h ext/mbstring/libmbfl/config.h ext/mysqlnd/php_mysqlnd_config.h
	rm -f scripts/man1/phpize.1 scripts/php-config scripts/man1/php-config.1 sapi/cli/php.1 sapi/cgi/php-cgi.1 ext/phar/phar.1 ext/phar/phar.phar.1
	rm -f sapi/fpm/php-fpm.conf sapi/fpm/init.d.php-fpm sapi/fpm/php-fpm.service sapi/fpm/php-fpm.8 sapi/fpm/status.html
	rm -f ext/iconv/php_have_bsd_iconv.h ext/iconv/php_have_glibc_iconv.h ext/iconv/php_have_ibm_iconv.h ext/iconv/php_have_iconv.h ext/iconv/php_have_libiconv.h ext/iconv/php_iconv_aliased_libiconv.h ext/iconv/php_iconv_supports_errno.h ext/iconv/php_php_iconv_h_path.h ext/iconv/php_php_iconv_impl.h
	rm -f ext/phar/phar.phar ext/phar/phar.php
	if test "$(srcdir)" != "$(builddir)"; then \
	  rm -f ext/phar/phar/phar.inc; \
	fi
	$(EGREP) define'.*include/php' $(top_srcdir)/configure | $(SED) 's/.*>//'|xargs rm -f

.PHONY: all clean install distclean test
.NOEXPORT:
zatabase.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase.c -o zatabase.lo 
kernel/main.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/main.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/main.c -o kernel/main.lo 
kernel/memory.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/memory.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/memory.c -o kernel/memory.lo 
kernel/exception.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/exception.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/exception.c -o kernel/exception.lo 
kernel/hash.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/hash.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/hash.c -o kernel/hash.lo 
kernel/debug.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/debug.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/debug.c -o kernel/debug.lo 
kernel/backtrace.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/backtrace.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/backtrace.c -o kernel/backtrace.lo 
kernel/object.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/object.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/object.c -o kernel/object.lo 
kernel/array.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/array.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/array.c -o kernel/array.lo 
kernel/extended/array.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/extended/array.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/extended/array.c -o kernel/extended/array.lo 
kernel/string.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/string.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/string.c -o kernel/string.lo 
kernel/fcall.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/fcall.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/fcall.c -o kernel/fcall.lo 
kernel/require.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/require.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/require.c -o kernel/require.lo 
kernel/file.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/file.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/file.c -o kernel/file.lo 
kernel/operators.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/operators.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/operators.c -o kernel/operators.lo 
kernel/concat.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/concat.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/concat.c -o kernel/concat.lo 
kernel/variables.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/variables.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/variables.c -o kernel/variables.lo 
kernel/filter.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/filter.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/filter.c -o kernel/filter.lo 
kernel/iterator.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/iterator.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/iterator.c -o kernel/iterator.lo 
kernel/exit.lo: /home/tim/github/ZataBase/zatabase/ext/kernel/exit.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/kernel/exit.c -o kernel/exit.lo 
zatabase/db.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/db.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/db.zep.c -o zatabase/db.lo 
zatabase/db/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/db/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/db/exception.zep.c -o zatabase/db/exception.lo 
zatabase/di.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di.zep.c -o zatabase/di.lo 
zatabase/di/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/exception.zep.c -o zatabase/di/exception.lo 
zatabase/di/injectable.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/injectable.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/injectable.zep.c -o zatabase/di/injectable.lo 
zatabase/di/injectionawareinterface.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/injectionawareinterface.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/injectionawareinterface.zep.c -o zatabase/di/injectionawareinterface.lo 
zatabase/di/service.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/service.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/service.zep.c -o zatabase/di/service.lo 
zatabase/di/service/builder.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/service/builder.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/service/builder.zep.c -o zatabase/di/service/builder.lo 
zatabase/di/serviceinterface.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/di/serviceinterface.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/di/serviceinterface.zep.c -o zatabase/di/serviceinterface.lo 
zatabase/diinterface.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/diinterface.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/diinterface.zep.c -o zatabase/diinterface.lo 
zatabase/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/exception.zep.c -o zatabase/exception.lo 
zatabase/execute.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute.zep.c -o zatabase/execute.lo 
zatabase/execute/complexresults.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/complexresults.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/complexresults.zep.c -o zatabase/execute/complexresults.lo 
zatabase/execute/condition.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition.zep.c -o zatabase/execute/condition.lo 
zatabase/execute/condition/between.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/between.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/between.zep.c -o zatabase/execute/condition/between.lo 
zatabase/execute/condition/equals.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/equals.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/equals.zep.c -o zatabase/execute/condition/equals.lo 
zatabase/execute/condition/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/exception.zep.c -o zatabase/execute/condition/exception.lo 
zatabase/execute/condition/lessthan.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/lessthan.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/lessthan.zep.c -o zatabase/execute/condition/lessthan.lo 
zatabase/execute/condition/like.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/like.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/like.zep.c -o zatabase/execute/condition/like.lo 
zatabase/execute/condition/morethan.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/morethan.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/morethan.zep.c -o zatabase/execute/condition/morethan.lo 
zatabase/execute/condition/within.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/within.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/condition/within.zep.c -o zatabase/execute/condition/within.lo 
zatabase/execute/delete.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/delete.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/delete.zep.c -o zatabase/execute/delete.lo 
zatabase/execute/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/exception.zep.c -o zatabase/execute/exception.lo 
zatabase/execute/insert.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/insert.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/insert.zep.c -o zatabase/execute/insert.lo 
zatabase/execute/querytype.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/querytype.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/querytype.zep.c -o zatabase/execute/querytype.lo 
zatabase/execute/results.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/results.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/results.zep.c -o zatabase/execute/results.lo 
zatabase/execute/select.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/select.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/select.zep.c -o zatabase/execute/select.lo 
zatabase/execute/update.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/update.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/execute/update.zep.c -o zatabase/execute/update.lo 
zatabase/helper/arraytoobject.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/arraytoobject.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/arraytoobject.zep.c -o zatabase/helper/arraytoobject.lo 
zatabase/helper/csv.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/csv.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/csv.zep.c -o zatabase/helper/csv.lo 
zatabase/helper/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/exception.zep.c -o zatabase/helper/exception.lo 
zatabase/helper/filehandler.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/filehandler.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/helper/filehandler.zep.c -o zatabase/helper/filehandler.lo 
zatabase/schema.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/schema.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/schema.zep.c -o zatabase/schema.lo 
zatabase/schema/alter.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/schema/alter.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/schema/alter.zep.c -o zatabase/schema/alter.lo 
zatabase/schema/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/schema/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/schema/exception.zep.c -o zatabase/schema/exception.lo 
zatabase/storage/adapter/file.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/storage/adapter/file.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/storage/adapter/file.zep.c -o zatabase/storage/adapter/file.lo 
zatabase/storage/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/storage/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/storage/exception.zep.c -o zatabase/storage/exception.lo 
zatabase/table.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table.zep.c -o zatabase/table.lo 
zatabase/table/column.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table/column.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table/column.zep.c -o zatabase/table/column.lo 
zatabase/table/exception.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table/exception.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table/exception.zep.c -o zatabase/table/exception.lo 
zatabase/table/relation.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relation.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relation.zep.c -o zatabase/table/relation.lo 
zatabase/table/relations/belongsto.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relations/belongsto.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relations/belongsto.zep.c -o zatabase/table/relations/belongsto.lo 
zatabase/table/relations/hasone.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relations/hasone.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/table/relations/hasone.zep.c -o zatabase/table/relations/hasone.lo 
zatabase/0__closure.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/0__closure.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/0__closure.zep.c -o zatabase/0__closure.lo 
zatabase/1__closure.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/1__closure.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/1__closure.zep.c -o zatabase/1__closure.lo 
zatabase/2__closure.lo: /home/tim/github/ZataBase/zatabase/ext/zatabase/2__closure.zep.c
	$(LIBTOOL) --mode=compile $(CC)  -I. -I/home/tim/github/ZataBase/zatabase/ext $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS)  -c /home/tim/github/ZataBase/zatabase/ext/zatabase/2__closure.zep.c -o zatabase/2__closure.lo 
$(phplibdir)/zatabase.la: ./zatabase.la
	$(LIBTOOL) --mode=install cp ./zatabase.la $(phplibdir)

./zatabase.la: $(shared_objects_zatabase) $(ZATABASE_SHARED_DEPENDENCIES)
	$(LIBTOOL) --mode=link $(CC) $(COMMON_FLAGS) $(CFLAGS_CLEAN) $(EXTRA_CFLAGS) $(LDFLAGS) -o $@ -export-dynamic -avoid-version -prefer-pic -module -rpath $(phplibdir) $(EXTRA_LDFLAGS) $(shared_objects_zatabase) $(ZATABASE_SHARED_LIBADD)

