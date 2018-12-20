<?php
class ModelVasPrestamos extends Model {

function cargarPrestamos($data = array()) {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);
	
   $json = array();	
   $sqltext = "select id_transaccion
                                    ,id_ccws
									,numero_celular
									,cod_promocion_lend
									,monto_prestado
									,monto_comision
									,cod_interfaz
									,cobrado
                                from lendme2
                               where (numero_celular = '".$data['filter_numero_celular']."' or numero_celular = '502".$data['filter_numero_celular']."')
                                 and fecha_otorgado >= to_date('".$data['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')
                                 and cobrado = 'N'";

   $query = $db->query($sqltext);

   $json = array(
		   'id_transaccion'     => $query->row['ID_TRANSACCION'],
		   'id_ccws'            => $query->row['ID_CCWS'],
		   'numero_celular'     => $query->row['NUMERO_CELULAR'],
		   'cod_promocion_lend' => $query->row['COD_PROMOCION_LEND'],
		   'monto_prestado'     => $query->row['MONTO_PRESTADO'],
		   'monto_comision'     => $query->row['MONTO_COMISION'],
		   'cod_interfaz'       => $query->row['COD_INTERFAZ'],
		   'cobrado'            => $query->row['COBRADO'],
		);
				
  return json_encode($json);	
}

 function cargarLogPrestamos($data = array()) {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);	 
   $json = array();	
   $sqltext = "select idccws,numero_celular,cod_promocion_lend,cod_interfaz,respuesta,aprobado
                 from log_lendme
                where (numero_celular = '".$data['filter_numero_celular']."' or numero_celular = '502".$data['filter_numero_celular']."')
                  and fecha >= to_date('".$data['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')";

   $query = $db->query($sqltext);

		$json = array(
		   'id_ccws'            => $query->row['IDCCWS'],
		   'numero_celular'     => $query->row['NUMERO_CELULAR'],
		   'cod_promocion_lend' => $query->row['COD_PROMOCION_LEND'],
		   'cod_interfaz'       => $query->row['COD_INTERFAZ'],
		   'respuesta'          => $query->row['RESPUESTA'],
		   'aprobado'           => $query->row['APROBADO']
		);

  return json_encode($json);
 }				  
 
function getPrestamosPendientes($data = array()) {
  $sqltext = "Select p.cod_promocion_lend 
      ,p.nombre_promocion
      ,to_char(fecha_otorgado,'dd-mm-yyyy hh24:mi:ss') fecha_otorgado
      ,monto_prestado
      ,monto_comision
      ,Nvl(Sum((Monto_Prestado+monto_comision)-(monto_cobrado+monto_comision_cobrado)),0) deuda
  From Lendme2 l
      ,promocion_lend p
 where p.cod_promocion_lend = l.cod_promocion_lend
   and (l.numero_Celular='".$data['filter_numero_celular']."' or numero_celular = '502".$data['filter_numero_celular']."')
   and (l.cobrado='N' or l.cobrado_comision='N') 
   and l.cod_promocion_lend not in (5,6,17,18,19,43,54,55,81,82,83,90,91,92,93,95,102,116,117,118)
  group by p.cod_promocion_lend
      ,p.nombre_promocion
      ,fecha_otorgado
      ,monto_prestado
      ,monto_comision
 order by 3 desc";	
   if (!empty($data['filter_numero_celular'])) {
   $query = $this->db->query($sqltext);
   return $query->rows;
   }
   else {
	     return null;
   }
}

 
}
?>