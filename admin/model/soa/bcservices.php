<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
  
  Metodos implementados:
    checkConexion($data = array())
	getEstado($status)
    getQueryCostumerInfo     - Pendiente
	getSupplementaryOffering - Corregido
	getQueryOfferingInstFF   - Pendiente
    DelSubOffering
*/

class ModelSoaBcservices extends Model {

public function checkConexion($data = array()) {
$queryCustomerInfo = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryCustomerInfoRequestMsg>
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
         <QueryCustomerInfoRequest>
            <bcs:QueryObj>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>filter_numero_celular</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:QueryObj>
         </QueryCustomerInfoRequest>
      </bcs:QueryCustomerInfoRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/BcServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $queryCustomerInfo = str_replace('[secuencia]', $secuencia, $queryCustomerInfo);
   
  foreach ($data as $key=>$parametro) {
  	$queryCustomerInfo = str_replace($key,$parametro,$queryCustomerInfo);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($queryCustomerInfo,'QueryCustomerInfo',$url);

  if ($result['code']==-1) {$huboError=1;}  
  return $huboError;
}	

public function getEstado($status) {
	$descripcion="";
	switch ((int)$status) {
		case 1: $descripcion = 'Idle';
		break;
		case 2: $descripcion = 'Active';
		break;
		case 3: $descripcion = 'Suspended(Rech)(S1)';
		break;
		case 4: $descripcion = 'PostActive(S3)';
		break;
		case 5: $descripcion = 'Suspended(S2)';
		break;
		case 8: $descripcion = 'PostActive(S4)';
		break;
	}
   return $descripcion;
}

public function getQueryCostumerInfo($data = array()) {
$queryCustomerInfo = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryCustomerInfoRequestMsg>
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
         <QueryCustomerInfoRequest>
            <bcs:QueryObj>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>filter_NNN8</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:QueryObj>
         </QueryCustomerInfoRequest>
      </bcs:QueryCustomerInfoRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/BcServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $queryCustomerInfo = str_replace('[secuencia]', $secuencia, $queryCustomerInfo);
   
  foreach ($data as $key=>$parametro) {
  	$queryCustomerInfo = str_replace($key,$parametro,$queryCustomerInfo);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($queryCustomerInfo,'QueryCustomerInfo',$url);

 if ($result===FALSE)
  {
   $huboError=1;	  
  }
  
   if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);
   $xml->registerXPathNamespace('bcs', $ns['bcs']);
   $xml->registerXPathNamespace('bcc', $ns['bcc']);
   //var_dump($xml);

   $Version=$xml->xpath('//cbs:Version');
   $ResultCode=$xml->xpath('//cbs:ResultCode');
   $ResultDesc=$xml->xpath('//cbs:ResultDesc');
   $SubscriberKey=$xml->xpath('//bcs:SubscriberKey');
   $Code = null;
   $Value = null;

   $visualizar['C_SUBSCRIBER_INFO_1']   ='cadena';
   $visualizar['C_SUBCOS_FOR_EIPIN']    ='cadena';
   $visualizar['C_SUB_LASTRECHARGETIME']='fecha';
      
   foreach($xml->xpath('//bcc:SubBasicInfo//bcc:SubProperty') as $SubProperty) {
	  $Code=$SubProperty->xpath('bcc:Code');
      $Value=$SubProperty->xpath('bcc:Value');
	  
	  if (in_array((string)$Code[0], array_keys($visualizar))) { 

	     if ($visualizar[(string)$Code[0]]=='fecha') {
		   $valor = date($this->language->get('datetime_format'), strtotime((string)$Value[0]));	 
		 }
		 else {
			 $valor = (string)$Value[0];
		 }
		 
		 $subproperties[] = array (
		     'Code' => (string)$Code[0],
			 'Value' =>  $valor
		 ); 
		 
		 
	  }
   }
 
   /*
   foreach ($xml->xpath('//bcc:SubBasicInfo//bcc:SubProperty[bcc:Code="C_SUBSCRIBER_INFO_1"]') as $SubProperty)
   {
    $Code=$SubProperty->xpath('bcc:Code');
    $Value=$SubProperty->xpath('bcc:Value');
   }
   */
   
   $UserCustomerKey=$xml->xpath('//bcc:UserCustomerKey');
   $SubIdentity=$xml->xpath('//bcc:SubIdentity//bcc:SubIdentity');
   $offeringID=$xml->xpath('//bcc:OfferingID'); 
   $Status=$xml->xpath('//bcc:Status'); 
   $ActivationTime=$xml->xpath('//bcs:ActivationTime');

   
   $activatime=null;
   $endias=null;
   
   if ($ActivationTime) {
	 $now    = time();
	 $fechai = strtotime((string)$ActivationTime[0]);
	 $activationtime = date("d-m-Y H:i:s",strtotime((string)$ActivationTime[0]));
	 $endias     = floor(($now - $fechai)/(60*60*24));
   }   
   
   $offeringdesc = (string)$ResultDesc[0];


   if (!empty($data['filter_numero_celular'])) {
	   
				 /*******************************************/
             $sql = "select main_offer_name
                       from main_offer
                      where main_offer_id = ".(int)$offeringID[0];
	         /*******************************************/
	        $query = $this->mysql->query($sql);
            $main_offer_name = $query->row['main_offer_name'];	   
	   
	        $status = (string)$Status[0];

   $results[] = array(
				'version'         => (string)$Version[0],
				'resultcode'      => (string)$ResultCode[0],
				'resultdesc'      => $offeringdesc,
				'subscriberkey'   => (string)$SubscriberKey[0],
				'usercustomerkey' => (string)$UserCustomerKey[0],
				'subIdentity'     => (string)$SubIdentity[0],
				'offeringid'      => (int)$offeringID[0],
				'offeringname'    => $main_offer_name,
				'status'          => $status."-".$this->getEstado($status),
				'activationtime'  => (string)$ActivationTime[0],
				'subpropertycode' => (string)$Code[0],
			    'subpropertyvalue'=> (string)$Value[0],
				'activationtime'  => $activationtime,
				'dias_antiguedad' => $endias,
				'subproperties'   => $subproperties
			);
   }
   else {
   $results[] = array(
				'version'         => "",
				'resultcode'      => "",
				'resultdesc'      => "",
				'subscriberkey'   => "",
				'usercustomerkey' => "",
				'subIdentity'     => "",
				'offeringid'      => "",
				'offeringname'    => "",
				'status'          => "",
				'activationtime'  => "",
				'subpropertycode' => "",
			    'subpropertyvalue'=> "",
				'activationtime'  => "",
				'dias_antiguedad' => "",
				'subproperties'   => ""			
			);	
   }   
   }
   /*************************************************************************************************************/
   return $results;
}	

public function getSupplementaryOffering($data = array()) {
$queryCustomerInfo = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryCustomerInfoRequestMsg>
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
         <QueryCustomerInfoRequest>
            <bcs:QueryObj>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>filter_NNN8</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:QueryObj>
         </QueryCustomerInfoRequest>
      </bcs:QueryCustomerInfoRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/BcServices';
  $secuencia = "Testing".date("YmdHis");
  $queryCustomerInfo = str_replace('[secuencia]', $secuencia, $queryCustomerInfo);
  $util = new Util();
   
  foreach ($data as $key=>$parametro) {
  	$queryCustomerInfo = str_replace($key,$parametro,$queryCustomerInfo);
	$url = str_replace($key,$parametro,$url);
  } 
   
  $result=$soa->getResponse($queryCustomerInfo,'QueryCustomerInfo',$url);
  /*
    Analizando respuesta: 
   */    
   $xml_queryCustomerInfo = new SimpleXMLElement($result);
   $ns = $xml_queryCustomerInfo->getNamespaces(true);
   $xml_queryCustomerInfo->registerXPathNamespace('cbs', $ns['cbs']);
   $xml_queryCustomerInfo->registerXPathNamespace('bcs', $ns['bcs']);
   $xml_queryCustomerInfo->registerXPathNamespace('bcc', $ns['bcc']);   

   $ResultCode = $xml_queryCustomerInfo->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_queryCustomerInfo->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);   
	
   if (strcmp((string)$ResultCode[0],"0")==0) {
   foreach ($xml_queryCustomerInfo->xpath('//bcs:SupplementaryOffering') as $SupplementaryOffering) {
	      $Status          = $SupplementaryOffering->xpath('bcc:Status');
		  $EffectiveTime   = $SupplementaryOffering->xpath('bcs:EffectiveTime');
		  $ActivationTime   = $SupplementaryOffering->xpath('bcs:ActivationTime');
		  $ExpirationTime  = $SupplementaryOffering->xpath('bcs:ExpirationTime');
		  
		  foreach ($SupplementaryOffering->xpath('bcc:OfferingKey') as $OfferingKey) {
		  $OfferingID = $OfferingKey->xpath('bcc:OfferingID');
	      $PurchaseSeq = $OfferingKey->xpath('bcc:PurchaseSeq');
		  }
		  
		  $sql = "select offering_name from offering o where offering_id = '".(string)$OfferingID[0]."'";
	      $query = $this->mysql->query($sql);
          $offer_name = $query->row['offering_name'];

		  $infoofferings[] = array(
				'offeringid'     => (string)$OfferingID[0],
				'offeringdesc'   => $offer_name,
				'purchaseseq'    => (string)$PurchaseSeq[0],
				'effectivetime'  => date("d-m-Y H:i:s", strtotime((string)$EffectiveTime[0])),
				'expirationtime' => date("d-m-Y H:i:s", strtotime((string)$ExpirationTime[0])),
				'activationtime' => date("d-m-Y H:i:s", strtotime((string)$ActivationTime[0])),
				'status'         => (string)$Status[0]=="1" ? 'Idle' : 
				                   ((string)$Status[0]=="4" ? 'Suspend' : "Active")
			);
			
   }
   $util->rasort($results,'effectivetime');
   $results['response']['offerings'] = $infoofferings;
   } else {
	     	$this->log->write('ERROR: ' . (string)$ResultDesc[0]." Archivo: model/soa/bcservices.php Funcion:getSupplementaryOffering");
          }

   return $results;
}

public function getQueryOfferingInstFF($supplementary,$data) {
$QueryOIProperty1 = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryOfferingInstPropertyRequestMsg>
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
               <cbs:RemoteIP>192.168.128.42</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>101</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>
         <QueryOfferingInstPropertyRequest>
            <bcs:OfferingOwner>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>filter_numero_celular</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:OfferingOwner>
EOD;

$QueryOIProperty2  = <<<EOD
      </QueryOfferingInstPropertyRequest>
      </bcs:QueryOfferingInstPropertyRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

$instancias=null;
foreach ($supplementary['response']['offerings'] as $sup) {
 if ($sup['offeringid']=='1322663043') {
       $instancias = $instancias . "<bcs:OfferingInst>
                                    <bcs:OfferingKey>
                                    <bcc:OfferingID>1322663043</bcc:OfferingID>
                                    <bcc:PurchaseSeq>".$sup['purchaseseq']."</bcc:PurchaseSeq>
                                    </bcs:OfferingKey>
                                    </bcs:OfferingInst>";
 } 
}

if ($instancias) {
	$QueryOIProperty = $QueryOIProperty1.$instancias.$QueryOIProperty2;
	//------------------------------------------------------------------
  $soa       = New Soa();
  $url       = 'http://filter_ip:8080/services/BcServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $QueryOIProperty = str_replace('[secuencia]', $secuencia, $QueryOIProperty);
   
  foreach ($data as $key=>$parametro) {
  	$QueryOIProperty = str_replace($key,$parametro,$QueryOIProperty);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($QueryOIProperty,'QueryOfferingInstProperty',$url);

  /*
    Analizando respuesta: 
  */    
  $xml_QueryOIProperty = new SimpleXMLElement($result);
  $ns = $xml_QueryOIProperty->getNamespaces(true);
  $xml_QueryOIProperty->registerXPathNamespace('cbs', $ns['cbs']);
  $xml_QueryOIProperty->registerXPathNamespace('bcs', $ns['bcs']);
  $xml_QueryOIProperty->registerXPathNamespace('bcc', $ns['bcc']);   

   $ResultCode = $xml_QueryOIProperty->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_QueryOIProperty->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);   
	
	
    if (strcmp((string)$ResultCode[0],"0")==0) {
  
      foreach($xml_QueryOIProperty->xpath('//QueryOfferingInstPropertyResult//bcs:OfferingInst') as $QueryOfferingIPR) {
		$OfferingID  = $QueryOfferingIPR->xpath('bcs:OfferingKey/bcc:OfferingID');
        $PurchaseSeq = $QueryOfferingIPR->xpath('bcs:OfferingKey/bcc:PurchaseSeq');
		$EffectiveTime = $QueryOfferingIPR->xpath('bcs:ProductInst/bcs:ProductInstProperty/bcs:EffectiveTime');
		$ExpirationTime = $QueryOfferingIPR->xpath('bcs:ProductInst/bcs:ProductInstProperty/bcs:ExpirationTime');
		
		$PropCode=$QueryOfferingIPR->xpath('bcs:ProductInst/bcs:ProductInstProperty/bcc:PropCode');
		$PropType=$QueryOfferingIPR->xpath('bcs:ProductInst/bcs:ProductInstProperty/bcc:PropType');
		
		foreach ($QueryOfferingIPR->xpath('bcs:ProductInst/bcs:ProductInstProperty/bcc:SubPropInst') as $SubPropInst) {
			$SubPropCode=$SubPropInst->xpath('bcc:SubPropCode');
			$Value=$SubPropInst->xpath('bcc:Value');
			
			$valores[] = array (
			  'subpropcode' => (string)$SubPropCode[0],
			  'value'       => (string)$Value[0]
			);
		}
		
		$favorito[]= array (
		    'offeringid'     => (string)$OfferingID[0],
			'purchaseseq'    => (string)$PurchaseSeq[0],
			'effectivetime'  => (string)$EffectiveTime[0],
			'expirationtime' => (string)$ExpirationTime[0],
			'propcode'       => (string)$PropCode[0],
			'proptype'       => (string)$PropType[0],
			'propiedades'    => $valores
		);
		unset($valores);
	  }
  
    }
    $results['response']['favoritos'] = $favorito;	
	//------------------------------------------------------------------
}
    return $results;
}

public function delOffering($data = array()) {
$ChangeSubOfferingRequest = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                                 xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
								 xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
								 xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:ChangeSubOfferingRequestMsg>
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
         <ChangeSubOfferingRequest>
            <bcs:SubAccessCode>
               <bcc:PrimaryIdentity>filter_numero_celular</bcc:PrimaryIdentity>
            </bcs:SubAccessCode>
            <bcs:SupplementaryOffering>
            	<bcs:DelOffering>
            	<bcs:OfferingKey>
            		<bcc:OfferingID>filter_OfferingId</bcc:OfferingID>
            		<bcc:PurchaseSeq>filter_PurchaseSeq</bcc:PurchaseSeq>
            	</bcs:OfferingKey>
            	</bcs:DelOffering>
            </bcs:SupplementaryOffering>
         </ChangeSubOfferingRequest>
      </bcs:ChangeSubOfferingRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
$soa = New Soa();
/*
${=(new java.text.SimpleDateFormat("yyyyMMddHHmmss")).format(new Date())}${=(int)(Math.random()*99)}
*/
  $url       = 'http://filter_ip:8080/services/BcServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $ChangeSubOfferingRequest = str_replace('[secuencia]', $secuencia, $ChangeSubOfferingRequest);
   
  foreach ($data as $key=>$parametro) {
  	$ChangeSubOfferingRequest = str_replace($key,$parametro,$ChangeSubOfferingRequest);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($ChangeSubOfferingRequest,'ChangeSubOffering',$url);
  
  /*
    Analizando respuesta: 
   */    
   $xml_ChangeSubOfferingRequest = new SimpleXMLElement($result);
   $ns = $xml_ChangeSubOfferingRequest->getNamespaces(true);
   $xml_ChangeSubOfferingRequest->registerXPathNamespace('cbs', $ns['cbs']);
   $xml_ChangeSubOfferingRequest->registerXPathNamespace('bcs', $ns['bcs']);
   $xml_ChangeSubOfferingRequest->registerXPathNamespace('bcc', $ns['bcc']);   

   $ResultCode = $xml_ChangeSubOfferingRequest->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_ChangeSubOfferingRequest->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);   
	
   return $results;	
}

public function delSubOffering($data = array()) {
$ChangeSubOfferingRequest = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                                 xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" 
								 xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
								 xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:ChangeSubOfferingRequestMsg>
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
         <ChangeSubOfferingRequest>
            <bcs:SubAccessCode>
               <bcc:PrimaryIdentity>filter_numero_celular</bcc:PrimaryIdentity>
            </bcs:SubAccessCode>
            <bcs:SupplementaryOffering>
            	<bcs:DelOffering>
            	<bcs:OfferingKey>
            		<bcc:OfferingID>filter_OfferingId</bcc:OfferingID>
            		<bcc:PurchaseSeq>filter_PurchaseSeq</bcc:PurchaseSeq>
            	</bcs:OfferingKey>
            	</bcs:DelOffering>
            </bcs:SupplementaryOffering>
         </ChangeSubOfferingRequest>
      </bcs:ChangeSubOfferingRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

/*
${=(new java.text.SimpleDateFormat("yyyyMMddHHmmss")).format(new Date())}${=(int)(Math.random()*99)}
*/
  $url       = 'http://filter_ip:8080/services/BcServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $ChangeSubOfferingRequest = str_replace('[secuencia]', $secuencia, $ChangeSubOfferingRequest);
   
  foreach ($data as $key=>$parametro) {
  	$ChangeSubOfferingRequest = str_replace($key,$parametro,$ChangeSubOfferingRequest);
	$url = str_replace($key,$parametro,$url);
  } 
  $soa = New Soa();
  $result=$soa->getResponse($ChangeSubOfferingRequest,'ChangeSubOffering',$url);
  
 if ($result===FALSE)
  {
   $huboError=1;	  
  }
  
   if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);
   $xml->registerXPathNamespace('bcs', $ns['bcs']);
   $xml->registerXPathNamespace('bcc', $ns['bcc']);
 
   $Version=$xml->xpath('//cbs:Version');
   $ResultCode=$xml->xpath('//cbs:ResultCode');
   $ResultDesc=$xml->xpath('//cbs:ResultDesc');

   if (!empty($data['filter_numero_celular'])) {

   $results[] = array(
				'version'         => (string)$Version[0],
				'resultcode'      => (string)$ResultCode[0],
				'resultdesc'      => (string)$ResultDesc[0]
			);
   }
   else {
   $results[] = array(
				'version'         => "",
				'resultcode'      => "",
				'resultdesc'      => ""
			);	
   }   
   
   /*************************************************************************************************************/
   return $results;
}	
}


}