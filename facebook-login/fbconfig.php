<?php
   include("../config.php");
// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
// FacebookSession::setDefaultApplication( '348820975669069','77e48ada4c63b6bcfc6651ea6a120b73' );
FacebookSession::setDefaultApplication('2309721359240776','dfee86672ff809614856847624cc67de');
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('https://www.koofamilies.com/demo1/facebook-login/fbconfig.php' );
try {  
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
	
  // When Facebook returns an error
} catch( Exception $ex ) {
	
	
  // When validation fails or other local issues
}
$past_url=$_SERVER['HTTP_REFERER'];

// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
    /* ---- header location after session ----*/
   // echo $fbid ; die();
    $fbmemebr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE fbid = '$fbid' "));
    
    if(isset($fbmemebr)){
       // echo $fbmemebr ; die();
     $upt_tt = mysqli_query($conn, "UPDATE `users` SET `fbid`= '$fbid' WHERE fbid = '$fbid' ");  
    }else{
        //echo 'fbmemebr' ; die();
        $date = date('Y-m-d') ;
        $SQL = "INSERT INTO `users` (fbid , email , name , image , user_roles , created_at) values ('$fbid' , '$femail' , '$fbfullname' , 'https://graph.facebook.com/$fbid' ,1 , '$date')" ;
       ///echo $SQL ; die();
      $test_method = mysqli_query($conn, $SQL);
    }
     $fbmemebrArray = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE fbid = '$fbid' "));
     //print_r($fbmemebrArray); die();
     
    $_SESSION['login'] = $fbmemebrArray['id'];
  
	if(strpos($past_url, 'login.php') !== false) 
	{  

		header("Location: ../dashboard.php");
	}
	else
	{
	   header("Location: ../order_place.php");	
	}
  
} else {
  $loginUrl = $helper->getLoginUrl();
    // header("Location: ../dashboard.php");
 header("Location: ".$loginUrl);
}
?>