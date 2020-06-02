<?php 
include("config.php"); 

if(isset($_SESSION['login'])){
$user_id = $_SESSION['login'];
} else {
$user_id = "";
}
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ORDER BY name ASC ");
$user_mobile =  isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'] : '';

$error = "";
if(isset($_GET['error_type'])){
$type = $_GET['error_type'];
if($type == 2)
$error= "The merchant you are trying to find was already introduced by another member.";
if($type == 1)
$error= "The merchant's phone number is incorrect.";
}
   ?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
      <?php include("includes1/head.php"); ?>
      <style>
         .sidebar {
         background: #eceff1;
         }
         div#app {
         margin-bottom: 20px;
         }
         .form-control {
         display: block;
         }
         table.table {
         width: 400px;
         }
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
         .ct_ctycode {     
         margin-bottom: 12px;  
         }
         a.dropdownlivalue {
         padding: 10px;
         }
         @media (min-width: 370px) and (max-width:380px) {
         button.btn.btn-block.btn-primary.testts.merchant_nam {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.tele_num {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.scan_code {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.fav_list {
         font-size: 11px!important;
         }
         button.btn.btn-block.btn-primary.testts.search_shopss {
         font-size: 11px!important;
         }
         }
         @media (min-width: 328px) and (max-width:628px) {
         .navbar-nav li a
         {
         padding: 0px;
         }
         .ripple {
         padding: 3px, 10px;
         }
         div#merchant_name {
         padding: 0;
         margin-left: 2px;
         }
         div#tele_number {
         padding: 0;
         margin-left: 2px;
         }
         div#scan_qrcode {
         padding: 0;
         margin-left: 0px;
         }
         .col-md-12.test_test {
         padding: 0;
         margin: 0;
         }
         .col-md-12.test_test {
         margin-bottom: 5px!important;
         margin: 0;
         }
         button.btn.btn-block.btn-primary.testts.merchant_nam {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.tele_num {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.scan_code {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.fav_list {
         font-size: 12px;
         }
         button.btn.btn-block.btn-primary.testts.search_shopss {
         font-size: 12px;
         }
         
         }
         .col-md-12.test_test {
         display: flex;
         }
         .col-md-3.test_qwertys {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left: 12px;
         }
         .col-md-3.test_qwertys1 {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left: 12px;
         }
         .col-md-3.test_qwertys2 {
         color: #fff;
         background-color: #fb9678;
         border-color: #fb9678;
         -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
         box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075); 
         margin-left: 12px;
         }
         button.btn.btn-block.btn-primary.testts:hover {
         border-color: #f99678;
         background-color: #f99678;
         }
         .col-md-12.test_test.dollar {
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
               <div class="col-md-12">
                  <h3> Find Merchant By:</h3>
                  <p style="margin-bottom:5px; font-size:18px; color: #FF0000"><?php echo $error;?></p>
                  <br>
                  <!-- new design -->
                  <div class="col-md-12 test_test dollar">
                     <div class="col-md-3 test_qwertys" id="scan_qrcode">
                        <button class="btn btn-block btn-primary testts scan_code"> Scan QR code </button>
                     </div>
                     <div class="col-md-3 test_qwertys1" id="tele_number">
                        <button class="btn btn-block btn-primary testts tele_num"> Telephone Number </button>
                     </div>
                     <div class="col-md-3 test_qwertys2" id="merchant_name">
                        <button class="btn btn-block btn-primary testts merchant_nam"> Merchant Name </button>
                     </div>
                  </div>
                  <div class="col-md-12 test_test">
                     <div class="col-md-3 test_qwertys">
						 	<a href="#favourate_list" class="btn btn-block testts fav_list"> Favorite List
<!--
                        <button > Favorite List</button>
-->
                         </a>
                     </div>
                     <div class="col-md-3 test_qwertys1">
						 	<a href="#favourate_list" class="btn btn-block testts fav_list"> Search Near by shops
                         </a>
<!--
                        <button class="btn btn-block btn-primary testts search_shopss"> Search Near by shops</button>
-->
                     </div>
                  </div>
                  <!-- end new design-->
                  
                  <!-- scn design-->
                  
                   <!-- third part-->
                 <!-- third part-->
                                   <!-- qr scanning code new--->
                   <div class="well col-md-12 testing_method">
                   <h4>Scanning QR code</h4>         
                  <div id="app">
                      <div class="sidebar">
                        <section class="cameras">
                          <h2>Cameras</h2>
                          <ul>
                            <li v-if="cameras.length === 0" class="empty">No cameras found</li>
                            <li v-for="camera in cameras">
<!--
                              <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
-->
                              <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)" class="active">
                                <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
                              </span>
                            </li>
                          </ul>
                        </section>
                        <section class="scans">
                          <h2>Scan Results</h2>
                          <ul v-if="scans.length === 0">
                            <li class="empty">No scans yet</li>
                          </ul>
                          <transition-group name="scans" tag="ul">
                		   <li id="stl_scan" v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
                          </transition-group>
                <!--
                          <button class="btn btn-block btn-primary" id="text_testing" class="stl_scan"> View </button>
                -->
                        </section>
                      
                      </div>
                      <div class="preview-container">
                        <video id="preview"></video>
                      </div>
                    </div>
  </div>
                  <!-- end qr scanning code new-->            
                  
                  <!-- end qr scanning code new-->    
                  <!--end scan design -->
                  
                  
                  
                  <!-- total design-->
                  <!--second scan code---->
                  <div class="well col-md-8 test_upload" id="test_upload">
                     <h4>Upload QR code</h4>
                     <form method="post">
                        <div class="input-group">
                           <input name="qr_code" type="text" size=16 placeholder="QR Code" class="qrcode-text form-control" id="qr_cursor">
                           <label class="qrcode-text-btn">
                           <input type="file" accept="image/*" onchange="openQRCamera(this);" tabindex=-1></label> 
                        </div>
                        <br>
                        <button type="submit" name="GetData" class="btn btn-primary btn-block" onclick="window.location.href = 'structure_merchant.php';">Submit</button>
                     </form>
                     <div class="common">
                        <?php
                           if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['GetData'])){
                           
                           	$qr_code = $_POST['qr_code']; 
                           	 $user_mobiless = "SELECT * FROM users WHERE mobile_number = '$qr_code' and user_roles='2'";
                           	$result = $conn->query($user_mobiless);
                           
                           	if ($result->num_rows > 0) {
                           		// output data of each row
                           		while($row = $result->fetch_assoc()) {
                           	?>
                        <h3 class="text_mobile">Mobile Number: <strong><?php echo $row['mobile_number'];?> </strong></h3>
                            <h3 class="text_mobile">Name: <strong><a style="color:#09caab;" href="<?php echo $site_url;?>/structure_merchant.php?sid=<?php echo $row['mobile_number'];?>">
							<?php echo $row['name']; ?></a></h3>
					
                        <?php
                           }
                           } else {
                           echo "<h1>No results</h1>";
                           }
                           
                           //$conn->close();
                           }
                           ?>    
                     </div>
                  </div>
                  <!-- secon scan code--->
                  <!-- mobile number--->
                  <div class="well col-md-8 test_mobile" id="test_mobile">
                     <h4>Mobile Number </h4>
                     <form action="structure_merchant.php" method="post">
                        <?php 
                           $product = isset($id) && $id !='undefined' ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0")) : '';
                           ?>
                        <!---new----->
                        <select name="countrycode" id="countrycode" class="ct_ctycode">
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
                              <option data-countryCode="RU" value="7">Russia (+7)</option>
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
                        <input type="text" name="merchant" id="merchant" class="ct_ctycode" required />						
                        <button class="btn btn-block btn-primary"> View </button>
                     </form>
                  </div>
                  <!-- end mobile number-->
                  <!-- merchant name-->
                  <div class="well col-md-8 mer_nam" id="mer_name">
                     <h4>Merchant Name</h4>
                     <form action="structure_merchant.php" method="post">
                        <?php 
                           // $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0" ));
                           ?>
                        <div>
                           <input type="text"  id="txtname" autocomplete="off" name="merchant_id" class="form-control" placeholder=" Search By company name"> 
                           <br> 
                           <ul class="dropdown-menu txtname" role="menu" aria-labelledby="dropdownMenu"  id="Dropdown_name">
                           </ul>
                        </div>
                        <!---new----->
                        <button class="btn btn-block btn-primary"> View </button>
                     </form>
                  </div>
                  <!-- end merchant name -->
                  <br>
                  <!-- favourate list-->
                  <div class="well col-md-8 favorite_list" id="favourate_list">
                     <h3> Favorite List</h3>
                     <div class="form-group">
                        <select name= "business" class="form-control business_type" user_id="<?php echo $_SESSION['login']?>" >
                           <option>Foods and Beverage, such as restaurants, healthy foods, franchise, etc</option>
                           <option>Motor Vehicle, such as car wash, repair, towing, etc</option>
                           <option>Hardware, such as household, building, renovation to end users</option>
                           <option>Grocery Shop such as bread, fish, etc retails shops</option>
                           <option>Clothes such as T-shirt, Pants, Bra, socks,etc</option>
                           <option>Business to Business (B2B) including all kinds of businesses</option>
                        </select>
                     </div>
                     <!--<?php 
                        $result = mysqli_query($conn,
                             "SELECT users.name, user_id, favorite_id
                             FROM favorities INNER JOIN users ON favorities.favorite_id = users.id
                             WHERE user_id=".$_SESSION['login']." AND (users.business1 = 'Foods and Beverage, such as restaurants, healthy foods, franchise, etc' OR users.business2 = 'Foods and Beverage, such as restaurants, healthy foods, franchise, etc')"
                            );
                        ?>-->
					<table class="table table table-striped kType_table">
					<thead>
					<tr>
					<th>No</th>
					<th>Name</th>
					<th>Distance</th>
					</tr>
					</thead>
					<tbody class="favorite_tr">
					</tbody>
					</table>
					<button type="button" class='btn btn-default nearby_restaurant_btn' user_id="<?php echo $user_id;?>">Click here to seach nearby shops.</button>
					<div id="map" style="height: 400px; display:none;"></div>
					<table class="table" id="nearby_restaurant">
                     <div id="map" style="height: 400px; display:none;"></div>
                     <table class="table" id="nearby_restaurant">
                        <thead>
                           <tr>
                              <th>id</th>
                              <th>Name</th>
                              <th>Distance</th>
                           </tr>
                        </thead>
                        <tbody class="nearby_tr">
                        </tbody>
                     </table>
                  </div>
                  <!-- end fanourate list-->
                  <!-- end total design -->                         
         </main>
         <!-- /.widget-body badge -->
         </div>
         <!-- /.widget-bg -->
         <!--search by name-->
         </div>
         <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
   </body>
</html>
<style>
   select.ct_ctycode.text_name {
   width: 100%;
   }
   a:not([href]):not([tabindex]):hover {
   color: #6a6a6a;
   text-decoration: none;
   }
</style>
<script type="text/javascript">
   var $ = jQuery; 
   $(function() 
   {
    $( "#txtname" ).autocomplete({
    source: 'auto_complete.php'
    });
    
   });
   
   //~ var TitleAttr = $("#stl_scan").attr('title');
   //~ alert(TitleAttr);
   
   $(document).ready(function () {
       
       $("#mr_name").keyup(function () {
           $.ajax({
               type: "POST",
               url: "auto_complete.php",
               data: {
                   keyword: $("#mr_name").val()
               },
               dataType: "json",
               success: function (data) {
                   if (data.length > 0) {
                       $('#Dropdown_name').empty();
                       $('#mr_name').attr("data-toggle", "dropdown");
                       $('#Dropdown_name').dropdown('toggle');
                   }
                   else if (data.length == 0) {
                       $('#mr_name').attr("data-toggle", "");
                   }
                   $.each(data, function (key,value) {
   
                       if (data.length >= 0)
                           $('#Dropdown_name').append('<li role="presentation" ><a class="dropdownlivalue">' + value + '</a></li>');
                   });
               }
           });
       });
       
       $('ul.txtname').on('click', 'li a', function () {
           $('#mr_name').val($(this).text());
           $("#Dropdown_name").css("display", "none");
   
       });
       
       var latitude = 0;
       var longitude = 0;
        navigator.geolocation.getCurrentPosition(function(position) {
            console.log("current location");
           latitude = position.coords.latitude;
           longitude = position.coords.longitude;
           getFavorite("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));
           //getNearbyRestaurant("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));
        });
        
        var map = new google.maps.Map(document.getElementById('map'), {
             center: { lat: latitude, lng: longitude },
             zoom: 15
       }); 
        $(".business_type").change(function(e){
           var data = {method: "getFavoriteByBusiness", type:e.target.value, user_id: $(this).attr('user_id')};
           
           getFavorite(e.target.value, $(this).attr('user_id'));
           
           $("#nearby_restaurant tbody").html("");
       });
       
       function getNearbyRestaurant(business, id){
           var data = {method: "getNearbyRestaurants", type:business, user_id: id};
           $.ajax({
               url:"functions.php",
               type:"post",
               data:data, 
               dataType: 'json',
               success:function(data){
                   var html = "";
                   $("#nearby_restaurant tbody").html(html);
                   for (var i = 0; i < data.length; i++){
                       var distance = getDistanceNearby(latitude, longitude, data[i]['latitude'], data[i]['longitude'], 'K');
                       data[i].distance = distance;
                   }
                   data.sort(function(a, b){
                       return a['distance'] - b['distance'];
                   });
                   for (var i = 0; i < data.length; i++){
                       html += "<tr>";
                       html += "<td>"+(i+1)+"</td>";
                       html += "<td><a href=structure_merchant.php?merchant_id="+data[i]['id']+">"+data[i]['name']+" ( "+data[i]['order_num']+", "+ data[i]['favorite_num']+")</a></td>";
                       
                       
                       html += "<td>"+data[i].distance+" km</td>";
                       html += "</tr>";
                   }
                   $("#nearby_restaurant tbody").html(html);
               }
           });
       } 
       
       function getFavorite(business, id){
           var data = {method: "getFavoriteByBusiness", type:business, user_id: id};
           $.ajax({
               url:"functions.php",
               type:"post",
               data:data, 
               dataType: 'json',
               success:function(data){
                   console.log(data);
                   var html = "";
                   $(".favorite_tr").html(html);
                   for (var i = 0; i < data.length; i++){
                       var distance = getDistance(latitude, longitude, data[i]['latitude'], data[i]['longitude'], 'K');
                       console.log(distance);
                       data[i].distance = distance;
                   }
                   data.sort(function(a, b){
                       return a['distance'] - b['distance'];
                   });
                   for (var i = 0; i < data.length; i++){
                       console.log(data);
                       html += "<tr>";
                       html += "<td>"+(i+1)+"</td>";
                       html += "<td><a href=structure_merchant.php?favorite_id="+data[i]['favorite_id']+">"+data[i]['name']+" ( "+data[i]['order_num']+", "+ data[i]['favorite_num']+")</a></td>";
                       
                       html += "<td>"+data[i].distance+" km</td>";
                       html += "</tr>";
                   }
                   $(".favorite_tr").html(html);
                   //getNearbyRestaurant(business, $(this).attr('user_id'));
               }
           });
       } 
       
       function getDistance(lat1, lon1, lat2, lon2, unit) {
       	var radlat1 = Math.PI * lat1/180
       	var radlat2 = Math.PI * lat2/180
       	var theta = lon1-lon2
       	var radtheta = Math.PI * theta/180
       	var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
       	if (dist > 1) {
       		dist = 1;
       	}
       	dist = Math.acos(dist)
       	dist = dist * 180/Math.PI
       	dist = dist * 60 * 1.1515
       	if (unit=="K") { dist = dist * 1.609344 }
       	if (unit=="N") { dist = dist * 0.8684 }
       	return dist.toFixed(1);
       }
       
       function getDistanceNearby(lat1, lon1, lat2, lon2, unit) {
       	var radlat1 = Math.PI * lat1/180
       	var radlat2 = Math.PI * lat2/180
       	var theta = lon1-lon2
       	var radtheta = Math.PI * theta/180
       	var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
       	if (dist > 1) {
       		dist = 1;
       	}
       	dist = Math.acos(dist)
       	dist = dist * 180/Math.PI
       	dist = dist * 60 * 1.1515
       	if (unit=="K") { dist = dist * 1.609344 }
       	if (unit=="N") { dist = dist * 0.8684 }
       	return dist.toFixed(1);
       }
       
       $(".nearby_restaurant_btn").click(function(e){
           e.preventDefault();
           
           getNearbyRestaurant($(".business_type").val(), $(this).attr('user_id')); 
           
       });
       
   });
</script>
<script src="js/ff2be4c29f.js"></script>
<style>
   .qrcode-text {
   padding-right:1.7em;
   margin-right:0
   }
   .qrcode-text-btn {
   background: url(<?php echo $site_url; ?>/img/1499401426qr_icon.svg) 50% 50% no-repeat;
   height: 37px;
   width: 30px;
   margin-left: -32px;
   cursor: pointer;
   z-index: 999;
   }
   .qrcode-text-btn > input[type=file] {
   position:absolute; 
   width:1px; 
   height:1px; 
   opacity:0;
   cursor: pointer;
   }
   @media only screen and (max-width: 760px) and (min-width: 360px)  {
   div#app {
   width: 290px!important;
       height: 90%;
   }
   #app {
   display: block!important;
   }
   div#main-content {
   width: 355px;
   }
   .test {
   display: block!important;
   float: left;
   }
   table.table {
   width: 280px;
   }
   .form-control {
   display: block;
   width: 260px!important;
   }
   }
</style>
<script src="js/qr_packed.js"></script>
<script>
   function openQRCamera(node) {
     var reader = new FileReader();
     reader.onload = function() {
   	node.value = "";
   	qrcode.callback = function(res) {
   	  if(res instanceof Error) {
   		alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
   	  } else {
   		node.parentNode.previousElementSibling.value = res;
   	  }
   	};
   	qrcode.decode(reader.result);
     };
     reader.readAsDataURL(node.files[0]);
   }
</script>
<link rel="stylesheet" href="html5qrcodereader-master/css/reset.css">
        <link rel="stylesheet" href="html5qrcodereader-master/css/styles.css">
<!-- adding new---->
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="<?php echo $site_url;?>/instascan-master/docs/style.css">
    <script type="text/javascript" src="js/adapter.min.js"></script>
    <script type="text/javascript" src="js/vue.min.js"></script>
    <script type="text/javascript" src="js/instascan.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script> 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&libraries=places" async defer></script> 
<style>
   div#app {
   margin-bottom:20px;
   }
   .mejs__controls {
   display: none;
   }
   #app {
   background: #263238;
   display: flex;
   align-items: stretch;
   justify-content: stretch;
   height: 85%;
   }
   .text_mobile{
   font-size: 18px;
   }
   .test {
   display: flex;
   float: left;
   }
   h4#scan_qrcode {
   cursor: pointer;
   }

   button.btn.btn-block.btn-primary.testts.scan_code {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
   button.btn.btn-block.btn-primary.testts.tele_num {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
   button.btn.btn-block.btn-primary.testts.merchant_nam {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys1 {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
.col-md-3.test_qwertys2 {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
button.btn.btn-block.btn-primary.testts.search_shopss {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}
button.btn.btn-block.btn-primary.testts.fav_list {
    color: #000;
    background-color: #34caab;
    border-color: #34caab;
}

.preview-container {
    width: 35%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 19px;
    left: -150px;
    border-top: 3px solid #fff;
    border-left: 3px solid #fff;
}
.preview-container:after {
   
   
       display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 19px;
    right: -150px;
    border-bottom: 3px solid #fff;
    border-right: 3px solid #fff;
    
}
.mejs__mediaelement:before {
   display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: -4px;
    right: -4px;
    border-top: 3px solid #fff;
    border-right: 3px solid #fff;
}
.mejs__mediaelement:after {
   
     display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -22px;
    left: -2px;
    border-bottom: 3px solid #fff;
    border-left: 3px solid #fff;
    
}
@media (min-width: 760px) and (max-width:800px) {
.preview-container {
    width: 53%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -55px;
    left: -3px;
}
}
@media (min-width: 328px) and (max-width:750px) {
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 10px;
    left: -110px;
    border-top: 3px solid #fff;
    border-left: 3px solid #fff;
}
.preview-container {
    width: 85%!important;
    margin: 0 auto;
    margin-top: 12px;
    height: 230px;
}
.preview-container:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 16px;
    right: -108px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -3px;
    left: -3px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 15px;
    left: -110px;
}
.mejs__mediaelement:before {
    display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: -4px;
    right: -2px;
}


}


.mejs__container {
    max-width: 95%;
   
}.preview-container:before




</style>
<!-- newly added qr code-->
<script>
	
	$(document).ready(function() {
	var x = setInterval(function() {
			var test_qwertyss= $('.scans-enter-to').attr('title');
		if(test_qwertyss != null ){
			 //~ alert(test_qwertyss);
        //~ window.location.href = "https://koofamilies.com/structure_merchant.php?sid="+test_qwertyss; 
         window.location.href = "<?php echo $site_url;?>/structure_merchant.php?sid="+test_qwertyss;
		}
		else {
		//~ alert('please scan the code');
			}
		
				 }, 1000); 
	});
</script>
<script>
   document.getElementById('scan_qrcode').onclick = function() {
       document.getElementById('qr_cursor').focus();
   };
   document.getElementById('tele_number').onclick = function() {
       document.getElementById('merchant').focus();
   };
   document.getElementById('merchant_name').onclick = function() {
       document.getElementById('txtname').focus();
   };
</script>

 <style>
a.btn.btn-block.testts.fav_list {
    color: black;
}  
.preview-container {
    width: 35%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 79px;
    left: -94px;
    z-index:999;
    border-top: 3px solid red;
    border-left: 3px solid red;
}
.preview-container:after {
   
   
       display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 110px;
    right: -83px;
    border-bottom: 3px solid red;
    border-right: 3px solid red;
    
}
.mejs__mediaelement:before {
   display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 20px;
    right: -3px;
    border-top: 3px solid red;
    border-right: 3px solid red;
}
.mejs__mediaelement:after {
   
     display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: 26px;
    left: -3px;
    border-bottom: 3px solid red;
    border-left: 3px solid red;
    
}
		  
		  
.sidebar {
    background: #eceff1;
}
div#app {
    margin-bottom: 20px;
}

.form-control {
    display: block;
    width: 350px;
}
table.table {
    width: 400px;
}

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
         .ct_ctycode {     
         margin-bottom: 12px;  
         }
    
 a.dropdownlivalue {
    padding: 10px;
}

@media (min-width: 328px) and (max-width:628px) {
.navbar-nav li a
{
	padding: 0px;
}
.ripple {
	
	padding: 3px, 10px;
}

}
@media (min-width: 760px) and (max-width:800px) {
.preview-container {
    width: 53%!important;
    margin: 0 auto;
    margin-top: 12px;
}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    bottom: -55px;
    left: -3px;
}
}
@media (min-width: 328px) and (max-width:750px) {
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
    top: 155px;
    left: -50px;
    
}

.preview-container {
    width: 85%!important;
    margin: 0 auto;
    margin-top: 12px;
    height:350px;
        

}
.preview-container:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
       bottom: 77px !important;
    right: -40px;
        z-index:999;


}
.mejs__mediaelement:after {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
        top: -77px !important;
    left: 55px !important;
    z-index: 999999 !important;

}
.preview-container:before {
    display: block;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
   top: 153px;
    left: -40px;
        z-index:999;


}
.mejs__mediaelement:before {
    display: block;
    float: right;
    content: "";
    width: 23px;
    height: 23px;
    position: relative;
      top: 130px;
    right: 55px;	
        z-index:999;

}
#preview_from_mejs {
   height:240px;
}


.mejs__container {
    max-width: 85%;
   
}
.mejs__overlay-play
{
	
    height: 190px !important;
}
}


.mejs__container {
    max-width: 95%;
   
}
.mejs__mediaelement {
    width: 90% !important;
    float: right;
    text-align: center;
        margin-left: 12px;
}

    
      </style>
