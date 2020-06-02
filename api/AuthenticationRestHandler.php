<?php
require_once("SimpleRest.php");
require_once("Properties.php");
		
class AuthenticationRestHandler extends SimpleRest {
	const STATUS_OK = 'OK';
    const STATUS_ERR = 'ERROR';
    
    
    
    
    function checklogin(){ 
		
		$dbcontroller = new DBController();
		$loindat = $dbcontroller->logincheck($_POST['mobile_number'],$_POST['password'],$_POST['user_roles']);
			
		if(!empty($loindat))
		{

			 $gen_authkey =mt_rand(100000, 999999);
			$auth_key_insert = $dbcontroller->auth_key_insert($_POST['mobile_number'],$_POST['user_roles'],$gen_authkey);
		
		
		if(!empty($auth_key_insert))
			{
				$success_data = array('status' => self::STATUS_OK,'response' => array('status_message' => 'Success' ),'auth_key'=>$auth_key_insert['0']['auth_key']);
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ),'auth_key'=>"null");
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}
		}
		else
		{
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ),'auth_key'=>"null");
				$response = $this->encodeJson($success_data);
			    echo $response;
		}
	}
	
 function checkregister(){

		$dbcontroller = new DBController();
		$registerdata = $dbcontroller->registercheck($_POST['mobile_number'],$_POST['password'],$_POST['email'],$_POST['ref_id'],$_POST['question'],$_POST['answer'],$_POST['name'],$_POST['user_roles']);

		if(!empty($registerdata))
			{
				$success_data = array('status' => self::STATUS_OK,'response' => array('status_message' => 'Success' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'user already exists' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}
		
		}

function checkforgot(){
		$dbcontroller = new DBController();
		$checkforgot = $dbcontroller->checkforgot($_POST['mobile_number'],$_POST['user_roles']);
	
		if(!empty($checkforgot))
		{

			 $rand_num =$rand =mt_rand(100000, 999999);
			$rand_num = $dbcontroller->rand_num($_POST['mobile_number'],$_POST['user_roles'],$rand_num);
		
		if(!empty($rand_num))
			{
				$cm =  $_POST['mobile_number'];
				$forgot_url = "http://kooexchange.com/demo/forgot_password.php?rand=".$rand_num['0']['rand_num']."&mn=".$cm;
				$sms = $dbcontroller-> gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url");
				$success_data = array('status' => self::STATUS_OK,'response' => array('status_message' => 'Success' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}
		}
		else
		{
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ),'auth_key'=>"null");
				$response = $this->encodeJson($success_data);
			    echo $response;
		}
	}
function dashboard(){
		$dbcontroller = new DBController();
		$dashboard_deatils = $dbcontroller->dashboard($_POST['auth_key']);
		//~ print_r($dashboard_deatils);
		
		if(!empty($dashboard_deatils))
			{
				$success_data = array('status' => self::STATUS_OK,'status_message' => 'Success', 'data' => $dashboard_deatils);
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}
		
	}

function contact(){
		$dbcontroller = new DBController();
		$contactdata = $dbcontroller->contact($_POST['user_id'],$_POST['subject'],$_POST['message'],$_POST['query_type'],$_POST['created_on']);
		if(!empty($contactdata))
			{
				$success_data = array('status' => self::STATUS_OK,'response' => array('status_message' => 'Success' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}

}

 function referral_list() {
	$dbcontroller = new DBController();
		$referrallist_deatils = $dbcontroller->referral_list($_POST['user_id']);
		print_r($referrallist_deatils);
		exit;
		if(!empty($referrallist_deatils))
			{
				$success_data = array('status' => self::STATUS_OK,'response' => array('status_message' => 'Success' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			}
			
			else {
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));
				$response = $this->encodeJson($success_data);
			    echo $response;
			
			}
	
 }





/*old*/
	function getAllProperties() {

		$property = new Properties();
		$rawData = $property->getAllProperty();
		$success_data = array();
		$rawData = json_decode($rawData);
		//echo "<pre>";print_r($rawData);echo "</pre>";
		if(empty($rawData)) {
			$statusCode = 404;
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));		
		} else {
			$statusCode = $rawData->statusCode;
			//$success_data = array('status' => $rawData->status,'response' => array('message' => $rawData->message,'property' => $rawData->data));
			//echo "<pre>";print_r($rawData);echo "</pre>";
			unset($rawData->statusCode);
			$success_data = $rawData;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		
		//$result = $success_data;

				
		if(strpos($requestContentType,'application/json') !== false){

			$response = $this->encodeJson($success_data);
			echo $response;
		}
	}

	function searchProperties() {

		$property = new Properties();
		$rawData = $property->searchProperties();
		$success_data = array();
		$rawData = json_decode($rawData);
		//echo "<pre>";print_r($rawData);echo "</pre>";
		if(empty($rawData)) {
			$statusCode = 404;
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));		
		} else {
			$statusCode = $rawData->statusCode;
			//$statusCode = 200;
			//$success_data = array('status' => $rawData->status,'response' => array('message' => $rawData->message,'property' => $rawData->data));
			 unset($rawData->statusCode);
			$success_data = $rawData;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		
		//$result = $success_data;

				
		if(strpos($requestContentType,'application/json') !== false){

			$response = $this->encodeJson($success_data);
			echo $response;
		}
	}
	function propertiesDetails() {

		$property = new Properties();
		$rawData = $property->propertiesDetails();
		$success_data = array();
		$rawData = json_decode($rawData);
		//echo "<pre>";print_r($rawData);echo "</pre>";
		if(empty($rawData)) {
			$statusCode = 404;
			$success_data = array('status' => self::STATUS_ERR,'response' => array('status_message' => 'No data Found' ));		
		} else {
			$statusCode = $rawData->statusCode;
			//$statusCode = 200;
			//$success_data = array('status' => $rawData->status,'response' => array('message' => $rawData->message,'property' => $rawData->data));
			 unset($rawData->statusCode);
			$success_data = $rawData;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		
		//$result = $success_data;

				
		if(strpos($requestContentType,'application/json') !== false){

			$response = $this->encodeJson($success_data);
			echo $response;
		}
	}
	
	public function defaultfun(){

		$statusCode = 405;
		$success_data =  array('status' => self::STATUS_ERR, 'response' => array('status_message' => 'Function not exits.'));

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		
		//$result = $success_data;

				
		if(strpos($requestContentType,'application/json') !== false){

			$response = $this->encodeJson($success_data);
			echo $response;
		}
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
}
?>
