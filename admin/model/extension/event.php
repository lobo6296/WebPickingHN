<?php
class ModelExtensionEvent extends Model {
	public function addEvent($code, $trigger, $action) {
		$this->mysql->query("INSERT INTO  event SET `code` = '" . $this->mysql->escape($code) . "', `trigger` = '" . $this->mysql->escape($trigger) . "', `action` = '" . $this->mysql->escape($action) . "'");
	
		return $this->mysql->getLastId();
	}

	public function deleteEvent($code) {
		$this->mysql->query("DELETE FROM  event WHERE `code` = '" . $this->mysql->escape($code) . "'");
	}
}