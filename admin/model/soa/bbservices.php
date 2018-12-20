<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
  
  Metodos implementados:
    getFreeUnits
	QueryCDR
*/
class ModelSoaBbservices extends Model {

public function getFreeUnits($data = array()) {
$queryFreeUnits = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bbs="http://www.huawei.com/bme/cbsinterface/bbservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:bbc="http://www.huawei.com/bme/cbsinterface/bbcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bbs:QueryFreeUnitRequestMsg>
        <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>filter_bmploginsystemcode</cbs:LoginSystemCode>
               <cbs:Password>filter_bmppassword</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>  
         <QueryFreeUnitRequest>
            <bbs:QueryObj>
               <bbs:SubAccessCode>
                  <bbc:PrimaryIdentity>filter_NNN8</bbc:PrimaryIdentity>
               </bbs:SubAccessCode>
            </bbs:QueryObj>
         </QueryFreeUnitRequest>
      </bbs:QueryFreeUnitRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $util = New Util(); 
  $url       = 'http://filter_ip:8080/services/BbServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $queryFreeUnits = str_replace('[secuencia]', $secuencia, $queryFreeUnits);   
   
  foreach ($data as $key=>$parametro) {
  	$queryFreeUnits = str_replace($key,$parametro,$queryFreeUnits);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($queryFreeUnits,'QueryFreeUnit',$url);
  /*
    Analizando respuesta: 
   */  
   $xml_queryFreeUnits = new SimpleXMLElement($result);
   $nsqb = $xml_queryFreeUnits->getNamespaces(true);
   $xml_queryFreeUnits->registerXPathNamespace('bbs', $nsqb['bbs']);
   $xml_queryFreeUnits->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_queryFreeUnits->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_queryFreeUnits->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (int)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);  
    
   if ((int)$ResultCode[0]==0) {

   foreach ($xml_queryFreeUnits->xpath('//QueryFreeUnitResult') as $QueryFreeUnitResult) {
	   
	   foreach ($QueryFreeUnitResult->xpath('bbs:FreeUnitItem') as $FreeUnitItem) {
	          $FreeUnitType = $FreeUnitItem->xpath('bbs:FreeUnitType');  
	          $FreeUnitTypeName = $FreeUnitItem->xpath('bbs:FreeUnitTypeName');
	          $MeasureUnitName  = $FreeUnitItem->xpath('bbs:MeasureUnitName');
	          $TotalUnusedAmount  = $FreeUnitItem->xpath('bbs:TotalUnusedAmount');
			  
		   	 foreach ($FreeUnitItem->xpath('bbs:FreeUnitItemDetail') as $FreeUnitDetail) {
		            $FreeUnitInstanceID = $FreeUnitDetail->xpath('bbs:FreeUnitInstanceID');
					$InitialAmount      = $FreeUnitDetail->xpath('bbs:InitialAmount');
					$EffectiveTime      = $FreeUnitDetail->xpath('bbs:EffectiveTime');
		            $ExpireTime         = $FreeUnitDetail->xpath('bbs:ExpireTime');
					$CurrentAmount      = $FreeUnitDetail->xpath('bbs:CurrentAmount');
					
     /*******************************************/
     $sql = "select comverse_name,sort_order
          from testing.accounts
         where balancetype = '".$FreeUnitType[0]."'
		   and offering_id = '".$data['offering_id']."'";
	  
	 /*******************************************/
	 $query = $this->mysql->query($sql);
     $billetera = $query->row['comverse_name'];
	 $sort_order= $query->row['sort_order'];
				
			  $infobilleteras[] = array(
			                     (string)$FreeUnitType[0]
			                    ,(string)$TotalUnusedAmount[0]
								,(string)$FreeUnitInstanceID[0]
								,(string)$FreeUnitTypeName[0]
								,(string)$ExpireTime[0]
								,$billetera
								,(string)$CurrentAmount[0]
								,(string)$InitialAmount[0]
								,(string)$EffectiveTime[0]
								,'sort_order'=>$sort_order);
	         }			 
	   }
     $util->aasort($infobilleteras,'sort_order');
    }   
    $results['response']['billeteras'] = $infobilleteras;
   }

    return $results;
}	

public function getQueryCDR($data = array()) {
$queryCDR = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bbs="http://www.huawei.com/bme/cbsinterface/bbservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:bbc="http://www.huawei.com/bme/cbsinterface/bbcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bbs:QueryCDRRequestMsg>
         <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>filter_bmploginsystemcode</cbs:LoginSystemCode>
               <cbs:Password>filter_bmppassword</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>       
         <QueryCDRRequest>
            <bbs:CustAccessCode>
            <bbc:PrimaryIdentity>filter_numero_celular</bbc:PrimaryIdentity>
            </bbs:CustAccessCode> 
            <bbs:SubAccessCode>
               <bbc:PrimaryIdentity>filter_numero_celular</bbc:PrimaryIdentity>
            </bbs:SubAccessCode>
            <bbs:TimePeriod>
               <bbs:StartTime>filter_fecha_inicia</bbs:StartTime>
                 <bbs:EndTime>filter_fecha_fin</bbs:EndTime>
            </bbs:TimePeriod>
            <bbs:TotalCDRNum>0</bbs:TotalCDRNum>
            <bbs:BeginRowNum>0</bbs:BeginRowNum>
            <bbs:FetchRowNum>499</bbs:FetchRowNum>
         </QueryCDRRequest>
      </bbs:QueryCDRRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/BbServices';
  $secuencia = "Testing".date("YmdHis");
  $queryCDR = str_replace('[secuencia]', $secuencia, $queryCDR);
   
  foreach ($data as $key=>$parametro) {
  	$queryCDR = str_replace($key,$parametro,$queryCDR);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $queryCDR = str_replace('filter_fecha_inicia','20161003000000',$queryCDR);
  $queryCDR = str_replace('filter_fecha_fin'   ,'20161003235959',$queryCDR);
  $queryCDR = str_replace('filter_category','0',$queryCDR);
  
if (empty($data['filter_numero_celular'])) {return null;}

  $url       = 'http://filter_ip:8080/services/BbServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $queryCDR = str_replace('[secuencia]', $secuencia, $queryCDR);
  //            <bbs:ServiceCategory>filter_category</bbs:ServiceCategory> 

  foreach ($data as $key=>$parametro) {
  	$queryCDR = str_replace($key,$parametro,$queryCDR);
	$url = str_replace($key,$parametro,$url);
  }
 
  $result=$soa->getResponse($queryCDR,'QueryCDR',$url);
  /*
    Analizando respuesta: 
   */    
   $xml_queryCDR = new SimpleXMLElement($result);
   $ns = $xml_queryCDR->getNamespaces(true);
   $xml_queryCDR->registerXPathNamespace('ars', $ns['ars']);
   $xml_queryCDR->registerXPathNamespace('cbs', $ns['cbs']);
  
   $ResultCode = $xml_queryCDR->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_queryCDR->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);
	
  if (strcmp((string)$ResultCode[0],"0")==0) {

     foreach ($xml_queryCDR->xpath('//QueryCDRResult') as $QueryCDRResult) {
	          $TotalCDRNum = $QueryCDRResult->xpath('bbs:TotalCDRNum');  
	          $BeginRowNum = $QueryCDRResult->xpath('bbs:BeginRowNum');
	          $FetchRowNum  = $QueryCDRResult->xpath('bbs:FetchRowNum');
       $i=0; 
	   $n=0;

	   foreach ($QueryCDRResult->xpath('bbs:CDRInfo') as $CDRInfo) {
		      
	          $cdrseq          = $CDRInfo->xpath('bbs:CdrSeq');  
	          $servicecategory = $CDRInfo->xpath('bbs:ServiceCategory');
	          $serivetype      = $CDRInfo->xpath('bbs:SeriveType');
			  $servicetypename = $CDRInfo->xpath('bbs:ServiceTypeName');
	          $subkey          = $CDRInfo->xpath('bbs:SubKey');
	          $refundindicator = $CDRInfo->xpath('bbs:RefundIndicator');	
			  $starttime       = $CDRInfo->xpath('bbs:StartTime');
			  $endtime         = $CDRInfo->xpath('bbs:EndTime');
			  $flowtype        = $CDRInfo->xpath('bbs:FlowType');

             $sql = "SELECT servicecategory_id,description
                       FROM servicecategory 
					  WHERE servicecategory_id = '".(int)$servicecategory[0]."'";
					  
	        $query = $this->mysql->query($sql);
            $description = $query->row['description'];	
			  
			$refundindicator=(int)$refundindicator[0];
			
			if ($i>=(int)$data['start']&&$n<(int)$data['limit']) {
			//echo "*".$i.">=".(int)$data['start']."&&".$n."<".(int)$data['limit']."<br>";	
            $cdrinfo[] = array('cdrseq'          => (string)$cdrseq[0],
			                     'servicecategory' => (string)$servicecategory[0],
								 'description'     => (string)$description,
								 'serivetype'      => (string)$serivetype[0],
								 'servicetypename' => (string)$servicetypename[0],
								 'subkey'          => (string)$subkey[0],
								 'refundindicator' => $refundindicator,
								 'refunddescription' => $refundindicator ==1 ? 'refund' : 'fee deduction',
								 'starttime'       => date("d-m-Y H:i:s", strtotime((string)$starttime[0])),
								 'endtime'         => date("d-m-Y H:i:s", strtotime((string)$endtime[0])),
								 'flowtype'        => (string)$flowtype[0] ."-".((string)$flowtype[0] =="1" ? 'MO' 
								                          :  (string)$flowtype[0] =="2" ? 'MT' 
														  :  (string)$flowtype[0] =="3" ? "CF" : ""),
								);
			 $n=$n+1;						
		    } 
		$i=$i+1;
       	
	   }
                   $results[] = array('totalcdrnum' => (string)$TotalCDRNum[0],
			                   'beginrownum' => (string)$BeginRowNum[0],
                               'fetchrownum' => (string)$FetchRowNum[0],
                               'cdrs'        => $cdrinfo							   
			);

    }

  }  
  return $results;	
} 
  
}