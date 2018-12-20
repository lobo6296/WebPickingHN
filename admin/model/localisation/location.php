<?php
class ModelLocalisationLocation extends Model {
	public function addLocation($data) {
		$this->mysql->query("INSERT INTO  location SET name = '" . $this->mysql->escape($data['name']) . "', address = '" . $this->mysql->escape($data['address']) . "', geocode = '" . $this->mysql->escape($data['geocode']) . "', telephone = '" . $this->mysql->escape($data['telephone']) . "', fax = '" . $this->mysql->escape($data['fax']) . "', image = '" . $this->mysql->escape($data['image']) . "', open = '" . $this->mysql->escape($data['open']) . "', comment = '" . $this->mysql->escape($data['comment']) . "'");
	
		return $this->mysql->getLastId();
	}

	public function editLocation($location_id, $data) {
		$this->mysql->query("UPDATE  location SET name = '" . $this->mysql->escape($data['name']) . "', address = '" . $this->mysql->escape($data['address']) . "', geocode = '" . $this->mysql->escape($data['geocode']) . "', telephone = '" . $this->mysql->escape($data['telephone']) . "', fax = '" . $this->mysql->escape($data['fax']) . "', image = '" . $this->mysql->escape($data['image']) . "', open = '" . $this->mysql->escape($data['open']) . "', comment = '" . $this->mysql->escape($data['comment']) . "' WHERE location_id = '" . (int)$location_id . "'");
	}

	public function deleteLocation($location_id) {
		$this->mysql->query("DELETE FROM  location WHERE location_id = " . (int)$location_id);
	}

	public function getLocation($location_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}

	public function getLocations($data = array()) {
		$sql = "SELECT location_id, name, address FROM  location";

		$sort_data = array(
			'name',
			'address',
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

	public function getTotalLocations() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  location");

		return $query->row['total'];
	}
}
