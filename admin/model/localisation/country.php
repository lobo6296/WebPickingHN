<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$this->mysql->query("INSERT INTO  country SET name = '" . $this->mysql->escape($data['name']) . "', iso_code_2 = '" . $this->mysql->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->mysql->escape($data['iso_code_3']) . "', address_format = '" . $this->mysql->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('country');
		
		return $this->mysql->getLastId();
	}

	public function editCountry($country_id, $data) {
		$this->mysql->query("UPDATE  country SET name = '" . $this->mysql->escape($data['name']) . "', iso_code_2 = '" . $this->mysql->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->mysql->escape($data['iso_code_3']) . "', address_format = '" . $this->mysql->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "' WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function deleteCountry($country_id) {
		$this->mysql->query("DELETE FROM  country WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function getCountry($country_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  country WHERE country_id = '" . (int)$country_id . "'");

		return $query->row;
	}

	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM  country";

			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3'
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
			$country_data = $this->cache->get('country.admin');

			if (!$country_data) {
				$query = $this->mysql->query("SELECT * FROM  country ORDER BY name ASC");

				$country_data = $query->rows;

				$this->cache->set('country.admin', $country_data);
			}

			return $country_data;
		}
	}

	public function getTotalCountries() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  country");

		return $query->row['total'];
	}
}