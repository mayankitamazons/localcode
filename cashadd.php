     <?php
	 include("config.php");
	 $profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
	 if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
	if(isset($_POST['Add']))
	{
	  if($_POST['amount'])
	  {
		  // print_R($_POST);
		  
		extract($_POST);
        if($cash_id)
		{
			$cash_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cash_system WHERE is_active='y' and id='".$cash_id."'"));
			// $old_balance=$cash_data['balance'];
			$opening=$cash_data['opening'];
			$cash_in=$cash_data['cash_in'];
			$sales=$_POST['sales'];
			$cash_out=$cash_data['cash_out'];
			$void_tras=$cash_data['void_tras'];
			$amount=$_POST['amount'];
			if($cash_type=="cash_in")
			{
				$cash_in=$cash_data['cash_in']+$amount;
			} else if($cash_type=="cash_out")
			{
				$cash_out=$cash_data['cash_out']+$amount;
			} else if($cash_type=="void_tras")
			{
				$void_tras=$cash_data['void_tras']+$amount;
			}
			// $balance=($cash_in+$cash_out)-$void_tras;
			$balance=$opening+$sales+$cash_in-($cash_out+$void_tras);
			 $date = date('Y-m-d H:i:s');
			$tras_utc=strtotime($date);
			 $query="UPDATE cash_system SET cash_in= '$cash_in',cash_out='$cash_out',void_tras='$void_tras',balance='$balance',sales='$sales' WHERE `cash_system`.`id`='$cash_id'";
			$update=mysqli_query($conn,$query);
			if($update)
			{
				$iquery="INSERT INTO `cash_flow` (`cash_id`, `user_id`, `amount`, `pre_balance`, `after_balance`, `cash_type`, `paid_from`, `cash_description`, `remark`, `tras_utc`) VALUES ('$cash_id', '$loginidset', '$amount', '$old_balance', '$balance',
				'$cash_type', '$paid_from', '$cash_description', '$remark', '$tras_utc')";
				$insert=mysqli_query($conn,$iquery);
				// header('cash.php');  
				header("Location: cash.php");     
			}
			
		}			
	  }		  
	}
	 ?>