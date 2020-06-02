<?php 
include("config.php");
$m_login_id=$_SESSION['login'];
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
if(isset($_POST['search'])){
	 $start_coin_dt = $_POST['start_dt'];
	$end_dt = $_POST['end_dt'];
	$coin_type = $_POST['coin_type'];
	$user_id = $_POST['user_id'];
	if($start_coin_dt && $end_dt)
	{
		$start_utc=strtotime($start_coin_dt);
		$end_utc=strtotime($end_dt);
		$filter.=" and created_on>=$start_utc and created_on<=$end_utc";
	}
	if($coin_type!="ALL")
		$filter.=" and wallet='$coin_type'";
	if($user_id!="all")
	{
		$selected_user_id=$user_id;
		$filter.=" and (sender_id=$user_id or receiver_id='$user_id')";
	}
	// echo $filter;
// die;
}

if(isset($_GET['coin_type']))
{
	$coin_type=$_GET['coin_type'];
	if($coin_type!="ALL")
   $filter.=" and wallet='$coin_type'";
} 
$total_rows = mysqli_num_rows(mysqli_query($conn, "(SELECT tranfer.type_method,tranfer.invoice_no,tranfer.id,users.name,users.mobile_number,tranfer.amount,tranfer.wallet,tranfer.created_on,'Send' AS tx_type FROM tranfer,users WHERE tranfer.sender_id=".$_SESSION['login']." AND users.id=tranfer.receiver_id  $filter) UNION (SELECT tranfer.type_method,tranfer.invoice_no,tranfer.id,users.name,users.mobile_number,tranfer.amount,tranfer.wallet,tranfer.created_on,'Receives' AS tx_type FROM tranfer,users WHERE tranfer.receiver_id=".$_SESSION['login']." AND users.id=tranfer.sender_id  $filter)"));
$total_page_num = ceil($total_rows / $limit);
$start = ($page - 1) * $limit;
$end = $page * $limit;
   $query="(SELECT tranfer.type_method,tranfer.invoice_no,tranfer.id,users.name,users.mobile_number,tranfer.amount,tranfer.wallet,tranfer.created_on,'Send' AS tx_type FROM tranfer,users WHERE tranfer.sender_id=".$_SESSION['login']." AND users.id=tranfer.receiver_id  $filter) UNION (SELECT tranfer.type_method,tranfer.invoice_no,tranfer.id,users.name,users.mobile_number,tranfer.amount,tranfer.wallet,tranfer.created_on,'Receives' AS tx_type FROM tranfer,users WHERE tranfer.receiver_id=".$_SESSION['login']." AND users.id=tranfer.sender_id  $filter) ORDER BY id DESC LIMIT $start,$end";
 // die;

$userq = mysqli_query($conn,"select sender_id,receiver_id from tranfer where sender_id='$m_login_id' or receiver_id='$m_login_id'");
$allresult=mysqli_fetch_all($userq);
$ucount=0;
foreach($allresult as $user)
{
	if($user[0])
	{
	$u[$ucount]=$user[0];
	$ucount++;
	}
	if($user[1])
	{
	$u[$ucount]=$user[1];
	$ucount++;
	}
}
$user_array=array_unique($u);
$usr_list=implode(',',$user_array);

$userq = mysqli_query($conn,"select id,mobile_number from users where id in($usr_list)");
$allmobile=mysqli_fetch_all($userq);

$tx_history_data = mysqli_query($conn,$query);


?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
	  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

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
	
	.wallet_h{
	    font-size: 30px;
        color: #213669;

	}
	.kType_table{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table th, .kType_table td{
	    border: 1px #aeaeae solid !important;
	}
	.kType_table thead th{
	    border-bottom: 1px  #aeaeae solid !important;
	} 
	.kType_table tbody .complain{
	    color: red;
	    text-decoration: underline;
	}
	.sort{
	    margin-bottom: 10px;
	}
	/*kType_table tbody tr.k_normal{
	    background: #ececec;
	}*/
	#kType_table tbody tr.k_user{
	    background: #bcbcbc;
	}
	#kType_table tbody tr.k_merchant{
	    background: #dcdcdc;
	}
	.select2-container--bootstrap{
	    width: 175px;
	    display: inline-block !important;
	    margin-bottom: 10px;
	}
	@media  (max-width: 750px) and (min-width: 300px)  {
	    .select2-container--bootstrap{
	        width: 300px;
	    }
	}
	</style>
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
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h3>Transaction Report</h3>  
					<div class="form-group" style="margin-top: 20px;width:100%;">
						  <form id="criteria_form" method="post" action=''>
							<div class="row col-sm-12">
							  <div class="col-sm-3">
								<div class="form-group">
									<label>Starting Date: </label>
									<div class="input-group date form_datetime form_datetime bs-datetime">
                                        <input type="text" size="16" class="form-control" name="start_dt" value="<?= $start_coin_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-set" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
									<!-- <input type="date" name="start_dt" class="form-control form_datetime" value="<?= $start_dt;?>"/> -->
								</div>
							  </div>
							  <div class="col-sm-3">
								<div class="form-group">
									<label>End Date: </label>
									<div class="input-group date form_datetime form_datetime bs-datetime">
                                       <input type="text" size="16" class="form-control" name="end_dt" value="<?= $end_dt;?>">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-set" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
									<!-- <input type="date" name="start_dt" class="form-control form_datetime" value="<?= $start_dt;?>"/> -->
								</div>
							  </div>
							  <div class="col-sm-3">
								<div class="form-group">
								<label>Wallet: </label>
									<select class="form-control select2" name="coin_type">
									  <option selected="" val="all">ALL</option>
									  <option val="MYR">MYR</option>
									  <option val="INR">KOO COIN</option>
									  <option val="CF">CF</option>
									  <option val="LIFE COIN">LIFE COIN</option>   
									</select>
								</div>
							  </div>
							  <?php  if($total_rows>0){?>
								<div class="col-sm-3">
								<div class="form-group">
								<label>Users: </label>   
									<select class="form-control select2" name="user_id">     
									  <option selected="" value="all">ALL USER</option>
									   <?php foreach($allmobile as $user){ ?>
										 <option  <?php if($selected_user_id==$user[0]){ echo "selected";}?> value="<?php  echo $user[0];?>"><?php  echo $user[1];?></option>
										<?php } ?> 
									</select>
								</div>
							  </div>
							  <?php } ?>
							</div>
							<div class="row" style="margin-left:2%;">
								<div class="col-sm-1" style="max-width:150px;">
								<input type="submit" value="Aply criteria" name="search"  class="btn btn-secondary"/>
									
							  </div>
								<div class="col-sm-1">
									<button type="button" class="btn btn-danger" onclick="window.location.href='./transaction_history.php'">Clear criteria</button>
							  </div>
							</div>
						  </form>
					</div>
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
								<th>Transaction ID</th>
								<th>Invoice No</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Amount</th>
								<th>Wallet</th>
								<th>Transaction Type</th>
								<th>Transaction On</th>
							</tr>
					    </thead>
					    <tbody>
					        <?php
					
							while($tx_history_row = mysqli_fetch_assoc($tx_history_data))
							
							{
								   $invo=$tx_history_row['invoice_no'];
								    $type_method=$tx_history_row['type_method'];
								  // $invo=explode(',',$invo);
								  // print_R($invo);
								// if($invoice_id)
								// {
									// $invo = mysqli_fetch_all(mysqli_query($conn, "SELECT invoice_no FROM order_list WHERE id in($invoice_id)"));
								// }
								?>
								<tr>
									<?php 
									if($tx_history_row['wallet'] == "INR")
							{
								$wat = "KOO COIN";
							}
							else
							{
								$wat = $tx_history_row['wallet']; 
							}
							
							?>
									<td><?php echo $tx_history_row['id']; ?></td>
									<td><?php if($tx_history_row['invoice_no']){ echo $invo;} ?></td>
									<td><?php if($type_method=="topup"){ echo "SELF TOPUP";} else {echo $tx_history_row['name'];} ?></td>
									
									<td><?php echo $tx_history_row['mobile_number']; ?></td>
									<td><?php echo number_format($tx_history_row['amount'],2); ?></td>
									<td><?php echo $wat; ?></td>
									<td><?php echo $tx_history_row['tx_type']; ?></td>
									<td><?php echo date("d-m-Y, h:i A", $tx_history_row['created_on']); ?></td>
								</tr>
								<?php
								// die;
							}
							?>
					    </tbody>
					</table>
				</div>
			</main>
        </div>
      
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	<script src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>






	<script>
	    $(document).ready(function(){
	        jQuery(".dropzone").dropzone({
                sending : function(file, xhr, formData){
                },
                success : function(file, response) {
                    $(".complain_image").val(file.name);
                    
                }
            });
			 $(".form_datetime").datetimepicker({
			autoclose: true,
			format: "yyyy-mm-dd  hh:ii:ss",
			fontAwesome: true
		});
            $('#kType_table').DataTable({
				"bSort": false,
				"pageLength":50,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				
	});
	  
	  
	</script>
		
</body>

</html>
