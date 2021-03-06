<?php
class ModelReportTigo extends Model {


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

public function getTotalAverangeoccupancy($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from TIGO.OCUPACION A
	where 1=1 ";

		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 	$sql .= " and A.TGFECHA between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   		   
 	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getAverangeoccupancy($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select to_char(A.TGFECHA,'dd/mm/yyyy hh24:mi:ss') TGFECHA
			,A.TGCARRIL
			,A.TGANCHO
			,A.TGLARGO
			,A.TIPCODE
			,A.TIGOSUBCTA_CODE
			,(A.TGANCHO * A.TGLARGO) AS PORCARRIL
		,ROW_NUMBER() over (order by A.TIPCODE) R 
		From TIGO.OCUPACION A
		Where 1=1 	
		And A.tipcode = ".$data['filter_tipinv']." ";

	$sql = "Select TGFECHA
		,TGCARRIL
		,TGANCHO
		,TGLARGO
		,TIPCODE
		,TIGOSUBCTA_CODE
		,PORCARRIL
		From (". 
			$sql;
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and A.TGFECHA between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
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
		$sql .= " order by TIPCODE";		

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

public function getDamaged($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWPACKING
			,B.HWCONTRACT
			,to_char(B.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') as HWFECHAING
			,C.HWESTADO
			,A.HWCAJA
			,A.HWARTCOD
			,D.HWARTDESC
			,A.HWSERIE
			,(A.HWRECBUEN - A.HWDESPBUEN) as EXISTENCIABE
			,A.HWRESERVADO
			,(A.HWRECBUEN - A.HWDESPBUEN - A.HWRESERVADO) as DISPONIBLE
			,(A.HWRECMAL - A.HWDESPMAL) as EXISTENCIAME
			,(C.RACBOD ||'-'|| C.RACTRM ||'-'|| C.RACRAC ||'-'|| c.RACNIV ||'-'|| C.RACESP) as LOCALIZACION 
			,ROW_NUMBER() over (order by A.HWPACKING,A.HWCAJA,A.HWARTCOD) R 
		From TIGO.DETINGHW a
		inner JOIN tigo.INGRESOHW b on A.HWPACKING = b.HWPACKING
		INNER JOIN tigo.CAJAHW c on c.HWPACKING = a.HWPACKING and c.HWCAJA = a.HWCAJA
		INNER JOIN tigo.CATALOGOHW d on d.HWARTCOD = a.HWARTCOD
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']."";

	$sql = "Select HWPACKING
		,HWCONTRACT
		,HWFECHAING
		,HWESTADO
		,HWCAJA
		,HWARTCOD
		,HWARTDESC
		,HWSERIE
		,EXISTENCIABE
		,HWRESERVADO
		,DISPONIBLE
		,EXISTENCIAME
		,LOCALIZACION
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and A.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and b.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
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
		$sql .= " order by HWPACKING,HWCAJA,HWARTCOD";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getTotaldamaged($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
 $sql = "Select count(*) total
 		FROM TIGO.DETINGHW a
 			inner JOIN tigo.INGRESOHW b on A.HWPACKING = b.HWPACKING
 			INNER JOIN tigo.CAJAHW c on c.HWPACKING = a.HWPACKING and c.HWCAJA = a.HWCAJA
 			INNER JOIN tigo.CATALOGOHW d on d.HWARTCOD = a.HWARTCOD
		Where 1=1  
		And B.TIPCODE   = ".$data['filter_tipinv']."";

		if (isset($data['filter_hwartcod'])) {
		 	$sql .= " And A.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}
		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 	$sql .= " And b.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
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
		FROM tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
  		WHERE 1=1 
			and B.TIPCODE   = ".$data['filter_tipinv']." ";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " AND A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and  G.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 	$query = $db->query($sql);

	//echo $sql;
	
	//print_r($sql);
	//exit(0);
 	return $query->row['TOTAL'];
}

public function getBydata($data){
	$sql = "";
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
				,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
				,e.HWMRNO
				,to_char(e.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') as HWFECHASOL
				,f.SITNOM
				,A.HWPACKING
				,C.HWPO
				,G.HWARTCOD
				,h.HWARTDESC
				,G.HWSERIE
				,G.HWSERIEPREDEFINIDA
				,G.HWSERIEACTIVOFIJO
				,A.HWCANTDESP
				,H.HWUNIMED
				,E.MRHW_ESTADO
				,i.TIGOSUBCTA_DESCRIP
				,B.HWENTREGO
				,B.HWRECIBIO
				,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
			From tigo.DETDESHW a
				INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
				INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
				INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
				inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
				inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
				inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
				inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
				INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
			Where 1=1 	
				And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
				,HWFDESPACHO
				,HWMRNO
				,HWFECHASOL
				,SITNOM
				,HWPACKING
				,HWPO
				,HWARTCOD
				,HWARTDESC
				,HWSERIE
				,HWSERIEPREDEFINIDA
				,HWSERIEACTIVOFIJO
				,HWCANTDESP
				,HWUNIMED
				,MRHW_ESTADO
				,TIGOSUBCTA_DESCRIP
				,HWENTREGO
				,HWRECIBIO
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
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
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);
	
	//echo $sql;
	//exit(0);
	return $query->rows;
}

public function getTotalPackinglist($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
		FROM tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
  		WHERE 1=1 
			and B.TIPCODE   = ".$data['filter_tipinv']." ";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " AND A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and  G.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		if (isset($data['filter_sitio'])) {
		 $sql .= " and i.sitid = ".$data['filter_sitio'];	
		}			   
 	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getPackinglist($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
				,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
				,e.HWMRNO
				,to_char(e.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') as HWFECHASOL
				,f.SITNOM
				,A.HWPACKING
				,A.HWCAJA
				,G.HWARTCOD
				,h.HWARTDESC
				,G.HWSERIE
				,G.HWSERIEPREDEFINIDA
				,G.HWSERIEACTIVOFIJO
				,A.HWCANTDESP
				,H.HWUNIMED
				,E.MRHW_ESTADO
				,i.TIGOSUBCTA_DESCRIP
				,B.HWENTREGO
				,B.HWRECIBIO
				,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
			From tigo.DETDESHW a
				INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
				INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
				INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
				inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
				inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
				inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
				inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
				INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
			Where 1=1 	
				And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
				,HWFDESPACHO
				,HWMRNO
				,HWFECHASOL
				,SITNOM
				,HWPACKING
				,HWCAJA
				,HWARTCOD
				,HWARTDESC
				,HWSERIE
				,HWSERIEPREDEFINIDA
				,HWSERIEACTIVOFIJO
				,HWCANTDESP
				,HWUNIMED
				,MRHW_ESTADO
				,TIGOSUBCTA_DESCRIP
				,HWENTREGO
				,HWRECIBIO
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
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
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);
	//echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getTotalBysite($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
		FROM tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
  		WHERE 1=1 
			and B.TIPCODE   = ".$data['filter_tipinv']." ";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " AND A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and  G.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		if (isset($data['filter_sitio'])) {
		 $sql .= " and b.sitid = ".$data['filter_sitio'];	
		}			   
 	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getBysite($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
				,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
				,e.HWMRNO
				,f.SITNOM
				,A.HWPACKING
				,C.HWCONTRACT
				,A.HWCAJA
				,G.HWARTCOD
				,h.HWARTDESC
				,G.HWSERIE
				,G.HWSERIEPREDEFINIDA
				,G.HWSERIEACTIVOFIJO
				,A.HWCANTDESP
				,H.HWUNIMED
				,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
			From tigo.DETDESHW a
				INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
				INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
				INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
				inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
				inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
				inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
				inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
				INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
			Where 1=1 	
				And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
				,HWFDESPACHO
				,HWMRNO
				,SITNOM
				,HWPACKING
				,HWCONTRACT
				,HWCAJA
				,HWARTCOD
				,HWARTDESC
				,HWSERIE
				,HWSERIEPREDEFINIDA
				,HWSERIEACTIVOFIJO
				,HWCANTDESP
				,HWUNIMED
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and B.SITID  = ".$data['filter_sitio'];	
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
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);
	//echo $sql;
	
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getTotalBysiteindetailthemovement($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
		FROM tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
  		WHERE 1=1 
			and B.TIPCODE   = ".$data['filter_tipinv']." ";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " AND A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		  if (isset($data['filter_hwartcod'])) {
		 $sql .= " and  G.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
		}

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		if (isset($data['filter_sitio'])) {
		 $sql .= " and b.sitid = ".$data['filter_sitio'];	
		}			   
 	$query = $db->query($sql);

 	return $query->row['TOTAL'];
}

public function getBysiteindetailthemovement($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
				,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
				,e.HWMRNO
				,f.SITNOM
				,A.HWPACKING
				,C.HWCONTRACT
				,A.HWCAJA
				,G.HWARTCOD
				,h.HWARTDESC
				,G.HWSERIE
				,G.HWSERIEPREDEFINIDA
				,G.HWSERIEACTIVOFIJO
				,A.HWCANTDESP
				,H.HWUNIMED
				,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
			From tigo.DETDESHW a
				INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
				INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
				INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
				inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
				inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
				inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
				inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
				INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
			Where 1=1 	
				And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
				,HWFDESPACHO
				,HWMRNO
				,SITNOM
				,HWPACKING
				,HWCONTRACT
				,HWCAJA
				,HWARTCOD
				,HWARTDESC
				,HWSERIE
				,HWSERIEPREDEFINIDA
				,HWSERIEACTIVOFIJO
				,HWCANTDESP
				,HWUNIMED
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and B.SITID  = ".$data['filter_sitio'];	
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
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);
	//echo $sql;
	
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getTotalInbounds($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from TIGO.INGRESOHW a
	where 1=1
	and A.TIPCODE = ".$data['filter_tipinv']." ";

		if (isset($data['filter_hwpacking'])) {
		 $sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and A.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 		   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getInbounds($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select a.HWPACKING
			,a.HWCONTRACT
			,a.HWFACTURA
			,a.HWDELIVERYNOTICE
			,A.HWPO
			,to_char(A.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') as HWFECHAING
			,ROWNUM
		,ROW_NUMBER() over (order by a.HWPACKING,a.HWCONTRACT,A.HWPO) R 
		From TIGO.INGRESOHW a
		Where 1=1 	
		And a.tipcode = ".$data['filter_tipinv']." ";

	$sql = "Select HWPACKING
		,HWCONTRACT
		,HWFACTURA
		,HWDELIVERYNOTICE
		,HWPO
		,HWFECHAING
		,ROWNUM
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and a.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
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
		$sql .= " order by HWPACKING,HWCONTRACT,HWPO";		

		$query = $db->query($sql);
	//	echo $sql;
	
	//print_r($sql);
	//exit(0);
		return $query->rows;
}

public function getTotalOutbounds($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from TIGO.DESPACHOHW A
    	INNER JOIN TIGO.MRHW B ON A.HWDESPACHO = B.HWMR AND A.TIPCODE = B.TIPCODE
    	INNER JOIN TIGO.SITIOS C ON C.SITID = A.SITID
	where 1=1 
	and a.tipcode   = ".$data['filter_tipinv']." ";

		 if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and a.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and a.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

	//echo $sql;
	
	//print_r($sql);
	//exit(0);

 return $query->row['TOTAL'];
}

public function getOutbounds($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select B.HWMRNO
			,to_char(B.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') HWFECHASOL
			,A.HWDESPACHO
			,to_char(A.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') HWFDESPACHO
			,C.SITNOM
			,ROWNUM
			,ROW_NUMBER() over (order by B.HWMRNO,A.HWDESPACHO,C.SITNOM) R 
		From TIGO.DESPACHOHW A
			INNER JOIN TIGO.MRHW B ON A.HWDESPACHO = B.HWMR AND A.TIPCODE = B.TIPCODE
			INNER JOIN TIGO.SITIOS C ON C.SITID = A.SITID
		Where 1=1 	
		And a.tipcode = ".$data['filter_tipinv']." ";

	$sql = "Select HWMRNO
		,HWFECHASOL
		,HWDESPACHO
		,HWFDESPACHO
		,SITNOM
		,ROWNUM
		From (". 
			$sql;
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and a.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and a.sitid = ".$data['filter_sitio'];	
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
		$sql .= " order by HWMRNO,HWDESPACHO,SITNOM";		

		$query = $db->query($sql);
	
	//	echo $sql;
	//print_r($sql);
	//exit(0);
	
	return $query->rows;
}

public function getTotalReturns($data) {
	$db = $this->conectar($this->session->data['conexion']);
						   
	$sql = "select count(*) total
	from TIGO.INGRESOHW a
    	LEFT JOIN tigo.SITIOS b on b.SITID = a.SITID
	where 1=1 
	and a.tipcode   = ".$data['filter_tipinv']."
	AND (SUBSTR(a.HWPACKING,1,3) <> 'RET' OR SUBSTR(a.HWPACKING,1,3) = 'DEV') ";

		  if (isset($data['filter_hwpacking'])) {
		 $sql .= " and a.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
		}

		if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
		 $sql .= " and a.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
				   and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";
		}	   
		 if (isset($data['filter_sitio'])) {
		 $sql .= " and a.sitid = ".$data['filter_sitio'];	
		}			   
 $query = $db->query($sql);

 return $query->row['TOTAL'];
}

public function getReturns($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select ROWNUM
			,Trim(A.HWPACKING) as HWPACKING
			,to_char(A.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') HWFECHAING
			,nvl(B.SITNOM,'Not Applicable') as SITNOM
			,A.HWTECNICO
			,ROW_NUMBER() over (order by a.HWPACKING,a.HWFECHAING) R 
		FROM TIGO.INGRESOHW a
			LEFT JOIN tigo.SITIOS b on b.SITID = a.SITID
		WHERE 1=1
		And a.tipcode = ".$data['filter_tipinv']."
		AND (SUBSTR(a.HWPACKING,1,3) <> 'RET' OR SUBSTR(a.HWPACKING,1,3) = 'DEV') ";

	$sql = "Select ROWNUM
		,HWPACKING
		,HWFECHAING
		,SITNOM
		,HWTECNICO		
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
				$sql .= " and a.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}			
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
				$sql .= " and a.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
				$sql .= " and a.sitid = ".$data['filter_sitio'];	
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
		$sql .= " order by HWPACKING,HWFECHAING";		

		$query = $db->query($sql);
	
		//echo $sql;
		//exit(0);

		return $query->rows;
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

public function getDamagedReportExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWPACKING
		,B.HWCONTRACT
		,to_char(B.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') as HWFECHAING
		,C.HWESTADO
		,A.HWCAJA
		,A.HWARTCOD
		,D.HWARTDESC
		,A.HWSERIE
		,(A.HWRECBUEN - A.HWDESPBUEN) as EXISTENCIABE
		,A.HWRESERVADO
		,(A.HWRECBUEN - A.HWDESPBUEN - A.HWRESERVADO) as DISPONIBLE
		,(A.HWRECMAL - A.HWDESPMAL) as EXISTENCIAME
		,(C.RACBOD ||'-'|| C.RACTRM ||'-'|| C.RACRAC ||'-'|| c.RACNIV ||'-'|| C.RACESP) as LOCALIZACION
		,ROW_NUMBER() over (order by A.HWPACKING,A.HWCAJA,A.HWARTCOD) R 
		From TIGO.DETINGHW a
		inner JOIN tigo.INGRESOHW b on A.HWPACKING = b.HWPACKING
		INNER JOIN tigo.CAJAHW c on c.HWPACKING = a.HWPACKING and c.HWCAJA = a.HWCAJA
		INNER JOIN tigo.CATALOGOHW d on d.HWARTCOD = a.HWARTCOD
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWPACKING
			,HWCONTRACT
			,HWFECHAING
			,HWESTADO
			,HWCAJA
			,HWARTCOD
			,HWARTDESC
			,HWSERIE
			,EXISTENCIABE
			,HWRESERVADO
			,DISPONIBLE
			,EXISTENCIAME
			,LOCALIZACION
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and A.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and b.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWPACKING,HWCAJA,HWARTCOD";		

		$query = $db->query($sql);
	
	//echo $sql;
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getAverangeoccupancyReportExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select to_char(A.TGFECHA,'dd/mm/yyyy hh24:mi:ss') TGFECHA
			,A.TGCARRIL
			,A.TGANCHO
			,A.TGLARGO
			,A.TIPCODE
			,A.TIGOSUBCTA_CODE
			,(A.TGANCHO * A.TGLARGO) AS PORCARRIL
			,ROW_NUMBER() over (order by A.TIPCODE) R 
		From TIGO.OCUPACION A
		Where 1=1 	
		And a.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select TGFECHA
			,TGCARRIL
			,TGANCHO
			,TGLARGO
			,TIPCODE
			,TIGOSUBCTA_CODE
			,PORCARRIL
		From (". 
			$sql;
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and A.TGFECHA between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			   
		$sql .= ")";
		
		$sql .= " order by TIPCODE";		

		$query = $db->query($sql);
	
	//echo $sql;
	//print_r($sql);
	//exit(0);
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

public function getStockReportBydataExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
			,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
			,e.HWMRNO
			,to_char(e.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') as HWFECHASOL
			,f.SITNOM
			,A.HWPACKING
			,C.HWPO
			,G.HWARTCOD
			,h.HWARTDESC
			,G.HWSERIE
			,G.HWSERIEPREDEFINIDA
			,G.HWSERIEACTIVOFIJO
			,A.HWCANTDESP
			,H.HWUNIMED
			,E.MRHW_ESTADO
			,i.TIGOSUBCTA_DESCRIP
			,B.HWENTREGO
			,B.HWRECIBIO
			,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
		From tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
			,HWFDESPACHO
			,HWMRNO
			,HWFECHASOL
			,SITNOM
			,HWPACKING
			,HWPO
			,HWARTCOD
			,HWARTDESC
			,HWSERIE
			,HWSERIEPREDEFINIDA
			,HWSERIEACTIVOFIJO
			,HWCANTDESP
			,HWUNIMED
			,MRHW_ESTADO
			,TIGOSUBCTA_DESCRIP
			,HWENTREGO
			,HWRECIBIO
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);
	
	//echo $sql;
	//print_r($sql);
	//exit(0);
	return $query->rows;
}

public function getStockReportPackinglistExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
			,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
			,e.HWMRNO
			,to_char(e.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') as HWFECHASOL
			,f.SITNOM
			,A.HWPACKING
			,A.HWCAJA
			,G.HWARTCOD
			,h.HWARTDESC
			,G.HWSERIE
			,G.HWSERIEPREDEFINIDA
			,G.HWSERIEACTIVOFIJO
			,A.HWCANTDESP
			,H.HWUNIMED
			,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
		From tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
			,HWFDESPACHO
			,HWMRNO
			,HWFECHASOL
			,SITNOM
			,HWPACKING
			,HWCAJA
			,HWARTCOD
			,HWARTDESC
			,HWSERIE
			,HWSERIEPREDEFINIDA
			,HWSERIEACTIVOFIJO
			,HWCANTDESP
			,HWUNIMED
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and A.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and i.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);

		return $query->rows;
}

public function getStockReportBysiteExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
			,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
			,e.HWMRNO
			,f.SITNOM
			,A.HWPACKING
			,C.HWCONTRACT
			,A.HWCAJA
			,G.HWARTCOD
			,h.HWARTDESC
			,G.HWSERIE
			,G.HWSERIEPREDEFINIDA
			,G.HWSERIEACTIVOFIJO
			,A.HWCANTDESP
			,H.HWUNIMED
			,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
		From tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
			,HWFDESPACHO
			,HWMRNO
			,SITNOM
			,HWPACKING
			,HWCONTRACT
			,HWCAJA
			,HWARTCOD
			,HWARTDESC
			,HWSERIE
			,HWSERIEPREDEFINIDA
			,HWSERIEACTIVOFIJO
			,HWCANTDESP
			,HWUNIMED
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and B.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);

		return $query->rows;
}

public function getStockReportBysiteindetailthemovementExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select A.HWDESPACHO
			,to_char(B.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') as HWFDESPACHO
			,e.HWMRNO
			,f.SITNOM
			,A.HWPACKING
			,C.HWCONTRACT
			,A.HWCAJA
			,G.HWARTCOD
			,h.HWARTDESC
			,G.HWSERIE
			,G.HWSERIEPREDEFINIDA
			,G.HWSERIEACTIVOFIJO
			,A.HWCANTDESP
			,H.HWUNIMED
			,ROW_NUMBER() over (order by A.HWDESPACHO,G.HWARTCOD,e.HWMRNO) R 
		From tigo.DETDESHW a
			INNER JOIN TIGO.DESPACHOHW b on a.HWDESPACHO = b.HWDESPACHO
			INNER JOIN TIGO.INGRESOHW c on a.HWPACKING = C.HWPACKING
			INNER JOIN TIGO.DETMRHW d on d.HWPACKING = a.HWPACKING and B.TIPCODE = d.TIPCODE
			inner JOIN TIGO.MRHW e on d.HWMR = e.HWMR and e.TIPCODE = d.TIPCODE and E.HWENTREGADO = A.HWDESPACHO
			inner JOIN TIGO.SITIOS f on f.SITID = B.SITID
			inner JOIN tigo.DETINGHW g on g.HWPACKING = c.HWPACKING and d.HWLINSOL = g.HWLINEA and g.HWCAJA = D.HWCAJA 
			inner JOIN tigo.CATALOGOHW h on h.HWARTCOD = g.HWARTCOD
			INNER JOIN TIGO.TIGO_SUBCUENTA i on g.TIGOSUBCTA_CODE = i.TIGOSUBCTA_CODE and i.TIPCODE = B.TIPCODE
		Where 1=1 	
		And B.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWDESPACHO
			,HWFDESPACHO
			,HWMRNO
			,SITNOM
			,HWPACKING
			,HWCONTRACT
			,HWCAJA
			,HWARTCOD
			,HWARTDESC
			,HWSERIE
			,HWSERIEPREDEFINIDA
			,HWSERIEACTIVOFIJO
			,HWCANTDESP
			,HWUNIMED
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
			$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_hwartcod'])) {
			$sql .= " and g.HWARTCOD LIKE '%" . $data['filter_hwartcod'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and B.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
			$sql .= " and B.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by HWDESPACHO,HWARTCOD,HWMRNO";		

		$query = $db->query($sql);

		return $query->rows;
}

public function getStockReportInboundsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select Trim(a.HWPACKING) as HWPACKING
			,a.HWCONTRACT
			,a.HWFACTURA
			,a.HWDELIVERYNOTICE
			,A.HWPO
			,to_char(A.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') as HWFECHAING
			,ROWNUM
			,ROW_NUMBER() over (order by a.HWPACKING,a.HWCONTRACT,A.HWPO) R 
		From TIGO.INGRESOHW a
		Where 1=1 	
		And a.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select HWPACKING
				,HWCONTRACT
				,HWFACTURA
				,HWDELIVERYNOTICE
				,HWPO
				,HWFECHAING
				,ROWNUM
		From (". 
			$sql;
			if (isset($data['filter_hwpacking'])) {
				$sql .= " and a.HWPACKING LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and a.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}   
		$sql .= ")";
		
		$sql .= " order by HWPACKING,HWCONTRACT,HWPO";		

		$query = $db->query($sql);

		return $query->rows;
}

public function getStockReportOutboundsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select B.HWMRNO
			,to_char(B.HWFECHASOL,'dd/mm/yyyy hh24:mi:ss') HWFECHASOL
			,A.HWDESPACHO
			,to_char(A.HWFDESPACHO,'dd/mm/yyyy hh24:mi:ss') HWFDESPACHO
			,C.SITNOM
			,ROWNUM
			,ROW_NUMBER() over (order by B.HWMRNO,A.HWDESPACHO,C.SITNOM) R 
		From TIGO.DESPACHOHW A
			INNER JOIN TIGO.MRHW B ON A.HWDESPACHO = B.HWMR AND A.TIPCODE = B.TIPCODE
			INNER JOIN TIGO.SITIOS C ON C.SITID = A.SITID
		Where 1=1 	
		And a.TIPCODE = ".$data['filter_tipinv']." ";

	$sql = "Select  HWMRNO
			,HWFECHASOL
			,HWDESPACHO
			,HWFDESPACHO
			,SITNOM
			,ROWNUM
		From (". 
			$sql;

			if (isset($data['filter_sitio'])) {
				$sql .= " and a.sitid = ".$data['filter_sitio'];	
			}

			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
			$sql .= " and a.HWFDESPACHO between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}   
		$sql .= ")";
		
		$sql .= " order by  HWMRNO,HWDESPACHO,SITNOM";		

		$query = $db->query($sql);

		//echo $sql;
		//exit(0);

		return $query->rows;
}

public function getStockReportReturnsExcel($data){
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "Select ROWNUM
			,Trim(A.HWPACKING) as HWPACKING
			,to_char(A.HWFECHAING,'dd/mm/yyyy hh24:mi:ss') HWFECHAING
			,nvl(B.SITNOM,'Not Applicable') as SITNOM
			,A.HWTECNICO
			,ROW_NUMBER() over (order by a.HWPACKING,a.HWFECHAING) R 
		FROM TIGO.INGRESOHW a
			LEFT JOIN tigo.SITIOS b on b.SITID = a.SITID
		WHERE 1=1
		And a.TIPCODE = ".$data['filter_tipinv']." 
		AND (SUBSTR(a.HWPACKING,1,3) <> 'RET' OR SUBSTR(a.HWPACKING,1,3) = 'DEV') ";

	$sql = "Select  ROWNUM
			,HWPACKING
			,HWFECHAING
			,SITNOM
			,HWTECNICO		
		From (". 
			$sql;

			if (isset($data['filter_hwpacking'])) {
				$sql .= " and a.hwpacking LIKE '%" . $data['filter_hwpacking'] . "%' ";
			}			
			if (isset($data['filter_date_start'])&&isset($data['filter_date_end'])) {
				$sql .= " and a.HWFECHAING between to_date('".$data['filter_date_start']."','yyyy-mm-dd')
					and to_date('".$data['filter_date_end']."','yyyy-mm-dd')";	
			}		   
			if (isset($data['filter_sitio'])) {
				$sql .= " and a.sitid = ".$data['filter_sitio'];	
			}		   
		$sql .= ")";
		
		$sql .= " order by  HWPACKING,HWFECHAING ";		

		$query = $db->query($sql);

		//echo $sql;
		//exit(0);

		return $query->rows;
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