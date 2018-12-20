<?php
class ModelUserApi extends Model {
	public function addApi($data) {
		$this->mysql->query("INSERT INTO ` api` SET name = '" . $this->mysql->escape($data['name']) . "', `key` = '" . $this->mysql->escape($data['key']) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

		$api_id = $this->mysql->getLastId();

		if (isset($data['api_ip'])) {
			foreach ($data['api_ip'] as $ip) {
				if ($ip) {
					$this->mysql->query("INSERT INTO ` api_ip` SET api_id = '" . (int)$api_id . "', ip = '" . $this->mysql->escape($ip) . "'");
				}
			}
		}
		
		return $api_id;
	}

	public function editApi($api_id, $data) {
		$this->mysql->query("UPDATE ` api` SET name = '" . $this->mysql->escape($data['name']) . "', `key` = '" . $this->mysql->escape($data['key']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE api_id = '" . (int)$api_id . "'");

		$this->mysql->query("DELETE FROM  api_ip WHERE api_id = '" . (int)$api_id . "'");

		if (isset($data['api_ip'])) {
			foreach ($data['api_ip'] as $ip) {
				if ($ip) {
					$this->mysql->query("INSERT INTO ` api_ip` SET api_id = '" . (int)$api_id . "', ip = '" . $this->mysql->escape($ip) . "'");
				}
			}
		}
	}

	public function deleteApi($api_id) {
		$this->mysql->query("DELETE FROM ` api` WHERE api_id = '" . (int)$api_id . "'");
	}

	public function getApi($api_id) {
		$query = $this->mysql->query("SELECT * FROM ` api` WHERE api_id = '" . (int)$api_id . "'");

		return $query->row;
	}

	public function getApis($data = array()) {
		$sql = "SELECT * FROM ` api`";

		$sort_data = array(
			'name',
			'status',
			'date_added',
			'date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

		$query = $this->mysql->query($sql);

		return $query->rows;
	}

	public function getTotalApis() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` api`");

		return $query->row['total'];
	}

	public function addApiIp($api_id, $ip) {
		$this->mysql->query("INSERT INTO ` api_ip` SET api_id = '" . (int)$api_id . "', ip = '" . $this->mysql->escape($ip) . "'");
	}

	public function getApiIps($api_id) {
		$query = $this->mysql->query("SELECT * FROM ` api_ip` WHERE api_id = '" . (int)$api_id . "'");

		return $query->rows;
	}

	public function getApiSessions($api_id) {
		$query = $this->mysql->query("SELECT * FROM ` api_session` WHERE api_id = '" . (int)$api_id . "'");

		return $query->rows;
	}

	public function addApiSession($api_id, $data) {
		$this->mysql->query("INSERT INTO ` api_session` SET api_id = '" . (int)$api_id . "', token = '" . $this->mysql->escape($data['token']) . "', date_added = NOW(), date_modified = NOW()");
	}

	public function deleteApiSession($api_session_id) {
		$this->mysql->query("DELETE FROM ` api_session` WHERE api_session_id = '" . (int)$api_session_id . "'");
	}
}
