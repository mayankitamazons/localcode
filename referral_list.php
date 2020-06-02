<?php 
include("config.php");
  
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

$limit = 25;

$total_rows = mysqli_num_rows(mysqli_query($conn, "(SELECT users.referral_id,users.name,users.email,users.referred_by FROM users WHERE users.id='".$_SESSION['login']."')"));
//$referral_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT referral_id FROM users WHERE id =".$_SESSION['login']));

$year = date('Y');
$month = date('m');
$start_dt = $year . '-'. $month. '-01 00-00-00';
$end_dt = $year . '-'. $month . '-31 23-59-59';


$referral_id = isset($_SESSION['referral_id']) ? $_SESSION['referral_id'] : '0';

 $sql = "	
		SELECT first_ref, second_ref, third_ref, referred_by, SUM(IF((a.date >= '".$start_dt."') AND (a.date <= '".$end_dt."'), rate, 0)) MONTH, SUM(rate) rate, SUM(cf) cf
    	FROM (
        	SELECT a.referral_id first_ref, '' second_ref, '' third_ref, a.referred_by, subscription.subscription_rate * 0.05 rate, b.subscription_date DATE, subscription.subscription_rate * 0.002 cf
        	FROM (
        	SELECT users.name, users.id, users.referral_id, users.referred_by, users.mobile_number
        	FROM users 
        	WHERE users.referred_by = '".$referral_id."' ) a LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b 
        	ON a.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type

        UNION ALL
        
            SELECT a.first_ref, users.referral_id second_ref,  '' third_ref, a.referred_by referred_by, subscription.subscription_rate * 0.02 rate, b.subscription_date DATE, subscription.subscription_rate * 0.002 cf
        	FROM (
        		SELECT users.name, users.id, users.referral_id first_ref, users.referred_by, users.mobile_number
        		FROM users
        		WHERE users.referred_by = '".$referral_id."'
        		) a INNER JOIN users ON a.first_ref = users.referred_by LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b 
        		ON users.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type
    
        UNION ALL
        
            SELECT a.first_ref, a.second_ref, users.referral_id third_ref,  a.referred_by referred_by, subscription.subscription_rate * 0.01 rate, b.subscription_date DATE, subscription.subscription_rate * 0.002 cf
        	FROM (
        		SELECT users.name, users.id, a.first_ref, users.referral_id second_ref, users.mobile_number, a.referred_by
        		FROM (
        			SELECT users.name, users.id, users.referral_id first_ref, users.referred_by, users.mobile_number
        			FROM users
        			WHERE users.referred_by = '".$referral_id."' ) a 
        		INNER JOIN users ON a.first_ref = users.referred_by ) a
        	INNER JOIN users ON a.second_ref = users.referred_by LEFT JOIN (SELECT users.referral_id, TYPE, subscription_date FROM merchant_subscription ms INNER JOIN users ON users.id = ms.user_id)b
        	ON users.referral_id = b.referral_id LEFT JOIN subscription ON subscription.id = b.type
    	) a
    	GROUP BY first_ref, second_ref, third_ref
			";

$array_refs = array();
if ($result=mysqli_query($conn,$sql))
  {
	
  // Fetch one and one row
  
  while ($row=mysqli_fetch_row($result))
    {
		$item = array(
			"first" => $row[0],
			"second" => $row[1],
			"third" => $row[2],
			"month" => $row[4],
			"rate" => $row[5],
			"CF" => $row[6]
		);
		array_push($array_refs, $item);
    }
  	// Free result set
  	mysqli_free_result($result);
   	$message_counts = array(0, 0, 0);
}

$sql_referred = "
	SELECT a.third_role, a.third_id, a.third_name, a.third_level, a.second_role, a.second_id, a.second_name, a.second_level, IF(users.id is NULL, '', users.user_roles) first_role, IF(users.id IS NULL, '', users.id) first_id, IF(users.name IS NULL, '', users.name) first_name, a.third_business1, a.third_business2, a.second_business1, a.second_business2, users.business1 first_business1, users.business2 first_business2
	FROM (
	SELECT a.third_role, a.third_id, a.third_name, a.third_level,a.third_business1, a.third_business2, users.referral_id second_level, users.business1 second_business1, users.business2 second_business2, IF(users.id is NULL, '', users.user_roles) second_role, IF(users.id IS NULL, '', users.id) second_id, IF(users.name IS NULL, '', users.name) second_name, IF(users.referred_by='', '', users.id) first_id, IF(users.referred_by='', '-',users.referred_by) first_level
	FROM (
	SELECT users.user_roles third_role, users.id third_id, a.third_level, users.business1 third_business1, users.business2 third_business2, IF(users.referred_by='', '-', users.referred_by) second_level, users.name third_name
	FROM (
	SELECT users.id, users.referred_by third_level
	FROM users
	WHERE referral_id = '".$referral_id."' AND users.referred_by != '' ) a LEFT JOIN users ON users.referral_id = a.third_level 
	order by referred_by desc limit 1) a LEFT JOIN users ON users.referral_id = a.second_level order by referred_by desc limit 1 ) a
	LEFT JOIN users ON users.referral_id = a.first_level order by referred_by desc limit 1
			";
$array_referred = array();
$referred_names = mysqli_fetch_assoc(mysqli_query($conn,$sql_referred));
$nature_array = array(
        "Foods and Beverage, such as restaurants, healthy foods, franchise, etc",
        "Motor Vehicle, such as car wash, repair, towing, etc",
        "Hardware, such as household, building, renovation to end users",
        "Grocery Shop such as bread, fish, etc retails shops",
        "Clothes such as T-shirt, Pants, Bra, socks,etc",
        "Business to Business (B2B) including all kinds of businesses"
    );
$nature_image = array(
        "foods.jpg",
        "car.jpg",
        "household.jpg",
        "grocery.jpg",
        "clothes.jpg",
        "b2b.jpg"
    );
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	
	.pagination {
		display: inline-block;
		padding-left: 0;
		margin: 20px 0;
		border-radius: 4px;
	}
	.pagination>li {
		display: inline;
	}
	.pagination>li:first-child>a, .pagination>li:first-child>span {
		margin-left: 0;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;
	}
	.pagination>li:last-child>a, .pagination>li:last-child>span {
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
	}
	.pagination>li>a, .pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #337ab7;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #ddd;
	}
	.pagination a {
		text-decoration: none !important;
	}
	.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
		z-index: 3;
		color: #fff;
		cursor: default;
		background-color: #337ab7;
		border-color: #337ab7;
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">
	<input type="hidden" class="login_id" value="<?php echo $_SESSION['login'];?>">
	
    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="well" style="width:100%">
						<h4>REFERRAL LIST</h4>
						<p class="copy-hint" style="font-size: 16px; color: #ff0000; margin-bottom: 0;display:none;">Link has been copied, please share to your friends.</p>
						<h4 style="font-size:1.3em;">Your own referral ID: <?php echo isset($_SESSION['referral_id']) ?  $_SESSION['referral_id'] : '';?> <!--<a class="btn btn-primary" href="signup_referral.php?invitation_id=<?php echo $_SESSION['referral_id'];?>">Share</a>--><button class="btn btn-info copy-link" style="margin-left:10px;" onclick="copy_url()">Copy link</button></h4>
						<div class="referrals">
							<div class="row referral_item">
								<div class="col-md-12">
									<div class="col-md-10">
										<div class="referral_label">First Level Referral:</div>
										<?php if($referred_names['first_id'] != ""){
										?>
											<?php 
													$sql_transaction = "
    												                SELECT count(a.id) ordered_num
                                                                    FROM (
                                                                    SELECT order_list.*
                                                                    FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                    WHERE users.referral_id = '".$referred_names['first_level']."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                    where user_roles = '2' and users.referral_id = '".$_SESSION['referral_id']."'";
												$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
												$sql_favorite = "SELECT COUNT(id) favorite_num
																FROM favorities
																WHERE favorite_id = '".$referred_names['first_id']."'";
												$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
											
											    $business1 = "";
											    $business2 = "";
											    for($i = 0; $i < count($nature_array); $i++){
											        if($referred_names['first_business1'] == $nature_array[$i])
											            $business1 = $nature_image[$i];
											        if($referred_names['first_business2'] == $nature_array[$i])
											            $business2 = $nature_image[$i];
											    }
											    
											    $sql_k = "select account_type from users where referral_id = '".$referred_names['first_level']."' order by user_roles desc limit 1";
										        $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));
											?>
											<div class="referral_name">
											    <?php if($referred_names['first_role'] == '2'){?>
											        <a href="structure_merchant.php?merchant_id=<?php echo $referred_names['first_id'];?>"><?php echo $referred_names['first_name'];?></a>
											    <?php } else {?>
											        <?php echo $referred_names['first_name'];?>
											    <?php }?>
											</div>
											<!--<?php if($referred_names['first_role'] == '1'){?>
											    <img class="member_image" src="/img/member.jpg">
											<?php }?>-->
											<h4 style="display: inline-block;">(</h4>
											<?php if($business1 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business1;?>">
											<?php }?>
											<?php if($business2 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business2;?>">
											<?php }?>
											<?php if($result_kType['account_type'] != ''){?>
											    <h4 class="transaction_num"> <?php echo $result_kType['account_type'];?>, </h4>
											<?php }?>
											<h4 class="transaction_num"><?php echo $result_transaction['ordered_num'];?>, </h4>
											<h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>
											<div class="referral_chat">
												<a href="chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $referred_names['first_id'];?>">
													<span class="count-icon">
														<i class="fa fa-2x fa-comment"></i>
															<span class="count unread_1"><?php echo $message_counts[0]?></span>
													</span>
												</a>
												<!--<div class="online first" style="display:inline-block;">
													<img src="/chat/assets/img/offline.png" style="width: 25px;">
												</div>-->
											</div>
										<?php }?>
									</div>
								</div>
							</div>
							<div class="row referral_item">
								<div class="col-md-12">
									<div class="col-md-10">
										<div class="referral_label">Second Level Referral:</div>
										<?php if($referred_names['second_id'] != ""){?>
											<?php 
													$sql_transaction = "
    												                SELECT count(a.id) ordered_num
                                                                    FROM (
                                                                    SELECT order_list.*
                                                                    FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                    WHERE users.referral_id = '".$referred_names['second_level']."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                    where user_roles = '2' and users.referral_id = '".$_SESSION['referral_id']."'";
                                                                    
												$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
												$sql_favorite = "SELECT COUNT(id) favorite_num
																FROM favorities
																WHERE favorite_id = '".$referred_names['second_id']."'";
												$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
											
											    $business1 = "";
											    $business2 = "";
											    for($i = 0; $i < count($nature_array); $i++){
											        if($referred_names['second_business1'] == $nature_array[$i])
											            $business1 = $nature_image[$i];
											        if($referred_names['second_business2'] == $nature_array[$i])
											            $business2 = $nature_image[$i];
											    }
											    $sql_k = "select account_type from users where referral_id = '".$referred_names['second_level']."' order by user_roles desc limit 1";
										        $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));
											?>
											<div class="referral_name">
											   <?php if($referred_names['second_role'] == '2'){?>
											        <a href="structure_merchant.php?merchant_id=<?php echo $referred_names['second_id'];?>"><?php echo $referred_names['second_name'];?></a>
											    <?php } else {?>
											        <?php echo $referred_names['second_name'];?>
											    <?php }?>
											</div>
											<!--<?php if($referred_names['second_role'] == '1'){?>
											    <img class="member_image" src="/img/member.jpg">
											<?php }?>-->
											<h4 style="display: inline-block;">(</h4>
											<?php if($business1 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business1;?>">
											<?php }?>
											<?php if($business2 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business2;?>">
											<?php }?>
											<?php if($result_kType['account_type'] != ''){?>
											    <h4 class="transaction_num"> <?php echo $result_kType['account_type'];?>, </h4>
											<?php }?>
											<h4 class="transaction_num"><?php echo $result_transaction['ordered_num'];?>, </h4>
											<h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>
											<div class="referral_chat">
												<a href="chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $referred_names['second_id'];?>">
													<span class="count-icon">
														<i class="fa fa-2x fa-comment"></i>
															<span class="count unread_2"></span>
													</span>
												</a>
											</div>
											<!--<div class="online second" style="display:inline-block;">
												<img src="/chat/assets/img/offline.png" style="width: 25px;">
											</div>-->
										<?php }?>
										
									</div>
								</div>
							</div>
							<div class="row referral_item">
								<div class="col-md-12">
									<div class="col-md-10">
										<div class="referral_label">Third Level Referral:</div>
										<?php if($referred_names['third_id'] != ""){
										?>
											<?php 
												$sql_transaction = "
    												                SELECT count(a.id) ordered_num
                                                                    FROM (
                                                                    SELECT order_list.*
                                                                    FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                    WHERE users.referral_id = '".$referred_names['third_level']."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                    where user_roles = '2' and users.referral_id = '".$_SESSION['referral_id']."'";
												$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
												$sql_favorite = "SELECT COUNT(id) favorite_num
																FROM favorities
																WHERE favorite_id = '".$referred_names['third_id']."'";
												$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
												
											    $business1 = "";
											    $business2 = "";
											    for($i = 0; $i < count($nature_array); $i++){
											        if($referred_names['third_business1'] == $nature_array[$i])
											            $business1 = $nature_image[$i];
											        if($referred_names['third_business2'] == $nature_array[$i])
											            $business2 = $nature_image[$i];
											    }
											    
											     $sql_k = "select account_type from users where referral_id = '".$referred_names['third_level']."' order by user_roles desc limit 1";
										         $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));
											?>
											<div class="referral_name">
											    <?php if($referred_names['third_role'] == '2'){?>
											        <a href="structure_merchant.php?merchant_id=<?php echo $referred_names['third_id'];?>"><?php echo $referred_names['third_name'];?></a>
											    <?php } else {?>
											        <?php echo $referred_names['third_name'];?>
											    <?php }?>
											</div>
											<!--<?php if($referred_names['third_role'] == '1'){?>
											    <img class="member_image" src="/img/member.jpg">
											<?php }?>-->
											<h4 style="display: inline-block;">(</h4>
											<?php if($business1 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business1;?>">
											<?php }?>
											<?php if($business2 != ""){ ?>
											    <img class="nature_image" src="/img/<?php echo $business2;?>">
											<?php }?>
											<?php if($result_kType['account_type'] != ''){?>
											    <h4 class="transaction_num"> <?php echo $result_kType['account_type'];?>, </h4>
											<?php }?>
										    <h4 class="transaction_num"> <?php echo $result_transaction['ordered_num'];?>, </h4>
											<h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>
											<div class="referral_chat">
											<a href="chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $referred_names['third_id'];?>">
													<span class="count-icon">
														<i class="fa fa-2x fa-comment"></i>
														<span class="count unread_3"></span>
													</span>
												</a>
											</div>
											<!--<div class="online third" style="display:inline-block;">
												<img src="/chat/assets/img/offline.png" style="width: 25px;">
											</div>-->
											
										<?php }?>
									</div>
								</div>
							</div>
						</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>First</th>
									<th>Second</th>
									<th>Third</th>
									<th>Month</th>
									<th>Total</th>
									<th>CF</th>
								</tr>
							</thead>
							<tbody>
							<?php if(count($array_refs) > 0) { ?>
								<?php foreach($array_refs as $value) { ?>
									<tr style="background-color: #e1e5ec">
									    <?php 
									        $first_id = $value['first'];
									        $merchant_id = $_SESSION['referral_id'];
									        $sql_merchant = "SELECT id, name, business1, business2, user_roles FROM users where referral_id='$first_id'  AND user_roles=2";
									        $result = mysqli_query($conn, $sql_merchant);
									        $num_rows = mysqli_num_rows($result);
									        if($num_rows == 0){
									            $sql_member = "SELECT id, name, business1, business2, user_roles FROM users where referral_id='$first_id' AND user_roles=1";
									            $result = mysqli_query($conn, $sql_member);
									        }
									        $data = mysqli_fetch_assoc($result);
									    ?>
									    <?php 
											$sql_transaction = "select count(a.id) ordered_num
                                                                FROM (
                                                                SELECT order_list.*
                                                                FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                WHERE users.referral_id = '".$first_id."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                where user_roles = '2' and users.referral_id = '".$merchant_id."'";
																
																/*WHERE user_id='".$data['id']."' and merchant_id = '".$_SESSION['login']."' AND STATUS='1'";*/
											$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
											$sql_favorite = "SELECT COUNT(id) favorite_num
															FROM favorities
															WHERE favorite_id = '".$data['id']."'";
											$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
											
										    $business1="";
										    $business2="";
										    for($i = 0; $i < count($nature_array); $i++){
										        if($data['business1'] == $nature_array[$i])
										            $business1 = $nature_image[$i];
										        if($data['business2'] == $nature_array[$i])
										            $business2 = $nature_image[$i];
										    }
										    
										    $sql_k = "select account_type from users where referral_id = '".$first_id."' order by user_roles desc limit 1";
										    $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));  
										?>
											
										<?php if($data['name'] != ""){?>
										    <td>
										        <?php if($data['user_roles'] == 2){?>
										            <a href="structure_merchant.php?merchant_id=<?php echo $data['id'];?>"><?php echo $data['name'];?></a>
										        <?php } else {?>
										            <?php echo $data['name'];?>
										        <?php }?>(
										        <?php if($business1 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business1;?>">
										        <?php }?>
										        <?php if($business2 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business2;?>">
										        <?php }?>
										            <?php if($result_kType['account_type'] != '') echo $result_kType['account_type'] . ', '?> 
										         <?php echo $result_transaction['ordered_num'];?>, <?php echo $result_favorite['favorite_num'];?> )
										    </td>
										<?php } else {?>
										    <td></td>
										<?php }?>
										<?php 
									        $second_id = $value['second'];
									        $sql_merchant = "SELECT id, name, business1, business2, user_roles FROM users where referral_id='$second_id' AND user_roles=2";
									        $result = mysqli_query($conn, $sql_merchant);
									        $num_rows = mysqli_num_rows($result);
									       
									        if($num_rows == 0){
									            $sql_member = "SELECT id, name, business1, business2, user_roles FROM users where referral_id != '' AND referral_id='$second_id' AND user_roles=1";
									            $result = mysqli_query($conn, $sql_member);
									        }
									        $data = mysqli_fetch_assoc($result);
									    ?>
									    <?php 
											$sql_transaction = "select count(a.id) ordered_num
                                                                FROM (
                                                                SELECT order_list.*
                                                                FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                WHERE users.referral_id = '".$second_id."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                where user_roles = '2' and users.referral_id = '".$merchant_id."'";

											$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
											$sql_favorite = "SELECT COUNT(id) favorite_num
															FROM favorities
															WHERE favorite_id = '".$data['id']."'";
											$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
										
										    $business1="";
										    $business2="";
										    for($i = 0; $i < count($nature_array); $i++){
										        if($data['business1'] == $nature_array[$i])
										            $business1 = $nature_image[$i];
										        if($data['business2'] == $nature_array[$i])
										            $business2 = $nature_image[$i];
										    }
										    
										    $sql_k = "select account_type from users where referral_id = '".$first_id."' order by user_roles desc limit 1";
										    $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));
										    
										?>
										<?php if($data['name'] != ""){?>
										    <td> <?php if($data['user_roles'] == 2){?>
										            <a href="structure_merchant.php?merchant_id=<?php echo $data['id'];?>"><?php echo $data['name'];?></a>
										        <?php } else {?>
										            <?php echo $data['name'];?>
										        <?php }?>(
										        <?php if($business1 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business1;?>">
										        <?php }?>
										        <?php if($business2 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business2;?>">
										        <?php }?>
										            <?php if($result_kType['account_type'] != '') echo $result_kType['account_type'] . ', '?>
										          <?php  echo $result_transaction['ordered_num'];?>, <?php echo $result_favorite['favorite_num'];?> )
										    </td>
										<?php } else {?>
										    <td></td>
										<?php }?>
										
										<?php 
									        $third_id = $value['third'];
									        $sql_merchant = "SELECT id, name, business1, business2, user_roles FROM users where referral_id='$third_id' AND user_roles=2";
									        $result = mysqli_query($conn, $sql_merchant);
									        $num_rows = mysqli_num_rows($result);
									        if($num_rows == 0){
									            $sql_member = "SELECT id, name, business1, business2 FROM users where referral_id != '' AND referral_id='$third_id' AND user_roles=1";
									            $result = mysqli_query($conn, $sql_member);
									        }
									        $data = mysqli_fetch_assoc($result);
									    ?>
										<?php 
											$sql_transaction = "select count(a.id) ordered_num
                                                                FROM (
                                                                SELECT order_list.*
                                                                FROM order_list INNER JOIN users on users.id = order_list.user_id
                                                                WHERE users.referral_id = '".$third_id."' and user_roles = '1' and status='1' ) a inner join users on users.id = a.merchant_id
                                                                where user_roles = '2' and users.referral_id = '".$merchant_id."'";

											$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
											$sql_favorite = "SELECT COUNT(id) favorite_num
															FROM favorities
															WHERE favorite_id = '".$data['id']."'";
											$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
										
										    $business1="";
										    $business2="";
										    for($i = 0; $i < count($nature_array); $i++){
										        if($data['business1'] == $nature_array[$i])
										            $business1 = $nature_image[$i];
										        if($data['business2'] == $nature_array[$i])
										            $business2 = $nature_image[$i];
										    }
										    
										    $sql_k = "select account_type from users where referral_id = '".$first_id."' order by user_roles desc limit 1";
										    $result_kType = mysqli_fetch_assoc(mysqli_query($conn,$sql_k));
										?>
										<?php if($data['name'] != ""){?> 
										    <td>
										        <?php if($data['user_roles'] == 2){?>
										            <a href="structure_merchant.php?merchant_id=<?php echo $data['id'];?>"><?php echo $data['name'];?></a>
										        <?php } else {?>
										            <?php echo $data['name'];?>
										        <?php }?>(
										        <?php if($business1 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business1;?>">
										        <?php }?>
										        <?php if($business2 != ""){?>
										            <img class="table_nature" src="/img/<?php echo $business2;?>">
										        <?php }?>
										            <?php if($result_kType['account_type'] != '') echo $result_kType['account_type'] . ', '?>
										         <?php echo $result_transaction['ordered_num'];?>, <?php echo $result_favorite['favorite_num'];?> )
										    </td>
										<?php } else {?>
										    <td></td>
										<?php }?>
										<td><?php echo round($value['month'], 2);?></td>
										<td><?php echo round($value['rate'], 2);?></td>
										<td><?php echo round($value['CF'], 2);?></td>
									</tr>
								<?php }?>
							<?php }?>
							</tbody>
							
						</table>
					</div>
					<div style="margin:0px auto;">
						<ul class="pagination">
						
						</ul>
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript">
		
		
		function copy_url(){
		    var dummy = document.createElement("input");

              document.body.appendChild(dummy);
            
              dummy.setAttribute("id", "dummy_id");
            
            var referral_id = '<?php echo $_SESSION['referral_id']; ?>';
            referral_id = referral_id.replace(/ /g, '%20');
              document.getElementById("dummy_id").value="<?php echo $site_url; ?>/signup_referral.php?invitation_id="+referral_id;
                
                
              dummy.select();
            
              document.execCommand("copy");
		    $(".copy-hint").css("display", "block");
		}
		
	</script>
</body>

</html>
