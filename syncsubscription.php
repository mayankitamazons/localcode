<?php 
include('config.php');

$merchant_id =  $_SESSION['login'];
$m_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT membership_plan FROM users WHERE id='$merchant_id'"));
if($m_data['membership_plan'])
{
	$conn2 = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");
	if(!$conn2)
	{
			
		$msg="Failed to Connect Live Server Try Again";
	}
	else
	{
		$totalplanlocal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_count FROM membership_plan WHERE user_id='$merchant_id'"))['total_count'];
		$liveplan = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT count(id) as total_count FROM membership_plan WHERE user_id='$merchant_id'"))['total_count'];
		if($totalplanlocal==$liveplan)
		{
		}
		else
		{
			
			$localplanarray = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' order by id desc"),MYSQLI_ASSOC);
			$liveplanarray = mysqli_fetch_all(mysqli_query($conn2, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' order by id desc"),MYSQLI_ASSOC);
			$i=0;
			
			foreach($liveplanarray as $live)
			{
				extract($live);
				$token_id_check=$live['token_id'];
				$tokemmatchid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_count FROM user_membership_plan WHERE token_membership='$token_id_check'"))['total_count'];
				if($tokemmatchid)
				{
					break;
				}
				else
				{
					$in=mysqli_query($conn, "INSERT INTO `membership_plan` (`id`,`user_id`,`token_id`,`plan_name`, `plan_desc`, `plan_img`, `plan_amount`, `plan_benefit`, `total_min_order_amount`, `total_max_order_amount`, `valid_from`, `valid_to`, `plan_type`, `validity`, `created`, `status`) 
					VALUES ('$id','$user_id','$token_id','$plan_name', '$plan_desc', '$plan_img', '$plan_amount', '$plan_benefit', '$total_min_order_amount', '$total_max_order_amount', '$valid_from', '$valid_to', '$plan_type', '$validity', '$created', '$status')");
					
				}
				
				$i++;
			}
		}
		// now sync member user 
		$totalmembershiplocal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_count FROM user_membership_plan WHERE merchant_id='$merchant_id'"))['total_count'];
		// print_R($totalmembershiplocal);
		// die;
		$totalmembershiplive = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT count(id) as total_count FROM user_membership_plan WHERE merchant_id='$merchant_id'"))['total_count'];
		
		if($totalmembershiplocal==$totalmembershiplive)
		{
		}
		else
		{
			$localmemershiparray = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM user_membership_plan WHERE merchant_id='$merchant_id' order by id desc"),MYSQLI_ASSOC);
			
			$livememershiparray = mysqli_fetch_all(mysqli_query($conn2, "SELECT * FROM user_membership_plan WHERE merchant_id='$merchant_id' order by id desc"),MYSQLI_ASSOC);
			$i=0;
			
			foreach($livememershiparray as $lm)
			{
				extract($lm);
				if($totalmembershiplocal==0)
				{
					$user_member_plan = "INSERT INTO `user_membership_plan`(`id`,`user_id`,`user_mobile`,`token_membership`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`) 
					VALUES ('$id','$user_id','$user_mobile','$token_membership','$merchant_id','$plan_id','$paid_via','$paid_date','$plan_active','$created','$is_upgrade')";
					mysqli_query($conn,$user_member_plan); 	
				}
				else
				{
					// match all old token 
					$tokencheck=$lm['token_membership'];
					$tokemmatch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as total_count FROM user_membership_plan WHERE token_membership='$tokencheck'"))['total_count'];
					if($tokemmatch)
					{
						break;
					}  
					else
					{
						$is_upgrade=$lm['is_upgrade'];
						if($is_upgrade=="y")
						mysqli_query($conn,"update user_membership_plan set plan_active='n' where user_id='$user_id' and merchant_id='$merchant_id'");
						$user_member_plan = "INSERT INTO `user_membership_plan`(`id`,`user_id`,`user_mobile`,`token_membership`,`merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`) 
						VALUES ('$id','$user_id','$user_mobile','$token_membership','$merchant_id','$plan_id','$paid_via','$paid_date','$plan_active','$created','$is_upgrade')";
						mysqli_query($conn,$user_member_plan);	  		
					}
					
				}
				$i++;
			}
		}
	}   
	$msg="SubScription Plan Sync";
}
else
{
	$msg="SubScription Plan is not active,Contact Support";
}?> 
<div><h2><?php echo $msg; ?></h2></div>

<?php  echo "<meta http-equiv='refresh' content='5;url=dashboard.php'>";	
?>