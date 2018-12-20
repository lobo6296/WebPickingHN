<?php
class ModelExtensionExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->mysql->query("SELECT * FROM  extension WHERE `type` = '" . $this->mysql->escape($type) . "' ORDER BY code");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}

	public function install($type, $code) {
		$this->mysql->query("INSERT INTO  extension SET `type` = '" . $this->mysql->escape($type) . "', `code` = '" . $this->mysql->escape($code) . "'");
	}

	public function uninstall($type, $code) {
		$this->mysql->query("DELETE FROM  extension WHERE `type` = '" . $this->mysql->escape($type) . "' AND `code` = '" . $this->mysql->escape($code) . "'");
		$this->mysql->query("DELETE FROM  setting WHERE `code` = '" . $this->mysql->escape($code) . "'");
	}
}