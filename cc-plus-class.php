<?php

/**
 * Consumer Connect Plus Class
 *
 *
 * @package    Fiber Management System
 * @author     Gregory Brewster <gbrewster@agoasite.com>
 * @copyright  (C)2016 City of Sandy
 *
 *
 */

class cc_plus {
	
	/**
	 * Make sure variables are private!
	 */
	private $cc_username;
	private $cc_password;
	private $cc_uri;
	
	/**
	 * Declares the default constructor
	 * Upon creation, the object will have the following variables set with information
	 * obtained from the parameters defined by new
	 */
	public function __construct($user, $pass, $uri) {
		$this->cc_username = $user;
		$this->cc_password = $pass;
		$this->cc_uri = $uri;
	}
	
/*****************************************************************************************************************************
 ** SUBSCRIBER METHODS  SUBSCRIBER METHODS  SUBSCRIBER METHODS  SUBSCRIBER METHODS  SUBSCRIBER METHODS  SUBSCRIBER METHODS  **
 ****************************************************************************************************************************/ 
 
	/**
	 *  Function will update/edit a subscriber
	 *  Params: array of arguments, key=>value
	 *  Returns: Array, "errors", "result" 
	 */
	public function put_edit_subscriber($subscriber_arr){
					
		$record = $this->query_put("/api/subscriber", json_encode($subscriber_arr, JSON_PRETTY_PRINT));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}

	/**
	 *  Function will create a subscriber
	 *  Params: FMS customer ID, type (residential or business), name
	 *  Returns: Array, "errors", "result" 
	 */
	public function post_add_subscriber($customId, $type, $name){
		$arguments = array(
						"name"=>$name, 
						"type"=>$type, 
						"customId"=>$customId
					);
					
		$record = $this->query_post("/api/subscriber", json_encode($arguments, JSON_PRETTY_PRINT));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 403){
			$record->error = "403, Customer already exists in CC+";
		} else if($http_code == 400){
			$record->error = "Invalid http response: ".$http_code." ".$record->result[0]->error;
		} else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}
	
	/**
	 *  Function will fetch a subscriber record from customId
	 *  Params: FMS customer id as customId
	 *  Returns: Array, "errors", "result" 
	 */
	public function get_subscriber_customId($customId){
		$record = $this->query_get("/api/subscriber", array("customId"=>$customId));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 404){
			$record->error = null;
		}else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		if(isset($record) && isset($record->result)){
			if(count($record->result) == 1){
				$record->result = array($record->result);
			}
		}
		
		return $record;
	}
	
	/**
	 *  Function will fetch a subscriber record from _id field
	 *  Params: cc subscriber record id (int)
	 *  Returns: Array, "errors", "result" 
	 */
	public function get_subscriber_id($id){
		if(!isset($id)){
			$id = "";
		}
		$record = $this->query_get("/api/subscriber/".$id, null);
		$http_code = $this->get_status_code($record->info);
		
		if(isset($record) && isset($record->result)){
			if(count($record->result) == 1){
				$record->result = array($record->result);
			}
		}
		
		return $record;
	}
	
	/**
	 *  Function will fetch a subscriber record from _id
	 *  Params:  cc subscriber record id (int)
	 *  Returns: Array, "errors", "result" 
	 */
	public function delete_subscriber_id($id){
		if(!isset($id)){
			$id = "";
		}
		$record = $this->query_delete("/api/subscriber/".$id, null);
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 404){
			$record->error = "No subscriber record found";
		} else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}
	

/*****************************************************************************************************************************
 **   PROVISIONING METHODS   PROVISIONING METHODS   PROVISIONING METHODS   PROVISIONING METHODS   PROVISIONING METHODS    ****
 ****************************************************************************************************************************/ 	

 	/**
	 *  Function will create a provisioning record
	 *  Params: array of arguments for record
	 *  Returns: Array, "errors", "result" 
	 */
	public function post_add_provisioning_record($arguments){
					
		$record = $this->query_post("/api/provisioning-record", json_encode($arguments, JSON_PRETTY_PRINT));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 403){
			$record->error = "403, Customer already exists in CC+";
		} else if($http_code == 400){
			$record->error = "Invalid http response: ".$http_code." ".$record->result[0]->error;
		} else if($http_code == 409){
			$record->error = "Invalid http response: ".$http_code." ".$record->result[0]->error;
		} else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}
	
	/**
	 *  Function will update/edit a subscriber
	 *  Params: array of arguments, key=>value
	 *  Returns: Array, "errors", "result" 
	 */
	public function put_update_provisioning_record($arguments){
					
		$record = $this->query_put("/api/provisioning-record/", json_encode($arguments, JSON_PRETTY_PRINT));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}
 
	/**
	 *  Function will fetch all provisioning records for a given fsan
	 *  Params: serial number /fsan of a device 
	 *  Returns: Array, "errors", "result" 
	 */
	public function get_provisioning_record_fsan($fsan){
		$record = $this->query_get("/api/provisioning-record", array("deviceId"=>$fsan));
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 404){
			$record->error = null;
		}else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}	
	
	/**
	 *  Function will delete a provisioning record from _id
	 *  Params: provisioning record id (int)
	 *  Returns: Array, "errors", "result" 
	 */
	public function delete_provisioning_record_id($id){
		if(!isset($id)){
			$id = "";
		}
		$record = $this->query_delete("/api/provisioning-record/".$id, null);
		$http_code = $this->get_status_code($record->info);
		
		if($http_code == 404){
			$record->error = "No provisioning record found";
		} else if($http_code != 200){
			$record->error = "Invalid http response: ".$http_code;
		}
		
		return $record;
	}
	
/*****************************************************************************************************************************
 ** DEVICE METHODS   DEVICE METHODS   DEVICE METHODS   DEVICE METHODS   DEVICE METHODS   DEVICE METHODS   DEVICE METHODS  ****
 ****************************************************************************************************************************/ 
 
	/**
	 *  Function will fetch a device record
	 *  Params: serial number/ fsan of device record
	 *  Returns: Array, "errors", "result" 
	 */
	public function get_device_serial($serialNumber){
		$record = $this->query_get("/api/device", array("serialNumber"=>$serialNumber));
		if(isset($record) && isset($record->result)){
			if(count($record->result) == 1){
				$record->result = array($record->result);
			}
		}
		return $record;
	}	
	
	
/*****************************************************************************************************************************
 ** CURL METHODS   CURL METHODS   CURL METHODS   CURL METHODS   CURL METHODS   CURL METHODS   CURL METHODS   CURL METHODS ****
 ****************************************************************************************************************************/ 
	
	
	/**
	 *  Function will query CC+ using the given parameters
	 *  Params: URI without host, json_encoded string of arguments
	 *  Returns: Array, "errors", "result" 
	 */
	protected function query_post($uri, $arguments){
		$data = (object) array();
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $this->cc_uri.$uri,
			CURLOPT_CUSTOMREQUEST=> "POST",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POSTFIELDS => $arguments,
			CURLOPT_HTTPHEADER => array(
									"Authorization: Basic ".base64_encode($this->cc_username.":".$this->cc_password), 
									"Content-Type: application/json"
								),
			//CURLOPT_VERBOSE => 1,				//debugging
		);
		
		// Set options against curl object
		curl_setopt_array($ch, $options);
		$file = curl_exec($ch);
		if(curl_error($ch)){
			$err = curl_error($ch);
		} else {
			$err = null;
		}
		
		$data->error = $err;
		$data->info = curl_getinfo($ch);
		
		$result_obj = json_decode($file);
		if($result_obj){
			if(count($result_obj) > 1){
				$data->result = $result_obj;
			} else {
				$data->result = array($result_obj);
			}
		} else {
			$data->result = array((object) array("response"=>$file));
		}

		
		// close curl object after you check for errors!
		curl_close($ch);
		
		return $data;
	}
	
	/**
	 *  Function will query CC+ using the given parameters
	 *  Params: URI without host, json_encoded string of arguments
	 *  Returns: Array, "errors", "result" 
	 */
	protected function query_put($uri, $arguments){
		$data = (object) array();
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $this->cc_uri.$uri,
			CURLOPT_CUSTOMREQUEST=> "PUT",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POSTFIELDS => $arguments,
			CURLOPT_HTTPHEADER => array(
									"Authorization: Basic ".base64_encode($this->cc_username.":".$this->cc_password), 
									"Content-Type: application/json"
								),
			//CURLOPT_VERBOSE => 1,				//debugging
		);
		
		// Set options against curl object
		curl_setopt_array($ch, $options);
		$file = curl_exec($ch);
		
		if(curl_error($ch)){
			$err = curl_error($ch);
		} else {
			$err = null;
		}
		
		$data->error = $err;
		$data->info = curl_getinfo($ch);
		
		$result_obj = json_decode($file);
		if($result_obj){
			if(count($result_obj) > 1){
				$data->result = $result_obj;
			} else {
				$data->result = array($result_obj);
			}
		} else {
			$data->result = array((object) array("response"=>$file));
		}
		
		// close curl object after you check for errors!
		curl_close($ch);
		
		return $data;
	}
	
	/**
	 *  Function will query CC+ using the given parameters
	 *  Params: URI without host, array of arguments, key=>value
	 *  Returns: Array, "errors", "result" 
	 */
	protected function query_get($uri, $arguments){
		$argument_str = "";
		if(isset($arguments) && count($arguments) > 0){
			$argument_str .= "?";
			foreach($arguments as $arg_key=>$arg_val){
				$argument_str.= $arg_key."=".$arg_val."&";
			}
		}
		
		$data = (object) array();
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $this->cc_uri.$uri.$argument_str,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER => array(
									"Authorization: Basic ".base64_encode($this->cc_username.":".$this->cc_password), 
									"Content-Type: application/json"
									),
			//CURLOPT_VERBOSE => 1,				//debugging
		);
		
		// Set options against curl object
		curl_setopt_array($ch, $options);
		$file = curl_exec($ch);
		
		if(curl_error($ch)){
			$err = curl_error($ch);
		} else {
			$err = null;
		}
		
		$data->error = $err;
		$data->info = curl_getinfo($ch);
		
		$result_obj = json_decode($file);
		if($result_obj){

			$data->result = $result_obj;
		} else {
			$data->result = null;
		}
		
		// close curl object after you check for errors!
		curl_close($ch);
		
		return $data;
	}
	
	/**
	 *  Function will query CC+ using the given parameters
	 *  Params: URI without host
	 *  Returns: Array, "errors", "result" 
	 */
	protected function query_delete($uri){
		$data = (object) array();
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $this->cc_uri.$uri,
			CURLOPT_CUSTOMREQUEST=> "DELETE",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER => array(
									"Authorization: Basic ".base64_encode($this->cc_username.":".$this->cc_password), 
									"Content-Type: application/json"
								),
			//CURLOPT_VERBOSE => 1,				//debugging
		);
		
		// Set options against curl object
		curl_setopt_array($ch, $options);
		$file = curl_exec($ch);
		
		if(curl_error($ch)){
			$err = curl_error($ch);
		} else {
			$err = null;
		}
		
		$data->error = $err;
		$data->info = curl_getinfo($ch);
		
		$result_obj = json_decode($file);
		if($result_obj){
			if(count($result_obj) > 1){
				$data->result = $result_obj;
			} else {
				$data->result = array($result_obj);
			}
		} else {
			$data->result = null;
		}
		
		// close curl object after you check for errors!
		curl_close($ch);
		
		return $data;
	}
	
	
	protected function get_status_code($info){
		return $info['http_code'];
	}
}
?>
