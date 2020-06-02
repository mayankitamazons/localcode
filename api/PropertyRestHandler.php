<?php
require_once("SimpleRest.php");
require_once("Properties.php");
		
class PropertyRestHandler extends SimpleRest {
	const STATUS_OK = 'OK';
    const STATUS_ERR = 'ERROR';

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