<?php
// HTTP
define('HTTP', $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');
define('HTTP_SERVER', 'http://localhost/upload/admin/');
define('HTTP_CATALOG','http://localhost/upload/');
//define('HTTP_SERVER', 'http://'.HTTP);
//define('HTTP_IMAGE', 'http://'.HTTP.'image/');
//define('HTTP_ADMIN', 'http://'.HTTP.'admin/');

// HTTPS
define('HTTPS_SERVER', 'https://localhost/upload/admin/');
define('HTTPS_CATALOG', 'https://localhost/upload/');

// DIR
define('BASE_DIR', str_replace('\\','/',realpath(dirname(__FILE__))).'/');
define('PHPEXCEL_ROOT',BASE_DIR.'/system/library/PHPExcel/');

/*
define('DIR_APPLICATION', BASE_DIR.'/catalog/');
define('DIR_SYSTEM', BASE_DIR.'/system/');
define('DIR_DATABASE', BASE_DIR.'/system/database/');
define('DIR_LANGUAGE', BASE_DIR.'/catalog/language/');
define('DIR_TEMPLATE', BASE_DIR.'/catalog/view/theme/');
define('DIR_CONFIG', BASE_DIR.'/system/config/');
define('DIR_IMAGE', BASE_DIR.'/image/');
define('DIR_CACHE', BASE_DIR.'/system/cache/');
define('DIR_DOWNLOAD', BASE_DIR.'/download/');
define('DIR_LOGS', BASE_DIR.'/system/logs/');
*/

// DIR
define('DIR_APPLICATION',  BASE_DIR);
define('DIR_SYSTEM',       'C:/xampp/htdocs/upload/system/');
define('DIR_IMAGE',        'C:/xampp/htdocs/upload/image/');
define('DIR_LANGUAGE',     BASE_DIR.'language/');
define('DIR_TEMPLATE',     BASE_DIR.'view/template/');
define('DIR_CONFIG',       'C:/xampp/htdocs/upload/system/config/');
define('DIR_CACHE',        'C:/xampp/htdocs/upload/system/storage/cache/');
define('DIR_DOWNLOAD',     'C:/xampp/htdocs/upload/system/storage/download/');
define('DIR_LOGS',         'C:/xampp/htdocs/upload/system/storage/logs/');
define('DIR_MODIFICATION', 'C:/xampp/htdocs/upload/system/storage/modification/');
define('DIR_UPLOAD',       'C:/xampp/htdocs/upload/system/storage/upload/');
define('DIR_CATALOG',      'C:/xampp/htdocs/upload/catalog/');
define('DIR_RESOURCES', str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/');

// DB MySQL
define('DB_DRIVER_MySQL',   'mysqli');
define('DB_HOSTNAME_MySQL', 'localhost');
define('DB_USERNAME_MySQL', 'jose');
define('DB_PASSWORD_MySQL', 'jose');
define('DB_DATABASE_MySQL', 'reportes');
define('DB_PORT_MySQL',     '3306');
define('DB_PREFIX_MySQL',   '');

// DB Oracle Testing Local
define('DB_DRIVER_TEST'  , 'oracle');
define('DB_HOSTNAME_TEST', 'localhost');
define('DB_USERNAME_TEST', 'CROPA');
define('DB_PASSWORD_TEST', 'JOSE');
define('DB_DATABASE_TEST', 'XE');
define('DB_PORT_TEST','1521');
define('DB_PREFIX_TEST'  , '');

// DB ORACLE DESARROLLO
define('DB_DRIVER_DESA'  , 'oracle');
define('DB_HOSTNAME_DESA', '192.168.10.18');
define('DB_USERNAME_DESA', 'DWH');
define('DB_PASSWORD_DESA', 'DWH');
define('DB_SERVICE_NAME_DESA', 'Dbprueba');
define('DB_PORT_DESA','1521');

// DB ORACLE PRODUCCION - 172.30.4.213
define('DB_DRIVER_PROD'  , 'oracle');
define('DB_HOSTNAME_PROD', 'localhost');
define('DB_USERNAME_PROD', 'cropa');
define('DB_PASSWORD_PROD', 'jose');
define('DB_DATABASE_PROD', 'XE');
define('DB_PORT_PROD','1521');
define('DB_PREFIX_PROD'  , '');
