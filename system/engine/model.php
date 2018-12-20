<?php
abstract class Model {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	function conectar($cod_ambiente) {
		/*
	  	1: PRODUCCION
	  	2: DESARROLLO
		*/
		switch ($cod_ambiente) {
			case 1: //PRODUCCION CROPA GT
   	        	$db = new DB('oracle'
				, DB_HOSTNAME_PROD
				, DB_USERNAME_PROD
				, DB_PASSWORD_PROD
				, DB_DATABASE_PROD
				, DB_PORT_PROD);		
			break;
			case 4: //DESARROLLO CROPA GT
   	           	$db = new DB('oracle'
				, DB_HOSTNAME_DESA
				, DB_USERNAME_DESA
				, DB_PASSWORD_DESA
				, DB_DATABASE_DESA
				, DB_PORT_DESA);	
			break;
			case 3: //TIGO PRODUCCION SAP
   	           	$db = new DB('oracle'
				, DB_HOSTNAME_PROTIGOSAP
				, DB_USERNAME_PROTIGOSAP
				, DB_PASSWORD_PROTIGOSAP
				, DB_DATABASE_PROTIGOSAP
				, DB_PORT_PROTIGOSAP);	
			break;
			case 2: //TIGO DESARROLLO SAP
   	           	$db = new DB('oracle'
				, DB_HOSTNAME_DESATIGOSAP
				, DB_USERNAME_DESATIGOSAP
				, DB_PASSWORD_DESATIGOSAP
				, DB_DATABASE_DESATIGOSAP
				, DB_PORT_DESATIGOSAP);	
			break;
			case 5: // TIGO PRODUCCION TGU
   	           	$db = new DB('oracle'
				, DB_HOSTNAME_PROTIGOTGU
				, DB_USERNAME_PROTIGOTGU
				, DB_PASSWORD_PROTIGOTGU
				, DB_DATABASE_PROTIGOTGU
				, DB_PORT_PROTIGOTGU);	
			break;
			case 6: // TIGO DESARROLLO TGU
   	           	$db = new DB('oracle'
				, DB_HOSTNAME_DESATIGOTGU
				, DB_USERNAME_DESATIGOTGU
				, DB_PASSWORD_DESATIGOTGU
				, DB_DATABASE_DESATIGOTGU
				, DB_PORT_DESATIGOTGU);	
			break;
		}
		return $db;
	}
}