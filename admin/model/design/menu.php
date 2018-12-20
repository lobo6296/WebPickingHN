<?php
class ModelDesignMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO  menu SET store_id = '" . (int)$data['store_id'] . "', type = '" .  $this->db->escape($data['type']) . "', link = '" .  $this->db->escape($data['link']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");
	
		$menu_id = $this->db->getLastId();
	
		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO  menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}	
	}

	public function obtenerMenuRecursivo($cod_menu,$arreglo) {	
	$menu = array(); 
	foreach ($arreglo as $result) {
	//echo $cod_menu." ".$result['cod_menu_padre']." ".$permiso."<br>";	
	if ($result['cod_menu_padre'] == $cod_menu) {
	
	$permiso=$this->user->hasPermission('access',$result['href']);
	
	if (empty($result['href'])) {$href=null;}
	else {$href=$this->url->link($result['href'], 'token=' . $this->session->data['token'], true);}
	
	//if (!($result['tipo']=='O'/*&&empty($permiso)*/)) {
	$menu[] = array (
	         'cod_menu'    => $result['cod_menu'],
			 'tipo'        => $result['tipo'],
			 'estado'      => $result['estado'],
			 'permiso'     => (empty($permiso)) ? 0 : $permiso,
			 'id'          => $result['id'],
			 'icon'        => $result['icon'],
			 'name'        => $this->language->get($result['name']),			 
			 'href'        => $href,
			 'children'    => $result['tipo']=='M' ? $this->obtenerMenuRecursivo($result['cod_menu'],$arreglo) : array() 
	   );
	 }
	}
	  return $menu;	
	}
	
	public function obtenerMenu() {
	$query = $this->mysql->query("select * from menu WHERE estado='A' order by cod_menu");		

	foreach ($query->rows as $result) {
	$arreglo[] = array (
	         'cod_menu'       => $result['cod_menu'],
			 'name'           => $result['nombre_menu'],
			 'cod_menu_padre' => $result['cod_menu_padre'],
			 'id'             => $result['id'],
			 'tipo'           => $result['tipo'],
			 'estado'         => $result['estado'],
			 'icon'           => $result['icon'],
			 'href'           => $result['href'] 
	   );
	}	
   
     $menu = $this->obtenerMenuRecursivo(1,$arreglo);
	 //exit(0);
     return $menu;
	}
	
	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE  menu SET store_id = '" . (int)$data['store_id'] . "', type = '" .  $this->db->escape($data['type']) . "', link = '" .  $this->db->escape($data['link']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE menu_id = '" . (int)$menu_id . "'");

		$this->db->query("DELETE FROM  menu_description WHERE menu_id = '" . (int)$menu_id . "'");

		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO  menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM  menu_module WHERE menu_id = '" . (int)$menu_id . "'");
		
		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO  menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}		
	}

	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM  menu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM  menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM  menu_module WHERE menu_id = '" . (int)$menu_id . "'");
	}

	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM  menu WHERE menu_id = '" . (int)$menu_id . "'");

		return $query->row;
	}

	public function getMenus($data = array()) {
		$sql = "SELECT * FROM  menu m LEFT JOIN  menu_description md ON(m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'md.name',
			'store',
			'm.sort_order',
			'm.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY md.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getMenuDescriptions($menu_id) {
		$menu_description_data = array();

		$query = $this->db->query("SELECT * FROM  menu_description WHERE menut_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $menu_description_data;
	}

	public function getTotalMenus() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM  menu");

		return $query->row['total'];
	}
}
