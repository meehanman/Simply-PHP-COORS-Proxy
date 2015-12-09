<?php

/*
PHP PROXY THAT BYPASES COORS
USAGE: ?
*/

$log_requests=false;
$debug=false;
$debugOutput=false;

//CORS HEADERS
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');
header('Access-Control-Allow-Headers: accept, content-type');


//URL + DATA SETUP
//GET BODY OF REQUEST
$content = file_get_contents('php://input');
$json_input = json_decode(file_get_contents('php://input'));

//GET URL TO SENT REQUEST TO
$url = $_GET['url'];

//Request Information
$request_method = $_SERVER['REQUEST_METHOD'];

//Start CURL to location
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request_method);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8","Accept:application/json, text/javascript, */*; q=0.01"));

#SO:9183178
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_HEADER, 1);

//Extract Response
if($debug){
    $json_response = '';
}else{
    $json_response = curl_exec($curl);
}

//Extract HTTP Status of request
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$header = substr($json_response, 0, $header_size);
$body = substr($json_response, $header_size);

//Close Connection
curl_close($curl);

//Decode Response into a JSON Object
$response = json_decode($json_response, true);


if($debug || $debugOutput){
    $echooutput = '{ "Status" : '.$status.', "Request_Method": "'.$request_method.'", "request_url": "'.$url.'", "header_size" : "'.$header_size.'", "header" : "'.$header.'", "body" : "'.$body.'", "Request" : "'.$content.'", "Response": '.$json_response.'}';
}else{
    $echooutput = $body;
}

if($log_requests){
    //Log all Proxy Requests
    $file = 'proxy.txt';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= date('Y-m-d H:i:s'). " | URL: ".$url." | Data: ".$content."\n Output: ".$echooutput." | Content: ".$content;
    // Write the contents back to the file
    file_put_contents($file, $current);
}

//Output to page
echo $echooutput;

?>
