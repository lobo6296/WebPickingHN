<?php
class ModelCatalogOperacion extends Model {
	
function conectar($cod_ambiente) {
	/*
	  1: PRODUCCION
	  2: DESARROLLO
	*/
	switch ($cod_ambiente) {
		case 1:
   	           $db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);		
		break;
		case 2:
		case 3:
		case 4:
   	           $db = new DB('oracle'
	                          , DB_HOSTNAME_DESA
							  , DB_USERNAME_DESA
							  , DB_PASSWORD_DESA
							  , DB_SERVICE_NAME_DESA
							  , DB_PORT_DESA);	
		break;
 }
	return $db;
}
	
	public function addOperacion($data) {
		
		$query = $this->mysql->query("select nvl(max(cod_operacion),0)+1 correlativo from ws_operacion");
		$cod_operacion = $query->row['CORRELATIVO'];
		
		$this->mysql->query("INSERT INTO ws_operacion 
			                        SET cod_operacion         = " . $cod_operacion. "
									   ,descripcion_operacion = '" . $this->mysql->escape($data['DESCRIPCION_OPERACION']) . "'
									   ,namespace             = '" . $this->mysql->escape($value['NAMESPACE']) . "'
									   ,metodo                = '" . $this->mysql->escape($value['METODO']) . "'
									   ,tipo_metodo           = '" . $this->mysql->escape($value['TIPO_METODO']) . "'
									   ,creador               = '" . $this->mysql->escape($value['CREADOR']) . "'
									   ,activo                = '" . $this->mysql->escape($value['ACTIVO']) . "'");

		return $cod_operacion;
	}

	public function editOperacion($cod_operacion, $data) {

			$this->mysql->query("UPDATE ws_operacion 
			                        SET descripcion_operacion = '" . $this->mysql->escape($data['DESCRIPCION_OPERACION']) . "'
									   ,namespace             = '" . $this->mysql->escape($value['NAMESPACE']) . "'
									   ,metodo                = '" . $this->mysql->escape($value['METODO']) . "'
									   ,tipo_metodo           = '" . $this->mysql->escape($value['TIPO_METODO']) . "'
									   ,creador               = '" . $this->mysql->escape($value['CREADOR']) . "'
									   ,activo                = '" . $this->mysql->escape($value['ACTIVO']) . "'
								  WHERE cod_operacion = ".$cod_operacion);
	}

	public function deleteOperacion($cod_operacion) {
		$query = $this->mysql->query("DELETE FROM ws_operacion WHERE cod_operacion = " . $cod_operacion);
	}

	public function getOperacion($data) {
		$db = $this->conectar($data['filter_cod_ambiente']);
		
		$query = $db->query("SELECT *
		                                FROM ws_operacion
									   WHERE cod_operacion = ".$cod_operacion);
		return $query->rows;
	}

	public function getTotalOperaciones($data) {
		$db = $this->conectar($data['filter_cod_ambiente']);
		 
		$query = $db->query("SELECT count(*) total
		                                FROM ws_operacion");
		return $query->row['TOTAL'];
	}	
	
	public function getOperaciones($data) {
		$sql = "SELECT *
		          FROM ws_operacion
				 WHERE 1=1";

 		$db = $this->conectar($data['filter_cod_ambiente']);
		
		if (isset($data['filter_descripcion_operacion'])) {
			$sql .= " AND descripcion_operacion LIKE '" . $db->escape($data['filter_descripcion_operacion']) . "%'";
		}
		
		if (isset($data['filter_metodo'])) {
			$sql .= " AND metodo LIKE '" . $db->escape($data['filter_metodo']) . "%'";
		}

		if (isset($data['filter_activo'])) {
			$sql .= " AND activo LIKE '" . $db->escape($data['filter_activo']) . "%'";
		}
				
		$sort_data = array(
			'cod_operacion',
			'descripcion_operacion',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
/*
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
*/
		$query = $db->query($sql);

		return $query->rows;
	}

}
