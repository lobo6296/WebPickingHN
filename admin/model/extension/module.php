<?php
class ModelExtensionModule extends Model {
	public function addModule($code, $data) {
		$this->mysql->query("INSERT INTO ` module` SET `name` = '" . $this->mysql->escape($data['name']) . "', `code` = '" . $this->mysql->escape($code) . "', `setting` = '" . $this->mysql->escape(json_encode($data)) . "'");
	}
	
	public function editModule($module_id, $data) {
		$this->mysql->query("UPDATE ` module` SET `name` = '" . $this->mysql->escape($data['name']) . "', `setting` = '" . $this->mysql->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}

	public function deleteModule($module_id) {
		$this->mysql->query("DELETE FROM ` module` WHERE `module_id` = '" . (int)$module_id . "'");
		$this->mysql->query("DELETE FROM ` layout_module` WHERE `code` LIKE '%." . (int)$module_id . "'");
	}
		
	public function getModule($module_id) {
		$query = $this->mysql->query("SELECT * FROM ` module` WHERE `module_id` = '" . $this->mysql->escape($module_id) . "'");

		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return array();	
		}
	}
	
	public function getModules() {
		$query = $this->mysql->query("SELECT * FROM ` module` ORDER BY `code`");

		return $query->rows;
	}	
		
	public function getModulesByCode($code) {
		$query = $this->mysql->query("SELECT * FROM ` module` WHERE `code` = '" . $this->mysql->escape($code) . "' ORDER BY `name`");

		return $query->rows;
	}	
	
	public function deleteModulesByCode($code) {
		$this->mysql->query("DELETE FROM ` module` WHERE `code` = '" . $this->mysql->escape($code) . "'");
		$this->mysql->query("DELETE FROM ` layout_module` WHERE `code` LIKE '" . $this->mysql->escape($code) . "' OR `code` LIKE '" . $this->mysql->escape($code . '.%') . "'");
	}	
}