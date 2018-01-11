# calix-cc-plus
This class was written in part of the Fiber Management System for the City of Sandy. It currently features the following operations

* Subscriber
 * Creating
 * Updating
 * Selecting via _id
 * Selecting via customId
 * Deleting
* Provisioning
 * Create
 * Update
 * Select via serialNumber/FSAN
 * Delete
* Devices
 * Select via serialNumber/FSAN
* TR-069 Methods
 
Please make sure the PHP CURL module is enabled on your system!!!
 
## Usage
 Include the class in your php page
 ```
 $cc = new cc_plus($config['cc-username'], $config['cc-password'], $config['cc-host-uri']);
 ```
 Where $config[''] vars are your cc plus api username/password and host
 
### Creating a subscriber record
 ```
 $cc_result = $cc->post_add_subscriber(json_encode($cust_id), "Business", "ACCOUNT NAME");
 ```
 
### Updating a subscriber record
 ```
 $cc_result = $cc->put_edit_subscriber($csrr);
 ```
 
### Selecting a subscriber record 
Using the customId
```
print_r($cc->get_subscriber_customId("3341"));
```
Using the _id field
```
print_r($cc->get_subscriber_id("_id_long_string"));
```

### Deleting a subscriber record 
```
print_r($cc->delete_subscriber_id("4b07c553-sdfas-sadfkjsalfjasad-sdfs"));
```

### Creating a provisioning record 
```
print_r($cc->post_add_provisioning_record($arr_of_attributes));
```

### Updating a provisioning record
```
print_r($cc->put_update_provisioning_record($arr_of_attributes_must_include_id));
```

### Selecting a provisioning record
Get provisioning record via device FSAN
```
print_r($cc->get_provisioning_record_fsan("CXNK00FFFFFF"));
```

### Deleting a provisioning record
```
print_r($cc->delete_provisioning_record_id("4b07c553-sdfas-sadfkjsalfjasad-sdfs"));
```

### Selecting a device record
Get device from serial number
```
print_r($cc->get_device_serial("CXNK00FFFFFF"));
```

### Sending TR-069 Messages
Set the parameters in an array. The following example sends a request to Consumer Connect to open the remote portal for a device. Most commands from the [tr-098 document from the Broadband Forum](https://www.broadband-forum.org/cwmp/tr-098-1-8-0.html) work. 
```
$tr_069_args = array(
	"operation"=>"SetParameterValues",
	"cpeIdentifier"=> array(
		"serialNumber"=>$fsan
	),
	"parameterValues"=>array(
		"InternetGatewayDevice.UserInterface.RemoteAccess.Enable"=>"true",
		"InternetGatewayDevice.UserInterface.RemoteAccess.Port"=>"8080",
		"InternetGatewayDevice.UserInterface.RemoteAccess.Protocol"=>"HTTP",
		"InternetGatewayDevice.User.2.RemoteAccessCapable"=>"true"
	)
);
```
Calix requires that a JSON string is sent to their system. The parameters above are required:
* operations - (SetParameterValues, GetParameterValues, Reboot, FactoryReset, Download (untested but didn't fail!). Upload is currently not supported at the time of this documentation writing. 
* cpeIdentifier - I use serialNumber, but refer to the calix documentation for others like IP or MAC or something..
* parameterValues - can set or use multiple elements within requests, unless Reboot or FactoryReset (which should be empty unless requesting some id callback thingy)

Then you can pass the array to the method. 
```
$record = $cc->post_tr_069_request(json_encode($tr_069_args));
```

## Example objects to help you get started
This class is designed to be fed php array's and std objects, and will perform a json encode before sending the request. This class is simply to get requests and responses in a good format. A typical response is:
```
(
    [error] =>
    [info] => Array
        (
          OMITTED BECAUSE IT IS LONG
        )

    [result] => Array
        (
            [0] => stdClass Object
                (
                    [_id] => dcd94b36-9ff5-83d8-ad18-639a8ad3297d
                    [name] => SANDYNET-TEST
                    [type] => Business
                    [customId] => 9999
                    [locations] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [devices] => Array
                                        (
                                            [0] => CXNK00FFFFFF
                                            [1] => CXNK00DDDDDD
                                        )

                                )

                        )

                )

        )

)

```
Errors are returned in the error element and can be access via $cc->error. Depending on the request and calix documentation, different statuses will triggere different errors to be sent. A 404 when deleting a subscriber record will result in a custom error message. This isn't the most ideal option, but it does work better than having to check both the error and result elements for improper responses. 
Another example is that when a subscriber is created that already contains the same customId, the error message returned from compass will be parsed into the error element. 
My standard rule for checking the result is:
```
if($cc_result && empty($cc_result->error)){
  //Holla
} else {
  // Error
}
```

When creating or updating a record, an couple example arrays are provided. 
### Creating a Provisioning Record
```
$subscriber_id = $csrr->{'_id'};
$request = array(
    "deviceId"=>$fsan,
    "subscriberId"=>$subscriber_id,
    "wifi" => array(
      "1" => array(
       "BeaconType" => "WPAand11i",
       "WPAAuthenticationMode" => "PSKAuthentication",
       "WPAEncryptionModes" => "TKIPandAESEncryption",
       "IEEE11iAuthenticationMode" => "PSKAuthentication",
       "IEEE11iEncryptionModes" => "TKIPandAESEncryption",
       "BasicAuthenticationMode" => "None",
       "BasicEncryptionModes" => "None",
       "SSIDAdvertisementEnabled" => $broadcast_2ghz,
       "Enable" => true,
       "SSID" => $ssid_2ghz,
       "KeyPassphrase" => $password_2ghz,
       "RadioEnabled" => $radio_2ghz
      ),
      "9" => array(
       "RadioEnabled" => false
      )

     )

    );
$cc_result = $cc->post_add_provisioning_record($request);
```

## Examples
Check out the examples folder for the following
* Creating a subscriber by giving only the required fields
* Update the subscriber by pulling its record using the customId set by an OSS and then using it's cc+ id to update it's customer config
* Pull a CPE's WLAN config from TR-069 in JSON
* Push a CPE's WiFi configuration by creating a provisioning record

