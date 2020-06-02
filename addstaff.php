<?php
session_start();

include("config.php");

$current_time = date('Y-m-d H:i:s');
if($_SESSION['login']=='')

{

    header('Location: '. $site_url .'/login.php');

    die;

}






?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">



<head>

    <style>

        .no-close .ui-dialog-titlebar-close {

            display: none;

        }

        .test_product{

            padding-right: 125px!important;

        }

        td.products_namess {

            text-transform: lowercase;

        }

        tr {

            border-bottom: 2px solid #efefef;

        }

        .well {

            min-height: 20px;

            padding: 19px;

            margin-bottom: 20px;

            background-color: #fff;

            border: 1px solid #e3e3e3;

            border-radius: 4px;

            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

        }

        td {

            border-right: 1px solid #efefef;

        }

        th {

            border-right: 1px solid #efefef;

        }

        tr.fdfd {

            border-bottom: 3px double #000;

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

        tr.red {

            color: red;

        }

        label.status {

            cursor: pointer;

        }

        td {

            border-right: 2px solid #efefef;

        }

        th {

            border-right: 2px solid #efefef;

        }

        .gr{

            color:green;

        }

        .or{

            color: orange !important;

        }

        .red.gr{

            color:green;

        }

        .product_name{

            width: 100%;

        }

        .total_order{

            font-weight:bold;

        }

        p.pop_upss {

            display: inline-block;

        }

        .location_head{

            width:200px;

        }

        .new_tablee {

            width: 200px!important;

            display: block;

            word-break: break-word;

        }

        td.test_productss {

            white-space: nowrap;

            /*width: 200px!important;*/

            display: block;

        }

        th.product_name.test_product {

            width: 200px!important;

        }



        @media only screen and (max-width: 600px) and (min-width: 300px){

            table.table.table-striped {

                white-space: unset!important;

            }





    </style>



    <?php include("includes1/head.php"); ?>

</head>


<body class="header-light sidebar-dark sidebar-expand pace-done">

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
                     
                    
					<?php
		
			
	if(isset($_REQUEST['submit']))
	{
			
 
	$name = addslashes($_POST['name']);
	$user_role = addslashes(5);
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	$countrycode = addslashes($_POST['countrycode']);
	$mobile_number = addslashes($_POST['mobile_number']);
	$account_type = addslashes($_POST['account_type']);
	$permissionapp=$_POST['permissionapp'];
	$permission_set=serialize($permissionapp);
	$order_print_setting = addslashes(isset($_POST['order_print_setting']) ? $_POST['order_print_setting'] : '');
	$order_print_live_setting = addslashes(isset($_POST['order_print_live_setting']) ? $_POST['order_print_live_setting'] : '');
	$cm =	$countrycode.''.$mobile_number;

	$error = "";
	
	if($name == "")
	{
		$error .= "Name cannot be Empty.<br>";
	}
	
	$already_exists1 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' && user_roles= '$user_role'"));

	 if($already_exists1 > 0)
	 {
		 $error .= "Mobile Number Already Exists.<br>";
	 }

	if(strlen($password) >= 15 || strlen($password) <= 7)
	{
		$error .= "Password must be between 8 and 15.<br>";
	}
	
	if($error == "")
	{
		$code = uniqid();

		$fund_pass = mt_rand(100000, 999999);

		$ref_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles != '3'"));
		
		if($ref_exists > 0 )
		{ 
		 $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		//~ $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		 $ref = $ref_id['referral_id'];

		}else{
			$ref = $name." ".$code;
		}
		
	
        if($account_type == ""){
            $k_date = "";
        } else {
            $k_date = date("Y-m-d");
        }
        
	
	     $date = "";
	    $code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(SUBSTR(merchant_code,5)) + 1 code FROM users "))['code'];
	    $merchant_code = "KOO_".str_pad($code,5,'0',STR_PAD_LEFT);
		$parentid=$_SESSION['login'];
	   
	    mysqli_query($conn, "INSERT INTO users SET order_print_setting='$order_print_setting',order_print_live_setting='$order_print_live_setting',name='$name',user_roles='$user_role',verification_code='',account_type='$account_type', k_date='$k_date', password='$password', joined='".time()."', isLocked='1',referral_id='$ref',referred_by='',security_answer= '',security_questions= '',fund_password='$fund_pass',email='$email',mobile_number='$cm', created_at='$date', merchant_code='$merchant_code',parentid='$parentid',permission_set='$permission_set'");
        
		$user_id = mysqli_insert_id($conn);
		$datetime = date('Y-m-d');
		if($account_type != ""){
		    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$datetime'");
		}
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];

	
		$subject = "Your Account | koofamilies";



					$email = addslashes($_POST['email']);
					
					
					
					
					$message = "
					<html>
					<head>
					<title>Verify Your Account | koofamilies</title>
					
					
					</head>
					<body>
					<h3>Your Login details : </h3>
					<p>Mobile Nuumber : $cm</p>
					<p>Password :  $password</p>
					</body>
					</html>
					";
					
					// Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					
					// More headers
					$headers .= 'From: <info@kooexchange.com>' . "\r\n";
					
					mail($email,$subject,$message,$headers);
					
					echo "<script>location.replace('staff.php');</script>";
		
		

	}
	else
	{
		echo "<strong>$error</strong>";
	
	}
		
	}	
	

	
	
	



?>


                    <div>

                        <h3>Add staff Account </h3>
						
						
						<form method="post" id="koosignup">
								<div class="login-top sign-top" style="width: 60%;

margin-left: 28%;">
									
									<input type="hidden" name="user_role" value="3">
									
									<div class="form-group input-has-value">
											
											<input type="text" style="width:50%;" class="name active form-control" placeholder="User Name Here" name="name" value="<?php isset($name) ? $name : ""; ?>" id="reg_name" />
									</div>
									
									<div class="form-group input-has-value">
									
									
									
											<select name="countrycode" id="countrycode" class="ct_ctycode form-control">
                                    	<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    	<option data-countryCode="CN" value="86">China (+86)</option>
                                    	<option data-countryCode="TH" value="66">Thailand (+66)</option>
                                    	<option data-countryCode="SG" value="65">Singapore (+65)</option>
                                    
                                    	<optgroup label="Other countries">
                                    	<option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                    		<option data-countryCode="AD" value="376">Andorra (+376)</option>
                                    		<option data-countryCode="AO" value="244">Angola (+244)</option>
                                    		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                    		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                    		<option data-countryCode="AR" value="54">Argentina (+54)</option>
                                    		<option data-countryCode="AM" value="374">Armenia (+374)</option>
                                    		<option data-countryCode="AW" value="297">Aruba (+297)</option>
                                    		<option data-countryCode="AU" value="61">Australia (+61)</option>
                                    		<option data-countryCode="AT" value="43">Austria (+43)</option>
                                    		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                    		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                    		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                    		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                    		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                    		<option data-countryCode="BY" value="375">Belarus (+375)</option>
                                    		<option data-countryCode="BE" value="32">Belgium (+32)</option>
                                    		<option data-countryCode="BZ" value="501">Belize (+501)</option>
                                    		<option data-countryCode="BJ" value="229">Benin (+229)</option>
                                    		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                    		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                    		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                    		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                    		<option data-countryCode="BW" value="267">Botswana (+267)</option>
                                    		<option data-countryCode="BR" value="55">Brazil (+55)</option>
                                    		<option data-countryCode="BN" value="673">Brunei (+673)</option>
                                    		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                    		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                    		<option data-countryCode="BI" value="257">Burundi (+257)</option>
                                    		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                    		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                    		<option data-countryCode="CA" value="1">Canada (+1)</option>
                                    		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                    		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                    		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                    		<option data-countryCode="CL" value="56">Chile (+56)</option>
                                    	<!--	<option data-countryCode="CN" value="86">China (+86)</option> -->
                                    		<option data-countryCode="CO" value="57">Colombia (+57)</option>
                                    		<option data-countryCode="KM" value="269">Comoros (+269)</option>
                                    		<option data-countryCode="CG" value="242">Congo (+242)</option>
                                    		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                    		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                    		<option data-countryCode="HR" value="385">Croatia (+385)</option>
                                    		<option data-countryCode="CU" value="53">Cuba (+53)</option>
                                    		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                    		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                    		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                    		<option data-countryCode="DK" value="45">Denmark (+45)</option>
                                    		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                    		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                    		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                    		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                    		<option data-countryCode="EG" value="20">Egypt (+20)</option>
                                    		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                    		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                    		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                    		<option data-countryCode="EE" value="372">Estonia (+372)</option>
                                    		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                    		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                    		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                    		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                    		<option data-countryCode="FI" value="358">Finland (+358)</option>
                                    		<option data-countryCode="FR" value="33">France (+33)</option>
                                    		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                    		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                    		<option data-countryCode="GA" value="241">Gabon (+241)</option>
                                    		<option data-countryCode="GM" value="220">Gambia (+220)</option>
                                    		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                    		<option data-countryCode="DE" value="49">Germany (+49)</option>
                                    		<option data-countryCode="GH" value="233">Ghana (+233)</option>
                                    		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                    		<option data-countryCode="GR" value="30">Greece (+30)</option>
                                    		<option data-countryCode="GL" value="299">Greenland (+299)</option>
                                    		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                    		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                    		<option data-countryCode="GU" value="671">Guam (+671)</option>
                                    		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                    		<option data-countryCode="GN" value="224">Guinea (+224)</option>
                                    		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                    		<option data-countryCode="GY" value="592">Guyana (+592)</option>
                                    		<option data-countryCode="HT" value="509">Haiti (+509)</option>
                                    		<option data-countryCode="HN" value="504">Honduras (+504)</option>
                                    		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                    		<option data-countryCode="HU" value="36">Hungary (+36)</option>
                                    		<option data-countryCode="IS" value="354">Iceland (+354)</option>
                                    		<option data-countryCode="IN" value="91">India (+91)</option>
                                    		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                    		<option data-countryCode="IR" value="98">Iran (+98)</option>
                                    		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                    		<option data-countryCode="IE" value="353">Ireland (+353)</option>
                                    		<option data-countryCode="IL" value="972">Israel (+972)</option>
                                    		<option data-countryCode="IT" value="39">Italy (+39)</option>
                                    		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                    		<option data-countryCode="JP" value="81">Japan (+81)</option>
                                    		<option data-countryCode="JO" value="962">Jordan (+962)</option>
                                    		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                    		<option data-countryCode="KE" value="254">Kenya (+254)</option>
                                    		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                    		<option data-countryCode="KP" value="850">Korea North (+850)</option>
                                    		<option data-countryCode="KR" value="82">Korea South (+82)</option>
                                    		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                    		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                    		<option data-countryCode="LA" value="856">Laos (+856)</option>
                                    		<option data-countryCode="LV" value="371">Latvia (+371)</option>
                                    		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                    		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                    		<option data-countryCode="LR" value="231">Liberia (+231)</option>
                                    		<option data-countryCode="LY" value="218">Libya (+218)</option>
                                    		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                    		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                    		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                    		<option data-countryCode="MO" value="853">Macao (+853)</option>
                                    		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                    		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                    		<option data-countryCode="MW" value="265">Malawi (+265)</option>
                                    		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    		<option data-countryCode="MV" value="960">Maldives (+960)</option>
                                    		<option data-countryCode="ML" value="223">Mali (+223)</option>
                                    		<option data-countryCode="MT" value="356">Malta (+356)</option>
                                    		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                    		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                    		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                    		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                    		<option data-countryCode="MX" value="52">Mexico (+52)</option>
                                    		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                    		<option data-countryCode="MD" value="373">Moldova (+373)</option>
                                    		<option data-countryCode="MC" value="377">Monaco (+377)</option>
                                    		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                    		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                    		<option data-countryCode="MA" value="212">Morocco (+212)</option>
                                    		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                    		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                    		<option data-countryCode="NA" value="264">Namibia (+264)</option>
                                    		<option data-countryCode="NR" value="674">Nauru (+674)</option>
                                    		<option data-countryCode="NP" value="977">Nepal (+977)</option>
                                    		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                    		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                    		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                    		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                    		<option data-countryCode="NE" value="227">Niger (+227)</option>
                                    		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                    		<option data-countryCode="NU" value="683">Niue (+683)</option>
                                    		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                    		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                    		<option data-countryCode="NO" value="47">Norway (+47)</option>
                                    		<option data-countryCode="OM" value="968">Oman (+968)</option>
                                    		<option data-countryCode="PW" value="680">Palau (+680)</option>
                                    		<option data-countryCode="PA" value="507">Panama (+507)</option>
                                    		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                    		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                    		<option data-countryCode="PE" value="51">Peru (+51)</option>
                                    		<option data-countryCode="PH" value="63">Philippines (+63)</option>
                                    		<option data-countryCode="PL" value="48">Poland (+48)</option>
                                    		<option data-countryCode="PT" value="351">Portugal (+351)</option>
                                    		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                    		<option data-countryCode="QA" value="974">Qatar (+974)</option>
                                    		<option data-countryCode="RE" value="262">Reunion (+262)</option>
                                    		<option data-countryCode="RO" value="40">Romania (+40)</option>
                                    		<option data-countryCode="RU" value="8">Russia (+7)</option>
                                    		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                    		<option data-countryCode="SM" value="378">San Marino (+378)</option>
                                    		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                    		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                    		<option data-countryCode="SN" value="221">Senegal (+221)</option>
                                    		<option data-countryCode="CS" value="381">Serbia (+381)</option>
                                    		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                    		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                    		<!-- <option data-countryCode="SG" value="65">Singapore (+65)</option> -->
                                    		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                    		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                    		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                    		<option data-countryCode="SO" value="252">Somalia (+252)</option>
                                    		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                    		<option data-countryCode="ES" value="34">Spain (+34)</option>
                                    		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                    		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                    		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                    		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                    		<option data-countryCode="SD" value="249">Sudan (+249)</option>
                                    		<option data-countryCode="SR" value="597">Suriname (+597)</option>
                                    		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                    		<option data-countryCode="SE" value="46">Sweden (+46)</option>
                                    		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                    		<option data-countryCode="SI" value="963">Syria (+963)</option>
                                    		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                    		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                    	<!--	<option data-countryCode="TH" value="66">Thailand (+66)</option> -->
                                    		<option data-countryCode="TG" value="228">Togo (+228)</option>
                                    		<option data-countryCode="TO" value="676">Tonga (+676)</option>
                                    		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                    		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                    		<option data-countryCode="TR" value="90">Turkey (+90)</option>
                                    		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                    		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                    		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                    		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                    		<option data-countryCode="UG" value="256">Uganda (+256)</option>
                                    	<option data-countryCode="GB" value="44">UK (+44)</option> 
                                    		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                    		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                    		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                    		<option data-countryCode="US" value="1">USA (+1)</option>
                                    		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                    		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                    		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                    		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                    		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                    		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                    		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                    		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                    		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                    		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                    		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                    		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                    	</optgroup>
                                    </select>  
                                     <input type="text" class="mobile_number form-control" placeholder="<?php echo $language["telephone_number"];?> " name="mobile_number" id="reg_mobnum" /> 
									</div>
								
										
										<div class="form-group input-has-value">
									
											  <input type="text"  class="form-control"  placeholder="Email Id" name="email"  />
                                        
									</div>
									
									
										<div class="form-group input-has-value">
									
											  <select name="account_type " class="form-control">
        								     <option value="">Non K1 / K2</option>
	                                         <option value="K1">K1</option>
	                                         <option value="K2">K2</option>
	                                         <option value="K1 &amp; K2">K1 &amp; K2</option>
	                                    </select>
                                        
									</div>
									
									
										
										
								
									<div class="form-group input-has-value">
									
											  <input type="password" class="password form-control" id="Password"  placeholder="Password" name="password"  />
                                         <input type="password" name="cpassword" id="cpassword"  placeholder="Confirm Password" class="form-control" >
									</div>
									
									
									<div class="form-group">
										<label>Order Print Setting</label><br>
										<input class="order_print_setting" type="checkbox" name="order_print_setting" >Auto Print Invoice<br>
										<input class="order_print_setting" type="checkbox" name="order_print_live_setting" >Auto Print Live order<br>
										
									</div>   
									
									<div class="form-group input-has-value">
									<div class="row">
									  <div class="col-md-6">
									    <label>Dashboard: </label>  <input type="checkbox" name="permissionapp[]" value="8"><br>
										<label>Add Product: </label>  <input type="checkbox" name="permissionapp[]" value="9"><br>
											<label>View Product: </label>  <input type="checkbox" name="permissionapp[]" value="10"><br>
											<label>Add Category: </label>  <input type="checkbox" name="permissionapp[]" value="11"><br>
											<label>Add Master: </label>  <input type="checkbox" name="permissionapp[]" value="12"><br>
											<label>View Category: </label>  <input type="checkbox" name="permissionapp[]" value="13"><br>
											<label>Subscription: </label>  <input type="checkbox" name="permissionapp[]" value="14"><br>
											<label>About Us: </label>  <input type="checkbox" name="permissionapp[]" value="15"><br>
										<label>Report: </label>  <input type="checkbox" name="permissionapp[]" value="16"><br>
										  <label>Shift Report: </label>  <input type="checkbox" name="permissionapp[]" value="17"><br>
										
									  </div>
									   <div class="col-md-6">
									   <label>Order List : </label>  <input type="checkbox" name="permissionapp[]" value="1"><br>
										<label>My Community : </label> <input type="checkbox" name="permissionapp[]" value="2"><br>
										<label>Referral List : </label>  <input type="checkbox" name="permissionapp[]" value="3"><br>
										<label>K Type : </label> <input type="checkbox" name="permissionapp[]" value="4"><br>
										<label>Profile : </label> <input type="checkbox" name="permissionapp[]" value="5"><br>
										<label>Inverstor Relations : </label> <input type="checkbox" name="permissionapp[]" value="6"><br>
										<label>Contact Us : </label> <input type="checkbox" name="permissionapp[]" value="7"><br>
										<label>Cash System : </label> <input type="checkbox" name="permissionapp[]" value="18"><br>
										<label>Product Order: </label> <input type="checkbox" name="permissionapp[]" value="19"><br>
										<label>Pos Product Order: </label> <input type="checkbox" name="permissionapp[]" value="20"><br>
										<label>Invetory: </label> <input type="checkbox" name="permissionapp[]" value="21"><br>
										<label>Stock Report: </label> <input type="checkbox" name="permissionapp[]" value="22"><br>
										<label>Supplier: </label> <input type="checkbox" name="permissionapp[]" value="23"><br>
										
									  </div>
									</div>
									
										
									
									
									</div>
                                    
										<div class="form-group input-has-value">
											<input type="submit" value="Submit" class="btn btn-primary" style="padding:14px;" name="submit">
										
										</div> 
                                      
                                       
                                       
        								
        								
        							
        								
                                           
                                        
                                    </div>
								</form>

                      	

                    </div>

                    <?php

                    $dt = new DateTime();

                    $today =  $dt->format('Y-m-d');

                    ?>

                   


                    

                    <div>

                        

                        

                        

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



