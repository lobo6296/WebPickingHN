<?php
namespace DESA;
final class ORACLE_DESA {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = oci_connect($username, $password,$hostname.'/'.$database)) {
			trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}
	}	

			
	public function __destruct() {
        if ($this->link) {
          oci_close($this->link); 
        }
    }    
	
	/*	
$stid = oci_parse($this->link, 'SELECT * FROM v_estadonumero');
oci_execute($stid);

echo "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
		*/
		
	/*
		if (!$conexión) {
          $e = oci_error();
          trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
		
	
		if (!mysql_select_db("oracle", $this->link)) {
			trigger_error('Error: Could not connect to database ' . $database);
		}
        
		mysql_query("SET NAMES 'utf8'", $this->link);
		mysql_query("SET CHARACTER SET utf8", $this->link);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->link);
		mysql_query("SET SQL_MODE = ''", $this->link);*/
	

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

     while ($result = oci_fetch_array($stid,  OCI_BOTH)) {
  	      $data[] = $result;
  	      $i++;
     }
       
   oci_free_statement($stid);
   
   					$query = new \stdClass();
					$query->row = isset($data[0]) ? $data[0] : array();
					$query->rows = $data;
					$query->num_rows = $i;

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