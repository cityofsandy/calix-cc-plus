<?php
# config file import
include_once "config.php";

# import the cc-plus class
include_once "../cc-plus-class.php";

# create new instance of it
$cc = new cc_plus($config['cc-username'], $config['cc-password'], $config['cc-host-uri']);

# this is your own customId made by some OSS or management system
$custId = 9001;
$fsan = "CXNK00FFFFFF";
$custRecord = $cc->get_subscriber_customId($custId);

# let's make sure our customer is actually valid!
if (empty($custRecord->error)) {
	# looks good here boss
	$cc_cust = $custRecord->result[0]->_id;

	$request = array(
	   "deviceId"=>$fsan,
	   "subscriberId"=>$cc_cust,
	   "wifi" => array(
	      "1" => array(
		 "BeaconType" => "WPAand11i",
		 "WPAAuthenticationMode" => "PSKAuthentication",
		 "WPAEncryptionModes" => "TKIPandAESEncryption",
		 "IEEE11iAuthenticationMode" => "PSKAuthentication",
		 "IEEE11iEncryptionModes" => "TKIPandAESEncryption",
		 "BasicAuthenticationMode" => "None",
		 "BasicEncryptionModes" => "None",
		 "SSIDAdvertisementEnabled" => true,
		 "Enable" => true,
		 "SSID" => "fbi_surveillance_2ghz",
		 "KeyPassphrase" => "daBears",
		 "RadioEnabled" => true
	      ),
	      "9" => array(
		 "RadioEnabled" => false
	      )

	   )

	);
	$cc_result = $cc->post_add_provisioning_record($request);
	print_r($cc_result);



} else {
	#womp, womp
}
