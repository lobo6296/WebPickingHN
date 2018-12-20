<?php
class ModelCatalogInformation extends Model {
	public function addInformation($data) {
		$this->mysql->query("INSERT INTO  information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");

		$information_id = $this->mysql->getLastId();

		foreach ($data['information_description'] as $language_id => $value) {
			$this->mysql->query("INSERT INTO  information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($value['title']) . "', description = '" . $this->mysql->escape($value['description']) . "', meta_title = '" . $this->mysql->escape($value['meta_title']) . "', meta_description = '" . $this->mysql->escape($value['meta_description']) . "', meta_keyword = '" . $this->mysql->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->mysql->query("INSERT INTO  information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				$this->mysql->query("INSERT INTO  information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->mysql->query("INSERT INTO  url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->mysql->escape($data['keyword']) . "'");
		}

		$this->cache->delete('information');

		return $information_id;
	}

	public function editInformation($information_id, $data) {
		$this->mysql->query("UPDATE  information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE information_id = '" . (int)$information_id . "'");

		$this->mysql->query("DELETE FROM  information_description WHERE information_id = '" . (int)$information_id . "'");

		foreach ($data['information_description'] as $language_id => $value) {
			$this->mysql->query("INSERT INTO  information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->mysql->escape($value['title']) . "', description = '" . $this->mysql->escape($value['description']) . "', meta_title = '" . $this->mysql->escape($value['meta_title']) . "', meta_description = '" . $this->mysql->escape($value['meta_description']) . "', meta_keyword = '" . $this->mysql->escape($value['meta_keyword']) . "'");
		}

		$this->mysql->query("DELETE FROM  information_to_store WHERE information_id = '" . (int)$information_id . "'");

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->mysql->query("INSERT INTO  information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->mysql->query("DELETE FROM  information_to_layout WHERE information_id = '" . (int)$information_id . "'");

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				$this->mysql->query("INSERT INTO  information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->mysql->query("DELETE FROM  url_alias WHERE query = 'information_id=" . (int)$information_id . "'");

		if ($data['keyword']) {
			$this->mysql->query("INSERT INTO  url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->mysql->escape($data['keyword']) . "'");
		}

		$this->cache->delete('information');
	}

	public function deleteInformation($information_id) {
		$this->mysql->query("DELETE FROM  information WHERE information_id = '" . (int)$information_id . "'");
		$this->mysql->query("DELETE FROM  information_description WHERE information_id = '" . (int)$information_id . "'");
		$this->mysql->query("DELETE FROM  information_to_store WHERE information_id = '" . (int)$information_id . "'");
		$this->mysql->query("DELETE FROM  information_to_layout WHERE information_id = '" . (int)$information_id . "'");
		$this->mysql->query("DELETE FROM  url_alias WHERE query = 'information_id=" . (int)$information_id . "'");

		$this->cache->delete('information');
	}

	public function getInformation($information_id) {
		$query = $this->mysql->query("SELECT DISTINCT *, (SELECT keyword FROM  url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM  information WHERE information_id = '" . (int)$information_id . "'");

		return $query->row;
	}

	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM  information i LEFT JOIN  information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . (int)$this->config->get('config_language_id'));

			if (!$information_data) {
				$query = $this->mysql->query("SELECT * FROM  information i LEFT JOIN  information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$information_data = $query->rows;

				$this->cache->set('information.' . (int)$this->config->get('config_language_id'), $information_data);
			}

			return $information_data;
		}
	}

	public function getInformationDescriptions($information_id) {
		$information_description_data = array();

		$query = $this->mysql->query("SELECT * FROM  information_description WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $information_description_data;
	}

	public function getInformationStores($information_id) {
		$information_store_data = array();

		$query = $this->mysql->query("SELECT * FROM  information_to_store WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_store_data[] = $result['store_id'];
		}

		return $information_store_data;
	}

	public function getInformationLayouts($information_id) {
		$information_layout_data = array();

		$query = $this->mysql->query("SELECT * FROM  information_to_layout WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $information_layout_data;
	}

	public function getTotalInformations() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  information");

		return $query->row['total'];
	}

	public function getTotalInformationsByLayoutId($layout_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}