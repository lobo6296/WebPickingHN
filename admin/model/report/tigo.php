<?php
class ModelReportTigo extends Model {



public function getTotalAverangeoccupancy($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getAverangeoccupancy($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getbyBOMNumber($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getTotalbyBOMNumber($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
 $sql = "Select count(*) total
		From Detinghw D
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV' 
		And I.tipcode   = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) > 0";

		if (isset($data['filter_hwartcod'])) {
		 	$sql .= " And D.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}
		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 	$sql .= " And I.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   And to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}

	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getdamaged($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getTotaldamaged($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
 $sql = "Select count(*) total
		From Detinghw D
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV' 
		And I.tipcode   = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) > 0";

		if (isset($data['filter_hwartcod'])) {
		 	$sql .= " And D.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}
		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 	$sql .= " And I.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   And to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}

	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}





public function getSitios() {
   	$db = $this->conectar($this->session->data['conexion']);
							  
    $sql = "select sitid,sitnom
              from sitios
             order by 1";

    $query = $db->query($sql);

	return $query->rows;
}

public function getTotalBydata($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalPackinglist($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalBysite($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalBysiteindetailthemovement($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalInbounds($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalOutbounds($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalReturns($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalOvertime($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalGeneralstockbycode($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalSummaryofmovement($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getTotalInboundbydate($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from ingresohw i
	,detinghw d
	,catalogohw c
	where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getBydata($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getPackinglist($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getBysite($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getBysiteindetailthemovement($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getInbounds($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getOutbounds($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getReturns($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getOvertime($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getGeneralstockbycode($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getSummaryofmovement($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getInboundbydate($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') hwfechaing
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS Existencia
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal)>0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,hwfechaing
		,Existencia
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			$data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

//reportes de excel
public function getbyBOMNumberReportExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') HWFECHAING
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS EXISTENCIA
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) > 0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,HWFECHAING
		,EXISTENCIA
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	
	//echo $sql;
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getdamagedReportExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select d.HWARTCOD
		,C1.HWARTDESC
		,D.HWCAJA
		,D.HWLINEA
		,C.HWPACKING
		,D.HWSERIE
		,to_char(i.hwfechaing,'dd/mm/yyyy hh24:mi:ss') HWFECHAING
		,(D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) AS EXISTENCIA
		,(D.HwRecBuen - D.HwDespBuen - D.HWRESERVADO) AS DISPONIBLE 
		,ROW_NUMBER() over (order by D.HWPACKING,D.HWCAJA,D.HWLINEA) R 
		From Detinghw D 
		Inner Join Ingresohw I On D.Hwpacking = I.Hwpacking
		Inner Join Cajahw C On D.Hwpacking = C.Hwpacking And D.Hwcaja = C.Hwcaja
		Inner Join Catalogohw C1 On D.Hwartcod = C1.Hwartcod
		Where 1=1 	
		And I.tipcode = ".$data['filter_tipinv']."
		AND SUBSTR(D.HWPACKING,1,3) <> 'RET' AND SUBSTR(D.HWPACKING,1,3) <> 'DEV'
		And (D.HwRecBuen - D.HwDespBuen) + (D.HwRecMal - D.HwDespMal) > 0 ";

	$sql = "Select HWARTCOD
		,HWARTDESC
		,HWCAJA
		,HWLINEA
		,HWPACKING
		,HWSERIE
		,HWFECHAING
		,EXISTENCIA
		,DISPONIBLE
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and i.hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWPACKING,HWCAJA,HWLINEA";		

		$query = $db->query($sql);
	
	//echo $sql;
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getStockReportExcel($data) {
   	$db = $this->conectar($this->session->data['conexion']);
							  
    $sql = "select substr(i.hwpacking,1,3) code
       ,i.hwpacking
       ,i.hwcontract
       ,to_char(hwfechaing,'dd/mm/yyyy hh24:mi:ss') InboundDate
       ,round(sysdate-hwfechaing) daysinventory
       ,d.hwcaja
       ,d.hwartcod
	   ,d.hwserie
       ,c.hwartdesc
       ,c.hwunimed
       ,(HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal) Existencia
       ,HwRecBuen - HwDespBuen - HwReservado Disponible
	   ,ROW_NUMBER() over (order by i.hwpacking,d.hwcaja) R
  from ingresohw i
      ,detinghw d
      ,catalogohw c
 where d.hwpacking = i.hwpacking
  and c.hwartcod  = d.hwartcod
  and i.tipcode   = ".$data['filter_tipinv']."
  and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

	$sql = "
	SELECT code
	      ,hwpacking
		  ,hwcontract
		  ,InboundDate
		  ,daysinventory
		  ,hwcaja
		  ,hwartcod
		  ,hwartdesc
		  ,hwserie
		  ,hwunimed
		  ,existencia
		  ,disponible
    FROM (". 
	       $sql;
		   if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		   }
		   if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		   }
	 	   if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
			          and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
		   }		   
	 	   if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
		   }		   
		$sql .= ")";
		
	   if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] <= 1) {
			  $data['start'] = 1;
			}

			if ($data['limit'] < 1) {
			  $data['limit'] = 20;
			}

			$sql .= " WHERE R BETWEEN " . ((int)$data['start']). " AND " .( (int)$data['limit'] + (int)$data['start']-1);
		}	
 $sql .= " order by hwpacking,hwcaja";		

    $query = $db->query($sql);

	return $query->rows;
}

public function getStockReportExcelExcel($data) {
   	$db = $this->conectar($this->session->data['conexion']);
							  
    $sql = "select i.hwpacking
       ,ca.hwbodega
       ,i.hwcontract
       ,to_char(hwfechaing,'dd/mm/yyyy hh24:mi:ss') InboundDate
       ,round(sysdate-hwfechaing) daysinventory
       ,decode(ca.hwestado,'D',null,ca.hwestado) hwestado
       ,d.hwcaja
       ,d.hwartcod
	   ,d.hwserie
       ,c.hwartdesc
       ,(HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal) Existencia
       ,HwReservado Solicitado
       ,HwRecBuen - HwDespBuen - HwReservado Disponible
       ,(HwRecMal - HwDespMal) damaged
       ,c.hwunimed       
       ,trim(ca.racbod)||'-'||trim(ca.ractrm)||'-'||trim(ca.racrac)||'-'||trim(ca.racniv)||'-'||trim(ca.racesp) location
	   ,ROW_NUMBER() over (order by i.hwpacking,d.hwcaja) R
  from ingresohw i
      ,detinghw d
      ,catalogohw c
      ,cajahw ca
 where d.hwpacking = i.hwpacking
  and c.hwartcod  = d.hwartcod
    and i.tipcode   = ".$data['filter_tipinv']."
  and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0
  and ca.hwpacking = d.hwpacking
  and ca.hwcaja    = d.hwcaja";

	$sql = "
	SELECT hwpacking
       ,hwbodega
       ,hwcontract
       ,InboundDate
       ,daysinventory
       ,hwestado
       ,hwcaja
       ,hwartcod
       ,hwartdesc
	   ,hwserie
       ,existencia
       ,solicitado
       ,disponible
       ,damaged
       ,hwunimed       
       ,location
    FROM (". 
	       $sql;
		   if (isset($data['filter_hwpacking'])) {
			$sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		   }
		   if (isset($data['filter_hwartcod'])) {
			$sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		   }
	 	   if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
			          and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
		   }		   
	 	   if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
		   }		   
		$sql .= ")";
		$sql .= " order by inbounddate,hwpacking,hwcaja";		

    $query = $db->query($sql);

	return $query->rows;
}	

public function getStockReportMovementsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getTotalStockReportExcel($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
 $sql = "select count(*) total
	from ingresohw i
   ,detinghw d
   ,catalogohw c
 where d.hwpacking = i.hwpacking
	and c.hwartcod  = d.hwartcod
	and i.tipcode   = ".$data['filter_tipinv']."
	and (HwRecBuen - HwDespBuen) + (HwRecMal - HwDespMal)>0";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and i.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and d.hwartcod LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and hwfechaing between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getStockReportBydataExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportPackinglistExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportBysiteExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportBysiteindetailthemovementExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportInboundsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportOutboundsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportReturnsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportOvertimeExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportGeneralstockbycodeExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportSummaryofmovementExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

public function getStockReportInboundbydateExcel($data){
	$db = $this->conectar($this->session->data['conexion']);
}

}