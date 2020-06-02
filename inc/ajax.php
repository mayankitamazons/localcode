<?php
require_once('config.php');
require_once('functions.php');
if(!isset($_SESSION)){session_start();}
//if(!is_ajax())
//{
	$db;
	if ( !isset($db))
    {
		include_once("Pdodb.class.php");
		//MySQL
		$db = new Pdodb(DB_DSN, DB_USER ,DB_PASSWORD );
	}

	if(isset($_REQUEST["action"]) && !empty($_REQUEST["action"])){ //Checks if action value exists
		$action = trim($_REQUEST["action"]);
		
		switch($action){
			//Switch case for value of action
			case "get_staff_attendance": get_staff_attendance(); break;
			case "save_staff_clock_in": save_staff_clock_in(); break;
			case "save_staff_clock_out": save_staff_clock_out(); break;
			
		}
	}
//}
 
//Function to check if the request is an AJAX request
function is_ajax(){
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_staff_attendance()
{
	global $db;
	
	try
	{
		$staff_id = isset($_REQUEST['staff'])?$_REQUEST['staff']:"";
		$from_date = isset($_REQUEST['from_date'])?$_REQUEST['from_date']:"";
		$to_date = isset($_REQUEST['from_date'])?$_REQUEST['to_date']:"";
		if($staff_id == "")
		{
			$q = $db->pdo->prepare("SELECT SL.* ,
					COALESCE(SUM(FLOOR(TIMESTAMPDIFF(HOUR, SL.time_in, IFNULL(SL.time_out ,NOW())))),0) AS hour_worked,
					COALESCE(SUM(FLOOR(TIMESTAMPDIFF(MINUTE, SL.time_in, IFNULL(SL.time_out ,NOW())))),0) AS min_worked,
					COALESCE(SUM(MOD(HOUR(TIMEDIFF(SL.time_out,SL.time_in)), 24)),0) AS hours,
					COALESCE(SUM(MINUTE(TIMEDIFF(SL.time_out,SL.time_in))),0) AS mins,
					GROUP_CONCAT(DATE_FORMAT(SL.time_in,'%h:%i %p') SEPARATOR ', ') AS time_in,
					GROUP_CONCAT(DATE_FORMAT(SL.time_out,'%h:%i %p') SEPARATOR ', ') AS time_out,
					DATE_FORMAT(SL.login_date,'%W') day_of_week,
					U.name AS staff_name 
					  FROM staff_activity_logs  AS SL 
					   INNER JOIN users AS U ON U.id = SL.user_id
					  WHERE (DATE(SL.login_date) BETWEEN :from_date AND :to_date) AND SL.time_out IS NOT NULL
					  GROUP BY SL.login_date , SL.user_id");
  			
  			
			$q->bindParam(':from_date', $from_date, PDO::PARAM_STR);
			$q->bindParam(':to_date', $to_date, PDO::PARAM_STR);
  
		}
		else
		{
			$q = $db->pdo->prepare("SELECT SL.* ,
					COALESCE(SUM(FLOOR(TIMESTAMPDIFF(HOUR, SL.time_in, IFNULL(SL.time_out ,NOW())))),0) AS hour_worked,
					COALESCE(SUM(FLOOR(TIMESTAMPDIFF(MINUTE, SL.time_in, IFNULL(SL.time_out ,NOW())))),0) AS min_worked,
					COALESCE(SUM(MOD(HOUR(TIMEDIFF(SL.time_out,SL.time_in)), 24)),0) AS hours,
					COALESCE(SUM(MINUTE(TIMEDIFF(SL.time_out,SL.time_in))),0) AS mins,
					GROUP_CONCAT(DATE_FORMAT(SL.time_in,'%h:%i %p') SEPARATOR ', ') AS time_in,
					GROUP_CONCAT(DATE_FORMAT(SL.time_out,'%h:%i %p') SEPARATOR ', ') AS time_out,
					DATE_FORMAT(SL.login_date,'%W') day_of_week,
					U.name AS staff_name 
					  FROM staff_activity_logs  AS SL 
					   INNER JOIN users AS U ON U.id = SL.user_id
					  WHERE SL.user_id = :staff_id  AND (DATE(SL.login_date) BETWEEN :from_date AND :to_date) AND SL.time_out IS NOT NULL
					  GROUP BY SL.login_date");
  
			$q->bindParam(':staff_id', $staff_id, PDO::PARAM_STR);
			$q->bindParam(':from_date', $from_date, PDO::PARAM_STR);
			$q->bindParam(':to_date', $to_date, PDO::PARAM_STR);
		}
		
		
		$q->execute();

		$ret = $q->fetchAll(PDO::FETCH_ASSOC);
		

		
		$data = array('success'=> true,'ret'=>$ret);
	}
	catch (PDOException $e)
	{
		 $data = array('success'=> false,'message'=>$e->getMessage());
		// JSON encode and send back to the server
		
	}
	echo json_encode($data);
	exit();
}



function save_staff_clock_in()
{
	global $db;
	try
	{
		$mobile_number = isset($_REQUEST['mobile_number'])?$_REQUEST['mobile_number']:"";
		$countrycode = isset($_REQUEST['countrycode'])?$_REQUEST['countrycode']:"";
		$password = isset($_REQUEST['password'])?$_REQUEST['password']:"";
		if (trim($mobile_number) == "" || trim($countrycode) == "" || trim($password) == "") 
		{
			throw new Exception('Invalid data');
		}
		
		$mobile_number = $countrycode.$mobile_number;
		
		$q = $db->pdo->prepare("SELECT *
				  FROM users AS U
				  WHERE U.user_roles = '5' AND U.mobile_number = :mobile_number AND U.password = :password");
  			
		$q->bindParam(':mobile_number', $mobile_number, PDO::PARAM_STR);
		$q->bindParam(':password', $password, PDO::PARAM_STR);
		$q->execute();
		
		$user = $q->fetch(PDO::FETCH_ASSOC);
	
		if(!$user)
		{
			throw new Exception('Invalid user');
		}
		else
		{
			//checking already clock in today for user
			$user_id = $user['id'];
			$login_date = date('Y-m-d');
			$q = $db->pdo->prepare("SELECT *
				  FROM staff_activity_logs AS SL
				  WHERE SL.user_id = :user_id AND SL.active = 1 AND SL.login_date = :login_date ORDER BY id DESC LIMIT 1");
  			
			$q->bindParam(':user_id', $user_id, PDO::PARAM_STR);
			$q->bindParam(':login_date', $login_date, PDO::PARAM_STR);
			$q->execute();
			
			$staff_activity = $q->fetch(PDO::FETCH_ASSOC);
			if($staff_activity)
			{
				throw new Exception('Already Clock In Today. Please Clock out and Clock in');
			}
			else
			{
				$time_in = date('Y-m-d H:i:s');
				$active = 1;
				$sql_staff_activity_logs = "INSERT INTO staff_activity_logs 
		    			(
		    			user_id, 
		    			login_date, 
		    			time_in, 
		    			active
		    			)
						values
						(
						'$user_id', 
		    			'$login_date', 
		    			'$time_in', 
		    			'$active'
						)";
						
				$stmt = $db->pdo->prepare($sql_staff_activity_logs);		
				$stmt->execute();	
			}
				
					
		}

		
		$data = array('success'=> true,'message'=>"Clock in Successfully");
	}
	catch (Exception $e)
	{
		 $data = array('success'=> false,'message'=>$e->getMessage());
		// JSON encode and send back to the server
		
	}
	echo json_encode($data);
	exit();
}

function save_staff_clock_out()
{
	global $db;
	try
	{
		$mobile_number = isset($_REQUEST['mobile_number'])?$_REQUEST['mobile_number']:"";
		$countrycode = isset($_REQUEST['countrycode'])?$_REQUEST['countrycode']:"";
		$password = isset($_REQUEST['password'])?$_REQUEST['password']:"";
		if (trim($mobile_number) == "" || trim($countrycode) == "" || trim($password) == "") 
		{
			throw new Exception('Invalid data');
		}
		
		$mobile_number = $countrycode.$mobile_number;
		
		$q = $db->pdo->prepare("SELECT *
				  FROM users AS U
				  WHERE U.user_roles = '5' AND U.mobile_number = :mobile_number AND U.password = :password");
  			
		$q->bindParam(':mobile_number', $mobile_number, PDO::PARAM_STR);
		$q->bindParam(':password', $password, PDO::PARAM_STR);
		$q->execute();
		
		$user = $q->fetch(PDO::FETCH_ASSOC);
	
		if(!$user)
		{
			throw new Exception('Invalid user');
		}
		else
		{
			//checking already clock out today for user
			$user_id = $user['id'];
			$login_date = date('Y-m-d');
			$q = $db->pdo->prepare("SELECT *
				  FROM staff_activity_logs AS SL
				  WHERE SL.user_id = :user_id AND SL.active = 1 AND SL.login_date = :login_date AND SL.time_out IS NULL ORDER BY id DESC LIMIT 1");
  			
			$q->bindParam(':user_id', $user_id, PDO::PARAM_STR);
			$q->bindParam(':login_date', $login_date, PDO::PARAM_STR);
			$q->execute();
			
		
			$staff_activity = $q->fetch(PDO::FETCH_ASSOC);
			
			if(!$staff_activity)
			{
				throw new Exception('Already Clock Out Today. Please Clock in and Clock Out');
			}
			else
			{
				$time_out = date('Y-m-d H:i:s');
			
				$active = 1;
				$sql_staff_activity_logs = "UPDATE staff_activity_logs SET time_out = '$time_out' , active = '0' WHERE login_date = '$login_date' AND user_id = '$user_id' AND active = 1";
						
				$stmt = $db->pdo->prepare($sql_staff_activity_logs);		
				$stmt->execute();	
			}
				
					
		}

		
		$data = array('success'=> true,'message'=>"Clock Out Successfully");
	}
	catch (Exception $e)
	{
		 $data = array('success'=> false,'message'=>$e->getMessage());
		// JSON encode and send back to the server
		
	}
	echo json_encode($data);
	exit();
}

?>