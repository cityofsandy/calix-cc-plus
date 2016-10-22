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
 
