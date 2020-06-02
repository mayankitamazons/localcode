<?php 
include("config.php");


$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$k_history = mysqli_query($conn, "SELECT * FROM k_type WHERE user_id='".$_SESSION['login']."' ORDER BY date");

$referred_by = $bank_data['referred_by'];
if($bank_data['referred_by'] == "")
    $referred_by = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles = '1' and  referral_id='".$_SESSION['referral_id']."'"))['referred_by'];
    
$user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'];

if(isset($_POST['submit']))
{

	$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, created_at, referred_by, k_date, account_type FROM users WHERE id='".$_SESSION['login']."'"));
	$user_id = $ref_result['id'];
	if(($ref_result['created_at'] != null) || ($ref_result['created_at'] != "")){
		$created_date = strtotime($ref_result['created_at']);
		$num_date = ceil((time() - $created_date)/60/60/24);
	} else {
		$date = date('y-m-d');
		$num_date = 183;
	}
	if($ref_result['referred_by'] == ""){
	    $num_date = 183;
		$date = "";
	}
    
    
    $type_expire = ceil((time() - strtotime($ref_result['k_date']))/60/60/24);
    
	$error = "";
	$expired_flag = false;
    $expired_type_flag = false;
    
	$realname = addslashes($_POST['realname']);
	$company = addslashes($_POST['company']);
	$register = addslashes($_POST['register']);
	$address = addslashes($_POST['address']);
	$gst = addslashes($_POST['gst']);
	$sst = addslashes($_POST['sst']);
	$facsimile = addslashes($_POST['facsimile']);
	$business1 = addslashes($_POST['business1']);
	$business2 = addslashes($_POST['business2']);
	$name_card = addslashes($_POST['name_card']);
	$card_number = addslashes($_POST['card_number']);
	$expiry_date = addslashes($_POST['expiry_date']);
	$cvv = addslashes($_POST['cvv']);
	$handphone_number = addslashes($_POST['handphone_number']);
	$bankname = addslashes($_POST['bankName']);
	$name_accoundholder = addslashes($_POST['name_accoundholder']);
	$ac_num = addslashes($_POST['ac_num']);
	$charge = addslashes($_POST['charge']);
	$nric_number = addslashes($_POST['nric_number']);
	$address_person = addslashes($_POST['address_person']);
	$hand_phone = addslashes($_POST['hand_phone']);
	$google_map = addslashes($_POST['google_map']);
	$latitude =  addslashes($_POST['latitude']);
	$longitude =  addslashes($_POST['longitude']);
	$doc_copy = addslashes($_POST['doc_copy']);
	$company_doc = addslashes($_POST['company_doc']);
	$number_lock = addslashes($_POST['number_lock']);
	$k_lock = addslashes($_POST['k_lock']);
	$account_type = addslashes($_POST['account_type']);
	$merchant_address = addslashes($_POST['merchant_address']);
	
	$merchant_code = $bank_data['merchant_code'];
	
	if($merchant_code == ""){
	    $code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(SUBSTR(merchant_code,5)) + 1 code FROM users "))['code'];
	    $merchant_code = "KOO_".str_pad($code,5,'0',STR_PAD_LEFT);
	}
	
	if($number_lock =="on") $number_lock = '1';
	else $number_lock = "0";
	
	if($k_lock == "on") $k_lock = '1';
	else $k_lock = "0";
	
	if($num_date < 183){
		$expired_flag = false;
		$referred_by = $ref_result['referred_by'];
		$date = $ref_result['created_at'];
	} else {
		$expired_flag = true;
		$referral_id = addslashes($_POST['referral_id']);
		$existing_referral = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE referral_id='".$referral_id."'"));
		if(count($existing_referral) > 0){
		    
		    $referred_by = $referral_id;
		    $date = date('y-m-d');
		} else {
		    $referred_by = $ref_result['referred_by'];
		    $date = $ref_result['created_at'];
		    
		}
	}
	
	if($type_expire < 2){
	    $expired_type_flag = false;
	    $account_type = $ref_result['account_type'];
	    $k_date = $ref_result['k_date'];
	} else {
	    $expired_type_flag = true;
	    $account_type = addslashes($_POST['account_type']);
	    $k_date = date('Y-m-d');
	    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$k_date'");
	}

	$flag = false;
	
	
	if($_FILES['doc_copy']['name'] != "")
	{
		$filename = $_FILES['doc_copy']['name'];
		move_uploaded_file($_FILES['doc_copy']['tmp_name'], "documents/".$filename);
	}
	else
	{
		$filename = $bank_data['doc_copy'];
	}
	
	if($_FILES['company_doc']['name'] != "")
	{
		$filenamess = $_FILES['company_doc']['name'];
		move_uploaded_file($_FILES['company_doc']['tmp_name'], "documents/profile_images/".$filenamess);
	}
	else
	{
		$filenamess = $bank_data['company_doc'];
	}
	
	if($flag == false)
	{
		$test_test = mysqli_query($conn, "UPDATE users SET merchant_code='$merchant_code', merchant_url='$merchant_address', name='$realname',latitude='$latitude', longitude='$longitude', company='$company',register='$register',address='$address',gst='$gst', sst='$sst', facsimile_number='$facsimile',referred_by='$referred_by', business1='$business1', business2='$business2', name_card='$name_card',card_number='$card_number',expiry_date='$expiry_date',cvv='$cvv',bank_name='$bankname',name_accoundholder='$name_accoundholder',bank_ac_num='$ac_num',charge='$charge',nric_number='$nric_number',address_person='$address_person',hand_phone='$hand_phone',google_map='$google_map',doc_copy='$filename',company_doc='$filenamess',number_lock='$number_lock', handphone_number='$handphone_number', created_at='$date', account_type='$account_type', k_date='$k_date', k_lock='$k_lock' WHERE id='".$_SESSION['login']."'");
		$error .= "Successfully Updated profile Details.<br>";
		if($expired_flag == false){
			$error .= "You can change the referral id after ".(183 - $num_date)." days. <br />"; 
		}
		if($expired_type_flag == false){
		    $error .= "You can change the K1/K2 tomorrow.";
		}
	}
} 
else
{
	$realname = $bank_data['name'];
	$company = $bank_data['company'];
	$register = $bank_data['register'];
	$address = $bank_data['address'];
	$gst = $bank_data['gst'];
	$sst = $bank_data['sst'];
	$facsimile = $bank_data['facsimile_number'];
	$business1 = $bank_data['business1'];
	$business2 = $bank_data['business2'];
	$bank_name = $bank_data['bank_name'];
	$charge = $bank_data['charge'];
	$nric_number = $bank_data['nric_number'];
	$address_person = $bank_data['address_person'];
	$hand_phone = $bank_data['hand_phone'];
	$google_map = $bank_data['google_map'];
	$name_card = $bank_data['name_card'];
	$card_number = $bank_data['card_number'];
	$expiry_date = $bank_data['expiry_date'];
	$cvv = $bank_data['cvv'];
	$bank_ac_num = $bank_data['bank_ac_num'];
	$name_accoundholder = $bank_data['name_accoundholder'];
	$profile_pic = $bank_data['doc_copy'];
	$company_doc = $bank_data['company_doc'];
	$handphone_number = $bank_data['handphone_number'];
	//$referral_id = $bank_data['referred_by'];
	$number_lock = $bank_data['number_lock'];
	$google_map = $bank_data['google_map'];
	$latitude= $bank_data['latitude'];
	$longitude = $bank_data['longitude'];
    $account_type = $bank_data['account_type'];
    $k_lock = $bank_data['k_lock'];
    $merchant_code = $bank_data['merchant_code'];
    $merchant_address = $bank_data['merchant_url'];
}
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
	.account_kType{
	    margin-bottom: 10px;
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

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
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<div class="container" > 
					    <div class="row">
					        <div class="well col-md-6">
							<form method="post" onsubmit="return change_amount()" enctype="multipart/form-data" id="profile_account">
								<div class="panel price panel-red">
									<h2>Update Profile Details</h2>
									<br><br>
									<div class="form-group">
										<label>User Name</label>
										<input type="text" name="realname" class="form-control" value="<?php if(isset($realname)){ echo $realname; }?>">
									</div>
									<div class="form-group">
										<label>Name Of Company</label>
										<input type="text" name="company" class="form-control" value="<?php if(isset($company)){ echo $company; }?>">
									</div>
									<div class="form-group">
										<label>Merchant Code<br></label>
										<input type="text" name="merchant_code" disabled class="form-control" value="<?php if(isset($merchant_code)){ echo $merchant_code; }?>">
									</div>
									<div class="form-group">
										<label>Registration Number of the Company</label>
										<input type="text" name="register" class="form-control" value="<?php if(isset($register)){ echo $register; }?>">
									</div>
									<div class="form-group">
										<label>Address</label>
										<input type="text" name="address" class="form-control" value="<?php if(isset($address)){ echo $address; }?>">
									</div>
									<div class="form-group">
										<label>GST Register Number</label>
										<input type="text" name="gst" class="form-control" value="<?php if(isset($gst)){ echo $gst; }?>" >
									</div>
									<div class="form-group">
										<label>SST Number</label><br>
										<input type="text" name="sst" class="form-control" value="<?php if(isset($sst)){ echo $sst; }?>" >
										
									</div>
									<div class="form-group">
										<label>Telephone Number<br>
										<span class="tele_num"><?php echo $bank_data['mobile_number']; ?></span> 
										</label>
									</div>  
									<div class="form-group">
										<label>Handphone Number<br>
										<input type="text" name="handphone_number" class="form-control" value="<?php if(isset($handphone_number)){ echo $handphone_number; }?>" >
									</div>
									<div class="form-group input-has-value">
										<label>Referral ID<br>
										<input type="text" name="referral_id" class="form-control" value="<?php if(isset($referred_by)){ echo $referred_by; }?>" >
									</div>
									<div class="form-group">
										<label>Facsimile Number</label>
										<input type="text" name="facsimile" class="form-control" value="<?php if(isset($facsimile)){ echo $facsimile; }?>" >
									</div>
									<div class="form-group"> 
										<label>Nature Of Business 1</label><br>
										<select name= "business1" class="form-control" >
											<option value="">Select a desired option</option>
											<option <?=$business1 === "Foods and Beverage, such as restaurants, healthy foods, franchise, etc"? 'selected="selected"' : ''; ?>>Foods and Beverage, such as restaurants, healthy foods, franchise, etc</option>
											<option <?=$business1 === "Motor Vehicle, such as car wash, repair, towing, etc"? 'selected="selected"' : ''; ?>>Motor Vehicle, such as car wash, repair, towing, etc</option>
											<option <?=$business1 === "Hardware, such as household, building, renovation to end users"? 'selected="selected"' : ''; ?>>Hardware, such as household, building, renovation to end users</option>
											<option <?=$business1 === "Grocery Shop such as bread, fish, etc retails shops"? 'selected="selected"' : ''; ?>> Grocery Shop such as bread, fish, etc retails shops</option>
											<option <?=$business1 === "Clothes such as T-shirt, Pants, Bra, socks,etc"? 'selected="selected"' : ''; ?>> Clothes such as T-shirt, Pants, Bra, socks,etc</option>
											<option <?=$business1 === "Business to Business (B2B) including all kinds of businesses"? 'selected="selected"' : ''; ?>> Business to Business (B2B) including all kinds of businesses</option>
										</select>
										
									</div>
									<div class="form-group"> 
										<label>Nature Of Business 2</label><br>
										<select name= "business2" class="form-control" >
											<option value="">Select a desired option</option>
											<option <?=$business2 === "Foods and Beverage, such as restaurants, healthy foods, franchise, etc"? 'selected="selected"' : ''; ?>>Foods and Beverage, such as restaurants, healthy foods, franchise, etc</option>
											<option <?=$business2 === "Motor Vehicle, such as car wash, repair, towing, etc"? 'selected="selected"' : ''; ?>>Motor Vehicle, such as car wash, repair, towing, etc</option>
											<option <?=$business2 === "Hardware, such as household, building, renovation to end users"? 'selected="selected"' : ''; ?>>Hardware, such as household, building, renovation to end users</option>
											<option <?=$business2 === "Grocery Shop such as bread, fish, etc retails shops"? 'selected="selected"' : ''; ?>> Grocery Shop such as bread, fish, etc retails shops</option>
											<option <?=$business2 === "Clothes such as T-shirt, Pants, Bra, socks,etc"? 'selected="selected"' : ''; ?>> Clothes such as T-shirt, Pants, Bra, socks,etc</option>
											<option <?=$business2 === "Business to Business (B2B) including all kinds of businesses"? 'selected="selected"' : ''; ?>> Business to Business (B2B) including all kinds of businesses</option>
										</select>
									</div>
									<!-- second part--->
									<div class="form-group">
										<label>Bank Details</label><br>
										<input class="new_creditcard" type="checkbox" name="credit_card" value="<?php if(isset($sst)){ echo $sst; }?>" >Add a New Credit Card<br>
										<input type="checkbox" class="bank_account" name="bank_account" value="<?php if(isset($sst)){ echo $sst; }?>" >Add a New Bank Account<br>
									</div>
									<div class="form-group">
										<label>Merchant Address</label><br>
										<input type="text" name="merchant_address" class="form-control" value="<?php if(isset($merchant_address)){ echo $merchant_address; }?>" >
									</div>
									
									<div class="form-group">
										<label>Hide Number</label><br>
										<input class="hide_number" type="checkbox" name="number_lock" <?php if($number_lock == '1') echo "checked='checked'";?>" ><br>
									</div>
									
									<div class="form-group">
										<label>K1/K2 Type</label><br>
										<select class='account_kType' name="account_type" style="">
										    <option <?php if($account_type == '') echo 'selected'; ?> value="">Non K1/K2</option>
										    <option <?php if($account_type == 'K1') echo 'selected'; ?> value="K1">K1</option>
										    <option <?php if($account_type == 'K2') echo 'selected'; ?> value="K2">K2</option>
										    <option <?php if($account_type == 'K1 & K2') echo 'selected'; ?> value="K1 & K2">K1 & K2</option>
										</select>
										<br />
										<input type="checkbox" class="k_lock" name="k_lock" <?php if($k_lock == '1') echo "checked='checked'";?>" >
										I only allow above members to place orders. 
										<br>
									</div>
									<div class="form-group">
									    <label>Date of changing - Discount to K1/K2</label><br>
									    <table class="table table-striped" id="kType_table">
									        <thead>
                                                <tr>
                                                    <th>Date</th>           
                                                    <th>Type</th>                    
                                                    <th>Discount</th>    
                                              </tr>
                                           </thead>
                                           <tbody>
                                               <?php while ($row=mysqli_fetch_assoc($k_history)){ ?>
                                                <tr>
                                                    <td><?php echo substr($row['date'], 0, 10); ?></td>
                                                    <td><?php echo $row['type']?></td>
                                                    <?php if(strlen($row['type']) > 2){?>
                                                        <td>4%</td>
                                                    <?php } else if(strlen($row['type']) == 2){?>
                                                        <td>2%</td>
                                                    <?php } else if(strlen($row['type']) < 2){ ?>
                                                        <td>0%</td>
                                                    <?php }?>
                                                </tr>
                                               <?php }?>
                                           </tbody>
									    </table>
									</div>
									<!-- credit card details--->
									<div class="credit_card">
    									<div class="form-group">
    										<label>NAME ON CARD</label>
    										<input type="text" name="name_card" class="form-control" value="<?php if(isset($name_card)){ echo $name_card; }?>" >
    									</div>
    									<div class="form-group">
    										<label>CARD NUMBER</label>
    										<input type="text" name="card_number" class="form-control" value="<?php if(isset($card_number)){ echo $card_number; }?>" >
    									</div>
    									
    									<div class="form-group">
    										<label>EXPIRATION DATE</label>
    										<input type="date" name="expiry_date" class="form-control"  value="<?php if(isset($expiry_date)){ echo $expiry_date; }?>" >
    									</div>
    									<div class="form-group">
    										<label>CVV</label>
    										<input type="text" name="cvv" class="form-control" value="<?php if(isset($cvv)){ echo $cvv; }?>" >
    									</div>
									
									</div>
									<!-- end credit card detils--->
									
									<!-- Bank branch details -->
									
									<div class="branch_details">
									<div class="form-group">
										<label>Bank Name</label>
										<input type="text" name="bankName" class="form-control" value="<?php if(isset($bank_name)){ echo $bank_name; }?>">
									</div>
									<div class="form-group">
										<label>Name of Account Holder</label>
										<input type="text" name="name_accoundholder" class="form-control" value="<?php if(isset($name_accoundholder)){ echo $name_accoundholder; }?>">
									</div>
<!--
									<div class="form-group">
										<label>Branch Name</label>
										<input type="text" name="branchName" class="form-control" value="<?php if(isset($branchName)){ echo $branchName; }?>" required>
									</div>
-->
<!--
									<div class="form-group">
										<label>IFSC Code</label>
										<input type="text" name="ifsc_code" class="form-control" value="<?php if(isset($ifsc_code)){ echo $ifsc_code; }?>" required>
									</div>
-->
									<div class="form-group">
										<label>Account Number</label>
										<input type="text" name="ac_num" class="form-control" value="<?php if(isset($bank_ac_num)){ echo $bank_ac_num; }?>">
									</div>
									
									</div>
									
									<!-- end bank branch details -->
									
									<!-- end second part--->
									
									<!-- third part--->
									
									
									<div class="form-group"> 
        							<label>Person in charge of the company</label>
        							<input type="text" class="form-control" name="charge" value="<?php if(isset($charge)){ echo $charge; }?>">
        						</div>  
        						<div class="form-group">
        							<label>NRIC number of the person in charge</label>
        							<input type="text" class="form-control" name="nric_number" value="<?php if(isset($nric_number)){ echo $nric_number; }?>">
        						</div>  
        						<div class="form-group">
        							<label>Address of the person in charge</label>
        							<input type="text" class="form-control" name="address_person"  value="<?php if(isset($address_person)){ echo $address_person; }?>">
        						</div>
        						<div class="form-group">								
        							<label>Handphone number of the person in charge</label>
									<input type="text" class="form-control" name="hand_phone" value="<?php if(isset($hand_phone)){ echo $hand_phone; }?>">									
								</div>
        						
									
									<!-- end third part--->
									
									<!--fourth part--->
									
									
									<div class="form-group">
										<label>Upload of the picture of NRIC of the person</label><br>
										<input type="file" name="doc_copy" <?php if(isset($profile_pic) && $profile_pic == ""){ 
											//echo "required"; 
											} ?>>
										<?php
										if(isset($profile_pic) && $profile_pic != "")
										{
										?>
										<a href="documents/<?php echo $profile_pic; ?>" target="_blank"><?php echo $profile_pic; ?></a>
										<?php
										}
										?>
									</div>
										<div class="form-group">
										<label>Upload of the SSM/Form 24/49 of the Company</label><br>
										<input type="file" name="company_doc" <?php if(isset($company_doc) && $company_doc == ""){
											 //echo "required"; 
											 } 
											 ?>>
										<?php
										if(isset($company_doc) && $company_doc != "")
										{
										?>
										<a href="documents/profile_images/<?php echo $company_doc; ?>" target="_blank"><?php echo $company_doc; ?></a>
										<?php
										}
										?>
									</div>
									<div class="form-group">							
            							<label>Address of the company as per Google Map API</label>
            							<input type="hidden" name="latitude" class="latitude" value="<?php if(isset($latitude)){ echo $latitude;}?>">
            							<input type="hidden" name="longitude" class="longitude" value="<?php if(isset($longitude)){ echo $longitude;}?>">
    									<input type="text" class="form-control" name="google_map" id="mapSearch" value="<?php if(isset($google_map)){ echo $google_map; }?>">										
								        <div id="map" style="height: 400px;"></div>
								    </div>
        						    
									<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
								</div>
							</form>
						</div>
						<div class="col-md-6 well">
						    <form method="post">
        						<h3></h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_password">
        						</div>
        						<div class="form-group">
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_password">
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_password">
        						</div>
        						
        						<input type="submit" value="Change Password" name="submit_pass" class="btn btn-block btn-primary">
        					</form>
        					<!--fund password-->
        				    <form method="post">
        						<h3>Change Fund Password</h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_fundpassword" required>
        						</div>
        						<div class="form-group"> 
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_fundpassword" required>
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_fundpassword" required>
        						</div>
        						<div class="form-group">								
        							<label>Security Questions</label>
        							<br>
										<select name= "questions">
    										<option value="">Select a desired question</option>
    										<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
    										<option value="What's the name of your best friend?">What's the name of your best friend?</option>
    										<option value="What is your favorite model of car?">What is your favorite model of car?</option>
    										<option value="Where would you like to visit again?">Where would you like to visit again?</option>
										</select>   
								</div>
        						
        						<input type="submit" value="Change Fund Password" name="submit_fundpass" class="btn btn-block btn-primary">
        					</form>		
					    </div>
					</div>
				</div>
                <h3 class="text_qrcode">QR Code</h3>
        		<br>
        		<div class="col-md-3"></div>
        		<div class="well col-md-6">
					<div style="margin:10px">
						<img src="qrcode/qrcode.php?text=<?php echo $user_mobile; ?>" style="width:100%" class="text_qrcode">
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>

</html>
<style>
select {
    height: 30px;
}
</style>
 <script type="text/javascript">
   /* $(document).ready(function(){
        var theForm = $("#profile_account");
        theForm.validate({
            'email':{
                email: true,

            remote:{

                url: "validatorAJAX.php",

                type: "post"

            }
        },


        },

        messages:{
        'email':{
        email: "Please enter a valid email address!",
        remote: "The email is already in use by another user!"
        },



        }

    });*/

    </script>   
<style>
  .tele_num{
	font-weight: 400;
    display: block;
    width: 345%;
    padding: 0.5625rem 1.2em;
    font-size: 0.875rem;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-image: none;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.25rem;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
h3.text_qrcode {
    width: 100%;
}
.credit_card{
 display:none;
}
.branch_details{
display:none;
}
div#multiSelectCombo {
    width: 450px!important;
}
</style>
<script type="text/javascript">

        $(document).ready(function () {
            $('.new_creditcard').click(function () {
            $('.credit_card').css('display', 'block');
            });
            
			$('.bank_account').click(function () {
             $('.branch_details').css('display', 'block');
             }); 
                
                
          });
    </script>



   <!-- Ignite UI Required Combined CSS Files -->
    <link href="http://cdn-na.infragistics.com/igniteui/2018.1/latest/css/themes/infragistics/infragistics.theme.css" rel="stylesheet" />
    <link href="http://cdn-na.infragistics.com/igniteui/2018.1/latest/css/structure/infragistics.css" rel="stylesheet" />

    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

    <!-- Ignite UI Required Combined JavaScript Files -->
    <script src="http://cdn-na.infragistics.com/igniteui/2018.1/latest/js/infragistics.core.js"></script>
    <script src="http://cdn-na.infragistics.com/igniteui/2018.1/latest/js/infragistics.lob.js"></script>
    <script>

        var colors = [
            { Name: "Foods and Beverage, such as restaurants, healthy foods,  franchise, etc" },
            { Name: "Motor Vehicle, such as car wash, repair, towing, etc" },
            { Name: "Hardware, such as  household, building, renovation to end users" },
            { Name: "Grocery Shop such as bread, fish, etc retails shops" },
            { Name: "Clothes such as T-shirt, Pants, Bra, socks,etc" },
            { Name: "Business to Business (B2B) including all kinds of businesses" },            
        ];
        
        
        $(function () {
            var latitude = 0;
            var longitude = 0;
             navigator.geolocation.getCurrentPosition(function(position) {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;
                var myLatLng = {lat: latitude, lng: longitude};
                var map = new google.maps.Map(document.getElementById('map'), {
                      center: { lat: latitude, lng: longitude },
                      zoom: 20
                }); 
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    draggable: true
                });
                google.maps.event.addListener(marker, 'position_changed', function(){
                    var lat = marker.getPosition().lat();
                    var lng = marker.getPosition().lng();
                    
                    var latlng = new google.maps.LatLng(lat, lng);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    
                    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            console.log(results[1].formatted_address);
                            $("#mapSearch").val(results[1].formatted_address);
                            /*if (results[1]) {
                                alert("Location: " + results[1].formatted_address);
                            }*/
                        }
                    });
                    
                    $(".latitude").val(lat);
                    $(".longitude").val(lng);
                    
                });
             });
             
             
            var searchBox = new google.maps.places.SearchBox(document.getElementById('mapSearch'));
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                places.forEach(function(place) {
                    var latitude = place.geometry.location.lat();
                    var longitude = place.geometry.location.lng();
                    $(".latitude").val(latitude);
                    $(".longitude").val(longitude);
                    var address = $("#mapSearch").val();
                });
            });

        });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&libraries=places" async defer></script>