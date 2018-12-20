<?php
// HTTP
define('HTTP_SERVER', 'http://192.168.10.17/webpicking/admin/');
// HTTPS
define('HTTPS_SERVER', 'https://192.168.10.17/webpicking/admin/');

// DIR
define('BASE_DIR', str_replace('\\','/',realpath(dirname(__FILE__))).'/');

// DIR                      /var/www/html/sis/     
define('PHPEXCEL_ROOT',    '/var/www/html/sis/webpicking/system/library/PHPExcel/');
define('DIR_RESOURCES',    '/var/www/html/sis/webpicking/');
define('DIR_APPLICATION',  '/var/www/html/sis/webpicking/admin/');
define('DIR_SYSTEM',       '/var/www/html/sis/webpicking/system/');
define('DIR_IMAGE',        '/var/www/html/sis/webpicking/image/');
define('DIR_LANGUAGE',     '/var/www/html/sis/webpicking/admin/language/');
define('DIR_TEMPLATE',     '/var/www/html/sis/webpicking/admin/view/template/');
define('DIR_CONFIG',       '/var/www/html/sis/webpicking/system/config/');
define('DIR_CACHE',        '/var/www/html/sis/webpicking/system/storage/cache/');
define('DIR_DOWNLOAD',     '/var/www/html/sis/webpicking/system/storage/download/');
define('DIR_LOGS',         '/var/www/html/sis/webpicking/system/storage/logs/');
define('DIR_MODIFICATION', '/var/www/html/sis/webpicking/system/storage/modification/');
define('DIR_UPLOAD',       '/var/www/html/sis/webpicking/system/storage/webpicking/');
define('DIR_CATALOG',      '/var/www/html/sis/webpicking/catalog/');

// DB MySQL
define('DB_DRIVER_MySQL',   'mysqli');
define('DB_HOSTNAME_MySQL', '192.168.10.238');
define('DB_USERNAME_MySQL', 'produc');
define('DB_PASSWORD_MySQL', 'Cropa,18%');
define('DB_DATABASE_MySQL', 'webpicking');
define('DB_PORT_MySQL',     '3306');
define('DB_PREFIX_MySQL',   '');

// DB ORACLE DESARROLLO
define('DB_DRIVER_DESA'  , 'oracle');
define('DB_HOSTNAME_DESA', '192.168.10.18');
define('DB_USERNAME_DESA', 'DWH');
define('DB_PASSWORD_DESA', 'DWH');
define('DB_SERVICE_NAME_DESA', 'Dbprueba');
define('DB_PORT_DESA','1521');

// DB ORACLE PRODUCCION 
define('DB_DRIVER_PROD'  , 'oracle');
define('DB_HOSTNAME_PROD', '192.168.10.209');
define('DB_USERNAME_PROD', 'CROPA');
define('DB_PASSWORD_PROD', 'pr0ducc10n');
define('DB_DATABASE_PROD', 'DBCROPA');
define('DB_PORT_PROD','1521');
define('DB_PREFIX_PROD'  , '');

// DB ORACLE TIGO SAP DESARROLLO 
define('DB_DRIVER_DESATIGO'  , 'oracle');
define('DB_HOSTNAME_DESATIGO', '192.168.0.21');
define('DB_USERNAME_DESATIGO', 'tigo');
define('DB_PASSWORD_DESATIGO', 'tigosap');
define('DB_DATABASE_DESATIGO', 'XE');
define('DB_PORT_DESATIGO','1521');
define('DB_PREFIX_DESATIGO'  , '');