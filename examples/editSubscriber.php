<?php
# config file import
include_once "config.php";

# import the cc-plus class
include_once "../cc-plus-class.php";

# create new instance of it
$cc = new cc_plus($config['cc-username'], $config['cc-password'], $config['cc-host-uri']);

# this is your own customId made by some OSS or management system
$custId = 9001;

$custRecord = $cc->get_subscriber_customId($custId);

# let's make sure our customer is actually valid!
if (empty($custRecord->error)) {
   # looks good here boss
   $cc_cust = $custRecord->result[0]->_id;
   echo "Customer ".$custId."'s CC+ id is ".$cc_cust."\n";

	 $cc_sub_payload = array(
		"_id"=>$cc_cust,
		"name"=>"MY NEW NAME",
		"type"=>"residential",
		"customId"=>"9001",
		"locations"=>array( 
			(object) array(
				"primary"=>true,
				"description"=>"UPC",
				"devices"=>array("CXNK00123456", "CXNK00654321"),
				"address"=>(object) array(
					"streetLine1"=>"1400 Douglas St",
					"city"=>"Omaha",
					"state"=>"NE",
					"zip"=>"68179"
				),
				"contacts"=> array(
					(object) array(
						"firstName"=> "Matthew",
						"lastName"=> "Mcconaughey",
						"email"=> "alrightalrightalright@fordmotorcorp.net",
						"phone"=> "555-867-5309",
					),
					(object) array(
						"firstName"=> "DJ",
						"lastName"=> "Khaled",
						"email"=> "lion@wethebestsound.com",
						"phone"=> "999-99-9999",
					)
				)
			)
		)
	);

	#uncomment the next line to print out the payload
	#echo json_encode($cc_sub_payload, JSON_PRETTY_PRINT);

   	$update_result = $cc->put_edit_subscriber($cc_sub_payload);
	if(empty($update_result->error)){
		echo ("Customer record updated\n");
		
	} else {
		echo ("Error: \n");
		print_r($update_result);
	}
}

