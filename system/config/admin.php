<?php
// Site
$_['site_base']         = substr(HTTP_SERVER, 7);
$_['site_ssl']          = false;
// Database desarrollo
$_['db_autostart_desa']  = false;
$_['db_type_desa']       = DB_DRIVER_DESA; 
$_['db_hostname_desa']   = DB_HOSTNAME_DESA;
$_['db_username_desa']   = DB_USERNAME_DESA;
$_['db_password_desa']   = DB_PASSWORD_DESA;
$_['db_database_desa']   = DB_DATABASE_DESA;
$_['db_port_desa']       = DB_PORT_DESA;


// Database MySQL
$_['db_autostart_mysql']  = true;
$_['db_type_mysql']       = DB_DRIVER_MySQL; 
$_['db_hostname_mysql']   = DB_HOSTNAME_MySQL;
$_['db_username_mysql']   = DB_USERNAME_MySQL;
$_['db_password_mysql']   = DB_PASSWORD_MySQL;
$_['db_database_mysql']   = DB_DATABASE_MySQL;
$_['db_port_mysql']       = DB_PORT_MySQL;

// Database Produccion
$_['db_autostart_prod']  = false;
$_['db_type_prod']       = DB_DRIVER_PROD; 
$_['db_hostname_prod']   = DB_HOSTNAME_PROD;
$_['db_username_prod']   = DB_USERNAME_PROD;
$_['db_password_prod']   = DB_PASSWORD_PROD;
$_['db_database_prod']   = DB_DATABASE_PROD;
$_['db_port_prod']       = DB_PORT_PROD;

// Session
$_['session_autostart'] = true;

// Actions
$_['action_pre_action']  = array(
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/permission'
);

// Actions
$_['action_default']     = 'common/dashboard';

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
);