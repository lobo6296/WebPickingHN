<?php 
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {
		$this->mysql->query("INSERT INTO  language SET name = '" . $this->mysql->escape($data['name']) . "', code = '" . $this->mysql->escape($data['code']) . "', locale = '" . $this->mysql->escape($data['locale']) . "', sort_order = '" . $this->mysql->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('language');

		$language_id = $this->mysql->getLastId();

		// Attribute
		$query = $this->mysql->query("SELECT * FROM  attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute) {
			$this->mysql->query("INSERT INTO  attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($attribute['name']) . "'");
		}

		// Attribute Group
		$query = $this->mysql->query("SELECT * FROM  attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute_group) {
			$this->mysql->query("INSERT INTO  attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($attribute_group['name']) . "'");
		}

		$this->cache->delete('attribute');

		// Banner
		$query = $this->mysql->query("SELECT * FROM  banner_image WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $banner_image) {
			$this->mysql->query("INSERT INTO  banner_image SET banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($banner_image['title']) . "', link = '" . $this->mysql->escape($banner_image['link']) . "', image = '" . $this->mysql->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
		}

		$this->cache->delete('banner');

		// Category
		$query = $this->mysql->query("SELECT * FROM  category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $category) {
			$this->mysql->query("INSERT INTO  category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($category['name']) . "', description = '" . $this->mysql->escape($category['description']) . "', meta_title = '" . $this->mysql->escape($category['meta_title']) . "', meta_description = '" . $this->mysql->escape($category['meta_description']) . "', meta_keyword = '" . $this->mysql->escape($category['meta_keyword']) . "'");
		}

		$this->cache->delete('category');

		// Customer Group
		$query = $this->mysql->query("SELECT * FROM  customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $customer_group) {
			$this->mysql->query("INSERT INTO  customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($customer_group['name']) . "', description = '" . $this->mysql->escape($customer_group['description']) . "'");
		}

		// Custom Field
		$query = $this->mysql->query("SELECT * FROM  custom_field_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $custom_field) {
			$this->mysql->query("INSERT INTO  custom_field_description SET custom_field_id = '" . (int)$custom_field['custom_field_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($custom_field['name']) . "'");
		}

		// Custom Field Value
		$query = $this->mysql->query("SELECT * FROM  custom_field_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $custom_field_value) {
			$this->mysql->query("INSERT INTO  custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_value['custom_field_id'] . "', name = '" . $this->mysql->escape($custom_field_value['name']) . "'");
		}

		// Download
		$query = $this->mysql->query("SELECT * FROM  download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $download) {
			$this->mysql->query("INSERT INTO  download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($download['name']) . "'");
		}

		// Filter
		$query = $this->mysql->query("SELECT * FROM  filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter) {
			$this->mysql->query("INSERT INTO  filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_cod_ambiente_id = '" . (int)$filter['filter_cod_ambiente_id'] . "', name = '" . $this->mysql->escape($filter['name']) . "'");
		}

		// Filter Group
		$query = $this->mysql->query("SELECT * FROM  filter_cod_ambiente_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter_cod_ambiente) {
			$this->mysql->query("INSERT INTO  filter_cod_ambiente_description SET filter_cod_ambiente_id = '" . (int)$filter_cod_ambiente['filter_cod_ambiente_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($filter_cod_ambiente['name']) . "'");
		}

		// Information
		$query = $this->mysql->query("SELECT * FROM  information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $information) {
			$this->mysql->query("INSERT INTO  information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($information['title']) . "', description = '" . $this->mysql->escape($information['description']) . "', meta_title = '" . $this->mysql->escape($information['meta_title']) . "', meta_description = '" . $this->mysql->escape($information['meta_description']) . "', meta_keyword = '" . $this->mysql->escape($information['meta_keyword']) . "'");
		}

		$this->cache->delete('information');

		// Length
		$query = $this->mysql->query("SELECT * FROM  length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $length) {
			$this->mysql->query("INSERT INTO  length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($length['title']) . "', unit = '" . $this->mysql->escape($length['unit']) . "'");
		}

		$this->cache->delete('length_class');

		// Option
		$query = $this->mysql->query("SELECT * FROM  option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option) {
			$this->mysql->query("INSERT INTO  option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->mysql->query("SELECT * FROM  option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option_value) {
			$this->mysql->query("INSERT INTO  option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->mysql->escape($option_value['name']) . "'");
		}

		// Order Status
		$query = $this->mysql->query("SELECT * FROM  order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->mysql->query("INSERT INTO  order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($order_status['name']) . "'");
		}

		$this->cache->delete('order_status');

		// Product
		$query = $this->mysql->query("SELECT * FROM  product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->mysql->query("INSERT INTO  product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($product['name']) . "', description = '" . $this->mysql->escape($product['description']) . "', tag = '" . $this->mysql->escape($product['tag']) . "', meta_title = '" . $this->mysql->escape($product['meta_title']) . "', meta_description = '" . $this->mysql->escape($product['meta_description']) . "', meta_keyword = '" . $this->mysql->escape($product['meta_keyword']) . "'");
		}

		$this->cache->delete('product');

		// Product Attribute
		$query = $this->mysql->query("SELECT * FROM  product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product_attribute) {
			$this->mysql->query("INSERT INTO  product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->mysql->escape($product_attribute['text']) . "'");
		}

		// Return Action
		$query = $this->mysql->query("SELECT * FROM  return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_action) {
			$this->mysql->query("INSERT INTO  return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($return_action['name']) . "'");
		}

		// Return Reason
		$query = $this->mysql->query("SELECT * FROM  return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_reason) {
			$this->mysql->query("INSERT INTO  return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($return_reason['name']) . "'");
		}

		// Return Status
		$query = $this->mysql->query("SELECT * FROM  return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_status) {
			$this->mysql->query("INSERT INTO  return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($return_status['name']) . "'");
		}

		// Stock Status
		$query = $this->mysql->query("SELECT * FROM  stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->mysql->query("INSERT INTO  stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($stock_status['name']) . "'");
		}

		$this->cache->delete('stock_status');

		// Voucher Theme
		$query = $this->mysql->query("SELECT * FROM  voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $voucher_theme) {
			$this->mysql->query("INSERT INTO  voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($voucher_theme['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		// Weight Class
		$query = $this->mysql->query("SELECT * FROM  weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $weight_class) {
			$this->mysql->query("INSERT INTO  weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($weight_class['title']) . "', unit = '" . $this->mysql->escape($weight_class['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		// Profiles
		$query = $this->mysql->query("SELECT * FROM  recurring_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $recurring) {
			$this->mysql->query("INSERT INTO  recurring_description SET recurring_id = '" . (int)$recurring['recurring_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->mysql->escape($recurring['name']));
		}

		return $language_id;
	}

	public function editLanguage($language_id, $data) {
		$language_query = $this->mysql->query("SELECT `code` FROM  language WHERE language_id = '" . (int)$language_id . "'");
		
		$this->mysql->query("UPDATE  language SET name = '" . $this->mysql->escape($data['name']) . "', code = '" . $this->mysql->escape($data['code']) . "', locale = '" . $this->mysql->escape($data['locale']) . "', sort_order = '" . $this->mysql->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");

		if ($language_query->row['code'] != $data['code']) {
			$this->mysql->query("UPDATE  setting SET value = '" . $this->mysql->escape($data['code']) . "' WHERE `key` = 'config_language' AND value = '" . $this->mysql->escape($language_query->row['code']) . "'");
			$this->mysql->query("UPDATE  setting SET value = '" . $this->mysql->escape($data['code']) . "' WHERE `key` = 'config_admin_language' AND value = '" . $this->mysql->escape($language_query->row['code']) . "'");
		}
		
		$this->cache->delete('language');
	}
	
	public function deleteLanguage($language_id) {
		$this->mysql->query("DELETE FROM  language WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		$this->mysql->query("DELETE FROM  attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  attribute_group_description WHERE language_id = '" . (int)$language_id . "'");

		$this->mysql->query("DELETE FROM  banner_image_description WHERE language_id = '" . (int)$language_id . "'");

		$this->mysql->query("DELETE FROM  category_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('category');

		$this->mysql->query("DELETE FROM  customer_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  filter_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  filter_cod_ambiente_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  information_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('information');

		$this->mysql->query("DELETE FROM  length_class_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('length_class');

		$this->mysql->query("DELETE FROM  option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  option_value_description WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  order_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('order_status');

		$this->mysql->query("DELETE FROM  product_attribute WHERE language_id = '" . (int)$language_id . "'");
		$this->mysql->query("DELETE FROM  product_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('product');

		$this->mysql->query("DELETE FROM  return_action WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_action');

		$this->mysql->query("DELETE FROM  return_reason WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_reason');

		$this->mysql->query("DELETE FROM  return_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_status');

		$this->mysql->query("DELETE FROM  stock_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('stock_status');

		$this->mysql->query("DELETE FROM  voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('voucher_theme');

		$this->mysql->query("DELETE FROM  weight_class_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('weight_class');

		$this->mysql->query("DELETE FROM  recurring_description WHERE language_id = '" . (int)$language_id . "'");
	}

	public function getLanguage($language_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  language WHERE language_id = '" . (int)$language_id . "'");

		return $query->row;
	}

	public function getLanguages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM  language";

			$sort_data = array(
				'name',
				'code',
				'sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY sort_order, name";
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
			$language_data = $this->cache->get('language');

			if (!$language_data) {
				$language_data = array();

				$query = $this->mysql->query("SELECT * FROM  language ORDER BY sort_order, name");

				foreach ($query->rows as $result) {
					$language_data[$result['code']] = array(
						'language_id' => $result['language_id'],
						'name'        => $result['name'],
						'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
					);
				}

				$this->cache->set('language', $language_data);
			}

			return $language_data;
		}
	}

	public function getLanguageByCode($code) {
		$query = $this->mysql->query("SELECT * FROM `language` WHERE code = '" . $this->mysql->escape($code) . "'");

		return $query->row;
	}

	public function getTotalLanguages() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  language");

		return $query->row['total'];
	}
}
