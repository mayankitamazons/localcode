<?php

include("config.php");

if($_SESSION['login']=='')
{
	include("dlogin.php");
}
// print_R($_SESSION);
// die;
$current_time = date('Y-m-d H:i:s');
function ceiling($number, $significance = 1)
								{
									return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
								}
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
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
	header("location:logout.php");
}
// if(!isset($loginidset) || empty($loginidset))
// {
    // header("location:logout.php");
// }else{
    // if(!checkSession()){
        // header("location:logout.php");
    // }
// }
/* code for limit  */
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
$user_roles=$profile_data['user_roles'];
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
$parent_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$loginidset'"));
 $cash_check=$parent_data['cash_system'];
 $total_table=$parent_data['total_table'];

$cash_match="n";
  $cash_allow=$_SESSION['cash_allow'];

 $cash_id=$_SESSION['cash_id'];
if($total_table)
{
	// get all section of merchant 
	$section_data = mysqli_fetch_all(mysqli_query($conn, "SELECT name,id,user_id FROM sections WHERE user_id='$loginidset'"));
	// print_R($section_data);
	// die;
}
if($cash_allow)
{
	if($cash_check=="on")
	{
		
		if($cash_id)
		{
			$cash_match="y";
		}
	}
}
// echo $cash_match;
// die;
$_SESSION['cash_allow']='';
$stock_inventory=$profile_data['stock_inventory'];
$process_password_need=$profile_data['process_password_need'];
if($process_password_need)
{
	$p_class="process_upss_delete_special";
	
}

else
{
	$p_class="";
}
if($stock_inventory=="on")
{
   $stockq="select * from order_list_2019 where merchant_id='".$loginidset."' and order_place='live' and stock_check='n'";

	$stockquery=mysqli_query($conn,$stockq);  
   $i=1;   $ps=0;
   while ($r=mysqli_fetch_assoc($stockquery)){
	   // print_r($r);
	   // die;
	   $s_p_id=$r['id'];
	     $parray=explode(",",$r['product_id']);
	     $qarray=explode(",",$r['quantity']);
		// $qarray=explode(",",$qty_list);
		foreach($parray as $s_id)
			{
				 $qty_s=$qarray[$ps];
				
				$sdetail= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$s_id."'"));
				$parent_id=$sdetail['parent_id'];
				$stock_value=$sdetail['stock_value'];
				
				if($parent_id)
				{
					$sdetail= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$parent_id."'"));	
					$single_p_id=$parent_id;
				}
				else
				{
					$single_p_id=$s_id;
				}
				$maintain_stock=$sdetail['maintain_stock'];
				// print_R($sdetail);
				// die;
				if($maintain_stock=="on")
				{
					 $old_pending_stock=$sdetail['pending_stock'];
					
					$p_name=$sdetail['product_name'];
					$reorder_level=$sdetail['reorder_level'];
					
					if($stock_value>1)
					{
						$qty_s=$stock_value*$qty_s;
					}
					  $new_stock=$old_pending_stock-$qty_s;
					// echo $new_stock;
					// die;
					if($new_stock<=0)
					{  
						
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock',on_stock='0' WHERE id='$single_p_id'");
							
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock',on_stock='0' WHERE id='$s_id'");
						$noti=$p_name."is Reached Below Reorder Level,Refill it";
						mysqli_query($conn, "INSERT INTO `stock_notification` (`product_id`, `product_name`, `current_stock`, `reorder_level`, `notification`,`merchant_id`) VALUES ('$single_p_id', '$p_name', '$new_stock', '$reorder_level','$noti','$merchant_id')");
					
					}
					else
					{
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock' WHERE id='$single_p_id'");	
					}  
					if($update)
					{
						$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`, `order_id`, `comment`,`child_id`) VALUES ('$single_p_id','$qty_s', 'out', '$order_id', 'productsell','$s_id')";
						mysqli_query($conn,$qu);   
					}
				}
				$ps++;
			}
		$qu="UPDATE `order_list_2019` SET `stock_check` = 'y' where id='$s_p_id'";
		mysqli_query($conn,$qu);   
   }	   
	
}
$sql = "SELECT count(id) as total_count FROM order_list_2019 WHERE merchant_id ='".$loginidset."'";
$row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
$rec_limit = 25;
 $rec_count = $row['total_count'];

if( isset($_GET{'page'} ) ) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page ;
         }else {
            $page = 0;
            $offset = 0;
         }
         
$left_rec = $rec_count - ($page * $rec_limit);
/* end  for limit  */
if(isset($_GET['q']) && isset($_GET['cr'])){
    $get_query = $_GET['q'];
    $field_raw = $_GET['cr'];
    $fields_list = ['phone_no'      =>   'order_list_2019.user_mobile',
                    'prod_no'       =>   'order_list_2019.product_code',
                    'invoice_no'    =>   'order_list_2019.invoice_no',
                    'table'         =>   'order_list_2019.table_type'
                    ];
    $field = $fields_list[$field_raw];
	$query="SELECT order_list_2019.*, sections.name as section_name FROM order_list_2019 left join sections on order_list_2019.section_type = sections.id WHERE merchant_id ='".$loginidset."' AND $field LIKE '%$get_query%' ORDER BY order_list_2019.created_on DESC LIMIT $offset, $rec_limit";
}else{
	  $query="SELECT order_list_2019.*, sections.name as section_name FROM order_list_2019 left join sections on order_list_2019.section_type = sections.id WHERE merchant_id ='".$loginidset."' ORDER BY order_list_2019.created_on DESC LIMIT $offset, $rec_limit";
}
// echo $query;
// die;
$total_rows = mysqli_query($conn,$query);
$total_rows1 = mysqli_query($conn,$query);
 $last_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT max(id) as max_id FROM order_list_2019 WHERE merchant_id ='".$loginidset."'"))['max_id'];

$merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$loginidset."'"));
$pending_time = $merchant_name['pending_time'];
$alaram_required = $merchant_name['alaram_required'];
$sstper = $merchant_name['sst_rate'];
if($_SESSION["langfile"]=='')
	$_SESSION['langfile']='english';
require_once ("languages/".$_SESSION["langfile"].".php");
 $i =1; 
$pending_data = array();

while ($row=mysqli_fetch_assoc($total_rows1)){
     // print_R($row);
	 // die;
    $created =$row['created_on'];
    $date=date_create($created);
    $dteDiff  = date_diff($date, date_create($current_time));
    $diff_day = $dteDiff->d;
    if($diff_day != '0') $diff_day .= ' days ';
    else $diff_day = '';
    $diff_hour = $dteDiff->h;
    if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
    $diff_minute = $dteDiff->i;
    if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute;
    $diff_second = $dteDiff->s;
    if($diff_second < 10) $diff_second = '0'.$diff_second;
    $diff_time = $diff_day.' '.$diff_hour.$diff_minute.$diff_second;
    if($diff_day == '')
      $diff_time = $diff_hour.$diff_minute;
    $diff_total_minute = 60 * $diff_hour + 60 * 24 * $diff_day + $diff_minute;
    $new_time = explode(" ",$created);
    if((intval($diff_total_minute) > intval($pending_time)) && ($row['status'] == 0)){ 
        $item = array("date" => $date, "new_time" => $new_time[1], 'diff_time' => $diff_time, 'invoice_no' => $row['invoice_no'], 'table_no' => $row['table_type'],'section_no'=>$row['section_name']);
        array_push($pending_data, $item);
    }
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
		blink {
-webkit-animation-name: blink; 
-webkit-animation-iteration-count: infinite; 
-webkit-animation-timing-function: cubic-bezier(1.0,0,0,1.0);
-webkit-animation-duration: 1s;
}
blink {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
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
	#mep_0
	{
		
	  display:none !important;
	}
	div.fixed {
		  position: fixed;
		  bottom: 0;
		  right: 0;
		  width: 100%;
		  text-align:right;
		  border: 3px solid #73AD21;
		}
    </style>
	<style type="text/css">
 

/* Gradient text only on Webkit */
.warning {
  background: -webkit-linear-gradient(45deg,  #c97874 10%, #463042 90%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  color: #8c5059;
  font-weight: 400;
  margin: 0 auto 6em;
  max-width: 9em;
}

.calculator {
  font-size: 18px;
  margin: 0 auto;
  width: 10em;
  &::before,
  &::after {
    content: " ";
    display: table;
  }
  
  &::after {
    clear: both;
  }
}

/* Calculator after dividing by zero */
.broken {
  animation: broken 2s;
  transform: translate3d(0,-2000px,0);
  opacity: 0;
}

.viewer {
  color: #c97874;
  float: left;
  line-height: 3em;
  text-align: right;
  text-overflow: ellipsis;
  overflow: hidden;
  width: 7.5em;
  height: 3em;
}

.button {
  border: 0;
  background: #99e1dc;
  color: #000;
  cursor: pointer;
  float: left;
  font: inherit;
  margin: 0.20em;
  width: 2em;
  height: 2em;
  transition: all 0.5s;
  
  &:hover {
    background: #201e40;
  }
  
  &:focus {
    outline: 0; // Better check accessibility

    /* The value fade-ins that appear */
    &::after {
      animation: zoom 1s;
      animation-iteration-count: 1;
      animation-fill-mode: both; // Fix Firefox from firing animations only once
      content: attr(data-num);
      cursor: default;
      font-size: 100px;
      position: absolute;
           top: 1.5em;
           left: 50%;
      text-align: center;
      margin-left: -24px;
      opacity: 0;
      width: 48px;    
    }
  }
}

/* Same as above, modified for operators */
.ops:focus::after {
  content: attr(data-ops);
  margin-left: -210px;
  width: 420px;
}

/* Same as above, modified for result */
.equals:focus::after {
  content: attr(data-result);
  margin-left: -300px;
  width: 600px;
}

/* Reset button */

.reset {
  background: rgba(201,120,116,.28);
  color:#c97874;
  font-weight: 400;
  margin-left: -77px;
  padding: 0.5em 1em;
  position: absolute;
    top: -20em;
    left: 50%;
  width: auto;
  height: auto;
  
  &:hover {
    background: #c97874;
    color: #100a1c;    
  }
  
  /* When button is revealed */
  &.show {
    top: 20em;
    animation: fadein 4s;
  }
}

/* Animations */

/* Values that appear onclick */
@keyframes zoom {
  0% { 
    transform: scale(.2); 
    opacity: 1;
  }
  
  70% { 
    transform: scale(1); 
  }
  
  100% { 
    opacity: 0;
  }
}

/* Division by zero animation */
@keyframes broken {
  0% {
    transform: translate3d(0,0,0);
    opacity: 1;
  }

  5% {
    transform: rotate(5deg);
  }

  15% {
    transform: rotate(-5deg);
  }

  20% {
    transform: rotate(5deg);
  }

  25% {
    transform: rotate(-5deg);
  }

  50% {
    transform: rotate(45deg);
  }

  70% {
    transform: translate3d(0,2000px,0);
    opacity: 1;
  }

  75% {
    opacity: 0;
  }

  100% {
    transform: translate3d(0,-2000px,0);
  }
}

/* Reset button fadein */
@keyframes fadein {
  0% {
    top: 20em;
    opacity: 0;
  }
  
  50% {
    opacity: 0;
  }
  
  100% {
    opacity: 1;
  }
}

@media (min-width: 420px) {
  .calculator {
    width: 12em;
  }
  .viewer {
    width: 8.5em;
  }
  .button {
    margin: 0.5em;
  }
}

@media (max-width: @screen-xs-min) {
  .modal-xs { width: @modal-sm; }
}

.modal-lg {
  max-width: 900px;}
@media (min-width: 768px) {
   .modal-lg {
    width: 100%;
  } 
}
@media (min-width: 992px) {
  .modal-lg {
    width: 900px;
  }
}

.blinking{
    animation:blinkingText 1.5s infinite;
	color:red;
	
}
@keyframes blinkingText{
    0%{     color: red;    }
    49%{    color: transparent; }
    50%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color:red;    }
}
    </style>
    <?php include("includes1/head.php"); ?>
	<?php 
	header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	?>
</head>
<body class="header-light sidebar-dark sidebar-expand pace-done">
<!--  
  <?php 
     if($_SESSION['pos']=="y")
		{
		  
			unset($_SESSION['pos']);
			 $_SESSION['pos']="";   
	   ?>
	   <meta http-equiv="Refresh" content="3; url=<?php echo $site_url;?>/pos.php" />
	   <?php 
		}
		  
   ?>
   !-->
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
				 
                    <?php if (count($pending_data) > 0){?>
                        <h5 style="color: red;"><?php echo $language['invoice_not_yet']; ?></h5>
                        <div style="width: 380px; max-height: 300px; overflow: auto;">
                        
                            <table class="table table-striped" >
                              <thead>
                                <tr>
                                 <th><?php echo $language["date_of_order"];?></th>
                                  <th><?php echo $language['invoice_number']; ?></th>
                                  <th><?php echo $language['section_number']; ?></th>
                                  <th> <?php echo $language['table_number']; ?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php  $i =1;
                                    foreach ($pending_data as $key => $value) {
										
										?>
                                        <tr style="text-align: center;">
                                            <td><?php echo date_format($value['date'],"Y/m/d"). ' ';
                                                 echo $value['new_time'].'<br>';?>
                                              <p style="color: red; margin-bottom: 0px;"> <?php  echo $value['diff_time']; ?></p>
                                                
                                            </td>
                                            <td style="font-size: 20px; cursor: pointer; text-decoration: underline;" class="pending_invoice_no" invoice-no="<?= $value['invoice_no'];?>"><?= $value['invoice_no'];?></td>
                                            <td style="font-size: 20px;"><?= $value['section_no'];?></td>
                                            <td style="font-size: 20px;"><?= $value['table_no'];?></td>
                                        </tr>
                                    <?php } ?>
                              </tbody>
                            </table> 
                    </div>
                    <?php }?>
                    
                    
                    <div>
                        <h3><?php //echo $language['order_list_2019'];?></h3>
                        <span style="cursor: pointer; color:blue;text-decoration:underline;font-size: 40px;" id="scan_order"><?php echo $language['scan_order'];?></span>
						&nbsp;&nbsp;&nbsp;&nbsp;<span style="cursor: pointer; color:green;text-decoration:underline;font-size: 40px;" id="scan_order1"><?php echo $language['table_combine'];?></span>
						<p><a href="pos.php" style="cursor: pointer; color:green;text-decoration:underline;font-size: 40px;margin-left:20%;">Place Order</a></p>
						<?php if($alaram_required=="y"){ ?>
						 <span class="alaram" style="display:none;">
						 <img id="alaram_noise" src="https://i.gifer.com/McRx.gif" style="max-width:200px;"/>
						 <button id="stop_alaram" class="btn btn-primary" style="font-size:28px;">Stop It</button>
					     </span>  <?php } ?>
						 
					</div>
					 <div class="form-group" style="margin-top: 20px;">
						  <form id="criteria_form">
							<div class="row">
							  <div class="col-sm-2">
								<select class="form-control" name="cr" id="criteria_field">
								  <option <?php echo (!isset($_GET['cr']) || $_GET['cr'] == "invoice_no") ? "selected" : ""; ?> val="invoice_no"><?php echo $language['invoice_number'];?></option>
								  <option <?php echo ($_GET['cr'] == "table") ? "selected" : ""; ?> val="table"><?php echo $language['table_number'];?></option>
								  <option <?php echo ($_GET['cr'] == "prod_no") ? "selected" : ""; ?> val="prod_no"><?php echo $language['product_code'];?></option>
								  <option <?php echo ($_GET['cr'] == "phone_no") ? "selected" : ""; ?> val="phone_no"><?php echo $language['telephone_number'];?></option>
								</select>
							  </div>
							  <div class="col-sm-2">
								<input type="text" class="form-control" name="q" id="criteria_query" placeholder="Enter your criteria ...">
							  </div>
							</div>
							<div class="row">
								<div class="col-sm-1" style="margin-top:20px;max-width:150px;">
									<button type="button" class="btn btn-secondary" id="apply_criteria"><?php echo $language['apply_criteria'];?></button>
							  </div>
								<div class="col-sm-1" style="margin-top:20px">
									<button type="button" class="btn btn-danger" onclick="window.location.href='./orderview.php'"><?php echo $language['clear_criteria'];?></button>
							  </div>
							  <div class="col-sm-1" style="margin-top:20px">
									<a href="sync.php" class="btn btn-primary">Local Coin Sync</a>
							  </div>
							  <div class="col-sm-1" style="margin-top:20px">
								<a href="syncsubscription.php" class="btn btn-primary">Sync subscription</a>
							  </div>
							</div>
							
						  </form>
						</div>
					
					 
                    <?php
                    $dt = new DateTime();
                    $today =  $dt->format('Y-m-d');
                    ?>
					<?php if($rec_count>25){ ?>        
					<p style="" class="pagecount">   
					 <?php
								if( $page > 0 ) {
									$last = $page - 2;
  									echo "<a href = \"$_PHP_SELF?" . (empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : $_SERVER['QUERY_STRING'] . "&") . "page=$last\">Last 25 Records</a> |";
  									echo "<a href = \"$_PHP_SELF?" . (empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : $_SERVER['QUERY_STRING'] . "&") . "page=$page\">Next 25 Records</a>";
								 }else if( $page == 0 ) {
									echo "<a href = \"$_PHP_SELF?" . (empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : $_SERVER['QUERY_STRING'] . "&") . "page=$page\">Next 25 Records</a>";
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									echo "<a href = \"$_PHP_SELF?" . (empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : $_SERVER['QUERY_STRING'] . "&") . "page=$last\">Last 25 Records</a>";
								 }
							?>
					</p>
					<?php } ?>
                    <table class="table table-striped">
                        <thead>
                         <tr>
						    <?php if($merchant_name['spassword'] && $merchant_name['spassword_need']){ ?> <th>Action</th> <?php  } ?>
                            <th><?php echo $language["items"];?></th>
                            <th><?php echo $language["date_of_order"];?></th>
                           
                            <th><?php echo $language["status"];?></th>
                            <th><?php echo $language["detail"];?></th>
                            <th><?php echo $language["short_section"];?></th>
							
                            
                            <th><?php echo $language["table_number"];?></th>
							<th><?php echo $language["invoice_number"];?></th>
                            <th><?php echo $language["internal_recipt"];?></th>
							<th><?php echo $language["customer_recipt"];?></th>
                            <!--th><?php echo $language["chat"];?></th!-->
                            
							 <th><?php echo $language["amount"];?></th>
							  <th><?php echo $language["quantity"];?></th>
                            <th><?php echo $language["total"];?></th>
							<?php if($sstper>0){ ?>
							 <th><?php echo "SST ".$sstper." %"?></th>
							 <!--th><?php echo "Grand Total (Inc ".$sstper." % SST)";?></th!-->
							 <th><?php echo "Grand Total";?></th>
							<?php } ?>
							 <th><?php echo $language["delivery_charges"];?></th>
							  <th><?php echo $language["membership_discount"];?></th>
							  <th><?php echo $language['coupon_discount']; ?></th>
							  <th><?php echo $language["final_total"];?></th>
                           
                            <th><?php echo $language['paid_by_wallet'];?></th>
                            <th><?php echo $language['bal_payment'];?></th>
                       
                           
                            <th class="product_name test_product"><?php echo $language["product_name"];?></th>
							<th class="product_name test_product" style="min-width: 350px;"><?php echo $language['varient'];?></th>
                            <th class="product_name test_product" style="min-width: 350px;"><?php echo $language["remark"];?></th>
                            <th><?php echo $language["product_code"];?></th>
                           
                            <th><?php echo $language["mode_of_payment"];?></th>
                            <th class="location_head"><?php echo $language["location"];?></th>
                            <th class="order_remark"><?php echo $language['order_remark'];?></th>
                            <th><?php echo $language["phone"];?></th>
							 <th><?php echo $language["username"];?></th>
                           
							<th><?php echo $language["delivery_services"];?></th>
                            <!-- <th><?php echo $language["print"];?></th> -->
                            <th>K1/K2</th>
							
							<th><?php echo $language["invoice"];?></th>
							<th><?php echo $language["kitchen"];?></th>
                        </tr>
                        </thead>
                         <tbody id="orderview-body">
                        <?php
                        $i =1;
                        while ($row=mysqli_fetch_assoc($total_rows)){
						$wallet=$row['wallet'];
						if($wallet=="myr_bal")
						$wal_label="MYR WALLET";
						else if($wallet=="inr_bal")
						$wal_label="KOO COIN";
						 else if($wallet=="usd_bal")
						$wal_label="CF WALLET";
						else if($wallet=="cash")
							$wal_label="CASH";
						else $wal_label=$wallet;
                        $product_ids = explode(",",$row['product_id']);
                        $quantity_ids = explode(",",$row['quantity']);
                        $amount_val = explode(",",$row['amount']);
                        $product_code = explode(",",$row['product_code']);
                        $amount_data = array_combine($product_ids, $amount_val);
                        $total_data = array_combine($quantity_ids, $amount_val);
                        $created =$row['created_on'];
                        $remark_ids = explode("|",str_replace("_", " ", $row['remark']));
                        $new_time = explode(" ",$created);
                        //$c = array_combine($product_ids, $quantity_ids);
                        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$row['product_id']."'"));
                       // $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));
                     
                        
                       
                        $date=date_create($created);
							
                        ?>
                        <?php
                        if($row['status'] == 1) $callss = "gr";
                        else if($row['status'] == 2) $callss = "or";
                        else $callss = " ";
                        $todayorder = $today == $new_time[0] ? "red" : "";
                        $i1 =1;  ?>
                        <tr id="<?php echo $row['invoice_no']; ?>" class="<?php echo $todayorder; ?> fdfd <?php echo $callss; ?>" data-id="<?php echo $row['id']; ?>">
                            <input type="hidden" class="merchant_<?php echo $row['id'];?>" value="<?php echo $merchant_name['name'];?>">
                            <input type="hidden" class="userphone_<?php echo $row['id'];?>" value="<?php echo $row['user_mobile'];?>" >
                            <input type="hidden" class="merchantphone_<?php echo $row['id'];?>" value="<?php echo $merchant_name['mobile_number'];?>" >
                            <input type="hidden" class="merchantaddress_<?php echo $row['id'];?>" value="<?php echo $merchant_name['google_map'];?>" >
							<input type='hidden' id='cash_id' name='cash_id' value='<?php echo $cash_id; ?>'/>
                            <?php if($i==1){ ?>
							<input type="hidden" value="<?php echo $last_id;?>"  id="last_id"/>
							<?php } ?>
							 	
							<?php if($merchant_name['spassword'] && $merchant_name['spassword_need']){ ?>
							<td class="table_number_<?php echo $row['id']?>"> Delete <p class="pop_upss_delete_special" data-id="<?php echo $row['id']; ?>"  style='margin-bottom: 0px;' data-prodid="<?php echo $row['id']; ?>"> <i class="fa fa-trash" aria-hidden="true"></i></p></td>
							<?php }?>
							<td><?php echo  $i ?></td>
                            <?php 
                              $dteDiff  = date_diff($date, date_create($current_time));
                              $diff_day = $dteDiff->d;
                              if($diff_day != '0') $diff_day .= ' days ';
                              else $diff_day = '';
                              $diff_hour = $dteDiff->h;
                              if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
                              $diff_minute = $dteDiff->i;
                              if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute.':';
                              $diff_second = $dteDiff->s;
                              if($diff_second < 10) $diff_second = '0'.$diff_second;
                              $diff_time = $diff_day.'<br>'.$diff_hour.$diff_minute.$diff_second;
							 
                            ?>
                            <td><?php echo date_format($date,"m/d/Y");  ?>
                                <?php echo '<br>'; echo $new_time[1] ?>
                                <?php 
                                  if($row['status'] == 0){?>
                                    <p style="color: red;"><?php echo $diff_time; ?></p> <?php 
                                  }?>
                            </td>
                           
                            <td>
                                <?php
                                if($row['status'] == 0)
								{
									$sta = "Pending";
									$s_color="red";
								}
                                else if($row['status'] == 1) 
								{
									$sta = "Done";
									$s_color="green";
								}
                                else 
								{
									$sta = "Accepted";
									$s_color="";
								}
								
                                ?>
								<input type="button" style="background-color:<?php echo $s_color;?>" class= "status btn btn-primary" value="<?php  echo $sta;?>" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"/>
							   <!--label class= "status btn btn-primary" status="<?php echo $row['status'];?>" data-invoce='<?php echo $row['invoice_no'];?>' data-id="<?php echo $row['id']; ?>"> <?php echo $sta; ?></label!-->
                              
							</td>
							<td class="s_order_detail btn btn-primary" order_id='<?php echo $row['id']; ?>'>Detail</td>
							   <td class="table_number_<?php echo $row['id']?>"><?php echo $row['section_name'];?></td>
							<td class="table_number_<?php echo $row['id']?>"><?php echo $row['table_type'];?></td>
                             <td>
                                <?php echo $row['invoice_no']; ?>
                            </td>
                            <td>
							 <a class="print-order <?php  if($row['auto_print']!='1'){ echo "blinking";} ?>" href="#" data-id="<?php echo $row['id']; ?>" data-invoice="<?php echo $row['invoice_no']; ?>"><?php echo $language['print_receipt']; ?></a>
							  
                            </td>
							 <td>
							 <a class="normal_print" href="#" data-id="<?php echo $row['id']; ?>" data-invoice="<?php echo $row['invoice_no']; ?>"><?php echo $language['print_receipt']; ?></a>
							  
                            </td>
							
                            <!--td><a target="_blank" href="<?php echo $site_url; ?>/chat/chat.php?sender=<?php echo $loginidset?>&receiver=<?php echo $row['user_id'];?>"><i class="fa fa-comments-o" style="font-size:25px;"></i></a></td!-->
                           
                            <td class="amount_<?php echo $row['id'];?>">

                                <?php
							
                                $q_id = 0;

                                foreach ($amount_val as $key => $value){

                                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
								
                                    if($value == '0') { ?>

                                        <p class="pop_upss" data-id="<?php echo $row['id']; ?>"  style='margin-bottom: 0px;' data-prodid="<?php echo $product_ids[$key]; ?>"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>

                                    <?php  }

                                    if( $quantity_ids[$key] && $value ) {
										
                                        echo @number_format($value, 2).'<br>';

                                    } else {

                                        echo '0<br>';
										// echo '<p class="pop_upss" data-id=' . $row['id'] . '  style="margin-bottom: 0px;display:block;" data-prodid="' . $key . '""><i class="fa fa-pencil-square-o" aria-hidden="true"></i>0</p>';

                                    }

                                    $q_id++;

                                } ?>

                            </td> 
							<td class="quantity_<?php echo $row['id'];?>"><?php
							
                                foreach ($quantity_ids as $key)
                                {  
                                    echo $key;
                                    echo '<br>';
                                }
                                ?></td>
                            <td class="total_order total_<?php echo $row['id']?>">
                                <?php
								
								 
								
                                $total = 0;
                                foreach ($amount_val as $key => $value){
                                    if( $quantity_ids[$key] && $value ) {
                                        $total =  $total + ($quantity_ids[$key] *$value );
                                    } 
                                }
                                echo  @number_format($total, 2);
                                ?>
                            </td>
							<?php if($sstper>0){ ?>
							<?php $incsst = ($sstper / 100) * $total;
							    $incsst=@number_format($incsst, 2);
								$incsst=ceiling($incsst,0.05);
								 $incsst=@number_format($incsst, 2);
							    $g_total=@number_format($total+$incsst, 2);
							 ?>
							  <td><?php echo $incsst; ?></td>
							  <td><?php  echo $g_total;?></td>
							  
							<?php } else { $g_total=$total;} ?>
							
							<td><?php  echo @number_format($row['order_extra_charge'],2); ?></td>
							<td><?php  echo @number_format($row['membership_discount'],2); ?></td>
							<td><?php echo @number_format($row['coupon_discount'],2); ?></td>
							<td><?php  echo @number_format(($g_total+$row['order_extra_charge'])-($row['membership_discount']+$row['coupon_discount']),2); ?></td>
							
							<td><?php  echo @number_format($row['wallet_paid_amount'],2); ?></td>
							<td><?php echo @number_format(($g_total+$row['order_extra_charge'])-($row['wallet_paid_amount']+$row['membership_discount']), 2); ?></td>   
                           
                          
                            <td class="products_namess product_name_<?php echo $row['id'];?> test_productss" ><?php foreach ($product_ids as $key )
                                {
									
                                    if(is_numeric($key))
                                    {
                                        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                        echo $product['product_name'].'<br>';
                                    }
                                    else
                                    {
                                        echo $key.'<br>';
                                    }
                                }
                                ?>
                            </td>
							<td><?php
							 // $row['varient_type'];
							// die;
							 // print_R($row['varient_type']);
							// die;
							 if($row['varient_type'])
							 {
						$v_str=$row['varient_type'];
							$v_array=explode(",",$v_str);
							  
							foreach($v_array as $vr)
							{
								
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									// echo "SELECT * FROM sub_products WHERE id  in ($v_match)";
									// die;
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										echo $srow['name'];
										// echo "&nbsp;&nbsp;";
									}
								}
								 else
								 {
									 // echo "</br>";
								 }
								 echo "<br/>";
							}
							 }
							  ?>
							</td>
                            <td><?php   foreach ($remark_ids as $vall)

                                {



                                    echo $vall.'<br>';



                                } ?></td>



                            <td>
                                <?php
                                foreach ($product_code as $key)
                                {
                                    echo $key.'<br>'; }
                                ?>
                            </td>
                           
                            
                             <td><?php echo $wal_label;  ?></td>  
                            <td class="location_<?php echo $row['id']; ?> new_tablee">
							<a class="" target="_blank" href="http://maps.google.com/maps?q=<?php echo  $row['location']; ?>">  
						
							<?php echo $row['location'];?></a></td>
							 <td><?php echo $row['remark_extra']; ?></td>
                              <td><?php echo $row['user_mobile']; ?></td>
							   <td class="username_<?php echo $row['id'];?>"><?php echo $row['user_name']; ?></td>
                            <td><a onclick="copy_orderDetail(<?php echo $row['id']?>)" href="#" class="delivery" id="<?php echo $row['id'];?>"><i class="fa fa-truck" style="font-size:25px;"></i></a></td>
                            <td><?php echo $wal_label;  ?></td>   
                            <?php if($sta == "Done"){?>
                                <td></td>
                            <?php }?>
							 <td><a target="_blank" href="print.php?id=<?php echo $row['id'];?>&merchant=<?php echo $loginidset?>">Print</a></td>
							  <td>
                                <?php if($row['status'] == 2){?>
                                    <a target="_blank" href="print_kitchen.php?id=<?php echo $row['id'];?>&merchant=<?php echo $loginidset?>">Print</a>
                                <?php }?>
                            </td>
                        </tr>
                        <?php   $i++; }
                        ?>
                        </tbody>  
                    </table>  
					<?php if($rec_count>25){ ?>    
					<p style="">
					 <?php
								if( $page > 0 ) {
									$last = $page - 2;
									echo "<a href = \"$_PHP_SELF?page=$last\">Last 25 Records</a> |";
									echo "<a href = \"$_PHP_SELF?page=$page\">Next 25 Records</a>";
								 }else if( $page == 0 ) {
									echo "<a href = \"$_PHP_SELF?page=$page\">Next 25 Records</a>";
								 }else if( $left_rec < $rec_limit ) {
									$last = $page - 2;
									echo "<a href = \"$_PHP_SELF?page=$last\">Last 25 Records</a>";
								 }
							?>
					</p>
					<?php } ?>

						
						
                    <div style="margin:0px auto;">
                        <ul class="pagination">
                            <?php
                            /*for($i = 1; $total_page_num && $i <= $total_page_num; $i++) {
                             if($i == $page) {
                              $active = "class='active'";
                             }
                             else {
                              $active = "";
                             }
                             echo "<li $active><a href='?page=$i'>$i</a></li>";
                            }*/
                            ?>
                        </ul>
                    </div>
					
						<div>
						 <div class="modal fade" id="myScanModal" role="dialog">
                            <div class="modal-dialog modal-lg" style="width: 1100px; max-width:1100px;">
                                <!-- Modal content-->
                                <div class="modal-content" style="border-radius: 4px;background-color: transparent;    padding: 0px;">
                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color:#99e1dc57;margin: 0px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="color: #3a3939c4;"><?php echo $language['statement']; ?></h4>
                                    </div>
                                    <div style="background-color:#99e1dc;padding: 10px;min-height:695px;">
                                        

                                      
                                        <div class="col-sm-12">
                                        <div class="col-sm-7" style="float: left;">
                                           <div class="inline fields">
                                            <div style="display: inline-block;height: 50px;">
                                                <label style="display: inline-block;height: 50px;">Barcode</label>
                                                <input type="text" id="barcode" autofocus style="display: inline-block;height: 50px;">
                                            </div>
                                            <div style="display: inline-block;height: 50px;">
                                                <button style="width: 100px; height: 50px;background-color: #99e1dc;" id="add_invoice"><?php echo $language['add'];?></button>
                                            </div>
                                        </div>
                                        <form id="scan" style="height: 476px; padding-top: 10px;">
                                            <table style="width: 100%;">
                                                <thead>
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">No</th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;"><?php echo $language['invoice_number']; ?></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;"><?php echo $language['qty']; ?></th>
                                                <th style="padding-left: 5px;"><?php echo $language['amount']; ?></th>
                                                </thead>
                                                </thead>
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;height: 357px;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;" id="scanned_data">
                                                    </tbody>
                                                </table>
                                            </div>
											 <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['total']; ?></span>
                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty1" id="tol_qty1" value="">
                                                <span id="total_amount" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt1" id="tol_mnt1" value="">
                                            </div>   

                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['paid']; ?></span>
                                                <input type="text" id="paid2" value="0" class="amount" name="paid2" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											  <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid By Koocoin</span>
		<input type="text" id="paidkoocoin" value="0" class="amount" readonly name="paidkoocoin" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['change']; ?></span>
                                                <input type="text" id="change2" name="change" class="amount" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%; border: 1px solid #555555;padding-left: 4px;">
                                            </div>
                                            <!--div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                <span id="total_amount2" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                            </div!-->
                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">
                                                <!--button style="width:200px;height:50px;background-color: #99e1dc;">Submit</button!-->
												 <button id="amount_submit_button" class="<?php echo $p_class; ?>" style="width:261px;height:65px;background-color: #99e1dc;float: left;"><?php echo $language['submit']; ?></button>
												 <a  style="color:white;font-size:20px;width: 306px;height: 65px;float: left;" class="btn btn-primary status_ewallet"><?php echo $language['e_wallet']; ?></a>
											 
                                            </div>
                                        </form>
                                        </div>
                                        <div  class="col-sm-5" style="float: left;">

                                                <form id="scan" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;"><?php echo $language['calculator']; ?></th>
                                               
                                                </thead>
                                               
                                            </table>
                                            <style>
                                                .jin_calculator {
                                                    width: 20em;
                                                    padding-top: 1em;
                                                }

                                                .jin_button {
                                                    width: 4em;
                                                    height: 4em;
                                                    margin-top: 0;
                                                    margin-bottom: 1em;
                                                }

                                                .jin_viewer {
                                                    width: 13.5em;
                                                    height: 4em;
                                                    margin-top: 0;
                                                    margin-bottom: 1em;
                                                    line-height: 4em;
                                                }
                                            </style>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            <div id="calculator2" class="calculator jin_calculator">

                                              <input type="button" id="clear2" class="clear button jin_button" value="C">

                                              <div id="viewer2" class="viewer jin_viewer">0</div>

                                              <input type="button" class=" num2 button jin_button" data-num="7" value="7" id="7" >
                                              <input type="button" class="num2 button jin_button" data-num="8" value="8" id="8" >
                                              <input type="button" class="num2 button jin_button" data-num="9" value="9" id="9">
                                              <input  type="button" data-ops="plus" class="ops2 button jin_button" value="+">

                                              <input type="button" class=" num2 button jin_button" data-num="4" value="4" id="4">
                                              <input type="button" class="num2 button jin_button" data-num="5" value="5" id="5">
                                              <input type="button" class="num2 button jin_button" data-num="6" value="6" id="6">
                                              <input type="button" data-ops="minus" class="ops2 button jin_button" value="-">

                                              <input type="button" class="num2 button jin_button" data-num="1" value="1" id="1" >
                                              <input type="button" class="num2 button jin_button" data-num="2" value="2" id="2">
                                              <input type="button" class="num2 button jin_button" data-num="3" value="3" id="3" >
                                              <input type="button" data-ops="times" class="ops2 button jin_button" value="*">

                                              <input type="button" class="num2 button jin_button" data-num="0" value="0" id="0" >
                                              <input type="button" class="num2 button jin_button" data-num="." value="." id="." >
                                              <input type="button" id="equals2" class="equals2 button jin_button" data-result="" value="=">
                                              <input type="button" data-ops="divided by" class="ops2 button jin_button" value="/">
                                            </div>
                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    
                                        </form>
                            
                                        </div>
                                    </div>
                                        
                                    </div>
                                </div>
                            </div>
                            </div>
					   

                        </div>
                       
						 <!---amit code --->

                           <div class="modal fade" id="myScanModal1" role="dialog">
                             <div class="modal-dialog modal-lg" style="width:1100px; max-width:1100px;">
                                <!-- Modal content-->
                                <div class="modal-content" style="border-radius: 4px;background-color: transparent;    padding: 0px;">
                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color:#296ca0;margin: 0px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="color: #3a3939c4;"><?php echo $language['statement']; ?></h4>
                                    </div>
                                    <div style="background-color: #6dafe2;padding: 10px;">
                                        

                                        <div class="inline fields">
                                            <div style="display: inline-block;">
                                                <label  style="display: inline-block;"><?php echo $language['merchant_id'];?> :</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="text" name="merchantid"  autofocus style="display: inline-block; height: 35px;" value="<?php echo $loginidset  ; ?>" readonly>
                                                <button style="display: none; width: 100px; background-color: red;" id=""><?php echo $language['merchant_id'];?></button>
                                            </div>
                                            <div style="display: inline-block;margin-left: 8%;">
                                                
                                                <label style="display: inline-block;"><?php echo $language['counter_number'];?> :</label>
                                                <input type="text" autofocus style="display: inline-block;height: 35px;" readonly>
                                                <button style="display: none;background-color: red;" id=""><?php echo $language['invoice']; ?></button>
                                            </div>
                                            <div style="display: inline-block;">
                                                <label style="display: inline-block;"><?php echo $language['invoice_number']; ?> :</label>
                                                <input type="text" id="invoice_num" class="invoice_num"  autofocus style="display: inline-block;height: 35px;">
                                                <button style="background-color: red;height: 50px;" data-toggle="modal" data-target="#InvoiceModel" id="invoice"><?php echo $language['invoice']; ?></button>
                                            </div>  
                                             
                                            <div style="display: inline-block;width:41%;">
                                                &nbsp;
                                                <label style="display: inline-block;"><?php echo $language['long_table_number']; ?> :</label>
                                                <input type="text" id="table_num" class="table_num" autofocus style="display: inline-block;height: 35px;">
                                                <button style="background-color: green;height: 60px;width:23%" data-toggle="modal" data-target="#myModalt" id="table"><?php echo $language['table']; ?></button>
                                            </div>
                                            <div style="display: inline-block;">
                                                <button style="background-color: #99e1dc;width:161px;height:50px;" id="add_invoicemy"><?php echo $language['add']; ?></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                        <div class="col-sm-7" style="float: left;">
                                            <form id="form" style="" method="POST" action="statement.php">
                                             <?php
                                               $pin = mt_rand(1000, 9999);
                                             ?>
                                              
                                           <input type="hidden" name="statementid" id="statementid" value="<?php echo $pin ;?>">
                                           <input type="hidden" name="merchant_ses" id="merchant_ses" value="<?php echo $loginidset;?>">
                                            <table style="width: 100%;" id="mytbl">
                                                <thead>
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 8%;"><center>No</center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 45%;"><center><?php echo $language['username']; ?></center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 22%;"><center><?php echo $language['table_number']; ?></center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 18%;"><center><?php echo $language['invoice_number']; ?></center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 7%;"><center><?php echo $language['qty']; ?></center></th>
                                                <th style="padding-left: 14px;"><center><?php echo $language['amount']; ?></center></th>
                                                </thead>
                                                </thead>
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;height: 205px;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;" id="scanned_data1">
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['total']; ?></span>
                                                <span id="total_qty1" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty1" id="tol_qty1" value="">
                                                <span id="total_amount1" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt1" id="tol_mnt1" value="">
                                            </div>
											<div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Discount</span>
                                                <input type="text" id="discount" value="0" class="amount changenumber" name="paid" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											<div style="display: flex;font-weight:bold;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Discount in %</span>
                                                <input type="text" id="discountper" value='0' placeholder="Enter % Discount" class="amount changenumber" name="discountper" style=" font-size: 20px; width:9%;border: 1px solid #555555 ;padding-left: 4px;">%
												<input type="hidden" id="discount_per_input" name="discount_per_input"/>
												
												<span id="discount_per" style="font-size: 20px; width: 30%;border: 1px solid;padding-left: 4px;margin-left:14%;"></span>
                                               
                                               
												
                                            </div>
											<div style="padding-top: 5px;font-weight:bold;display: flex;" class="input-has-value">
											    <select id="select_wallet" class="form-control" name="select_wallet" style="font-size:18px;">
												<option value='-1'> Select Wallet</option>
												<option  value='wechat'>Wechat</option>
												<option  value='boostpay'>Boost Pay</option>
												<option  value='grabpay'>Grab Pay</option>
												<option  value='touch'>Touch & Go</option>
												<option  value='fpx'>FPX</option>
												</select>
                                               
												
                                                <input type="text" id="wallet_paid" value="0" class="amount changenumber" name="wallet_paid_amount" style=" font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['paid']; ?></span>
                                                <input type="text" id="paid" value="0" class="amount changenumber" name="paid" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;"><?php echo $language['change']; ?></span>
                                                <input type="text" readonly id="change" name="change" class="amount changenumber" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%; border: 1px solid #555555;padding-left: 4px;">
                                            <input type="hidden" name="discount_amount" id="discount_amount" value="0"/>
											<input type="hidden" name="paid_amount_pos" id="paid_amount_pos" value="0"/>
											<input type="hidden" name="change_pos" id="change_pos" value="0"/>
											</div>

                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">
                                                
                                                <button type="button" style="width:200px;height:50px;background-color: #99e1dc;" data-dismiss="modal"><?php echo $language['close']; ?></button>
                                               <input type="submit" style="width:200px;height:50px;background-color: #99e1dc;" value="Print" onclick="this.form.target='_blank';return true;">
                                            
                                                <input type="button" style="width:200px;height:50px;background-color: #99e1dc;"  value="<?php echo $language['submit']; ?>" class="<?php echo $p_class; ?>" <?php if($p_class==''){?> onclick="myFunction()" <?php } ?>>
												<a style="color:white;font-size:20px;" class="status_ewallet btn btn-primary"><?php echo $language['e_wallet']; ?></a>
											 
											</div>
                                        </form>
                                        </div>
                                        <div  class="col-sm-5" style="float: left;">

                                                <form id="scan1" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;"><?php echo $language['calculator']; ?></th>
                                               
                                                </thead>
                                               
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            <div id="calculator" class="calculator jin_calculator">

                                              <input type="button" id="clear" class="clear button jin_button" value="C">

                                              <div id="viewer" class="viewer jin_viewer">0</div>

                                              <input type="button" class=" num button jin_button" data-num="7" value="7" id="7" >
                                              <input type="button" class="num button jin_button" data-num="8" value="8" id="8" >
                                              <input type="button" class="num button jin_button" data-num="9" value="9" id="9">
                                              <input  type="button" data-ops="plus" class="ops button jin_button" value="+">

                                              <input type="button" class=" num button jin_button" data-num="4" value="4" id="4">
                                              <input type="button" class="num button jin_button" data-num="5" value="5" id="5">
                                              <input type="button" class="num button jin_button" data-num="6" value="6" id="6">
                                              <input type="button" data-ops="minus" class="ops button jin_button" value="-">

                                              <input type="button" class="num button jin_button" data-num="1" value="1" id="1" >
                                              <input type="button" class="num button jin_button" data-num="2" value="2" id="2">
                                              <input type="button" class="num button jin_button" data-num="3" value="3" id="3" >
                                              <input type="button" data-ops="times" class="ops button jin_button" value="*">

                                              <input type="button" class="num button jin_button" data-num="0" value="0" id="0" >
                                              <input type="button" class="num button jin_button" data-num="." value="." id="." >
                                              <input type="button" id="equals" class="equals button jin_button" data-result="" value="=">
                                              <input type="button" data-ops="divided by" class="ops button jin_button" value="/">
                                            </div>
                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    
                                        </form>
                            
                                        </div>
                                    </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
						  <div class="modal fade" id="InvoiceModel" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content" style="width: 139%;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><?php echo $language['pending_invoice']; ?></h4>
                                        <button type="button" style="margin-right: 30%;width:130px;height:50px;background-color: #99e1dc;" data-dismiss="modal"><?php echo $language['close']; ?></button>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;">
                                            <div class="col-sm-12">
                                                <div class="form-group" id="invoice_list">
												<?php
													$Merchantid = $loginidset;



													$sql = "select * from order_list_2019 where status in(0,2) and merchant_id='$Merchantid' group by invoice_no limit 0,100";

													$rel = mysqli_query($conn, $sql);





														while($data = mysqli_fetch_assoc($rel))

														{   

														   

															echo ' <input type="button" style="margin: 10px; background-color:#296ca0;" data-id="'.$data['id'].'" class="btn btn-info" name="invo" value="'.$data["invoice_no"].'">';

															

														}

												?>
												</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="padding-bottom:2px;">
                                           <!-- <button>Submit</button>-->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  
						<div class="modal fade" id="orderdetailmodel" role="dialog">						
							<div class="modal-dialog">
							<!-- Modal content-->		
							<div class="modal-content">	
							<div class="modal-header">	
							<button type="button" class="close" data-dismiss="modal">&times;</button>						
							<h4 class="modal-title">Order Detail</h4>	
							</div>					
							<form id ="orderdetailform">		
							<div class="modal-body" style="padding-bottom:0px;">
							<div class="col-sm-10" id="orderdata">						
											
							</div>						
												
							</form>						
							</div>						
							</div>						
							</div>
						</div>
						<div class="modal fade" id="deletemodel" role="dialog">						
						<div class="modal-dialog">
						<!-- Modal content-->		
						<div class="modal-content">	
						<div class="modal-header">	
						<button type="button" class="close" data-dismiss="modal">&times;</button>						
						<h4 class="modal-title">Delete record</h4>	
						</div>					
						<form id ="data_spcialpassword">		
						<div class="modal-body" style="padding-bottom:0px;">
						<div class="col-sm-10">						
						<div class="form-group">				
						<label>enter special password</label>					
						<input type="password" name="spassword" id = "spassword" class="form-control" value="" required>						
						<input type="hidden" id="idspecialpass" name="idspecialpass" value="">	
						</div>						
						</div>						
						</div>						
						<div class="modal-footer" style="padding-bottom:2px;">
						<button><?php echo $language['submit']; ?></button>					
						</div>						
						</form>						
						</div>						
						</div>						
						</div>
						<div class="modal fade" id="deleteprocessmodel" role="dialog">						
						<div class="modal-dialog">
						<!-- Modal content-->		
						<div class="modal-content">	
						<div class="modal-header">	
						<button type="button" class="close" data-dismiss="modal">&times;</button>						
						<h4 class="modal-title">Process Order</h4>	
						</div>					
						<form id ="data_processpassword">		
						<div class="modal-body" style="padding-bottom:0px;">
						<div class="col-sm-10">						
						<div class="form-group">				
						<label>Enter process password</label>					
						<input type="password" name="process_password" id = "process_password" class="form-control" value="" required>						
						<input type="hidden" id = "scan_type" class="form-control">						
						<input type="hidden" id="idprocesspass" name="idprocesspass" value="">	
						</div>						
						</div>						
						</div>						
						<div class="modal-footer" style="padding-bottom:2px;">
						<button><?php echo $language['submit']; ?></button>					
						</div>						
						</form>						
						</div>						
						</div>						
						</div>
						<style type="text/css">
						  .blank_table
						  {
							  background: green;
							  padding: 2%;
							  text-align: center;
							  color:white;
							  
						  }
						  .process_table
						  {
							  background: yellow;
							  padding: 2%;
							  text-align: center;
						  }
						  .pending_table
						  {
							   background: red;
							  padding: 2%;
							  text-align: center;
							  color:white;
						  }
						</style>
						<div class="modal fade" id="myModalt" role="dialog" >
                            <div class="modal-dialog modal-lg">
                                
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Table Proccessing </h4>
                                        <button type="button" style="margin-right: 30%; width:130px;height:50px;background-color: #99e1dc;" data-dismiss="modal">Close</button>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;">
                                            <div class="col-sm-12">
                                                <div class="form-group" id="table_list">
												<?php
												   $Merchantid = $loginidset;
												$total_table;
												if($total_table>0)
												{
												foreach($section_data as $se)
												{
                                                    $sectionType=$se[1];
													$UserID=$se[2];
													$activeTableArray=[];	
													$getActiveTable=mysqli_query($conn,"SELECT table_type from order_list_2019 where section_type='$sectionType' AND merchant_id='$UserID' AND status='0'");
                                                    while($rowGetActivaTable=mysqli_fetch_assoc($getActiveTable))
                                                    {
                                                        $activeTableArray[]=$rowGetActivaTable["table_type"];
                                                    }
                                                    
													echo "<p> Section ".$se[0]."</p><div class='row'>";
													for($f=1;$f<=$total_table;$f++)
													{
														
													   if(in_array($f,$activeTableArray))
													   {
															echo "<div title='order' class='col-md-1 pending_table' onclick='pending_table(".$f.",".$sectionType.",".$UserID.")' style='margin:1%'>".$f."</div>";													   
													   }
													   else
													   {
														   echo "<div title='order' class='col-md-1 blank_table'  style='margin:1%;'>".$f."</div>";													   
													   }
													}
													echo "</div></br>";
												}     
												
												} else
												{
													$sql = "select order_list_2019.invoice_no,order_list_2019.id,order_list_2019.section_type,order_list_2019.table_type,sections.name from order_list_2019 inner join sections on order_list_2019.section_type=sections.id where order_list_2019.status in(0,2) and order_list_2019.merchant_id='$Merchantid' group by order_list_2019.invoice_no order by sections.name asc limit 0,30";
													$rel = mysqli_query($conn, $sql);
													while($data = mysqli_fetch_assoc($rel))
													{   
															$show_value=$data['invoice_no']."-".$data['name']."-".$data['table_type'];
													   // print_R($data);

														echo ' <input type="button" style="margin: 10px; background-color:#296ca0;" class="btn btn-info" name="tbl" data-invoce="'.$data['invoice_no'].'" data-id="'.$data['id'].'" data-section="'.$data['section_type'].'"  data-table="'.$data['table_type'].'"  value="'.$show_value.'">';

														

													}
												}
												?>
												</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="padding-bottom:2px;">
                                           <!-- <button>Submit</button>-->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!---amit code end--->
                        <!---vikas code-->
                        <div class="modal fade" id="myModalTable" role="dialog" >
                            <div class="modal-dialog modal-lg">
                                
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" id="VTitle">Table Proccessing</h4>
                                        <button type="button" class="btn btn-success selectAll" id="selectAll" value="0" style="margin-right: 30%; width:130px;height:50px;background-color: #313a46;border-color: #313a46;">Select All</button>
                                        <!-- <button type="button" style="margin-right: 30%; width:130px;height:50px;background-color: #99e1dc;" data-dismiss="modal">Close</button> -->
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;margin-bottom: 15px;">
                                            <div class="col-sm-12" id="VBody">
                                                
                                            </div>
                                        </div>
                                        <!-- <div class="modal-footer" style="padding-bottom:2px;">
                                           
                                        </div> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                          <!---vikas code end--->
						  
                        <div class="modal fade" id="AmountModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Edit Amount</h4>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;">
                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label><?php echo $language['amount']; ?></label>
                                                    <input type="text" name="amount" id = "amount" class="form-control" value="" required>
                                                    <input type="hidden" id="id" name="id" value="">
                                                    <input type="hidden" id="p_id" name="p_id" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="padding-bottom:2px;">
                                            <button><?php echo $language['submit']; ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="myModal" role="dialog" >
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="modalcontent">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $language['close']; ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                    </div>
				     <div class="fixside" id="fixside" style="display:none;">
					  
					    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Status Processing</h5>
                <button type="button" class="close" id="pending_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="t" rue="">×</span></button>
            </div>
            <div class="modal-body text-center" style="padding:1%;">
			 
     
										 <table class="table">
    <thead>
      <tr>
        <th><?php echo $language['table_number']; ?></th>
        <th><?php echo $language['invoice_number']; ?></th>
        <th><?php echo $language['qty']; ?></th>
        <th><?php echo $language['amount']; ?></th>
      </tr>
    </thead>
    <tbody id="pending_data">
      
     
    </tbody>
  </table>
            </div>
           
			<div class="row">
			  <div class="col-md-6 input-has-value">
			  
							<div style="padding-top: 5px;display: flex;font-weight:bold;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty3" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty3" id="tol_qty3" value="">
                                                <span id="total_amount3" style="font-size:20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt3" id="tol_mnt1" value="">
												 <input type="hidden" name="discount_amount" id="discount_amount_2" value="0"/>
											<input type="hidden" name="paid_amount_pos" id="paid_amount_pos_2" value="0"/>
											<input type="hidden" name="change_pos" id="change_pos_2" value="0"/>
                                            </div>
											<div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Discount</span>
                                                <input type="text" id="discount_2" value="0" class="amount changenumber2" name="paid" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											<div style="display: flex;font-weight:bold;">
                                                <span style="font-size:14px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Discount in %</span>
                                                <input type="text" id="discountper_2" value='0' placeholder="Enter % Discount" class="amount changenumber2" name="discountper" style=" font-size: 20px; width:17%;border: 1px solid #555555 ;padding-left: 4px;">%
												<input type="hidden" id="discount_per_input_2" name="discount_per_input"/>
												
												<span id="discount_per_2" style="font-size: 20px; width: 30%;border: 1px solid;padding-left: 4px;margin-left:14%;"></span>
                                               
                                               
												
                                            </div>
											<div style="padding-top: 5px;font-weight:bold;display: flex;" class="input-has-value">
											    <select id="select_wallet_2" class="form-control" name="select_wallet_2" style="font-size:18px;">
												<option value='-1'> Select Wallet</option>
												<option  value='wechat'>Wechat</option>
												<option  value='boostpay'>Boost Pay</option>
												<option  value='grabpay'>Grab Pay</option>
												<option  value='touch'>Touch & Go</option>
												<option  value='fpx'>FPX</option>
												</select>
                                               
												
                                                <input type="text" id="wallet_paid_2" value="0" class="amount changenumber2" name="wallet_paid_amount" style=" font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>

                                            <div style="padding-top: 5px;font-weight:bold;display: flex;" class="input-has-value">
                                                <span style="font-size:20px; width: 40%;    border: 1px solid; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid</span>
                                                <input type="text" id="paid_2" value="0" class="amount changenumber2" name="paid" style=" font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
											 <!--div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid By Koocoin</span>
                                                <input type="text" id="paidkoocoin3" value="0" class="amount" name="paidkoocoin3" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div!-->
											
                                            <div style="padding-top: 5px;font-weight:bold;display: flex;">
                                                <span style="font-size:20px; width: 40%;    border: 1px solid;border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Change</span>
                                                <input type="text" readonly id="change3" name="change" class="amount changenumber2" style=" font-size: 20px; width: 37%;margin-left: 23%; border: 1px solid #555555;padding-left: 4px;">
                                            </div>
											<br>
											<input type="submit" class="status_pay_submit btn btn-primary btn-md waves-effect waves-light <?php if($process_password_need){ echo "process_password_need";}?>" value="<?php echo $language['submit']; ?>">
                                            <!--a  style="color:white;" class="btn btn-primary status_ewallet">E-wallet</a!-->
											 
			  </div>
			  <div class="col-md-6">
			    <div class="" style="float: left;">

                                                <form id="cal1" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <tr><th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;"><?php echo $language['calculator']; ?></th>
                                               
                                                </tr></thead>
                                               
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                                <div id="calculator3" class="calculator input-has-value">

                                              <input type="button" id="clear3" class="clear button" value="C">

                                              <div id="viewer3" class="viewer">0</div>

                                              <input type="button" class=" num3 button" data-num="7" value="7" id="7">
                                              <input type="button" class="num3 button" data-num="8" value="8" id="8">
                                              <input type="button" class="num3 button" data-num="9" value="9" id="9">
                                              <input type="button" data-ops="plus" class="ops3 button" value="+">

                                              <input type="button" class=" num3 button" data-num="4" value="4" id="4">
                                              <input type="button" class="num3 button" data-num="5" value="5" id="5">
                                              <input type="button" class="num3 button" data-num="6" value="6" id="6">
                                              <input type="button" data-ops="minus" class="ops3 button" value="-">

                                              <input type="button" class="num3 button" data-num="1" value="1" id="1">
                                              <input type="button" class="num3 button" data-num="2" value="2" id="2">
                                              <input type="button" class="num3 button" data-num="3" value="3" id="3">
                                              <input type="button" data-ops="times" class="ops3 button" value="*">

                                              <input type="button" class="num3 button" data-num="0" value="0" id="0">
                                              <input type="button" class="num3 button" data-num="." value="." id=".">
                                              <input type="button" id="equals3" class="equals3 button" data-result="" value="=">
                                              <input type="button" data-ops="divided by" class="ops3 button" value="/">
                                            </div><table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            
                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    
                                        </form>
                            
                                        </div>
			  </div>
			</div>
			 
              
		
    </div>

					 </div>
								<audio id="my_audio" src="<?php echo $site_url;?>/sound.mp3" autostart="0"></audio>  
                <?php if($alaram_required=="y"){ ?>								
				<audio id="alarm_beeps" src="<?php echo $site_url;?>/alarm_beeps.mp3" autostart="0"></audio> <?php } ?>    
					
 
					      
        </main>
		
    </div>
    <!-- /.widget-body badge -->
    <style type="text/css">
	   .fixside
	   {
		 position: fixed;
		bottom: 0;
		right: 1%;
		top: 15%;
		width: auto;
		max-width:600px;
		height: auto;
		border: 5px solid #73AD21;
		z-index: 999;
		opacity: 1;
		background: white;
	
	   }
	</style>
	<div id="ShiftModel" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
									   
											<div class="modal-header">
												<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
												   Set Opening Balance to Start
											</div>
											 <div class="modal-body" style="padding-bottom:0px;">
											   <div class=" col-md-6 form-group">
													<label>Opening Balance :</label>
													<input type="Number" class="form-control" id='opening_balance' name="opening_balance" value="" placeholder="Opening Balance" required>  
												 <input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $loginidset;?>">
												 </br>
												 <button type="submit" class="btn btn-primary start_show">Start</button>
												
												</div>
												
											 </div>
										
										
									
					    		</div>

				  			</div>
	</div>
</div>
<!-- /.widget-bg -->
<!-- /.content-wrapper -->
<?php include("includes1/footer.php"); ?>
	<div class="modal fade" id="LesaaAmountModel" role="dialog" style="z-index:999999;margin-top:3%;">
                    <div class="modal-dialog">
                      <div class="modal-content" id="modalcontent">
					   <div class="modal-header">
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
     
                          <div class="modal-body">
                              <h2 style="font-size:24px;">Your current balance is <span id="pending_label"></span> </h2>
							   <button class="btn btn-large btn-primary insufficient_close">ok</button>
                          </div>
                      
                      </div>
                    </div>
    </div>
<div id="WalletModel" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
								
					      			<div class="modal-header">
					        			<h2>Total Amount has to pay RM<span id="total_wallet_amount"></span></h2>
										  <button type="button" class="close"  data-dismiss="modal">&times;</button>
					      				
					      			</div>
				      				<div class="modal-body" style="text-align: left;">
				      					<div class="credentials-container">
    
											<h5>Enter your phone number</h5>
											<div>
											  <div class="input-group mb-2" style="margin-bottom:0px !important;">
												<div class="input-group-prepend">
												  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
												</div>
												<input type="hidden" id="paid_wallet_amount"/>
												<input type="hidden" id="selected_invoice_id"/>
											
												<input type="hidden"  value="<?php echo $loginidset; ?>" id="wallet_merchant_id"/>
											
												<input type="number" autocomplete="tel" maxlength='10' id="mobile_number"   class="mobile_number form-control" style="min-width:250px;"  placeholder="123115670" name="mobile_number" required="" />
												 <input type="submit" mode="confirm" id="confirm_payment" class="btn btn-primary" value="Confirm"/>
											</div>
										
											  
										   </div>
										  
										</div>
						
					      			</div>
					      			
					      
					    		</div>

				  			</div>
						</div>
						
</body>
</html>
<script type="text/javascript">
     var merchant_id = '<?php echo $loginidset;?>';
    var site_url = '<?php echo $site_url;?>';
	//mycode

  function printer() {
 
  var statementid=document.getElementById("statementid").value;  
  var merchant_ses=document.getElementById("merchant_ses").value;  
  var paid=document.getElementById("paid").value;  
  var change=document.getElementById("change").value;  
  var tol_qty=document.getElementById("tol_qty1").value;  
  var tol_mnt=document.getElementById("tol_mnt1").value;  
  var statementid=document.getElementById("statementid").value;  
  var product_name = $("input[name='product_name[]']")
              .map(function(){return $(this).val();}).get();
//alert(product_name);


 var product_code = $("input[name='product_code[]']")
              .map(function(){return $(this).val();}).get();  

 var user = $("input[name='user[]']")
              .map(function(){return $(this).val();}).get();  

 var remark = $("input[name='remark[]']")
              .map(function(){return $(this).val();}).get();  

 var tablety = $("input[name='tablety[]']")
              .map(function(){return $(this).val();}).get(); 
 var invo = $("input[name='invo[]']")
              .map(function(){return $(this).val();}).get(); 

 var orderid = $("input[name='orderid[]']")
              .map(function(){return $(this).val();}).get(); 

 var section = $("input[name='section[]']")
              .map(function(){return $(this).val();}).get(); 
 var total = $("input[name='total[]']")
              .map(function(){return $(this).val();}).get();              

var qtyno = $("input[name='qtyno[]']")
              .map(function(){return $(this).val();}).get(); 
   //if (paid == '' || orderid == '' ) {
//alert("Something went wrong");
//}else{        

          $.ajax({
          type: "POST",
          url: "print_in.php",
          data: {statementid:statementid, merchant_ses:merchant_ses, paid:paid, change:change, tol_qty:tol_qty, tol_mnt: tol_mnt ,statementid:statementid, product_name:product_name, product_code:product_code, user:user, remark:remark, tablety:tablety, invo:invo, orderid:orderid, section:section, qtyno:qtyno,total:total },
          cache: false,
          success: function(data) {
          alert(data);
          }
          });

         // return false;
         // }
}



function myFunction() {
 
  var statementid=document.getElementById("statementid").value;  
  var merchant_ses=document.getElementById("merchant_ses").value;  
  var paid=document.getElementById("paid").value;  
  var change=document.getElementById("change").value;  
  var tol_qty=document.getElementById("tol_qty1").value;  
  var tol_mnt=document.getElementById("tol_mnt1").value;  
  var statementid=document.getElementById("statementid").value;  



var product_name = $("input[name='product_name[]']")
              .map(function(){return $(this).val();}).get();
//alert(product_name);


 var product_code = $("input[name='product_code[]']")
              .map(function(){return $(this).val();}).get();  

 var user = $("input[name='user[]']")
              .map(function(){return $(this).val();}).get();  

 var remark = $("input[name='remark[]']")
              .map(function(){return $(this).val();}).get();  

 var tablety = $("input[name='tablety[]']")
              .map(function(){return $(this).val();}).get(); 
 var invo = $("input[name='invo[]']")
              .map(function(){return $(this).val();}).get(); 

 var orderid = $("input[name='orderid[]']")
              .map(function(){return $(this).val();}).get(); 

 var section = $("input[name='section[]']")
              .map(function(){return $(this).val();}).get(); 
 var total = $("input[name='total[]']")
              .map(function(){return $(this).val();}).get();              

var qtyno = $("input[name='qtyno[]']")
              .map(function(){return $(this).val();}).get(); 
    var select_wallet=$('#select_wallet').val();
    var discount_amount=$('#discount_amount').val();
    var wallet_paid_amount=$('#wallet_paid_amount').val();
    var total_amount=$('#total_amount1').val();
    var paid_amount_pos=$('#paid_amount_pos').val();
    var change_pos=$('#change_pos').val();
 //  if (paid == '' || orderid == '' ) {
//alert("Something went wrong");
//}else{        

          $.ajax({
          type: "POST",
          url: "statement_data.php",
          data: {total_amount:total_amount,select_wallet:select_wallet,discount_amount:discount_amount,wallet_paid_amount:wallet_paid_amount,paid_amount_pos:paid_amount_pos,change_pos:change_pos,statementid:statementid, merchant_ses:merchant_ses, paid:paid, change:change, tol_qty:tol_qty, tol_mnt: tol_mnt ,statementid:statementid, product_name:product_name, product_code:product_code, user:user, remark:remark, tablety:tablety, invo:invo, orderid:orderid, section:section, qtyno:qtyno,total:total },
          cache: false,
          success: function(data) {
          // alert(data);
         location.reload();
          }
          });

          //return false;
         // }
}

  $('#myScanModal1').on('hidden.bs.modal', function (e) {
  $(this)
    .find("#paid")
       .val('')
       .end()
       .find("#change")
       .val('')
       .end();
    
})


//end code
    function reloadData(merchant_id,site_url){
        $.ajax({
                url : site_url + 'get_order_list_2019_merchant.php',
                type : 'post',
                dataType: 'json',
                data: {merchant: merchant_id},
                success: function(data){
                    //console.log(data);
                    console.log("Updating order list...");
					 // document.getElementById("my_audio").play();
                    // console.log("Merchant ID: " + merchant_id);
					var last_id = $('#last_id').val();
                    var content = "";
					content +="<input type='hidden' id='last_id' value='"+last_id+"'/>";
                    for(var i = 0; i < data.length; i++){
                        content +=  "<tr id='"+data[i]['invoice_no']+"' class='"+data[i]['todayorder']+" fdfd "+data[i]['callss']+"' data-id='"+data[i]['id']+"'>";
                        
                        content +=      "<input type='hidden' class='merchant_"+data[i]['id']+"' value='"+data[i]['merchant_name']+"'>";
                        
                        content +=      "<input type='hidden' class='userphone_"+data[i]['id']+"' value='"+data[i]['user_mobile_number']+"' >";
                        content +=      "<input type='hidden' class='merchantphone_"+data[i]['id']+"' value='"+data[i]['merchant_mobile_number']+"' >";
                        content +=      "<input type='hidden' class='merchantaddress_"+data[i]['id']+"' value='"+data[i]['merchant_google_map']+"' >";
                        content +=      "<td>"+(i+1)+"</td>";
                        content +=      "<td>"+data[i]['date']+"<br>"+data[i]['new_time']+"<p style='color: red;'>"+data[i]['diff_time']+"</p></td>";
                       content += "<td><input type='button' style='background-color:"+data[i]['s_color']+"' class='status btn btn-primary "+data[i]['callss']+"' value='"+data[i]['sta']+"' status='"+data[i]['status']+"' data-id='"+data[i]['id']+"' data-invoce='"+data[i]['invoice_no']+"' /></td>";
                       
					   // content += "<td><label class='status' status='"+data[i]['status']+"' data-id='"+data[i]['id']+"'>"+data[i]['sta']+"</label></td>";
                       content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['section_type']+"</td>";
                        content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['table_type']+"</td>";
						content += "<td>"+data[i]['invoice_no']+"</td>";
                        content += "<td><a class='print-order' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                        content += "<td><a class='normal_print' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                       
					   content += "<td><a target='_blank' href='"+site_url+"/chat/chat.php?sender="+merchant_id+"&receiver="+data[i]['user_id']+"'><i class='fa fa-comments-o' style='font-size:25px;'></i></a></td>";
                       
					
                        content += "<td>"+data[i]['quantity_val']+"</td>";
						// alert(data[i]['sstper']);
						
                        content += "<td>"+data[i]['total_val']+"</td>";
                        
                        if(data[i]['sstper']>0)
						{
						   content += "<td>"+data[i]['incsst']+"</td>";
                           content += "<td>"+data[i]['g_total']+"</td>";
                        	
						}
                        content += "<td class='quantity_"+data[i]['id']+"'>"+data[i]['quantities']+"</td>";
                        content += "<td class='products_namess product_name_"+data[i]['id']+" test_productss'>"+data[i]['product_name']+"</td>";
                         content += "<td>"+data[i]['varient_type']+"</td>";
                        content += "<td>"+data[i]['remark']+"</td>";
                        content += "<td>"+data[i]['product_code']+"</td>";
                        	content += "<td>"+data[i]['amount_val']+"</td>";
                        content += "<td>"+data[i]['wallet']+"</td>";
                        content += "<td class='location_"+data[i]['id']+" new_tablee'>"+data[i]['location']+"</td>";
                        content += "<td>"+data[i]['lock_mobile']+"</td>";
						 content +=      "<td class='username_"+data[i]['id']+"'>"+data[i]['user_name']+"</td>";
                        content += "<td><a onclick='copy_orderDetail("+data[i]['id']+")'' href='#' class='delivery' id='"+data[i]['id']+"'><i class='fa fa-truck' style='font-size:25px;'></i></a></td>";
                        content += "<td>"+data[i]['account_type']+"</td>";
						 content += "<td><a target='_blank' href='print.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</a></td>";
						 if(data[i]['status'] == '2'){
                            content += "<td target='_blank' href='print_kitchen.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</td>";
                        } else {
                           content += "<td></td>"; 
                        }
                        content += "</tr>";
                    }
                    // console.log(content);
					// alert('Order refresh');
                    $("#orderview-body").html(content);
                },
                error: function(data){
                    console.log("Error:");
                    console.log(data);
                }
            }); 
    }

    function copy_orderDetail(id){
        var detailContent = "";
        var username = $("username");
        var dummy = document.createElement("input");
        document.body.appendChild(dummy);
        var product_name = $(".product_name_" + id).html().split("<br>");
        var product_qty = $(".quantity_"+id).html().split("<br>");
        var product_amount = $(".amount_"+id).html().split("<br>");
        dummy.setAttribute("id", "dummy_id");
        var detail = "User Name: " + $(".username_" + id).html() +" ";
        detail += "User Phone: " + $(".userphone_" + id).val() + " ";
        detail += "Merchant Name: " + $(".merchant_" + id).val() + " ";
        detail += "Merchant Phone: " + $(".merchantphone_" + id).val() + " ";
        detail += "Merchant Address: " + $(".merchantaddress_" + id).val() + " ";
        for(var i = 0; i < product_name.length - 1; i++){
            detail += "Product Name: " + product_name[i] + " ";
            detail += "Quantity: " + product_qty[i] + " ";
            var amount = product_amount[i].trim();
            if(product_amount[i].indexOf("class") > -1){
                amount = 0;
            }
            detail += "Amount: " + amount + " ";
        }
        detail += "Total: " + $(".total_" + id).html().trim() + "   ";
        detail += "Table Number: " + $(".table_number_" + id).html().trim() + "   ";
        detail += "Location: " + $(".location_" + id).html().trim() + "   ";
        document.getElementById("dummy_id").value= detail;
        dummy.select();
        document.execCommand("copy");
        alert("Send Delivery Service to Admin!");
    }
    function hasClass(element, className) {
        return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
    }
	function percentage(num, per) {
	return (num/100)*per;
	} 
    $(document).ready(function(){
        var local_id=localStorage.getItem("login_cache_id");
	    // alert(local_id);
		if(local_id=='' || local_id==null)
		{
			localStorage.setItem('login_cache_id','<?php echo $_SESSION['login'];?>');
			localStorage.setItem('login_role_id','<?php echo "2";?>');
		}
        // localStorage.setItem('login_user_role','<?php echo $_SESSION['login'];?>'); 
        
		 var cash_match='<?php echo $cash_match;?>';
		var cash_id=$('#cash_id').val();
		// alert(cash_id);
		if(cash_match=="y")
		$('#ShiftModel').modal('show');
		$('.start_show').click(function() {
		var opening_balance=$('#opening_balance').val();
		
		// var user_id=$('#merchant_id').val();
		var user_id="<?php echo $loginidset; ?>";
		
		if(opening_balance)
		{
			var data = {user_id:user_id,opening_balance:opening_balance,method:"startcash",cash_id:cash_id};
			$.ajax({
				  
				  url :'functions.php',
				  type:'POST',
				  dataType : 'json',
				  data:data,
				  success:function(response){
					  var data = JSON.parse(JSON.stringify(response));
					  if(data.status==true)
					  {
						location.reload();  
					  }
					  else
					  {
						  alert('Failed to start cash system');
					  }
					 
					}		  
			  });
		}
		else
		{
			alert('Opening Balance is Required To start');
		}
	});
	$(".changenumber").on("keyup", function(){
		// alert(3);
		   var dis_per =$("#discountper").val();
		    var dis_num =$("#discount").val();
		    var tol = document.getElementById("total_amount1").innerText;
			var perc = percentage(tol,dis_per).toFixed(2);
			// alert(perc);  
			$('#discount_per_input').val(perc);
			$('#discount_per').html(perc);
			var paid3=$('#paid').val();   
			var paidkoocoin=$("#wallet_paid").val();
			 var total_paid = parseFloat(dis_num)+parseFloat(paid3);
			  var mb = $('#viewer').text();
		// alert(mb);
		    		  // var final = parseFloat(tol)-parseFloat(total_paid);
		    		  // var final =(parseFloat(tol))-(parseFloat(total_paid)+parseFloat(paidkoocoin));
		    		  // var final =(parseFloat(paid3)+parseFloat(paidkoocoin))-(parseFloat(tol)+parseFloat(dis_num));
					  //  Total - discount - discount by percentage( to be created by you)-Boost pay-Cash paid
					 
		    		  var final =parseFloat(tol)-parseFloat(dis_num)-parseFloat(perc)-parseFloat(paidkoocoin)-parseFloat(paid3);
                var value = Math.abs(final);
                      var v = value.toFixed(2);
					  // alert(v);
                      $('#change').val(v);
		$('#paid_amount_pos').val(paid3);
		$('#change_pos').val(v);
		$('#discount_amount').val(dis_num);
	 });
	$(".changenumber2").on("keyup", function(){
		// alert(3);
		   var dis_per =$("#discountper_2").val();
		    var dis_num =$("#discount_2").val();
		    var tol = document.getElementById("total_amount3").innerText;
			var perc = percentage(tol,dis_per).toFixed(2);
			// alert(perc);  
			$('#discount_per_input').val(perc);
			$('#discount_per_2').html(perc);
			var paid3=$('#paid_2').val();   
			var paidkoocoin=$("#wallet_paid_2").val();  
			 var total_paid = parseFloat(dis_num)+parseFloat(paid3);
			  var mb = $('#viewer3').text();
		// alert(total_paid);
		    		  // var final = parseFloat(tol)-parseFloat(total_paid);
		    		  // var final =(parseFloat(tol))-(parseFloat(total_paid)+parseFloat(paidkoocoin));
		    		  // var final =(parseFloat(paid3)+parseFloat(paidkoocoin))-(parseFloat(tol)+parseFloat(dis_num));
					  //  Total - discount - discount by percentage( to be created by you)-Boost pay-Cash paid
					 
		    		  var final =parseFloat(tol)-parseFloat(dis_num)-parseFloat(perc)-parseFloat(paidkoocoin)-parseFloat(paid3);
                var value = Math.abs(final);
                      var v = value.toFixed(2);
					  // alert(v);
                      $('#change3').val(v);  
		$('#paid_amount_pos_2').val(paid3);
		$('#change_pos_2').val(v);
		$('#discount_amount_2').val(dis_num);
	 });
	  $(".s_order_detail").click(function(e){
		  var s_id = $(this).attr('order_id');
		  // alert(s_id);
		  $.ajax({
                        type: "POST",
                        url: "singleorder.php",
                        data: {s_id:s_id},
                        success: function(data) {
							$('#orderdata').html(data);
                        },
                        error: function(result) {
                            alert('error');
                        }
                });
		  $("#orderdetailmodel").modal("show"); 
	  });
		$("#apply_criteria").on("click", function(e){
			e.preventDefault();
			var criteria = $("#criteria_field").find("option:selected").attr("val");
			var query = $("#criteria_query").val();
			window.location.href = "./orderview.php?cr=" + criteria + "&q=" + query;
		  });
		  $("#criteria_form").on("submit", function(e){
			e.preventDefault();
			$(this).find("button#apply_criteria").trigger("click");
		  });
		var alaram_required='<?php echo $alaram_required; ?>';
		if(alaram_required=="y")
		{
			 setInterval(function() {
					var data = {id: $(".user_id").val(), method: "ringalaram"};
					$.ajax( {
						url : "functions.php",
						type:"post",
						data : data,
						dataType : 'json',
						success : function(data) {
							if(data==1)
							{
								document.getElementById("alarm_beeps").play();   
								$('.alaram').show();
							}
							else
							{
								$('.alaram').hide();
							}
						}
					} );
				}, 5000);
		}  
		      
		     setInterval(function() {
				 // alert('new');
        //unPrintedOrders = [];
		var last_id = $('#last_id').val();
		var site_url = '<?php echo $site_url;?>';
		// alert(last_id);
		var merchant_id="<?php echo $loginidset; ?>";
        var data = {last_id:last_id, method: "neworder",merchant_id:merchant_id};
        $.ajax( {
            url : "functions.php",
            type:"post",
            data : data,
            dataType : 'json',
            success : function(data) {
				var data = JSON.parse(JSON.stringify(data));
				 // var data = JSON.parse(data);
				 // alert(data);      
					 var order_id=data.id;
					 // alert(order_id);
				  if(order_id){
					  // alert('New order');
					  var order_place=data.order_place;
					 
					  if(order_place=="live")
					  {
						  // alert('Make Audio');
					      document.getElementById("my_audio").play();   		
					  }
					 // alert(order_id);
					$('#last_id').val(order_id);
					 reloadData(merchant_id,site_url);
					
					
				  }
				  else
				  {
					  alert('No new');
				  }

            }
        } );
    }, 15000);
        function handleKeyPress (e) {
            if( hasClass( document.getElementById("myScanModal"), "show" ) ) {
                if (e.keyCode === 13) {
                    var barcodeRead = $("#barcode").val();
                    setTimeout(function(){
                        addOrderToDialog( barcodeRead );
                        $("#barcode").val('');
                        $("#barcode").focus();
                    }, 200);
                }
            }
        }
		$("#pending_close").click(function() {
			$('.fixside').hide();
		});
		$("form#data_processpassword").submit(function(e) {
			 e.preventDefault();
			 var formData = new FormData(this);
			var process_password=$('#process_password').val();
			var scan_type=$('#scan_type').val();
			var dbpass="<?php echo $merchant_name['fund_password'];?>";
			if(dbpass==process_password)
			{
				var data = {process_password: $("#process_password").val(),idprocesspass: $("#idprocesspass").val()};
				if(scan_type=="mass")
					 $( "#scan").submit();
				
				else if(scan_type=="invo")
				myFunction();
			  else if(scan_type=="normal")
			  {
				  var yourArray = [];
				  $('.past_invoice').each(function() {
					   e.preventDefault();   
					   var data_id = $(this).attr("data_id");
					    yourArray.push(data_id);
					   // alert(data_id);
				  });
				   $.ajax({
								url : 'update_status.php',    
								type: 'POST',
								data: {bulk_invoice_id:yourArray, status: 1},
								success:function(data){
									//~ alert(1);
									location.reload();
								}
							});
				  
			  }
				 // alert('2'); 
			}
			else
			{
				alert('Wrong Process Password,Try again');
			}
		});	
		$("form#data_spcialpassword").submit(function(e) {
			 e.preventDefault();
			 var formData = new FormData(this);
			var spassword=$('#spassword').val();
			var dbpass="<?php echo $merchant_name['spassword'];?>";
			if(dbpass==spassword)
			{
			 var data = {spassword: $("#spassword").val(),idspecialpass: $("#idspecialpass").val()};
			 //alert('asdas') ;
			 $.ajax({
                url: 'delete_record_special.php',
                type: 'post',
                data: $(this).serialize(),
                success: function (data) {
                    // console.log(data ? "true" : "false");
					   if(data == 1){
                        $("#deletemodel").modal("hide");
                        // reloadData(merchant_id,site_url);
						location.reload();
                    }else{
                        alert("An error occured, try again later");
                        console.log(data);
                    }
                    
                }
            });
			}
			else
			{
				alert('Wrong Special Password,Try again');
			}
		});	
        $("#add_invoice").click(function() {
            if( hasClass( document.getElementById("myScanModal"), "show" ) ) {
                var barcodeRead = $("#barcode").val();
                setTimeout(function(){
                    addOrderToDialog( barcodeRead );
                    $("#barcode").val('');
                    $("#barcode").focus();
                }, 200);
            }
        });
		// stop alarma
		 $("#stop_alaram").click(function() {
			 
			 var data = {id: $(".user_id").val(), method: "stopalaram"};
			$.ajax( {
				url : "functions.php",
				type:"post",
				data : data,
				dataType : 'json',
				success : function(data) {
					if(data==1)
					{
						  document.getElementById("my_audio").stop(); 
					   $('.alaram').hide();
					}
					else
					{
						//$('.alaram').hide();
					}
				}
			} );
		 });
			<!--  start of amit code !-->
		
		 $("#scan_order1").click(function() {
			 $('#selected_invoice_id').val('');
			  $('#paid').val(0);        
			  $('#scan_type').val('invo');        
             // $("#selected_invoice_id").val();
            $("#myScanModal1").modal("show");
            $("#total_qty1").text('');
            $("#viewer").text('');
			
            $("#scanned_data1").html('');
            $("#total_amount1").text('');
		
        });
		$(".status_ewallet").click(function() {
			// alert(3);
			 // $("#WalletModel").show();
				
			
			var paid_wallet_amount=$("#paid_wallet_amount").val();
			// alert(paid_wallet_amount);
			// show_total = paid_wallet_amount.toFixed(2);
			 $('#total_wallet_amount').html(paid_wallet_amount);
			
			 $('#WalletModel').modal('show');
		}); 
			$("#confirm_payment").click(function() {
			var mobile_num=$('#mobile_number').val();
			var wallet_merchant_id=$('#wallet_merchant_id').val();
			var paid_wallet_amount=$("#paid_wallet_amount").val();
			// alert(paid_wallet_amount);
			var selected_invoice_id=$("#selected_invoice_id").val();
			
			if(mobile_num)
			{
				  $(this).attr("disabled", "disabled");
				$.ajax({
					  type: "POST",    
					  url: "functions.php",
					  data: {mobile_num:mobile_num,method:"deductfund",merchant_id:wallet_merchant_id,paid_wallet_amount:paid_wallet_amount,selected_invoice_id:selected_invoice_id},
					  cache: false,
					  success: function(data) {
						   var obj = JSON.parse( data );
						   if(obj.status==true)
						   {
							   var pending_bal=obj.pending_bal;
							   // alert(pending_bal);
							   $('#pending_label').html(pending_bal);
							   $('#LesaaAmountModel').modal('show');
							   $("#selected_invoice_id").val();
							    
							   setTimeout(function() { 
								  location.reload();
							   }, 5000);
							     
							    
								
						   }
						   else
						   {
							   var type=obj.type;
							   if(type=="lessbal")
							   {
								alert(obj.msg);
							   }
						   }
					  // alert(data);
					  }
				});
						}
		});  
		 $("#invoice").click(function(){

                 $("#InvoiceModel").show();

                 // $.ajax({
                        // type: "POST",
                        // url: "fetch_invoice.php",
                        
                        // success: function(data) {
                          
                          // $("#invoice_list").html(data);
                     // },
                        // error: function(result) {
                            // alert('error');
                        // }
                    // });


            });
			 $("#table").click(function(){

                 $("#myModalt").show();
                 // $.ajax({
                        // type: "POST",
                        // url: "fetch_table.php",
                        
                        // success: function(data) {
                          
                          // $("#table_list").html(data);
                     // },
                        // error: function(result) {
                            // alert('error');
                        // }
                    // });

            });
			 $("#add_invoicemy").click(function() {  

                    var invoice_num = $(".invoice_num").val();
                    var table_num = $(".table_num").val();
                    var q = table_num.match(/[a-z]+|\d+/ig);

                 $.ajax({
                        type: "POST",
                        url: "fetch.php",
                        data: {invoice_num:invoice_num,table_num:q},
                        success: function(data) {
                           // alert(data);
                           if( data != null ) {
                                var obj = JSON.parse( data );
                                //alert(obj);
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    
                                    var flag = 0;

                                $("#scanned_data1").find("tr").each(function () {
                                    var td1 = $(this).find("td:eq(0)").text();
                                    var td2 = $(this).find("td:eq(1)").text();
                                    var td3 = $(this).find("td:eq(2)").text();
                                    var td4 = $(this).find("td:eq(3)").text();
                                    var td5 = $(this).find("td:eq(4)").text();
                                    var td6 = $(this).find("td:eq(5)").text();
                                    var td7 = $(this).find("td:eq(6)").text();
                                    var td8 = $(this).find("td:eq(7)").text();
                                    var td9 = $(this).find("td:eq(8)").text();
                                    var td10 = $(this).find("td:eq(9)").text();
                                    var td11 = $(this).find("td:eq(10)").text();
                                      
                                       // var tb3 = td3.length;
                                      var tabl_num = table_num.length;
                                      //var t_num = td3.slice(-tabl_num);
                                     // alert(invoice_num);

                                 if ((invoice_num == td4) || (table_num == td3)) {
                                        flag = 1;
                                    }
                                });
                                if (flag == 1) {
                                    alert('Already Exists');
                                     $("#invoice_num").val('');
                                     $("#table_num").val('');

                                } else {

                                  for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
                                        var amount = 0;
                                        if( order['product_qty'][i] && order['product_amt'][i] ) {
                                            amount = order['product_qty'][i] * order['product_amt'][i];
                                        } else {
                                            amount = 0;
                                        }
                                        total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
										
                                    }
                                    var total_amount =  empty($("#total_amount1").text()) ? 0 : parseFloat($("#total_amount1").text());
                                    // total_amount += total;
									// alert(total_amount);
                                    total = total.toFixed(2);
									 $("#paid_wallet_amount").val(total_amount.toFixed(2));
                                    $("#total_amount1").text(total_amount.toFixed(2));
                                    var total_qty =  empty($("#total_qty1").text()) ? 0 : parseInt($("#total_qty1").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty1").text(parseInt(total_qty));


                                   var i=1;
                                    var list =
                                        '<tr><td style="text-align:center; padding-left: 5px;width: 8%;" >' + parseInt(document.getElementById("scanned_data1").childElementCount + 1) + '</td>' +
                                        '<td style="padding-left: 5px;width: 42%;" id="test" class="btl"><input type="hidden" name="user[]" value="'+ order['username'] +'">' + order['username'] + '</td>' +
                                         '<td style=" text-align:center;padding-left: 5px;width: 22%;"><input type="hidden" name="tablety[]" value="' + order['table_type'] + '">' + order['section_type'] + ''+order['table_type']+'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 21%;"><input type="hidden" name="invo[]" value="' + order['invoice_no'] + '">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none"><input type="hidden" name="orderid[]" value="' + order['id'] +'">' + order['id'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="section[]" value="' + order['section_type'] +'">' + order['section_type'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_code[]" value="' + order['product_code'] +'">' + order['product_code'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="remark[]" value="' + order['remark'] +'">' + order['remark'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_name[]" value="' + order['product_name'] +'">' + order['product_name'] +'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 7%;"><input type="hidden" name="qtyno[]" value="' + parseInt(qtyOfInvoice) + '">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td style="text-align:center; padding-left: 42px;"><input type="hidden" name="total[]" value="' + total +  '">' + total +  '</td></tr>';

                                    $("#scanned_data1").append(list);
                                    $("#invoice_num").val('');
                                     $("#table_num").val('');

 
                                }
                              }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });
                });
				   $('.num').click(function() { 
                       var mb = $('#viewer').text();
                     $('#paid').val(mb);
                   
                     var tol = document.getElementById("total_amount1").innerText;
                     // alert(tol);
                      var final = parseFloat(tol)-parseFloat(mb);
                      var value = Math.abs(final);
                      var v = value.toFixed(2);
                      $('#change').val(v);
                      
                       var tol_qty = document.getElementById("total_qty1").innerText;
                     //  alert(tol_qty);
                    
                        $('#tol_qty1').val(tol_qty);
                        $('#tol_mnt1').val(tol);



                    });
					$('.num2').click(function() { 
                    // alert(2);
                       var mb = $('#viewer2').text();
					  // alert(mb);
					  
                     $('#paid2').val(mb);
                   
                     var tol = document.getElementById("total_amount").innerText;
					var paidkoocoin=$('#paidkoocoin').val();
                       var final = parseFloat(tol)-(parseFloat(mb)+parseFloat(paidkoocoin));
                      var value = Math.abs(final);
                      var v = value.toFixed(2);
                      $('#change2').val(v);
                      
                       var tol_qty = document.getElementById("total_qty").innerText;
                     //  alert(tol_qty);
                    
                        $('#total_qty').val(tol_qty);
                        $('#total_amount2').val(tol);



                    });
					$('.num3').click(function() { 
                    // alert(2);
                       var mb = $('#viewer3').text();
					  // alert(mb);
                     $('#paid_2').val(mb);
                   
                     var tol = document.getElementById("total_amount3").innerText;
               
                      var final = parseFloat(tol)-parseFloat(mb);
                      var value = Math.abs(final);
                      var v = value.toFixed(2);
                      $('#change3').val(v);
                      
                       var tol_qty = document.getElementById("total_qty_3").innerText;
                     //  alert(tol_qty);
                    
                        $('#total_qty_3').val(tol_qty);
                        $('#total_amount3').val(tol);



                    });
					 	 $('body').on('click',"input[name=tbl]",function(e){
						  // e.preventDefault();
						     e.preventDefault();   
                         var tbl = $(this).attr("data-table");
                         var data_id = $(this).attr("data-id");
						 var selected_invoice_id=$('#selected_invoice_id').val();
							if(selected_invoice_id)
							{
								var new_in_id=selected_invoice_id+","+data_id;
							}
							else
							{
								var new_in_id=data_id;
							}
							
							$('#selected_invoice_id').val(new_in_id);
                       // alert(tbl);
					var invo = $(this).attr("data-invoce");
					// alert(invo);
                   $(this).attr("disabled", "disabled");
                      var tbl_val = $(this).val();
                      var section = $(this).attr("data-section");
                       var sec_name = $(this).attr("data-name");
                      var id = $(this).attr("data-id");
                          $('.table_num').val(tbl);
                          $('.section_num').val(section);
                       $('#invoice_num').val(invo);
                     var invoice_num = $(".invoice_num").val();
                     var table_num = $(".table_num").val();
                     var section_num = $(".section_num").val();
                    // var q = tbl.match(/[a-z]+|\d+/ig);
                  

                 $.ajax({
                        type: "POST",
                        url: "fetch.php",
                        data: {invoice_num:invo,section_num:section},
                        success: function(data) {
                           // alert(data);
                        // print normal order 
						  
                            if( data != null ) {
                                var obj = JSON.parse( data );
                                //alert(obj);
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    
                                    var flag = 0;

                                $("#scanned_data1").find("tr").each(function () {
                                    var td1 = $(this).find("td:eq(0)").text();
                                    var td2 = $(this).find("td:eq(1)").text();
                                    var td3 = $(this).find("td:eq(2)").text();
                                    var td4 = $(this).find("td:eq(3)").text();
                                    var td5 = $(this).find("td:eq(4)").text();
                                    var td6 = $(this).find("td:eq(5)").text();
                                    var td7 = $(this).find("td:eq(6)").text();
                                    var td8 = $(this).find("td:eq(7)").text();
                                    var td9 = $(this).find("td:eq(8)").text();
                                    var td10 = $(this).find("td:eq(9)").text();
                                    var td11 = $(this).find("td:eq(10)").text();
                                      
                                 //   var tb3 = td3.length;
                                   // var tabl_num = table_num.length;
                                      var trid = $(this).attr('id');
									  // alert(td4);
                                 if ((invoice_num == td4)) {
                                        flag = 1;
                                    }
                                });
                                if (flag == 1) {
                                    alert('Already Exists');
                                    $("#invoice_num").val('');

                                    $("#table_num").val('');

                                } else {

                                  for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
                                        var amount = 0;
                                        if( order['product_qty'][i] && order['product_amt'][i] ) {
                                            amount = order['product_qty'][i] * order['product_amt'][i];
                                        } else {
                                            amount = 0;
                                        }
                                        total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
										
                                    }
                                    var total_amount =  empty($("#total_amount1").text()) ? 0 : parseFloat($("#total_amount1").text());
                                    total_amount += total;
                                    total = total.toFixed(2);
									// alert(total_amount);
									 $("#paid_wallet_amount").val(total_amount.toFixed(2));
                                    $("#total_amount1").text(total_amount.toFixed(2));
									var paid=$("#paid").val();
									// alert(paid);
									if(paid >0)
									{
										var pending= paid-(total_amount.toFixed(2));
										$("#change").val(pending);
									} 
                                    var total_qty =  empty($("#total_qty1").text()) ? 0 : parseInt($("#total_qty1").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty1").text(parseInt(total_qty));


                                   var i=1;
                                    var list =
                                        '<tr id="'+tbl_val+'"><td style="text-align:center; padding-left: 5px;width: 8%;" >' + parseInt(document.getElementById("scanned_data1").childElementCount + 1) + '</td>' +
                                        '<td style=" padding-left: 5px;width: 42%;" id="test" class="btl"><input type="hidden" name="user[]" value="'+ order['username'] +'">' + order['username'] + '</td>' +
                                         '<td style=" text-align:center;padding-left: 5px;width: 22%;"><input type="hidden" name="tablety[]" value="' + order['table_type'] + '">'+order['table_type']+'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 21%;"><input type="hidden" name="invo[]" value="' + order['invoice_no'] + '">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none"><input type="hidden" name="orderid[]" value="' + order['id'] +'">' + order['id'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="section[]" value="' + order['section_type'] +'">' + order['section_type'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_code[]" value="' + order['product_code'] +'">' + order['product_code'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="remark[]" value="' + order['remark'] +'">' + order['remark'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_name[]" value="' + order['product_name'] +'">' + order['product_name'] +'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 7%;"><input type="hidden" name="qtyno[]" value="' + parseInt(qtyOfInvoice) + '">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td style="text-align:center; padding-left: 42px;"><input type="hidden" name="total[]" value="' + total +  '">' + total +  '</td></tr>';

                                    $("#scanned_data1").append(list);
                                    $("#invoice_num").val('');
                                    $("#table_num").val('');

                                    
                                     $('#myModalt').modal('hide');
                                     $('#myModalTable').modal('hide');
                                  }


                                }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });



                    });
		// process password 
	    $("body").on('click','.process_password_need',function(){          
		$("#deleteprocessmodel").modal("show");     
			$('#scan_type').val('normal');
		
		return false;		
		});
		$("body").on('click','.process_upss_delete_special',function(){          
		$("#deleteprocessmodel").modal("show");        
		var dataid=$(this).data("id"); 	
		//var prodid=$(this).data("prodid");   
		$("#tbl").val("");          
		$("#idprocesspass").val(dataid); 
		return false;		
		});
		$("body").on('click','.pop_upss_delete_special',function(){          
		$("#deletemodel").modal("show");        
		var dataid=$(this).data("id"); 	
		//var prodid=$(this).data("prodid");   
		$("#tbl").val("");          
		$("#idspecialpass").val(dataid);             
		});

    $("#selectAll").click(function(){
      $(".testClass").each(function(i) {
          var btn = $(this);
          $(this).css({"background-color": "gray"});
          setTimeout(btn.trigger.bind(btn, "click"), i * 100);
      });
      var val = $(this).val();
      if(val == 0){
        $('input[name=chkTest]').attr('checked', true);
        $("#selectAll").val(1);
      }
      else{
        $('input[name=chkTest]').attr('checked', false);
        $("#selectAll").val(0);
      }
    });

    // $("#selectAll").click(function() {
    //   $(".chkTest").prop("checked", $("#selectAll").prop("checked"));
    // });


    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
		$('body').on("click", "input[name=tblbulk]",function(e){  
      
			var x=$(this);
			var tbl = $(this).attr("data-table");
            var data_id = $(this).attr("data-id");
			var invo = $(this).attr("data-invoce");
			var section = $(this).attr("data-section");
			 $('#invoice_num').val(invo);
			 var invoice_num = $(".invoice_num").val();
			 // alert(invoice_num);
			 
			$.ajax({
				     
                        type: "POST",
                        url: "fetch.php",
                        data: {invoice_num:invo,section_num:section},
                        success: function(data) {
                           // alert(data);
                        // print normal order 
						  
                            if( data != null ) {
                                var obj = JSON.parse( data );
                                // alert(obj);
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    var flag = 0;
									                 $("#scanned_data1").find("tr").each(function () {
                                    var td1 = $(this).find("td:eq(0)").text();
                                    var td2 = $(this).find("td:eq(1)").text();
                                    var td3 = $(this).find("td:eq(2)").text();
                                    var td4 = $(this).find("td:eq(3)").text();
                                    var td5 = $(this).find("td:eq(4)").text();
                                    var td6 = $(this).find("td:eq(5)").text();
                                    var td7 = $(this).find("td:eq(6)").text();
                                    var td8 = $(this).find("td:eq(7)").text();
                                    var td9 = $(this).find("td:eq(8)").text();
                                    var td10 = $(this).find("td:eq(9)").text();
                                    var td11 = $(this).find("td:eq(10)").text();
                                      
                                 //   var tb3 = td3.length;
                                   // var tabl_num = table_num.length;
                                      var trid = $(this).attr('id');
									  // alert(invoice_num);   
									  // alert('invoce');
									  // alert(td4);
                                 if ((invoice_num == td4)) {
                                        flag = 1;
                                    }
                                });
                                if (flag == 1) {
									           $(this).css({"background-color": "#296ca0"});    
                                    alert('Already Exists');
                                    $("#invoice_num").val('');

                                    $("#table_num").val('');

                                } else {
									
                                  if($(x).attr("data-status") == 'notset'){
                                    $(x).css({"background-color": "gray"}); 
                                    $(x).attr("data-status","yesset");
                                    var i = $(x).attr("data-i");
                                    $("#chkTest"+i).prop("checked", true);
                                    var selected_invoice_id_=$('#selected_invoice_id').val();
                                    var selected_invoice_id=$('#selected_invoice_id').val().split(",");
                                    var data_id = $(x).attr("data-id");
                                    var arr='';
                                    if(selected_invoice_id_ != '')
                                    {
                                      arr +=","+data_id;
                                      $('#selected_invoice_id').val(selected_invoice_id_+arr);
                                    }
                                    else
                                    {
                                       arr += data_id;
                                       $('#selected_invoice_id').val(arr);
                                    }
                                  }
                                  else{
                                    $(x).css({"background-color": "#296ca0"});
                                    $(x).attr("data-status","notset");
                                    var i = $(x).attr("data-i");
                                    $("#chkTest"+i).prop("checked", false);
                                    var selected_invoice_id=$('#selected_invoice_id').val().split(",");
                                    var data_id = $(x).attr("data-id");
                                    var arr='';
                                    selected_invoice_id.remove(data_id);
                                    arr = selected_invoice_id.join();
                                    $('#selected_invoice_id').val(arr);
                                  }
									// var selected_invoice_id=$('#selected_invoice_id').val();
									// if(selected_invoice_id)
									// {
									// 	var new_in_id=selected_invoice_id+","+data_id;
									// }
									// else
									// {
									// 	var new_in_id=data_id;
									// }
									// $('#selected_invoice_id').val(new_in_id);	
                                }


                                }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });
			
		});
		$('body').on("click", "#submit_bulk",function(e){
			var all_invoice=$('#selected_invoice_id').val();
			if(all_invoice)
			{
				
                 $.ajax({
                        type: "POST",
                        url: "bulkadd.php",
                       data: {all_invoice:all_invoice},
					   dataType : 'json', 
                        success: function(data) {
							var obj = JSON.parse(JSON.stringify(data));
							 var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    
                                    var flag = 0;
							if(obj.status==true)
							{
								
								var total_count=obj.record.length;
								 var forder =obj.record;
								// alert(total_count);
								// alert(order['product_name']);​
								  for (var j = 0, len = total_count; j< total_count; j++) {
									  // alert(j);
									   var order=forder[j];
										for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
											var amount = 0;
											if( order['product_qty'][i] && order['product_amt'][i] ) {
												amount = order['product_qty'][i] * order['product_amt'][i];
											} else {
												amount = 0;
											}
											total += amount;
											total=parseFloat(total.toFixed(2));
											totalQty += parseInt(order['product_qty'][i]);
											qtyOfInvoice += parseInt(order['product_qty'][i]);
										}
										 var total_amount =  empty($("#total_amount1").text()) ? 0 : parseFloat($("#total_amount1").text());
                                    total_amount += total;
                                    // total = total.toFixed(2);
									// alert(total_amount);
									$("#paid_wallet_amount").val(total_amount.toFixed(2));
                                    $("#total_amount1").text(total_amount.toFixed(2));
									var paid=$("#paid").val();
									// alert(paid);
									if(paid >0)
									{
										var pending= paid-(total_amount.toFixed(2));
										$("#change").val(pending);
									}   
                                    var total_qty =  empty($("#total_qty1").text()) ? 0 : parseInt($("#total_qty1").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty1").text(parseInt(total_qty));


                                   var i=1;
                                    var list =
                                        '<tr><td style="text-align:center; padding-left: 5px;width: 8%;" >' + parseInt(document.getElementById("scanned_data1").childElementCount + 1) + '</td>' +
                                        '<td style=" padding-left: 5px;width: 42%;" id="test" class="btl"><input type="hidden" name="user[]" value="'+ order['username'] +'">' + order['username'] + '</td>' +
                                         '<td style=" text-align:center;padding-left: 5px;width: 22%;"><input type="hidden" name="tablety[]" value="' + order['table_type'] + '">' + order['section_type'] + ''+order['table_type']+'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 21%;"><input type="hidden" name="invo[]" value="' + order['invoice_no'] + '">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none"><input type="hidden" name="orderid[]" value="' + order['id'] +'">' + order['id'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="section[]" value="' + order['section_type'] +'">' + order['section_type'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_code[]" value="' + order['product_code'] +'">' + order['product_code'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="remark[]" value="' + order['remark'] +'">' + order['remark'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_name[]" value="' + order['product_name'] +'">' + order['product_name'] +'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 7%;"><input type="hidden" name="qtyno[]" value="' + parseInt(qtyOfInvoice) + '">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td  ref="total" style="text-align:center; padding-left: 42px;"><input type="hidden" name="total[]" value="' + total +  '">' + total +  '</td></tr>';

                                          $("#scanned_data1").append(list);
                                          $("#invoice_num").val('');
                                           $("#table_num").val('');
                                          $('#myModalTable').modal('hide');
										  $('#selected_invoice_id').val('');
										  // alert(j);
									}
							}
							
                    },
                    error: function(result) {
                        alert('error');
                      }
					});
			}
			else
			{
				alert('Add Invoice First');
			}
		});
		$('body').on("click", "input[name=invo]",function(e){
						 e.preventDefault();
							var data_id = $(this).attr("data-id");
							var invo = $(this).attr("value");
							$(this).attr("disabled", "disabled");
							$('#invoice_num').val(invo);
							var invoice_num = $(".invoice_num").val();
							var table_num = $(".table_num").val();
							var selected_invoice_id=$('#selected_invoice_id').val();
							if(selected_invoice_id)
							{
								var new_in_id=selected_invoice_id+","+data_id;
							}
							else
							{
								var new_in_id=data_id;
							}
							
							$('#selected_invoice_id').val(new_in_id);
                 $.ajax({
                        type: "POST",
                        url: "fetch.php",
                        data: {invoice_num:invoice_num,table_num:table_num},
                        success: function(data) {
                           // alert(data);
                            if( data != null ) {
                                var obj = JSON.parse( data );
                                //alert(obj);
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    
                                    var flag = 0;

                                $("#scanned_data1").find("tr").each(function () {
                                    var td1 = $(this).find("td:eq(0)").text();
                                    var td2 = $(this).find("td:eq(1)").text();
                                    var td3 = $(this).find("td:eq(2)").text();
                                    var td4 = $(this).find("td:eq(3)").text();
                                    var td5 = $(this).find("td:eq(4)").text();
                                    var td6 = $(this).find("td:eq(5)").text();
                                    var td7 = $(this).find("td:eq(6)").text();
                                    var td8 = $(this).find("td:eq(7)").text();
                                    var td9 = $(this).find("td:eq(8)").text();
                                    var td10 = $(this).find("td:eq(9)").text();
                                    var td11 = $(this).find("td:eq(10)").text();
                                      
                                       // var tb3 = td3.length;
                                      var tabl_num = table_num.length;
                                      var t_num = td3.slice(-tabl_num);
                                     // alert(t_num);

                                 if ((invoice_num == td4) || (table_num == td3)) {
                                        flag = 1;
                                    }
                                });
                                if (flag == 1) {
                                    alert('Already Existsd');
                                    $("#invoice_num").val('');
                                    $("#table_num").val('');


                                } else {
									// alert('S');

                                  for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
                                        var amount = 0;
                                        if( order['product_qty'][i] && order['product_amt'][i] ) {
                                            amount = order['product_qty'][i] * order['product_amt'][i];
                                        } else {
                                            amount = 0;
                                        }
                                        total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
										
										
										
                                    }
                                    var total_amount =  empty($("#total_amount1").text()) ? 0 : parseFloat($("#total_amount1").text());
                                    total_amount += total;
                                    total = total.toFixed(2);
									// alert(total_amount);
									$("#paid_wallet_amount").val(total_amount.toFixed(2));
                                    $("#total_amount1").text(total_amount.toFixed(2));
									var paid=$("#paid").val();
									// alert(paid);
									if(paid >0)
									{
										var pending= paid-(total_amount.toFixed(2));
										$("#change").val(pending);
									}   
                                    var total_qty =  empty($("#total_qty1").text()) ? 0 : parseInt($("#total_qty1").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty1").text(parseInt(total_qty));


                                   var i=1;
                                    var list =
                                        '<tr><td style="text-align:center; padding-left: 5px;width: 8%;" >' + parseInt(document.getElementById("scanned_data1").childElementCount + 1) + '</td>' +
                                        '<td style=" padding-left: 5px;width: 42%;" id="test" class="btl"><input type="hidden" name="user[]" value="'+ order['username'] +'">' + order['username'] + '</td>' +
                                         '<td style=" text-align:center;padding-left: 5px;width: 22%;"><input type="hidden" name="tablety[]" value="' + order['table_type'] + '">' + order['section_type'] + ''+order['table_type']+'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 21%;"><input type="hidden" name="invo[]" value="' + order['invoice_no'] + '">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none"><input type="hidden" name="orderid[]" value="' + order['id'] +'">' + order['id'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="section[]" value="' + order['section_type'] +'">' + order['section_type'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_code[]" value="' + order['product_code'] +'">' + order['product_code'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="remark[]" value="' + order['remark'] +'">' + order['remark'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_name[]" value="' + order['product_name'] +'">' + order['product_name'] +'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 7%;"><input type="hidden" name="qtyno[]" value="' + parseInt(qtyOfInvoice) + '">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td style="text-align:center; padding-left: 42px;"><input type="hidden" name="total[]" value="' + total +  '">' + total +  '</td></tr>';

                                          $("#scanned_data1").append(list);
                                          $("#invoice_num").val('');
                                           $("#table_num").val('');
                                          $('#InvoiceModel').modal('hide');

 
                                }

                              }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });
               });
			        
					
				<!--  end of amit code !-->
        function empty(str){
            return !str || !/[^\s]+/.test(str);
        }
        function addOrderToDialog(barcode) {
            var orders = $("#scanned_data tr");
            var ids = [];
            orders.each(function(row, v) {
                $(this).find("td").each(function(cell, v) {
                    if( cell == 2 ) {
                        ids.push($(this).text());
                    }
                });
            });
            var res = barcode.split("-");
            if( res.length > 1 ) {
                var id = res[0];
                if( ids.indexOf(id) > -1 ) {
                    return;
                }
                var invoice_id = res[1];
                if( ! empty( id ) && ! empty( invoice_id ) ) {
                    $.ajax({
                        url : 'functions.php',
                        type: 'POST',
                        data: { id : id, invoice_no : invoice_id, method: 'getOrderDetailByIdAndInvoice'},
                          success:function(data){
							 var selected_invoice_id=$('#selected_invoice_id').val();
							if(selected_invoice_id)
							{
								var new_in_id=selected_invoice_id+","+id;
							}
							else
							{
								var new_in_id=id;
							}
							// alert(new_in_id);
							$('#selected_invoice_id').val(new_in_id);
                            if( data != null ) {
                                var obj = JSON.parse( data );
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                    if( parseInt(order['status']) != 0 ) {
                                        return;
                                    }
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
									
                                    for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
                                        var amount = 0;
                                        if( order['product_qty'][i] && order['product_amt'][i] ) {
                                            amount = order['product_qty'][i] * order['product_amt'][i];
										    
                                        } else {
                                            amount = 0;
                                        }
                                        // total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
                                    }
									var amount=order['final_amount'];
									var final_amount=order['final_amount'];
									var wallet_paid_amount=order['wallet_paid_amount'];
                                    var total_amount =  empty($("#total_amount").text()) ? 0 : parseFloat($("#total_amount").text());
									// alert(total_amount);
									amount=parseFloat(amount);
									 // amount = amount.toFixed(2);
									 // alert(final_amount);
									 final_amount = parseFloat(final_amount).toFixed(2);
									 // alert(final_amount);
                                    total_amount += amount;
									
                                    total = total.toFixed(2);
                                    
                                    $("#total_amount").text(total_amount.toFixed(2));
									var paid2=$("#paid2").val();
									var paidkoocoin=$("#paidkoocoin").val();
									// alert(paid2);
									
									// alert(wallet_paid_amount);
									// alert(paidkoocoin);
									if(wallet_paid_amount>0)
									{
										var paidkoocoin=parseFloat(wallet_paid_amount)+parseFloat(paidkoocoin);
										$('#paidkoocoin').val(paidkoocoin);
									}
									$("#paid_wallet_amount").val(total_amount.toFixed(2));
									if(paid2 >0)
									{
										// alert(parseFloat(paid2)+parseFloat(paidkoocoin));
										var pending= (parseFloat(total_amount))-(parseFloat(paid2)+parseFloat(paidkoocoin));
										var pending=pending.toFixed(2);
										// alert(pending);
										$("#change2").val(pending);
											$("#paid_wallet_amount").val(pending);
									} 
                                    var total_qty =  empty($("#total_qty").text()) ? 0 : parseInt($("#total_qty").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty").text(parseInt(total_qty));
                                    var list =
                                        '<tr><td style="padding-left: 5px;width: 10%;">' + parseInt(document.getElementById("scanned_data").childElementCount + 1) + '</td>' +
                                        '<td style="padding-left: 5px;width: 30%;">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none">' + order['id'] +'</td>' +
                                        '<td style="padding-left: 5px;width: 30%;">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td style="padding-left: 5px;">' + final_amount +  '</td></tr>';
                                    $("#scanned_data").append(list);
                                }
                            }
                        }
                    });
                }
            }
        }
        function updateOrder(id) {
            console.log(id);
            $.ajax({
                url: 'update_status.php',
                type: 'POST',
                data: {id: id, status: 1},
                success: function (data) {
                    // location.reload();
                }
            });
        }
        document.getElementById("barcode").addEventListener('keypress', handleKeyPress);
        function getCurrentTime() {
            var today = new Date();
            var hh = today.getHours();
            var mm = today.getMinutes();
            var ss = today.getSeconds();
            return hh + ':' + mm + ':' + ss;
        }
        function getCurrentDate() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            switch( mm ) {
                case 1:
                    mm = 'Jan';
                    break;
                case 2:
                    mm = 'Feb';
                    break;
                case 3:
                    mm = 'Mar';
                    break;
                case 4:
                    mm = 'Apr';
                    break;
                case 5:
                    mm = 'May';
                    break;
                case 6:
                    mm = 'Jun';
                    break;
                case 7:
                    mm = 'Jul';
                    break;
                case 8:
                    mm = 'Aug';
                    break;
                case 9:
                    mm = 'Sep';
                    break;
                case 10:
                    mm = 'Oct';
                    break;
                case 11:
                    mm = 'Nov';
                    break;
                case 12:
                    mm = 'Dec';
                    break;
            }
            return dd + '/' + mm + '/' + yyyy;
        }
        $('#myScanModal').on('shown.bs.modal', function () {
            $('#barcode').focus();
        });
		 $('#WalletModel').on('shown.bs.modal', function () {
            $('#mobile_number').focus();
        });
		
        $("#scan_order").click(function() {   
            $('#scan_type').val('mass');        
			$("#change2").val();
			$("#paid2").val();
			$("#selected_invoice_id").val('');
            $("#myScanModal").modal("show");
            $("#total_qty").text('');
            $("#scanned_data").html('');
            $("#total_amount").text('');
			
			
        });

        $(".well").on('click','.print-order', function(e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
			// alert('called');
			var r = confirm("Want to Make Internal Receipt ??");
			 if (r == true) {

            $.ajax({
                url : 'functions.php',
                type: 'POST',
                data: { id : id, method: 'getOrderDetail'},
                success:function(data){
                    if( data != null ) {
                        var obj = JSON.parse( data );
                        if( obj.length > 0 ) {
                            var order = obj[0];
                            data = {order: order, method: "pintOrder", date : getCurrentDate() , time : getCurrentTime()};
                            $.ajax( {
                                url : "functions.php",
                                type:"post",
                                data : data,
                                dataType : 'json',
                                success : function(data) {
								//	alert('print done');
								 // location.reload(); 
									var report = JSON.parse( data );
									

							alert(JSON.stringify(data));	
									alert(report.status);
									alert(report.message);
                                    if( ! data || data.indexOf('print_setting_error') > -1 ) {
                                        alert("You need to set print ip address in profile page.");
                                    }
                                    // alert(data);
									alert("Your order has been printed.");
									 // location.reload(); 
                                },
                                error: function(data){
                                    console.log(data);
                                }
                            });
                        }
                    }
                }
            });
			 }
        });
		  $(".well").on('click','.normal_print', function(e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
			// alert('Making Normal Print');
			var r = confirm("Want to Make Customer Print ??");
				  if (r == true) {
					 $.ajax({
                url : 'functions.php',
                type: 'POST',
                data: { id : id, method: 'getOrderDetail'},
                success:function(data){
                    if( data != null ) {
                        var obj = JSON.parse( data );
                        if( obj.length > 0 ) {
                            var order = obj[0];
                            data = {order: order, method: "normalorder", date : getCurrentDate() , time : getCurrentTime()};
                            $.ajax( {
                                url : "functions.php",
                                type:"post",
                                data : data,
                                dataType : 'json',
                                success : function(data) {
								//	alert('print done');
									var report = JSON.parse( data );
									 alert('Normal print done');
									 tempAlert("Normal print done.",3000);
									// var al = window.open('', 'Normal print done.'); 
									// window.setTimeout(function() {al.close()}, 3000);

							alert(JSON.stringify(data));	
									// alert(report.status);
									// alert(report.message);
                                    if( ! data || data.indexOf('print_setting_error') > -1 ) {
                                        alert("You need to set print ip address in profile page.");
                                    }
                                    alert(data);
									alert("Your order has been printed.");
                                },
                                error: function(data){
                                    console.log(data);
                                }
                            });
                        }
                    }
                }
            });
				  } else {
				
				  }
           
        });
		$("body").on('click','.status_pay_submit',function(e){
			var all_invoice=$('#selected_invoice_id').val();
			// alert(all_invoice);
			var select_wallet=$('#select_wallet_2').val();
			// var select_wallet=$('#select_wallet').val();
			var discount_amount=$('#discount_amount_2').val();
			var wallet_paid_amount=$('#wallet_paid_2').val();
			var total_amount=document.getElementById("total_amount3").innerText;
			var paid_amount_pos=$('#paid_amount_pos_2').val();
			var change_pos=$('#change_pos_2').val();
			if(all_invoice)
			{
				$.ajax({
				  type: "POST",
				  url: "functions.php",
				  data: {method:"bulkprocess",total_amount:total_amount,select_wallet:select_wallet,discount_amount:discount_amount,wallet_paid_amount:wallet_paid_amount,paid_amount_pos:paid_amount_pos,change_pos:change_pos,all_invoice:all_invoice},
				  cache: false,
				  success: function(data) {
				  // alert(data);
				 location.reload();
				  }
				  });
			}
			else
			{
				alert('Add Invoice First');
			}
			
		});
        $("body").on('click','.status',function(e){
			e.preventDefault();
			var process_password_need='<?php echo $process_password_need ?>';
			var $this = $(this);
			 // e.preventDefault();   
			 $(this).attr("disabled", "disabled");  
			 // alert(process_password_need);
			 if(process_password_need>0)
			 $this.val('Processing');
			else
				 $this.val('Done');
			$(this).css('background','green');
            var data_id = $(this).attr("data-id");
			// alert(data_id);
			var selected_invoice_id=$('#selected_invoice_id').val();
			
			if(selected_invoice_id)
			{
				 var new_in_id=selected_invoice_id+","+data_id;
				 $('#selected_invoice_id').val(new_in_id);
			}
			else
			{
				$('#selected_invoice_id').val(data_id);
				var selected_invoice_id=data_id;
			}
			
		       // alert(tbl);
			var invo = $(this).attr("data-invoce")
            var status = $(this).attr("status");
			// alert(status);
			// return false;
			if(status==0)
			{
			$('.fixside').show();
			var select_wallet_2=$('#select_wallet_2').val();
			// alert(select_wallet_2);
			 $.ajax({
                        type: "POST",
                        url: "fetch.php",
                        data: {invoice_num:invo},
                        success: function(data) {
							
							if(process_password_need==0)
							{
								 $.ajax({
								url : 'update_status.php',
								type: 'POST',
								data: {id:data_id, status: 1,select_wallet_2:select_wallet_2},
								success:function(data){
									//~ alert(1);
									// location.reload();
								}
								});
							}
                           // alert(data);
                        // print normal order 
						  
                            if( data != null ) {
                                var obj = JSON.parse( data );
                                // alert(obj.length);
                                if( obj.length > 0 ) {
                                    var order = obj[0];
                                    console.log(order);
                                    var total = 0;
                                    var totalQty = 0;
                                    var qtyOfInvoice = 0;
                                    
                                    var flag = 0;

                                $("#pending_data").find("tr").each(function () {
                                    var td1 = $(this).find("td:eq(0)").text();
                                    var td2 = $(this).find("td:eq(1)").text();
                                    var td3 = $(this).find("td:eq(2)").text();
                                    var td4 = $(this).find("td:eq(3)").text();
                                    var td5 = $(this).find("td:eq(4)").text();
                                    var td6 = $(this).find("td:eq(5)").text();
                                    var td7 = $(this).find("td:eq(6)").text();
                                    var td8 = $(this).find("td:eq(7)").text();
                                    var td9 = $(this).find("td:eq(8)").text();
                                    var td10 = $(this).find("td:eq(9)").text();
                                    var td11 = $(this).find("td:eq(10)").text();
                                      // alert(td1);
                                 //   var tb3 = td3.length;
                                   // var tabl_num = table_num.length;
                                      var trid = $(this).attr('id');
									  // alert(td4);
                                 if ((invoice_num == td4)) {
                                        flag = 1;
                                    }
                                });
                                if (flag == 1) {
                                    alert('Already Exists');
                                    $("#invoice_num").val('');

                                    $("#table_num").val('');

                                } else {

                                  for( var i = 0 ; i < order['product_name'].length ; i ++ ) {
                                        var amount = 0;
                                        if( order['product_qty'][i] && order['product_amt'][i] ) {
                                            amount = order['product_qty'][i] * order['product_amt'][i];
                                        } else {
                                            amount = 0;
                                        }
                                        total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
										
                                    }
                                    var total_amount =  empty($("#total_amount3").text()) ? 0 : parseFloat($("#total_amount3").text());
                                    total_amount += total;
                                    total = total.toFixed(2);
                                    $("#paid_wallet_amount").val(total_amount.toFixed(2));
                                    $("#total_amount3").text(total_amount.toFixed(2));
									var paid=$("#paid3").val();
									// alert(paid);
									if(paid >0)
									{
										var pending= paid-(total_amount.toFixed(2));
										$("#change3").val(pending);
									} 
                                    var total_qty =  empty($("#total_qty3").text()) ? 0 : parseInt($("#total_qty3").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty3").text(parseInt(total_qty));


                                   var i=1;
                                    var list =
                                        '<tr id="'+data_id+'"><td style="text-align:center; padding-left: 5px;width: 8%;" >' + parseInt(document.getElementById("pending_data").childElementCount + 1) + '</td>' +
                                       
                                         '<td style=" text-align:center;padding-left: 5px;width: 22%;"><input type="hidden" name="tablety[]" value="' + order['table_type'] + '">'+order['table_type']+'</td>' +
                                        '<td style="text-align:center; padding-left: 5px;width: 21%;"><input type="hidden" name="invo[]" value="' + order['invoice_no'] + '">' + order['invoice_no'] + '</td>' +
                                          '<td style="text-align:center; padding-left: 5px;width: 7%;"><input type="hidden" name="qtyno[]" value="' + parseInt(qtyOfInvoice) + '">' + parseInt(qtyOfInvoice) + '</td>' +
                                        
                                        '<td style="display: none"><input type="hidden" class="past_invoice" name="past_invoice[]" data_id="'+data_id+'" value="'+data_id+'"/><input type="hidden" name="product_code[]" value="' + order['product_code'] +'">' + order['product_code'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="remark[]" value="' + order['remark'] +'">' + order['remark'] +'</td>' +
                                        '<td style="display: none"><input type="hidden" name="product_name[]" value="' + order['product_name'] +'">' + order['product_name'] +'</td>' +
                                      
                                        '<td style="text-align:center; padding-left: 42px;"><input type="hidden" name="total[]" value="' + total +  '">' + total +  '</td></tr>';

                                    $("#pending_data").append(list);
                                    $("#invoice_num").val('');
                                    $("#table_num").val('');

                                    setTimeout(function() {
								  location.reload();
								}, 30000);
                                     // $('#myModalt').modal('hide');
                                  }


                                }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });
			}
			else   
			{
				 $.ajax({
                    url : 'update_status.php',
                    type: 'POST',
                    data: {id:data_id, status: 1},
                    success:function(data){
                        //~ alert(1);
                        location.reload();
                    }
                });
			}
            
        });
        /*adding new update */
        $("body").on('click','.pop_upss',function(){
            $("#AmountModal").modal("show");
            var dataid=$(this).data("id");
            var prodid=$(this).data("prodid");
            $("#amount").val("");
            $("#id").val(dataid);
            $("#p_id").val(prodid);
        });
        $("form#scan").submit(function(e) {
			// alert(32);
            var orders = $("#scanned_data tr");
            orders.each(function(row, v) {
                $(this).find("td").each(function(cell, v) {
                    if( cell == 2 ) {
                        updateOrder($(this).text());
                    }
                });
            });
            $("#myScanModal").modal("hide");
            $("#scanned_data").html('');
            e.preventDefault();
            window.setTimeout( function() { window.location.reload(); }, 500 );
        });
		 $("form#scan1").submit(function(e) {
            var orders = $("#scanned_data1 tr");
            orders.each(function(row, v) {
                $(this).find("td").each(function(cell, v) {
                    if( cell == 2 ) {
                        updateOrder($(this).text());
                    }
                });
            });
            $("#myScanModal1").modal("hide");
            $("#scanned_data1").html('');
            e.preventDefault();
            window.setTimeout( function() { window.location.reload(); }, 500 );
        });
        $("form#data").submit(function(e) {
            //alert('adf') ;
            // console.log(e);
            e.preventDefault();
            var formData = new FormData(this);
            // console.log($(this).serialize());
            var data = {amount: $("#amount").val(),p_id: $("#p_id").val(),id: $("#id").val()};
            $.ajax({
                url: 'update_amount.php',
                type: 'post',
                data: $(this).serialize(),
                success: function (data) {
                    // console.log(data ? "true" : "false");
					   if(data == 1){
                        $("#AmountModal").modal("hide");
						location.reload();
                        // reloadData(merchant_id,site_url);
                    }else{
                        alert("An error occured, try again later");
                        console.log(data);
                    }
                    
                }
            });
        });
    });
</script>
<script>
    function pending_table(id,section,mid)
    {
        $.ajax({
                url: 'getTableInfo.php',
                type: 'post',
                data: {id:id, section:section, mid:mid},
                success: function (data) {
                    
					   if(data.responseCode == 1){
                        $("#VTitle").html(data.section);
                        $("#VBody").html(data.data);
                        $("#myModalt").modal("hide");
                        $("#myModalTable").modal("show");
						
                        // reloadData(merchant_id,site_url);
                    }else{
                        alert("An error occured, try again later");
                        console.log(data);
                    }
                    
                }
            });
        
    }
    </script>
<script>
    /*window.setInterval('refresh()', 60000);
    function refresh() {
        if( !   hasClass( document.getElementById("myScanModal"), "hide" ) ) {
            window.location.reload();
        }
    }*/
	    setInterval(function(){ 
        
        if( !(hasClass( document.getElementById("myScanModal"), "show" ) || hasClass( document.getElementById("myScanModal1"), "show" )) ) {
			 if(!($("#fixside").is(":visible"))){
				 // alert('close');
					window.location.reload();
			 }
			 else
			 {
				 // alert('open');
			 }

        }
    }, 
    30000);      
	 // setInterval(function(){ 
        
  //       if( !hasClass( document.getElementById("myScanModal1"), "show" ) ) {

  //           window.location.reload();



  //       }
  //   }, 
  //   60000); 
    // var merchant_id = '<?php echo $loginidset;?>';
    // var site_url = '<?php echo $site_url;?>';
    // setInterval(function(){
        // if( !hasClass( document.getElementById("myScanModal"), "show" ) ) {
            // window.location.reload();
            // $.ajax({
                // url : site_url + '/get_order_list_2019_merchant.php',
                // type : 'post',
                // dataType: 'json',
                // data: {merchant: merchant_id},
                // success: function(data){
                    // console.log(data);
                    // console.log("Updating order list...");
                    // console.log("Merchant ID: " + merchant_id);
                    // var content = "";
                    // for(var i = 0; i < data.length; i++){
                        // content +=  "<tr id='"+data[i]['invoice_no']+"' class='"+data[i]['todayorder']+" fdfd "+data[i]['callss']+"' data-id='"+data[i]['id']+"'>";
                        
                        // content +=      "<input type='hidden' class='merchant_"+data[i]['id']+"' value='"+data[i]['merchant_name']+"'>";
                        
                        // content +=      "<input type='hidden' class='userphone_"+data[i]['id']+"' value='"+data[i]['user_mobile_number']+"' >";
                        // content +=      "<input type='hidden' class='merchantphone_"+data[i]['id']+"' value='"+data[i]['merchant_mobile_number']+"' >";
                        // content +=      "<input type='hidden' class='merchantaddress_"+data[i]['id']+"' value='"+data[i]['merchant_google_map']+"' >";
                        // content +=      "<td>"+(i+1)+"</td>";
                        // content +=      "<td>"+data[i]['date']+"<br>"+data[i]['new_time']+"<p style='color: red;'>"+data[i]['diff_time']+"</p></td>";
                        // content +=      "<td class='username_"+data[i]['id']+"'>"+data[i]['user_name']+"</td>";
                        // content += "<td><label class='status' status='"+data[i]['status']+"' data-id='"+data[i]['id']+"'>"+data[i]['sta']+"</label></td>";
                        // if(data[i]['status'] == '2'){
                            // content += "<td target='_blank' href='print_kitchen.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</td>";
                        // } else {
                           // content += "<td></td>"; 
                        // }
                        // content += "<td><a class='print-order' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                        // content += "<td><a target='_blank' href='"+site_url+"/chat/chat.php?sender="+merchant_id+"&receiver="+data[i]['user_id']+"'><i class='fa fa-comments-o' style='font-size:25px;'></i></a></td>";
                        // content += "<td><a target='_blank' href='print.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</a></td>";
                        // content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['section_type']+"</td>";
                        // content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['table_type']+"</td>";  
                        // content += "<td>"+data[i]['invoice_no']+"</td>";
                        // content += "<td class='quantity_"+data[i]['id']+"'>"+data[i]['quantities']+"</td>";
                        // content += "<td class='products_namess product_name_"+data[i]['id']+" test_productss'>"+data[i]['product_name']+"</td>";
                        // content += "<td>"+data[i]['remark']+"</td>";
                        // content += "<td>"+data[i]['product_code']+"</td>";
                        // content += "<td>"+data[i]['amount_val']+"</td>";

                        // content += "<td>"+data[i]['quantity_val']+"</td>";

                        // content += "<td>"+data[i]['total_val']+"</td>";
                        // content += "<td>"+data[i]['wallet']+"</td>";
                        // content += "<td class='location_"+data[i]['id']+" new_tablee'>"+data[i]['location']+"</td>";
                        // content += "<td>"+data[i]['lock_mobile']+"</td>";
                        // content += "<td><a onclick='copy_orderDetail("+data[i]['id']+")'' href='#' class='delivery' id='"+data[i]['id']+"'><i class='fa fa-truck' style='font-size:25px;'></i></a></td>";
                        // content += "<td>"+data[i]['account_type']+"</td>";
                        // content += "</tr>";
                    // }
                    // console.log(content);
                    // $("#orderview-body").html(content);
                // },
                // error: function(data){
                    // console.log("Error:");
                    // console.log(data);
                // }
            // }); 
        // }
    // },
    // 120000);
    $(".pending_invoice_no").click(function(e){
        var id = $(this).attr('invoice-no');
        var top = $("#"+id).position().top;
        window.scroll(0, top - 90); 
        //console.log($(this).attr('invoice-no'));
    });
    /*function ajax_loading(){
        $.ajax({
            url : site_url + 'get_order_list_2019_merchant.php',
            type : 'post',
            dataType: 'json',
            data: {merchant: merchant_id},
            success: function(data){
                //console.log(data);
                console.log("sdfsdf");
                var content = "";
                for(var i = 0; i < data.length; i++){
                    content +=  "<tr id='"+data[i]['invoice_no']+"' class='"+data[i]['todayorder']+" fdfd "+data[i]['callss']+"' data-id='"+data[i]['id']+"'>";
                    
                    content +=      "<input type='hidden' class='merchant_"+data[i]['id']+"' value='"+data[i]['merchant_name']+"'>";
                    
                    content +=      "<input type='hidden' class='userphone_"+data[i]['id']+"' value='"+data[i]['user_mobile_number']+"' >";
                    content +=      "<input type='hidden' class='merchantphone_"+data[i]['id']+"' value='"+data[i]['merchant_mobile_number']+"' >";
                    content +=      "<input type='hidden' class='merchantaddress_"+data[i]['id']+"' value='"+data[i]['merchant_google_map']+"' >";
                    content +=      "<td>"+(i+1)+"</td>";
                    content +=      "<td>"+data[i]['date']+"<br>"+data[i]['new_time']+"<p style='color: red;'>"+data[i]['diff_time']+"</p></td>";
                    content +=      "<td class='username_"+data[i]['id']+"'>"+data[i]['user_name']+"</td>";
                    content += "<td><label class='status' status='"+data[i]['status']+"' data-id='"+data[i]['id']+"'>"+data[i]['sta']+"</label></td>";
                    if(data[i]['status'] == '2'){
                        content += "<td target='_blank' href='print_kitchen.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</td>";
                    } else {
                       content += "<td></td>"; 
                    }
                    content += "<td><a class='print-order' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                    content += "<td><a target='_blank' href='"+site_url+"/chat/chat.php?sender="+merchant_id+"&receiver="+data[i]['user_id']+"'><i class='fa fa-comments-o' style='font-size:25px;'></i></a></td>";
                    content += "<td><a target='_blank' href='print.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</a></td>";
                    content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['table_type']+"</td>";
                    content += "<td>"+data[i]['invoice_no']+"</td>";
                    content += "<td class='quantity_"+data[i]['id']+"'>"+data[i]['quantities']+"</td>";
                    content += "<td class='products_namess product_name_"+data[i]['id']+" test_productss'>"+data[i]['product_name']+"</td>";
                    content += "<td>"+data[i]['remark']+"</td>";
                    content += "<td>"+data[i]['product_code']+"</td>";
                    content += "<td>"+data[i]['amount_val']+"</td>";
                    content += "<td>"+data[i]['quantity_val']+"</td>";
                    content += "<td>"+data[i]['total_val']+"</td>";
                    content += "<td>"+data[i]['wallet']+"</td>";
                    content += "<td class='location_"+data[i]['id']+" new_tablee'>"+data[i]['location']+"</td>";
                    content += "<td>"+data[i]['lock_mobile']+"</td>";
                    content += "<td><a onclick='copy_orderDetail("+data[i]['id']+")'' href='#' class='delivery' id='"+data[i]['id']+"'><i class='fa fa-truck' style='font-size:25px;'></i></a></td>";
                    content += "<td>"+data[i]['account_type']+"</td>";
                    content += "</tr>";
                }
                $("#orderview-body").html(content);
            }
        }); 
    }*/
      
</script>
<script type="text/javascript">
    
/*
TODO:
    Limit number input
    Disallow . from being entered multiple times
    Clean up structure
*/

(function() {
  "use strict";

  // Shortcut to get elements
  var el = function(element) {
    if (element.charAt(0) === "#") { // If passed an ID...
      return document.querySelector(element); // ... returns single element
    }

    return document.querySelectorAll(element); // Otherwise, returns a nodelist
  };

  // Variables
  var viewer = el("#viewer"), // Calculator screen where result is displayed
    equals = el("#equals"), // Equal button
    nums = el(".num"), // List of numbers
    ops = el(".ops"), // List of operators
    theNum = "", // Current number
    oldNum = "", // First number
    resultNum, // Result
    operator; // Batman

  // When: Number is clicked. Get the current number selected
  var setNum = function() {
    if (resultNum) { // If a result was displayed, reset number
      theNum = this.getAttribute("data-num");
      resultNum = "";
    } else { // Otherwise, add digit to previous number (this is a string!)
      theNum += this.getAttribute("data-num");
    }

    viewer.innerHTML = theNum; // Display current number
	

  };

  // When: Operator is clicked. Pass number to oldNum and save operator
  var moveNum = function() {
    oldNum = theNum;
    theNum = "";
    operator = this.getAttribute("data-ops");

    equals.setAttribute("data-result", ""); // Reset result in attr
  };

  // When: Equals is clicked. Calculate result
  var displayNum = function() {

    // Convert string input to numbers
    oldNum = parseFloat(oldNum);
    theNum = parseFloat(theNum);

    // Perform operation
    switch (operator) {
      case "plus":
        resultNum = oldNum + theNum;
        break;

      case "minus":
        resultNum = oldNum - theNum;
        break;

      case "times":
        resultNum = oldNum * theNum;
        break;

      case "divided by":
        resultNum = oldNum / theNum;
        break;

        // If equal is pressed without an operator, keep number and continue
      default:
        resultNum = theNum;
    }

    // If NaN or Infinity returned
    if (!isFinite(resultNum)) {
      if (isNaN(resultNum)) { // If result is not a number; set off by, eg, double-clicking operators
        resultNum = "You broke it!";
      } else { // If result is infinity, set off by dividing by zero
        resultNum = "Look at what you've done";
        el('#calculator').classList.add("broken"); // Break calculator
        el('#reset').classList.add("show"); // And show reset button
      }
    }

    // Display result, finally!
	// alert(resultNum);
    viewer.innerHTML = resultNum;
	$('#paid').val(resultNum);
    equals.setAttribute("data-result", resultNum);

    // Now reset oldNum & keep result
    oldNum = 0;
    theNum = resultNum;

  };

  // When: Clear button is pressed. Clear everything
  var clearAll = function() {
    oldNum = "";
    theNum = "";
    viewer.innerHTML = "0";
    equals.setAttribute("data-result", resultNum);
  };

  /* The click events */

  // Add click event to numbers
  for (var i = 0, l = nums.length; i < l; i++) {
    nums[i].onclick = setNum;
  }

  // Add click event to operators
  for (var i = 0, l = ops.length; i < l; i++) {
    ops[i].onclick = moveNum;
  }

  // Add click event to equal sign
  equals.onclick = displayNum;

  // Add click event to clear button
  el("#clear").onclick = clearAll;


}());
//num2fucntion 

// calculator2
(function() {
  "use strict";

  // Shortcut to get elements
  var el = function(element) {
    if (element.charAt(0) === "#") { // If passed an ID...
      return document.querySelector(element); // ... returns single element
    }

    return document.querySelectorAll(element); // Otherwise, returns a nodelist
  };

  // Variables
  var viewer = el("#viewer2"), // Calculator screen where result is displayed
    equals = el("#equals2"), // Equal button
    nums = el(".num2"), // List of numbers
    ops = el(".ops2"), // List of operators
    theNum = "", // Current number
    oldNum = "", // First number
    resultNum, // Result
    operator; // Batman

  // When: Number is clicked. Get the current number selected
  var setNum = function() {
    if (resultNum) { // If a result was displayed, reset number
      theNum = this.getAttribute("data-num");
      resultNum = "";
    } else { // Otherwise, add digit to previous number (this is a string!)
      theNum += this.getAttribute("data-num");
    }

    viewer.innerHTML = theNum; // Display current number

  };

  // When: Operator is clicked. Pass number to oldNum and save operator
  var moveNum = function() {
    oldNum = theNum;
    theNum = "";
    operator = this.getAttribute("data-ops");

    equals.setAttribute("data-result", ""); // Reset result in attr
  };

  // When: Equals is clicked. Calculate result
  var displayNum = function() {

    // Convert string input to numbers
    oldNum = parseFloat(oldNum);
    theNum = parseFloat(theNum);

    // Perform operation
    switch (operator) {
      case "plus":
        resultNum = oldNum + theNum;
        break;

      case "minus":
        resultNum = oldNum - theNum;
        break;

      case "times":
        resultNum = oldNum * theNum;
        break;

      case "divided by":
        resultNum = oldNum / theNum;
        break;

        // If equal is pressed without an operator, keep number and continue
      default:
        resultNum = theNum;
    }

    // If NaN or Infinity returned
    if (!isFinite(resultNum)) {
      if (isNaN(resultNum)) { // If result is not a number; set off by, eg, double-clicking operators
        resultNum = "You broke it!";
      } else { // If result is infinity, set off by dividing by zero
        resultNum = "Look at what you've done";
        el('#calculator2').classList.add("broken"); // Break calculator
        el('#reset').classList.add("show"); // And show reset button
      }
    }

    // Display result, finally!
    viewer.innerHTML = resultNum;
	$('#paid2').val(resultNum);
    equals.setAttribute("data-result", resultNum);

    // Now reset oldNum & keep result
    oldNum = 0;
    theNum = resultNum;

  };

  // When: Clear button is pressed. Clear everything
  var clearAll = function() {
    oldNum = "";
    theNum = "";
    viewer.innerHTML = "0";
    equals.setAttribute("data-result", resultNum);
  };

  /* The click events */

  // Add click event to numbers
  for (var i = 0, l = nums.length; i < l; i++) {
    nums[i].onclick = setNum;
  }

  // Add click event to operators
  for (var i = 0, l = ops.length; i < l; i++) {
    ops[i].onclick = moveNum;
  }

  // Add click event to equal sign
  equals.onclick = displayNum;

  // Add click event to clear button
  el("#clear2").onclick = clearAll;
  

}());
// calcuator 3 

(function() {
  "use strict";

  // Shortcut to get elements
  var el = function(element) {
    if (element.charAt(0) === "#") { // If passed an ID...
      return document.querySelector(element); // ... returns single element
    }

    return document.querySelectorAll(element); // Otherwise, returns a nodelist
  };

  // Variables
  var viewer = el("#viewer3"), // Calculator screen where result is displayed
    equals = el("#equals3"), // Equal button
    nums = el(".num3"), // List of numbers
    ops = el(".ops3"), // List of operators
    theNum = "", // Current number
    oldNum = "", // First number
    resultNum, // Result
    operator; // Batman

  // When: Number is clicked. Get the current number selected
  var setNum = function() {
    if (resultNum) { // If a result was displayed, reset number
      theNum = this.getAttribute("data-num");
      resultNum = "";
    } else { // Otherwise, add digit to previous number (this is a string!)
      theNum += this.getAttribute("data-num");
    }

    viewer.innerHTML = theNum; // Display current number

  };

  // When: Operator is clicked. Pass number to oldNum and save operator
  var moveNum = function() {
    oldNum = theNum;
    theNum = "";
    operator = this.getAttribute("data-ops");

    equals.setAttribute("data-result", ""); // Reset result in attr
  };

  // When: Equals is clicked. Calculate result
  var displayNum = function() {

    // Convert string input to numbers
    oldNum = parseFloat(oldNum);
    theNum = parseFloat(theNum);

    // Perform operation
    switch (operator) {
      case "plus":
        resultNum = oldNum + theNum;
        break;

      case "minus":
        resultNum = oldNum - theNum;
        break;

      case "times":
        resultNum = oldNum * theNum;
        break;

      case "divided by":
        resultNum = oldNum / theNum;
        break;

        // If equal is pressed without an operator, keep number and continue
      default:
        resultNum = theNum;
    }

    // If NaN or Infinity returned
    if (!isFinite(resultNum)) {
      if (isNaN(resultNum)) { // If result is not a number; set off by, eg, double-clicking operators
        resultNum = "You broke it!";
      } else { // If result is infinity, set off by dividing by zero
        resultNum = "Look at what you've done";
        el('#calculator3').classList.add("broken"); // Break calculator
        el('#reset3').classList.add("show"); // And show reset button
      }
    }

    // Display result, finally!
    viewer.innerHTML = resultNum;
	// alert(resultNum);
	$('#paid3').val(resultNum);
    equals.setAttribute("data-result", resultNum);

    // Now reset oldNum & keep result
    oldNum = 0;
    theNum = resultNum;

  };

  // When: Clear button is pressed. Clear everything
  var clearAll = function() {
    oldNum = "";
    theNum = "";
    viewer.innerHTML = "0";
    equals.setAttribute("data-result", resultNum);
  };

  /* The click events */

  // Add click event to numbers
  for (var i = 0, l = nums.length; i < l; i++) {
    nums[i].onclick = setNum;
  }

  // Add click event to operators
  for (var i = 0, l = ops.length; i < l; i++) {
    ops[i].onclick = moveNum;
  }

  // Add click event to equal sign
  equals.onclick = displayNum;

  // Add click event to clear button
  el("#clear3").onclick = clearAll;
  

}());

</script>
<!-- code by avnish tomar -->
<script>
$('#mobile_number').on("input",function(){
	var len = $(this).val().length;
	if( len >= 10 ){
	    $("[mode='confirm']").click();
	}
});
</script>
<!-- end of avnish tomar code -->