<?php
// HTTP
define('HTTP_SERVER', 'http://192.168.10.17/upload/admin/');
// HTTPS
define('HTTPS_SERVER', 'https://192.168.10.17/upload/admin/');

// DIR
define('BASE_DIR', str_replace('\\','/',realpath(dirname(__FILE__))).'/');

// DIR                      /var/www/html/sis/     
define('PHPEXCEL_ROOT',    '/var/www/html/sis/upload/system/library/PHPExcel/');
define('DIR_RESOURCES',    '/var/www/html/sis/upload/');
define('DIR_APPLICATION',  '/var/www/html/sis/upload/admin/');
define('DIR_SYSTEM',       '/var/www/html/sis/upload/system/');
define('DIR_IMAGE',        '/var/www/html/sis/upload/image/');
define('DIR_LANGUAGE',     '/var/www/html/sis/upload/admin/language/');
define('DIR_TEMPLATE',     '/var/www/html/sis/upload/admin/view/template/');
define('DIR_CONFIG',       '/var/www/html/sis/upload/system/config/');
define('DIR_CACHE',        '/var/www/html/sis/upload/system/storage/cache/');
define('DIR_DOWNLOAD',     '/var/www/html/sis/upload/system/storage/download/');
define('DIR_LOGS',         '/var/www/html/sis/upload/system/storage/logs/');
define('DIR_MODIFICATION', '/var/www/html/sis/upload/system/storage/modification/');
define('DIR_UPLOAD',       '/var/www/html/sis/upload/system/storage/upload/');
define('DIR_CATALOG',      '/var/www/html/sis/upload/catalog/');

// DB MySQL
define('DB_DRIVER_MySQL',   'mysqli');
define('DB_HOSTNAME_MySQL', '172.22.116.77');
define('DB_USERNAME_MySQL', 'testing');
define('DB_PASSWORD_MySQL', 'T35t1n$99');
define('DB_DATABASE_MySQL', 'testing');
define('DB_PORT_MySQL',     '3306');
define('DB_PREFIX_MySQL',   '');

// DB ORACLE DESARROLLO
define('DB_DRIVER_DESA'  , 'oracle');
define('DB_HOSTNAME_DESA', '192.168.10.18');
define('DB_USERNAME_DESA', 'DWH');
define('DB_PASSWORD_DESA', 'DWH');
define('DB_SERVICE_NAME_DESA', 'Dbprueba');
define('DB_PORT_DESA','1521');

// DB ORACLE PRODUCCION - 172.30.4.213
define('DB_DRIVER_PROD'  , 'oracle');
define('DB_HOSTNAME_PROD', '172.22.58.172');
define('DB_USERNAME_PROD', 'WAP');
define('DB_PASSWORD_PROD', 'WAP!1MC3L');
define('DB_DATABASE_PROD', 'INHOUSE');
define('DB_PORT_PROD','1521');
define('DB_PREFIX_PROD'  , '');
