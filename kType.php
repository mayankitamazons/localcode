<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$user_id = $_SESSION['login'];
$user_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'"));
$user_role = $user_info['user_roles'];
$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));

if(isset($_SESSION['year'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
} else {
    $year = date("Y");
    $month = date("m");
}

$start_dt = $year."-".$month."-01"." 00-00-00";
$end_dt = $year."-".$month."-31"." 23-59-59";

if($user_role == '2'){
    $k_history = mysqli_query($conn, "SELECT k1k2_history.*, users.name, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.user_id inner join order_list on order_list.id = k1k2_history.order_id WHERE k1k2_history.merchant_id='".$user_id."' AND order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' AND order_list.status='1' ORDER BY order_list.created_on desc ");
} 
if($user_role == '1'){
    $k_history = mysqli_query($conn, "SELECT k1k2_history.*, users.name, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.merchant_id inner join order_list on order_list.id = k1k2_history.order_id WHERE k1k2_history.user_id='".$user_id."' AND order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' AND order_list.status='1' ORDER BY order_list.created_on desc");
}
if(isset($_POST['filter']) && $_POST['filter']){
    $_SESSION['year'] = $_POST['year'];
    $_SESSION['month'] = $_POST['month'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $start_dt = $year."-".$month."-01"." 00-00-00";
    $end_dt = $year."-".$month."-31"." 23-59-59";
    
    if($user_role == '2'){
        $k_history = mysqli_query($conn, "SELECT k1k2_history.*, users.name, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.user_id inner join order_list on order_list.id = k1k2_history.order_id WHERE k1k2_history.merchant_id='".$user_id."' AND order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' AND order_list.status='1' ORDER BY order_list.created_on desc");
    } 
    if($user_role == '1'){
        $k_history = mysqli_query($conn, "SELECT k1k2_history.*, users.name, order_list.created_on, order_list.quantity, order_list.amount FROM k1k2_history inner join users on users.id = k1k2_history.merchant_id inner join order_list on order_list.id = k1k2_history.order_id WHERE k1k2_history.user_id='".$user_id."' AND order_list.created_on > '".$start_dt."' AND order_list.created_on < '".$end_dt."' AND order_list.status='1' ORDER BY order_list.created_on desc");
    }
}


?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
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
	    border: 1px #aeaeae solid;
	}
	.kType_table th, .kType_table td{
	    border: 1px #aeaeae solid;
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
                    <?php if($user_role== "2"){?>
					    <h2 class="text-center wallet_h">Merchant Name: <?php echo $user_info['name'];?></h2>
					<?php } else if($user_role == "1"){?>
					    <h2 class="text-center wallet_h">Member Name: <?php echo $user_info['name'];?></h2>
					<?php }?>
					<form class="sort row" method="post" action="">
					    <input type="hidden" name="filter" value="filter">
					    <div class="col-sm-3"></div>
					    <div class="col-sm-6">
					        <label class="control-label col-sm-2">Year: </label>
					        <select class="form-control col-sm-3" name="year" style="display: inline-block;">
				                <?php for($i = 2018; $i < 2023; $i++){?>
				                    <option <?php if($year == $i) echo "selected";?>><?php echo $i;?></option>
				                <?php }?>
				            </select>
				            <label class="control-label col-sm-2">Month: </label>
				            <select class="form-control col-sm-2" name="month" style="display: inline-block;">
				                <?php for($i = 1; $i < 13; $i++){
				                    $mt = sprintf("%02d", $i);?>
				                    <option <?php if($month == $mt) echo "selected";?> value="<?php echo $mt;?>"><?php echo $i;?></option>
				                <?php }?>
				                
				            </select>
				            <button class="btn btn-default col-sm-2" type="submit">Filter</button>
					    </div>
				        
					        
					</form>
					<table class="table table-striped kType_table">
					    <thead>
					        <tr>
					            <th>Date</th>
					            <th>Username</th>
					            <th>Member Type</th>
					            <th>Merchant Type</th>
					            <th>Total(RM)</th>
					            <th>Discount(RM)</th>
					            <th>Discount Paid</th>
					            <th>Complain</th>
					        </tr>
					    </thead>
					    <tbody>
					        <?php $sum = 0; ?>
					        <?php while ($row=mysqli_fetch_assoc($k_history)){?>
					        <?php echo $row['mark'];?>
					            <tr>
					                <td><?php echo substr($row['created_on'], 0, 10)?></td>
					                <td><?php echo $row['name'];?></td>
					                <td><?php echo $row['k_user'];?></td>
					                <td><?php echo $row['k_merchant'];?></td>
					                <?php 
					                    $amount_array = explode(",", $row['amount']);
					                    $qty_array = explode(",", $row['quantity']);
					                    $total = 0;
					                    for($i = 0; $i < count($amount_array); $i++){
					                        $total += $amount_array[$i] * $qty_array[$i];
					                    }
					                    $discount_rate = substr($row['discount'], 0, 1);
					                ?>
					                <td><?php echo $total;?></td>
					                <td><?php echo ($total * $discount_rate / 100); $sum += $total * $discount_rate / 100; ?></td>
					                <td>
					                    <?php if($row['mark'] == 0){?>
					                        <?php if($user_role == 2){?>
					                            <a href="#" class="discount_mark" id="<?php echo $row['id'];?>" status="<?php echo $row['mark'];?>">Not Paid</a>
					                        <?php } else {?>
					                            Not Paid
					                        <?php }?>
					                    <?php } else {?>
					                        <?php if($user_role == 2){?>
					                            <a href="#" class="discount_mark" id="<?php echo $row['id'];?>" status="<?php echo $row['mark'];?>">Paid</a>
					                        <?php } else {?>
					                            Paid
					                        <?php }?>
					                    <?php }?>
					                </td>
					                <td><p class="complain" data-target="complainModal" data-id="<?php echo $row['id'];?>" style="cursor:pointer;">Complain</p></td>
					            </tr>
					        <?php }?>
					    </tbody>
					</table>
					<div class="modal fade" id="complainModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Edit Amount</h4>
                                </div>
                                <form id ="data">
                                    <input type="hidden" name="id" class="k_id" value="" >
                                    <input type="hidden" name="role" class="role" value="<?php echo $user_role;?>">
                                    <div class="modal-body" style="padding-bottom:0px;">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Comment</label>
                                        		<input type="text" name="complain" id="complain" class="form-control comment" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="complain_image" class="complain_image" value="">
                                                <div action="/file-upload.php" class="dropzone" data-id="<?php echo $row['id'];?>" enctype="multipart/form-data" method="get">
            										<div class="fallback">
            									    	<input name="file" type="file" multiple />
            									    </div>
            									</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="padding-bottom:2px;">
                            			<button class="btn btn-default complain_btn" type="button">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
					<div class="total_discount" style="float:right;">
					    <h5>Total: <?php echo $sum;?> (RM)</h5>
					</div>
				</div>
			</main>
        </div>
        
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
	<script type="text/javascript" src="/js/dropzone.js"></script>
	<script>
	    $(".complain").click(function(e){
	        var id = $(this).attr('data-id');
	        $("#complainModal").modal();
	        $(".k_id").val(id);
	    });
	    
	    jQuery(".dropzone").dropzone({
            sending : function(file, xhr, formData){
            },
            success : function(file, response) {
                $(".complain_image").val(file.name);
                
            }
        });
        $(".complain_btn").click(function(){
            var id = $(".k_id").val();
            var data = {"method": "k_type", "id": id, role: $(".role").val(), "complain": $(".comment").val(), "image": $(".complain_image").val()};
            
            $.ajax({
                  url:"/functions.php",
                  type:"post",
                  data:data,    
                  success:function(data){
                      $("#complainModal").modal('hide');
                      window.location.href="<?php echo $site_url;?>/kType.php";
                        //$(".home-listing .grid-group-style").html(data);
                  }
            });
        });
        $(".discount_mark").click(function(){
            var status = $(this).attr("status");
            var id = $(this).attr("id");
            var data = {"method":"changeDiscountStatus", "status": status, "id": id};
            $.ajax({
              url:"/admin_panel/functions.php",
              type:"post",
              data:data,    
              success:function(data){
                window.location.href="/kType.php";
              }
            });
        });
	</script>
</body>

</html>
