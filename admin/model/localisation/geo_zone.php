<?php
class ModelLocalisationGeoZone extends Model {
	public function addGeoZone($data) {
		$this->mysql->query("INSERT INTO  geo_zone SET name = '" . $this->mysql->escape($data['name']) . "', description = '" . $this->mysql->escape($data['description']) . "', date_added = NOW()");

		$geo_zone_id = $this->mysql->getLastId();

		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->mysql->query("DELETE FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$value['country_id'] . "' AND zone_id = '" . (int)$value['zone_id'] . "'");				

				$this->mysql->query("INSERT INTO  zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
			}
		}

		$this->cache->delete('geo_zone');
		
		return $geo_zone_id;
	}

	public function editGeoZone($geo_zone_id, $data) {
		$this->mysql->query("UPDATE  geo_zone SET name = '" . $this->mysql->escape($data['name']) . "', description = '" . $this->mysql->escape($data['description']) . "', date_modified = NOW() WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		$this->mysql->query("DELETE FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->mysql->query("DELETE FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' AND country_id = '" . (int)$value['country_id'] . "' AND zone_id = '" . (int)$value['zone_id'] . "'");				

				$this->mysql->query("INSERT INTO  zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
			}
		}

		$this->cache->delete('geo_zone');
	}

	public function deleteGeoZone($geo_zone_id) {
		$this->mysql->query("DELETE FROM  geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		$this->mysql->query("DELETE FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		$this->cache->delete('geo_zone');
	}

	public function getGeoZone($geo_zone_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		return $query->row;
	}

	public function getGeoZones($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM  geo_zone";

			$sort_data = array(
				'name',
				'description'
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
		} else {
			$geo_zone_data = $this->cache->get('geo_zone');

			if (!$geo_zone_data) {
				$query = $this->mysql->query("SELECT * FROM  geo_zone ORDER BY name ASC");

				$geo_zone_data = $query->rows;

				$this->cache->set('geo_zone', $geo_zone_data);
			}

			return $geo_zone_data;
		}
	}

	public function getTotalGeoZones() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  geo_zone");

		return $query->row['total'];
	}

	public function getZoneToGeoZones($geo_zone_id) {
		$query = $this->mysql->query("SELECT * FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		return $query->rows;
	}

	public function getTotalZoneToGeoZoneByGeoZoneId($geo_zone_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalZoneToGeoZoneByCountryId($country_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  zone_to_geo_zone WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}

	public function getTotalZoneToGeoZoneByZoneId($zone_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  zone_to_geo_zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}
}