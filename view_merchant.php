<?php
include("config.php");
include_once('php/Section.php');
// include_once('php/SectionTable.php');
// Start of Hire's work
// Load merchant's product with QR
$p_status='';
if(!empty($_GET['status'])){
	$p_status=$_GET['status'];
}
if(!empty($_SESSION['o'])){
	$o_done=$_SESSION['o'];
	$_SESSION['o']='';
}

if(!empty($_GET['sid'])){
    $sid = $_GET['sid'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$sid' and user_roles='2'"));
    $merchant_name = $product['name'];
    $_SESSION['invitation_id'] = $product['referral_id'];
    $_SESSION['merchant_id'] = $product['id'];
    $_SESSION['address_person'] = $product['address'] ;
    $_SESSION['latitude'] = $product['latitude'] ; 
    $_SESSION['longitude'] = $product['longitude'] ;
    $_SESSION['IsVIP'] = $product['IsVIP'] ;
    $_SESSION['mm_id']= $product['id'];
    $_SESSION['mm_id']= $product['id'];
     
} 
// End of Hire's work
$sectionsObj = new Section($conn);
// $sectionTablesObj = new SectionTable($conn);
$sectionsFilter = [
  'user_id' => isset($_SESSION['merchant_id']) ? $_SESSION['merchant_id'] : null,
  'status' => true
];
$sectionsList = $sectionsObj->getList($sectionsFilter);

$sectionTableFilter = [
  'status' => true
];

if($sectionsList) {
  // $sectionTableFilter['section_id'] = (array_keys($sectionsList))[0];
}
// $sectionTablesList = $sectionTablesObj->getList($sectionTableFilter);


$bank_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
$check_number=$bank_data['mobile_number'];

$check_number=str_replace("60","",$check_number);
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
<?php  $login_user_id=$_SESSION['login'];
  if(isset($login_user_id))
  {
	    $urecord = isset($login_user_id) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,user_roles,setup_shop,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$login_user_id."'")) : '';
    	$balance_inr=$urecord['balance_inr'];	
		if($balance_inr=='')
			$balance_inr=0;
  }
 ?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

  
<head>
    <?php include("includes1/head.php"); ?>
	<script src="js/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="css/jquery.fancybox.min.css">
	<script src="js/jquery.fancybox.min.js"></script>
  <style>
    body.noscroll{
      overflow: hidden !important;
      position: fixed;
    }
    .other_products {
    display: flex;
}
    .create_date
    {
      float: right;
    }

    .comment_box {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-top: 15px;
    box-shadow: 0 0 5px 0px;
  }
    .submit_button
    {
      width:25% !important;
    }
    .comment{
      width:90%;
    }
  .well
  {
  
    min-height: 20px;
    background-color: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .well {
    width: 100% !important;
    min-height: 20px;
    background-color: transparent!important;
    border: 0px solid #e3e3e3!important;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}
  .well form{
      min-height: 280px;
  }
  .pro_name
  {
   text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
    height: 60px;
    }
    .about_mer {
    width: 100%;
}

 .input-controls {
      margin-top: 10px;
      border: 1px solid transparent;
      border-radius: 2px 0 0 2px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      height: 32px;
      outline: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    #searchInput {
      background-color: #fff;
      font-family: Roboto;
      font-size: 15px;
      font-weight: 300;
      margin-left: 12px;
      padding: 0 11px 0 13px;
      text-overflow: ellipsis;
      width: 50%;
    }
    #searchInput:focus {
      border-color: #4d90fe;
    }
    input.quatity {
    width: 90px;
}
.common_quant {
    display: flex;
}
p.quantity {
    margin-top: 7px;
}
.order_product{
    margin-top: 15px;
    margin-left: 10px;
    font-size: 20px;
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 10px;
}
.comm_prd{
    border: 1px #000000 solid;
    padding-left: 15px;
    margin-bottom: 10px;
}
.mBt10{
    margin-bottom: 10px;
}
@media only screen and (max-width: 767px) and (min-width: 300px)  {
    .new_grid{
      grid-template-columns: 1fr 1fr !important;
    }

    .text_add_cart {
        background: #003A66;
        width: 109px;
        text-align: center;
        padding: 10px;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        /* margin-right: 8px; */
        border-radius: 8px;
        margin-left: -10px;
    }
   .master_category_filter{
        font-size: 1.2rem;
        line-height: 0.8rem;
        margin-bottom: 5px !important;
        padding: 0.5rem 0.5rem;
    }
    .category_filter{
        font-size: 1.2rem;
        line-height:0.8rem;
        margin-bottom: 5px !important;
        padding: 0.4rem 0.9em;
    }
    .order_product{
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 18px;
        padding-left: 5px;
        padding-right: 5px;
    }
    .oth_pr{
        margin-top: 20px !important;
    }

}
.nature_image {
   width: 40px;
   height: 40px;
}


@media only screen and (max-width: 600px) and (min-width: 300px)  {

  .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }

    .oth_pr{
        margin-top: 26px!important;
    padding: 6px!important;
    }
}

@media only screen and (max-width: 500px) and (min-width: 400px)  {
     .well{
        padding-top: 0px !important;
     }
     .pro_name {
         font-size: 18px;
         margin: 10px 0px 0px;
         height: 35px;
     }
     .set_calss.input-has-value {
        width: 180px;
     }
     
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
  .new_grid{
    grid-template-columns: 1fr 1fr !important;
  }
     .well{
        padding-top: 0px !important;
     }
h4.head_oth {
    font-size: 20px;
}
     .pro_name {
        text-align: center;
        font-size: 14px;
        overflow: hidden;
        /* white-space: nowrap; */
        height: auto;
        /* width: 100px; */
        line-height: 15px;
     }
     .text_add_cart {
         margin: 5px 0px;
         padding: 7px;
     }
     .common_quant {
        display: block;
     }
     .text_add_cart {
         background: #003A66;
         width: 109px;
         text-align: center;
         padding: 10px;
         color: #fff;
         text-transform: uppercase;
         font-weight: 600;
         cursor: pointer;
         /* margin-right: 8px; */
         border-radius: 8px;
         margin-left: -10px;
     }
     .mBt10{
         margin-bottom: 2px;
     }
     .nature_image {
       width: 25px;
       height: 25px;
    }
    .starting-bracket{
        margin-top: 0.8rem;
    }
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }
   .text_add_cart {
        padding: 6px;
   }

   .row#main-content {
        margin-right: 0px;
        margin-left: 0px;

    }
    .oth_pr{
  height: 40px;
  }
}
@media only screen and (max-width: 1050px) and (min-width: 992px)  {
   .text_add_cart{width: 100px}
   .text_add_cart {
       width: 125px;
       margin: 0 auto;
   }
   p.quantity {
        margin-left: 35px;
   }
   .common_quant {
        display: block;
   }
   input.quatity {
        width: 130px;
   }
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
   .set_calss.input-has-value {
        width: 173px;
   }
   .about_uss {
        width: 165px;
   }
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
   input.btn.btn-block.btn-primary.submit_button {
        width: 100%!important;
   }
   p.test_testing {
        margin: 2px;
   }
   .text_add_cart {
        margin: 5px auto;
   }
   input.quatity {
        width: 118px;
   }
   .well {
        min-height: 20px;
        padding: 0px 0 0;
   }
   .common_quant {
        display: block;
   }
   .set_calss.input-has-value {
        width: 160px;

   }
   .grid.row {
        margin-left: 18px;
   }
   p {
        margin-bottom: 0;
   }
}

@media only screen and (max-width: 800px) and (min-width: 750px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   .common_quant {
        display: block;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 800px) and (min-width: 650px)  {
   .common_quant {
        display: block;
   }
   .text_add_cart {
        width: 142px;
   }
}

/* Edited by Sumit */
@media (min-width:768px) and (max-width:1150px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  label {
      font-weight: 600;
      width: 100%;
  }
  .fjhj br {
      display: none;
  }
  .master_category_filter{
      background-color: #545c73;
      border-color: #4a5368;
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
  }
  .master_category_filter:focus, .master_category_filter.focus {
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
  }
  .master_category_filter:hover {
      color: #fff;
      background-color: #4a5368;
      border-color: #545c73;
  }
}
@media (min-width:200px) and (max-width:767px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .fjhj br {
      display: none;
  }
}

.fjhj br {
    display: none;
}
label {
    font-weight: 600;
    width: 100%;
}
/* Edited by Sumit  */
.introduce-remarks{
  height: 28px;  
  line-height: 12px;
}
#ProductModel .introduce-remarks{
  height: auto;
}
input[name='p_total[]'],input[name='p_price[]']{
  text-align: right;
}

/* Style for new products layout */

.new_grid{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 40px;
  grid-row-gap: 20px;
}

/* End of Style for new products layout */

/* Style for remarks */

.extra-price-ingredient{
  position: absolute;
  top:-15px;
  right: -15px;
  background: #1dd800;
  color: white;
  width: 40px;
  height: 40px;
  z-index: 1000;
  display: grid;
  vertical-align: middle;
  align-content: center;
  text-align: center;
  border-radius: 50%;
}
#remarks_area .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#remarks_area .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#remarks_area .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
/*End of style for remarks*/
.product_button span{
  background: #003A66;
  text-align: center;
  padding: 10px;
  color: #fff;
  font-size: 10px;
  text-transform: uppercase;
  font-weight: 500;
  cursor: pointer;
  border-radius: 8px;
  display: inline-block;
  margin-left:20px;
  padding:10px;
}

  .ingredient{
    border: 1px solid #03a9f3;
    color :#03a9f3;
    width: 95%;
    border-radius: 5px;
    padding: 3px;
    box-sizing: border-box;
    letter-spacing: 1px;
    margin: 8px 0;
    -webkit-touch-callout: none; 
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none; 
    -ms-user-select: none; 
    user-select: none;
  }
  .ingredient span:nth-child(even){
    padding-left: 10px;
    font-weight: bold;
  }
  #ingredients_container{
    display: grid;
    grid-template-columns: 1fr 1fr;
  }
  .credentials-container{
    width: 60%;
    
  }
  .credentials-container > div{
    display: grid;
    grid-template-columns: 2fr 3fr;
    grid-column-gap: 5px;
    grid-row-gap: 5px;
    margin-bottom: 10px;
  }
  .credentials-container > div > *{
    width: 100%;
  }
  #reg_field,#passwd_field{
    display: block;
    margin-top: 10px;
    width: 40%;
/*    grid-column-start: 1;
    grid-column-end: 3;
    grid-column-gap: 10px;
    grid-row-gap: 2px;
*/  }
  @media (max-width: 767px) {
  #remarks_area .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #ProductModel > .modal-dialog{
    max-width: 90%;
   } 
   .credentials-container{
      width: 100%;
      margin-bottom: 20px;
    }
    .credentials-container > div{
      grid-template-columns: 1fr;
    }
    #reg_field{
      grid-template-columns: 1fr;
    }
    #passwd_field > input{
      grid-column-start: 1 !important;
      grid-column-end: 3 !important;
    }
    #reg_field, #passwd_field{
      width: 100%;
    }
  }
  input[type='submit'][disabled],button[disabled]{
    cursor: not-allowed;
  }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
      /* display: none; <- Crashes Chrome on hover */
      -webkit-appearance: none;
      margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
  }
  #login_passwd_modal .login_main button.btn{
    width: 100%;
  }
  #login_passwd_modal .login_main .row{
    margin-bottom: 20px;
  }
  @media (max-width: 767px) {
   #ProductModel > .modal-dialog{
    max-width: 90%;
   } 
  }
  body.noscroll{
      overflow: hidden !important;
      position: fixed;
    }

#remarks_area .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #remarks_area .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
}
  </style>
  <script type="text/javascript">
      var subproducts_global = [];
      var products_id_global = [];
	   var lastAdd = null;
  </script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEr0LmMAPOTZ-oxiy9PoDRi3YWdDE_vlI&libraries=places" async defer></script> 
<style type="text/css">
#pop_cart
{
	font-size:.8em;
}
.modal 
{
	width:93%;
}
 @media only screen and (max-width:400px) {
	 .navbar-nav > li
	 {
	 }
 }
@import url( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

.adminDemoVideo {
  position: relative;
  display: inline-block;
}
 @media only screen and (min-width: 700px) {
  .adminDemoVideo::before {
    content: '\f00e';
    z-index: 5;
    position: absolute;
    left: 9%;
    top: 89%;
    transform: translate( -50%, -50% );
    padding: 3px 15px 3px 25px;
    color: white;
    font-family: 'FontAwesome';
    font-size: 30px !important;
    background-color: rgba(23, 35, 34, 0.75);
    border-radius: 5px 5px 5px 5px;
}  
}
@media only screen and (max-width: 700px) {
  .adminDemoVideo::before {
    content: '\f00e';
    z-index: 5;
    position: absolute;
    left:9%;
    top:89%;
	
    transform: translate( -50%, -50% );
    padding: 3px 5px 3px 5px;
    color: white;
    font-family: 'FontAwesome';
    font-size:12px !important;
    background-color: rgba(23, 35, 34, 0.75);
    border-radius: 5px 5px 5px 5px;
}  
}
</style> 
</head>

<body  class="header-light sidebar-dark sidebar-expand pace-done">
     <?php
        $id = $_SESSION['mm_id'];
        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
		$product_zoom=$merchant_detail['product_zoom'];

		 $online_pay=0;
		 $discounted_product = $merchant_detail['discounted_product'];
		 $online_pay=0;
	 if($merchant_detail['credit_check'] || $merchant_detail['wallet_check'] || $merchant_detail['boost_check'] || $merchant_detail['grab_check']
	 || $merchant_detail['wechat_check'] || $merchant_detail['touch_check'] || $merchant_detail['fpx_check'])
	 {
		  $online_pay=1;
		  // $payment_alert="y";
	 }	
	  $online_pay=1;
     $location_order=$merchant_detail['location_order'];
     $koo_wallet=$merchant_detail['koo_wallet'];
     $merchant_id=$merchant_detail['id'];
     $special_coin_name=$merchant_detail['special_coin_name'];
	 if($special_coin_name)
	 {
		$special_coin_min=$merchant_detail['special_coin_min'];
		$special_coin_max=$merchant_detail['special_coin_max'];
		if($_SESSION['login'])
		{
			// echo "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and user_id ='".$_SESSION['login']."'";
			
		$special_wallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  merchant_id='$merchant_id' and user_id ='".$_SESSION['login']."'"));
		// print_R($special_wallet);
		// die;
		if($special_wallet)
		 $special_bal=$special_wallet['coin_balance'];
				else
				 $special_bal=0;
		}
		else
		{
		  $special_bal=0;  	
		}
		
	 }
	 else
	 {
	    $special_bal=0;	 
	 }
	  $submerchantsql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$merchant_detail['name']."' ");
        $stallcount=mysqli_num_rows($submerchantsql); 
    // die;
    $location_range=$merchant_detail['location_range'];
        // echo json_decode($merchant_detail['custom_message'])->message;
        if(isset(json_decode($merchant_detail['custom_message'])->message) && !empty(json_decode($merchant_detail['custom_message'])->message)){
          if(isset($_GET['data']) && !empty($_GET['data']) && isset($_GET['sid']) && !empty($_GET['sid'])){
          $merchant_message = json_decode($merchant_detail['custom_message'])->message;
        ?>
  
        <div class="modal-backdrop show"></div> 

        <div class="modal in" id="merchant_message" tabindex="-1" role="dialog" data-show="true" style="display:block;">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Message from <span style="color: #f00"><?php echo $merchant_detail['name'];?></span></h5>
              </div>
              <div class="modal-body">
                <p><?php  echo $merchant_message; ?></p>
              </div>
            </div>
          </div>
        </div>
      <?php  }} ?>

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

                    <?php
                     $id = $_SESSION['mm_id'];
      
                    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
					
					
                    if( isset($_SESSION['login']) ) {
                        $sql_transaction = "SELECT COUNT(id) ordered_num
                            FROM order_list
                            WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$id."' AND STATUS='1'";
                        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));    
                    } else {
                        $result_transaction = '';
                    }
                    
                    $sql_favorite = "SELECT COUNT(id) favorite_num
                    FROM favorities
                    WHERE favorite_id = '".$id."'";
                    $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
                    
                    $business1 = "";
                    $business2 = "";
                    for($i = 0; $i < count($nature_array); $i++){
                    if($merchant_detail['business1'] == $nature_array[$i])
                    $business1 = $nature_image[$i];
                    if($merchant_detail['business2'] == $nature_array[$i])
                    $business2 = $nature_image[$i];
                    }
                    
                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0"));
                    $total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0");  
          
                    $favorite = isset($_SESSION['login']) ? mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."") : '';
                    $count = $favorite != '' ? mysqli_num_rows($favorite) : 0;
                    ?>
                    
                    
                    <main class="main-wrapper clearfix" style="min-height: 522px;">
                    <div class="row" id="main-content" style="padding-top:25px">
          <input type="hidden" id="shop_status" value="<?php echo $merchant_detail['shop_open']; ?>"/>
                    <?php
                    if($_SESSION['IsVIP'] ==1){ 
                      ?>
                    
                        <div class="box-right">
                            
                            <div class="title">
                                <div class="title-left"> <img src="new/images/merchant.png"> <div class="title-h">  <a href="#"> Merchant Name : <?php echo $merchant_detail['name'];?></a> </div>  </div> 
                            <div class="title-right"> 
                                <div class="favorite_icon">
                                <?php if($count > 0) {?>
                                <i class="heart fa fa-heart"></i>
                                <?php } else {?>
                                <i class="heart fa fa-heart-o"></i>
                                <?php }?>
                                
                                <h4 class="starting-bracket" style="display: inline-block;">(</h4>
                                <?php if($business1 != ""){ ?>
                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">
                                <?php }?>
                                <?php if($business2 != ""){ ?>
                                <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business2;?>">
                                <?php }?>
                                <?php if($merchant_detail['account_type'] != ''){?>
                                <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
                                <?php }?>
                                <h4 class="transaction_num"><?php echo $result_transaction['ordered_num'];?>, </h4>
                                <h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>
                                </div>
                                </div>
                            </div> 
                        </div> 
                        <div class="cont-area3"> 
                        <div class="white-box"> 
                        <div class="btns">
                        <div class="main-btn"> <a  href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a> </div>
                        <div class="main-btn1">  <a  href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </div>
                        <?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
                        <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a>
						
                        <?php }?>
                        </div>
                        
                        
                    
                        <div class="clear-both"> </div>
                        
                        <div class="head-title">Merchant</div>
                        <div class="main-cont">
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently.
                        </div>
                        
                        
                        <div class="grey-bg"> 
                        <div class="grey-left">Merchant Name  </div> 
                        <div class="grey-right"> Product No : 0 </div> 
                        </div> 
                        
                        </div>
                        </div>
                    </div> 
                    
                    <?php }else{ ?> 
                    <div class="col-md-12">
                    <div class="total_rat_abt">
                    <div class="about_uss"><a class="merchant_about" href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a></div>
                    <div class="rating_menuss"><a class="merchant_ratings" href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </a></div>
                    <div class="rating_menuss">
					<?php if($merchant_detail['google_map']){ ?>
						<a class="merchant_ratings" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $merchant_detail['google_map']; ?>"> LOCATION</a> 
						
						<?php  } else { ?>
						<a class="merchant_ratings"  href="#"> LOCATION</a> 
						
						<?php }  ?>
					<!--a class="merchant_ratings" href="<?php echo $site_url; ?>/location.php?address=<?php echo  $_SESSION['address_person'] ?>"><?php echo $language['location']?> </a!-->
					
					</div>
                    
					<?php if($stallcount>0){ ?>
					 <div class="rating_menuss"><a class="merchant_ratings" onclick="our_stall()">Our Stalls </a></div>
					 <?php } ?>
                    <?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
                    <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a>
					
                    <?php }?>
                    </div>
					
				  <div class="clear"></div>
				  <a style="text-align:center;width:100%;margin-top:2%;" href="https://play.google.com/store/apps/details?id=com.koobigfamilies.app" target="blank">
					<img style="max-width:140px;" src="google.png" alt=""></a>
				  <div class="clear"></div>
                    
                    </div>
                      <p style="color:red;" id="error_label"></p>
                    
                    <div class="col-md-12 row favorite" style="margin-left:15px; margin-bottom: 10px; padding-left:0px;" >
                    <div style="clear:both;">
                    <h4 class="favorite_name" style="display: inline-blick;">
                    <a href="javascript:jqcc.cometchat.launch({uid:' <?php echo $merchant_detail['id'];?>'});">Chat with <?php echo $merchant_detail['name'];?> </a></h4>
                    </div>
                    <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_detail['name'];?></h4>
                    <div class="favorite_icon">
                    <?php if($count > 0) {?>
                    <i class="heart fa fa-heart"></i>
                    <?php } else {?>
                    <i class="heart fa fa-heart-o"></i>
                    <?php }?>
                    </div>
                    <h4 class="starting-bracket" style="display: inline-block;">(</h4>
                    <?php if($business1 != ""){ ?>
                    <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">
                    <?php }?>
                    <?php if($business2 != ""){ ?>
                    <img style="margin-top:10px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business2;?>">
                    <?php }?>
                    <?php if($merchant_detail['account_type'] != ''){?>
                    <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
                    <?php }?>
                    <h4 class="transaction_num"><?php if( isset( $result_transaction['ordered_num'] ) ) {echo $result_transaction['ordered_num'];}?>, </h4>
                    <h4 class="favorite_num"><?php if( isset($result_favorite['favorite_num']) ) {echo $result_favorite['favorite_num'];}?>)</h4>
                    </div>
            <?php
               $current_time = date("h:i");
               $opening_hr=$merchant_detail['start_time_setup'];
               $end_hr=$merchant_detail['end_time_setup'];
              $date1 = DateTime::createFromFormat('H:i', $current_time);
             $date2 = DateTime::createFromFormat('H:i', $opening_hr);
             $date3 = DateTime::createFromFormat('H:i', $end_hr);
            $go_ahead=true;
            // if($merchant_detail['shop_open']=="0")
            // {
                 // $go_ahead=false;
            // }
              if($go_ahead==true){  
          ?>
               <!-- if store is open !-->
          <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>
                    <!--div class="comm_prd">
                    <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>
                   
          <div class="oth_pr" id="oth_pr">
                    Order 
                    </div>
          
                    </div!-->
          <?php } ?>
          

                     <?php
                        if($merchant_detail['menu_type']==2){
                            include 'view_merchant_layout2.php';
                        } else {  
                            include 'view_merchant_layout1.php';
                        }
                    ?>  
                   
                   
                </div>
        
        <!-- without picture--->
        <?php
        if($merchant_detail['menu_type']==1) {


    // echo "SELECT * FROM products WHERE category = '".$sub_cat."' and user_id ='".$id."' and status=0";
    // die;
        $total_rows1 = isset($category) ? mysqli_query($conn, "SELECT * FROM products WHERE category = '".$sub_cat."' and user_id ='".$id."' and status=0") : [];
        ?>
        <div class="without_picture">
        
        <table class="table table-striped" id="without_table">
        <thead>
        <tr>
         <th>S.no</th>
         <th>Product Name</th>
         <th>Action</th>
         <th>Remark</th>
         <th>Price</th>
         <th>Code</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i =1;
        while ($row=mysqli_fetch_assoc($totalo_rws1)){
          // var_dump($row);
          if($row['image'] == '') {
        
        
        ?>
          <tr>
              <td><?php echo $i; ?> </td>
              <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
                <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">
            <td><?php echo $row['product_name']; ?></td>
         <?php if($row['varient_exit']=="y") { $cart_class="with_varient";} else { $cart_class="without_varient";} ?>
            <?php 
              if($row['on_stock']){
             ?>
            
            <td  class="text_add_cart_without  <?php echo $cart_class ?>"  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" data-name = "<?php echo $row['product_name'] ?>" id="text_without">Add to Cart</td>
            <?php 
              }else{
                ?>
                  <p class='no_stock_add_to_cart'>Out of stock</p>

                <?php
              }
             ?>
            <td><?php echo number_format((float)$row['product_price'], 2, '.', ''); ?></td>
            <td><?php echo $row['remark']; ?></td>
            <td><?php echo $row['product_type']; ?></td>
            
            </tr>
        <?php  $i++; }
        ?>
        <?php } ?>
        </tbody>
        </table>
        </div>
        <?php } ?>
        
        <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>
        <div class="comm_prd">
        <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>
    
        <div class="oth_pr" id="oth_pr">Order</div>
      
        </div>
    <?php } ?>
        
        </div>
        </main>
        </div>
        <!-- adding new-->
        <?php $profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
        ?>
        <main class="main-wrapper clearfix" style="min-height: 522px;">
        <?php if( !isset($profile_data['user_roles']) || $profile_data['user_roles'] ==  '') { ?>
        <form method="post" id="order_place" action="order_cash.php">
        <?php
        $stl_key = rand();
        $_SESSION['stl_key'] = $stl_key; ?>
        <input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">
        
        
        <?php } else { ?>
        <form id="order_place" action="order_cash.php" method="post">
        <?php } ?>
        
       <table class="table table-striped" id="cartsection">
        <thead>
        <tr> 
        <th></th>
        <th><?php echo ucfirst(strtolower($language["product_name"])); ?></th>
        <th>Qty</th>

        <th><?php echo ucfirst(strtolower($language["product_code"])); ?></th>
        
        <th><?php echo ucfirst(strtolower($language["remark"])); ?></th>

        <th><?php echo ucfirst(strtolower("Extras")); ?></th>
        
    <th>Unit Price</th>
        <th>Total</th>
        </tr>
        <tbody id="test"> </tbody>
        </thead>
        
        </table>
        
        <a href="#main-content"><p class="" style="width: 12rem !important;text-align:center;font-size: 16px;padding:14px;background-color: #003A66;color: white; font-weight: bold; border-radius: 8px;"> Add more order </p></a> <br/>
        
        
        <div class="location_merchant">
        <div class="name_mer">
          <div style="display:grid;grid-template-columns:.2fr 2fr;grid-column-gap: 10px;vertical-align: middle;align-content:center;>
            <?php $deliver_place="Delivery Place"; ?>
            <label class="head_loca" style="display:grid;align-content:center;text-align:left;"><?php echo ucfirst(strtolower($deliver_place)); ?></label>
            <input type="hidden" name="latitude" value="<?php echo $merchant_detail['latitude'];?>">
           
            <input type="hidden" name="longitude" value="<?php echo $merchant_detail['longitude'];?>">
       <input type="hidden" name="user_lat" id='user_lat'>
            <input type="hidden" name="user_long" id='user_long'>

            <?php 
            // --------------------
            // Start of Hire's code 
            // --------------------
            if(isset($_GET['data']))
            {
              $getdetail=$_GET['data'];
             $getdetail=base64_decode($getdetail);
       // print_R($getdetail);
       // die;
              $epxplode=explode("hweset",$getdetail);
        // print_R($epxplode);
        // die;
              $section=$epxplode[0];
              $tablenumber=$epxplode[1];
            }
            // --------------------
            // End of Hire's code 
            // --------------------
            ?>
        <div class="row">
          <div style="float:left;width:80%;margin-left:5%;"> <input class="form-control comment" id="mapSearch" name="location" placeholder="location" value="<?php  if($tablenumber!="Delivery") { echo $merchant_detail['google_map'];}?>"  style="margin: 0 !important;">
            </div>
          <div style="float:left;display:none;"><img src="https://img.icons8.com/office/16/000000/worldwide-location.png"></div>
        </div>
             
      </div>
      </div>  
    
  <div style="float:left;width:100%;top:0;">
  
    
    <div style="float:left;width:20%;">
        <label>Section <br></label>
             <!--input type="text" class="form-control table" name="section_type" value="<?php echo $section; ?>"/!-->
        <select name="section_type" 
              class="form-control"  <?php if($merchant_detail['section_required']=="1"){ echo "required='required'";} ?>
              data-table-list-url="<?php echo $site_url; ?>/table_list.php">
       
       
          <?php foreach($sectionsList as $sectionId => $sectionName): ?>
            <?php
              $isSelected = "";
              if($section == $sectionId) {
                $isSelected = "selected";
              }
            ?>   
            <option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
          <?php endforeach; ?>
        </select>
    </div>

    <div style="float:left;width:33%;">
            <input type="hidden" name='varient_must' id='varient_must'/>
        <input type="hidden" name='varient_count' value='0' id='varient_count'/>
            <label><?php echo "Table number"; ?></label>
            <input type="Number" class="form-control table"  id="table_type" name="table_type"  <?php if($merchant_detail['table_required']=="1"){ echo "required";} ?>  value="<?php echo $tablenumber; ?>"/>
         <!--select name="table_type" class="form-control section-tables" required>
              
            </select!-->
    </div>
    
  </div>  
      
    <div class="credentials-container">
    
    <h5>Enter your phone number</h5>
    <div>
      <div class="input-group mb-2" style="margin-bottom:0px !important;">
        <div class="input-group-prepend">
          <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
        </div>
    
        <input type="number" autocomplete="tel" maxlength='10' id="mobile_number" style="max-width:220px;"  class="mobile_number form-control" value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="123115670" name="mobile_number" required="" />
        
    </div>
    <small>
    We can contact you if anything wrong with the order.We promise that we will not use it for other purpose.
    </small>
	<?php if($merchant_detail['sst_rate']>0){ ?>
	<p style="color:red"> All above prices are subject to <?php echo $merchant_detail['sst_rate']; ?>% SST</p> <?php } ?>
    <small id="mobile_error" style="display: none;color:#e6614f;">
     Please Key in valid number
    </small>
      <img id="loader-credentials" src="<?php echo $site_url;?>img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>
      
   </div>
  
  </div>
  
    <div class="container">
    <div class="row">
	    <div class="col-xs-4" style="margin-left:2%;">
		<input value='0' type="hidden" id="total_rebate_amount" name="total_rebate_amount"/>
		<input type="hidden" value='0' id="total_cart_amount" name="total_cart_amount"/>
		<input type="hidden" value='0' id="rem_amount" name="rem_amount"/>
		<input type="hidden" value='0' id="payable_amount" name="payable_amount"/>
		<input type="hidden" id="wallet_selected" name="wallet_selected" value="n">
        <input type="hidden" id="selected_wallet" name="selected_wallet" value="">
         <input type="hidden" id="selected_wallet_bal" name="selected_wallet_bal" value="">
		<input type="hidden" value='<?php echo $login_user_id;?>' name="login_user_id" id="login_user_id"/>
		
		   <input type="submit" style="width:100% !important;border: 1px black solid;" class="btn btn-block btn-primary submit_button" name="cashpayment"  id="confm"  value="<?php echo $language["confirm_order"];?>">
          
		</div>     
        <div class="col-xs-4 online_label" style="margin-left:1%;">
		<?php if($discounted_product || $special_coin_name){ ?>
            <input type="submit"  style="width:100% !important;border: 1px black solid;" class="btn btn-block btn-primary submit_button online_pay" name="cashpayment" value='Confirm (E-wallet) <?php if($special_coin_name && $_SESSION['login']){ echo "(".$special_coin_name." $".number_format($special_bal,2).")";} ?>'/>
        
		<?php } else { ?>   
		 
		<?php } ?>   
        </div>
		<?php if($merchant_detail['paypal_enable']=="y"){ ?>
       <div class="col-xs-3" style="margin-left:1%;margin-top:-3%;">
	   
	   <input type="image" class="paypal_pay" value="submit" src="https://miro.medium.com/max/312/1*MqdZnOy5ySk8PIUbUtt5Cg.png" alt="submit Button" onMouseOver="this.src='https://miro.medium.com/max/312/1*MqdZnOy5ySk8PIUbUtt5Cg.png'">
		
		
	   </div>
		<?php } ?>
    
    
    </div>
	<div class="row">
	   
	</div>
</div>
    
    
         <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
        <input type="hidden" name="options" value="" />  
        <input type="hidden" name="price_extra" value="" />  
        <input type="hidden" id="myr_input_bal" name="myr_input_bal" value="<?php echo $urecord['balance_myr']; ?>" />  
        <input type="hidden" id="usd_input_bal" name="usd_input_bal" value="<?php echo $urecord['balance_usd']; ?>" />  
        <input type="hidden" id="inr_input_bal" name="inr_input_bal" value="<?php echo $balance_inr; ?>" />  
        <input type="hidden" id="special_input_bal" name="special_input_bal" value="<?php echo $special_bal; ?>" />  
    
       
       
        </div>
        </div>
        <a href="#cartsection"><img id="cartmove" src ="images/shopping-14-512.png" style="width:90px;height:90px;position: fixed;right: 10px;bottom: 70px;"></a>
    
        </form>
		<form id="paypal_form" action="<?php echo $paypalUrl; ?>" method="post">
								<div class="panel price panel-red" style="padding:50px 5px;">
									<input type="hidden" name="business" value="<?php echo $paypalId; ?>">
									<input type="hidden" name="cmd" value="_xclick">
									<input type="hidden" name="item_order_id">
									<input type="hidden" name="item_name" value="koofamilies Order">
									<input type="hidden" name="item_number" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="MYR">
									<input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url."&sid=".$merchant_detail['mobile_number']; ?>">
									<input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">
									
									
									<input style="display:none;" type="number" class="form-control"  id="paypal_amount" name="amount" placeholder="Amount (in MYR)">
									<br><br>
									
								</div>   
		</form>
        <?php 
        // ---------------------------
        // Start of DrakkoFire's code 
        // Remark Project
        // ---------------------------
        ?>
      <?php } else { ?>
             <h5 class="favorite_name" style="display: inline-blick;margin-left:1%;">We are temporary closed for online order now. Please try later. Sorry for any inconvenient caused !</h5>
           
            <?php } ?>
        <div id="remarks_area" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                      <h4 class="modal-title">Remarks</h4>
                  </div>
                  <div class="modal-body">
                    <?php 
                      $ingredients = json_decode(mysqli_fetch_row(mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'"))[0]);
                      foreach ($ingredients as $ingredient) {
                        if(!empty($ingredient)){
                          // $ingName = (sizeof(explode("[", $ingredient)) > 1) ? explode("[", $ingredient)[0] : $ingredient;
                          $ingName = $ingredient->name;
                          $ingPrice = ($ingredient->price == 0) ? null : $ingredient->price;
                          // $ingPrice = (!empty(explode("]",explode("[", $ingredient)[1])[0])) ? explode("]",explode("[", $ingredient)[1])[0] : null;

                          ?>
                           <div style="margin: 10px 8px;" class="btn-group" data-toggle="buttons" data-subcategory='<?php echo $ingredient->subcategory; ?>'>
                              <label class="btn btn-secondary">
                                <input type="checkbox" name="ingredient" value="<?php echo $ingName; ?>" autocomplete="off"> 
                                <?php
                                    echo ucfirst(str_replace("_", " ",$ingName)); 
                                ?>
                              </label>
                              <?php  if(!is_null($ingPrice)){ ?><div class='extra-price-ingredient'><?php echo number_format($ingPrice,2,".",""); ?></div><?php } ?>
                            </div>
                            <?php
                        }
                      }

                    ?>
                    <input type="hidden" name="remark" id="remark_input" class="form-control" style="margin: 10px 0" placeholder="Write here your remarks"/>
                    <div id="small_text" style="position:relative;margin-top:2em;width:100%;">
                      <small style="position: absolute;bottom: 2px;left: 5px;font-weight: bold;color:red;">Note : e.g. 0.30 = Rm 0.30</small>
                    </div>
                  </div>
                  <div class="modal-footer" style="position: relative;">
                    <button type="button" id="reset_remark" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success save_close" data-dismiss="modal">
                    Save and
                    <p class="text_add_cart" style="width: 20px; height: 20px; font-size: 12px;padding: 4px 0 0 0;">
                      <i class="fa fa-plus"></i>
                    </p> 
                    </button>
          <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>
                    <button type="button" class="btn btn-default manual_input">Manual input</button>
          <?php } ?>
                  </div>
              </div>
          </div>
        </div>
        <?php 
        // ---------------------------
        // Remark project
        // End of DrakkoFire's code 
        // ---------------------------
        ?>
</main>
<!-- /.widget-body badge -->
</div>
    <!-- /.widget-bg -->
<?php } ?>
    <!-- /.content-wrapper -->

    <?php include("includes1/commonfooter.php"); ?>
   <div class="modal fade" id="ProductModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Product Varieties For <br/> <span id="varient_name" style="font-weight:bold;"></span></h4>
                    
                    <p id="varient" style="display:none;"></p>
                    <p id="varient_type" style="display:none;"></p>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body product_data" style="padding-bottom:0px;max-height:50vh;overflow-x: auto;">
                     <p id="varient_error" style="color:red;display:none;">Please select at least one choice. Thank</p>
                      
                       <div id="product_main" class="ingredients_container">
                         
                       </div>
                      
                      <div class="product_extra">
                      <input id="p_pop_price"  type="hidden"/>
                      <table border="1px solid" style="width:80%;color:black;">
                      <tr><td> Product Name </td><td> Rm </td></tr>
                       <tbody id="product_table">
                          
                       </tbody>
                        
                        <tr><td> <b> Total : </b></td><td id="pr_total"></td></tr>
                        <tbody><tr><td>Remarks</td><td id="remark_td"></td></tr></tbody>
                      </table>
                       <br/>
                      
                        <!--p id="pr_total"></p!-->
                        
                      </div>
                      
                    
                                        </div>
                                        <div style="margin: 10px 0 10px 34%;"  class="modal-footer product_button pop_model">
                        
                                         
                                        </div>
                    <br/>
                                    </form>
                                </div>
                            </div>
                        </div>
            <div class="modal fade" id="location_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>This merchant require your permission for location in order to place order </h5>
                          <button type="button" class="btn btn-primary" onclick="getLocation()">Allow Now</button>
                
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                    
                    </div>
               
            </div>
        </div>
 </div>
   <div class="modal fade" id="paypal_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>
								Something Went Wrong to Complete Payment Try Again or Use cash method to complete it
							</h5>
                          
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                       <button id="paypal_cash" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Pay Cash</button>
                       <button id="paypal_close" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>
                    </div>
               
            </div>
        </div>
 </div>
    <div class="modal fade" id="shop_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>
						  <?php if($merchant_detail['mobile_number']!="60172669613"){ ?>
              Sorry, we are currently experiencing some internet connection issue. If you want to place any order, please contact our waiter for placing order.
						  <?php } else { ?>
						   Our online order is from 8:00pm to 11.00pm only. please contact our waiter for your order. 
						  <?php } ?>
              </h5>
                          
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                       <button id="close_shop" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>
                    </div>
               
            </div>
        </div>
 </div>
 
 <div class="modal fade" id="work_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>
						  Sorry,Shop is Close Now
						  </h5>
                          
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                       <button id="close_shop" type="button" class="btn btn-primary" data-dismiss="modal" style="width:50%;margin-bottom: 3%;text-align: center;">Close</button>
                    </div>
               
            </div>
        </div>
 </div>
  <div class=" modal fade" id="ProductAdded" role="dialog">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p>The product added</p>
                   <div id="without_varient_footer" class="modal-footer model_pop" style="padding-bottom:2px;">
                   <input type="hidden" id="pop_ok" name="pop_ok">
                    <button role="button" style="min-height:40px;position:static !important;" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal" data-target="#remarks_area" disabled="">Remarks</button>
                    <button role="button" class="close_pop btn btn-large btn-primary" style="background:#50D2B7;border:none;">Ok</button>
                  </div>    
                                </div>
                            </div>
    </div>
	  <div class=" modal fade" id="AlerModel" role="dialog">
        <div class="element-item modal-dialog modal-dialog-centered" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;display: grid;align-content: center;">
            <!-- Modal content-->
            <div class="element-item modal-content">
                <div class="element-item modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    
                              </div>   
                                    <p id="show_msg">The product added</p>
                    
                                </div>
                            </div>
    </div>
 	<div class="modal fade" id="map_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>Sorry, To Place order You has to be in <span id='map_range'> </span> km range of Merchant </h5>
                          
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                       
                    </div>
               
            </div>
        </div>
 </div>
 

  <div class="modal fade" id="map_model" role="dialog">
        <div class="modal-dialog">
         

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>Sorry, To Place order You has to be in <span id='map_range'> </span> km range of Merchant </h5>
                          
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                       
                    </div>
               
            </div>
        </div>
 </div>
  <div class="modal fade" id="our_stall" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sub Merchant List of <?php echo $merchant_detail['name'];?> </h4>
                </div>
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-sm-10">
                            <div class="form-group">
                       

                              <div class="container">
                              
                                <table class="table table-striped submer">
                                  <thead>
                                    <tr>
                                      <th>Submerchant Name</th>
                                      <th>Favorite</th>
                                    </tr>
                                  </thead>
                                 
                                  <tbody> 
                                    <form id="sub_mer_form" action="structure_merchant.php" method="post">

                                      <?php 
                                            $sql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$merchant_detail['name']."' ");
                                            
                                            while($data = mysqli_fetch_array($sql))
                                             {
                                            
                                             $fav=mysqli_query($conn, "SELECT count(*) as number FROM `favorities` WHERE `favorite_id`='".$data['id']."'");
                                             $cu=mysqli_fetch_array($fav);

                                             $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE name='".$data['name']."' ");
                                             $m =mysqli_fetch_array($sql2);
                                            echo'<tr value='.$m['id'].' onclick="getId(this)"><td>'.$data['name'].' </td><td>( '.$cu['number'].' ) <i style="font-size: 17px;" class="heart fa fa-heart"></i> </td><input type="hidden" name="merchant_id" id="merchant_id" value=""><input type="hidden" name="sub_mer_id" id="sub_mer_id" value="sub_mer_id"></tr>';
                                            
                                              }

                                            ?>
                                           
                                     </form> 
                                  
                                  </tbody>
                                 
                                </table>
                              </div>



                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <!--<button type="button" class="btn btn-primary" data-dismiss="modal">No</button> <a href="view_merchant.php"><input type="button" class="btn btn-primary" value="Yes"></a>-->
                    </div>
                </form>
            </div>
        </div>
 </div>
<div class="modal fade" id="login_passwd_modal" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="form-group">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Enter your phone number</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post" id="cred_ajax_popup">
          <div class="modal-body">
            <div class="form-group">
              <label for="check_phone">Phone number</label>
              <input type="text" id="check_phone" class="form-control" name="check_phone"/>
              <div class="passwd_field">
                <label for="login_password">Password</label>
                <input type="password" id="login_password" class="form-control" name="login_password" required/>
                <small class="wrong_login" style="display: none;color:#e6614f;">
                  Something went wrong! Try again
                </small>
                <small class="acc_blocked" style="display: none;color:#e6614f;">
                  This account is blocked, please contact support.
                </small>
                <small class="reg_pending" style="display: none;color:#e6614f;">
                  This account is waiting for activation.
                </small>
                <small class="logged-in" style="display: none;color:#e6614f;">
                  You are already logged into this account
                </small>
                <small class="success_login" style="display: none;color:#28a745;">
                  Successfully logged in!
                </small>
              </div>
            </div>
          </div>
          <div class="modal-footer login_footer">
            <div class="row" style="margin: 0;">
              <div class="col" style="padding: 0;margin: 5px;">
                <input type="submit" class="btn btn-primary" name="login_ajax" value="Login" style="width:100%;"/>
              </div>
              <div class="col" style="padding:0; margin: 5px;">
                <div class="btn facebook-login" style="width:100%;height:100%;"></div>
              </div>
              <div class="col-sm-4" style="padding: 0;margin: 5px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Exit</button>
              </div>
            </div>
          </div>
          <div class="modal-footer register_footer" style="display: none;">
            <div class="row" style="margin: 0;">
              <div class="col" style="padding: 0;margin: 5px;">
                <input type="submit" class="btn btn-primary" name="register_ajax" value="Register" style="width:100%;"/>
              </div>
               <div class="col" style="padding:0; margin: 5px;">
                <div class="btn facebook-login" style="width:100%;height:100%;position:relative;"></div>
              </div>
                            <div class="col-sm-4" style="padding: 0;margin: 5px;">
                <button id="continue_guest" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;">Continue as Guest</button>
              </div>
                        </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- login popup for rebeat process!-->
	<div class="modal fade" id="LesaaAmountModel" role="dialog" style="z-index:999999;margin-top:3%;">
                    <div class="modal-dialog">
                      <div class="modal-content" id="modalcontent">
					   <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
                          <div class="modal-body">
                              <p style="font-size:18px;">Insufficient balance for deduction, please select other wallet or pay by cash. </p>
							   <button class="btn btn-large btn-primary insufficient_close">ok</button>
                          </div>
                      
                      </div>
                    </div>
    </div>
	<div class="modal fade" id="WalletModel" role="dialog" style="z-index:999999;margin-top:3%;">
                    <div class="modal-dialog">
                      <div class="modal-content" id="modalcontent">
					   <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
                          <div class="modal-body">
                              <p style="font-size:18px;">Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet </p>
							   <button class="btn btn-large btn-primary"  data-dismiss="modal">ok</button>
                          </div>
                      
                      </div>
                    </div>
    </div>
	<div class="modal fade" id="ProccedAmount" role="dialog" style="z-index:999999;margin-top:3%;">
                    <div class="modal-dialog">
                      <div class="modal-content" id="modalcontent">
					   <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
                          <div class="modal-body">
                              <p style="font-size:18px;" id="amount_label"> </p>
                            <button class="btn btn-large btn-primary make_payment">Yes</button>
							<button class="btn btn-large btn-primary extracss" data-dismiss="modal">Cancel</button>
						 </div>
                      
                      </div>
                    </div>
    </div>
<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="form-group">
      <div class="modal-content">
	    
        <div class="modal-header">
        
          <button type="button" class="close cash_pay" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
          <div class="modal-body online_login_model">
		  <div class="form-group">
				
				<p id="with_wallet" style="font-size:16px;color:red;display:none;">
				<span style='font-size:20px;'>&#128512;</span> 
				<!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->
					<?php if($special_coin_name){  echo "Congratulation, your order is completed. Please login to use $special_coin_name wallet.";?>
					
					<?php } else {?>
					<!--Congratulation, your order is completed. <span id="with_wallet_span"> Please login to claim for your rebate of RM <span class="rebate_amount_label">15.20 </span> into your KOO Coin wallet.</span!-->  
					Congratulation, your order is completed. Please login to use KOO Coin wallet.
					<?php } ?>
				 </p>    
				 <p id="without_wallet" style="font-size: 16px; color: red;display:none;">
				<span style="font-size:20px;">😀</span> 
				<!--Congratulations, your order has just earned Rm <span class="rebate_amount_label"></span> into your Koo Coin wallet. You can use it after 24 hours !-->
					<?php if($special_coin_name){ echo "Congratulation, your order is completed. Please login to use $special_coin_name wallet.";} else { echo "Congratulation, your order is completed. Please login to use KOO Coin wallet.";} ?>
					
					
				
				 </p>
			 
				
			</div>
			<div class="wallet_mode" style="display:none;">
				<h4>Please choose your wallet to pay</h4>
				 <div class="row">
					<!--div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-primary text-white">
						<div class="card-body wallet_select" wallet_name="MYR" type="myr_bal">MYR <br> <span  id="myr_bal"><?php if(isset($urecord['balance_myr'])){ echo $urecord['balance_myr'];} ?></span></div>
					</div>
					<div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;" class="col-md-3 card bg-info text-white">
						<div class="card-body wallet_select" wallet_name="CF"  type="usd_bal">CF <br> <span id="usd_bal"><?php if(isset($urecord['balance_usd'])){ echo $urecord['balance_usd'];} ?></span></div>
					</div!-->
					<?php if($special_coin_name){ ?>
					 <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;font-size: 13px;" class="col-md-7 card bg-info text-white">
						<div class="card-body wallet_select" wallet_name="<?php echo $special_coin_name; ?>"  type="special_bal"  style="color:black;padding:1.00rem !important;font-size: 18px;">
						 <?php echo $special_coin_name; ?>  RM <span id="special_bal" style="color:red;font-weight:bold;"><?php if(isset($special_bal)){ echo number_format($special_bal,2);} ?></span>
						 </div>
						
				   </div>
				    <small style="margin-left: 3%;color:red;">(Min RM <?php echo $merchant_detail['special_coin_min'];?> to use, and Max RM <?php echo $merchant_detail['special_coin_max'];?> per transaction)</small>
					<?php } else { ?>
				   <div style="margin: 2%;max-height: 80px;text-align: left;padding: 0%;font-size: 13px;" class="col-md-7 card bg-info text-white">
						<div class="card-body wallet_select" wallet_name="KOO COIN"  type="inr_bal"  style="color:black;padding:1.00rem !important;font-size: 18px;">
						 KOO REWARD  RM <span id="inr_bal" style="color:red;font-weight:bold;"><?php if(isset($balance_inr)){ echo number_format($balance_inr,2);} ?></span>
						 </div>
						
					</div> 
					 <small style="margin-left: 3%;color:red;">(Min RM 0.50 to use, and Max RM 10.00 per transaction)</small>
					<?php } ?>
				   
				 <div clas="form-group" style="margin-left:3%;font-size:15px;">
				 <p class="select_label" style="display:none;">Selected Wallet : <span id='wallet_name'></span></br>
				    
					
					 Total Amount: Rm <span id="total_cart_amount_label" style="font-weight: bold;color: black;"></span></br>
					  <span id="wallet_payment_label" style="display:none;"></span>
					  <span id="bal_to_paid_label" style="display:none;color: green;"></span>
				 
					 </p> 
						 
					 <span class="btn btn-large btn-primary wallet_final_payment" style="display:none;">Pay Now<span>
				 </div>
					<div class="form-group no-wallet">
					<p style="text-align: center;">OR</p>
				   <span class="btn btn-block btn-primary cash_pay" name="cashpayment">Change to Cash Payment 
				  </span> 
					
					</div>
				</div>
				
			</div>
			
			<div id="login_process">
             
			
				
			  <div class="login_passwd_field" style="display:none;">
                <label for="login_password">Password to login</label>
                <input  type="password" id="login_ajax_password" class="form-control" name="login_password" required/>
				
       <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
	  <span onclick="myFunction()" id="eye_pass"> Show Password </span>   
 
						
				<small class="forgot_pass" style="color:#28a745;float:right;">
                  Reset/Create Password
                </small>
              
              </div>
			    <div class="forgot-form" style="display:none;">
				  <label for="login_password">Reset/Create  Password</label>
				  <div class="input-group mb-2">
					<div class="input-group-prepend">
					  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
					</div>
					
					<input  type="number" autocomplete="tel" maxlength='10' id="user_mobile" class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />
				   
				  </div>
				  <small id="forgot_error" style="display: none;color:#e6614f;">
					 Please Key in valid number
					</small>
				  <img id="loader-credentials" src="<?php echo $site_url;?>img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>
				</div>
			  
         
          <div class="modal-footer login_footer">
            <div class="row" style="margin: 0;">
			 
             
              <div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">
                
                <input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="Login" style="float: right;display:none;"/>
				 	 <small id="login_error" style="display: none;color:#e6614f;">
                 
                </small>
				
              </div>
			  <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">
                <input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>
              </div>         
           
            </div>
			
          </div>
		  </div>
		  
      </div>
    </div>
  </div>
</div>
</div>
<!-- end login popup for rebeat process!-->




</body>

</html>

  <script>
    // Start of DrakkoFire's code
    // Remark
	   var varient_selected = [];
	var already_login='<?php echo $login_user_id; ?>';
	 
   var merchant_mobile=<?php echo $merchant_detail['mobile_number'];?>;
    $("#reset_remark").click(function(){
      $("#remarks_area .btn.btn-secondary.checkbox-checked.active").removeClass("checkbox-checked").removeClass("active");
    });
   $("#remarks_area").on("shown.bs.modal", function(){
      $("body").addClass("noscroll");
    });
    $('#remarks_area').on('hide.bs.modal', function (e) {
        $(this).removeClass("transaction");
     $("body").removeClass("noscroll");
        $("#remark_input").attr("type","hidden").val('');
        $("input[name='ingredients'].selected").removeClass("selected");
        $("a.introduce-remarks.selected").removeClass("selected");
    });
    $('#remarks_area').on('click','.save_close', function (e) {
        var selected = [];
        var extras = [];
        $('div#remarks_area .btn-secondary.checkbox-checked.active').each(function() {
            selected.push($(this).children("input[name='ingredient']").val());
            val_extra = $.trim($(this).siblings(".extra-price-ingredient").html());
            if(val_extra != ''){
              extras.push(val_extra);
            }
        });
        console.log(extras);
        if($("#remark_input").val() != ''){
          selected.push($("#remark_input").val().split(' ').join('_'));
        }
        var input_extras = 0;
        for (var i = 0; i < extras.length; i++) {
          input_extras += parseFloat(extras[i]);
        }
        var id = $("#remarks_area").data("id");
        // console.log(input_extras);
        // console.log(selected.toString().split("_").join(" "));
        var qty = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='qty[]']").val());
		if(isNaN(qty)) {
		var qty = 1;
		}
		// alert(qty);
        var unitPrice = parseFloat($(".introduce-remarks.selected").parent().parent().find("input[name='p_price[]']").val());
        // console.log(unitPrice);
        $(".introduce-remarks.selected").parent().parent().find("input[name='p_extra']").val(input_extras);
        $(".introduce-remarks.selected").parent().parent().find("input[name='p_total[]']").val((input_extras + unitPrice < 0) ? 0 : ((input_extras + unitPrice)*qty).toFixed(2));
        $(".introduce-remarks.selected").siblings("input[name='extra']").val(extras);
        $("input[name='single_ingredients'].selected").siblings("input[name='extra']").val(extras);
        if(!$(".introduce-remarks.selected").parent().hasClass("pop_model")){
          $("a.introduce-remarks.selected").html((selected.toString() == '') ? "Remarks" : selected.toString().split("_").join(" "));
        }else{
          $("#remark_td").html((selected == '') ? "" : selected.toString().split("_").join(" "));
        }
        $(".introduce-remarks.selected").removeClass("selected");
        $("input[name='ingredients'].selected").val('').val(selected).removeClass("selected");
        $("input[name='single_ingredients'].selected").val('').val(selected).removeClass("selected");
        if($("#remarks_area").hasClass("transaction")){
          if($("#remarks_area").hasClass("no-back")){
            $("#pop_cart[data-id='" + id + "']").click();
            $("#pop_cart").removeData("id");
            $("#remarks_area").removeClass("no-back");
          }else{
            $(".text_add_cart[data-id='" + id + "']").click();
          }

          // BUSCAME

          $("#remarks_area").removeData("id");
        }
    });
	 $(".insufficient_close").click(function(e){
		  $(this).removeClass(" btn-primary").addClass("btn-default");
		  $("#LesaaAmountModel").modal("hide");
	 });
    $(".manual_input").click(function(e){

      if($("#remark_input").attr("type") == "hidden"){
        $("#remark_input").attr("type","text");
      }else if($("#remark_input").attr("type") == "text"){
        $("#remark_input").attr("type","hidden").val('');
      }

      e.preventDefault();
    });
	 $("#paypal_cash").click(function(e){ 
	 
		$.ajax({
             url:"functions.php",
             type:"post",
             data:{method:"paypalcash"},
             dataType:'json',
             success:function(response){
				 var data = JSON.parse(JSON.stringify(response));
					if(data.status==true)
					{

					   window.location.replace("https://www.koofamilies.com/orderlist.php");
					}
					else
					{
						alert(data.msg);  
					}
			 }
		});			 
	 });
   $("#paypal_close").click(function(e){    
      window.location.replace("https://www.koofamilies.com/view_merchant.php");
   });
   $(".close_shop").click(function(e){
      $('#shop_model').modal('hide');
   });
    $("input[type='submit']").click(function(e){
      var remarks = []; 
      var price_extra = [];
      $('#test input[name="ingredients"]').each(function() {
             remarks.push($(this).val());
        });
      $('#test input[name="extra"]').each(function() {
             price_extra.push($(this).val());
        });
      // console.log("Price extra (Array):");
      // console.log(price_extra);
      var result = '';
        for (var i = 0; i <= remarks.length - 1; i++) {
          // console.log(remarks[i]);
          if(i != remarks.length - 1){
            result += remarks[i] + "|";
          }else{
            result += remarks[i];
          }
        }
      var result_price = '';
      for (var i = 0; i <= price_extra.length - 1; i++) {
          // console.log(price_extra[i]);
          if(i != price_extra.length - 1){
            result_price += price_extra[i] + "|";
          }else{
            result_price += price_extra[i];
          }
        }
      $("input[name='options']").val(result);
      $("input[name='price_extra']").val(result_price);
      console.log("result_price");
      console.log(result_price);
    });
    $("body").on("click",".introduce-remarks", function(e){
		  e.preventDefault();
      $(this).addClass("selected");
      if($(this).parent().parent().parent().parent().hasClass("element-item")){
        var id = $(this).siblings("input[name='p_id']").val();
        console.log(id);
        $("#remarks_area").addClass("transaction").attr("data-id", id);
      }
      $("#ProductAdded").modal("hide");
      $(this).parent().parent().find("input[name='ingredients']").addClass("selected");
      $(this).parent().parent().find("input[name='single_ingredients']").addClass("selected");
      var ingredients = $(this).parent().parent().find("input[name='ingredients']").val();
      $("#remarks_area .modal-body .btn-group .btn-secondary").removeClass("checkbox-checked").removeClass("active");
    
    });

    $(".modal-footer").on("click", ".introduce-remarks", function(){
      // alert(3);
	  // alert(3);
       // if($(this).parent().is("#without_varient_footer")){
        
      // } else {
        // var varient_count = $(".removevarient").length;
		
        // if(varient_count < 1)
        // {  
            // $('#varient_error').show(); 
            // return false;
        // }
      // }
      var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
      var quantity = $(this).closest("form").find("input[name='quatity']").val();
      var name = $(this).siblings("#pop_cart").data("name");
      var code = $(this).siblings("#pop_cart").data("code");
      var id = $(this).siblings("#pop_cart").data("id");
      var p_extra = $(this).siblings("input[name='extra']").val();
      $("#remarks_area").addClass("transaction").attr("data-id", id);
      $(this).siblings("input[name='single_ingredients']").addClass("selected")
      if($(this).parent().is("#without_varient_footer")){
        $("#remarks_area").addClass("no-back");
      }
      $('#ProductModel').modal('hide');
      $(".text_add_cart[data-id='" + id + "']").parent().siblings(".introduce-remarks").click();
      $(".modal_pop").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");
      return false;
    })

    // Remark
    //  product varient feature 
   $(".without_varient").on("click", function(){
    console.log("Without varient");
     // $(this).hide();
     $("#product_main").html("");
     $("#product_table").html("");
    var id = $(this).data("id");
    var child_id="child_"+id;
    var product_child_id="product_child_"+id;
    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
    var p_extra = $(this).parent().parent().find("input[name='extra']").val();
    if(p_extra == ''){
      p_extra = 0;
    }
    document.getElementById(child_id).classList.remove("fa-plus");
    var code = $(this).data("code");
    var p_price = $(this).data("pr");
	 var rebate = $(this).data("rebate");
    var name = $(this).data("name");
    console.log("Price extra: " + p_extra);
    var extra_price = 0;
    if(p_extra == ''){
      p_extra = 0;
    }else{
      p_extra = p_extra.split(",")
      for(var i = 0; i < p_extra.length; i++){
        extra_price += parseFloat(p_extra[i]);
      }
    }  
    console.log("Extra price" + extra_price);
    // $("#varient_name").html(name);
    // alert(extra_price);   
    var quantity = $(this).closest("form").find("input[name='quatity']").val();
    var p_total = p_price*quantity+extra_price;
    var p_total=p_total.toFixed(2);

    // alert(p_total);
	  $("#without_varient_footer").html("<div class='row'><div class='col-md-12'>Quantity: <input name='quantity_input' min='1' type='number' class='quatity' value='" + quantity + "' style='width:2.5em;text-align:center' min='0' max='99'/></div></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-rebate='"+rebate+"' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>Ok</span>");
    
   // $("#without_varient_footer").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal' style='top:unset;bottom:3px;right:100px;border-radius: 5px;'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + extra_price + "'/><span id='pop_cart' data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"' data-pr=" + p_price + " class='close_pop btn btn-large btn-primary' data-dismiss='modal' style='background:#50D2B7;border:none;'>Ok</span>");
    // $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]' maxlength='3' class='product_qty'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? "Remarks" : single_remarks) +  "</a><input type='hidden' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price.toFixed(2)+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= " + p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value=''></td> </tr>");
    document.getElementById(child_id).classList.add("fa-check");
     document.getElementById(product_child_id).style.backgroundColor = "red";
            // alert('The product added');
    $('#pop_ok').val(id);
    $("#ProductAdded").modal("show");
     document.getElementById(child_id).classList.add("fa-plus");
     document.getElementById(child_id).classList.remove("fa-check");   
     document.getElementById(product_child_id).style.backgroundColor = "#50d2b7";
             // $(".text_add_cart").show(); 

        // $(".element-item input[name='extra']").val('');
        // $(".text_add_cart input[name='extra']").val('');
        // $("input[name='single_ingredients']").val('');
    
   });
	$("#without_varient_footer").on("change", "input[name='quantity_input']", function(){
      var newQuantity = $(this).val();
      $(this).parent().parent().parent().find("#pop_cart").attr("data-quantity", newQuantity);
   });
   $(".with_varient").on("click", function(){
    // $(this).hide();
	// alert(4);  
    var p_price = $(this).data("pr");
    console.log(p_price);
     // $('#varient_count').val(0);
     $("#product_main").html("");
     $("#product_table").html("");
    var id = $(this).data("id");
    var varient_must = $(this).attr("data_varient_must");
    $('#varient_must').val(varient_must);
    var child_id="child_"+id;
    var product_child_id="product_child_"+id;
    var subproduct_selected = '';
    var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
    var p_extra = $(this).parent().parent().find("input[name='extra']").val();
	// alert(p_extra);
     document.getElementById(child_id).classList.remove("fa-plus");
     document.getElementById(child_id).classList.add("fa-check");
     document.getElementById(product_child_id).style.backgroundColor = "red";
    var code = $(this).data("code");
    var name = $(this).data("name");
	var rebate = $(this).data("rebate");
    var quantity = $(this).siblings(".quantity").children(".quatity").val();
        // alert(quantity);
    // var p_total = p_price*quantity;
    p_total = parseFloat(p_price).toFixed(2);
    $("#varient_name").html(name);
        $("#p_pop_price").val(p_total);     
         $("#product_table").append("<tr><td> "+name+" </td><td> "+p_total+" </td></tr>");  
       $("#pr_total").html("<b>"+p_total+"</b>");
       $("#remark_td").html((single_remarks == '') ? "" : single_remarks.toString().split("_").join(" "));
      // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");
      $(".pop_model").html("<div class='row' style='width:11em'></div><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><input type='hidden' name='extra' value='" + p_extra + "'/><span id='pop_cart' data-rebate='"+rebate+"' data-pr=" + p_price + " data-id='"+id+"' data-code='"+code+"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");
        

      for(var i = 0; i < subproducts_global.length; i++){
        for(var j = 0; j < subproducts_global[i].length; j++){
          if(subproducts_global[i][j]['product_id'] == id){
            subproduct_selected = subproducts_global[i];
            break;
          }
        }
      }
      // console.log(subproduct_selected);
      var exists_in_subproducts = false;
      for(var i = 0; i < subproduct_selected.length; i++){
        if(subproduct_selected[i]['product_id'] == id){
          exists_in_subproducts = true;
          break;
        }
      }
      if(exists_in_subproducts){

        var content = '';
        for(var i = 0; i < subproduct_selected.length; i++){
          content +="<div  id='prodct_cart_"+subproduct_selected[i]['id']+"' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' class='ingredient product_cart'>";
              content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' aria-label='Close'>";
              content +="<span aria-hidden='true'><i class='fa fa-plus'></i></span>";
              content +="</button><span class='ingredient-name'>"+subproduct_selected[i]['name']+" &nbsp; Price Rm "+subproduct_selected[i]['product_price']+"</span></div>";
          // console.log(content);
          
        }

        $("#product_main").html(content);
        console.log(varient_selected);
        varient_selected.forEach(function(e){
          $("#product_main div").each(function(){
            if($(this).data("name") == e)
              $(this).click();
          })
        });
        varient_selected = [];
        if (showModal) {
          $("#ProductModel").modal("show");
        }else{
          showModal = true;
        }

      }
  });
  $(document).on("click", '.product_cart', function(event) { 
    $('#varient_error').hide();   
     var content = "";
       var id = $(this).data("id");
     var varient_count=$('#varient_count').val();
     varient_count++;
     // alert(varient_count);
     $('#varient_count').val(varient_count);
    var p_price = $(this).data("price");
    var name = $(this).data("name");
    // alert(p_price);
    var p_price=parseFloat(p_price).toFixed(2);
    var p_pop_price=$("#p_pop_price").val();
    var c_id="prodct_cart_"+id;
    // alert(c_id);
    var sum=parseFloat(p_price)+parseFloat(p_pop_price);
    // var sum= parseFloat((p_price).toFixed(2));
    var sum=parseFloat(sum).toFixed(2);
    // alert(sum);
    $("#p_pop_price").val(sum);
    $("#pr_total").html("<b>"+sum+"</b>");
    $("#varient").append("<br/>-"+name+"(Rm "+p_price +")");
    var old_varent=$("#varient_type").html();
    $("#varient_type").html(old_varent+","+id+"");
       
    // var content="<p data-id="+id+" data-pr="+p_price+">"+name +"  : Rm "+p_price +"<button  data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'>X</button></p>";
    // content +="<div  id='prodct_cart_"+id+"' data-name='"+name+"' data-id='"+id+"' data-price='"+p_price+"' class='ingredient product_cart'>";
              // content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+name+"' data-id='"+name+"' data-price='"+p_price+"' aria-label='Close'>";
              // content +="<span aria-hidden='true'>X</span>";
              // content +="</button><span class='ingredient-name'>"+name+" &nbsp; Price Rm "+p_price+"</span></div>";
            
    // alert(content);
     var link = document.getElementById(c_id);
    link.style.display = 'none'; //or
	var show_label=""+name+" (Rm "+p_price+")";
	// alert(show_label);
     // $("#product_info").append(content); 
         $("#product_table").append("<tr><td> &nbsp;&nbsp;<button data-id="+id+" data-name="+name+" data-pr="+p_price+" type='button' class='removevarient'><i class='fa fa-remove'></i></button>-<span show_label='"+show_label+"' class='sub_pro_name'>"+name+"</span></td><td> "+p_price+" </td></tr>");       
     content='';
  });
  $(document).on("click", '.removevarient', function(event) { 
      var varient_count=$('#varient_count').val();
     varient_count--;
     // alert(varient_count);
     $('#varient_count').val(varient_count);
     var id = $(this).data("id");
     var price = $(this).data("pr");
      var price=parseFloat(price).toFixed(2);
     var name = $(this).data("name");
     var c_id="prodct_cart_"+id;
     var p_pop_price=$("#p_pop_price").val();
     var p_pop_price=parseFloat(p_pop_price).toFixed(2);
     var old_varent=$("#varient_type").html();
     var varent_list=$("#varient").html();
     // alert(varent_list);
     var r_key="<br>-"+name+"(Rm "+price +")";
     // alert(r_key);
     var new_varient_list = varent_list.replace(r_key,'');
     var new_vareint = old_varent.replace(id,'');
    $("#varient").html(new_varient_list);
    $("#varient_type").html(new_vareint);
     var link = document.getElementById(c_id);
    link.style.display = 'block'; //or
     var sum=parseFloat(p_pop_price)-parseFloat(price);
      var sum=parseFloat(sum).toFixed(2);
    $("#p_pop_price").val(sum);   
    $("#pr_total").html("<b>"+sum+"</b>");
    jQuery(this).closest('tr').remove();
    
  });
    $(document).on("click", '#pop_cart', function(event) {   
   // alert(3);
   var varient_must=$('#varient_must').val();
    var last_added_id = $(".producttr").length;
   var go_ahead="y";
   if(varient_must=="y")
   {
     var varient_count=$('#varient_count').val(); 
     // if(varient_count>0)
     // {
      
     // }
     // else
     // {  
       // var go_ahead="n";
       // $('#varient_error').show(); 
     // }
	  if($(this).parent().is("#without_varient_footer")){
        // do nothing
      } else {
        var varient_count = $(".removevarient").length;
		// alert(varient_count);
        if(varient_count < 1)
        {  
            $('#varient_error').show(); 
            return false;
        }
      }
   }
   else   
   {
    var go_ahead="y";
   }
   if(go_ahead=="y")
   {
    var select_varient=$('#select_varient').val();
    //var p_price = $(this).data("pr");
    var p_price = $(this).data("pr");
    var single_remarks = $(this).siblings("input[name='single_ingredients']").val();
    var p_extra = $(this).siblings("input[name='extra']").val();
     $("input[name='single_ingredients']").val('');
     $(".element-item input[name='extra']").val('');
      $("#ProductModel").modal("hide");
      var sub_str=$("#varient").html();
	   var sub_count = $(".sub_pro_name").length;
         var f_html='';
      var f_html=$(".sub_pro_name")
  .map(function() {
    return $(this).attr('show_label');    
  })
  .get()  
  .join("</br>-");
	 if(sub_count>0)
      var name=$(this).data("name")+"</br>-"+f_html;
	else
	  var name=$(this).data("name");
      var s_id=$(this).data("id");
      var id=$(this).data("id")+last_added_id;
	  // alert(id);
	  var rebate_per=$(this).data("rebate");
      var child_id="child_"+id;
      var child_s_id="child_"+$(this).data("id");
      var product_child_s_id="product_child_"+$(this).data("id");
      var product_child_id="product_child_"+id;
      var extra_child_id="extra_child_"+id;
      var extra_price = 0;
      if(p_extra == ''){
        p_extra = 0;
      }else{
        p_extra = p_extra.split(",")
        for(var i = 0; i < p_extra.length; i++){
        extra_price += parseFloat(p_extra[i]);
        }
      }
      var p_pop_price = $("#p_pop_price").val();
	  var quantity=$(this).data("quantity");
      var product_price = (p_pop_price == "" || !p_pop_price) ? parseFloat($(this).data("pr")) : parseFloat(p_pop_price);
     var p_total = (product_price + extra_price).toFixed(2) * parseInt(quantity);
	 var p_total=p_total.toFixed(2);
	   var rebate_amount=rebatevalue(p_total,rebate_per);  
      $("#p_pop_price").val("");
	  // alert(rebate_amount);  
      console.log(p_price);
      console.log(p_extra);  
      console.log(p_total);
      
      var code=$(this).data("code");
      document.getElementById(child_s_id).classList.add("fa-plus");
      document.getElementById(child_s_id).classList.remove("fa-check");
      document.getElementById(product_child_s_id).style.backgroundColor = "#50d2b7";
      var varient_type=$("#varient_type").html();
      // alert(varient_type); 
    $("#test").append("<tr class='producttr'>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value="+rebate_amount+" id='"+id+"rebate_amount'><input type='hidden' name='rebate_per[]' value="+rebate_per+" id='"+id+"rebate_per'><input style='width:50px;'  onchange='UpdateTotal("+id+","+product_price+")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+s_id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' data-rid='"+id+"' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? "Remarks" : single_remarks) +  "</a><input type='hidden' id='"+extra_child_id+"' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id='"+extra_child_id+"' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='"+product_price+"' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= "+ p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value="+varient_type+"></td> </tr>");
     
	  // alert('The product added');
      $("#varient_type").html('');   
      $("#varient").html('');  
   }    
   else
   {
    $('#varient_error').show();
   }
   
  });
  // end product varient 
  var other_product_id = 1;
   $(".oth_pr").on("click", function(){
       $('html,body').animate({
        scrollTop: $("#cartsection").offset().top},
        'slow');  
           
    $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td><input style='width:120px;' type=text  id='other_product_name_"+other_product_id+"' class='other_product_name'><input type='hidden' name='p_id[]' id='other_product_id_"+other_product_id+"'></td> <td><input style='width:50px;' onchange='UpdateTotalCart("+other_product_id+")' id='other_qty_"+other_product_id+"' type=number name='qty[]' min='1' class='product_qty' value='1'></td> <td><input class='other_product_code' style='width:70px;' type= text name='p_code[]' id='other_product_code_"+other_product_id+"'><input type='hidden' name='ingredients'/></td><td> <a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='extra'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td><td><input style='width:70px;' id='other_product_price_"+other_product_id+"' type='text' name='p_price[]' readonly></td><td><input type='text' style='width:70px;' name='p_total[]' readonly  id='"+other_product_id+"_cat_total'></td></tr>");
      if(merchant_mobile!="60172669613")
  { 
   var focus_id="other_product_code_"+other_product_id;
  }
  else
  {
     var focus_id="other_product_name_"+other_product_id; 
  } 
    document.getElementById(focus_id).focus(); 
  other_product_id++;
    /* jQuery(".other_product_name").autocomplete({
      source: "auto_complete_product_name.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
     // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      }
    });
    jQuery(".other_product_name").keyup(function(e){
      var id = $(this).attr('id').split('_')[3];
      $("#other_product_id_"+id).val($(this).val());
    });
     jQuery(".other_product_code").autocomplete({
      source: "auto_complete_product_code.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
     // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
    $("#other_product_name_"+id).val(ui.item.name);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      }
    }); */
    // jQuery(".other_product_code").autocomplete({
      // source: "auto_complete_product_code.php",
      // minLength: 1,
      // select: function(event, ui) {
        // if(!isActive(ui.item.active_time)){
          // alert("This item is not active at the moment");
          // $(".other_product_code").val('');
        // }else{
          // if(ui.item.on_stock == 0){
            // alert("This item is not on stock");
          // }else{
            // var id = $(this).attr('id').split('_')[3];
            // var qty_id='other_qty_'+id;
            // var qty_no=document.getElementById(qty_id).value;
            // var total_cart=qty_no*(ui.item.price);
            // console.log(ui);
            // $("#other_product_id_"+id).val(ui.item.id);
            // $("#other_product_code_"+id).val(ui.item.code);
            // $("#other_product_name_"+id).val(ui.item.name);
            // $("#other_product_price_"+id).val(ui.item.price);
            // $("#other_product_remark_"+id).val(ui.item.remark);
            // var cart_id=id+"_cat_total";
            // document.getElementById(cart_id).value =total_cart;
          // }
        // }
      // }
    // });
       jQuery(".other_product_name").autocomplete({
      source: "auto_complete_product_name.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
     // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      },
      change: function(event, ui) {
      var id = $(this).attr('id').split('_')[3];
    var p_name='other_product_name_'+id;
    // alert(p_name);
    if (ui.item == null) {
      var p_name=document.getElementById(p_name).value;
      var res='';
            $.ajax({
                url: 'product_check.php',
               type:'POST',
        dataType : 'json',
         data:{p_name:p_name},
                success: function (res) {
          // var data = JSON.parse(JSON.stringify(data));
          // var o_status=data.status;
                    // console.log(data ? "true" : "false");
          
             if(res){
              var qty_id='other_qty_'+id;
              var qty_no=document.getElementById(qty_id).value;
              var total_cart=qty_no*(res.product_price);
              $("#other_product_id_"+id).val(res.id);
              $("#other_product_code_"+id).val(res.product_type);
              $("#other_product_name_"+id).val(res.product_name);
              $("#other_product_price_"+id).val(res.product_price);
              $("#other_product_remark_"+id).val(res.remark);
              var cart_id=id+"_cat_total";
              document.getElementById(cart_id).value =total_cart;
              
            }else{   
              alert('Select Product Name From  list or Enter Proper Product name');
              $("#other_product_name_"+id).val("");
            }
                    
                }
            });
      
       
    }
  }
    });
    jQuery(".other_product_name").keyup(function(e){
      var id = $(this).attr('id').split('_')[3];
      $("#other_product_id_"+id).val($(this).val());
    });
     jQuery(".other_product_code").autocomplete({
      source: "auto_complete_product_code.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
    var qty_id='other_qty_'+id;
    var qty_no=document.getElementById(qty_id).value;
     var total_cart=qty_no*(ui.item.price);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
    $("#other_product_name_"+id).val(ui.item.name);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
    document.getElementById(cart_id).value =total_cart;
      },
    change: function(event, ui) {
      var id = $(this).attr('id').split('_')[3];
    var pr_code='other_product_code_'+id;
    if (ui.item == null) {
      var pr_code=document.getElementById(pr_code).value;
      var res='';
            $.ajax({
                url: 'product_check.php',
               type:'POST',
        dataType : 'json',
         data:{pr_code:pr_code},
                success: function (res) {
          // var data = JSON.parse(JSON.stringify(data));
          // var o_status=data.status;
                    // console.log(data ? "true" : "false");
          
             if(res){
              var qty_id='other_qty_'+id;
              var qty_no=document.getElementById(qty_id).value;
              var total_cart=qty_no*(res.product_price);
              $("#other_product_id_"+id).val(res.id);
              $("#other_product_code_"+id).val(res.product_type);
              $("#other_product_name_"+id).val(res.product_name);
              $("#other_product_price_"+id).val(res.product_price);
              $("#other_product_remark_"+id).val(res.remark);
              var cart_id=id+"_cat_total";
              document.getElementById(cart_id).value =total_cart;
              
            }else{   
              alert('Select Code From Product Code list or Enter Proper code');
              $("#other_product_code_"+id).val("");
            }
                    
                }
            });
      
       
    }
  }
    });
  });
  
     jQuery(document).on('click', 'button.removebutton', function () {
         alert("Product has Removed");
         jQuery(this).closest('tr').remove();
         return false;
     });

     $()
  </script>

<style>
.category_filter{
    margin-bottom: 10px;
  padding: 8px;
}
.sub_category_grid{
    margin-top: 10px;
}
.other_products{
display:none;
}
.text_add_cart{
    background: #003A66;
    width: 120px;
    text-align: center;
    padding: 10px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-right: 8px;
    border-radius: 8px;
}
.text_add_cart_without{
    background: #003A66;
    width: 120px;
    text-align: center;
    padding: 10px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-right: 8px;
    border-radius: 8px;
}
.comm_prd {
    display: flex;
}
.oth_pr {
    background: #003A66;
    width: 80px;
    text-align: center;
    margin: 10px 10px;
    padding: 10px;
    color: #fff;
    font-size: 20px;
    text-transform: uppercase;
    font-weight: 500;
    cursor: pointer;
    border-radius: 8px;
}
.total_rat_abt {
    font-size: 17px;
    display:flex;
}
.rating_menuss {
   padding: 5px;
    margin-right: 25px;
    padding: 5px;
    background: #00736A;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    border-radius: 10px;
}
.about_uss {
    padding: 7px!important;
    margin-right: 25px;
    padding: 5px;
    background: #00736A;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    border-radius: 10px;

}

a.merchant_about {
    color: #fff;
}
a.merchant_ratings {
    color: #fff;
}
p.no_stock_add_to_cart{
  width: 100%;
  padding: 15px;
  text-align: center;
  background-color: #fff;
}
.no_stock_add_to_cart{
  border-radius: 5px;
  color: #f92d2d;
  font-weight: bold;
  box-sizing: border-box;
}
.container_test.out_of_stock{
  position: relative;
}
.container_test.out_of_stock:after{
  content:'';
  position: absolute;
  height: 100%;
  width: 100%;
  z-index: 10;
  top: 0;
  left: 0;
  background-image: url(images/out-of-stock.png);
  background-repeat: no-repeat;
  background-size: 75%;
  background-position: left top;
}
.container_test.not_available{
  position: relative;
}
.container_test.not_available:after{
  content:'';
  position: absolute;
  height: 100%;
  width: 100%;
  z-index: 10;
  left: 0;
  top: 0;
  background-image: url(images/no-available.png);
  background-repeat: no-repeat;
  background-size: 80%;
  background-position: center;
}
</style>
<script>
  var showModal = true;
function toSeconds(date){
  var a = date.split(':'); 

  return parseInt(a[0]) * 3600 + parseInt(a[1]) * 60; 

}
function isActive(date){ // Checks if a product is active by date
  if(date == 1){
    return true;
  }
  var dateObj = JSON.parse(date);
  console.log(dateObj);
  var currentDate = new Date();
  var currentDay = parseInt(currentDate.getDay()) + 1;
  var currentHours = currentDate.getHours() + ":" + currentDate.getMinutes();
  console.log("Current Day: " + currentDay);
  console.log("Current Hours: " + currentHours);
  for(var i = 0; i < dateObj.length; i++){

    var daysOnObj = dateObj[i]['days'].split("-");

    if(daysOnObj.includes(currentDay.toString())){

      var start_seconds = toSeconds(dateObj[i]['start']);
      console.log(start_seconds);
      var end_seconds = toSeconds(dateObj[i]['end']);
      console.log(end_seconds);
      var current_seconds = toSeconds(currentHours);
      console.log(current_seconds);
      if(start_seconds < current_seconds && current_seconds < end_seconds){
        return true;
      }

    }

  }
    return false;
}

$('.make_bigger').click(function() {
  //~ $('.active').not(this).addClass('non_active');
  $('.active').not(this).removeClass('active');
  if ($(this).hasClass('active')) {
    $(this).removeClass('active');
  } else {
    $(this).removeClass('non_active');
    $(this).addClass('active');
  }
});
// init Isotope
var $grid_sub = $('.sub_category_grid').isotope({
    // options
    layoutMode: 'fitRows'
});
var $grid = $('.grid').isotope({
  // options
});
var menu_type='<?php echo $merchant_detail['menu_type'];?>';
if(menu_type==1)
{
var master_filter='.'+'<?php echo $master_cat;?>';
$grid.isotope({ filter:master_filter });
}
// filter items on button click
$('.master_category_filter').on( 'click', function(e) {
    e.preventDefault();
    var filterValue = $(this).attr('data-filter');

    $grid_sub.on( 'arrangeComplete', function ( event, filteredItems) {
        console.log(event, filteredItems);
        // $(filteredItems[0].element).find('button').trigger('click');
        console.log('am called');
    });

    $grid_sub.isotope({ filter: filterValue });




  var menu_type='<?php echo $merchant_detail['menu_type'];?>';
    
        var filterValue = $(this).attr('data-filter');
        var position_value = $(this).attr('data-position');
    
        $("#without_table tbody").html("");  
    // alert(position_value);
    // alert(menu_type);
       
    if(menu_type==1)
    {
      var data = {type:"mainclick",method:"getImageProduct", id: <?php echo $id;?>, category:position_value};
        $(".new_grid").html("");
      $.ajax({
             url:"functions.php",
             type:"post",
             data:data,
             dataType:'json',
             success:function(result){
                // console.log("Resultado:");
                // console.log(result);
                var html="";
                for(var i = 0; i < result.length; i++){
                    html += "<div class='well col-md-4 element-item Cham鸳鸯'>";
          html += " <form action='product_view.php' method='post' class='set_calss input-has-value' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' style='background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);'>";
          if(isActive(result[i]['active_time'])){
            if(result[i]['on_stock'] == 1){
              html += " <div class='container_test'>";      
            }else{
              html += " <div class='container_test out_of_stock'>";      
            }
          }else{
              html += " <div class='container_test not_available'>";      
          }
          html +="<img src='<?php echo $site_url; ?>/images/product_images/"+result[i]['image']+"' class='make_bigger' width='100%' height='150px'>";
                    // html += "<td>"+result[i]['product_name']+"</td>";
                    // html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";
                    // html += "<td>"+result[i]['price']+"</td>";
                    // html += "<td>"+result[i]['remark']+"</td>";
                    // html += "<td>"+result[i]['type']+"</td>";
                    html += "</div>";
                    html += "<input type='hidden' id='id' name='m_id' value='"+result[i]['user_id']+"'>";
                    html += "<input type='hidden' id='id' name='p_id' value='"+result[i]['id']+"'>";
                    html += "<p class='pro_name'>"+result[i]['product_name']+"</p>";
          html += "<p class='mBt10'></p>";
          html += "<p class='mBt10'></p>Price : Rm"+result[i]['price']+"<p></p>";
                    html += "<div class='common_quant'>";
          //html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' data-name='Carlsberg Smooth (B)X3'>Add to Cart</p>";
          if(isActive(result[i]['active_time'])){
            if(result[i]['on_stock'] == 1){
              html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to Cart</p>";
              html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";
              html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";
            }else{
              html += "<p class='no_stock_add_to_cart'>Out of stock</p>";
            }
          }else{
              html += "<p class='no_stock_add_to_cart'>This product is not available in this moment</p>";
          }



           
          html += "<p class='quantity'> </p>";
                    html += "</div>";
                    html += "</div>";
                    html += "</form>";
                    html += "</div>";
                }
                $(".new_grid").html(html);
      $(".text_add_cart").on("click", function(){
        if($(this).parent().hasClass("save_close")){
          return false;
        }
        var p_extra = $(this).parent().parent().find("input[name='extra']");
        var id = $(this).data("id");
        var code = $(this).data("code");
        var p_price = $(this).data("pr");
        var name = $(this).data("name");
        var quantity = $(this).closest("form").find("input[name='quatity']").val();
        var single_remarks = $(this).parent().parent().find("input[name='single_ingredients']").val();
        var p_total = p_price*quantity;
      p_total = p_total.toFixed(2);
        
        $("#p_pop_price").val(p_total);  
         $("#product_table").append("<tr><td> "+name+" </td><td> "+p_total+" </td></tr>");  
       $("#pr_total").html("<b>"+p_total+"</b>");
      // $("#product_info").html("<p>"+name+": Rm "+p_total+"</p>");
      $(".pop_model").html("<a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? "Remarks" : single_remarks) + "</a><input type='hidden' name='extra' value='" + p_extra + "'/><input type='hidden' name='single_ingredients' value='" + single_remarks + "'/><span id='pop_cart' class='product_button' data-id='"+id+"' data-code='"+ code +"'  data-name='"+name+"' data-quantity='"+quantity+"'>Add to Cart</span>");
        
      for(var i = 0; i < subproducts_global.length; i++){
        for(var j = 0; j < subproducts_global[i].length; j++){
          if(subproducts_global[i][j]['product_id'] == id){
            subproduct_selected = subproducts_global[i];
            break;
            // console.log(subproducts_global[i][j]['product_id']);
          }
        }
      }
      console.log(subproduct_selected);
      var exists_in_subproducts = false;
      for(var i = 0; i < subproduct_selected.length; i++){
        if(subproduct_selected[i]['product_id'] == id){
          exists_in_subproducts = true;
          break;
        }
      }
      if(exists_in_subproducts){

        var content = '';
        for(var i = 0; i < subproduct_selected.length; i++){
          
          content +="<div  id='prodct_cart_"+subproduct_selected[i]['id']+"' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' class='ingredient product_cart'>";
          content +="<button type='button' class='btn btn-info remove-ingredient' data-name='"+subproduct_selected[i]['name']+"' data-id='"+subproduct_selected[i]['id']+"' data-price='"+subproduct_selected[i]['product_price']+"' aria-label='Close'>";
          content +="<span aria-hidden='true'><i class='fa fa-plus'></i></span>";
          content +="</button><span class='ingredient-name'>"+subproduct_selected[i]['name']+" &nbsp; Price Rm "+subproduct_selected[i]['product_price']+"</span></div>";
          console.log(content);
          
        }

        $("#product_main").html(content);
        $("#ProductModel").modal("show");

      }
        
      });
                
             }
         });
    
     var data = {type:"mainclick",method:"getNoneImageProduct", id: <?php echo $id;?>, category: position_value};
        $.ajax({
             url:"functions.php",
             type:"post",
             data:data,
             dataType:'json',
             success:function(result){
              console.log("Resultado:");
              console.log(result);
                var html="";
                for(var i = 0; i < result.length; i++){
                    html += "<tr>";
                    html += "<td>"+(i + 1)+"</td>";
                    html += "<td>"+result[i]['product_name']+"</td>";
                    if(isActive(result[i]['active_time'])){
                      if(result[i]['on_stock'] == 1){
                        html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";
                      }else{
                        html += "<td class='no_stock_add_to_cart'>Out of stock</td>";
                      }
                    }else{
                        html += "<td class='no_stock_add_to_cart'>Not available</td>";
                    }
                    html += "<td>"+result[i]['price']+"</td>";
                    html += "<td>"+result[i]['remark']+"</td>";
                    html += "<td>"+result[i]['type']+"</td>";
                    html += "</tr>";
                }
                $("#without_table tbody").html(html);
  $(".text_add_cart_without").on("click", function(){
    var id = $(this).data("id");
    //~ alert(id);
    var code = $(this).data("code");
    //~ alert(code);
    var p_price = $(this).data("pr");
    //~ alert(p_price);
    var name = $(this).data("name");
    // alert(name);
    var quantity = 1 ;
    //alert(quantity) ;
    if(quantity ==''){
        
        var quantity = 1 ;
    }
    var p_total = p_price *quantity ;
    p_total = p_total.toFixed(2);

    $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;' maxlength='3'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]' class='product_qty' value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='extra'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= "+p_total+" readonly  id='"+id+"_cat_total'></td> </tr>");
    alert('The product added');
  }); 
                
             }
         });
     }
  
});


$('.sub_category_grid .category_filter button').on( 'click',function() {
      var filterValue = $(this).attr('data-filter');
      var subcateg_show = $(this).data("subcategory");
      console.log(filterValue);
      console.log(subcateg_show);
      $("#remarks_area .modal-body .btn-group").each(function(){
        if($(this).data("subcategory") == subcateg_show || $(this).data("subcategory") == "all"){
          $(this).show();
        }else{
          $(this).hide();
        }
      });
      $grid.isotope({ filter: filterValue });
});

</script>
<style>
.sub_category_grid button{ /* You Can Name it what you want*/
margin-right:10px;
}
.sub_category_grid button:last-child{
margin-right:0px;
/*so the last one dont push the div thas giving the space only between the inputs*/
}
img.active {
  animation: make_bigger 1s ease;
  width: 600px;
  height: 400px;
}

img.non_active {
  animation: make_smaller 1s ease;
  width: 127px;
  height: 128px;
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
form.set_calss.input-has-value {
<!--
    width: 50%;
-->
    width: 173px;
}
.about_uss {
    width: 165px;
}
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
input.btn.btn-block.btn-primary.submit_button {
    width: 100%!important;
}
.common_quant {
    display: block;
}
form.set_calss.input-has-value {
    width: 100%;
    width: 170px;
    margin-left: -20px;
}
.grid.row {
    margin-left: 18px;
}
/*.pro_name {
    height: 130px;
}*/
img.make_bigger {
    height: 100px;
}
}
@media only screen and (max-width: 800px) and (min-width: 750px)  {
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
.common_quant {
    display: block;
}
}
.col-md-4{
  max-width: 100% !important;
}
.well.col-md-4{
  padding: 0 !important;
}

</style>

<script>
$(document).ready(function(){
     
	 $("#cartmove").click(function(){
		 // alert(3);
		// $("#table_type").focus();
		 setTimeout(function() { $('input[name="table_type"]').focus() },1000);
	  });
   $('#test').html();
    var already_login='<?php echo $login_user_id; ?>';
   if(already_login)
    {
	   var local_id=localStorage.getItem("login_cache_id");
		if(local_id=='' || local_id==null)
		{
			localStorage.setItem('login_cache_id','<?php echo $login_user_id;?>');
			localStorage.setItem('login_role_id','<?php echo "1";?>');
		}
	}
   var p_status='<?php echo $p_status; ?>';
   var o_done='<?php echo $o_done; ?>';
	if(o_done=="done")
  {
	var msg="Order sent";  
	$('#show_msg').html(msg);
	$('#AlerModel').modal('show'); 
	setTimeout(function(){ $("#AlerModel").modal("hide"); },3000);  
  }
  var location_order='<?php echo $location_order; ?>';
  var shop_status=$('#shop_status').val();
  
if(p_status=="paypalcancel")
{
	$('#paypal_model').modal('show');
}
  if(location_order==1)
  {
    
    $('.pro_status').hide();
     $( "#confm" ).prop( "disabled", true );
     $( "#confmpayment" ).prop( "disabled", true );   
  }
  // alert(shop_status);
  if(shop_status==0)
  {
    $('.pro_status').hide();
     $( "#confm" ).prop( "disabled", true );
     $( "#confmpayment" ).prop( "disabled", true ); 
    $('#error_label').html("Sorry, we are currently experiencing some internet connection issue. If you want to place any order, please contact our waiter for placing order.");
    $('#shop_model').modal('show');
    exit;
  }
  
  $("button.introduce-remarks").prop("disabled", false);
  $("#merchant_message").delay(parseInt(<?php echo (isset(json_decode($bank_data['custom_message'])->time)) ?  json_decode($bank_data['custom_message'])->time : 5; ?>) * 1000).queue(function(nxt){
    $(".modal.in").removeClass("in").addClass("fade").hide();
    $(".modal-backdrop").remove();
    // $("#merchant_message").modal("hide");
    nxt();
  });
      var prev_number = '';
    $(".credentials-container input[name='mobile_number']").on("keyup", function(){
      var prefix = "+91";
      var number = $(this).val();
      // console.log("Previous number: " + prev_number);
      // console.log("Current number: " + (prefix + number));
      if(prev_number == prefix+number){
        return false;
      }
      prev_number = prefix+number;
    var guest_permission=<?php echo $merchant_detail['guest_permission']; ?>; 
    // alert(guest_permission);
    if(guest_permission==0)
    {
      if(number.length >= 9 && number.length <= 10 && number[0] == 1){
         $('#mobile_error').hide();
       $("input.submit_button").prop("disabled",false);
       $("input.submit_payment_button").prop("disabled",false);
       $("input[name='mobile_number']").addClass("is-valid").removeClass("is-invalid");
       
      }else{
       $("input.submit_button").prop("disabled",true);
       $("input.submit_payment_button").prop("disabled",true);
         $("input[name='mobile_number']").removeClass("is-valid").addClass("is-invalid");
       $('#mobile_error').show();
       
      }
    }
    });
  
  // $('#confm').click(function(){
       // $('#order_place').attr('action', 'order_cash.php');
       
         
        
    // });


    $('#confmpayment').click(function(){
       $('#order_place').attr('action', 'order_place.php');
       
    });
    $("body").on("click","#login_passwd", function(e){
      var phone_num = $(this).parent().parent().find("input[name='mobile_number']").val();
      $("#login_phone_number").val("+60" + phone_num);
      e.preventDefault();
    });
    var latitude = 0;
    var longitude = 0;
    
	  if(location_order==1)
	{
  navigator.geolocation.watchPosition(function(position) {
    // alert("i'm tracking you!");
    },
    function(error) {
    if (error.code == error.PERMISSION_DENIED)
    {
      // alert('Permission Not given');
      if(location_order==1)
      {
        $("#error_label").html("This merchant require your permission for location in order to place order");
        $('#location_model').modal('show');
      }
    }
    });
    navigator.geolocation.getCurrentPosition(function(position) {
       $('#location_model').modal('hide');
            console.log("current location");
           latitude = position.coords.latitude;
           longitude = position.coords.longitude;
      // latitude=0;
         codeLatLng(latitude, longitude);
       if(latitude>0 && longitude>0)
       {
         $('#location_model').modal('hide');
         $('#user_lat').val(latitude);
         $('#user_long').val(longitude);
         calculatedisatace(latitude,longitude);
        
           // $( "#confm" ).prop( "disabled", true );
          // alert(latitude);
       }
       else
       {
          // $("#error_label").html("Sorry, To Place order Location Permission is needed ");
         // $('#location_model').modal('show');
         
       }
           // getFavorite("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));
           //getNearbyRestaurant("Foods and Beverage, such as restaurants, healthy foods, franchise, etc", $(".business_type").attr('user_id'));
        });
	}
  
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
  
    $("button[name='login_ajax']").on("click",function(e){
      var phone_num = $("#login_phone_number").val();
      var passwd = $("#login_password").val();
      var passwd_length_valid = (passwd.length > 2) ? true : false;
      $(this).prop("disabled",true);
      if(passwd_length_valid){
        $.post("./login.php", {
          mobile_phone: phone_num,
          password: passwd
        }, function(data,result){
          
          console.log("Data:");
          console.log(data);
          var status_code = (data == "logged-in") ? 4 : (data == "acc-locked") ? 3 : (data == "reg_pending") ? 2 : (data == 1) ? 1 : 0 ;
          console.log(status_code);
          if(status_code == 0){
            $("#login_password").rFemoveClass("is-valid").addClass("is-invalid");
            $(".third_problem.logged-in.success_login,.reg_pending,.acc_blocked").hide();
            $(".wrong_login").show();
          }else if(status_code == 1){
            $("#login_password").removeClass("is-invalid").addClass("is-valid");
            $(".third_problem.logged-in.wrong_login,.reg_pending,.acc_blocked").hide();
            $(".success_login").show();
            $(".credentials-container").remove();
            $("form[action='guest_user.php']").attr("action","order_place.php");
          }else if(status_code == 2){
            $("#login_password").removeClass("is-valid").addClass("is-invalid");
            $(".third_problem.logged-in.success_login,.acc_blocked,.wrong_login").hide();
            $(".reg_pending").show();
          }else if(status_code == 3){
            $("#login_password").removeClass("is-valid").addClass("is-invalid");
            $(".third_problem.logged-in.success_login,.reg_pending,.wrong_login").hide();
            $(".acc_blocked").show();
          }else if(status_code == 4){
            $("#login_password").removeClass("is-valid").addClass("is-invalid");
            $(".third_problem.acc_blocked.success_login,.reg_pending,.wrong_login").hide();
            $(".logged-in").show();
          }else{
            $("#login_password").removeClass("is-valid").addClass("is-invalid");
            $(".logged-in.acc_blocked.success_login,.reg_pending,.wrong_login").hide();
            $(".third_problem").show();
          }
        })
      }else{
        if(passwd.length == 0){
          alert("You have to enter a password");
        }else{
          alert("The password is too short");
        }
      }
      e.preventDefault();
    })

    //$('.master_category_filter:first-child').trigger('click');
    $('.sub_category_grid .category_filter:first-child button').trigger('click');
    //$('.filter-button-group .category_filter:first-child').trigger('click');
  var menu_type='<?php echo $merchant_detail['menu_type'];?>';
  if(menu_type==2){
        $('.master_category_filter:first-child').trigger('click');
    }

    $(".category_filter").click(function(e){
    var menu_type='<?php echo $merchant_detail['menu_type'];?>';
    
        var filterValue = $(this).attr('data-filter');
    
        $("#without_table tbody").html("");  
    
       
    if(menu_type==1)
    {
      var data = {method:"getImageProduct", id: <?php echo $id;?>, category:''};
        $(".new_grid").html("");
      $.ajax({
             url:"functions.php",
             type:"post",
             data:data,
             dataType:'json',
             success:function(result){
              console.log(result);
                var html="";
        
                for(var i = 0; i < result.length; i++){
                    html += "<div class='well col-md-4 element-item Cham鸳鸯'>";
          html += " <form action='product_view.php' method='post' class='set_calss input-has-value' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' style='background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);'>";
          if(isActive(result[i]['active_time'])){
            if(result[i]['on_stock'] == 1){
              html += " <div class='container_test'>";      
            }else{
              html += " <div class='container_test out_of_stock'>";      
            }
          }else{
              html += " <div class='container_test not_available'>";      
          }
          html +="<img src='<?php echo $site_url; ?>/images/product_images/"+result[i]['image']+"' class='make_bigger' width='100%' height='150px'>";
                    // html += "<td>"+result[i]['product_name']+"</td>";
                    // html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";
                    // html += "<td>"+result[i]['price']+"</td>";
                    // html += "<td>"+result[i]['remark']+"</td>";
                    // html += "<td>"+result[i]['type']+"</td>";
                    html += "</div>";
                    html += "<input type='hidden' id='id' name='m_id' value='"+result[i]['user_id']+"'>";
                    html += "<input type='hidden' id='id' name='p_id' value='"+result[i]['id']+"'>";
                    html += "<p class='pro_name'>"+result[i]['product_name']+"</p>";
          html += "<p class='mBt10'></p>";
          html += "<p class='mBt10'></p>Price : Rm"+result[i]['price']+"<p></p>";
                    html += "<div class='common_quant'>";
          //html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='C005' data-pr='39' data-name='Carlsberg Smooth (B)X3'>Add to Cart</p>";
          if(isActive(result[i]['active_time'])){
            if(result[i]['on_stock'] == 1){
              html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to Cart</p>";
              html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";
              html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";
            }else{
              html += "<p class='no_stock_add_to_cart'>Out of stock</p>";
            }
          }else{
              html += "<p class='no_stock_add_to_cart'>This product is not available in this moment</p>";
          }
       //    if(result[i]['on_stock'] == 1){
            // html += "<p class='text_add_cart' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to Cart</p>";
       //      html += "<div style='display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;' class='input-has-value'>";
            // html += " <label>X</label><input type='number' value='1' class='quatity' name='quatity' style='height:1.5em'>";
       //    }else{
       //      html += "<p class='no_stock_add_to_cart'>Out of stock</p>";
       //    }

          html += "<p class='quantity'> </p>";
                    html += "</div>";
                    html += "</div>";
                    html += "</form>";
                    html += "</div>";
                }
                $(".new_grid").html(html);
      $(".text_add_cart").on("click", function(){
        var id = $(this).data("id");
        var code = $(this).data("code");
        var p_price = $(this).data("pr");
        var name = $(this).data("name");
        var quantity = $(this).closest("form").find("input[name='quatity']").val();
        var p_extra = $(this).parent().parent().find("input[name='extra']").val();
        var p_total = p_price*quantity;
        p_total = p_total.toFixed(2);
        
        $("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]'  min='1' class='product_qty' maxlength='3'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a><input type='hidden' name='extra' value='" + p_extra + "'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' value='0' readonly></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= "+p_total+" readonly  id='"+id+"_cat_total'></td> </tr>");
        alert('The product added');
      });
                
             }
         });
    }
     var data = {method:"getNoneImageProduct", id: <?php echo $id;?>, category: ''};
        $.ajax({
             url:"functions.php",
             type:"post",
             data:data,
             dataType:'json',
             success:function(result){
                var html="";
                for(var i = 0; i < result.length; i++){
                    html += "<tr>";
                    html += "<td>"+(i + 1)+"</td>";
                    html += "<td>"+result[i]['product_name']+"</td>";
                    if(isActive(result[i]['active_time'])){
                      if(result[i]['on_stock'] == 1){
                        html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";
                      }else{
                        html += "<td class='no_stock_add_to_cart'>Out of stock</td>";
                      }
                    }else{
                        html += "<td class='no_stock_add_to_cart'>Not available</td>";
                    }
                    html += "<td>"+result[i]['price']+"</td>";
                    html += "<td>"+result[i]['remark']+"</td>";
                    html += "<td>"+result[i]['type']+"</td>";
                    html += "</tr>";
                }
                $("#without_table tbody").html(html);
                $(".text_add_cart_without").on("click", function(){
    var id = $(this).data("id");
    //~ alert(id);
    var code = $(this).data("code");
    //~ alert(code);
    var p_price = $(this).data("pr");
    //~ alert(p_price);
    var name = $(this).data("name");
    // alert(name);
    var quantity = 1 ;
    //alert(quantity) ;
    if(quantity ==''){
        
        var quantity = 1 ;
    }
    var p_total = p_price *quantity ;
    p_total = p_total.toFixed(2);
	 $("#test").append("<tr class='producttr'>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value="+rebate_amount+" id='"+id+"rebate_amount'><input type='hidden' name='rebate_per[]' value="+rebate_per+" id='"+id+"rebate_per'><input style='width:50px;'  onchange='UpdateTotal("+id+","+product_price+")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+s_id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients' value='" + single_remarks + "'/></td><td>"+code+"</td><td><a href='#remarks_area' data-rid='"+id+"' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>" + ((single_remarks == '') ? "Remarks" : single_remarks) +  "</a><input type='hidden' id='"+extra_child_id+"' name='extra' value='"+extra_price+"'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id='"+extra_child_id+"' value='" + extra_price.toFixed(2) + "' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='"+product_price+"' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= "+ p_total + " readonly  id='"+id+"_cat_total'><input type='hidden' name='varient_type[]' value="+varient_type+"></td> </tr>");
       alert('The product added');
  });
                
             }
         });
    });
	$("#confm").click(function(e){
		 var product_qty=$('.product_qty').val();
		 var table_type=$('#table_type').val();
		 var number=$('#mobile_number').val();
		
		 var total_rebate=0;
		 var total_amount=0;
		var s_flag=true;
		if ((product_qty == null) || (product_qty=='')){
			// alert('Without Prouct add cant able to go ahead.');
			$('#show_msg').html("Without Prouct add cant able to go ahead");
			 $('#AlerModel').modal('show');
			
			var s_flag=false;
			return false;  
		  }
		  if(number.length >= 9 && number.length <= 10 && number[0] == 1){
		  }
		  else
		  {
			   var s_flag=false;
		  }
		  if($('#table_type').prop('required')){
			  if(table_type=='')
			  {
				  $('#table_type').focus();
				var s_flag=false;
				// return false;
			  }
		  }
		  
		   if(s_flag)
		   {
			    // alert(3);
			    $(".rebate_amount").each(function(){
				   // var total_rebate+= $(this).val();
				   total_rebate += parseFloat($(this).val());
			   });
			   $(".p_total").each(function(){
				   // var total_rebate+= $(this).val();
				   total_amount += parseFloat($(this).val());
			   });
			   // alert(total_amount);
			   var total_amount=total_amount.toFixed(2);
			     var total_rebate=total_rebate.toFixed(2);
			   $('#total_rebate_amount').val(total_rebate);
			   $('#total_cart_amount').val(total_amount);
			   $('#total_cart_amount_label').html(total_amount);
				$('input[type=submit]', this).attr('disabled', 'disabled');
				$(this).removeClass(" btn-primary").addClass("btn-default");
		   }
	});

  // $('form').submit(function(){
    // $('input[type=submit]', this).attr('disabled', 'disabled');
// });

  // $('#order_place').submit(function () {
    // var product_qty=$('.product_qty').val();
    // var s_flag=true;
    // if ((product_qty == null) || (product_qty=='')){
        // alert('Without Prouct add cant able to go ahead.');
        // var s_flag=false;
        // return false;
      // }
      
      // if(s_flag)
      // $('input[type=submit]', this).attr('disabled', 'disabled');
        // });
	// code for rebeat process by mayank 
	  $(".login_ajax").click(function(){
		   // $(this).removeClass(" btn-primary").addClass("btn-default");
		 // setTimeout(function() {
			
         // $(this).removeClass("btn-default").addClass("btn-primary");
			// }.bind(this), 5000);
		    var usermobile="60"+$("#mobile_number").val();
		    var login_password=$("#login_ajax_password").val();
		    var wallet_selected=$("#wallet_selected").val();
			    
			 $("#login_error").hide();
			if((login_password.length)>5)
			{
				   $(this).removeClass(" btn-primary").addClass("btn-default");
				  // alert(login_password);
					var data = {usermobile:usermobile,login_password:login_password};
					// alert(data);
					$.ajax({
					  
					  url :'login.php',
					  type:'POST',
					  dataType : 'json',
					  data:data,
					  success:function(response){
							var data = JSON.parse(JSON.stringify(response));
						  if(data.status==true)
						  {
							  $('#login_process').hide();
							 $('#with_wallet').hide(); 
							 $('#without_wallet').hide(); 
							  $('.join_now').hide();
							 walletstep();
						  }
						  else
						  {
							  $("#login_ajax_password").val('');
							   
							   $("#login_error").html('Your password is incorrect.Please try with correct password again or Reset that with Forgot Password');
							  $("#login_error").show();
						  }
						  
						}		  
				  });
		  
			}
			else
			{    $("#login_error").html('Enter Atleaset 6 digit password to make login');
				 $("#login_error").show();
			}
		 
	   });
	   $(".forgot_reset").click(function(){
		    $(this).removeClass(" btn-primary").addClass("btn-default");
		 setTimeout(function() {
			
         $(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
		   var usermobile=$("#user_mobile").val();
		   // alert(usermobile);
		   var data = {usermobile:usermobile,method:"forgotpass2"};
			// alert(data);
			$.ajax({
			  
			  url :'functions.php',
			  type:'POST',
			  dataType : 'json',
			  data:data,  
			  success:function(response){
				  var data = JSON.parse(JSON.stringify(response));
				  // alert(data.status);
				  if(data.status)
				  {  
						$('#forgot_error').css('color', 'black');
					   $("#forgot_error").html(data.msg);
					   
					  $("#forgot_error").show();
					  
					  $(".forgot_now ").hide();
				  }
				  else
				  {
					  $(".forgot_reset").show();
					  $("#forgot_error").html(data.msg);
					  $("#forgot_error").show();
					  $(".forgot_now ").hide();
				  }
				  
				}		  
		  });
	   });
		$(".forgot_pass").click(function(){
				$('#with_wallet').hide();
				$('#without_wallet').hide();
				$('.login_passwd_field').hide();
			   $(".join_now").hide();
			   $(".forgot_reset").show();
			   $(".forgot_now").show();
			  $('.forgot-form').show();
		  });  
		  
		  // paypal pay button click 
		$(".paypal_pay").click(function(e){
			 e.preventDefault();
			var product_qty=$('.product_qty').val();
			var table_type=$('#table_type').val();
			var number=$('#mobile_number').val();
			var total_rebate=0;
			var total_amount=0;
			var s_flag=true;
			if ((product_qty == null) || (product_qty=='')){
			// alert('Without Prouct add cant able to go ahead.');
			$('#show_msg').html("Without Prouct add cant able to go ahead");
			 $('#AlerModel').modal('show');
			var s_flag=false;
			return false;  
			}
			if(number.length >= 9 && number.length <= 10 && number[0] == 1){
			}
			else
			{
				$('#mobile_number').focus();
			   var s_flag=false;
			}
			if($('#table_type').prop('required')){
			  if(table_type=='')
			  {
				  $('#table_type').focus();
				var s_flag=false;
				// return false;
			  }
		  }
		  if(s_flag)
		  {
			
			   $(".p_total").each(function(){
				   // var total_rebate+= $(this).val();
				   total_amount += parseFloat($(this).val());
			   });
			   // alert(total_amount);
			    var total_amount=total_amount.toFixed(2);
			   	$('#paypal_amount').val(total_amount);   
			  
				$('#total_cart_amount').val(total_amount);
			
			  $.ajax({
				type: 'post',
				  dataType : 'json',
				url: 'order_payal_cash.php',
				data: $('#order_place').serialize(),
				success: function (response) {
					var data = JSON.parse(JSON.stringify(response));
					if(data.status==true)
					{
						$('.paypal_pay').hide();
						if(data.order_id)
						{
							// alert(data.order_id);
							$('#item_order_id').val(data.order_id);
							$('#paypal_form').submit();
						}
						else
						{
							alert(data.msg);
						}
						
					}
					else
					{
						alert(data.msg);
					}
				 
				}
			  });
		     // alert('Ready To take payment'); 
		  }
		  return false;
		  if(s_flag)
		  $('input[type=submit]', this).attr('disabled', 'disabled');
		
	 });
		  $(".online_pay").click(function(e){
			 
		 var product_qty=$('.product_qty').val();
		  var table_type=$('#table_type').val();
		 var number=$('#mobile_number').val();
		 var total_rebate=0;
		 var total_amount=0;
		var s_flag=true;
		if ((product_qty == null) || (product_qty=='')){
			// alert('Without Prouct add cant able to go ahead.');
			$('#show_msg').html("Without Prouct add cant able to go ahead");
			 $('#AlerModel').modal('show');
			
			var s_flag=false;
			return false;  
		  }
		  if(number.length >= 9 && number.length <= 10 && number[0] == 1){
		  }
		  else
		  {
			  $('#mobile_number').focus();
			   var s_flag=false;
		  }
		  if($('#table_type').prop('required')){
			  if(table_type=='')
			  {
				  $('#table_type').focus();
				var s_flag=false;
				// return false;
			  }
		  }
		     
		  if(s_flag)
		  {
			 
			   $(".rebate_amount").each(function(){
				   // var total_rebate+= $(this).val();
				   total_rebate += parseFloat($(this).val());
			   });
			   
			   $(".p_total").each(function(){
				   // var total_rebate+= $(this).val();
				   total_amount += parseFloat($(this).val());
			   });
			   // alert(total_rebate);
			   var total_amount=total_amount.toFixed(2);
			   if(total_rebate>0)
			   {
				   $('#without_wallet').hide();
				   // if(total_rebate>10)
				   // {
					   // var total_rebate=10;
				   // }
				   $('.rebate_amount_label').html(total_rebate);
				  $('.rebate_label').show(); 
					if(!already_login)
					{
						 $('#with_wallet').show();
						// $('#without_wallet').show(); 
					}
				  // $('#without_wallet').hide(); 
			   }
			   else
			   {
				   $('#with_wallet').hide();
					if(!already_login)
					{  
						$('#without_wallet').show();
					}
					
			   }
			    var total_rebate=total_rebate.toFixed(2);
			   $('#total_rebate_amount').val(total_rebate);
			   $('#total_cart_amount').val(total_amount);
			   $('#total_cart_amount_label').html(total_amount);
			  
			  // alert(already_login);
			 if(!already_login)
			{
				var mobile_number=$('#mobile_number').val();
				var merchant_id='<?php echo $id; ?>';
				var special_coin_name='<?php echo $special_coin_name; ?>';
				if(mobile_number)
				{
				// alert(mobile_number);
				 $.ajax({
					url: 'functions.php',
					type:'POST',
					dataType : 'json',
					data:{mobile_number:mobile_number,method:"usercheck",merchant_id:merchant_id,special_coin_name:special_coin_name},
					success: function (res) {
					var data = JSON.parse(JSON.stringify(res));
					// alert(JSON.stringify(data));
					// alert(data.status);
						if(data.status)
						{
							
							$('.online_pay').removeClass(" btn-primary").addClass("btn-default");
							$('#myr_bal').html(data.data.balance_myr);
							$('#usd_bal').html(data.data.balance_usd);
							$('#inr_bal').html(data.data.balance_inr);
							$('#special_bal').html(data.data.balance_special);
							$('#myr_input_bal').val(data.data.balance_myr);
							$('#usd_input_bal').val(data.data.balance_usd);
							$('#inr_input_bal').val(data.data.balance_inr);
							$('#special_input_bal').val(data.data.balance_special);
							$('#newuser_model').modal('show');
							$('.forgot-form').hide();
							$('.forgot_reset').hide();
							$('.login_passwd_field').show();
							$('.join_now').show();   
							$('.login_ajax').show();
						}
						else
						{
							$('#WalletModel').modal('show');
							$("#order_place").submit();
							
							// alert('Wallet Feature is Only Applicale for Register Member,We are Processing as Cash Wallet');
							
						}
						
					}
				});
				}
				else
				{
					$('#mobile_number').foucus();
					alert('Phone number is Required');
				}
				
				 // $(".forgot-form").show();
			}
			else
			{
				$('#newuser_model').modal('show');
				$('#login_process').hide();
				$('.forgot-form').hide();
				$('.forgot_reset').hide();
				$('.login_passwd_field').show();
				$('.join_now').show();   
				$('.login_ajax').show();
				walletstep();
			} 
		  }
		  return false;
		  if(s_flag)
		  $('input[type=submit]', this).attr('disabled', 'disabled');
		
	 });
	 $(".wallet_select").click(function(){
		    var special_coin_name='<?php  echo $special_coin_name;?>';
			if(special_coin_name)
			{
				var special_coin_min='<?php  echo $special_coin_min;?>';
				var special_coin_max='<?php  echo $special_coin_max;?>';
				w_bal=$('#special_input_bal').val(); 
				var type =special_coin_name;
				var min_bal=special_coin_min;
				var max_bal=special_coin_max;
				var rebate_amount=0;
				var wallet_name=special_coin_name;
			}
			else
			{
				var type = $(this).attr('type');
				var w_bal =0;
				// alert(type);
				var wallet_name = $(this).attr('wallet_name');
				if(type=="myr_bal")
				w_bal=$('#myr_input_bal').val(); 
				if(type=="usd_bal")
				w_bal=$('#usd_input_bal').val(); 
				if(type=="inr_bal")
				w_bal=$('#inr_input_bal').val(); 
				var min_bal=0.5;
				var max_bal=10;
				var rebate_amount=$('#total_rebate_amount').val();
			}
			// alert(w_bal);
			// alert(min_bal);
			 $('#wallet_selected').val('y');
				$('#selected_wallet').val(type);
				$('#selected_wallet_bal').val(w_bal);
				$('.select_label').show();
				// alert(w_bal);
				var p_bal=0;
				  $('#wallet_name').html(wallet_name);
				if((parseFloat(w_bal)>=parseFloat(min_bal)))
				{
					if(parseFloat(w_bal)>parseFloat(max_bal))
						p_bal=max_bal;
					else 
					p_bal=w_bal;	
				   var p_bal=parseFloat(p_bal);
					var total_amount=$('#total_cart_amount').val();
					
					if(parseFloat(p_bal)>parseFloat(total_amount))    
						var p_bal=total_amount;
					// alert()
				   p_bal = (parseInt(p_bal * 10)/10).toFixed(2);
				   
					$('#wallet_payment_label').show();
					//$('#bal_payment_label').show();
				   $('#wallet_payment_label').html("Paying By Wallet Rm: <span style='color:black;font-weight:bold;'>"+p_bal+"</span></br>");
				
					
					var merchant_id='<?php echo $id; ?>';
					var selected_wallet_bal=$('#selected_wallet_bal').val();
					if(parseFloat(selected_wallet_bal)>parseFloat(max_bal))
						selected_wallet_bal=max_bal;
					 selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);
					var rem=0;
					if(parseFloat(selected_wallet_bal)<parseFloat(total_amount))
					{
						var rem=parseFloat(total_amount)-parseFloat(selected_wallet_bal);
						var rem=parseFloat(rem).toFixed(2);
						var payable_amount=selected_wallet_bal;
							var payable_amount=parseFloat(payable_amount).toFixed(2);
							var selected_wallet_bal=parseFloat(selected_wallet_bal).toFixed(2);
						// var p_msg="You are going to pay Rm "+selected_wallet_bal+" throught your wallet ,The Remaining Rm balance of "+rem+" will be paid by you via Cash";	
					}
					else
					{
						var total_amount=parseFloat(total_amount).toFixed(2);
					  // var p_msg="You are going to pay Rm "+total_amount+" throught your wallet";	
					  var payable_amount=total_amount;
					}
				  $('#bal_to_paid_label').show();
				  $('#bal_to_paid_label').html("Balance to be paid by cash Rm: <span style='color:black;font-weight:bold;'>"+rem+"</span></br>");
				
				  $('.wallet_final_payment').show(); 
				}  
				else
				{  
					
					$('#LesaaAmountModel').modal('show');
					// alert('Insufficient balance for deduction, please select other wallet or pay by cash. ');
					
					exit;
				}
			
	  });
	  $(".cash_pay").click(function(){
		  $(this).removeClass(" btn-primary").addClass("btn-default");
		   $("#order_place").submit();
	  });
	   $(".make_payment").click(function(){
		$(this).removeClass(" btn-primary").addClass("btn-default");
		$("#order_place").submit();
	  });
	  
		
	  $(".wallet_final_payment").click(function(){
		var total_amount=$('#total_cart_amount').val();
		var rebate_amount=$('#total_rebate_amount').val();
		var merchant_id='<?php echo $id; ?>';
		var selected_wallet_bal=$('#selected_wallet_bal').val();
		var special_coin_name='<?php  echo $special_coin_name;?>';
		var special_coin_max='<?php  echo $special_coin_max;?>';
		if(special_coin_name)
			var max_bal=special_coin_max;
		else 
			var max_bal=10;
		if(parseFloat(selected_wallet_bal)>parseFloat(max_bal))
			selected_wallet_bal=max_bal;
		var rem=0;
		selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);
		if(parseFloat(selected_wallet_bal)<parseFloat(total_amount))
		{ 
			var rem=parseFloat(total_amount)-parseFloat(selected_wallet_bal);
			var rem=parseFloat(rem).toFixed(2);
			var payable_amount=selected_wallet_bal;
				var payable_amount=parseFloat(payable_amount).toFixed(2);
				
				var selected_wallet_bal=selected_wallet_bal;
				var selected_wallet_bal = (parseInt(selected_wallet_bal * 10)/10).toFixed(2);
			var p_msg="You are going to pay RM <span style='color:black;font-weight:bold;'>"+selected_wallet_bal+"</span> through your wallet. The remaining balance of RM <span style='color:black;font-weight:bold;'>"+rem+"</span> is to be paid by cash.";	
		
		}
		else
		{
			var total_amount=parseFloat(total_amount).toFixed(2);
		  var p_msg="You are going to pay Rm <span style='color:black;font-weight:bold;'>"+total_amount+"</span> through your wallet";	
		  var payable_amount=total_amount;
		}
		 $(this).removeClass(" btn-primary").addClass("btn-default");
		$('#amount_label').html(p_msg);
		$('#ProccedAmount').modal('show');
		var wallet_type=$('#selected_wallet').val();
		var user_id=$('#login_user_id').val();
		$('#rem_amount').val(rem);  
		$('#payable_amount').val(payable_amount);
		// var r = confirm(p_msg);
			if (r == true) {
			    	// alert(data);
					var wallet_type=$('#selected_wallet').val();
					var user_id=$('#login_user_id').val();
					$('#rem_amount').val(rem);
					$('#payable_amount').val(payable_amount);
					 // $("#order_place").submit();
					// var order_id=$("#order_id").val();
					// var data = {order_id:order_id,user_id:user_id,method:"walletpay",w_type:wallet_type,total_amount:total_amount,rem:rem,payable_amount:payable_amount,merchant_id:merchant_id,rebate_amount:rebate_amount};
					// $.ajax({
						// url :'functions.php',
					  // type:'POST',
					  // dataType : 'json',
					  // data:data,
					  // success:function(response){
						  // var data = JSON.parse(JSON.stringify(response));
						  // if(data.status)
						  // {
							  // location.reload();
						  // }
						  // else
						  // {
							  // alert('Failed to make payment From Wallet');
						  // }
						  
						// }		  
						// });
			} else {
			 // alert('Wallet reject');
			 // location.reload();
			}
		
		
	});
	// end for rebaeat process
  
});
   function  walletstep()
	{
		$('.wallet_mode').show();
	}
	function rebatevalue(a,b)
	{
		  var c = (parseFloat(a)*parseFloat(b))/100;
		return parseFloat(c).toFixed(2);
	}
	function myFunction() {
  var x = document.getElementById("login_ajax_password");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass").html('Hide Password');
			 $('#eye_slash').removeClass( "fa-eye-slash" );
            $('#eye_slash').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass").html('Show Password');
	  $('#eye_slash').addClass( "fa-eye-slash" );
            $('#eye_slash').removeClass( "fa-eye" );
  }
}
  function our_stall(){

      $('#our_stall').modal('show');

   
  }

 function  getId(element) {


     // alert("row" + element.closest('tr').rowIndex);
      var id = $(element).closest('tr').find('td:first-child').text();
      $("#merchant_id").val(id);
    $("#sub_mer_form").submit();
    
}
  function getLocation() {
    // alert(3);
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    // x.innerHTML = "Geolocation is not supported by this browser.";
  $( "#confm" ).prop( "disabled", true );
  $('.text_add_cart').hide();
  if(location_order==1)
  $('#location_model').modal('show');
  }
}

function codeLatLng(lat, lng) {
    var geocoder= new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
  // alert(latlng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      // alert(results)
        if (results[1]) {
         //formatted address
         // alert()
        //find country name
           var full_address=results[0].formatted_address;
       $('#mapSearch').val(full_address);
        //city data
        // alert(city.short_name + " " + city.long_name)


        } else {
          alert("No results found");
        }
      } else {
        // alert("Geocoder failed due to: " + status);
      }
    });
  }
function calculatedisatace(to_lat,to_long)
{
  
  var from_lat='<?php echo $merchant_detail['latitude']; ?>';
  var from_long='<?php echo $merchant_detail['longitude']; ?>';
  var location_range='<?php echo $merchant_detail['location_range']; ?>';
  $.ajax({
               url: 'calculate_distance.php',  
               type: 'POST',
               data:{from_lat:from_lat,from_long:from_long,to_lat:to_lat,to_long:to_long},
                 success: function(distance) {
           // alert(distance);
           // alert(location_range);
             if(parseInt(distance) > parseInt(location_range))
             {
               $('.pro_status').hide();
            $( "#confm" ).prop( "disabled", true );
            $( "#confmpayment" ).prop( "disabled", true );
            // $('#map_range').html(location_range);
               // $('#map_model').modal('show');
             }
             else
             {
               $('.pro_status').show();
               $( "#confmpayment" ).prop( "disabled", false );
              $( "#confm" ).prop( "disabled", false );
             }
          }
           });
}

function showPosition(position) {
  
  // x.innerHTML = "Latitude: " + position.coords.latitude + 
  // "<br>Longitude: " + position.coords.longitude;
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
  if(latitude && longitude)
  {
    $('#user_lat').val(latitude);
    $('#user_long').val(longitude);
    calculatedisatace(latitude,longitude);
    
    $('#location_model').modal('hide');
  }
}

</script>
<script>
// document.getElementById('oth_pr').onclick = function() {
    // document.getElementById('test_code').focus();
// };
// document.getElementById('text_without').onclick = function() {
    // document.getElementById('test_athy').focus();
// };

</script>


<script>
var chat_appid = '52013';
</script>
<?php 
?>

<?php 
  if(isset($_SESSION["merchant_id"]) && $_SESSION["merchant_id"] > 0) { ?>
   <script>
    var chat_id = "<?php echo $_SESSION["merchant_id"]; ?>";
    var chat_name = "<?php echo $_SESSION["user_name"]; ?>"; 
    var chat_link = "<?php echo $_SESSION["user_link"]; ?>"; //Similarly populate it from session for user's profile link if exists
    var chat_avatar = "<?php echo $_SESSION["user_avatar"]; ?>"; //Similarly populate it from session for user's avatar src if exists
    var chat_role = "<?php echo $_SESSION["user_role"]; ?>"; //Similarly populate it from session for user's role if exists
    var chat_friends = '<?php echo $_SESSION["merchant_id"]; ?>'; //Similarly populate it with user's friends' site user id's eg: 14,16,20,31
    </script>
  <?php } ?>
<script>
(function() {
    var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
    document.getElementsByTagName("head")[0].appendChild(chat_css);
    var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
})();
</script>
<script type="text/javascript">
  var chat_appid = '52013';
  
  var chat_position = 'left';

  (function() {
    var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
    document.getElementsByTagName("head")[0].appendChild(chat_css);
    var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
  })();
</script>

<script>
function UpdateTotal(id, uprice= 0){
	// alert(id);           
	// alert(uprice);     
  var qty = $("#"+id+"_test_athy").val();
  var extra_val = $("#extra_child_"+id).val();
  // var extra = $("#"+id+"_test_athy").parent().parent().find("input[name='p_extra']").val().parseInt();
  //alert(qty);
  // alert(extra_val);
  if(extra_val=='')
	  var extra_val=0;
  var p_t=parseFloat(uprice)+parseFloat(extra_val);
  var total =  parseFloat(Number(qty*p_t).toFixed(2));
  // alert(qty);
  $("#"+id+"_cat_total").val(total);
}
function UpdateTotalCart(id=0){
  // var qty = $("#"+id+"_test_athy").val();
  var qty = $("#other_qty_"+id).val();
  var unitprize = $("#other_product_price_"+id).val();
  var total =  parseFloat(Number(qty*unitprize).toFixed(2));
  $("#"+id+"_cat_total").val(total);
  
  
}

// console.log("dsfgsdf", $('.section-dropdown'));
$('.section-dropdown').on('change', function(e) {
  e.preventDefault();
  let $this = $(this);
  let sectionId = $this.val();
  let $tableDropDown = $('.section-tables');
  let url = $this.attr('data-table-list-url') + '?section_id='+sectionId;
  let promise = $.ajax({
                  url: url,
                  type: 'get',
                  dataType: 'JSON'
                });
  promise.done(function(response) {
    
    if(response.length < 1) {
      let option = '<option>No Table Found</option>';
      $tableDropDown.html(option);
      return;
    }
    let option = "";
    response.forEach(function(item, index) {
      option += '<option value="'+item.id+'">'+item.name+'</option>';
    });
    $tableDropDown.html(option);
  });
});
</script>
  <script>
     
        
          
          /**/
        $(function () {
          
              $(window).on('load', function(){
             
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
        });
    </script>


