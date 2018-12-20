<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->mysql->query("INSERT INTO  user_group SET name = '" . $this->mysql->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->mysql->escape(json_encode($data['permission'])) : '') . "'");
	
		return $this->mysql->getLastId();
	}

	public function editUserGroup($user_group_id, $data) {
		$this->mysql->query("UPDATE  user_group SET name = '" . $this->mysql->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->mysql->escape(json_encode($data['permission'])) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function deleteUserGroup($user_group_id) {
		$this->mysql->query("DELETE FROM  user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function getUserGroup($user_group_id) {
		$query = $this->mysql->query("SELECT DISTINCT * FROM  user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		$user_group = array(
			'name'       => $query->row['name'],
			'permission' => json_decode($query->row['permission'], true)
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM  user_group";

		$sql .= " ORDER BY name";

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

	public function getTotalUserGroups() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM  user_group");

		return $query->row['total'];
	}

	public function addPermission($user_group_id, $type, $route) {
		$user_group_query = $this->mysql->query("SELECT DISTINCT * FROM  user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type][] = $route;

			$this->mysql->query("UPDATE  user_group SET permission = '" . $this->mysql->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}

	public function removePermission($user_group_id, $type, $route) {
		$user_group_query = $this->mysql->query("SELECT DISTINCT * FROM  user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type] = array_diff($data[$type], array($route));

			$this->mysql->query("UPDATE  user_group SET permission = '" . $this->mysql->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}
}