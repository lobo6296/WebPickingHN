<?php
class ModelToolPdf extends Model{
 
public function stockreport($data) {
	
  $this->load->model('report/tigo');
  $this->load->language('report/stockreport'); 

  $detalle = $this->model_report_tigo->getStockReportExcel($data); 
  
  require_once('/var/www/html/sis/webpicking/system/library/pdf.php' );     
  $pdf = new PDF();
 
  $pdf->AddPage();
 
  $miCabecera = array(/*$this->language->get('column_hwpacking'), 
                      $this->language->get('column_hwbodega'),
                      $this->language->get('column_hwcontract'), 
					  $this->language->get('column_inbounddate'),
					  $this->language->get('column_daysinventory'),
					  $this->language->get('column_hwestado'),*/
					  $this->language->get('column_hwcaja'),
					  $this->language->get('column_hwartcod'),
					  $this->language->get('column_hwartdesc')/*,
					  $this->language->get('column_existencia'),
					  $this->language->get('column_solicitado'),
					  $this->language->get('column_disponible'),
					  $this->language->get('column_damaged'),
					  $this->language->get('column_hwunimed'),
					  $this->language->get('column_location')*/
					  );
 

 foreach ($detalle as $p) {            
   $misDatos[] = array(
   /*  'hwpacking'     => $p['HWPACKING'],
	 'hwbodega'      => $p['HWBODEGA'],
	 'hwcontract'    => $p['HWCONTRACT'],
	 'inbounddate'   => $p['INBOUNDDATE'],
	 'daysinventory' => $p['DAYSINVENTORY'],
	 'hwestado'      => $p['HWESTADO'],*/
	 'hwcaja'        => 12,
	 'hwartcod'      => $p['HWARTCOD'],
	 'hwartdesc'     => $p['HWARTDESC']/*,
	 'existencia'    => $p['EXISTENCIA'],
	 'solicitado'    => $p['SOLICITADO'],
	 'disponible'    => $p['DISPONIBLE'],
	 'damaged'       => $p['DAMAGED'],
	 'hwunimed'      => $p['HWUNIMED'],
	 'location'      => $p['LOCATION']*/
  );   
}

$pdf->tablaHorizontal($miCabecera, $misDatos);
 
$pdf->Output();
}

function generarReporte($data) {

  if ($data['reporte']=='stockreport') {
    $this->stockreport($data);	
  }		
}	

 
}
?>
