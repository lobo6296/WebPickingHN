<?php
class ModelFraudIp extends Model {
	public function install() {
		$this->mysql->query("
		CREATE TABLE IF NOT EXISTS ` fraud_ip` (
		  `ip` varchar(40) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`ip`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->mysql->query("DROP TABLE IF EXISTS ` ip`");
	}

    public function addIp($ip) {
        $this->mysql->query("INSERT INTO ` fraud_ip` SET `ip` = '" . $this->mysql->escape($ip) . "', date_added = NOW()");
    }

    public function removeIp($ip) {
        $this->mysql->query("DELETE FROM ` fraud_ip` WHERE `ip` = '" . $this->mysql->escape($ip) . "'");
    }

	public function getIps($start = 0, $limit = 10) {
        if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

        $query = $this->mysql->query("SELECT * FROM ` fraud_ip` ORDER BY `ip` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalIps() {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` fraud_ip`");

		return $query->row['total'];
	}

	public function getTotalIpsByIp($ip) {
		$query = $this->mysql->query("SELECT COUNT(*) AS total FROM ` fraud_ip` WHERE ip = '" . $this->mysql->escape($ip) . "'");

		return $query->row['total'];
	}
}
