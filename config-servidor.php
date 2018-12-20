<?php
// HTTP
define('HTTP_SERVER', 'http://172.22.116.77/upload/admin/');
// HTTPS
define('HTTPS_SERVER', 'https://localhost/upload/admin/');
// DIR
define('BASE_DIR', str_replace('\\','/',realpath(dirname(__FILE__))).'/');

// DIR
define('PHPEXCEL_ROOT',    '/apache-tomcat-wstest/webapps/ROOT/upload/system/library/PHPExcel/');
define('DIR_APPLICATION',  '/apache-tomcat-wstest/webapps/ROOT/upload/admin/');
define('DIR_SYSTEM',       '/apache-tomcat-wstest/webapps/ROOT/upload/system/');
define('DIR_IMAGE',        '/apache-tomcat-wstest/webapps/ROOT/upload/image/');
define('DIR_LANGUAGE',     '/apache-tomcat-wstest/webapps/ROOT/upload/admin/language/');
define('DIR_TEMPLATE',     '/apache-tomcat-wstest/webapps/ROOT/upload/admin/view/template/');
define('DIR_CONFIG',       '/apache-tomcat-wstest/webapps/ROOT/upload/system/config/');
define('DIR_CACHE',        '/apache-tomcat-wstest/webapps/ROOT/upload/system/storage/cache/');
define('DIR_DOWNLOAD',     '/apache-tomcat-wstest/webapps/ROOT/upload/system/storage/download/');
define('DIR_LOGS',         '/apache-tomcat-wstest/webapps/ROOT/upload/system/storage/logs/');
define('DIR_MODIFICATION', '/apache-tomcat-wstest/webapps/ROOT/upload/system/storage/modification/');
define('DIR_UPLOAD',       '/apache-tomcat-wstest/webapps/ROOT/upload/system/storage/upload/');
define('DIR_CATALOG',      '/apache-tomcat-wstest/webapps/ROOT/upload/catalog/');

// DB MySQL
define('DB_DRIVER_MySQL',   'mysqli');
define('DB_HOSTNAME_MySQL', '172.22.116.77');
define('DB_USERNAME_MySQL', 'testing');
define('DB_PASSWORD_MySQL', 'T35t1n$99');
define('DB_DATABASE_MySQL', 'testing');
define('DB_PORT_MySQL',     '3306');
define('DB_PREFIX_MySQL',   '');

// DB Oracle Testing Local
define('DB_DRIVER_TEST'  , 'oracle');
define('DB_HOSTNAME_TEST', 'localhost');
define('DB_USERNAME_TEST', 'JOSE');
define('DB_PASSWORD_TEST', 'JOSE');
define('DB_DATABASE_TEST', 'XE');
define('DB_PORT_TEST','1521');
define('DB_PREFIX_TEST'  , '');

// DB ORACLE DESARROLLO
define('DB_DRIVER_DESA'  , 'oracle');
define('DB_HOSTNAME_DESA', '172.22.52.225');
define('DB_USERNAME_DESA', 'WAP');
define('DB_PASSWORD_DESA', 'WAP!1MC3L');
define('DB_SERVICE_NAME_DESA', 'PLUSDB');
define('DB_PORT_DESA','1521');

// DB ORACLE PRODUCCION - 172.30.4.213
define('DB_DRIVER_PROD'  , 'oracle');
define('DB_HOSTNAME_PROD', '172.22.58.172');
define('DB_USERNAME_PROD', 'WAP');
define('DB_PASSWORD_PROD', 'WAP!1MC3L');
define('DB_DATABASE_PROD', 'INHOUSE');
define('DB_PORT_PROD','1521');
define('DB_PREFIX_PROD'  , '');

// DB ORACLE DRP_PLAZA
define('DB_DRIVER'  , 'oracle');
define('DB_HOSTNAME', '172.22.58.172');
define('DB_USERNAME', 'WAP');
define('DB_PASSWORD', 'WAP!1MC3L');
define('DB_DATABASE', 'INHOUSE');
define('DB_PORT','1521');
define('DB_PREFIX'  , '');

