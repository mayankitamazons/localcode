<?php
   include("config.php");
   
	if($_SESSION['new_order']=="y")
	{
	   $new_order="y";
	}
	else
	{
		$new_order="n";
	}
   
   // $new_order="y";
   $show_pop="n";
   // print_R($_SESSION);
  // die;   
  function checkSession(){
  $conn = $GLOBALS['conn'];
  $session = $_COOKIE['session_id'];
  
  $rw = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM users WHERE session = '$session'"));
  if($rw > 0){
    return true;
  }else{
    return false;
  }
}
  // print_R($_SESSION);
  // die;
   if(isset($_SESSION['tmp_login']))
   {
	 $user_id=$_SESSION['tmp_login']; 
      $limit="0,1";	 
   }
   else
   {
	   $user_id=$_SESSION['login'];
	   $limit="0,50";
   }
   if($_SESSION['login'])
   {
	   $user_id=$_SESSION['login'];
   }
  
     $query="SELECT order_list.*, sections.name as section_name FROM order_list left join sections on order_list.section_type = sections.id WHERE order_list.user_id ='".$user_id."' ORDER BY `created_on` DESC LIMIT $limit";
// die;
     $total_rows = mysqli_query($conn,$query);
	 
	 $user_order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$user_id."' ORDER BY `created_on` DESC limit 0,1"));
	   $totalcount=count($user_order);
	 // print_R($user_order);
	 // die;
	 $check_number=$user_order['user_mobile'];
	 $check_number=str_replace("60","",$check_number);
	 $order_id=$user_order['id'];
	 $section_saved=$user_order['section_saved'];
	 $show_alert=$user_order['show_alert'];
	 if($user_order)
	 {
		 $newuser=$user_order['newuser'];
	 }
	 else
	 {
		 $newuser='n';
	 }
	 if($user_order['user_id'])
	   {
		    $user_id=$user_order['user_id'];
	    $uinfo = mysqli_query($conn,"select * from users where id='$user_id'");
        $user_data = mysqli_fetch_array($uinfo);
		$password_created=$user_data['password_created'];
		$otp_verified=$user_data['otp_verified'];
		$user_password=$user_data['password'];
	   }
	  if(isset($_POST['method'])){
	$user = $_POST['user'];
	$payment = $_POST['payment'];
	$sql = "SELECT * FROM payments WHERE type='$payment' and user = '$user'";
	$result = mysqli_query($conn, $sql);
	$payment = mysqli_fetch_assoc($result);
	$res = array(
		"name" => $payment['name'],
		"mobile" => $payment['mobile'],
		"remark" => $payment['remark'],
		"qr_code" => $payment['qr_code']
	);
	echo json_encode($res);
	exit();
}

	if($user_order['merchant_id'])
   {
	   $merchant_id=$user_order['merchant_id'];
	    $info = mysqli_query($conn,"select * from users where id='$merchant_id'");
		$online_pay=$user_order['online_pay'];
		$payment_alert=$user_order['payment_alert'];
        $merchant_data = mysqli_fetch_array($info);
		if($online_pay)
		{
			$cash_check = $merchant_data['cash_check'];
			$credit_check = $merchant_data['credit_check'];
			$wallet_check = $merchant_data['wallet_check'];
			$boost_check = $merchant_data['boost_check'];
			$grab_check = $merchant_data['grab_check'];
			$wechat_check = $merchant_data['wechat_check'];
			$touch_check = $merchant_data['touch_check'];
			$fpx_check = $merchant_data['fpx_check'];
			$cash_image = "available";
			$credit_image = "available";
			$wallet_image = "available";
			$boost_image = "available";
			$grab_image = "available";
			$wechat_image = "available";
			$touch_image = "available";
			$fpx_image = "available";
			if($cash_check == "0")
				$cash_image = "unavailable";
			if($credit_check == "0")
				$credit_image = "unavailable";
			if($wallet_check == "0")
				$wallet_image = "unavailable";
			if($boost_check == "0")
				$boost_image = "unavailable";
			if($grab_check == "0")
				$grab_image = "unavailable";
			if($wechat_check == "0")
				$wechat_image = "unavailable";
			if($touch_check == "0")
				$touch_image = "unavailable";
			if($fpx_check == "0")
				$fpx_image = "unavailable";
		}
		// echo "select * from Offers_statement where merchant_id ='$merchant_id'";
        $offerquery = mysqli_query($conn,"select * from offers_statement  where merchant_id ='$merchant_id'");
        $offerdata = mysqli_fetch_array($offerquery);
		// print_R($offerdata);
		// die;
   }
	 if($show_alert=="y" && $section_saved=="y" && ($payment_alert=="n" || $payment_alert=="verified"))
	 {
		$res1= mysqli_query($conn,"UPDATE `order_list` set show_alert ='n' WHERE id ='$order_id'");
	     
	 }
	 // $show_alert="y";
	$first_section_id=$user_order['section_type'];
	$first_table_id=$user_order['table_type'];
	  $merchant_id=$user_order['merchant_id'];
	    $open_order_id=$user_order['id'];
	 if($first_section_id=="" || $first_table_id=="")
	 {
		 if($section_saved=="n")
		 {
		 if($totalcount>0)
		 {
		include_once('php/Section.php');
		// include_once('php/SectionTable.php');

		$sectionsObj = new Section($conn);
		// $sectionTablesObj = new SectionTable($conn);
		$sectionsFilter = [
		  'user_id' => isset($merchant_id) ? $merchant_id : null,
		  'status' => true
		];
		
		$sectionsList = $sectionsObj->getList($sectionsFilter);
		
		 
		 $open_order_id=$user_order['id'];
		 }
		 $show_pop="y";
		 }
		 else
		 {
			 $show_pop="n";
		 }
	 }
	 $created_new =$user_order['created_on'];
      $status1 =$user_order['status'];
   	$_SESSION['mm_id'] = "";
   	$_SESSION['o_id'] = "";
	// echo $show_pop;
	// die;
	// print_R($user_order);
	// die;
	$last_order_utc=$user_order['created_timestamp'];
	  $last_order_utc=strtotime($last_order_utc);

	 $current_date=date('Y-m-d H:i:s');
	$current_utc=strtotime($current_date);
	 $diff=abs($current_utc-$last_order_utc);
	 $years = floor($diff / (365*60*60*24));  
  
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
 
 $days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24));   
			 // die;
	if($days>1)
	{
		 $show_pop="n";
		 if($section_saved=="n")
		 $res1= mysqli_query($conn,"UPDATE `order_list` set show_alert ='n',section_saved='y' WHERE id ='$order_id'");
	}	

   	?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
     <style type="text/css">
				 #partitioned {
  padding-left: 15px;
  letter-spacing: 42px;
  border: 0;
  background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
  background-position: bottom;
  background-size: 50px 1px;
  background-repeat: repeat-x;
  background-position-x: 35px;
  width: 220px;
  min-width:220px;
}

#divInner{
  left: 0;
  position: sticky;
}

#divOuter{
  width:190px; 
  overflow:hidden
}
				</style>
      <style>
		  .test_product{
		        padding-right: 125px!important;
		    }
		td.products_namess {
            text-transform: lowercase;
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
         td {
            border-right: 2px solid #efefef;
		}
		th {
            border-right: 2px solid #efefef;
        }
        tr.br_bk {
            border-bottom: 3px double #000;
        }
        .table tbody + tbody {
            border: none!important;
        }
         tr.red {
         color: red;
         }
         label.dp_lab {
         cursor: pointer;
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
         .product_name{
		     width: 100%;
		 }
		  .total_order{
		 font-weight:bold;
		 }
		 .gr{
		     color:green;
		 }
		 .or{
             color: orange;
         }
		 .red.gr{
		     color:green;
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
}
#SectionModel .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#SectionModel .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #SectionModel > .modal-dialog{
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
  
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
  #SectionModel{
	  max-width:400px;
  }
}

      </style>
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
    margin-bottom: 10px;
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <?php include("includes1/head.php"); ?>
      <?php // include("mpush.php"); ?>
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
         <div class="well">
            <h3>Order list</h3>
            <?php
               $dt = new DateTime();
               $today =  $dt->format('Y-m-d');
               $today_order = explode(" ",$created_new);
                if( $today == $today_order[0] && $status1 == 1 ){ ?>
                <div style="display: none;">
<audio autoplay> <source src="<?php echo $site_url;?>/images/sound/doorbell-1.mp3" type="audio/mpeg"> Your browser does not support the audio tag. </audio>
    </div>
    <?php } ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?php echo $language["items"];?></th>
                     <th><?php echo $language["date_of_order"];?></th>
                     <th>No</th>
					 <th class="test_product"><?php echo $language["merchant_name"];?></th>
					 <th><?php echo $language["status"];?></th>
					   <th>Section</th>
					 <th><?php echo $language["table_number"];?></th>
					 <th>Invoice Number</th>
					 <th class="location_head"><?php echo $language["location"];?></th>
					 <th><?php echo $language["chat"];?></th>
					 <th><?php echo $language["telephone_number"];?></th>
                     <th><?php echo $language["product_code"];?></th>
                     <th class="product_name test_product"><?php echo $language["product_name"];?></th>
                    <th class="product_name test_product"><?php echo "VARIENT";?></th>
                     <th class="product_name test_product"><?php echo $language["remark"];?></th>
                     <th><?php echo $language["quantity"];?></th>
                     <th>Price</th>
                     <th><?php echo $language["amount"];?></th>
                     <th><?php echo $language["total"]?></th>
                     <th><?php echo $language["mode_of_payment"];?></th>
                     <th><?php echo $language["rating_comment"];?></th>
                     <th><?php echo $language["print"];?></th>
                     <th>K1/K2</th>
                  </tr>
               </thead>
               <?php  $i =1;
                  while ($row=mysqli_fetch_assoc($total_rows)){
				  
				  //print_r($row);
				 // echo "<hr>";
                  	$product_ids = explode(",",$row['product_id']);
                  	$quantity_ids = explode(",",$row['quantity']);
                  	$product_code = explode(",",$row['product_code']);
                  	$remark_ids = explode("|",$row['remark']);
                  	$c = array_combine($product_ids, $quantity_ids);
                  	$amount_val = explode(",",$row['amount']);
                    $amount_data = array_combine($product_ids, $amount_val);
                    $total_data = array_combine($quantity_ids, $amount_val);
                    //var_dump($amount_val);
					// $order_list = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC");
                    $user_namess = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));
                    $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    $created =$row['created_on'];
                    $date=date_create($created);
					$section_type=$row['section_type'];
					 $section_id=$section_type;
					 $merchant_id=$user_order['merchant_id'];
					 if($section_type)
					 {
					  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
					 
					 }
					 $table_type=$row['table_type'];
                    $new_time = explode(" ",$created);
                    $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    //$account_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT k_merchant FROM k1k2_history WHERE id ='".$row['id']."'"))['account_type'];
                  ?>
               <tbody>
                  <?php

                   if($row['status'] == 1) $callss = "gr";
                     else if($row['status'] == 2) $callss = "or";
                     else $callss = " ";
                     $todayorder = $today == $new_time[0] ? "red" : "";
                   $i1 =1;
                      ?>
                  <tr  data-id="<?php echo $row['id']; ?>" class="<?php echo $todayorder; ?> <?php echo $callss; ?> br_bk" >
                     <td><?php echo  $i; ?></td>
                     <td><?php echo date_format($date,"Y/m/d");  ?>
                     <?php echo '<br>'; echo $new_time[1] ?>
                                         </td>
                     <td><?php
                        foreach ($quantity_ids as $key => $val)
                        {

                        echo $i1; echo '<br>';
                         $i1++;
                        }
                        ?></td>
                    <td><a href="<?php echo $site_url; ?>/view_merchant.php?sid=<?php echo $merchant_name['mobile_number'];?>"><?php echo $merchant_name['name'];  ?></td>
                        
						<td>
                       <?php
                        $sta = $row['status'] == 1 ? "Done" : "Pending"?>
                        <?php if($row['popup']==0 && $row['status'] == 1 )
                        {
                            //echo $row['id'];
                        }
                        ?>
                        <label class= "status" data-id="<?php echo $row['id']; ?>" style="cursor:pointer;"> <?php echo $sta; ?></label>
                     </td>   
					   <td><?php echo $section_type['name'];?></td>
                         <td><?php echo $row['table_type'];?></td>
                         <td><?php echo $row['invoice_no'];?></td>
                         <td class="location_<?php echo $row['id']; ?> new_tablee"><?php echo $row['location'];?></td>
  <td><a target="_blank" href="<?php echo $site_url; ?>/chat/chat.php?sender=<?php echo $user_id;?>&receiver=<?php echo $row['merchant_id'];?>"><i class="fa fa-comments-o"></i></a></td>
                         <?php if($merchant_name['number_lock'] == 0){?>
                            <td><?php echo $merchant_name['mobile_number'];?></td>
                        <?php } else {?>
                            <td></td>
                        <?php }?>

                     <td>
                        <?php
                           foreach ($product_code as $key)
                           {
                           //$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                if($key == "") echo '- <br>';
                                else echo $key.'<br>'; 
                               
                           }
                           ?>

                     </td>
                     <td class="products_namess test_productss">
                        <?php foreach ($product_ids as $key ){
							if(is_numeric($key)){
                                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                echo $product['product_name'].'<br>';
					        }else {
						        echo $key.'<br>';
					        }
                         } ?>
                         </td>
					<td><?php if($row['varient_type']){$v_str=$row['varient_type'];
							$v_array=explode("|",$v_str);
							foreach($v_array as $vr)
							{
								
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										echo $srow['name'];
										echo "&nbsp;&nbsp;";
									}
								}
								 else
								 {
									 echo "</br>";
								 }
								 echo "<br/>";
					} }
							  ?>
							</td>
                     <td>
                        <?php
                           foreach ($remark_ids as $vall)
                           {

                           	echo $vall.'<br>';

                           }
                           ?>
                     </td>
                     <td><?php foreach ($quantity_ids as $key)
                        {
                              if($key == "") echo '0 <br>';
                              else echo $key.'<br>';

                        }    ?>
                    </td>
                    <td>
                        <?php 
                            foreach ($amount_val as $key => $value){ 
                                echo $value.'<br>'; 
                            } 
                        ?>
                    </td>
                     <td><?php 
                          foreach ($amount_val as $key => $value)
                        {
						                 
                        echo $quantity_ids[$key] * $value.'<br>'; 
                        } ?></td>
                     <td class="total_order"><?php 
                        $total = 0;
                        foreach ($amount_val as $key => $value)
                        {
                         $total =  $total + ($quantity_ids[$key] * $value);
                        
                        } 
                        echo  $total;
                           ?> </td>

                     <td><?php echo $row['wallet'];  ?></td>
                     <td>
                        <?php //if($row['status']== '1'){ ?>
							 <label class="dp_lab"  data-id="<?php echo $merchant_name['id'];  ?>" data-oid="<?php echo $total;?>" data-orid="<?php echo $row['id']; ?>">Click Here</label>

                        <?php// }   ?>
                     </td>                
                     <?php if($sta == "Done"){?>
                        <?php $merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$row['id']."'"));	?>
                      <td><a target="_blank" href="print.php?id=<?php echo $row['id'];?>&merchant=<?php echo $merchant['merchant_id']?>">Print</a></td>
                      <td><?php echo $user_name['account_type']; ?></td>
                      <?php }?>
                  </tr>
                  <?php  	 $i++; ?>

               </tbody>
          <?php       }

                     ?>
            </table>
            <div style="margin:0px auto;">
               <ul class="pagination">
                  <?php
                   global $total_page_num ;
                     for($i = 1; $i <= $total_page_num; $i++)
                     {
                      if($i == $page)
                      {
                       $active = "class='active'";
                      }
                      else
                      {
                       $active = "";
                      }
                      echo "<li $active><a href='?page=$i'>$i</a></li>";
                     }
                     ?>
               </ul>
            </div>
            <div>
                <!-- add new code-->
				<!-- edit amount--->
	        <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edit Amount</h4>
                        </div>
                        <div class="modal-body" style="padding-bottom:0px;">

		                    <div class="col-sm-10">
                                <form id ="data">
                                    <div class="form-group">
                                        <label>Amount</label>
		 	                            <input type="text" name="amount" id = "amount" class="form-control" value="" required>
                                        <input type="hidden" id="id" name="id" value="">
                                        <input type="hidden" id="p_id" name="p_id" value="">
                                    </div>
                                </div>
		                    </div>
                        <div class="modal-footer" style="padding-bottom:2px;">
                			<button>Submit</button>
                        </div>
                        </form>
                    </div>
                 <div class="modal fade" id="myModal" role="dialog" >
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content" id="modalcontent">
                       <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
				</div>
			<!-- end edit function--->
	<?php
	$res = mysqli_query($conn,"SELECT * FROM `order_list` WHERE status_change_date = CURDATE() and status = 1 and popup = 1 and user_id ='".$user_id."'");
	if(mysqli_num_rows($res)==0)
	{
	    $res = mysqli_query($conn,"SELECT * FROM `order_list` WHERE status_change_date = CURDATE() and status = 1 and popup = 0 and user_id ='".$user_id."'");
	    $r = mysqli_fetch_array($res);
	    if(mysqli_num_rows($res)!=0)
	    {
	        include_once 'share_popup.php';
	        //$res = mysqli_query($conn,"UPDATE `order_list` set popup = 1 WHERE status_change_date = CURDATE() and status = 1 and popup = 0 and user_id ='".$_SESSION['login']."'");
	    }
	}
	 ?>
		<!-- end new code--->

      </main>
      </div>
      <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/commonfooter.php"); ?>
	    <!-- Modal -->
  <div class="modal fade" id="PasswordModel" role="dialog" style="">
   <div class="modal-dialog">
           <?php 

          

            ?>

            <!-- Modal content-->
            <div class="modal-content">
              
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
					
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					    <div class="form-group register_password" style="<?php  if($password_created=="y"){ echo "display:none;"; }?>">
             
							  <div class="passwd_field">
								<label for="login_password">Please create your password</label>
								<input type="password" id="login_password" class="form-control" name="login_password"/>
											
					   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
					  <span id="eye_pass_2" onclick="myFunction2()" > Show Password </span>
					   <small id="register_error" style="display: none;color:#e6614f;">
                 
                </small>
										
							  </div>
				</div>
						
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
             
						  <div class="col" style="padding: 0;margin: 5px;">
							
							<input type="submit" class="btn btn-primary register_ajax"  name="register_ajax" value="Confirm" style="float: right;"/>
							 <small id="register_error" style="display: none;color:#e6614f;">
							 
							</small>
							
						  </div>
						         
					   
						</div>
						  <small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;">
						  <u class="finalskip">Skip</u>
						</small>    
						
                    </div>
                  
            </div>
        </div>
  </div>
  <style>
.text_payment{
    width: 50%!important;
    text-align: center;
    margin: 0 auto;
}
.pay_wallet{
    font-size: 14px;
    text-align: center;
}
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 50%;
    margin: 0 auto;
    padding: 15px;
}
.wallet_hr{
    width: 510px;
    margin-left: -15px;
    border-top: 1px solid black;
}
/*jupiter 24.02.19*/
	.img60{
		width: 40px;
		height: auto;
	}
	.payment_title{
		margin-top: 0.8rem;
		font-size: 16px;
	}
	.table td{
		border-top: 1px solid black !important;
	}
/**/
 @media (min-width: 360px) and (max-width:650px) {
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 100%;
    margin: 0 12px;
    padding: 14px;
}
.wallet_hr {
    width: 325px;
}
}
 @media (min-width: 700px) and (max-width:800px) {

.wallet_hr {
    width: 335px;
   }
}
 @media (min-width: 650px) and (max-width:700px) {

.wallet_hr {
    width: 307px;    
}
}
 @media (min-width: 430px) and (max-width:400px) {

.wallet_hr {
    width: 360px!important;
}
}

</style>
   <div class="modal fade" id="OnlineModel" role="dialog" style="">
   <div class="modal-dialog">
           <?php 

          

            ?>

            <!-- Modal content-->
            <div class="modal-content">
              
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
					
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					       <p>Select Payment Mode</p>
							<select class="form-control text_payment required" id="text_payment" style="font-weight: bold;" name="wallet">  
        						<!--<option class="text_csah" value='cash_<?php echo $order_total['id'];?>'><?php echo $language["cash"] ;?></option>
        						<option value='MYR'><?php echo $language["wallet"];?></option>  !-->
								<?php if($merchant['mobile_number']!="60172669613"){ ?>
        						
								<?php if($credit_check == "1"){?>
    						    	<option title="Credit Card">Credit Card</option>
    						    <?php }?>
        						<?php if($boost_check == "1"){?>
    						    	<option title="Boost Pay" value='1'>Boost Pay</option>
    						    <?php }?>
    						    <?php if($grab_check == "1"){?>
    						    	<option value='2' title="Grab Pay">Grab Pay</option>
    						    <?php }?>
    						    <?php if($wechat_check == "1"){?>
    						    	<option value='3' title="WeChat">WeChat</option>
    						    <?php }?>
    						    <?php if($touch_check == "1"){?>
    						    	<option value='4' title="Touch & Go">Touch & Go</option>
    						    <?php }?>
    						    <?php if($fpx_check == "1"){?>
    						    	<option value='5' title="FPX">FPX</option>
    						    <?php } }?>
        					</select>  
                      
						   <input type="hidden" id="id" name="m_id" value="<?php echo $m_id;?>">
						   <input type="hidden" id="amount" name="amount" value="<?php echo $total;?>">
						    <input type="hidden" id="member" name="member" value="<?php echo $member;?>">
					       <input type="hidden" id="o_id" name="o_id" value="<?php echo $order_total['id'];?>">
						    <?php if(isset($_GET['user_id'])){  ?>
						    <input type="hidden" id="guest_id" name="guest_id" value="<?php echo $_GET['user_id'];?>">
						    <input type="hidden" id="guest_order_id" name="guest_order_id" value="<?php echo $_GET['order_id'];?>">
						   <?php } ?>
						  
						<!-- jupiter 24.02.19 -->
							<?php if($merchant['mobile_number']!="60172669613"){    ?>
					  <div class="payment_section">
					  	<table class="table" border="1" style="margin-top: 10px; " >
					  		<tbody>
					  			<!--tr>
					  				<td><h5 class="payment_title">Cash</h5></td>
						  			<td><img src="images/payments/cash.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $cash_image;?>.jpg" class="img60"></a></td>
					  			</tr!-->
								<?php if($credit_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Credit Card</h5></td>
						  			<td><img src="images/payments/credit.jpg" class="img60"></td>
						  			<td><img src="images/payments/<?= $credit_image;?>.jpg" class="img60"></a></td>
					  			</tr>
								<?php } ?>
					  			<!--tr>
					  				<td><h5 class="payment_title">Wallet</h5></td>
						  			<td><img src="images/payments/wallet.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $wallet_image;?>.jpg" class="img60"></a></td>
					  			</tr!-->
								<?php if($boost_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Boost Pay</h5></td>
						  			<td><img src="images/payments/boost.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="1" title="Boost Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($grab_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Grab Pay</h5></td>
						  			<td><img src="images/payments/grab.jpg" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="2" title="Grab Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  					
						  			</td>
					  			</tr>
								<?php }?>
									<?php if($wechat_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">WeChat</h5></td>
						  			<td><img src="images/payments/wechat.jpg" class="img60"></td>
						  			<td>
						  			
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="3" title="WeChat"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($touch_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Touch & Go</h5></td>
						  			<td><img src="images/payments/touch.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="4"title="Touch & Go"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  				
						  			</td>
					  			</tr>
								<?php }?>
					  			<?php if($fpx_check == "1"){?>
								<tr>
					  				<td><h5 class="payment_title">FPX</h5></td>
						  			<td><img src="images/payments/fpx.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="5" title="FPX"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			</td>
					  			</tr>
									<?php }?>
					  		</tbody>
					  	</table>
					  </div>  
					  	<?php } ?>
					  	
                         
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div class="row" style="margin: 0;">
			 
             
						  <div class="col" style="">
							
							<!--input type="submit" class="btn btn-primary online_ajax"  name="online_ajax" value="Confirm" style="float: right;"/!-->
							 <small id="register_error" style="display: none;color:#e6614f;">
							 
							</small>
							
						  </div>
						         
					   
						</div>
						  <small  class="skiponline"  style="color:#e6614f;font-size:14px;min-width:50px;">
						  <u class="skiponline">Skip</u>
						</small>    
						
                    </div>
                  
            </div>
        </div>
  </div>
  <!-- Modal -->
     <div id="paymentModal" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
								<form  id="paymentdata" method="POST"  enctype="multipart/form-data" action="payment_submit.php">    
					      			<div class="modal-header">
					        			<button type="button" class="close" data-dismiss="modal">&times;</button>
					        			<h4 class="modal-title payment_header">Modal Header<img src="images/payments/boost.png"></h4>
					      				
					      			</div>
				      				<div class="modal-body" style="text-align: left;">
				      					<input type="hidden" value="" name="wallet" id="payment_type" class="payment_type">
				      				
				      					<input type="hidden" value="<?php echo $open_order_id;?>" name="payment_order_id">
					        			<h5 class="">Please pay to <span class="merchant_name">sdf</span></h5>
					        			<h5>Mobile Number +60 <span class="mobile"></span></h5>
					        			<h5>QR Code:</h5>
					        			<img class="qr_code_image">
					        			<h5 class="">Reference: <span class="reference"></span></h5>
										 <div class="form-group" style="width:70%;">
    <label for="pwd">Upload image (proof of payment):</label>
    <input type="file" class="form-control" name="paymentproff" id="paymentproff">
  </div>
					        			<button type="submit" class="btn btn-primary confirm_payment_btn"  style="margin-bottom: 10px;">I have paid to the merchant</button>
					        			<button type="button" class="btn btn-default different_method" data-dismiss="modal">I want to pay with another method</button>
										  <small  class="finalskip"  style="color:#e6614f;font-size:14px;min-width:50px;float:right;">
						  <u class="skiponline">Skip</u>
						</small>    
						
					      			</div>
					      			
					      	</form>
					    		</div>

				  			</div>
						</div>
  <div class="modal fade" id="SectionModel" role="dialog" style="">
   <div class="modal-dialog">
           <?php 

          

            ?>

            <!-- Modal content-->
            <div class="modal-content">
              <form method="post" action="sectionsave.php">
                <div class="modal-header">
                    <button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
					<?php if($merchant_data['mian_merchant']){ ?>
                    <h4 class="modal-title">Main Merchant (<?php echo $merchant_data['mian_merchant'];?>)</h4>
					<?php } ?>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
					    <?php if(($new_order=="y") && ($show_alert=="y")){ ?>
             <p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been sent to our kitchen!</p>
              <?php } ?>
						<?php if($first_table_id=='' || $first_section_id==''){ ?>
                        <div class="col-md-12" style="text-align: center;">
                         <span><strong> If you have found your table, kindly key in now:-</strong></span>
						 <?php if($first_section_id==''){ ?>
                         <center><div class=" col-md-6 form-group">
                            <label>Section :</label>
                             <select id='section_type' name="section_type" required  class="form-control" data-table-list-url="<?php echo $site_url; ?>/table_list.php">
			  
						  <?php 
						  foreach($sectionsList as $sectionId => $sectionName): ?>
							<?php
							  $isSelected = "";
							  if($section_id == $sectionId) {
								$isSelected = "selected";
							  }
							?>   
							<option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
						  <?php endforeach; ?>
						</select>
						 </div></center> <?php } if($first_table_id==''){ ?>
                          <center> <div class=" col-md-6 form-group">
                            <label>Table Number :</label>
                             <input type="text" class="form-control" id='table_booking' name="table_booking" value="" placeholder="Table Number" required>  
                          </div></center>
						 <?php } ?>
						 <?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
                          <p><strong><?php echo $offerdata['discp'];?> </strong></p>
						 <?php } ?>
                          <input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $merchant_data['mian_merchant'];?>">
                          <input type="hidden" name="more_order" id="more_order" value="moreOrder">
                          <input type="hidden" name="current_oid" value="">
                          <input type="hidden" name="order_id" value="<?php echo $user_order['id']; ?>">
                        </div>
						<?php  } ?>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <div> <img style="max-height:100px !important;" src="Drink-pink.png"></div>
						<?php if($offerdata['discp'] && $merchant_data['mian_merchant']){ ?>   
                        <button type="submit" class="btn btn-primary" style="background-color: red;">Yes order with <?php echo $merchant_data['mian_merchant'];?>
						</button>
						<?php } else { ?>
						<button type="submit" class="btn btn-primary" style="float: right;">Confirm</button>
						
						<?php  } ?>
						  <!--small  class="finalskip"  style="color:#e6614f;font-size:14px;">
						  <u>Skip</u>
						</small!-->
						
                    </div>
                </form>
            </div>
        </div>
  </div>
 
<div class="modal fade" id="newuser_model" tabindex="-1" role="dialog" aria-labelledby="login_passwd_modal_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="form-group">
      <div class="modal-content">
	    
        <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
          <div class="modal-body">
             <div class="form-group">
              
			  <input type="hidden" id="user_mobile" value="<?php echo $user_order['user_mobile']; ?>"/>
			  <input type="hidden" id="user_id" value="<?php echo $user_order['user_id']; ?>"/>
			  <input type="hidden" id="order_id" value="<?php echo $user_order['id']; ?>"/>
			  <input type="hidden" id="system_otp"/>
			  <input type="hidden"  value='0' id="otp_count"/>
			  <?php if(($new_order=="y") && ($show_alert=="y")){ ?>
             <p style="font-size: 16px;color: red;"><span style='font-size:20px;'>&#128512;</span> Congratulations ! Your order has been sent to our kitchen!</p>
              <?php } ?>
			</div>
			<div class="form-group otp_form" style="display:none;">
				<div id="divOuter">
					<div id="divInner">
					Otp code
						<input id="partitioned" type="Number" maxlength="4" />
						   <!--small style="float:right;color:#28a745;display:none;" class="resend send_otp">Resend</small!-->
						 <small class="otp_error" style="display: none;color:#e6614f;">
							Invalid Otp code
						</small>
					</div>
				</div>
			</div>
				
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
		
        <input  type="number" autocomplete="tel" maxlength='10'  class="mobile_number form-control" <?php if($check_number){ echo "readonly";} ?> value="<?php if($check_number){ echo $check_number;}  ?>" placeholder="Phone number" name="mobile_number" required="" />
       
	  </div>
	  <small id="forgot_error" style="display: none;color:#e6614f;">
		 Please Key in valid number
		</small>
      <img id="loader-credentials" src="<?php echo $site_url;?>img/loader.gif" style="display:none;width:40px;height:40px;grid-column-start: 2;grid-column-end: 3;"/>
    </div>
			  
          </div>
          <div class="modal-footer login_footer">
            <div class="row" style="margin: 0;">
			 
             
              <div class="col otp_fields join_now" style="padding: 0;margin: 5px;display:none;">
                
                <input type="submit" class="btn btn-primary login_ajax"  name="login_ajax" value="Confirm" style="float: right;display:none;"/>
				 <small id="login_error" style="display: none;color:#e6614f;">
                 
                </small>
				
              </div>
			  <div class="col otp_fields forgot_now" style="padding: 0;margin: 5px;display:none;">
                <input type="submit" class="btn btn-primary forgot_reset"   value="Reset" style="width:50%;float: right;display:none;"/>
              </div>         
           
            </div>
			 <small  class="reg_pending skip" id='skip' style="color:#e6614f;font-size:14px;min-width:50px;">
                  <u>Got it !</u>
                </small>
				 <small  class="reg_pending register_skip skip" style="color:#e6614f;font-size:14px;display:none;min-width:50px">
                  <u>Got it !</u>
                </small>
          </div>
		  
      </div>
    </div>
  </div>
</div>
   
   </body>
</html>
  <script>
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
function myFunction2() {
  var x = document.getElementById("login_password");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_2").html('Hide Password');
			 $('#eye_slash_2').removeClass( "fa-eye-slash" );
            $('#eye_slash_2').addClass( "fa-eye" );
			
  } else {
    x.type = "password";   
	 $("#eye_pass_2").html('Show Password');
	  $('#eye_slash_2').addClass( "fa-eye-slash" );
            $('#eye_slash_2').removeClass( "fa-eye" );
  }
}
</script>
<?php $login_user_id=$_SESSION['login']; ?>
<script type="text/javascript">
   $(document).ready(function(){
	    var new_order='<?php echo $new_order; ?>';
		var show_pop='<?php echo $show_pop; ?>';
		var already_login='<?php echo $login_user_id; ?>';
		var newuser='<?php echo $newuser; ?>';
		var show_alert='<?php echo $show_alert; ?>';
		// alert(show_pop);
		if(show_pop=="y")
		{
			$('#SectionModel').modal('show');
			
		}
		else
		{
			nextstep();
		}
	    // alert(new_order);
		  // nextstep();
		// alert($('#SectionModel').hasClass( "show"));
		
				
		
	
		
   });
   $(".forgot_reset").click(function(){
		    $(this).removeClass(" btn-primary").addClass("btn-default");
		 setTimeout(function() {
			
         $(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
		   var usermobile=$("#user_mobile").val();
		   var data = {usermobile:usermobile,method:"forgotpass"};
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
		  $('.login_passwd_field').hide();
		   $(".join_now").hide();
		   $(".forgot_reset").show();
		   $(".forgot_now").show();
		  $('.forgot-form').show();
	  });  
   $(".send_otp").click(function(){
	  // $(".resend").hide();
	  
	  var otp_count=$("#otp_count").val();
	  if(otp_count<3)
	  {
	  var usermobile=$("#user_mobile").val();
	  // var usermobile=+919001025477;
	   //target:'#picture';
	    // $(this).hide();
		var data = {usermobile:usermobile, method: "sendotp"};
	  $.ajax({
		  
		  url :'functions.php',
		  type:'POST',
      dataType : 'json',
      data:data,
		  success:function(response){
			  var data = JSON.parse(JSON.stringify(response));
			  if(data.status==true)
			  {
				  otp_count++;
				  
				  $("#otp_count").val(otp_count);
				  $("#system_otp").val(data.otp);
				 $(".otp_form").show(); 
			  }
			  
			}		  
	  });
	   // setTimeout(function () {
						// $(".resend").show();
					// }, 15000);
	  }
	  else
	  {
		  // $('#register_error').html('You Extend Send Otp limit');
		  // $('#register_error').show();
	  }
	 
  });
   $(".skiponline").click(function(){
     location.reload();
   });
   $(".finalskip").click(function(){
	   // alert(3);
	 $('#newuser_model').modal('hide');
	 $('#SectionModel').modal('hide');
	 $('#PasswordModel').modal('hide');
		});
		$("#sectionclose").click(function(){
	  // alert(show_pop);
			nextstep();
		});
  $(".skip").click(function(){
	  $('#newuser_model').modal('hide');
	  // alert(show_pop);
			// nextstep();
		});
		$(".different_method").click(function(){
			$("#OnlineModel").modal("show");
			$("#paymentModal").modal("hide");
		});
	    $(".confirm_payment_btn").click(function(e){
		var payment = $(".payment_type").val();
		// alert(payment);
		if(payment == "1"){
			payment = "Boost Pay";
		}
		 
		$(".text_payment").val(payment);
		$("#paymentModal").modal("hide");
		 // document.getElementById("paymentdata").submit();
	});
	     $(".payment_btn").click(function(e) {
			 // alert(3);
			e.preventDefault();
		 var title = $(this).attr('title');
		 var payment = $(this).attr('payment');
		
			var user='<?php echo $merchant_id; ?>';
				$(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   	$(".payment_type").val(payment);
		if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
		{
			// alert(payment);
			var title = $(this).attr('title');
			// alert(title);
		     $.ajax({
            url : 'orderlist.php',
            type : 'post',
            dataType : 'json',   
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
				$("#OnlineModel").modal("hide");
				// alert(payment);
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
				// var title = $(".text_payment").find(':selected').attr('title'); 
				
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".merchant_name").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
				
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
            }
			}); 	
		}			

		
		
	});
		$("select.text_payment").change(function(e) {
			e.preventDefault();
		var payment=$(this).val();
		// alert(payment);
		var title = $(".text_payment").find(':selected').attr('title');   
		// var title = $(this).attr('title');
			// alert(title);
			var user='<?php echo $merchant_id; ?>';
				$(".payment_type").val(payment);
	  // var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  // $("#data").attr("action", action);
	   // alert(payment);
	   	$(".payment_type").val(payment);
		if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
		{
			// alert(payment);
			
		     $.ajax({
            url : 'orderlist.php',
            type : 'post',
            dataType : 'json',
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
				$("#OnlineModel").modal("hide");
				// alert(payment);
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
				var title = $(".text_payment").find(':selected').attr('title'); 
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".merchant_name").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
				var title = $(".text_payment").find(':selected').attr('title');  
				// alert(title);
				$('#payment_type').val(title);
				$("#paymentModal").modal("show");
            }
			}); 	
		}			

		
		
	});
		function nextstep()
		{
			
			var online_pay='<?php echo $online_pay; ?>';
			var payment_alert='<?php echo $payment_alert; ?>';
			var show_alert='<?php echo $show_alert; ?>';
		    var new_order='<?php echo $new_order; ?>';
			var show_pop='<?php echo $show_pop; ?>';
			var already_login='<?php echo $login_user_id; ?>';
			var newuser='<?php echo $newuser; ?>';
		    var show_alert='<?php echo $show_alert; ?>';
		    var otp_verified='<?php echo $otp_verified; ?>';
		    var password_created='<?php echo $password_created; ?>';
			$('#SectionModel').modal('hide');
			// alert(show_alert);
			// alert(password_created);
			// alert(otp_verified);
			
			if((show_alert=="n") && (password_created=="n") && (otp_verified=="y"))
			{
				 
				$('#PasswordModel').modal('show');
			}
			
			if(new_order=="y")
			{
				if(payment_alert=='y')
				{
					if(online_pay)
					{
						$('#OnlineModel').modal('show');
					}
				}
				else
				{
					// alert(show_alert);
				// alert(show_pop);
				if((show_alert=="y"))    
				{
					$('#newuser_model').modal('show'); 
				}   
			  
			   if((newuser=="y") && (show_alert=='y'))
			   {
				    
			   
					   
				   if(otp_verified=="n")
				   {
					   $('.otp_form').show();
					   var otp_count=$("#otp_count").val();
					    var usermobile=$("#user_mobile").val();
						  // var usermobile=+919001025477;
						   //target:'#picture';
							// $(this).hide();
							var data = {usermobile:usermobile, method: "sendotp"};
						  $.ajax({
							  
							  url :'functions.php',
							  type:'POST',
						  dataType : 'json',
						  data:data,   
							  success:function(response){
								  var data = JSON.parse(JSON.stringify(response));
								  if(data.status==true)
								  {
									  otp_count++;
									  
									  $("#otp_count").val(otp_count);
									  $("#system_otp").val(data.otp);
									 $(".otp_form").show(); 
								  }
								  
								}		  
						  });
						   // setTimeout(function () {
											// $(".resend").show();
										// }, 15000);
				   }
				   else
				   {
					   
				   }
			   }
			   if((newuser=="n") && (show_alert=='y'))
			   {
				   // alert(already_login);
				   if(!already_login)
					{
						<?php  // unset($_SESSION['new_order']); ?>
					   $('.login_passwd_field').show();
					   $('.join_now').show();   
					   $('.login_ajax').show();
					   // $(".forgot-form").show();
					}
			   }
					
				}
				
			   
		   }
		   
		   
		}
   $(".login_ajax").click(function(){
		   $(this).removeClass(" btn-primary").addClass("btn-default");
		 setTimeout(function() {
			
         $(this).removeClass("btn-default").addClass("btn-primary");
			}.bind(this), 5000);
		    var usermobile=$("#user_mobile").val();
		    var login_password=$("#login_ajax_password").val();
			 $("#login_error").hide();
			if((login_password.length)>5)
			{
				  // alert(login_password);
					var data = {usermobile:usermobile,login_password:login_password};
					// alert(data);
					$.ajax({
					  
					  url :'login.php',
					  type:'POST',
					  dataType : 'json',
					  data:data,
					  success:function(response){
							 
						  if(response==1)
						  {
							  location.reload();
							  
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
	    $(".online_ajax").click(function(){
			var wallet=$("#text_payment option:selected" ).text();
			var payment_order_id=$("#order_id").val();
			var data = {wallet:wallet,payment_order_id:payment_order_id};
			$.ajax({
					  
					  url :'payment_submit.php',
					  type:'POST',
					  dataType : 'json',
					  data:data,
					  success:function(response){
						 
						   location.reload();
						}		  
				  });
	   });
	    $(".register_ajax").click(function(){
			// return false;
			var password_created='<?php  echo $password_created;?>';
			// alert(password_created);
			
				   $("#register_error").hide();
					$(this).removeClass(" btn-primary").addClass("btn-default");
				 setTimeout(function() {  
					
				 $(this).removeClass("btn-default").addClass("btn-primary");
					}.bind(this), 5000);
					var user_id=<?php echo $user_id; ?>;
					var order_id=$("#order_id").val();
					
					var login_password=$("#login_password").val();  
					var pass_length=login_password.length;
					if((pass_length==0) || (pass_length>5))
					{    
					var data = {user_id:user_id,register_password:login_password,save_password:"y"};
				   
					$.ajax({
					  
					  url :'passwordsave.php',
					  type:'POST',
					  dataType : 'json',
					  data:data,
					  success:function(response){
						  if(response==1)
						  {
							  location.reload();
							  $('#PasswordModel').modal('hide'); 
						  }
						  else
						  {
							 
							  // $("#login_error").show();
						  }
						  
						}		  
				  });
					}
					else
					{
						$("#register_error").html('The password must be six digit and above');
						$("#register_error").show();
						// $("#login_error").show();
					}
			
			 
			return false;
	   });
    $('#partitioned').on('keyup', function(){ // consider `myInput` is class...

  var user_input = $(this).val();
  // alert(user_input);
  var page_otp=$("#system_otp").val();
   var usermobile=$("#user_mobile").val();
  if(user_input.length % 4 == 0){
	  if(user_input==page_otp)
	  {
		  var data = {usermobile:usermobile,method:"otp_submit"};
		   
		$.ajax({
			  
			  url :'login.php',
			  type:'POST',
			  dataType : 'json',
			  data:data,
			  success:function(response){
				  if(response==1)
				  {
					  location.reload();
					  $('#newuser_model').modal('hide'); 
				  }
				  else
				  {
					 $('#register_error').html('Something Went wrong to validate otp,try again');
					  $("#login_error").show();
				  }
				  
				}		  
		  });
		
	  }
	  else
	  {
		  $('.otp_error').show();
	  }
   
  }
});
</script>
<script src="js/jquery.form.js"></script> 



<script>
setInterval(function(){ 
        
        if( !(hasClass( document.getElementById("newuser_model"), "show" )) ) {

            window.location.reload();

        }
    }, 
    60000);    

 </script>
