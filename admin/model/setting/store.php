<?php
class ModelSettingStore extends Model {
	public function addStore($data) {
		$this->mysql->query("INSERT INTO  store SET name = '" . $this->mysql->escape($data['config_name']) . "', `url` = '" . $this->mysql->escape($data['config_url']) . "', `ssl` = '" . $this->mysql->escape($data['config_ssl']) . "'");

		$store_id = $this->mysql->getLastId();

		// Layout Route
		$query = $this->mysql->query("SELECT * FROM  layout_route WHERE store_id = '0'");

		foreach ($query->rows as $layout_route) {
			$this->mysql->query("INSERT INTO  layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->mysql->escape($layout_route['route']) . "', store_id = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		return $store_id;
	}

	public function editStore($store_id, $data) {
		$this->mysql->query("UPDATE  store SET name = '" . $this->mysql->escape($data['config_name']) . "', `url` = '" . $this->mysql->escape($data['config_url']) . "', `ssl` = '" . $this->mysql->escape($data['config_ssl']) . "' WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function deleteStore($store_id) {
		$this->mysql->query("DELETE FROM  store WHERE store_id = '" . (int)$store_id . "'");
		$this->mysql->query("DELETE FROM  layout_route WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function getStore($store_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  store WHERE store_id = '" . (int)$store_id . "'");

		return $query->row;
	}

	public function getStores($data = array()) {
			$query = $this->mysql->query("SELECT * FROM store ORDER BY store_id");
			$store_data = $query->rows;
		return $store_data;
	}

	public function getTotalStores() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM store");

		return $query->row['total'];
	}

	public function getTotalStoresByLayoutId($layout_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByLanguage($language) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_language' AND `value` = '" . $this->mysql->escape($language) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCurrency($currency) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_currency' AND `value` = '" . $this->mysql->escape($currency) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCountryId($country_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByZoneId($zone_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByInformationId($information_id) {
		$account_query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		$checkout_query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  setting WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND store_id != '0'");

		return $query->row['total'];
	}
}