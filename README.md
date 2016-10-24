# calix-cc-plus
This class was written in part of the Fiber Management System for the Cit of Sandy. It currently features the following operations


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

