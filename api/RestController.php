<?php
require_once("AuthenticationRestHandler.php");
$method = $_SERVER['REQUEST_METHOD'];
$view = "";
$page_key = ''; 
//echo "<pre>";print_r($_REQUEST);echo "</pre>";


if(isset($_REQUEST['action']))
{
   $page_key = $_REQUEST['action'];
}
/*
controls the RESTful services
URL mapping
*/

	switch($page_key){

		case "CheckLogin": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->checklogin();
			break;
		case "CheckRegister": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->checkregister();
			break;
			case "CheckForgot": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->checkforgot();
			break;
			case "Dashboard": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->dashboard();
			break;
			case "Contact": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->contact();
			break;
			case "Referral_list": 
			// to handle REST Url /mobile/list/
			$AuthenticationRestHandler = new AuthenticationRestHandler();
			$result = $AuthenticationRestHandler->referral_list();
			break;
			
			
			
		default:
			$PropertyRestHandler = new PropertyRestHandler();
			$result = $PropertyRestHandler->defaultfun();
			break; 

}
?>
