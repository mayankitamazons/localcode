<?php
//require_once ('../includes/config.php');
class DBController {
	private $conn = "";
	private $host = "localhost";
	private $user = "kooexcha_demo";
	private $password = "}D5K_0AhUBSU";
	private $database = "kooexcha_demo";

	

	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->conn = $conn;
		}
	}

	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	
	 function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg)  
            {           
                        $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
                        $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
                        $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
                        $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
                        $fd = @implode ('', file ($url));      
                        if ($fd)  
                        {                       
				    if ($fd > 0) {
					//Print("MT ID : " . $fd);
					$ok = "success";
				    }        
				    else {
					print("Please refer to API on Error : " . $fd);
					$ok = "fail";
				    }
                        }           
                        else      
                        {                       
                                    // no contact with gateway                      
                                    $ok = "fail";       
                        }           
                        return $ok;  
            } 
	
	function logincheck($mobile_number,$password,$user_roles)
	{
		
				 $select_query = "SELECT * FROM users WHERE mobile_number='$mobile_number' AND user_roles= '$user_roles' AND password= '$password' ";

				 		$result = mysqli_query($this->conn,$select_query);
				 		
				 		while($row=mysqli_fetch_assoc($result)) {
							
		
							
			$resultset[] = $row;
		}
		if(!empty($resultset))
			return $resultset;
	}
	
	function auth_key_insert($mobile_number,$user_roles,$gen_authkey)
	{	
		
    $updatequery =  "UPDATE users SET auth_key='$gen_authkey' WHERE mobile_number= '$mobile_number' AND user_roles = '$user_roles'";
	$result = mysqli_query($this->conn,$updatequery);
		if($result == 1)
		{
			$select_query = "SELECT * FROM users WHERE mobile_number='$mobile_number'AND user_roles = '$user_roles'";
			$result = mysqli_query($this->conn,$select_query);

			while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
			}
			if(!empty($resultset))
			return $resultset;
		}
		
	}
	
	
	
function registercheck($mobile_number,$password,$email,$ref_id,$question,$answer,$name,$user_roles)
	{
			
		 $select_query = "SELECT * FROM users WHERE mobile_number='$mobile_number' AND user_roles= '$user_roles'";	

						$result =  mysqli_num_rows(mysqli_query($this->conn,$select_query));
						if($result >= 1)
						{
}
else
{
			$code = uniqid();

		$fund_pass = mt_rand(100000, 999999);	
							$ref = $name." ".$code;

			$insertquery= "INSERT INTO users SET name='$name',user_roles='$user_roles', password='$password', referral_id='$ref',security_answer= '$answer',security_questions= '$question',email='$email',mobile_number='$mobile_number',fund_password='$fund_pass',verification_code='$code',referred_by='$referred_by',isLocked='1',joined='1',real_name='$name',mric_no='',address='',facebook='',authentication='',rand_num='',auth_key=''";
			// print_r($insertquery);
			
			$dbcontroller = new DBController();
			$result = mysqli_query($this->conn,$insertquery);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
			
		}
	}
	
	function checkforgot($mobile_number,$user_roles){
		 $select_query = "SELECT * FROM users WHERE mobile_number='$mobile_number' AND user_roles= '$user_roles' ";

				 		$result = mysqli_query($this->conn,$select_query);
				 		
				 		while($row=mysqli_fetch_assoc($result)) {
							
		
							
			$resultset[] = $row;
		}
		if(!empty($resultset))
			return $resultset;
	}
	
	
	function rand_num($mobile_number,$user_roles,$rand_num)
	{	
		
    $updatequery =  "UPDATE users SET rand_num='$rand_num' WHERE mobile_number= '$mobile_number' AND user_roles = '$user_roles'";
	$result = mysqli_query($this->conn,$updatequery);
		if($result == 1)
		{
			$select_query = "SELECT * FROM users WHERE mobile_number='$mobile_number'AND user_roles = '$user_roles'";
			$result = mysqli_query($this->conn,$select_query);

			while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
			}
			if(!empty($resultset))
			return $resultset;
		}
		
	}
	
	function dashboard($auth_key){
		
		$dashboard_query = "SELECT balance_usd,balance_inr,balance_myr FROM users WHERE auth_key='$auth_key'";
	$result = mysqli_query($this->conn,$dashboard_query);

			while($row=mysqli_fetch_assoc($result)) {
			$resultset = $row;
			}
			if(!empty($resultset))
			return $resultset;
	}
	
	
	function contact($user_id,$subject,$message,$query_type){

	$contact_query = "INSERT INTO contacts SET user_id='$user_id', subject='".$subject."', message='".$message."', query_type='".$query_type."', created_on='".time()."'";
	$dbcontroller = new DBController();
			$result = mysqli_query($this->conn,$contact_query);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
	
}

 function referral_list($user_id) {
	$referral_list= "SELECT users.referral_id,users.name,users.email,users.referred_by FROM users WHERE users.id='$user_id'";
	print_r($referral_list);
	$result = mysqli_query($this->conn,$referral_list);

			while($row=mysqli_fetch_assoc($result)) {
			$resultset = $row;
			}
			if(!empty($resultset))
			return $resultset;
}







/*old*/

	function executeQuery($query) {
        $conn = $this->connectDB();    
        $result = mysqli_query($conn, $query);
        if (!$result) {
            //check for duplicate entry
            if($conn->errno == 1062) {
                return false;
            } else {
                trigger_error (mysqli_error($conn),E_USER_NOTICE);
				
            }
        }		
        $affectedRows = mysqli_affected_rows($conn);
		return $affectedRows;
		
    }
	function executeSelectQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}
		if(!empty($resultset))
			return $resultset;
	}
	function executeSelectQuerySingleRow($query) {
		$result = mysqli_query($this->conn,$query);
		$resultset = mysqli_fetch_assoc($result);
		// while($row=mysqli_fetch_assoc($result)) {
		// 	$resultset[] = $row;
		// }
		if(!empty($resultset))
			return $resultset;
	}
}
?>
