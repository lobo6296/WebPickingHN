<?php
class ModelVasRecargas extends Model {

function conectar($cod_ambiente) {
	/*
	  1: PRODUCCION
	  2: DESARROLLO
	*/
	if ($cod_ambiente==1) {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);
    } 
	else {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_DESA
							  , DB_USERNAME_DESA
							  , DB_PASSWORD_DESA
							  , DB_SERVICE_NAME_DESA
							  , DB_PORT_DESA);	
    }
	return $db;
}

function getInfoTuDiaTigo($data = array()) {
date_default_timezone_set('America/Guatemala');
$hoy = (int)date('d',strtotime('today'));
$lastday = (int)date('t',strtotime('today'));

$sqltext = "select to_number(to_char(fecha,'dd')) dia
      ,TO_CHAR(fecha, 'FMMon, YYYY') mes
      ,replace(replace(TO_CHAR(fecha, 'DAY'),'Á','A'),'É','E') nom_dia    
                  ,tipo_promocion||'-'||descripcion descripcion
				  ,tipo_promocion
  from recargas.calendario_triple
 where fecha>=trunc(sysdate)+1
   and fecha <= last_day(sysdate)
 union
select to_number(to_char(fecha_promocion,'dd'))
      ,TO_CHAR(fecha_promocion, 'FMMon, YYYY') mes
      ,replace(replace(TO_CHAR(fecha_promocion, 'DAY'),'Á','A'),'É','E') nom_dia    
      ,td.tipo_promocion||'-'||nombre_promocion nombre_promocion
      ,0
  from recargas.tudiatigo td
      ,recargas.promocion_tudiatigo t
  where t.cod_promocion = td.tipo_promocion
    and (numero_celular  = '502".$data['filter_numero_celular']."' or numero_celular  = '".$data['filter_numero_celular']."')
    and fecha_promocion>=trunc(sysdate)
	and fecha_promocion <= last_day(sysdate)
order by 1";

for ($i = $hoy; $i<=$lastday; $i++) {
   $dias[$i]=array ();
}

 $db = $this->conectar($data['filter_cod_ambiente']);
 $query = $db->query($sqltext);
 
 foreach ($query->rows as $result) {
	 $dias[$result['DIA']]['nom_dia'] = $result['NOM_DIA'];
	 $dias[$result['DIA']]['mes'] = $result['MES'];
	 $dias[$result['DIA']]['dias'][] = array(
	     'descripcion' => $result['DESCRIPCION'],
		 'tipo_promocion' => $result['TIPO_PROMOCION']
	 ); 
 }
   
return $dias;
}

 
}
?>