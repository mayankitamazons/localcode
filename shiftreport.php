<?php 
include("config.php");

$date = date('Y-m-d H:i');
$shift_start_dt = $date;
$end_dt = $date;
$shift_printed=$_SESSION['shift_printed'];
if($shift_printed=="y")
{
	include("logout.php");
}
$_SESSION['shift_printed']='';
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
	header("location:logout.php");
}
// $merchant = $_SESSION['login'];
// $mercant_id=$_SESSION['login'];
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
$mercant_id=$loginidset;
$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$_SESSION['login']."'"));
$lastcash=mysqli_fetch_assoc(mysqli_query($conn,"select *  from cash_system where user_id='$mercant_id' and is_active='n' order by id desc limit 0,1"));
  $last_cash_logout=$lastcash['logout_time'];

if($last_cash_logout)
 $last_cash_logout= strtotime('+1 second', $last_cash_logout);
if($last_cash_logout=='' && $merchant_detail['last_login'])
$last_cash_logout=$merchant_detail['last_login'];
else
{
	if($last_cash_logout=='')
	$last_cash_logout=strtotime($date);
}
if($last_cash_logout)
  $shift_start_dt=date("Y-m-d H:i:s", $last_cash_logout);

$sstper = $merchant_detail['sst_rate'];
// echo $last_cash_logout;
// die;
if(isset($_POST['search'])){
	// $start_dt = $_POST['start_dt'];
	// $end_dt = $_POST['end_dt'];
	$end_dt=date("Y-m-d H:i", strtotime($_POST['end_dt']));
}

$print_report="y";
  $sql1 = "
	SELECT *
    FROM order_list
    left join users on order_list.user_id = users.id
    WHERE created_on >= '$shift_start_dt' AND created_on <= '$end_dt' AND merchant_id = '$mercant_id' and status in(0,2)";

$result1 = mysqli_query($conn, $sql1);
$pending_count=mysqli_num_rows($result1);
$pastquery=mysqli_query($conn,"select *  from cash_system where user_id='$mercant_id' and is_active='n' order by id desc");

 $pastcount=mysqli_num_rows($pastquery);
    $sql = "
	SELECT *
    FROM order_list
    left join users on order_list.user_id = users.id
    WHERE created_on >= '$shift_start_dt' AND created_on <= '$end_dt' AND merchant_id = '$mercant_id'";

$result = mysqli_query($conn, $sql);
$reports = array();
while($row = mysqli_fetch_assoc($result)){
	$products = explode(",", $row['product_id']);
	$qtys = explode(",", $row['quantity']);
	$amounts = explode(",", $row['amount']);
	for($i = 0; $i < count($products); $i++){
		$product_id = $products[$i];
		$sql2 = "SELECT *
                FROM products
                WHERE id = '$product_id'";
        $product = mysqli_fetch_assoc(mysqli_query($conn, $sql2));
        $item = array(
        	'name' => $product['product_name'],
        	'category' => $product['category'],
        	'qty' => $qtys[$i],
        	'amounts' => $amounts[$i],
        	'date' => substr($row['created_on'], 0, 10)
        );
        array_push($reports, $item);
	}
}
function cmp($a, $b){
    return strcmp($a['category'], $b['category']);
}
usort($reports, "cmp");
if(($pending_count>0) && count($reports)>0)
$print_report="n";	


?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
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
	/* 10-17 customize */
	.search-item{
		display:inline-block;
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
				
					<form action="" method="post" style="width:100%;">
						<input type="hidden" value="<?= $_SESSION['login'];?>" name="user_id">
						<div class="col-sm-12">
							<div class="col-sm-3 search-item">
								<div class="form-group">
									<label>Shift Starting Date: </label>
									<div class="input-group">
									
                                        <input readonly type="text" size="16" class="form-control" name="start_dt" value="<?= $shift_start_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <!--button class="btn default" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button!-->
                                        </span>
                                    </div>
									<!-- <input type="date" name="start_dt" class="form-control form_datetime" value="<?= $shift_start_dt;?>"/> -->
								</div>
							</div>
							<div class="col-sm-3 search-item">
								<label>End Date: </label>
									<div class="input-group date form_datetime form_datetime bs-datetime">
                                        <input type="text" size="16" class="form-control" name="end_dt" value="<?= $end_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-set" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
								<!-- <div class="form-group">
									<label>End Date: </label>
									<input type="date" name="end_dt" class="form-control" value="<?= $end_dt;?>"/>
								</div> -->
							</div>
							
							<div class="col-sm-2 search-item" >
								<input type="submit" value="Search" class="btn btn-default form-control" name="search">
							</div>
							<div class="col-sm-2 search-item" >
								<?php if(count($reports) > 0){
								    if($print_report=="y")
									{
										$url="print_shift_report.php?type=latest&start_dt=".$shift_start_dt."&end_dt=".date('Y-m-d H:i',strtotime($end_dt))."&user=".$mercant_id;
										
									}
									else
									{
										// $url="print_shift_report.php?type=latest&start_dt=".$shift_start_dt."&end_dt=".date('Y-m-d H:i',strtotime($end_dt))."&user=".$mercant_id;
										
										$url="#";
										// $url="print_shift_report.php?start_dt=".$shift_start_dt."&end_dt=".date('Y-m-d H:i',strtotime($end_dt))."&user=".$mercant_id;
										
									}   
									?>
										<a href="<?php echo $url; ?>" class="btn btn-default form-control <?php if($print_report=="n"){ echo "print_stop";} ?>" >Close Shift</a>

								<?php }?>
								
							</div>
						</div>
					</form>
					
					<?php if(count($reports)>0){ ?>
					<table class="table table-striped display">
						<thead>
						<tr>
							<th>No</th>
							<th>Date</th>
							<th>Category</th>
							<th>Product</th>
							<th>Qty</th>
							<th>Unit Amounts</th>
							<th>Total Amount</th>
							<?php if($sstper>0){ ?>
							 <th><?php echo "SST ".$sstper." %"?></th>
							 <!--th><?php echo "Grand Total (Inc ".$sstper." % SST)";?></th!-->
							 <th><?php echo "Grand Total";?></th>
							<?php } ?>
							
						</tr>
						</thead>
						<tbody>
							<?php 
							$t_qty=0;
							 $t_amount=0;
							 $t_sum=0;
							for($i = 0; $i < count($reports); $i++){
								$t_qty+=$reports[$i]['qty'];
								$t_amount+=$reports[$i]['amounts'];
								$t_sum+=$reports[$i]['amounts']*$reports[$i]['qty'];
								 $product_qty = explode(",", $reports[$i]['qty']);
								$product_amt = explode(",", $reports[$i]['amounts']);
								$total=0;
								$c=0;
							     
								foreach($product_amt as $p)
								{
									 $total+=($p*$product_qty[$c]);
									$c++;
									// echo "</br>";
								}
								// echo $total;
								// die;
								   $finaltotal+=$total;
								?>
								<tr>
									<td><?= $i + 1;?></td>
									<td><?= $reports[$i]['date'];?></td>
									<td><?= $reports[$i]['category'];?></td>
									<td><?= $reports[$i]['name'];?></td>
									<td><?= $reports[$i]['qty'];?></td>
									
									<td><?= $reports[$i]['amounts'];?></td>
									<td><?= $reports[$i]['amounts']*$reports[$i]['qty'];?></td>
									<?php 
									$total=$finaltotal;
									 // $total=($reports[$i]['amounts']);
									if($sstper>0){ ?>   
							<?php $incsst = ($sstper / 100) * $total;
							    // $incsst=$incss;
							    $g_total=$total+$incsst;
							 ?>
							  <td><?php echo number_format($incsst,2); ?></td>
							  <td><?php  echo number_format($g_total,2);?></td>
							<?php } ?>
								</tr>
							<?php }?>
						</tbody>
						<tr style="border-top: 3px solid black;">
						<th colspan="4">Total</th>
						<th><?php echo $t_qty; ?></th>
						<th><?php echo $t_amount; ?></th>
						<th><?php echo $t_sum; ?></th>
						</tr>
					</table>
					<?php } ?>
					 <div style="width:100%">   
				 <?php  if($pastcount>0){?>
					<h4>Past Cash System Record</h4>
					
					
					   <table class="table">
						<thead>
						  <tr>
							<th>id</th>
							<th>Shift Login Time</th>
							<th>Shift Logout Time</th>
							<th>Report</th>
						
						  </tr>
						</thead>
						<tbody>
						<?php  $j=1; while ($p=mysqli_fetch_assoc($pastquery)){
							
							?>
						    <tr>
							<th><?php echo $j; ?></th>
							<th><?php echo $s_d=date("Y-m-d H:i", $p['login_time']); ?></th>
							<th><?php echo $end_d=date("Y-m-d H:i", $p['logout_time']);
							$url="print_shift_report.php?type=past&start_dt=".$s_d."&end_dt=".$end_d."&user=".$mercant_id."&cash_id=".$p['id'];
							?></th>
							<th><a href="<?php echo $url; ?>" class="">Report</a></th>
							
						  </tr>
						<?php $j++; } ?>
						</tbody>
					  </table>
					<?php } ?>    
					</div>
				</div>
			</main>
        </div>
    </div>
    
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<!-- <script src="js/components-date-time-pickers.min.js" type="text/javascript"></script> -->
</body>

</html>
<script>
$(document).ready(function() {
 //$('.display').DataTable();
 $(".form_datetime").datetimepicker({
    autoclose: true,
    format: "yyyy-mm-dd  hh:ii:ss",
    fontAwesome: true
});
$('.print_stop').click(function() {
  alert('You still have pending/accepted transactions, please do it before you are allowed to close shift.');
});

});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
