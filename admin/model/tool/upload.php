<?php
class ModelToolUpload extends Model {
	public function addUpload($name, $filename) {
		$code = sha1(uniqid(mt_rand(), true));

		$this->mysql->query("INSERT INTO ` upload` SET `name` = '" . $this->mysql->escape($name) . "', `filename` = '" . $this->mysql->escape($filename) . "', `code` = '" . $this->mysql->escape($code) . "', `date_added` = NOW()");

		return $code;
	}
		
	public function deleteUpload($upload_id) {
		$this->mysql->query("DELETE FROM  upload WHERE upload_id = '" . (int)$upload_id . "'");
	}

	public function getUpload($upload_id) {
		$query = $this->mysql->query("SELECT * FROM ` upload` WHERE upload_id = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function getUploadByCode($code) {
		$query = $this->mysql->query("SELECT * FROM  upload WHERE code = '" . $this->mysql->escape($code) . "'");

		return $query->row;
	}

	public function getUploads($data = array()) {
		$sql = "SELECT * FROM  upload";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->mysql->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_filename'])) {
			$implode[] = "filename LIKE '" . $this->mysql->escape($data['filter_filename']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "date_added = '" . $this->mysql->escape($data['filter_date_added']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'filename',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

	public function getTotalUploads() {
		$sql = "SELECT COUNT(*) AS total FROM  upload";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->mysql->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_filename'])) {
			$implode[] = "filename LIKE '" . $this->mysql->escape($data['filter_filename']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "date_added = '" . $this->mysql->escape($data['filter_date_added']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->mysql->query($sql);

		return $query->row['total'];
	}
}