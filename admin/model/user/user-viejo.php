<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->mysql->query("INSERT INTO ` user` SET username = '" . $this->mysql->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->mysql->escape($salt = token(9)) . "', password = '" . $this->mysql->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->mysql->escape($data['firstname']) . "', lastname = '" . $this->mysql->escape($data['lastname']) . "', email = '" . $this->mysql->escape($data['email']) . "', image = '" . $this->mysql->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		return $this->mysql->getLastId();
	}

	public function editUser($user_id, $data) {
		$this->mysql->query("UPDATE ` user` SET username = '" . $this->mysql->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->mysql->escape($data['firstname']) . "', lastname = '" . $this->mysql->escape($data['lastname']) . "', email = '" . $this->mysql->escape($data['email']) . "', image = '" . $this->mysql->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->mysql->query("UPDATE ` user` SET salt = '" . $this->mysql->escape($salt = token(9)) . "', password = '" . $this->mysql->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->mysql->query("UPDATE ` user` SET salt = '" . $this->mysql->escape($salt = token(9)) . "', password = '" . $this->mysql->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->mysql->query("UPDATE ` user` SET code = '" . $this->mysql->escape($code) . "' WHERE LCASE(email) = '" . $this->mysql->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->mysql->query("DELETE FROM ` user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->mysql->query("SELECT u.*, (SELECT ug.name FROM ` jrivera.user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM ` jrivera.usuario` u WHERE u.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username) {
		$query = $this->mysql->query("SELECT * FROM ` user` WHERE username = '" . $this->mysql->escape($username) . "'");

		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->mysql->query("SELECT * FROM ` user` WHERE code = '" . $this->mysql->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array()) {
		$sql = "SELECT * FROM ` user`";

		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
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

	public function getTotalUsers() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` user`");

		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` user` WHERE LCASE(email) = '" . $this->mysql->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}
}