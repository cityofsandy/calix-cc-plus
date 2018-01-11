<?php
# config file import
include_once "config.php";

# import the cc-plus class
include_once "../cc-plus-class.php";

# create new instance of it
$cc = new cc_plus($config['cc-username'], $config['cc-password'], $config['cc-host-uri']);

$cc_result = $cc->post_add_subscriber(json_encode(9001), "Business", "TEST ACCOUNT NAME");


print_r($cc_result);
