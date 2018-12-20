<?php
namespace Cart;

class User {
	private $user_id;
	private $username;
	private $permission = array();

	public function __construct($registry) {
		$this->mysql = $registry->get('mysql');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->mysql->query("SELECT * 
			                                     FROM user 
												WHERE user_id = '" . (int)$this->session->data['user_id'] . "' 
												  AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];


				$this->user_group_id = $user_query->row['user_group_id'];

				

				$this->mysql->query("UPDATE user 
				                        SET ip = '" . $this->mysql->escape($this->request->server['REMOTE_ADDR']) . "' 
									  WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->mysql->query("SELECT permission 
				                                           FROM user_group 
														  WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = json_decode($user_group_query->row['permission'], true);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	
	public function login($username, $password,$estacion) {
			
		$sqltext = "SELECT * 
		              FROM user 
		  		     WHERE username = '" . $this->mysql->escape($username) . "' 
					   AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->mysql->escape(htmlspecialchars($password, ENT_QUOTES)) . "'))))) 
					   OR password = '" . $this->mysql->escape(md5($password)) . "') 
					   AND status = '1'";
											  
											  
		$user_query = $this->mysql->query($sqltext);
	
		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];
			$this->session->data['ausrid'] = strtoupper($user_query->row['username']);
			
			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->mysql->query("SELECT permission 
			                                           FROM user_group 
													  WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}
		} else {
			    return false;
		}
		
		$class = 'DB\\oracle';

		if (class_exists($class)) {
			
		  switch ($estacion) {
               case 1: 
			          	  $db = new $class(DB_HOSTNAME_PROTIGOTGU
		                  ,DB_USERNAME_PROTIGOTGU
						  ,DB_PASSWORD_PROTIGOTGU
						  ,DB_DATABASE_PROTIGOTGU
						  ,DB_PORT_PROTIGOTGU);
						  $this->session->data['conexion'] = 5;
               break;

               case 2:
			          	  $db = new $class(DB_HOSTNAME_PROTIGOSAP
		                  ,DB_USERNAME_PROTIGOSAP
						  ,DB_PASSWORD_PROTIGOSAP
						  ,DB_DATABASE_PROTIGOSAP
						  ,DB_PORT_PROTIGOSAP);
						$this->session->data['conexion'] = 2;						  
               break;
		  } 			   
  			
		  $sql = "SELECT NOMUSU,TIPINV,tipdesce 
		            FROM TIGO.USUARIOSWEB u
					    ,tigo.tiptrae t
				   WHERE t.tipcode = u.tipinv
				     and CODUSUW = ".$username." 
				     AND PASUSU = '".$password."'";

		  $query = $db->query($sql);
		  
		  if ($query->num_rows) {
			$this->session->data['nomusu'] = $query->row['NOMUSU'];
			$this->session->data['tipinv'] = $query->row['TIPINV']; 
			$this->session->data['tipdesce'] = $query->row['TIPDESCE']; 
			return true;
		  }
		  else {
			    if ($this->user_group_id!=1) {
			     return false;  
				} else {
					    return true;
				}
		  }

		} 
		
	}

	public function logear($username) {
		$sql = "SELECT * 
		          FROM user 
				 WHERE username = '" . $this->mysql->escape($username) ."' AND status = '1'";
        $user_query = $this->mysql->query($sql);

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];
			$this->session->data['ausrid'] = strtoupper($user_query->row['username']);
			
			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->mysql->query("SELECT permission 
			                                           FROM user_group 
													  WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}	
	
	public function logout() {
		unset($this->session->data['user_id']);
		unset($this->session->data['ausrid']);

		$this->user_id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) {
		$tiene=false;
		
		if (isset($this->permission[$key])) {
			$tiene = in_array($value, $this->permission[$key]);
		} else {
			$tiene= false;
		}

		return $tiene;
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}

	public function getGroupId() {
		return $this->user_group_id;
	}
}
