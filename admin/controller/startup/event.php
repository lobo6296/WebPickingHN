<?php
class ControllerStartupEvent extends Controller {
	public function index() {
		/*
		$query = $this->mysql->query("SELECT * FROM ` event` WHERE `trigger` LIKE 'admin/%' ORDER BY `event_id` ASC","event.php->function index()");
		
		foreach ($query->rows as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
		*/
	}
}