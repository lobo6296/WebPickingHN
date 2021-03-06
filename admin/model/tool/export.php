<?php
/*

const BORDER_NONE             = 'none';
const BORDER_DASHDOT          = 'dashDot';
const BORDER_DASHDOTDOT       = 'dashDotDot';
const BORDER_DASHED           = 'dashed';
const BORDER_DOTTED           = 'dotted';
const BORDER_DOUBLE           = 'double';
const BORDER_HAIR             = 'hair';
const BORDER_MEDIUM           = 'medium';
const BORDER_MEDIUMDASHDOT    = 'mediumDashDot';
const BORDER_MEDIUMDASHDOTDOT = 'mediumDashDotDot';
const BORDER_MEDIUMDASHED     = 'mediumDashed';
const BORDER_SLANTDASHDOT     = 'slantDashDot';
const BORDER_THICK            = 'thick';
const BORDER_THIN             = 'thin';

*/
static $registry = null;

// Error Handler

function error_handler_for_export_import($errno, $errstr, $errfile, $errline) {
	global $registry;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
	
	$config = $registry->get('config');
	$url = $registry->get('url');
	$request = $registry->get('request');
	$session = $registry->get('session');
	$log = $registry->get('log');
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if (($errors != "Fatal Error") && isset($request->get['route']) && ($request->get['route']!='tool/export_import/download'))  {
		if ($config->get('config_error_display')) {
			echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
	} else {
		$session->data['export_import_error'] = array( 'errstr'=>$errstr, 'errno'=>$errno, 'errfile'=>$errfile, 'errline'=>$errline );
		$token = $request->get['token'];
		$link = $url->link( 'tool/export_import', 'token='.$token, 'SSL' );
		header('Status: ' . 302);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $link));
		exit();
	}

	return true;
}

function fatal_error_shutdown_handler_for_export_import() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export_import(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ModelToolExport extends Model {

private $error = array();
protected $null_array = array();
protected $hyperlinks = array();

protected function clean( &$str, $allowBlanks=false ) {
		$result = "";
		$n = strlen( $str );
		for ($m=0; $m<$n; $m++) {
			$ch = substr( $str, $m, 1 );
			if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
				continue;
			}
			$result .= $ch;
		}
		return $result;
	}

protected function multiquery( $sql ) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$this->db->query($sql);
			}
		}
	}

protected function startsWith( $haystack, $needle ) {
		if (strlen( $haystack ) < strlen( $needle )) {
			return false;
		}
		return (substr( $haystack, 0, strlen($needle) ) == $needle);
	}

protected function endsWith( $haystack, $needle ) {
		if (strlen( $haystack ) < strlen( $needle )) {
			return false;
		}
		return (substr( $haystack, strlen($haystack)-strlen($needle), strlen($needle) ) == $needle);
	}

protected function clearCache() {
		$this->cache->delete('*');
	}

protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}	
	
protected function isInteger($input){
		return(ctype_digit(strval($input)));
	}
/*********************************************************************************
 EXCEL
 *********************************************************************************/	
protected function setColumnStyles( &$worksheet, &$styles, $min_row, $max_row ) {
		if ($max_row < $min_row) {
			return;
		}
		foreach ($styles as $col=>$style) {
			$from = PHPExcel_Cell::stringFromColumnIndex($col).$min_row;
			$to = PHPExcel_Cell::stringFromColumnIndex($col).$max_row;
			$range = $from.':'.$to;
			$worksheet->getStyle( $range )->applyFromArray( $style, false );
		}
	}	
	
protected function setCellRow( $worksheet, $row/*1-based*/, $data, &$default_style=null, &$styles=null ) {
		if (!empty($default_style)) {
			$worksheet->getStyle( "$row:$row" )->applyFromArray( $default_style, false );
		}
		if (!empty($styles)) {
			foreach ($styles as $col=>$style) {
				$worksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($style,false);
			}
		}
		$worksheet->fromArray( $data, null, 'A'.$row, true );

	}

protected function setCell( &$worksheet, $row/*1-based*/, $col/*0-based*/, $val, &$style=null ) {
		$worksheet->setCellValueByColumnAndRow( $col, $row, $val );
		if (!empty($style)) {
			$worksheet->getStyleByColumnAndRow($col,$row)->applyFromArray( $style, false );
		}
	}	
	
function setCellValue($worksheet,$cell,$value) {
  $worksheet->setCellValue($cell,$value);
}	

function cellColor($workbook,$cells,$color){
	$worksheet = $workbook->getActiveSheet();

    $worksheet->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
} 
 
/*********************************************************************************
 FIN EXCEL
 *********************************************************************************/
function stockreport($workbook,$y,$param) {
	
  $this->load->model('report/tigo');
  $this->load->language('report/stockreport'); 
  
  $worksheet = $workbook->getActiveSheet();
  
  $detalle = $this->model_report_tigo->getStockReportExcel($param); 
  $data=null;

  $j=0;
		
  $worksheet->setCellValue('A'.$y,$data['reporte']);
  $start=$y;
  $i=$start;
				
		foreach ($detalle as $p) {			
            
			if ($i==$start) {
				
			  $data[0] = $this->language->get('column_hwpacking');
			  $data[1] = $this->language->get('column_hwbodega');
			  $data[2] = $this->language->get('column_hwcontract');
			  $data[3] = $this->language->get('column_inbounddate');
			  $data[4] = $this->language->get('column_daysinventory');
			  $data[5] = $this->language->get('column_hwestado');
			  $data[6] = $this->language->get('column_hwcaja');
			  $data[7] = $this->language->get('column_hwartcod');
			  $data[8] = $this->language->get('column_hwartdesc');
			  $data[9] = $this->language->get('column_existencia');
			  $data[10] = $this->language->get('column_solicitado');
			  $data[11] = $this->language->get('column_disponible');
			  $data[12] = $this->language->get('column_damaged');
			  $data[13] = $this->language->get('column_hwunimed');
			  $data[14] = $this->language->get('column_location');
		      
			  $this->cellColor($workbook,'A'.$start.':O'.$start, 'FFFFE0');	
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );

			  $i += 1;	
			}

			$data[0] = $p['HWPACKING'];
			$data[1] = $p['HWBODEGA'];
			$data[2] = $p['HWCONTRACT'];
			$data[3] = $p['INBOUNDDATE'];
			$data[4] = $p['DAYSINVENTORY'];
			$data[5] = $p['HWESTADO'];
			$data[6] = $p['HWCAJA'];
			$data[7] = $p['HWARTCOD'];
			$data[8] = $p['HWARTDESC'];
			$data[9] = $p['EXISTENCIA'];
			$data[10] = $p['SOLICITADO'];
			$data[11] = $p['DISPONIBLE'];
			$data[12] = $p['DAMAGED'];
			$data[13] = $p['HWUNIMED'];
			$data[14] = $p['LOCATION'];
			
			$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			
			$i += 1;
			$j = 0;
		}
}	
 
function bypackinglist($workbook,$y,$param) {
	
  $this->load->model('report/tigo');
  $this->load->language('report/bypackinglist'); 
  
  $worksheet = $workbook->getActiveSheet();
  
  $detalle = $this->model_report_tigo->getStockReportExcel($param); 
  $data=null;

  $j=0;
		
  $worksheet->setCellValue('A'.(5),'Packing List');
  $worksheet->setCellValue('B'.(5),$param['filter_hwpacking']);
  $start=$y;
  $i=$start;
				
		foreach ($detalle as $p) {			
            
			if ($i==$start) {
				
			  //$data[0] = $this->language->get('column_hwpacking');
			  $data[1] = $this->language->get('column_hwbodega');
			  $data[2] = $this->language->get('column_hwcontract');
			  $data[3] = $this->language->get('column_inbounddate');
			  $data[4] = $this->language->get('column_daysinventory');
			  $data[5] = $this->language->get('column_hwestado');
			  $data[6] = $this->language->get('column_hwcaja');
			  $data[7] = $this->language->get('column_hwartcod');
			  $data[8] = $this->language->get('column_hwartdesc');
			  $data[9] = $this->language->get('column_existencia');
			  $data[10] = $this->language->get('column_solicitado');
			  $data[11] = $this->language->get('column_disponible');
			  $data[12] = $this->language->get('column_damaged');
			  $data[13] = $this->language->get('column_hwunimed');
			  $data[14] = $this->language->get('column_location');
		      
			  $this->cellColor($workbook,'A'.$start.':N'.$start, 'FFFFE0');	
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );

			  $i += 1;	
			}

			//$data[0] = $p['HWPACKING'];
			$data[1] = $p['HWBODEGA'];
			$data[2] = $p['HWCONTRACT'];
			$data[3] = $p['INBOUNDDATE'];
			$data[4] = $p['DAYSINVENTORY'];
			$data[5] = $p['HWESTADO'];
			$data[6] = $p['HWCAJA'];
			$data[7] = $p['HWARTCOD'];
			$data[8] = $p['HWARTDESC'];
			$data[9] = $p['EXISTENCIA'];
			$data[10] = $p['SOLICITADO'];
			$data[11] = $p['DISPONIBLE'];
			$data[12] = $p['DAMAGED'];
			$data[13] = $p['HWUNIMED'];
			$data[14] = $p['LOCATION'];
			
			$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			
			$i += 1;
			$j = 0;
		}
}	
  
function bybomnumber($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/bybomnumber'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getbyBOMNumberReport($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	
	$worksheet->setCellValue('A'.(5),'Bom No.');
	$worksheet->setCellValue('B'.(5),$param['filter_hwartcod']);
	$start=$y;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwartcod');
				$data[2] = $this->language->get('column_hwartdesc');
				$data[3] = $this->language->get('column_hwcaja');
				$data[4] = $this->language->get('column_hwpacking');
				$data[5] = $this->language->get('column_hwserie');
				$data[6] = $this->language->get('column_fechaing');
				$data[7] = $this->language->get('column_existencia');
				$data[8] = $this->language->get('column_disponible');
				
				$this->cellColor($workbook,'A'.$start.':N'.$start, 'FFFFE0');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWARTCOD'];
			  $data[2] = $p['HWARTDESC'];
			  $data[3] = $p['HWCAJA'];
			  $data[4] = $p['HWPACKING'];
			  $data[5] = $p['HWSERIE'];
			  $data[6] = $p['HWFECHAING'];
			  $data[7] = $p['EXISTENCIA'];
			  $data[8] = $p['DISPONIBLE'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
			  $totExistencia = $totExistencia + $p['EXISTENCIA'];
			  $totDisponible = $totDisponible + $p['DISPONIBLE'];
		  	}
		  	$worksheet->setCellValue('F'.$i,'Totales');
			$worksheet->setCellValue('G'.$i,$totExistencia);
			$worksheet->setCellValue('H'.$i,$totDisponible);
}

function damaged($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/damaged'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getDamagedReportExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(5),'Bom No.');
	$worksheet->setCellValue('B'.(5),$param['filter_hwartcod']);
	$start=$y;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwpacking');
				$data[2] = $this->language->get('column_hwcontract');
				$data[3] = $this->language->get('column_hwfechaing');
				$data[4] = $this->language->get('column_hwestado');
				$data[5] = $this->language->get('column_hwcaja');
				$data[6] = $this->language->get('column_hwartcod');
				$data[7] = $this->language->get('column_hwartdesc');
				$data[8] = $this->language->get('column_hwserie');
				$data[9] = $this->language->get('column_existenciabe');
				$data[10] = $this->language->get('column_hwreservado');
				$data[11] = $this->language->get('column_disponible');
				$data[12] = $this->language->get('column_existenciame');
				$data[13] = $this->language->get('column_localizacion');
				
				$this->cellColor($workbook,'A'.$start.':N'.$start, 'FFFFE0');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWPACKING'];
			  $data[2] = $p['HWCONTRACT'];
			  $data[3] = $p['HWFECHAING'];
			  $data[4] = $p['HWESTADO'];
			  $data[5] = $p['HWCAJA'];
			  $data[6] = $p['HWARTCOD'];
			  $data[7] = $p['HWARTDESC'];
			  $data[8] = $p['HWSERIE'];
			  $data[9] = $p['EXISTENCIABE'];
			  $data[10] = $p['HWRESERVADO'];
			  $data[11] = $p['DISPONIBLE'];
			  $data[12] = $p['EXISTENCIAME'];
			  $data[13] = $p['LOCALIZACION'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
			  $totExistencia = $totExistencia + $p['EXISTENCIABE'];
			  $totDisponible = $totDisponible + $p['DISPONIBLE'];
			  $totDisponibleDa = $totDisponibleDa + $p['EXISTENCIAME'];
		  	}
		  	$worksheet->setCellValue('F'.$i,'Totales');
			$worksheet->setCellValue('I'.$i,$totExistencia);
			$worksheet->setCellValue('K'.$i,$totDisponible);
			$worksheet->setCellValue('L'.$i,$totDisponibleDa);
}

function averangeoccupancy($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/averangeoccupancy'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getAverangeoccupancyReportExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(5),'Bom No.');
	$worksheet->setCellValue('B'.(5),$param['filter_hwartcod']);
	$start=$y;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_tgfecha');
				$data[2] = $this->language->get('column_tgcarril');
				$data[3] = $this->language->get('column_tgancho');
				$data[4] = $this->language->get('column_tglargo');
				$data[5] = $this->language->get('column_porcarril');
				
				$this->cellColor($workbook,'A'.$start.':E'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['TGFECHA'];
			  $data[2] = $p['TGCARRIL'];
			  $data[3] = $p['TGANCHO'];
			  $data[4] = $p['TGLARGO'];
			  $data[5] = $p['PORCARRIL'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
			  $totExistencia = $totExistencia + $p['PORCARRIL'];
		  	}
		  	$worksheet->setCellValue('B'.$i,'Total Diario Ocupaciones');
			$worksheet->setCellValue('E'.$i,$totExistencia);

			$this->cellColor($workbook,'B'.$i.':B'.$i, 'bdbdbd');
			$this->cellColor($workbook,'E'.$i.':E'.$i, 'bdbdbd');	
			
}

function bydata($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/bydata'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportBydataExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Bom No.');
	$worksheet->setCellValue('B'.(6),$param['filter_hwartcod']);
	$worksheet->setCellValue('C'.(6),'Packing');
	$worksheet->setCellValue('D'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwdespacho');
				$data[2] = $this->language->get('column_hwfdespacho');
				$data[3] = $this->language->get('column_hwmrno');
				$data[4] = $this->language->get('column_hwfechasol');
				$data[5] = $this->language->get('column_sitnom');
				$data[6] = $this->language->get('column_hwpacking');
				$data[7] = $this->language->get('column_hwpo');
				$data[8] = $this->language->get('column_hwartcod');
				$data[9] = $this->language->get('column_hwartdesc');
				$data[10] = $this->language->get('column_hwserie');
				$data[11] = $this->language->get('column_hwseriepredefinida');
				$data[12] = $this->language->get('column_hwserieactivofijo');
				$data[13] = $this->language->get('column_hwcantdesp');
				$data[14] = $this->language->get('column_hwunimed');
				$data[15] = $this->language->get('column_mrhw_estado');
				$data[16] = $this->language->get('column_tigosubcta_descrip');
				$data[17] = $this->language->get('column_hwentrego');
				$data[18] = $this->language->get('column_hwrecibio');
				
				$this->cellColor($workbook,'A'.$start.':R'.$start, 'FFFFE0');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWDESPACHO'];
			  $data[2] = $p['HWFDESPACHO'];
			  $data[3] = $p['HWMRNO'];
			  $data[4] = $p['HWFECHASOL'];
			  $data[5] = $p['SITNOM'];
			  $data[6] = $p['HWPACKING'];
			  $data[7] = $p['HWPO'];
			  $data[8] = $p['HWARTCOD'];
			  $data[9] = $p['HWARTDESC'];
			  $data[10] = $p['HWSERIE'];
			  $data[11] = $p['HWSERIEPREDEFINIDA'];
			  $data[12] = $p['HWSERIEACTIVOFIJO'];
			  $data[13] = $p['HWCANTDESP'];
			  $data[14] = $p['HWUNIMED'];
			  $data[15] = $p['MRHW_ESTADO'];
			  $data[16] = $p['TIGOSUBCTA_DESCRIP'];
			  $data[17] = $p['HWENTREGO'];
			  $data[18] = $p['HWRECIBIO'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
			  $totExistencia = $totExistencia + $p['HWCANTDESP'];
			  //$totDisponible = $totDisponible + $p['DISPONIBLE'];
			  //$totDisponibleDa = $totDisponibleDa + $p['EXISTENCIAME'];
		  	}
		  	$worksheet->setCellValue('F'.$i,'Totales');
			$worksheet->setCellValue('M'.$i,$totExistencia);
			//$worksheet->setCellValue('K'.$i,$totDisponible);
			//$worksheet->setCellValue('L'.$i,$totDisponibleDa);
}

function packinglist($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/packinglist'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportPackinglistExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Bom No.');
	$worksheet->setCellValue('B'.(6),$param['filter_hwartcod']);
	$worksheet->setCellValue('C'.(6),'Packing');
	$worksheet->setCellValue('D'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwdespacho');
				$data[2] = $this->language->get('column_hwfdespacho');
				$data[3] = $this->language->get('column_hwmrno');
				$data[4] = $this->language->get('column_hwfechasol');
				$data[5] = $this->language->get('column_sitnom');
				$data[6] = $this->language->get('column_hwpacking');
				$data[7] = $this->language->get('column_hwcaja');
				$data[8] = $this->language->get('column_hwartcod');
				$data[9] = $this->language->get('column_hwartdesc');
				$data[10] = $this->language->get('column_hwserie');
				$data[11] = $this->language->get('column_hwseriepredefinida');
				$data[12] = $this->language->get('column_hwserieactivofijo');
				$data[13] = $this->language->get('column_hwcantdesp');
				$data[14] = $this->language->get('column_hwunimed');
				
				$this->cellColor($workbook,'A'.$start.':N'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWDESPACHO'];
			  $data[2] = $p['HWFDESPACHO'];
			  $data[3] = $p['HWMRNO'];
			  $data[4] = $p['HWFECHASOL'];
			  $data[5] = $p['SITNOM'];
			  $data[6] = $p['HWPACKING'];
			  $data[7] = $p['HWCAJA'];
			  $data[8] = $p['HWARTCOD'];
			  $data[9] = $p['HWARTDESC'];
			  $data[10] = $p['HWSERIE'];
			  $data[11] = $p['HWSERIEPREDEFINIDA'];
			  $data[12] = $p['HWSERIEACTIVOFIJO'];
			  $data[13] = $p['HWCANTDESP'];
			  $data[14] = $p['HWUNIMED'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function bysite($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/bysite'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportBysiteExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Bom No.');
	$worksheet->setCellValue('B'.(6),$param['filter_hwartcod']);
	$worksheet->setCellValue('C'.(6),'Packing');
	$worksheet->setCellValue('D'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwdespacho');
				$data[2] = $this->language->get('column_hwfdespacho');
				$data[3] = $this->language->get('column_hwmrno');
				$data[4] = $this->language->get('column_sitnom');
				$data[5] = $this->language->get('column_hwpacking');
				$data[6] = $this->language->get('column_hwcontract');
				$data[7] = $this->language->get('column_hwcaja');
				$data[8] = $this->language->get('column_hwartcod');
				$data[9] = $this->language->get('column_hwartdesc');
				$data[10] = $this->language->get('column_hwserie');
				$data[11] = $this->language->get('column_hwseriepredefinida');
				$data[12] = $this->language->get('column_hwserieactivofijo');
				$data[13] = $this->language->get('column_hwcantdesp');
				$data[14] = $this->language->get('column_hwunimed');
				
				$this->cellColor($workbook,'A'.$start.':N'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWDESPACHO'];
			  $data[2] = $p['HWFDESPACHO'];
			  $data[3] = $p['HWMRNO'];
			  $data[4] = $p['SITNOM'];
			  $data[5] = $p['HWPACKING'];
			  $data[6] = $p['HWCONTRACT'];
			  $data[7] = $p['HWCAJA'];
			  $data[8] = $p['HWARTCOD'];
			  $data[9] = $p['HWARTDESC'];
			  $data[10] = $p['HWSERIE'];
			  $data[11] = $p['HWSERIEPREDEFINIDA'];
			  $data[12] = $p['HWSERIEACTIVOFIJO'];
			  $data[13] = $p['HWCANTDESP'];
			  $data[14] = $p['HWUNIMED'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function bysiteindetailthemovement($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/bysiteindetailthemovement'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportBysiteindetailthemovementExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Bom No.');
	$worksheet->setCellValue('B'.(6),$param['filter_hwartcod']);
	$worksheet->setCellValue('C'.(6),'Packing');
	$worksheet->setCellValue('D'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_hwdespacho');
				$data[2] = $this->language->get('column_hwfdespacho');
				$data[3] = $this->language->get('column_hwmrno');
				$data[4] = $this->language->get('column_sitnom');
				$data[5] = $this->language->get('column_hwpacking');
				$data[6] = $this->language->get('column_hwcontract');
				$data[7] = $this->language->get('column_hwcaja');
				$data[8] = $this->language->get('column_hwartcod');
				$data[9] = $this->language->get('column_hwartdesc');
				$data[10] = $this->language->get('column_hwserie');
				$data[11] = $this->language->get('column_hwseriepredefinida');
				$data[12] = $this->language->get('column_hwserieactivofijo');
				$data[13] = $this->language->get('column_hwcantdesp');
				$data[14] = $this->language->get('column_hwunimed');
				
				$this->cellColor($workbook,'A'.$start.':N'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['HWDESPACHO'];
			  $data[2] = $p['HWFDESPACHO'];
			  $data[3] = $p['HWMRNO'];
			  $data[4] = $p['SITNOM'];
			  $data[5] = $p['HWPACKING'];
			  $data[6] = $p['HWCONTRACT'];
			  $data[7] = $p['HWCAJA'];
			  $data[8] = $p['HWARTCOD'];
			  $data[9] = $p['HWARTDESC'];
			  $data[10] = $p['HWSERIE'];
			  $data[11] = $p['HWSERIEPREDEFINIDA'];
			  $data[12] = $p['HWSERIEACTIVOFIJO'];
			  $data[13] = $p['HWCANTDESP'];
			  $data[14] = $p['HWUNIMED'];
			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function inbounds($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/inbounds'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportInboundsExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Packing');
	$worksheet->setCellValue('B'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_rownum');
				$data[2] = $this->language->get('column_hwpacking');
				$data[3] = $this->language->get('column_hwcontract');
				$data[4] = $this->language->get('column_hwfactura');
				$data[5] = $this->language->get('column_hwdeliverynotice');
				$data[6] = $this->language->get('column_hwpo');
				$data[7] = $this->language->get('column_hwfechaing');
								
				$this->cellColor($workbook,'A'.$start.':H'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['ROWNUM'];
			  $data[2] = $p['HWPACKING'];
			  $data[3] = $p['HWCONTRACT'];
			  $data[4] = $p['HWFACTURA'];
			  $data[5] = $p['HWDELIVERYNOTICE'];
			  $data[6] = $p['HWPO'];
			  $data[7] = $p['HWFECHAING'];
			  			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function outbounds($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/outbounds'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportOutboundsExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Packing');
	$worksheet->setCellValue('B'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_rownum');
				$data[2] = $this->language->get('column_hwmrno');
				$data[3] = $this->language->get('column_hwfechasol');
				$data[4] = $this->language->get('column_hwdespacho');
				$data[5] = $this->language->get('column_hwfdespacho');
				$data[6] = $this->language->get('column_sitnom');
								
				$this->cellColor($workbook,'A'.$start.':G'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['ROWNUM'];
			  $data[2] = $p['HWMRNO'];
			  $data[3] = $p['HWFECHASOL'];
			  $data[4] = $p['HWDESPACHO'];
			  $data[5] = $p['HWFDESPACHO'];
			  $data[6] = $p['SITNOM'];
			  			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function returns($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/returns'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportReturnsExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Packing');
	$worksheet->setCellValue('B'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_rownum');
				$data[2] = $this->language->get('column_hwpacking');
				$data[3] = $this->language->get('column_hwfechaing');
				$data[4] = $this->language->get('column_sitnom');
				$data[5] = $this->language->get('column_hwtecnico');
								
				$this->cellColor($workbook,'A'.$start.':E'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['ROWNUM'];
			  $data[2] = $p['HWPACKING'];
			  $data[3] = $p['HWFECHAING'];
			  $data[4] = $p['SITNOM'];
			  $data[5] = $p['HWTECNICO'];
			  			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function overtime($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/returns'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportReturnsExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Packing');
	$worksheet->setCellValue('B'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_rownum');
				$data[2] = $this->language->get('column_hwpacking');
				$data[3] = $this->language->get('column_hwfechaing');
				$data[4] = $this->language->get('column_sitnom');
				$data[5] = $this->language->get('column_hwtecnico');
								
				$this->cellColor($workbook,'A'.$start.':E'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['ROWNUM'];
			  $data[2] = $p['HWPACKING'];
			  $data[3] = $p['HWFECHAING'];
			  $data[4] = $p['SITNOM'];
			  $data[5] = $p['HWTECNICO'];
			  			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function generalstockbycode($workbook,$y,$param) {
	
	$this->load->model('report/tigo');
	$this->load->language('report/returns'); 
	
	$worksheet = $workbook->getActiveSheet();
	
	$detalle = $this->model_report_tigo->getStockReportReturnsExcel($param); 
	$data=null;
  
	$j=0;
	$totExistencia = 0;
	$totDisponible = 0;
	$totDisponibleDa = 0;
	
	$worksheet->setCellValue('A'.(6),'Packing');
	$worksheet->setCellValue('B'.(6),$param['filter_hwpacking']);
	$start=$y + 1;
	$i=$start;
				  
		  foreach ($detalle as $p) {			
			  
			  if ($i==$start) {
				  
				$data[1] = $this->language->get('column_rownum');
				$data[2] = $this->language->get('column_hwpacking');
				$data[3] = $this->language->get('column_hwfechaing');
				$data[4] = $this->language->get('column_sitnom');
				$data[5] = $this->language->get('column_hwtecnico');
								
				$this->cellColor($workbook,'A'.$start.':E'.$start, 'ffbb33');	
				$this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
  
				$i += 1;	
			  }
  
			  $data[1] = $p['ROWNUM'];
			  $data[2] = $p['HWPACKING'];
			  $data[3] = $p['HWFECHAING'];
			  $data[4] = $p['SITNOM'];
			  $data[5] = $p['HWTECNICO'];
			  			  
			  $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles );
			  
			  $i += 1;
			  $j = 0;
		  	}
}

function generarReporte($workbook,$y,$data) {
	$util = New Util();
		
	$workbook->getActiveSheet()->setCellValue('A1',strtoupper($data['titulo']));
	$workbook->getActiveSheet()->setCellValue('A2',$this->session->data['tipdesce']);
	$workbook->getActiveSheet()->setCellValue('A3','DEL: '.$data['filter_date_start'].' al '.$data['filter_date_end']);
	$workbook->getActiveSheet()->setCellValue('A4','FECHA GENERACIÓN: '.$util->getTime());
	
  if ($data['reporte']=='stockreport') {
    $this->stockreport($workbook,$y,$data);	
  } elseif ($data['reporte']=='bypackinglist') {
	$this->bypackinglist($workbook,$y,$data);	  
  } elseif ($data['reporte']=='bymovements') {
	$this->bymovements($workbook,$y,$data);	  
  } elseif ($data['reporte']=='bybomnumber') {
	$this->bybomnumber($workbook,$y,$data);	  
  } elseif ($data['reporte']=='damaged') {
	$this->damaged($workbook,$y,$data);	  
  } elseif ($data['reporte']=='averangeoccupancy') {
	$this->averangeoccupancy($workbook,$y,$data);	  
  } elseif ($data['reporte']=='bydata') {
	$this->bydata($workbook,$y,$data);	  
  } elseif ($data['reporte']=='packinglist') {
	$this->packinglist($workbook,$y,$data);	  
  } elseif ($data['reporte']=='bysite') {
	$this->bysite($workbook,$y,$data);	  
  } elseif ($data['reporte']=='bysiteindetailthemovement') {
	$this->bysiteindetailthemovement($workbook,$y,$data);	  
  } elseif ($data['reporte']=='inbounds') {
	$this->inbounds($workbook,$y,$data);	  
  } elseif ($data['reporte']=='outbounds') {
	$this->outbounds($workbook,$y,$data);	  
  } elseif ($data['reporte']=='returns') {
	$this->returns($workbook,$y,$data);	  
  } elseif ($data['reporte']=='overtime') {
	$this->overtime($workbook,$y,$data);	  
  } elseif ($data['reporte']=='generalstockbycode') {
	$this->generalstockbycode($workbook,$y,$data);	  
  } elseif ($data['reporte']=='summaryofmovement') {
	$this->summaryofmovement($workbook,$y,$data);	  
  } elseif ($data['reporte']=='inboundbydate') {
	$this->inboundbydate($workbook,$y,$data);	  
  }	 


  $workbook->setActiveSheetIndex(0);	
}	
	
public function download($data) {
	require_once('/var/www/html/sis/webpicking/system/PHPExcel/Classes/PHPExcel.php' );     
    spl_autoload_unregister(array('YiiBase', 'autoload'));

	require_once('/var/www/html/sis/webpicking/system/PHPExcel/Classes/PHPExcel.php' );
	
	set_time_limit(1800);
	$fileType = 'Excel2007';
	$workbook = new PHPExcel();

	$filename = "/var/www/html/sis/webpicking/templates/template01.xlsx";

	$workbook = PHPExcel_IOFactory::load($filename);

	$workbook->setActiveSheetIndex(0);
			
	$datetime = date('Y-m-d');
	$filename = $data['reporte'].'-'.$datetime;
	$filename .= '.xlsx';
	
	$worksheet = $workbook->getActiveSheet();
	$worksheet->getColumnDimensionByColumn(0)->setWidth(18);
			
	$y = $this->generarReporte($workbook,7,$data);
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
	$objWriter->setPreCalculateFormulas(false);
	$objWriter->save('php://output');

	// Clear the spreadsheet caches
	$this->clearSpreadsheetCache();
	exit;	
}
 
}
?>
