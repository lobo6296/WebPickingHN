<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
 
  Metodos implementados:
    delBalance                 Corregido
	delFreeUnit                Corregido
	getQueryRechargeLogResult  Pendiente
	getBalanceResult           Corregido
*/
class ModelSoaArservices extends Model {

public function Adjustment($data = array()) {
$Adjustment = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:AdjustmentRequestMsg>
         <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>1002</cbs:LoginSystemCode>
               <cbs:Password>1vNykF4orZPvXMT57DdNFg==</cbs:Password>
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
            <cbs:AdditionalProperty>
               <cbs:Code>?</cbs:Code>
               <cbs:Value>?</cbs:Value>
            </cbs:AdditionalProperty>
         </RequestHeader>
         <AdjustmentRequest>
            <ars:AdjustmentSerialNo>[secuencia]</ars:AdjustmentSerialNo>
            <ars:AdjustmentObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>48724939</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:AdjustmentObj>
            <ars:OpType>1</ars:OpType>
            <ars:AdjustmentInfo>
               <arc:BalanceType>C_MAIN_ACCOUNT</arc:BalanceType>
               <arc:BalanceID>160634715</arc:BalanceID>
               <arc:AdjustmentType>1</arc:AdjustmentType>
               <arc:AdjustmentAmt>1000</arc:AdjustmentAmt>
               <arc:CurrencyID>1058</arc:CurrencyID>
            </ars:AdjustmentInfo>
    		  <ars:AdditionalProperty>
		  <arc:Code>C_REMARKS</arc:Code>
		  <arc:Value>Testing</arc:Value>
		  </ars:AdditionalProperty>        
            <ars:AdditionalProperty>
               <arc:Code>C_ADJUST_TYPE</arc:Code>
               <arc:Value>1</arc:Value>
            </ars:AdditionalProperty>
         </AdjustmentRequest>
      </ars:AdjustmentRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $util = New Util();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $Adjustment = str_replace('[secuencia]', $secuencia, $Adjustment);
  
  foreach ($data as $key=>$parametro) {
  	$Adjustment = str_replace($key,$parametro,$Adjustment);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($Adjustment,'Adjustment',$url); 

  /*
    Analizando respuesta: 
   */  
   $xml_Adjustment = new SimpleXMLElement($result);
   $nsqb = $xml_Adjustment->getNamespaces(true);
   $xml_Adjustment->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_Adjustment->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_Adjustment->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_Adjustment->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_Adjustment->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

 if (strcmp((string)$ResultCode[0],"0")==0) {
   foreach ($xml_Adjustment->xpath('//AdjustmentResult') as $AdjustmentResult) {
	   $AdjustmentSerialNo = $AdjustmentResult->xpath('ars:AdjustmentSerialNo');
   foreach ($AdjustmentResult->xpath('ars:AdjustmentInfo') as $AdjustmentInfo) {
	 $BalanceType         = $AdjustmentInfo->xpath('arc:BalanceType');
     $BalanceID           = $AdjustmentInfo->xpath('arc:BalanceID');
     $BalanceTypeName     = $AdjustmentInfo->xpath('arc:BalanceTypeName');
	 $OldBalanceAmt       = $AdjustmentInfo->xpath('arc:OldBalanceAmt');
     $NewBalanceAmt       = $AdjustmentInfo->xpath('arc:NewBalanceAmt');
    }
   }
    $adjustmentInfo = array (
	   'balancetype'     => (string)$BalanceType[0],
	   'balanceid'       => (string)$BalanceID[0],
	   'balancetypename' => (string)$BalanceTypeName[0],
	   'oldbalanceamt'   => (string)$OldBalanceAmt[0],
	   'newbalanceamt'   => (string)$NewBalanceAmt[0]
	); 
    $results['response']['adjustment'] = $adjustmentInfo;
   }

    return $results;	
}

public function Recharge($data = array()) {
$Recharge = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:RechargeRequestMsg>
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
         <RechargeRequest>
            <ars:RechargeSerialNo>[secuencia]</ars:RechargeSerialNo>
            <ars:RechargeType>1</ars:RechargeType>
            <ars:RechargeChannelID>3</ars:RechargeChannelID>
            <ars:RechargeObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>filter_NNN8</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:RechargeObj>
            <ars:RechargeInfo>
                  <ars:CashPayment>
                  <ars:PaymentMethod>1001</ars:PaymentMethod>
                  <ars:Amount>filter_monto00</ars:Amount>
               </ars:CashPayment>
            </ars:RechargeInfo>
            <ars:CurrencyID>1058</ars:CurrencyID>
         </RechargeRequest>		 
      </ars:RechargeRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $util = New Util();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $Recharge = str_replace('[secuencia]', $secuencia, $Recharge);
  
  foreach ($data as $key=>$parametro) {
  	$Recharge = str_replace($key,$parametro,$Recharge);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($Recharge,'Recharge',$url); 

  /*
    Analizando respuesta: 
   */  
   $xml_Recharge = new SimpleXMLElement($result);
   $nsqb = $xml_Recharge->getNamespaces(true);
   $xml_Recharge->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_Recharge->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_Recharge->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_Recharge->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_Recharge->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

 if (strcmp((string)$ResultCode[0],"0")==0) {
   foreach ($xml_Recharge->xpath('//RechargeResult') as $RechargeResult) {
	   $rechargeSerialNo = $RechargeResult->xpath('ars:RechargeSerialNo');
   foreach ($RechargeResult->xpath('ars:BalanceChgInfo') as $BalanceChgInfo) {
	 $BalanceType         = $BalanceChgInfo->xpath('arc:BalanceType');
     $BalanceID           = $BalanceChgInfo->xpath('arc:BalanceID');
     $BalanceTypeName     = $BalanceChgInfo->xpath('arc:BalanceTypeName');
	 $OldBalanceAmt       = $BalanceChgInfo->xpath('arc:OldBalanceAmt');
     $NewBalanceAmt       = $BalanceChgInfo->xpath('arc:NewBalanceAmt');
    }
   }
    $rechargeInfo = array (
	   'balancetype'     => (string)$BalanceType[0],
	   'balanceid'       => (string)$BalanceID[0],
	   'balancetypename' => (string)$BalanceTypeName[0],
	   'oldbalanceamt'   => (string)$OldBalanceAmt[0],
	   'newbalanceamt'   => (string)$NewBalanceAmt[0]
	); 
    $results['response']['recharge'] = $rechargeInfo;
   }

    return $results;	
}
	
public function delBalance($data = array()) {
$adjustment = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:AdjustmentRequestMsg>
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
				 <AdjustmentRequest>
					<ars:AdjustmentSerialNo>[secuencia]</ars:AdjustmentSerialNo>
					<ars:AdjustmentObj>
					   <ars:SubAccessCode>
					   <arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
					   </ars:SubAccessCode>
					</ars:AdjustmentObj>
					<ars:OpType>2</ars:OpType>
					<ars:AdjustmentInfo>
					   <arc:BalanceType>filter_Type</arc:BalanceType>
					   <arc:BalanceID>filter_InstanceID</arc:BalanceID>
					</ars:AdjustmentInfo>
					<ars:AdditionalProperty>
					   <arc:Code>C_ADJUST_TYPE</arc:Code>
					   <arc:Value>0</arc:Value>
					</ars:AdditionalProperty>
				 </AdjustmentRequest>
      </ars:AdjustmentRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $adjustment = str_replace('[secuencia]', $secuencia, $adjustment);
   
  foreach ($data as $key=>$parametro) {
  	$adjustment = str_replace($key,$parametro,$adjustment);
	$url = str_replace($key,$parametro,$url);
  } 
    
  $result=$soa->getResponse($adjustment,'Adjustment',$url);
  /*
    Analizando respuesta: 
   */    
   $xml_adjustment = new SimpleXMLElement($result);
   $ns = $xml_adjustment->getNamespaces(true);
   $xml_adjustment->registerXPathNamespace('ars', $ns['ars']);
   $xml_adjustment->registerXPathNamespace('cbs', $ns['cbs']);
  
   $ResultCode = $xml_adjustment->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_adjustment->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

  if (strcmp((string)$ResultCode[0],"0")==0) {
   $Version=$xml_adjustment->xpath('//cbs:Version');
   $ResultCode=$xml_adjustment->xpath('//cbs:ResultCode');
   $ResultDesc=$xml_adjustment->xpath('//cbs:ResultDesc');
   $AcctKey=$xml_adjustment->xpath('//ars:AcctKey');
   $AdjustmentSerialNo=$xml_adjustment->xpath('//ars:AdjustmentSerialNo');
 
   $infoajuste[] = array(
				'version'            => (string)$Version[0],
				'resultcode'         => (string)$ResultCode[0],
				'resultdesc'         => (string)$ResultDesc[0],
				'acctkey'            => (string)$AcctKey[0],
				'adjustmentserialno' => (string)$AdjustmentSerialNo[0]
			);
	$results['response']['ajuste'] = $infoajuste;		
   }
 return $results;      
}	
	
public function delFreeUnit($data = array()) {	
$adjustment = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
	<soapenv:Header/>
	<soapenv:Body>
		<ars:AdjustmentRequestMsg>
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
			<AdjustmentRequest>
				<ars:AdjustmentSerialNo>[secuencia]</ars:AdjustmentSerialNo>
				<ars:AdjustmentObj>
					<ars:SubAccessCode>
						<arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
					</ars:SubAccessCode>
				</ars:AdjustmentObj>
				<ars:OpType>2</ars:OpType>			
            <ars:FreeUnitAdjustmentInfo>
               <ars:FreeUnitInstanceID>filter_InstanceID</ars:FreeUnitInstanceID>
               <ars:FreeUnitType>filter_Type</ars:FreeUnitType>
               <ars:AdjustmentType>2</ars:AdjustmentType>
               <ars:AdjustmentAmt>0</ars:AdjustmentAmt>
            </ars:FreeUnitAdjustmentInfo>				
				<ars:AdditionalProperty>
					<arc:Code>C_REMARKS</arc:Code>
					<arc:Value>Ajuste_[secuencia]</arc:Value>
				</ars:AdditionalProperty>
				<ars:AdditionalProperty>
					<arc:Code>C_ADJUST_TYPE</arc:Code>
					<arc:Value>2</arc:Value>
				</ars:AdditionalProperty>
			</AdjustmentRequest>
		</ars:AdjustmentRequestMsg>
	</soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $adjustment = str_replace('[secuencia]', $secuencia, $adjustment);
  
  foreach ($data as $key=>$parametro) {
  	$adjustment = str_replace($key,$parametro,$adjustment);
	$url = str_replace($key,$parametro,$url);
  } 

  $this->log->write('Adjustment Request: '.$adjustment); 
  $result=$soa->getResponse($adjustment,'Adjustment',$url);
  $this->log->write('Adjustment Response: '.$result);
  
   /*
    Analizando respuesta: 
   */  
   $xml_adjustment = new SimpleXMLElement($result);
   $ns = $xml_adjustment->getNamespaces(true);
   $xml_adjustment->registerXPathNamespace('ars', $ns['ars']);
   $xml_adjustment->registerXPathNamespace('cbs', $ns['cbs']);

   $ResultCode = $xml_adjustment->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_adjustment->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);
   
  if (strcmp((string)$ResultCode[0],"0")==0) {
   $Version=$xml_adjustment->xpath('//cbs:Version');
   $ResultCode=$xml_adjustment->xpath('//cbs:ResultCode');
   $ResultDesc=$xml_adjustment->xpath('//cbs:ResultDesc');
   $AcctKey=$xml_adjustment->xpath('//ars:AcctKey');
   $AdjustmentSerialNo=$xml_adjustment->xpath('//ars:AdjustmentSerialNo');
 
   $infoajuste[] = array(
				'version'            => (string)$Version[0],
				'resultcode'         => (string)$ResultCode[0],
				'resultdesc'         => (string)$ResultDesc[0],
				'acctkey'            => (string)$AcctKey[0],
				'adjustmentserialno' => (string)$AdjustmentSerialNo[0]
			);
	$results['response']['ajuste']=$infoajuste;		
   }

 return $results;   
}

public function getQueryAdjustLog($data = array()) {
$QueryAdjustLog = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:QueryAdjustLogRequestMsg>
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
         <QueryAdjustLogRequest>
            <ars:QueryObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
            <ars:TotalRowNum>0</ars:TotalRowNum>
            <ars:BeginRowNum>0</ars:BeginRowNum>
            <ars:FetchRowNum>455</ars:FetchRowNum>
            <ars:StartTime>filter_starttime</ars:StartTime>
            <ars:EndTime>filter_endtime</ars:EndTime>
         </QueryAdjustLogRequest>
      </ars:QueryAdjustLogRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $util = New Util();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $QueryAdjustLog = str_replace('[secuencia]', $secuencia, $QueryAdjustLog);
  
  foreach ($data as $key=>$parametro) {
  	$QueryAdjustLog = str_replace($key,$parametro,$QueryAdjustLog);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($QueryAdjustLog,'QueryAdjustLog',$url);
  
  /*
    Analizando respuesta: 
   */  
   $xml_QueryAdjustLog = new SimpleXMLElement($result);
   $nsqb = $xml_QueryAdjustLog->getNamespaces(true);
   $xml_QueryAdjustLog->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_QueryAdjustLog->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_QueryAdjustLog->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_QueryAdjustLog->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_QueryAdjustLog->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

 if (strcmp((string)$ResultCode[0],"0")==0) {
   foreach ($xml_QueryAdjustLog->xpath('//QueryAdjustLogResult') as $QueryAdjustLogResult) {
	   $TotalRowNum = $QueryAdjustLogResult->xpath('ars:TotalRowNum');
   foreach ($QueryAdjustLogResult->xpath('ars:AdjustInfo') as $AdjustInfo) {
	 $TradeTime         = $AdjustInfo->xpath('ars:TradeTime');
     $AcctKey           = $AdjustInfo->xpath('ars:AcctKey');
     $SubKey            = $AdjustInfo->xpath('ars:SubKey');
	 $PrimaryIdentity   = $AdjustInfo->xpath('ars:PrimaryIdentity');
     $TransID           = $AdjustInfo->xpath('ars:TransID');
     $ExtTransID        = $AdjustInfo->xpath('ars:ExtTransID');
	 $Remark            = $AdjustInfo->xpath('ars:Remark');

	 foreach ($AdjustInfo->xpath('ars:FreeUnitAdjustmentInfo') as $FreeUnitAdjustmentInfo) {
	  $FreeUnitInstanceID = $FreeUnitAdjustmentInfo->xpath('ars:FreeUnitInstanceID');
	  $FreeUnitType       = $FreeUnitAdjustmentInfo->xpath('ars:FreeUnitType');
	  $AdjustmentType     = $FreeUnitAdjustmentInfo->xpath('ars:AdjustmentType');
	  $AdjustmentAmt      = $FreeUnitAdjustmentInfo->xpath('ars:AdjustmentAmt');
	  $MeasureUnit        = $FreeUnitAdjustmentInfo->xpath('ars:MeasureUnit');
	  $FreeUnitTypeName   = $FreeUnitAdjustmentInfo->xpath('ars:FreeUnitTypeName');
     }
	 $propiedades = array();
	 foreach ($AdjustInfo->xpath('ars:AdditionalProperty') as $AdditionalProperty) {
	  $Code  = $AdditionalProperty->xpath('arc:Code');
	  $Value = $AdditionalProperty->xpath('arc:Value');
	  $propiedades[] = array(
	     'code'  => (string)$Code[0],
		 'value' => (string)$Value[0]
	  );
     }	 

	      $adjustlog[] = array(
	              'tradetime'         => date($this->language->get('datetime_format'), strtotime((string)$TradeTime[0])),
	              'acctkey'           => (string)$AcctKey[0],
	              'subkey'            => (string)$SubKey[0],
				  'primaryidentitity' => (string)$PrimaryIdentity[0],
	              'transid'           => (string)$TransID[0],
	              'exttransid'        => (string)$ExtTransID[0],
	              'remark'            => (string)$Remark[0],
				  'freeunitinstanceid'=> (string)$FreeUnitInstanceID[0],
				  'freeunittype'      => (string)$FreeUnitType[0],
				  'adjustmenttype'    => (string)$AdjustmentType[0],
				  'adjustmentamt'     => (string)$AdjustmentAmt[0],
				  'measureunit'       => (string)$MeasureUnit[0],
				  'freeunittypename'  => (string)$FreeUnitTypeName[0],
				  'propiedades'       => $propiedades    
	      );
		  unset($propiedades);
    }
	$util->aasort($adjustlog,'tradetime');
	$results['response']['totalrownum'] = (string)$TotalRowNum[0];
   } 
    $results['response']['adjustlog'] = $adjustlog;
   }

    return $results;	
}

public function getQueryRechargeLogResult($data = array()) {
$QueryRechargeLog = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:QueryRechargeLogRequestMsg>
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
         <QueryRechargeLogRequest>
            <ars:QueryObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
            <ars:TotalRowNum>0</ars:TotalRowNum>
            <ars:BeginRowNum>0</ars:BeginRowNum>
            <ars:FetchRowNum>455</ars:FetchRowNum>
            <ars:StartTime>filter_fecha_inicio</ars:StartTime>
            <ars:EndTime>filter_fecha_fin</ars:EndTime>
         </QueryRechargeLogRequest>
      </ars:QueryRechargeLogRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa  = New Soa();
  $util = New Util();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".$util->getTime();
  $secuencia = str_replace(' ', 'T', $secuencia);
 
  $QueryRechargeLog = str_replace('[secuencia]', $secuencia, $QueryRechargeLog);
  
  foreach ($data as $key=>$parametro) {
  	$QueryRechargeLog = str_replace($key,$parametro,$QueryRechargeLog);
	$url = str_replace($key,$parametro,$url);
  } 
  $QueryRechargeLog = str_replace('filter_fecha_inicio', $util->getDate()."000000", $QueryRechargeLog);
  $QueryRechargeLog = str_replace('filter_fecha_fin', $util->getDate()."235959", $QueryRechargeLog);
   
  $result=$soa->getResponse($QueryRechargeLog,'QueryRechargeLog',$url);
  /*
    Analizando respuesta: 
   */  
   $xml_QueryRechargeLog = new SimpleXMLElement($result);
   $nsqb = $xml_QueryRechargeLog->getNamespaces(true);
   $xml_QueryRechargeLog->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_QueryRechargeLog->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_QueryRechargeLog->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_QueryRechargeLog->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_QueryRechargeLog->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);
  
  if (strcmp((string)$ResultCode[0],"0")==0) {
   foreach ($xml_QueryRechargeLog->xpath('//QueryRechargeLogResult') as $QueryRechargeLogResult) {
	   $TotalRowNum = $QueryRechargeLogResult->xpath('ars:TotalRowNum');
   foreach ($QueryRechargeLogResult->xpath('ars:RechargeInfo') as $RechargeInfo) {
	 $TradeTime         = $RechargeInfo->xpath('ars:TradeTime');
     $AcctKey           = $RechargeInfo->xpath('ars:AcctKey');
     $SubKey            = $RechargeInfo->xpath('ars:SubKey');
     $TransID           = $RechargeInfo->xpath('ars:TransID');
     $ExtTransID        = $RechargeInfo->xpath('ars:ExtTransID');
	 $RechargeAmount    = $RechargeInfo->xpath('ars:RechargeAmount');
   	 $RechargeType      = $RechargeInfo->xpath('ars:RechargeType');
	 $ExtRechargeType   = $RechargeInfo->xpath('ars:ExtRechargeType');
	 $RechargeChannelID = $RechargeInfo->xpath('ars:RechargeChannelID');
	 
	 foreach ($RechargeInfo->xpath('ars:BalanceChgInfo') as $BalanceChgInfo) {
	  $BalanceType       = $BalanceChgInfo->xpath('arc:BalanceType');
	  $BalanceID         = $BalanceChgInfo->xpath('arc:BalanceID');
	  $BalanceTypeName   = $BalanceChgInfo->xpath('arc:BalanceTypeName');
	  $OldBalanceAmt     = $BalanceChgInfo->xpath('arc:OldBalanceAmt');
	  $NewBalanceAmt     = $BalanceChgInfo->xpath('arc:NewBalanceAmt');
     }
	      $recargas[] = array(
	              'tradetime'         => date($this->language->get('datetime_format'), strtotime((string)$TradeTime[0])),
	              'acctkey'           => (string)$AcctKey[0],
	              'subkey'            => (string)$SubKey[0],
	              'transid'           => (string)$TransID[0],
	              'exttransid'        => (string)$ExtTransID[0],
	              'rechargeamount'    => number_format((float)$RechargeAmount[0]/100,2,'.',','),
	              'rechargetype'      => (string)$RechargeType[0],
	              'extrechargetype'   => (string)$ExtRechargeType[0],
	              'rechargechannelid' => (string)$RechargeChannelID[0],
	              'balancetype'       => (string)$BalanceType[0],
	              'balanceid'         => (string)$BalanceID[0],
	              'balancetypename'   => (string)$BalanceTypeName[0],
	              'oldbalanceamt'     => number_format((float)$OldBalanceAmt[0]/100,2,'.',','),
	              'newbalanceamt'     => number_format((float)$NewBalanceAmt[0]/100,2,'.',',')    
	      );
    }
	$util->aasort($recargas,'tradetime');
	$results['response']['totalrownum'] = (string)$TotalRowNum[0];
   } 
    $results['response']['recargas'] = $recargas;
   }

    return $results;
}
	
public function getBalanceResult($data = array()) {
$queryBalance = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:QueryBalanceRequestMsg>
          <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[Secuencia]</cbs:MessageSeq>
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
         <QueryBalanceRequest>
            <ars:QueryObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>filter_NNN8</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
         </QueryBalanceRequest>
      </ars:QueryBalanceRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $util = New Util();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $queryBalance = str_replace('[secuencia]', $secuencia, $queryBalance);
   
  foreach ($data as $key=>$parametro) {
  	$queryBalance = str_replace($key,$parametro,$queryBalance);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($queryBalance,'QueryBalance',$url);
  /*
    Analizando respuesta: 
   */  
   $xml_queryBalance = new SimpleXMLElement($result);
   $nsqb = $xml_queryBalance->getNamespaces(true);
   $xml_queryBalance->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_queryBalance->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_queryBalance->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_queryBalance->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_queryBalance->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

  if (strcmp((string)$ResultCode[0],"0")==0) {
   /*************************************************************************************************************/

   foreach ($xml_queryBalance->xpath('//ars:BalanceResult') as $BalanceResult) {
	 $BalanceType     = $BalanceResult->xpath('arc:BalanceType');
	 $BalanceTypeName = $BalanceResult->xpath('arc:BalanceTypeName');
	 $TotalAmount     = $BalanceResult->xpath('arc:TotalAmount');
	 foreach ($BalanceResult->xpath('arc:BalanceDetail') as $BalanceDetail) {
		 $InitialAmount     = $BalanceDetail->xpath('arc:InitialAmount');
		 $EffectiveTime     = $BalanceDetail->xpath('arc:EffectiveTime');
		 $BalanceInstanceID = $BalanceDetail->xpath('arc:BalanceInstanceID');
		 $ExpireTime        = $BalanceDetail->xpath('arc:ExpireTime');
	 }
     /*******************************************/
     $sql = "select comverse_name,sort_order
          from testing.accounts
         where balancetype = '".$BalanceType[0]."'
		   and offering_id = '".$data['offering_id']."'";
	  
	 /*******************************************/
	 $query = $this->mysql->query($sql);
     $billetera = $query->row['comverse_name'];
	 $sort_order= $query->row['sort_order'];
	 
	 $infobilleteras[] = array((string)$BalanceType[0]
	                   ,(string)$TotalAmount[0]
					   ,(string)$BalanceInstanceID[0]
					   ,(string)$BalanceTypeName[0]
					   ,(string)$ExpireTime[0]
					   ,(string)$billetera
					   ,(string)$InitialAmount[0]
					   ,(string)$EffectiveTime[0]
					   ,'sort_order' => $sort_order);
    }
	$util->aasort($infobilleteras,'sort_order');
	
	$results['response']['billeteras'] = $infobilleteras;
   }
    return $results;
 }
 
}