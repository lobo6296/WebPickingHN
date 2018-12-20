<?php
class ModelRestSms extends Model {

public function enviaPalabra($celular,$numero_corto,$mensaje) {
$service_url = 'http://172.22.120.2:13100/sendsms?username=jescobar&password=tomcat';
$service_url = $service_url.'&from='.$celular
                           .'&to='.$numero_corto
                           .'&text='.str_replace(' ','%20',$mensaje);
					
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
$respuesta=0;
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
	$respuesta=1;
}
curl_close($curl);
return $respuesta;
}
}
/*

function post($celular,$numero_corto,$mensaje) {
$service_url = 'http://example.com/api/conversations';
$curl = curl_init($service_url);
$curl_post_data = array(
        'message' => 'test message',
        'useridentifier' => 'agent@example.com',
        'department' => 'departmentId001',
        'subject' => 'My first conversation',
        'recipient' => 'recipient@example.com',
        'apikey' => 'key001'
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}
echo 'response ok!';
var_export($decoded->response);
}

function another() {
$service_url = 'http://example.com/api/conversations/[CONVERSATION_ID]';
$ch = curl_init($service_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

$curl_post_data = array(
        'note' => 'this is spam!',
        'useridentifier' => 'agent@example.com',
        'apikey' => 'key001'
);

curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$response = curl_exec($ch);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}

curl_close($curl);
$decoded = json_decode($curl_response);

if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}
echo 'response ok!';
var_export($decoded->response);	
}
*/
 
?>