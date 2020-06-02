<?php 
include("config.php");
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
$query=mysqli_query($conn,"select *  from cash_system where user_id='$loginidset' and is_active='y'");
$pastquery=mysqli_query($conn,"select *  from cash_system where user_id='$loginidset' and is_active='n' order by id desc");
$totalcount=mysqli_num_rows($query);
 $pastcount=mysqli_num_rows($pastquery);


?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<style>
	.container {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
table, th, td,tr {
  border: 2px solid gray;
}
.add 
{
	background-color: #50d2b7;
	width: 25px;
	height: 25px;
	font-size: 12px;
	border-radius: 100%;
	text-align: center;
	line-height: normal;
	padding: 4px 0 0 0;
	margin: 0;
	display: inline-block;
	vertical-align: top;
}

button {
  background-color:#51D2B7; /* Green */
  border: none;
  color: white;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
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
				 <?php if($totalcount==0){ ?>
				     <div style="width:100%;">
					 	<!--h2>
							Please <span class="start_cash" style="color:#51D2B7">click here</span> to Start Cash System
							</h2!-->
					 </div>
					 
				 <?php } if($totalcount>0){
					 $totaldata=mysqli_fetch_assoc($query);
					 $start_utc=$totaldata['login_time'];
					 $cash_id=$totaldata['id'];
					 $date_time=date("Y-m-d H:i", $start_utc);
					 $show_time=date("H:i A d-m-Y", $start_utc);
					    $saleq="select id,quantity,amount from order_list where status in(0,1,2) and created_on >= '$date_time' and merchant_id='$loginidset'";
					
					  $total_sale = mysqli_query($conn,$saleq);
					  // print_R($total_sale);
					  $finaltotal=0;
					  while ($row=mysqli_fetch_assoc($total_sale)){
						   $product_qty = explode(",", $row['quantity']);
							$product_amt = explode(",", $row['amount']);
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
							
					  }
					  // echo $finaltotal;
					   // $finaltotal=number_format($finaltotal,2);
					
					 ?>
				 <h4 style="margin-left:32%;">Cash System</h4>
				
				 <p>Opening From <?php echo  $show_time;  ?></p>
				   <div class="container">
							<input type='hidden' id='opening_bal' value='<?php echo $totaldata['opening']; ?>'/>
							<input type='hidden' id='sales' value='<?php echo number_format($finaltotal,2); ?>'/>
							<input type='hidden' id='cash_in' value='<?php echo $totaldata['cash_in']; ?>'/>
							<input type='hidden' id='cash_out' value='<?php echo $totaldata['cash_out']; ?>'/>
							<input type='hidden' id='void_tras' value='<?php echo $totaldata['void_tras']; ?>'/>
							
							<table class="table" style="max-width:450px;">
							  <tbody>
							    <tr>
								  <td>Opening  Balance</td>
								  <td><?php echo  number_format($totaldata['opening'],2);?></td>
								</tr>
								<tr>
								  <td>Sales</td>
								  <td><?php echo $finaltotal; ?></td>
								  <td><a href="orderview.php">Detail</a></td>
								</tr>
								<tr>
								  <td>Cash In <i type="cash_in"  class="add fa fa-plus"></i></td>
								  <td><?php echo  number_format($totaldata['cash_in'],2);?></td>
								  <td class="tras_detail" type="cash_in">Detail</td>
								</tr>
								<tr>
								  <td>Cash Out <i  type="cash_out" class="add fa fa-plus"></i></td>
								  <td><?php echo  number_format($totaldata['cash_out'],2);?></td>
								  <td class="tras_detail" type="cash_out">Detail</td>
								</tr>
								<tr>
								  <td>Void Trascation <i type="void_tras"  class="add fa fa-plus"></i></td>
								  <td><?php echo  number_format($totaldata['void_tras'],2);?></td>
								  <td class="tras_detail" type="void_tras">Detail</td>
								</tr>
								<tr>
								  <td>Balance </td>
								  <td><?php $balance=$totaldata['opening']+$finaltotal+$totaldata['cash_in']-($totaldata['cash_out']+$totaldata['void_tras']);
									echo number_format($balance, 2);
									$cash_id=$totaldata['id'];
									 $query="UPDATE cash_system SET balance='$balance',sales='$finaltotal' WHERE `cash_system`.`id`='$cash_id'";
							$update=mysqli_query($conn,$query);
								  ?>
								  <input type='hidden' id='balance' value='<?php echo $balance; ?>'/>
								  </td>
								  <td></td>
								</tr>
							  </tbody>
							  
							  
							</table>
							<!--button onclick="ShiftClose()">Shift Close</button!-->
							<button><a href="orderview.php" style="color:white;">Return</a></button>
					</div>
					   <div style="width:100%">   
				 <?php  } if($pastcount>0){?>
					<h4>Past Cash System Record</h4>
					<div class="col-sm-2" style="margin-top:2%;">
					<a href="print_cash_report.php"  class="btn btn-primary form-control" >Report</a>
					
					</div>
					<?php  if($pastcount>1){?>
					<div class="col-sm-2" style="margin-top:2%;">
					
					<a href="print_cash_report.php?type=past"  class="btn btn-primary form-control" >All Report</a>
					</div>  
					<?php } ?>
					   <table class="table">
						<thead>
						  <tr>
							<th>Opening Balance</th>
							<th>Sales</th>
							<th>Cash in</th>
							<th>Cash out</th>
							<th>Void Trascation</th>
							<th>Balance</th>
							<th>Start time</th>
							<th>End time</th>
						  </tr>
						</thead>
						<tbody>
						<?php   while ($p=mysqli_fetch_assoc($pastquery)){?>
						    <tr>
							<th><?php echo number_format($p['opening'],2); ?></th>
							<th><?php echo number_format($p['sales'],2); ?></th>
							<th><?php echo number_format($p['cash_in'],2); ?></th>
							<th><?php echo number_format($p['cash_out'],2); ?></th>
							<th><?php echo number_format($p['void_tras'],2); ?></th>
							
							<th><?php
							$balancepast=$p['opening']+$p['sales']+$p['cash_in']-($p['cash_out']+$p['void_tras']);
							echo number_format($balancepast,2); ?></th>
							<th><?php echo $start_dt=date("Y-m-d H:i:s", $p['login_time']); ?></th>
							<th><?php echo date("Y-m-d H:i:s", $p['logout_time']); ?></th>
							
						  </tr>
						<?php } ?>
						</tbody>
					  </table>
					<?php } ?>
					</div>
				</div>
			</main>
        </div>
    </div>
	
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
	 <div id="CashDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" style="max-width:1000px !important;">
									
									<form method="post">
									
									 
                                        <div class="modal-content catelog_plan_body">
										<div class="modal-header">
												<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
												  
											</div>
                                           <?php
										   // echo "SELECT * FROM cash_flow WHERE cash_id='".$cash_id."' order by tras_utc desc";
										  
												$cquery = mysqli_query($conn, "SELECT * FROM cash_flow WHERE cash_id='".$cash_id."' order by tras_utc desc");

											$totalc=mysqli_num_rows($cquery);
										   ?>
										      <div class="modal-body">
                                                <div class="row">
												
                        <div class="col-12 table-responsive catelog_body">   
						            <label>Trascation Detail</label>
									<?php if($totalc>0){ ?>
                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                         
                                                <tr>
							<th>Amount</th>
							<th>Cash Type</th>
							
							<th>Paid From</th>
							<th>Invoice No</th>
							<th>Cash Description</th>
							<th>Remark</th>
							<th>Trsacation Time</th>
							
						  </tr>
                                                
                                                
                                              
                                           
                                        </thead>
                                       
                                        <tbody> 
										  <?php   while ($cp=mysqli_fetch_assoc($cquery)){?>
						   <tr>
						   <td><?php echo $cp['amount']; ?></td>
						   <td><?php echo $cp['cash_type']; ?></td>
						  
						   <td><?php echo $cp['paid_from']; ?></td>
						    <td><?php echo $cp['invoice_no']; ?></td>
						   <td><?php echo $cp['cash_description']; ?></td>
						   <td><?php echo $cp['remark']; ?></td>
						  <th><?php echo date("Y-m-d H:i:s", $cp['tras_utc']); ?></th>
							
						   </tr>
						<?php } ?>
                                        </tbody>
                                    </table>
									<?php } else { echo "No Category";} ?>
                                </div>

                       
                    
							</div>
							 
														</div>
													
                                        </div>
									</form>
                                    </div>
          </div>
	<div id="CashDetail2" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
									   
											<div class="modal-header">
												<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
												  
											</div>
											 <div class="modal-body" id="report_detail" style="padding-bottom:0px;">
											  <?php 

$cquery = mysqli_query($conn, "SELECT * FROM cash_flow WHERE cash_id='".$cash_id."'");

 $totalc=mysqli_num_rows($query);
?>
<?php if($totalc>0){ ?>
  <table class="table">
		<thead>
						  <tr>
							<th>Amount</th>
							<th>Cash Type</th>
							<th>Invoice No</th>
							<th>Paid From</th>
							<th>Cash Description</th>
							<th>Remark</th>
							
						  </tr>
						</thead>
						<?php  ?>
						<tbody>
						<?php   while ($cp=mysqli_fetch_assoc($cquery)){?>
						   <tr>
						   <td><?php echo $cp['amount']; ?></td>
						   <td><?php echo $cp['cash_type']; ?></td>
						   <td><?php echo $cp['invoice_no']; ?></td>
						   <td><?php echo $cp['paid_from']; ?></td>
						   <td><?php echo $cp['cash_description']; ?></td>
						   <td><?php echo $cp['remark']; ?></td>
						  
						   </tr>
						<?php } ?>
						</tbody>
	</table>
<?php  } else { echo "No data";}?>
	
												
											 </div>
										
										
									
					    		</div>

				  			</div>
	</div>
    
	<div id="AddModel" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
									   
											<div class="modal-header">
												<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
												  <p id="page_title"> Set Opening Balance to </p>
											</div>
											 <div class="modal-body" style="padding-bottom:0px;">
											   <form action="cashadd.php" method="post">
												<div class="form-group">
												  
												  <input type="hidden" class="form-control"  name="cash_id" value="<?php echo $cash_id; ?>">  
												  <input type="hidden" class="form-control"  name="cash_type" id="cash_type">  
												  <input type="hidden" class="form-control"  name="sales" value='<?php echo  $finaltotal;?>'>  
												  <input type="text" class="form-control"  name="amount"  placeholder="Amount" required>  
												</div>
												<div class="form-group" id="invoice_area" style="display:none;">
												  
												  <input type="text" class="form-control"  name="invoice_id" placeholder="Invoice Id">  
												</div>
												<div class="form-group" id="paid_area" style="display:none;">
												  
												  <input type="text" class="form-control"  name="paid_from" placeholder="Paid From">  
												</div>
												<div class="form-group">
												  <label>Description</label>
												 <textarea rows="4" cols="40" name="cash_description"></textarea> 
												</div>
												<div class="form-group">
												   <label>Remark</label>
												  <input type="text" class="form-control"  name="remark"  placeholder="Remark">  
												</div>
												<button type="submit" name="Add" class="btn">Add</button>
											  </form>
																							
												
											 </div>
										
										
									
					    		</div>

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
	$('.start_cash').click(function() {
		$('#ShiftModel').modal('show');
	});
	$('.tras_detail').click(function() {
		var type=$(this).attr('type');
		var cash_id='<?php echo $cash_id; ?>';
		var data = {cash_id:cash_id};
		$('#CashDetail').modal('show');
		
	});
	$('.add').click(function() {
		var type=$(this).attr('type');
		$('#cash_type').val(type);
		if(type=="cash_in")
		{
			title="Add Cash In";
			$('#paid_area').show();
			$('#invoice_area').hide();
		}
		if(type=="cash_out")
		{
			title="Add Cash Out";
			$('#paid_area').show();
			$('#invoice_area').hide();
		}
		if(type=="void_tras")
		{
			title="Add Void Trascation";
			$('#invoice_area').show();
			$('#paid_area').hide();
		}
		$('#page_title').html(title);
		$('#AddModel').modal('show');
	});
	$('.start_show').click(function() {
		var opening_balance=$('#opening_balance').val();
		var user_id=$('#merchant_id').val();
		// alert(user_id);
		if(opening_balance)
		{
			var data = {user_id:user_id,opening_balance:opening_balance,method:"startcash"};
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
});
function ShiftClose() {
  var txt;
  var r = confirm("Are You sure want to close Shift!");
  if (r == true) {
	  var user_id=$('#merchant_id').val();
	  var opening=$('#opening').val();
	  var sales=$('#sales').val();
	  // alert(sales);
	  var cash_in=$('#cash_in').val();
	  var cash_out=$('#cash_out').val();
	  var void_tras=$('#void_tras').val();
	  var balance=$('#balance').val();
      var cash_id='<?php echo $cash_id; ?>';
	  var data = {user_id:user_id,cash_id:cash_id,method:"shiftclose",opening:opening,sales:sales,cash_in:cash_in,cash_out:cash_out,void_tras:void_tras,balance:balance};
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
						  alert('Failed to Close Shift system');
					  }
					 
					}		  
			  });
  } else {
    
  }
 
}
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
