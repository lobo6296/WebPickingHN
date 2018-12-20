<?php
class ModelExtensionModification extends Model {
	public function addModification($data) {
		$this->mysql->query("INSERT INTO  modification SET code = '" . $this->mysql->escape($data['code']) . "', name = '" . $this->mysql->escape($data['name']) . "', author = '" . $this->mysql->escape($data['author']) . "', version = '" . $this->mysql->escape($data['version']) . "', link = '" . $this->mysql->escape($data['link']) . "', xml = '" . $this->mysql->escape($data['xml']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function deleteModification($modification_id) {
		$this->mysql->query("DELETE FROM  modification WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function enableModification($modification_id) {
		$this->mysql->query("UPDATE  modification SET status = '1' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function disableModification($modification_id) {
		$this->mysql->query("UPDATE  modification SET status = '0' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function getModification($modification_id) {
		$query = $this->mysql->query("SELECT * FROM  modification WHERE modification_id = '" . (int)$modification_id . "'");

		return $query->row;
	}

	public function getModifications($data = array()) {
		$sql = "SELECT * FROM  modification";

		$sort_data = array(
			'name',
			'author',
			'version',
			'status',
			'date_added'
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

	public function getTotalModifications() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  modification");

		return $query->row['total'];
	}
	
	public function getModificationByCode($code) {
		$query = $this->mysql->query("SELECT * FROM  modification WHERE code = '" . $this->mysql->escape($code) . "'");

		return $query->row;
	}	
}