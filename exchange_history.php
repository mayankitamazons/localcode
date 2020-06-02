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

$limit = 25;

$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM ex_requests WHERE user_id='".$_SESSION['login']."'"));
$total_page_num = ceil($total_rows / $limit);

$start = ($page - 1) * $limit;
$end = $page * $limit;

$exchange_history_data = mysqli_query($conn, "SELECT * FROM ex_requests WHERE user_id='".$_SESSION['login']."' ORDER BY id DESC LIMIT $start,$end")
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
					<div class="well" style="width:100%">
						<h3>Exchange History</h3>
						<table class="table table-striped">
							<tr>
								<th>Request ID</th>
								<th>Amount (From)</th>
								<th>Amount (To)</th>
								<th>User Note</th>
								<th>Request On</th>
								<th>Status</th>
								<th>Admin Note</th>
							</tr>
							<?php
							while($exchange_history_row = mysqli_fetch_assoc($exchange_history_data))
							{
								?>
								<tr>
									<?php 
									if($exchange_history_row['wallet_to'] == "INR")
							{
								$wato = "CNY";
							}
							else
							{
								$wato = $exchange_history_row['wallet_to'];
							}
							
							?>
							<?php 
									if($exchange_history_row['wallet_from'] == "INR")
							{
								$watf = "CNY";
							}
							else
							{
								$watf = $exchange_history_row['wallet_from'];;
							}
							
							?>
									<td><?php echo $exchange_history_row['id']; ?></td>
									<td><?php echo $exchange_history_row['amount_from'] . " " . $watf; ?></td>
									<td><?php echo $exchange_history_row['amount_to'] . " " . $wato; ?></td>
									<td><?php echo $exchange_history_row['user_note']; ?></td>
									<td><?php echo date("d-m-Y, H:i A", $exchange_history_row['created_on']); ?></td>
									<td><?php echo $exchange_history_row['status'] . " (" . date("d-m-Y, H:i A", $exchange_history_row['status_date']) . ")"; ?></td>
									<td><?php echo $exchange_history_row['status_note']; ?></td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
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
</body>

</html>
