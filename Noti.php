<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include("config.php");
Class Noti{
	
}
try{   
     $inputdata = json_decode(file_get_contents("php://input"));
	  // echo json_encode($inputdata);
	// die;
	if($inputdata)
	{
		$reciever_id=$inputdata->reciever_id;
		$msg=$inputdata->msg;
		$sender_id=$inputdata->sender_id;
		$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT moengage_unique_id FROM users WHERE id='".$reciever_id."'"));
		$sender_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='".$sender_id."'"));
		$push_id=$user_data['moengage_unique_id'];
		$sender_name=$sender_data['name'];
		
		if($push_id)
		{
			$result=exec("/usr/bin/python myscript.py");
			 $resultarray=explode(",",$result);
			
			 if (count($resultarray)>0) {
				 // code...
				 $data['camp_name']=$camp_name=$resultarray[0];
				 $data['sign']=$sign=$resultarray[1];
				 $data['push_email']=$push_id;
				 $data['title']='Message From '.$sender_name;
				 $data['message']=$msg;
				 $data['redirectURL']='http://koofamilies.com/chat/chat.php?sender='.$reciever_id.'&receiver='.$sender_id;
				 include 'push.php';
				 $user = new Push();
				 $resultpush = $user->send_push($data);
				 echo json_encode($resultpush);
				die;
			 }
		}
		
	}
//	$response = $stripe->cardPay($inputdata);
	
}catch(Exception $e){
	$response	=	$e->getMessage();
	
}
?>