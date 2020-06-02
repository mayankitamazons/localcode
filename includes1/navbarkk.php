<?php
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
    require_once ("languages/".$_SESSION["langfile"].".php");
/*else {
    $st_phone = substr($_SESSION['mobile'], 0, 2);
    if($st_phone == "60"){
        $_SESSION["langfile"] = "malaysian";
    } else if($st_phone == "86"){
         $_SESSION["langfile"] = "chinese";
    } else {
        $_SESSION["langfile"] = "english";
    }
    require_once ("languages/".$_SESSION["langfile"].".php");
}*/


$profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
// print_R($profile_data);
$last_login=$profile_data['last_login'];

$date = date('Y-m-d H:i:s');
if($last_login)
$start_dt=date("Y-m-d H:i:s", $last_login);
else
$start_dt = $date;	
$end_dt = $date;
  $sql1 = "
	SELECT *
    FROM order_list
    left join users on order_list.user_id = users.id
    WHERE created_on >= '$start_dt' AND created_on <= '$end_dt' AND merchant_id = '$merchant' and status in(0,2)";

$result1 = mysqli_query($conn, $sql1);
$pending_count=mysqli_num_rows($result1);
?>
<nav class="navbar">
    <!--<audio id="myAudio" style="display:none;">
      <source src="/notification.mp3" type="audio/mpeg">
    </audio>-->
    <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'];?>" >
<!-- Logo Area -->
<div class="navbar-header">
<a href="index.php" class="navbar-brand">
    <p class="logo-expand">Koo Families</p>
    <p class="logo-collapse">Koo</p>
<!-- <p>OSCAR</p> -->
</a>
</div>
<!-- /.navbar-header -->
<!-- Left Menu & Sidebar Toggle -->
<ul class="nav navbar-nav">
<li class="sidebar-toggle"><a href="javascript:void(0)" class="ripple"><i class="fa fa-bars" aria-hidden="true"></i></a>
</li>
</ul>
<!-- /.navbar-left -->
<div class="spacer"></div>

<!-- User Image with Dropdown -->
<ul class="nav navbar-nav">
<?php if(isset($profile_data['user_roles']) && $profile_data['user_roles'] ==  '2'){?>

         <li class="home_screen">
       <a href="prepos.php" style="cursor:pointer; font-size: 18px;height: 74px;margin-top: 7%;color: white;margin-left: -14%;background:#4387fd;border: none;" class="btn btn-primary">Order</a>
		</li>
    <?php }?>
	<?php if(isset($profile_data['user_roles']) && $profile_data['user_roles'] ==  '5'){?>

         <li class="home_screen">
       <a href="prepos.php" style="cursor:pointer; font-size: 18px;height: 74px;margin-top: 7%;color: white;margin-left: -14%;background:#4387fd;border: none;" class="btn btn-primary">Order</a>
		</li>
    <?php }?>
	<!--new-->
	<?php  if( isset($profile_data['user_roles']) && $profile_data['user_roles'] !=  '') { ?>
<li class="home_screen">	 
    	<div class="home_screen">
            <input type="hidden" class="sender_id" value="<?php echo $_SESSION['login'];?>">
    		<a href="https://koofamilies.com" class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
				<i class="fa fa-home" aria-hidden="true"></i>
    		</a>
    
    	</div>
    </li>
    <?php } else { ?>
		<li class="home_screen">	 
    	<div class="home_screen">
    
    		<a href="https://koofamilies.com/login.php" class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
				<i class="fa fa-sign-in"></i>
    		</a>
    
    	</div>
    </li>
    <?php } ?>
    <!--end new--->

    <li class="dropdown">	
    	<div class="stacked-icons">
    		<a class="fa-stack fa-1x unread" style="cursor:pointer; margin-top: 30px; margin-right:5px;">
    			<i class="fa fa-comment fa-stack-2x"></i>
    			<strong class="fa-stack-1x fa-stack-text fa-inverse unread_num" style="color: red;"></strong>
    		</a>
    
    	</div>
    </li>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" style="cursor:pointer; font-size: 18px; padding:0" data-toggle="dropdown" >Language</a>
  
  <ul class="dropdown-menu" style="padding:10px 15px;">
	<a href="?language=english" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">English</a>
	<a href="?language=chinese" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Chinese</a>
	<a href="?language=malaysian" style="display:block; color:#000; font-size: 18px; margin-bottom: 10px;">Malay</a>
  </ul>
</li>
<li class="dropdown">
  <a class="dropdown-toggle" style="cursor:pointer" data-toggle="dropdown"><img src="images/wallet.png" style="width:40px"></a>
  <?php
  $balance = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'")) : '';
	$a_user_id=$balance['id'];
 ?>
  <ul class="dropdown-menu" style="padding:10px 10px;">
	<table class="table table-striped">
	  <?php if( $balance['balance_myr']) {?>
	    <tr><th>MYR</th>
		<td><a href="transaction_history.php?coin_type=MYR">
		<?php if( $balance['balance_myr']) { echo number_format($balance['balance_myr'],2);} else{ echo "0.00";} ?></a></td>
		</tr>
		<?php } if( $balance['balance_usd']) { ?>
		<tr><th>CF</th>
		<td><a href="transaction_history.php?coin_type=CF">
		<?php if($balance['balance_usd']) { echo number_format($balance['balance_usd'],2);}else{ echo "0.00";} ?></a></td>
		   
		</tr>
		<?php  } if( $balance['balance_inr']) {?>
		<tr><th>Koo Coin</th>
		<td><a href="transaction_history.php?coin_type=INR">
		<?php if( $balance['balance_inr'] ) { echo number_format($balance['balance_inr'],2);}else{ echo "0.00";} ?></a></td>
		
		</tr>
		<?php  }
						    $sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where user_id='$a_user_id'";
						
						$sub_rows = mysqli_query($conn,$sq);
						 if(mysqli_num_rows($sub_rows)>0){
							while ($swallet=mysqli_fetch_assoc($sub_rows)){
					?>
					
					<tr><th><?php echo $swallet['special_coin_name'];?></th>
					<td><a href="transaction_history.php?coin_type=<?php echo $swallet['special_coin_name'];?>">
					<?php if($swallet['coin_balance']){echo number_format($swallet['coin_balance'],2);}else{ echo "0.00";} ?></a></td></tr>
						
							<?php } } ?>
	</table>
  </ul>
</li>
<li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown">
<span class="thumb-sm"><img src="./Dashboard_files/user-image.png" class="rounded-circle" alt="" style="width: 40px;"> </span></a>
<div class="dropdown-menu dropdown-left dropdown-card-dark text-inverse" style="padding:8px">
	<!-- logout menu -->
	<?php   
	$setup_shop=$balance['setup_shop'];
	$user_roles=$balance['user_roles'];
	  if(($setup_shop=="y") && ($user_roles=="2")){ ?>   
	<a class="Logoutpop">Logout</a>
	<?php } else { ?>
	  	<a href="logout.php">Logout </a>     
	<?php } ?>
	<!-- // logout menu -->
</div>
</li>
</ul>
<!-- /.navbar-right -->
</nav>
<div class="modal fade" id="LoginModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Logout</h4>
										
										
                                    </div>
                                    <form id ="closeshop">
                                        <div class="modal-body closeshop" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">
										  
												<a href="logout.php?s=shop_close" class="btn btn-primary">Shop Close with Logout  </a>  
												&nbsp;&nbsp;&nbsp;&nbsp;
												<?php
												  if($pending_count>0)
													{
														$url="#";
														
													}
													else
													{
														$url="logout.php?s=shift_close";
													}   
																?>
												<!--a  href="<?php echo $url; ?>" class="btn btn-primary  <?php if($pending_count>0){ echo "print_shift_stop";} ?>" ">Logout & Shift Close </a>  
												<br/>
												<br/!-->
													<a href="logout.php" class="btn btn-primary">Logout </a>    
											
										
                                        </div>
                                        <div style="margin: 10px 0 10px 34%;"  class="modal-footer product_button pop_model">
										    
                                         
                                        </div>
										<br/>
                                    </form>
                                </div>
                            </div>
                        </div>
<style>

i.fa.fa-home {
    font-size: 30px;
    margin-left: -15px;
    color:#09caab;
}
i.fa.fa-sign-in {
    font-size: 26px;
    margin-left: -10px;;
    color:#09caab;
    margin-top: 5px;
}
@media only screen and (max-width: 400px) and (min-width: 300px)  {
li.home_screen {
    margin-right: -15px;
}
i.fa.fa-bars {
    font-size: 19px;
}
}

</style>
