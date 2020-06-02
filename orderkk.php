<?php
session_start();
include("config.php");
$current_time = date('Y-m-d H:i:s');
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
// if(!isset($_SESSION['login']) || empty($_SESSION['login']))
// {
    // header("location:logout.php");
// }else{
    // if(!checkSession()){
        // header("location:logout.php");
    // }
// }
/* code for limit  */
$sql = "SELECT count(id) as total_count FROM order_list WHERE merchant_id ='".$_SESSION['login']."'";
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
$query="SELECT order_list.*, sections.name as section_name FROM order_list left join sections on order_list.section_type = sections.id WHERE merchant_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC LIMIT $offset, $rec_limit";

$total_rows = mysqli_query($conn,$query);
$total_rows1 = mysqli_query($conn,$query);
$merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$_SESSION['login']."'"));
$pending_time = $merchant_name['pending_time'];
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
                <div class="well">
                    <?php if (count($pending_data) > 0){?>
                        <h5 style="color: red;">Invoice not yet done and require immediate attention!</h5>
                        <div style="width: 380px; max-height: 300px; overflow: auto;">
                        
                            <table class="table table-striped" >
                              <thead>
                                <tr>
                                  <th><?php echo $language["date_of_order"];?></th>
                                  <th>Invoice <br> Numbers</th>
                                  <th>Section <br> Numbers</th>
                                  <th>Table <br> Number</th>
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
                                            <td style="font-size: 20px;"><?= $value['section_name'];?></td>
                                            <td style="font-size: 20px;"><?= $value['table_no'];?></td>
                                        </tr>
                                    <?php } ?>
                              </tbody>
                            </table> 
                    </div>
                    <?php }?>
                    
                    
                    <div>
                        <h3><?php //echo $language['order_list'];?></h3>
                        <span style="cursor: pointer; color:blue;text-decoration:underline;font-size: 40px;" id="scan_order"><?php echo $language['scan_order'];?></span>
						&nbsp;&nbsp;&nbsp;&nbsp;<span style="cursor: pointer; color:green;text-decoration:underline;font-size: 40px;" id="scan_order1">Table/combine bill</span>

					</div>
                    <?php
                    $dt = new DateTime();
                    $today =  $dt->format('Y-m-d');
                    ?>
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
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php echo $language["items"];?></th>
                            <th><?php echo $language["date_of_order"];?></th>
                            <th>Username</th>
                            <th><?php echo $language["status"];?></th>
                           
                            <th>Receipt</th>
							 <th>Receipt 2</th>
                            <th><?php echo $language["chat"];?></th>
							 <th><?php echo $language["quantity"];?></th>
                           
								<th><?php echo "Section";?></th>
                            <th><?php echo $language["table_number"];?></th>
                            <th>Invoice Number</th>
                            <th>Invoice</th>
                            <th class="product_name test_product"><?php echo $language["product_name"];?></th>
							   <th class="product_name test_product"><?php echo "VARIENT";?></th>
                            <th class="product_name test_product"><?php echo $language["remark"];?></th>
                            <th><?php echo $language["product_code"];?></th>
                            <th>Price</th>
                            <th><?php echo $language["amount"];?></th>
                            <th><?php echo $language["total"];?></th>
                            <th><?php echo $language["mode_of_payment"];?></th>
                            <th class="location_head"><?php echo $language["location"];?></th>
                            <th>Phone</th>
                            <th>Delivery <br> Service</th>
                            <!-- <th><?php echo $language["print"];?></th> -->
                            <th>K1/K2</th>
                        </tr>
                        </thead>
                        <tbody id="orderview-body">
                        <?php
                        $i =1;
                        while ($row=mysqli_fetch_assoc($total_rows)){
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
                            <?php if($i==1){ ?>
							<input type="hidden" value="<?php echo $row['id'];?>"  id="last_id"/>
							<?php } ?>
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
                            <td class="username_<?php echo $row['id'];?>"><?php echo $row['user_name']; ?></td>
                            <td>
                                <?php
                                if($row['status'] == 0) $sta = "Pending";
                                else if($row['status'] == 1) $sta = "Done";
                                else $sta = "Accepted";
                                ?>
                                <label class= "status" status="<?php echo $row['status'];?>" data-id="<?php echo $row['id']; ?>"> <?php echo $sta; ?></label>
                            </td>
                            
                            <td>
							 <a class="print-order <?php  if($row['auto_print']==0){ echo "blinking";} ?>" href="#" data-id="<?php echo $row['id']; ?>" data-invoice="<?php echo $row['invoice_no']; ?>">Print Receipt</a>
							  
                            </td>
							 <td>
							 <a class="normal_print" href="#" data-id="<?php echo $row['id']; ?>" data-invoice="<?php echo $row['invoice_no']; ?>">Print Receipt</a>
							  
                            </td>
                            <td><a target="_blank" href="<?php echo $site_url; ?>/chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $row['user_id'];?>"><i class="fa fa-comments-o" style="font-size:25px;"></i></a></td>
                             <td class="quantity_<?php echo $row['id'];?>"><?php
                                foreach ($quantity_ids as $key)
                                {
                                    echo $key;
                                    echo '<br>';
                                }
                                ?></td>
                            <td class="table_number_<?php echo $row['id']?>"><?php echo $row['section_name'];?></td>
							<td class="table_number_<?php echo $row['id']?>"><?php echo $row['table_type'];?></td>
                            <td>
                                <?php echo $row['invoice_no']; ?>
                            </td>
							  <td><a target="_blank" href="print.php?id=<?php echo $row['id'];?>&merchant=<?php echo $_SESSION['login']?>">Print</a></td>
                           
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
							 if($row['varient_type'])
							 {
							$v_str=$row['varient_type'];
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
                            <td>
                                <?php
                                foreach ($amount_val as $key => $value){
                                    echo @number_format($value, 2).'<br>';
                                }
                                ?>
                            </td>
                            <td class="amount_<?php echo $row['id'];?>">

                                <?php

                                $q_id = 0;

                                foreach ($amount_val as $key => $value){

                                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
								
                                    if($value == '0') { ?>

                                        <p class="pop_upss" data-id="<?php echo $row['id']; ?>"  style='margin-bottom: 0px;' data-prodid="<?php echo $key; ?>"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></p>

                                    <?php  }

                                    if( $quantity_ids[$key] && $value ) {

                                        echo @number_format($quantity_ids[$key] * $value, 2).'<br>';

                                    } else {

                                        // echo '0<br>';
										echo '<p class="pop_upss" data-id=' . $row['id'] . '  style="margin-bottom: 0px;display:block;" data-prodid="' . $key . '""><i class="fa fa-pencil-square-o" aria-hidden="true"></i>0</p>';

                                    }

                                    $q_id++;

                                } ?>

                            </td>  
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
                            <td><?php echo $row['wallet'];  ?></td>
                            <td class="location_<?php echo $row['id']; ?> new_tablee"><?php echo $row['location'];?></td>
                              <td><?php echo $row['user_mobile']; ?></td>
                            <td><a onclick="copy_orderDetail(<?php echo $row['id']?>)" href="#" class="delivery" id="<?php echo $row['id'];?>"><i class="fa fa-truck" style="font-size:25px;"></i></a></td>
                            <td><?php echo $row['wallet'];  ?></td>
                            <?php if($sta == "Done"){?>
                                <td></td>
                            <?php }?>
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
				<audio id="my_audio" src="<?php echo $site_url;?>/ting.wav" autostart="0"></audio>    
					
					      
						
						
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
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content" style="border-radius: 4px;background-color: transparent;    padding: 0px;">
                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color:#99e1dc57;margin: 0px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="color: #3a3939c4;">Statement</h4>
                                    </div>
                                    <div style="background-color:#99e1dc;padding: 10px;min-height:675px;">
                                        

                                      
                                        <div class="col-sm-12">
                                        <div class="col-sm-8" style="float: left;">
                                           <div class="inline fields">
                                            <div style="display: inline-block;height: 50px;">
                                                <label style="display: inline-block;height: 50px;">Barcode</label>
                                                <input type="text" id="barcode" autofocus style="display: inline-block;height: 50px;">
                                            </div>
                                            <div style="display: inline-block;height: 50px;">
                                                <button style="width: 100px; height: 50px;background-color: #99e1dc;" id="add_invoice">Add</button>
                                            </div>
                                        </div>
                                        <form id="scan" style="height: 476px; padding-top: 10px;">
                                            <table style="width: 100%;">
                                                <thead>
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">No</th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">InvoiceNumber</th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">Qty</th>
                                                <th style="padding-left: 5px;">Amount</th>
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
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty1" id="tol_qty1" value="">
                                                <span id="total_amount" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt1" id="tol_mnt1" value="">
                                            </div>   

                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid</span>
                                                <input type="text" id="paid2" value="0" class="amount" name="paid2" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Change</span>
                                                <input type="text" id="change2" name="change" class="amount" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%; border: 1px solid #555555;padding-left: 4px;">
                                            </div>
                                            <!--div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                <span id="total_amount2" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                            </div!-->
                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">
                                                <!--button style="width:200px;height:50px;background-color: #99e1dc;">Submit</button!-->
												 <button id="amount_submit_button" style="width:200px;height:50px;background-color: #99e1dc;">Submit</button>
                                            </div>
                                        </form>
                                        </div>
                                        <div  class="col-sm-4" style="float: left;">

                                                <form id="scan" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">Calculator</th>
                                               
                                                </thead>
                                               
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            <div id="calculator2" class="calculator">

                                              <input type="button" id="clear2" class="clear button" value="C">

                                              <div id="viewer2" class="viewer">0</div>

                                              <input type="button" class=" num2 button" data-num="7" value="7" id="7" >
                                              <input type="button" class="num2 button" data-num="8" value="8" id="8" >
                                              <input type="button" class="num2 button" data-num="9" value="9" id="9">
                                              <input  type="button" data-ops="plus" class="ops2 button" value="+">

                                              <input type="button" class=" num2 button" data-num="4" value="4" id="4">
                                              <input type="button" class="num2 button" data-num="5" value="5" id="5">
                                              <input type="button" class="num2 button" data-num="6" value="6" id="6">
                                              <input type="button" data-ops="minus" class="ops2 button" value="-">

                                              <input type="button" class="num2 button" data-num="1" value="1" id="1" >
                                              <input type="button" class="num2 button" data-num="2" value="2" id="2">
                                              <input type="button" class="num2 button" data-num="3" value="3" id="3" >
                                              <input type="button" data-ops="times" class="ops2 button" value="*">

                                              <input type="button" class="num2 button" data-num="0" value="0" id="0" >
                                              <input type="button" class="num2 button" data-num="." value="." id="." >
                                              <input type="button" id="equals2" class="equals2 button" data-result="" value="=">
                                              <input type="button" data-ops="divided by" class="ops2 button" value="/">
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
                             <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content" style="border-radius: 4px;background-color: transparent;    padding: 0px;">
                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color:#296ca0;margin: 0px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" style="color: #3a3939c4;">Statement</h4>
                                    </div>
                                    <div style="background-color: #6dafe2;padding: 10px;">
                                        

                                        <div class="inline fields">
                                            <div style="display: inline-block;">
                                                <label  style="display: inline-block;">Merchant ID :</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="text" name="merchantid"  autofocus style="display: inline-block; height: 35px;" value="<?php echo $_SESSION['login']  ; ?>" readonly>
                                                <button style="display: none; width: 100px; background-color: red;" id="">Merchant ID</button>
                                            </div>
                                            <div style="display: inline-block;margin-left: 8%;">
                                                
                                                <label style="display: inline-block;">Counter Number :</label>
                                                <input type="text" autofocus style="display: inline-block;height: 35px;" readonly>
                                                <button style="display: none;background-color: red;" id="">Invoice</button>
                                            </div>
                                            <div style="display: inline-block;">
                                                <label style="display: inline-block;">Invoice Number :</label>
                                                <input type="text" id="invoice_num" class="invoice_num"  autofocus style="display: inline-block;height: 35px;">
                                                <button style="background-color: red;height: 50px;" data-toggle="modal" data-target="#InvoiceModel" id="invoice">Invoice</button>
                                            </div>  
                                             
                                            <div style="display: inline-block;width:41%;">
                                                &nbsp;
                                                <label style="display: inline-block;">Table Number :</label>
                                                <input type="text" id="table_num" class="table_num" autofocus style="display: inline-block;height: 35px;">
                                                <button style="background-color: green;height: 50px;" data-toggle="modal" data-target="#myModalt" id="table">Table</button>
                                            </div>
                                            <div style="display: inline-block;">
                                                <button style="background-color: #99e1dc;width:161px;height:50px;" id="add_invoicemy">Add</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                        <div class="col-sm-8" style="float: left;">
                                            <form id="form" style="" method="POST" action="statement.php">
                                             <?php
                                               $pin = mt_rand(1000, 9999);
                                             ?>
                                              
                                           <input type="hidden" name="statementid" id="statementid" value="<?php echo $pin ;?>">
                                           <input type="hidden" name="merchant_ses" id="merchant_ses" value="<?php echo $_SESSION['login'];?>">
                                            <table style="width: 100%;" id="mytbl">
                                                <thead>
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 8%;"><center>No</center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 45%;"><center>Username</center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 22%;"><center>Table Number</center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 18%;"><center>Invoice Number</center></th>
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 7%;"><center>Qty</center></th>
                                                <th style="padding-left: 14px;"><center>Amount</center></th>
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
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>
                                                <span id="total_qty1" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>
                                                 <input type="hidden" name="tol_qty1" id="tol_qty1" value="">
                                                <span id="total_amount1" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>
                                                 <input type="hidden" name="tol_mnt1" id="tol_mnt1" value="">
                                            </div>

                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Paid</span>
                                                <input type="text" id="paid" value="0" class="amount" name="paid" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%;border: 1px solid #555555 ;padding-left: 4px;">
                                            </div>
                                            <div style="padding-top: 5px;    display: flex;">
                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Change</span>
                                                <input type="text" id="change" name="change" class="amount" style="background-color:#6dafe2; font-size: 20px; width: 30%;margin-left: 30%; border: 1px solid #555555;padding-left: 4px;">
                                            </div>

                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">
                                                
                                                <button type="button" style="width:200px;height:50px;background-color: #99e1dc;" data-dismiss="modal">Close</button>
                                               <input type="submit" style="width:200px;height:50px;background-color: #99e1dc;" value="Print" onclick="this.form.target='_blank';return true;">
                                            
                                                <input type="button" style="width:200px;height:50px;background-color: #99e1dc;"  value="Submit" onclick="myFunction()">
                                            </div>
                                        </form>
                                        </div>
                                        <div  class="col-sm-4" style="float: left;">

                                                <form id="scan1" style="">
                                            <table style="width: 100%;">
                                                
                                                <thead style="background-color: #e8dfdf;">
                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">Calculator</th>
                                               
                                                </thead>
                                               
                                            </table>
                                            <div class="modal-body" style="padding-bottom:0;padding: 0; overflow: auto;background-color: white;">
                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">
                                                    <tbody style="width: 100%;">

                                            <div id="calculator" class="calculator">

                                              <input type="button" id="clear" class="clear button" value="C">

                                              <div id="viewer" class="viewer">0</div>

                                              <input type="button" class=" num button" data-num="7" value="7" id="7" >
                                              <input type="button" class="num button" data-num="8" value="8" id="8" >
                                              <input type="button" class="num button" data-num="9" value="9" id="9">
                                              <input  type="button" data-ops="plus" class="ops button" value="+">

                                              <input type="button" class=" num button" data-num="4" value="4" id="4">
                                              <input type="button" class="num button" data-num="5" value="5" id="5">
                                              <input type="button" class="num button" data-num="6" value="6" id="6">
                                              <input type="button" data-ops="minus" class="ops button" value="-">

                                              <input type="button" class="num button" data-num="1" value="1" id="1" >
                                              <input type="button" class="num button" data-num="2" value="2" id="2">
                                              <input type="button" class="num button" data-num="3" value="3" id="3" >
                                              <input type="button" data-ops="times" class="ops button" value="*">

                                              <input type="button" class="num button" data-num="0" value="0" id="0" >
                                              <input type="button" class="num button" data-num="." value="." id="." >
                                              <input type="button" id="equals" class="equals button" data-result="" value="=">
                                              <input type="button" data-ops="divided by" class="ops button" value="/">
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
                                        <h4 class="modal-title">Pending Invoice</h4>
                                        <button type="button" style="margin-right: 30%;width:130px;height:50px;background-color: #99e1dc;" data-dismiss="modal">Close</button>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;">
                                            <div class="col-sm-12">
                                                <div class="form-group" id="invoice_list">
												<?php
													$Merchantid = $_SESSION['login'];



													$sql = "select * from order_list where status in(0,2) and merchant_id='$Merchantid' group by invoice_no limit 0,30";

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
						 <div class="modal fade" id="myModalt" role="dialog" >
                            <div class="modal-dialog modal-lg">
                                
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Pending Table</h4>
                                        <button type="button" style="margin-right: 30%; width:130px;height:50px;background-color: #99e1dc;" data-dismiss="modal">Close</button>
                                    </div>
                                    <form id ="data">
                                        <div class="modal-body" style="padding-bottom:0px;">
                                            <div class="col-sm-12">
                                                <div class="form-group" id="table_list">
												<?php
												   $Merchantid = $_SESSION['login'];

        

	    $sql = "select order_list.invoice_no,order_list.id,order_list.section_type,order_list.table_type,sections.name from order_list inner join sections on order_list.section_type=sections.id where order_list.status in(0,2) and order_list.merchant_id='$Merchantid' group by order_list.invoice_no order by sections.name asc limit 0,30";

	$rel = mysqli_query($conn, $sql);





		while($data = mysqli_fetch_assoc($rel))

		{   

		   // print_R($data);

		    echo ' <input type="button" style="margin: 10px; background-color:#296ca0;" class="btn btn-info" name="tbl" data-invoce="'.$data['invoice_no'].'" data-id="'.$data['id'].'" data-section="'.$data['section_type'].'"  data-table="'.$data['table_type'].'"  value="'.$data["name"].'-'.$data["table_type"].'">';

		    

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
                            </div>
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
        </main>
		
    </div>
    <!-- /.widget-body badge -->
	

</div>
<!-- /.widget-bg -->
<!-- /.content-wrapper -->
<?php include("includes1/footer.php"); ?>
</body>
</html>
<script type="text/javascript">
     var merchant_id = '<?php echo $_SESSION['login'];?>';
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
 //  if (paid == '' || orderid == '' ) {
//alert("Something went wrong");
//}else{        

          $.ajax({
          type: "POST",
          url: "statement_data.php",
          data: {statementid:statementid, merchant_ses:merchant_ses, paid:paid, change:change, tol_qty:tol_qty, tol_mnt: tol_mnt ,statementid:statementid, product_name:product_name, product_code:product_code, user:user, remark:remark, tablety:tablety, invo:invo, orderid:orderid, section:section, qtyno:qtyno,total:total },
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
                url : site_url + '/get_order_list_merchant.php',
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
                        content +=      "<td class='username_"+data[i]['id']+"'>"+data[i]['user_name']+"</td>";
                        content += "<td><label class='status' status='"+data[i]['status']+"' data-id='"+data[i]['id']+"'>"+data[i]['sta']+"</label></td>";
                      
                        content += "<td><a class='print-order' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                        content += "<td><a class='normal_print' href='#' data-id='"+data[i]['id']+"' data-invoice='"+data[i]['invoice_no']+"'>Print Receipt</a></td>";
                        content += "<td><a target='_blank' href='"+site_url+"/chat/chat.php?sender="+merchant_id+"&receiver="+data[i]['user_id']+"'><i class='fa fa-comments-o' style='font-size:25px;'></i></a></td>";
                         content += "<td class='quantity_"+data[i]['id']+"'>"+data[i]['quantities']+"</td>";
					  
                        content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['section_type']+"</td>";
                        content += "<td class='table_number_"+data[i]['id']+"'>"+data[i]['table_type']+"</td>";
                        content += "<td>"+data[i]['invoice_no']+"</td>";
                       content += "<td><a target='_blank' href='print.php?id="+data[i]['id']+"&merchant="+merchant_id+"'>Print</a></td>";
                        content += "<td class='products_namess product_name_"+data[i]['id']+" test_productss'>"+data[i]['product_name']+"</td>";
                         content += "<td>"+data[i]['varient_type']+"</td>";
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
                    // console.log(content);
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
    $(document).ready(function(){
		 
		     setInterval(function() {
				 // alert('new');
        //unPrintedOrders = [];
		var last_id = $('#last_id').val();
		var site_url = '<?php echo $site_url;?>';
		// alert(last_id);
		var merchant_id="<?php echo $_SESSION['login']; ?>";
        var data = {last_id:last_id, method: "neworder",merchant_id:merchant_id};
        $.ajax( {
            url : "functions.php",
            type:"post",
            data : data,
            dataType : 'json',
            success : function(data) {
				// alert(data);
				  if(data>0){
					  // alert('New order');
					  document.getElementById("my_audio").play();   
					 
					$('#last_id').val(data);
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
			<!--  start of amit code !-->
		 $("#scan_order1").click(function() {
             
            $("#myScanModal1").modal("show");
            $("#total_qty1").text('');
            $("#scanned_data1").html('');
            $("#total_amount1").text('');
		
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
                                    total_amount += total;
                                    total = total.toFixed(2);
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
               
                      var final = parseFloat(tol)-parseFloat(mb);
                      var value = Math.abs(final);
                      var v = value.toFixed(2);
                      $('#change2').val(v);
                      
                       var tol_qty = document.getElementById("total_qty").innerText;
                     //  alert(tol_qty);
                    
                        $('#total_qty').val(tol_qty);
                        $('#total_amount2').val(tol);



                    });
						 $('body').on('click',"input[name=tbl]",function(e){
						  // e.preventDefault();
						     e.preventDefault();   
                      var tbl = $(this).attr("data-table");
                      var tbl_val = $(this).val();
                      var section = $(this).attr("data-section");
                       var sec_name = $(this).attr("data-name");
                      var id = $(this).attr("data-id");
                          $('.table_num').val(tbl);
                          $('.section_num').val(section);
                      
                     var invoice_num = $(".invoice_num").val();
                     var table_num = $(".table_num").val();
                     var section_num = $(".section_num").val();
                    // var q = tbl.match(/[a-z]+|\d+/ig);
                  

                 $.ajax({
                        type: "POST",
                        url: "fetch.php",
                        data: {id:id,table_num:tbl,section_num:section},
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
                                      
                                 //   var tb3 = td3.length;
                                   // var tabl_num = table_num.length;
                                      var trid = $(this).attr('id');
                                 if ((invoice_num == td4) || (tbl_val == trid)) {
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

                                    
                                     $('#myModalt').modal('hide');
                                  }


                                }
                            }
 

                        },
                        error: function(result) {
                            alert('error');
                        }
                    });



                    });
					 $('body').on("click", "input[name=invo]",function(e){
						 e.preventDefault();
              var invo = $(this).attr("value");
                   $(this).attr("disabled", "disabled");
              $('#invoice_num').val(invo);
              var invoice_num = $(".invoice_num").val();
              var table_num = $(".table_num").val();

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
                                        total += amount;
                                        totalQty += parseInt(order['product_qty'][i]);
                                        qtyOfInvoice += parseInt(order['product_qty'][i]);
                                    }
                                    var total_amount =  empty($("#total_amount").text()) ? 0 : parseFloat($("#total_amount").text());
                                    total_amount += total;
                                    total = total.toFixed(2);
                                    $("#total_amount").text(total_amount.toFixed(2));
									var paid2=$("#paid2").val();
									// alert(paid2);
									if(paid2 >0)
									{
										var pending= paid2-(total_amount.toFixed(2));
										$("#change2").val(pending);
									}   
                                    var total_qty =  empty($("#total_qty").text()) ? 0 : parseInt($("#total_qty").text());
                                    total_qty += qtyOfInvoice;
                                    $("#total_qty").text(parseInt(total_qty));
                                    var list =
                                        '<tr><td style="padding-left: 5px;width: 10%;">' + parseInt(document.getElementById("scanned_data").childElementCount + 1) + '</td>' +
                                        '<td style="padding-left: 5px;width: 30%;">' + order['invoice_no'] + '</td>' +
                                        '<td style="display: none">' + order['id'] +'</td>' +
                                        '<td style="padding-left: 5px;width: 30%;">' + parseInt(qtyOfInvoice) + '</td>' +
                                        '<td style="padding-left: 5px;">' + total +  '</td></tr>';
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
        $("#scan_order").click(function() {           
			$("#change2").val();
			$("#paid2").val();
            $("#myScanModal").modal("show");
            $("#total_qty").text('');
            $("#scanned_data").html('');
            $("#total_amount").text('');
			
			
        });

        $(".well").on('click','.print-order', function(e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
			alert('called');
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
									var report = JSON.parse( data );
									

							alert(JSON.stringify(data));	
									alert(report.status);
									alert(report.message);
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
        });
		  $(".well").on('click','.normal_print', function(e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
			// alert('Making Normal Print');
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
									alert(report.status);
									alert(report.message);
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
        });
        $("body").on('click','.status',function(){
            var data_id = $(this).attr("data-id");
            var status = $(this).attr("status");
            if(status == 0){
                $.ajax({
                    url : 'update_status.php',
                    type: 'POST',
                    data: {id:data_id, status: 2},
                    success:function(data){
                        //~ alert(1);
                        location.reload();
                    }
                });
            }
            if(status == 2){
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

            window.location.reload();

        }
    }, 
    60000);       
	 // setInterval(function(){ 
        
  //       if( !hasClass( document.getElementById("myScanModal1"), "show" ) ) {

  //           window.location.reload();



  //       }
  //   }, 
  //   60000); 
    // var merchant_id = '<?php echo $_SESSION['login'];?>';
    // var site_url = '<?php echo $site_url;?>';
    // setInterval(function(){
        // if( !hasClass( document.getElementById("myScanModal"), "show" ) ) {
            // window.location.reload();
            // $.ajax({
                // url : site_url + '/get_order_list_merchant.php',
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
            url : site_url + 'get_order_list_merchant.php',
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

</script>

