<?php
class Util {
	
//Metodos para ordenar arreglos
public function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

public function rasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    arsort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}
public function getDate() {
date_default_timezone_set('America/Guatemala');
$tstamp = (String)date('Ymd', time());	
return $tstamp;	
} 

public function concatCodeArea($numero_celular) {
	return  strlen($numero_celular)==8 ? '502'.$numero_celular : $numero_celular;
}

public function removeCodeArea($numero_celular) {
	return  strlen($numero_celular)>8 ? (int)$numero_celular-50200000000 : $numero_celular;
}

public function getTime() {
date_default_timezone_set('America/Guatemala');
$tstamp = (String)date('Y-m-d H:i:s', time());	
return $tstamp;	
}

public function getDateHour() {
    date_default_timezone_set('America/Guatemala');
    $tstamp = (String)date('Y-m-d H', time());	
    return $tstamp;	
    } 

}
