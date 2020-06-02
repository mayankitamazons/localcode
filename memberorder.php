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

$limit = 50;
$date = date('Y-m-d H:i:s');
$end_dt = $date;
$filter="";
$member_user_id=$_GET['user_id'];
$promo_id=$_GET['promo_id'];
$m_id=$_SESSION['login'];
$merchant_id=$_SESSION['login'];
$user_id=$_GET['user_id'];
// $userdata = mysqli_fetch_assoc(mysqli_query($conn,"select * from users where id='$user_id'"));
$mobile_number=$member_user_id;
   $defalut_plan="select count(plan.id) as total_count,u.created,u.id from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$m_id' and plan.default_plan='y'
	and u.user_mobile='$user_id'";

$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));

$defalutplan=$defalutarray['total_count'];
$created_date=$defalutarray['created'];
   $query="select order_list.* from order_list where order_list.user_mobile='$member_user_id' and order_list.membership_plan_id != '0' and order_list.merchant_id='$merchant_id' and created_on>='$created_date'";

$m_data=mysqli_query($conn,$query);

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
					<?php
					    
						
						
						$plan_name=mysqli_fetch_assoc(mysqli_query($conn, "select  plan_name from membership_plan where id='$promo_id'"))['plan_name'];
						$local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_place='local'"))['total_amount'];
						$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_place='live'"))['total_amount'];

						// $local_coin_after=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_date>='$created_date'"))['local_coin'];
						$local_coin_after=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date' and order_place='local'"))['total_amount'];
						$total_amount_after=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date' and order_place='live'"))['total_amount'];

					?>
					<div class="well" style="width:100%">
					<h3>Trial Purchase:  <?php echo date('F d, Y h:i:A', strtotime($created_date)); ?></h3>
					<div class="row">
					
					  <div class="col-md-6">
					  Total Amount: <?php echo number_format($total_amount,2); ?></br>
						Local Order Point : <?php echo number_format($local_coin,2); ?>  </br>
						Final Amount: <?php echo number_format($local_coin+$total_amount,2); ?> </br> </div>
					  <div class="col-md-6">
					    Total Amount After Trial: <?php echo number_format($total_amount_after,2); ?></br>
						Local Order Point After Trial: <?php echo number_format($local_coin_after,2); ?></br>
						Final Amount After Trial: <?php echo number_format($local_coin_after+$total_amount_after,2); ?></br>   
					  </div>
					</div>
						<h3> Member Order List in <?php echo $plan_name; ?> (after Buying Trial Plan)</h3>
								
						<table class="table table-striped">
							<tr>
								<th>User ID</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Order Invoice No</th>
								<th>Total Order Amount</th>
								<th>Total Discount</th>
								
								
							</tr>
							<?php
					
							while($row = mysqli_fetch_assoc($m_data))
							{
								$l_user_id=$row['user_id'];
								$user_mobile=$row['user_mobile'];
								
								?>
								<tr>
									<td><?php echo $row['user_id']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['user_mobile']; ?></td>
									<td><?php echo $row['invoice_no']; ?></td>
									<td><?php echo $row['total_cart_amount']; ?></td>
									<td><?php echo $row['membership_discount']; ?></td>
									
									
								</tr>
								<?php
								// die;
							}
							?>
						</table>
					
					

					</div>
					  <div class="row" id="main-content" style="padding-top:25px">
					<?php 
					   
                           $l_q="select * from local_coin_sync where user_mobile='$mobile_number' and merchant_id='$m_id' and order_date>='$created_date' order by id desc";  	
					   $l_query=mysqli_query($conn,$l_q);
					   if(mysqli_num_rows($l_query)>0)
					   {
					?>
					<div class="well" style="width:100%">
						<h3> Local Order List</h3>
								
						<table class="table table-striped">
							<tr>
								<th>User ID</th>
								
								<th>Mobile</th>
								<th>Local Invoice Id</th>
								<th>Total Order Amount</th>
								<th>Order Date</th>
								
								
							</tr>
							<?php
					
							while($row = mysqli_fetch_assoc($l_query))
							{
								
								?>
								<tr>
									<td><?php echo $row['user_id']; ?></td>
									<td><?php echo $row['user_mobile']; ?></td>
									<td><?php echo $row['local_invoice_id']; ?></td>
									<td><?php echo $row['local_coin']; ?></td>
									<td><?php echo date('d-m-Y h:i:A',strtotime($row['order_date'])); ?></td>
									
									
								</tr>
								<?php
								// die;
							}
							?>
						</table>
					
					

					</div>
					   <?php } ?>
					<div style="margin:0px auto;">
						<ul class="pagination">
						<?php
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
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
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
});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
