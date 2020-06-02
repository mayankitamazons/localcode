<?php 
include("config.php");

$date = date('Y-m-d H:i:s');
$start_dt = $date;
$end_dt = $date;
if(!isset($_SESSION['login']) || empty($_SESSION['login'])){
	header("location:logout.php");
}
$merchant = $_SESSION['login'];
$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$_SESSION['login']."'"));
$mercant_id=$_SESSION['login'];
$last_login=$merchant_detail['last_login'];
if($last_login)
$start_dt=date("Y-m-d H:i:s", $last_login);
else
$start_dt = $date;	


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
if(isset($_POST['search'])){
	// print_R($_POST);
	
	$start_dt = $_POST['start_dt'];
	$end_dt = $_POST['end_dt'];
if(isset($_POST['q']) && isset($_POST['cr'])){
    $get_query = $_POST['q'];
     $field_raw = $_POST['cr'];
    $fields_list = ['product_name'      =>   'products.product_name',
                    'product_code'       =>   'products.product_type',
                    'productsell'    =>   'inventory_stock.comment',
                    'stock_type'    =>   'inventory_stock.stock_type',
                    'child_product_name'         =>   'child.product_name'
                    ];
     $field = $fields_list[$field_raw];
	
	  $sql="SELECT inventory_stock.*,products.product_name,child.product_name as child_name FROM inventory_stock inner join products on products.id = inventory_stock.product_id left join products as child on child.id=inventory_stock.child_id  
WHERE inventory_stock.created >= '$start_dt' AND inventory_stock.created <= '$end_dt' AND products.user_id = '$merchant' AND $field LIKE '%$get_query%' 
order by inventory_stock.id desc LIMIT $offset, $rec_limit";


}else{
	  $sql="SELECT inventory_stock.*,products.product_name,child.product_name as child_name FROM inventory_stock inner join products on products.id = inventory_stock.product_id left join products as child on child.id=inventory_stock.child_id  
WHERE inventory_stock.created >= '$start_dt' AND inventory_stock.created <= '$end_dt' AND products.user_id = '$merchant' 
order by inventory_stock.id desc LIMIT $offset, $rec_limit";
}
}
else
{
  $sql="SELECT inventory_stock.*,products.product_name,child.product_name as child_name FROM inventory_stock inner join products on products.id = inventory_stock.product_id left join products as child on child.id=inventory_stock.child_id  
WHERE inventory_stock.created >= '$start_dt' AND inventory_stock.created <= '$end_dt' AND products.user_id = '$merchant' 
order by inventory_stock.id desc LIMIT $offset, $rec_limit";
}
$result = mysqli_query($conn, $sql);
$reports=mysqli_num_rows($result);
$rec_limit = 25;
$rec_count = $reports;
$left_rec = $rec_count - ($page * $rec_limit);
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
									<label> Starting Date: </label>
									<div class="input-group">
                                        <input  type="text" size="16" class="form-control" name="start_dt" value="<?= $start_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
									<!-- <input type="date" name="start_dt" class="form-control form_datetime" value="<?= $start_dt;?>"/> -->
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
							
							<!--div class="col-sm-2 search-item" >
								<input type="submit" value="Search" class="btn btn-default form-control" name="search">
							</div!-->
							<!--div class="col-sm-2 search-item" >
								<?php if($reports > 0){
								    
									?>
									<a href="stockreport.php" class="btn btn-default form-control" >Report</a>
								<?php }?>
								
							</div!-->
						</div>
						<div class="form-group" style="margin-top:5px;">
						
							<div class="row">
							  <div class="col-md-3">
								<select class="form-control" name="cr" id="criteria_field">
								  <option  val="-1">Select criteria</option>
								
								  <option <?php echo ($_POST['cr'] == "product_name") ? "selected" : ""; ?> value="product_name">Product Name</option>
								 
								  <option <?php echo ($_POST['cr'] == "product_code") ? "selected" : ""; ?> value="product_code">Product Code</option>
								  <option <?php echo ($_POST['cr'] == "stock_type") ? "selected" : ""; ?> value="stock_type">Stock Type</option>
								  <option <?php echo ($_POST['cr'] == "productsell") ? "selected" : ""; ?> value="productsell">Comment</option>
								   <option <?php echo ($_POST['cr'] == "child_product_name") ? "selected" : ""; ?> value="child_product_name">Child Product Name</option>
								</select>
							  </div>
							  <div class="col-md-3">
								<input type="text" class="form-control" name="q" id="criteria_query" placeholder="Enter your criteria ...">
							  </div>   
							</div>
							<div class="row">
								<div class="col-sm-1" style="margin-top:20px;max-width:150px;">
									
									<input type="submit" value="Search" class="btn btn-default form-control" name="search">
							  </div>
								<div class="col-sm-1" style="margin-top:20px">
									<button type="button" class="btn btn-danger" onclick="window.location.href='./stockreport.php'">Clear criteria</button>
							  </div>
							</div>
						
					</div>
					</form>
					
					 <?php
                    $dt = new DateTime();
                    $today =  $dt->format('Y-m-d');
                    ?>
					<?php if($rec_count>25){ ?>    
					<p style="">
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
					<table class="table table-striped display">
						<thead>
						<tr>
							<th>No</th>
							<th>Date</th>
						
							<th>Main Product</th>
							<th>Child Product</th>
							<th>Qty</th>
							<th>Stock Type</th>
							<th>Comment</th>
							
							
						</tr>
						</thead>
						<tbody>
							<?php $i=1; while ($r=mysqli_fetch_assoc($result)){  ?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?= $r['created'];?></td>
								
									<td><?= $r['product_name'];?></td>
									<td><?= $r['child_name'];?></td>
									
									<td><?= $r['stock_count'];?></td>
									<td><?= $r['stock_type'];?></td>
									<td><?= $r['comment'];?></td>
								
								</tr>
							<?php $i++; }?>
						</tbody>
					</table>
					
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


});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
