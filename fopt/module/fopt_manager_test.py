#!/usr/bin/env python
#-*- coding: utf-8 -*-
#
##################################################################################################
#  File Name	: fopt_manager.py
#  Description	: fopt project manager
#  FuncList	:
#  Writer	: Lim SeongYeol( limsy37@naver.com )
#  Modification
#	- 2014.07.17, write code
#	- 2014.07.18, file write - unicode(utf-8)
#	- 2014.07.26, compiling option add - fPIC : for memset, memcpy
#	- 2014.07.26, modify Configuration path and name.
#	- 2014.07.26, issue - when this script is executed by apache, sys.stdout.encoding value is NONE
#	- 2014.07.26, result file - always create report file including when the result are none.
#	- 2014.07.26, insert code that write core_analyzer result file
#	- 2014.07.31, db_change_fopt_state - db connection, Query Exec, db close
##################################################################################################
#
# FOPT Manager
#	- input num_work(code_idx)
#	- Source Code Exception Check (TODO)
#	- Source Code Compile( So Dynamic Library )
#	- Run FEC_Tester
#	- Runtime-Error Check, Run Core_Analyzer
#	- Database Update( state_id )
#
#

import MySQLdb
import sys, os, commands
import re



##################################################################################################
# Configuration - MySQL Info : host, user, passwd
##################################################################################################
STATIC_DB_HOST	= "localhost"#"127.0.0.1"
STATIC_DB_USER	= "root"
STATIC_DB_PASS	= "marionette"

##################################################################################################
# Configuration - PATH : Default Working Directory, Each File Name
##################################################################################################
STATIC_PATH_WORK_DIR		= "/var/www/html/fopt/result/"
STATIC_PATH_FEC_TESTER		= "/var/www/html/fopt/module/fopt_tester"
STATIC_PATH_CORE_ANALYZER	= "/var/www/html/fopt/module/fopt_core_analyzer"
STATIC_NAME_CODE			= "input.c"
STATIC_NAME_OBJ				= "input.o"
STATIC_NAME_LIBRARY			= "fec_input.so"
STATIC_NAME_CORE			= "core"
STATIC_NAME_RES_COMPILE		= "res_compile.txt"
STATIC_NAME_RES_RUN			= "res_run_tester.txt"
STATIC_NAME_RES_ERROR		= "res_error_report.txt"
STATIC_PATH_SAMPLE			= "/var/www/html/fopt/result/sample/sample0.mp4"


##################################################################################################
# MySQL Database Function
##################################################################################################

# 
# Delete Code.
#
#def db_connection( mysql_host, mysql_user, mysql_passwd ):
#	mysql_db = MySQLdb.connect( host=mysql_host, user=mysql_user, passwd=mysql_passwd )
#	return mysql_db
#
#def db_get_cursor( mysql_db ):
#	cursor = mysql_db.cursor( MySQLdb.cursors.DictCursor )
#	return cursor
#
#def db_close( mysql_db, cursor ):
#	cursor.close()
#	mysql_db.commit()
#	mysql_db.close()
#

def db_change_fopt_state( code_idx, state_id ):
	# 1. Connection DB
	mysql_db = MySQLdb.connect( host=STATIC_DB_HOST, user=STATIC_DB_USER, passwd=STATIC_DB_PASS )
	cursor = mysql_db.cursor( MySQLdb.cursors.DictCursor )
	
	# 2. Query Execute
	query = 'UPDATE fopt.code SET state_id=\'%s\' WHERE code_idx=%d' % ( state_id, code_idx )
	print "-", state_id
	cursor.execute( query )

	# 3. Close DB
	cursor.close()
	mysql_db.commit()
	mysql_db.close()



##################################################################################################
# File Function
##################################################################################################
def file_write( file_name, output ):
	f = open( file_name, 'w' )
	f.write( unicode(output + "\n", "UTF-8" ).encode('utf-8') )#sys.stdout.encoding).encode('utf-8') )
	f.close()

def file_write_append( file_name, output ):
	f = open( file_name, 'a' )
	f.write( unicode(output + "\n", "UTF-8" ).encode('utf-8') )#sys.stdout.encoding).encode('utf-8') )
	f.close()

def file_read( file_name ):
	f = open( file_name, 'r' )
	output = f.read()
	f.close()
	return output



##################################################################################################
# Source code Exception Check Function
##################################################################################################
def source_exception_check( file_name ):
	code = file_read( file_name )
	return True, "hehe"

	re.search( "(void)[ \t\n]{1,}(info)[ \t\n]{0,}\([ \t\n]{0,}(unsigned)", code );
#void (*Encoding)(unsigned char*, unsigned char*, int, double);
#void (*Decoding)(unsigned char*, unsigned char*, int, double);
#unsigned int (*Info)(unsigned int, double);
#?
#?


##################################################################################################
# Manager Main Function
##################################################################################################
def main( code_idx ):
	# 0. program init
	# 0-1. program header
	print "# FOPT Manager ( code_idx =", code_idx, ")"
	print "  System Character-set : ", sys.stdout.encoding
	print

	# 0-2. get paths
	path_work_dir		= STATIC_PATH_WORK_DIR + "%d/" % code_idx
	path_code			= path_work_dir + STATIC_NAME_CODE
	path_obj			= path_work_dir + STATIC_NAME_OBJ
	path_lib			= path_work_dir + STATIC_NAME_LIBRARY
	path_core			= path_work_dir + STATIC_NAME_CORE
	path_res_compile	= path_work_dir + STATIC_NAME_RES_COMPILE
	path_res_run		= path_work_dir + STATIC_NAME_RES_RUN
	path_res_error		= path_work_dir + STATIC_NAME_RES_ERROR
	path_sample			= STATIC_PATH_SAMPLE


	# 0-3. file open - input source code
	db_change_fopt_state( code_idx, "wait" )
	if os.path.isfile( path_code ) == False | os.access( path_code, os.R_OK ) == False:
		sys.exit( '! <' + path_code + "> file read fail" )

	
	# 1. Compiling
	# 1-1. source code exception check
	db_change_fopt_state( code_idx, "compiling" )
	result, output = source_exception_check( path_code )
	if( result == False ):
		file_write( path_res_compile, output )
		db_change_fopt_state( code_idx, "compile error" )
		sys.exit( "! compile error" )

	# 1-2. source code compile - object
	sh_cmd = "gcc -c %s -g -o %s -fPIC" % ( path_code, path_obj )
	exitcode, output = commands.getstatusoutput( sh_cmd )
	file_write( path_res_compile, output )
	if( exitcode != 0 ):
		db_change_fopt_state( code_idx, "compile error" )
		sys.exit( "! compile error" )
	
	# 1-3. source code compile - so library
	sh_cmd = "gcc -shared %s -o %s -g" % ( path_obj, path_lib )
	exitcode, output = commands.getstatusoutput( sh_cmd )
	file_write_append( path_res_compile, output )
	if( exitcode != 0 ):
		db_change_fopt_state( code_idx, "compile error" )
		sys.exit( "! compile error" )
	else:
		file_write_append( path_res_compile, "Compiling Success!!" )


	# 2. run tester( $fec_tester <libname> <dir> <sample> )
	db_change_fopt_state( code_idx, "simulating" )
	sh_cmd = "%s %s %s %s" % ( STATIC_PATH_FEC_TESTER, path_lib, path_work_dir, path_sample )
	exitcode, output = commands.getstatusoutput( sh_cmd )
	file_write(path_res_error, output)

	# 3. runtime error check and exec core_analyzer
	if( exitcode != 0 ):
		sh_cmd = "%s %s" % ( STATIC_PATH_CORE_ANALYZER, path_core )
		exitcode, output = commands.getstatusoutput( sh_cmd )
	#	file_write( path_res_error, output )
		db_change_fopt_state( code_idx, "runtime error" )
	else:
		file_write( path_res_error, "Code Testing Success!!" )
		db_change_fopt_state( code_idx, "complete" )



##################################################################################################
# Entry Point
##################################################################################################
if __name__ == '__main__':
	# exception - program argument
	if len(sys.argv) != 2:
		sys.exit( "Usage : " + sys.argv[0] + " <code_idx>\n" )
	
	# call main function
	main( int(sys.argv[1]) )

