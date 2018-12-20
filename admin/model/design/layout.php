<?php
class ModelDesignLayout extends Model {
	public function addLayout($data) {
		$this->mysql->query("INSERT INTO  layout SET name = '" . $this->mysql->escape($data['name']) . "'");

		$layout_id = $this->mysql->getLastId();

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->mysql->query("INSERT INTO  layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->mysql->escape($layout_route['route']) . "'");
			}
		}

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->mysql->query("INSERT INTO  layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->mysql->escape($layout_module['code']) . "', position = '" . $this->mysql->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
			}
		}

		return $layout_id;
	}

	public function editLayout($layout_id, $data) {
		$this->mysql->query("UPDATE  layout SET name = '" . $this->mysql->escape($data['name']) . "' WHERE layout_id = '" . (int)$layout_id . "'");

		$this->mysql->query("DELETE FROM  layout_route WHERE layout_id = '" . (int)$layout_id . "'");

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->mysql->query("INSERT INTO  layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->mysql->escape($layout_route['route']) . "'");
			}
		}

		$this->mysql->query("DELETE FROM  layout_module WHERE layout_id = '" . (int)$layout_id . "'");

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->mysql->query("INSERT INTO  layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->mysql->escape($layout_module['code']) . "', position = '" . $this->mysql->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
			}
		}
	}

	public function deleteLayout($layout_id) {
		$this->mysql->query("DELETE FROM  layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->mysql->query("DELETE FROM  layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		$this->mysql->query("DELETE FROM  layout_module WHERE layout_id = '" . (int)$layout_id . "'");
		$this->mysql->query("DELETE FROM  category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->mysql->query("DELETE FROM  product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->mysql->query("DELETE FROM  information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
	}

	public function getLayout($layout_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row;
	}

	public function getLayouts($data = array()) {
		$sql = "SELECT * FROM  layout";

		$sort_data = array('name');

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

	public function getLayoutRoutes($layout_id) {
		$query = $this->mysql->query("SELECT * FROM  layout_route WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->rows;
	}

	public function getLayoutModules($layout_id) {
		$query = $this->mysql->query("SELECT * FROM  layout_module WHERE layout_id = '" . (int)$layout_id . "' ORDER BY position ASC, sort_order ASC");

		return $query->rows;
	}

	public function getTotalLayouts() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  layout");

		return $query->row['total'];
	}
}