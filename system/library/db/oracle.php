<?php
namespace DB;
final class ORACLE {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = oci_connect($username, $password,$hostname.'/'.$database)) {
			$e = oci_error();
			//Could not make a database link using
			var_dump($e);
			echo 'Error: '.$e. $username . '@' . $hostname." ".$hostname.'/'.$database;
			exit(0);
		}
	}	

			
	public function __destruct() {
        if ($this->link) {
          oci_close($this->link); 
        }
    }    

	public function query($sql,$pagina) {
	  
	 $sql = str_replace("`", "",$sql);
	 //echo "query: ".$sql."[".$pagina."]<br>";
	 
     if ($this->link) {
      // Prepare the statement
      $stid = oci_parse($this->link, $sql);
	  //echo $sql;
   
     if (!$stid) {
       $e = oci_error($this->connection);
       var_dump($e);
     }
   
   // Perform the logic of the query
     $r = oci_execute($stid);
     if (!$r) {
       $e = oci_error($stid);
       var_dump($e);
     }
     		$i = 0;
     $data = array();

     while ($result = oci_fetch_array($stid,  OCI_ASSOC+OCI_RETURN_NULLS)) {
  	      $data[] = $result;
  	      $i++;
     }
       
   oci_free_statement($stid);
   
   					$query = new \stdClass();
					$query->row = isset($data[0]) ? $data[0] : array();
					$query->rows = $data;
					$query->num_rows = $i;
					$query->errormsg = $e;

					unset($data);

					return $query;
     }
 }

 public function execute($sql) {
     if ($this->link) {
      // Prepare the statement
   $stid = oci_parse($this->link, $sql);
     
   //echo $sql;
   
   if (!$stid) {
      $e = oci_error($this->link);
      var_dump($e);
   }
 
   // Perform the logic of the query
   $r = oci_execute($stid);
   if (!$r) {
      $e = oci_error($stid);
      var_dump($e);
   }
   return $r; 
     }
 }


	public function escape($value) {
		return $value;
	}

	public function countAffected() {
		if ($this->link) {
			return mysql_affected_rows($this->link);
		}
	}

	public function getLastId() {
		if ($this->link) {
			return mysql_insert_id($this->link);
		}
	}
}