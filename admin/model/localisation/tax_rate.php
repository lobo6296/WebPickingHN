<?php
class ModelLocalisationTaxRate extends Model {
	public function addTaxRate($data) {
		$this->mysql->query("INSERT INTO  tax_rate SET name = '" . $this->mysql->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->mysql->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_added = NOW(), date_modified = NOW()");

		$tax_rate_id = $this->mysql->getLastId();

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->mysql->query("INSERT INTO  tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
		
		return $tax_rate_id;
	}

	public function editTaxRate($tax_rate_id, $data) {
		$this->mysql->query("UPDATE  tax_rate SET name = '" . $this->mysql->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->mysql->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_modified = NOW() WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		$this->mysql->query("DELETE FROM  tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->mysql->query("INSERT INTO  tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
	}

	public function deleteTaxRate($tax_rate_id) {
		$this->mysql->query("DELETE FROM  tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
		$this->mysql->query("DELETE FROM  tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
	}

	public function getTaxRate($tax_rate_id) {
		$query = $this->mysql->query("SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM  tax_rate tr LEFT JOIN  geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = '" . (int)$tax_rate_id . "'");

		return $query->row;
	}

	public function getTaxRates($data = array()) {
		$sql = "SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM  tax_rate tr LEFT JOIN  geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id)";

		$sort_data = array(
			'tr.name',
			'tr.rate',
			'tr.type',
			'gz.name',
			'tr.date_added',
			'tr.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY tr.name";
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

	public function getTaxRateCustomerGroups($tax_rate_id) {
		$tax_customer_group_data = array();

		$query = $this->mysql->query("SELECT * FROM  tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		foreach ($query->rows as $result) {
			$tax_customer_group_data[] = $result['customer_group_id'];
		}

		return $tax_customer_group_data;
	}

	public function getTotalTaxRates() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  tax_rate");

		return $query->row['total'];
	}

	public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		return $query->row['total'];
	}
}