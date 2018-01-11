<?php
# contact CPE, pull Wlan interface data, pipe to json file

# config file import
include_once "config.php";

# import the cc-plus class
include_once "../cc-plus-class.php";

# create new instance of it
$cc = new cc_plus($config['cc-username'], $config['cc-password'], $config['cc-host-uri']);

$fsan = "CXNK00FFFFFF";

$tr_069_args = array(
   "operation"=>"GetParameterValues",
   "cpeIdentifier"=> array(
      "serialNumber"=>$fsan	// On and connected device!
   ),
   "parameterNames"=>array(
      "InternetGatewayDevice.LANDevice."
   )
);

print("Working....\r");
$tr_response = $cc->post_tr_069_request(json_encode($tr_069_args));

if(empty($tr_response->error)){
   //print_r($tr_response->result[0]);
   $file_result = file_put_contents(time()."_".$fsan."_WiFiConfig.json", json_encode($tr_response, JSON_PRETTY_PRINT));

   if($file_result){
      print_r("File saved successfully\n");
   } else {
      print_r("Failed to save file. Is directory writeable?\n");
   }
} else {
   print_r("failed");
}
