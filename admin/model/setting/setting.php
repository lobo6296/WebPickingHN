<?php
class ModelSettingSetting extends Model {
	public function getSetting($code, $store_id = 0) {
		$setting_data = array();

		$query = $this->mysql->query("SELECT * FROM testing.setting WHERE store_id = ".(int)$store_id." AND `code` = '" . $this->mysql->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $setting_data;
	}

	public function editSetting($code, $data, $store_id = 0) {
		$this->mysql->query("DELETE FROM ` setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->mysql->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->mysql->query("INSERT INTO  setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->mysql->escape($code) . "', `key` = '" . $this->mysql->escape($key) . "', `value` = '" . $this->mysql->escape($value) . "'");
				} else {
					$this->mysql->query("INSERT INTO  setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->mysql->escape($code) . "', `key` = '" . $this->mysql->escape($key) . "', `value` = '" . $this->mysql->escape(json_encode($value, true)) . "', serialized = '1'");
				}
			}
		}
	}

	public function deleteSetting($code, $store_id = 0) {
		$this->mysql->query("DELETE FROM  setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->mysql->escape($code) . "'");
	}
	
	public function getSettingValue($key, $store_id = 0) {
		$query = $this->mysql->query("SELECT value 
		                             FROM setting 
									WHERE `key` = '" . $this->mysql->escape($key) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return null;	
		}
	}
	
	public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->mysql->query("UPDATE  setting SET `value` = '" . $this->mysql->escape($value) . "', serialized = '0'  WHERE `code` = '" . $this->mysql->escape($code) . "' AND `key` = '" . $this->mysql->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->mysql->query("UPDATE  setting SET `value` = '" . $this->mysql->escape(json_encode($value)) . "', serialized = '1' WHERE `code` = '" . $this->mysql->escape($code) . "' AND `key` = '" . $this->mysql->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		}
	}
}
