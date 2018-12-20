<?php
class ModelReportDashboard extends Model {

public function getTotalByEstado($data) {
   	$db = $this->conectar(2);
							  
    $sql = "select count(*) total
              from mrhw
             where getEstadoMr(hwfechaentrega,hwentregado) = '".$data['estado']."'
               and tipcode = ".$data['filter_tipinv'];
  		   
    $query = $db->query($sql);

	return $query->row['TOTAL'];
}

	public function getTotalIngByDay() {
        $db = $this->conectar(2);

		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}

		$query = $db->query(" select to_char(hwfechaing,'hh24') hour,count(*) total  from ingresohw i where i.tipcode   = 5 and trunc(hwfechaing)=trunc(sysdate) group by to_char(hwfechaing,'hh24')");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

}