<?php 
session_start();
include("config.php");

// if(!isset($_SESSION['login']))
// {
	// header("location:login.php");
// }

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
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
 // $referral_id = isset($_SESSION['referral_id']) ? $_SESSION['referral_id'] : '0';
 $referral_id=$profile_data['referral_id'];
   // echo "SELECT * FROM users WHERE referred_by= '".$referral_id."' and id='".$_SESSION['login']."'";
// die;   
if($referral_id)
$u_query =mysqli_query($conn,"SELECT * FROM users WHERE referred_by= '".$referral_id."'");
?> 
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="/css/dropzone.css" type="text/css" /> 
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
					<h3>Member List</h3>  
						<p class="copy-hint" style="font-size: 16px; color: #ff0000; margin-bottom: 0;display:none;">Link has been copied, please share to your friends.</p>
						<h4 style="font-size:1.3em;">Your own referral ID: <?php echo isset($referral_id) ?  $referral_id : '';?> <a class="btn btn-primary" href="signup_referral.php?invitation_id=<?php echo $referral_id;?>">Add Member</a><button class="btn btn-info copy-link" style="margin-left:10px;" onclick="copy_url()">Copy link</button></h4>
					
					<table class="table table-striped kType_table" id="kType_table">
					    <thead>
					        <tr>
					        <th>User ID</th>
							<th>Membership</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Email</th>
							<th>Address</th>
							<th>DOB</th>
							<th>Registration Time</th>
                		
					        </tr>
					    </thead>
					    <tbody>
					        <?php
                    	$i=1;
                    		while($row = mysqli_fetch_assoc($u_query))
							{
							$l_user_id=$row['id'];
							$isLocked=$row['isLocked'];
							
							 ?>
                        	 <tr style="<?php  if($isLocked=="1"){ echo "color:red;";} ?>">
									<td><?php echo $row['id']; ?></td>
									<td id="change_membership" user_id="<?php echo $l_user_id;?>" cur_membership="<?php echo $row['membership']; ?>"><?php echo $row['membership']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['mobile_number']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['address']; ?></td>   
									<td><?php if($row['dob'] && $row['dob']!="0000-00-00 00:00:00"){ echo date("Y-m-d h:i:s",strtotime($row['dob']));} else{ echo "--";} ?></td>
									<td><?php  $date=$row['joined'];
										echo $joinigdate=date("Y-m-d h:i:sa",$date);  ?>
									</td>
									
								</tr>
                    	<?php
                            $i++;  
                    	}?>
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
	






	<script>
	    $(document).ready(function(){
	        jQuery(".dropzone").dropzone({
                sending : function(file, xhr, formData){
                },
                success : function(file, response) {
                    $(".complain_image").val(file.name);
                    
                }
            });
            $('#kType_table').DataTable({
				"bSort": false,
				"pageLength":50,
				dom: 'Bfrtip',
				 buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
				});
				$(document).on('click', '#change_membership', function(e){
					e.preventDefault();
					var cur_membership=$(this).attr('cur_membership');
					var user_id=$(this).attr('user_id');
					var data = {user_id:user_id,cur_membership:cur_membership,method:"changemembership"};
						$.ajax({
							url :'functions.php',
						  type:'POST',
						  dataType : 'json',
						  data:data,
						  success:function(response){
							  var data = JSON.parse(JSON.stringify(response));
							  if(data.status)
							  {
								 alert('User Memership status change');
							  }
							  else
							  {
								  alert('Failed to change Memership status');
							  }
							  location.reload();
							}		  
							});
				});
				$(document).on('click', '.credit_amount', function(e){
			 
				  e.preventDefault();
					 // alert(3);
					 $(this).hide();
					 var rebate=$(this).attr('rebate');
					 var user_id=$(this).attr('user_id');
					 var order_id=$(this).attr('order_id');
					 var wallet=$(this).attr('wallet');
					 var user_mobile=$(this).attr('user_mobile');
					 $('#order_id').val(order_id);
					 $('#w_type_input').val(wallet);
					 $('#credit_amount_input').val(rebate);
					 $('#user_id').val(user_id);
					 $('#credit_amount_label').html(rebate);
					 $('#credit_mobile_label').html(user_mobile);
					 var rebate=$('#credit_amount_input').val();
					 var wallet=$('#w_type_input').val();
					 var order_id=$('#order_id').val();
					 var user_id=$('#user_id').val();
					  // alert(user_id);
					  // alert(order_id);
					 var data = {order_id:order_id,user_id:user_id,method:"walletcredit",rebate:rebate,w_type:wallet};
						$.ajax({
							url :'walletcredit.php',
						  type:'POST',
						  dataType : 'json',
						  data:data,
						  success:function(response){
							  var data = JSON.parse(JSON.stringify(response));
							  if(data.status)
							  {
								 alert('Amount Credited');
							  }
							  else
							  {
								  alert('Failed to make payment From Wallet');
							  }
							  location.reload();
							}		  
							});
				});
				$(document).on('click', '.undo_confirm_amount', function(e){
			 
				  e.preventDefault();
				
					 // alert(3);
					 $(this).hide();
					 var rebate=$(this).attr('rebate');
					 var user_id=$(this).attr('user_id');
					 var order_id=$(this).attr('order_id');
					 var wallet=$(this).attr('wallet');
					 var user_mobile=$(this).attr('user_mobile');
					
					  // alert(user_id);
					  // alert(order_id);
					 var data = {order_id:order_id,user_id:user_id,method:"walletdeduct",rebate:rebate,w_type:wallet};
						$.ajax({
							url :'walletcredit.php',
						  type:'POST',
						  dataType : 'json',
						  data:data,
						  success:function(response){
							  var data = JSON.parse(JSON.stringify(response));
							  if(data.status)
							  {
								 alert('Amount Deducted');
							  }
							  else
							  {
								  alert('Failed to make payment From Wallet');
							  }
							  location.reload();
							}		  
							});
				});
	});
	  
	  
	</script>
		<script type="text/javascript">
		
		
		function copy_url(){
		    var dummy = document.createElement("input");

              document.body.appendChild(dummy);
            
              dummy.setAttribute("id", "dummy_id");
            
            var referral_id = '<?php echo $_SESSION['referral_id']; ?>';
            referral_id = referral_id.replace(/ /g, '%20');
              document.getElementById("dummy_id").value="https://www.koofamilies.com/signup_referral.php?invitation_id="+referral_id;
                
                
              dummy.select();
            
              document.execCommand("copy");
		    $(".copy-hint").css("display", "block");
		}
		
	</script>
	
</body>

</html>
