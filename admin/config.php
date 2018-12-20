<?php
// HTTP
define('HTTP_SERVER', 'http://192.168.10.17/webpicking/admin/');
// HTTPS
define('HTTPS_SERVER', 'https://192.168.10.17/webpicking/admin/');

// DIR
define('BASE_DIR', str_replace('\\','/',realpath(dirname(__FILE__))).'/');

// DIR                      /var/www/html/sis/     
define('PHPEXCEL_ROOT',    '/var/www/html/sis/webpicking/system/PHPExcel/Classes/');
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

// DB ORACLE DESARROLLO CROPA GUATEMALA Z13
define('DB_DRIVER_DESA'  , 'oracle');
define('DB_HOSTNAME_DESA', '192.168.10.18');
define('DB_USERNAME_DESA', 'CROPA');
define('DB_PASSWORD_DESA', 'pruebas');
define('DB_DATABASE_DESA', 'dbprueba');
define('DB_PORT_DESA','1521');
define('DB_PREFIX_DESA'  , '');

// PRODUCCION 192.168.1.250
define('DB_DRIVER_DESATIGO'  , 'oracle');
define('DB_HOSTNAME_DESATIGO', '192.168.1.250');
define('DB_USERNAME_DESATIGO', 'tigo');
define('DB_PASSWORD_DESATIGO', 't160t6u');
define('DB_DATABASE_DESATIGO', 'XE');
define('DB_PORT_DESATIGO','1521');
define('DB_PREFIX_DESATIGO'  , '');


// DB ORACLE PRODUCCION CROPA GUATEMALA Z13
define('DB_DRIVER_PROD'  , 'oracle');
define('DB_HOSTNAME_PROD', '192.168.10.209');
define('DB_USERNAME_PROD', 'CROPA');
define('DB_PASSWORD_PROD', 'pr0ducc10n');
define('DB_DATABASE_PROD', 'DBCROPA');
define('DB_PORT_PROD','1521');
define('DB_PREFIX_PROD'  , '');

// DB ORACLE TIGO SAP DESARROLLO 
define('DB_DRIVER_DESATIGOSAP'  , 'oracle');
define('DB_HOSTNAME_DESATIGOSAP', '192.168.0.21');
define('DB_USERNAME_DESATIGOSAP', 'tigo');
define('DB_PASSWORD_DESATIGOSAP', 'tigosap');
define('DB_DATABASE_DESATIGOSAP', 'XE');
define('DB_PORT_DESATIGOSAP','1521');
define('DB_PREFIX_DESATIGOSAP'  , '');

// DB ORACLE TIGO SAP PRODUCCION 
define('DB_DRIVER_PROTIGOSAP'  , 'oracle');
define('DB_HOSTNAME_PROTIGOSAP', '192.168.2.155');
define('DB_USERNAME_PROTIGOSAP', 'tigo');
define('DB_PASSWORD_PROTIGOSAP', 'tigosap');
define('DB_DATABASE_PROTIGOSAP', 'XE');
define('DB_PORT_PROTIGOSAP','1521');
define('DB_PREFIX_PROTIGOSAP'  , '');

// DB ORACLE TIGO TGU DESARROLLO NO HAY DE PRUEBA
define('DB_DRIVER_DESATIGOTGU'  , 'oracle');
define('DB_HOSTNAME_DESATIGOTGU', '192.168.0.21');
define('DB_USERNAME_DESATIGOTGU', 'tigo');
define('DB_PASSWORD_DESATIGOTGU', 'tigosap');
define('DB_DATABASE_DESATIGOTGU', 'XE');
define('DB_PORT_DESATIGOTGU','1521');
define('DB_PREFIX_DESATIGOTGU'  , '');

// DB ORACLE TIGO TGU PRODUCCION 
define('DB_DRIVER_PROTIGOTGU'  , 'oracle');
define('DB_HOSTNAME_PROTIGOTGU', '192.168.1.250');
define('DB_USERNAME_PROTIGOTGU', 'tigo');
define('DB_PASSWORD_PROTIGOTGU', 't160t6u');
define('DB_DATABASE_PROTIGOTGU', 'XE');
define('DB_PORT_PROTIGOTGU','1521');
define('DB_PREFIX_PROTIGOTGU'  , '');