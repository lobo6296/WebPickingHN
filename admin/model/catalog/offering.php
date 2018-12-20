<?php
class ModelCatalogOffering extends Model {
	public function addOffering($data) {//Corregida
		
		foreach ($data['offering'] as $language_id => $value) {
			$this->mysql->query("INSERT INTO testing.offering 
			                        SET offering_id                = '" . (int)$value['offering_id'] . "'
									   ,offering_name              = '" . $this->mysql->escape($value['offering_name']) . "'
									   ,offering_code              = '" . $this->mysql->escape($value['offering_code']) . "'
									   ,offering_short_name        = '" . $this->mysql->escape($value['offering_short_name']) . "'
									   ,payment_mode               = '" . $this->mysql->escape($value['payment_mode']) . "'
									   ,catalog                    = '" . $this->mysql->escape($value['catalog']) . "'
									   ,subscription_offering_type = '" . $this->mysql->escape($value['subscription_offering_type']) . "'
									   ,rent_charge                = '" . $this->mysql->escape($value['rent_charge']) . "'
								       ,sort_order                 = '" . $this->mysql->escape($value['sort_order']) . "'
									   ,language_id                = '" . (int)$language_id . "'
								       ,date_added                 = NOW()");
		}
		
		$offering_id = $data['offering_id'];

		return $offering_id;
	}

	public function editOffering($offering_id, $data) {//Corregida

			foreach ($data['offering'] as $language_id => $value) {
			$this->mysql->query("UPDATE testing.offering 
			                        SET offering_id                = '" . (int)$value['offering_id'] . "'
									   ,offering_name              = '" . $this->mysql->escape($value['offering_name']) . "'
									   ,offering_code              = '" . $this->mysql->escape($value['offering_code']) . "'
									   ,offering_short_name        = '" . $this->mysql->escape($value['offering_short_name']) . "'
									   ,payment_mode               = '" . $this->mysql->escape($value['payment_mode']) . "'
									   ,catalog                    = '" . $this->mysql->escape($value['catalog']) . "'
									   ,subscription_offering_type = '" . $this->mysql->escape($value['subscription_offering_type']) . "'
									   ,rent_charge                = '" . $this->mysql->escape($value['rent_charge']) . "'
								       ,sort_order                 = '" . $this->mysql->escape($value['sort_order']) . "'
									   ,language_id                = '" . (int)$language_id . "'
								       ,date_added                 = NOW()
								 WHERE offering_id = '".(int)$offering_id."'");
		}
	}

	public function copyOffering($product_id) {

	}

	public function getAccounts($offering_id) {
	  $query = $this->mysql->query("SELECT *
                                      FROM accounts
							         WHERE offering_id = '" . (int)$offering_id . "'
							         ORDER BY sort_order");
	  return $query->rows;						 
	} 
	
	public function getOfferDet($offering_id) {
	  $query = $this->mysql->query("SELECT offering_id
	                                      ,correlativo
										  ,account_name
										  ,amount
										  ,unit
										  ,validity
										  ,recurrency
                                      FROM offering_det d
									 WHERE offering_id = '" . (int)$offering_id . "'
                                     ORDER BY correlativo");
	  return $query->rows;	
	}
	
	public function deleteOffering($offering_id) {//Corregida
		$query = $this->mysql->query("DELETE FROM testing.offering WHERE offering_id = '" . (int)$offering_id . "'");
	}

	public function getOffering($offering_id) {//Corregida
		$query = $this->mysql->query("SELECT offering_id 
		                                    ,offering_name
											,offering_code
											,offering_short_name
											,payment_mode
											,catalog
											,subscription_offering_type
											,rent_charge
											,sort_order
		                                FROM testing.offering
									   WHERE offering_id = ".(int)$offering_id);
		return $query->rows;
	}

	public function getOfferings($data = array()) {//Corregida
		$sql = "SELECT *
		          FROM testing.offering
				 WHERE 1=1";
				  
		if (isset($data['filter_offering_id'])) {
			$sql .= " AND offering_id LIKE '" . $this->mysql->escape($data['filter_offering_id']) . "%'";
		}
		
		if (isset($data['filter_offering_name'])) {
			$sql .= " AND offering_name LIKE '" . $this->mysql->escape($data['filter_offering_name']) . "%'";
		}
		
		if (isset($data['filter_subofftype'])) {
			
			switch ($data['filter_subofftype']) {
			      case 1: $filter_subofftype='Primary';
                  break; 				  
			      case 0: $filter_subofftype='Supplementary';
				  break;
		    }
			$sql .= " AND subscription_offering_type LIKE '" . $filter_subofftype . "%'";
		}		
		
		$sort_data = array(
			'offering_id',
			'offering_name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY subscription_offering_type," . $data['sort'];
		} else {
			$sql .= " ORDER BY subscription_offering_type,offering_name";
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

	public function getOfferingsByCategoryId($category_id) {
		$query = $this->mysql->query("SELECT * FROM  product p LEFT JOIN  product_description pd ON (p.product_id = pd.product_id) LEFT JOIN  product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getOfferingDescriptions($offering_id) {//Corregida
	
		$offering_data = array();

		$query = $this->mysql->query("SELECT * 
		                                FROM offering 
									   WHERE offering_id = '" . (int)$offering_id . "'");

		foreach ($query->rows as $result) {
			$offering_data = array(
				'offering_id'                => $result['offering_id'],
				'offering_name'              => $result['offering_name'],
				'offering_code'              => $result['offering_code'],
				'offering_short_name'        => $result['offering_short_name'],
				'payment_mode'               => $result['payment_mode'],
				'catalog'                    => $result['catalog'],
				'subscription_offering_type' => $result['subscription_offering_type'],
				'rent_charge'                => $result['rent_charge'],
				'sort_order'                 => $result['sort_order'],
			);
		}

		return $offering_data;
	}

	public function getOfferingCategories($product_id) {
		$product_category_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getOfferingFilters($product_id) {
		$product_filter_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getOfferingAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->mysql->query("SELECT attribute_id FROM  product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->mysql->query("SELECT * FROM  product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getOfferingOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->mysql->query("SELECT * FROM ` product_option` po LEFT JOIN ` option` o ON (po.option_id = o.option_id) LEFT JOIN ` option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->mysql->query("SELECT * FROM  product_option_value pov LEFT JOIN  option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getOfferingOptionValue($product_id, $product_option_value_id) {
		$query = $this->mysql->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM  product_option_value pov LEFT JOIN  option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN  option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOfferingImages($product_id) {
		$query = $this->mysql->query("SELECT * FROM  product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getOfferingDiscounts($product_id) {
		$query = $this->mysql->query("SELECT * FROM  product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getOfferingSpecials($product_id) {
		$query = $this->mysql->query("SELECT * FROM  product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getOfferingRewards($product_id) {
		$product_reward_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getOfferingDownloads($product_id) {
		$product_download_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getOfferingStores($product_id) {
		$product_store_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getOfferingLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getOfferingRelated($product_id) {
		$product_related_data = array();

		$query = $this->mysql->query("SELECT * FROM  product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->mysql->query("SELECT * FROM ` product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalOfferings($data = array()) { //Corregida
		$sql = "SELECT count(*) total 
		          FROM testing.offering
				 WHERE 1 = 1";

		if (isset($data['filter_offering_id'])) {
			$sql .= " AND offering_id LIKE '" . $this->mysql->escape($data['filter_offering_id']) . "%'";
		}
		
		if (isset($data['filter_offering_name'])) {
			$sql .= " AND offering_name LIKE '" . $this->mysql->escape($data['filter_offering_name']) . "%'";
		}

		if (isset($data['filter_subofftype'])) {
			
			switch ($data['filter_subofftype']) {
			      case 1: $filter_subofftype='Primary';
                  break; 				  
			      case 0: $filter_subofftype='Supplementary';
				  break;
		    }
			$sql .= " AND subscription_offering_type LIKE '" . $filter_subofftype . "%'";
		}		

		$query = $this->mysql->query($sql);

		return $query->row['total'];
	}

	public function getTotalOfferingsByTaxClassId($tax_class_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByStockStatusId($stock_status_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByWeightClassId($weight_class_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByLengthClassId($length_class_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByDownloadId($download_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByManufacturerId($manufacturer_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByAttributeId($attribute_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByOptionId($option_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByProfileId($recurring_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalOfferingsByLayoutId($layout_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
