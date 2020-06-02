<?php 
include("config.php");

// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE

$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
$stock_inventory=$profile_data['stock_inventory'];
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}

if($stock_inventory=="on")
{
   $stockq="select * from order_list where merchant_id='".$loginidset."' and order_place='live' and stock_check='n'";

	$stockquery=mysqli_query($conn,$stockq);  
   $i=1;   $ps=0;
   while ($r=mysqli_fetch_assoc($stockquery)){
	   // print_r($r);
	   // die;
	   $s_p_id=$r['id'];
		  $invoice_no=$r['invoice_no'];
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
						
						$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`, `order_id`, `comment`,`child_id`,`invoice_no`) VALUES ('$single_p_id','$qty_s', 'out', '$order_id', 'productsell','$s_id','$invoice_no')";
						mysqli_query($conn,$qu);   
					}
				}
				$ps++;
			}
		$qu="UPDATE `order_list` SET `stock_check` = 'y' where id='$s_p_id'";
		mysqli_query($conn,$qu);   
   }	   
	
}
$categories = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$loginidset."' and status=0");

//~ $total_rows = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$_SESSION['login']."'"));
//$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// $current_id = $bank_data['id'];

	$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0 and parent_id='0' and maintain_stock='on' order by pending_stock asc");
    $totalcount=mysqli_num_rows($total_rows);
?>


<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
<style>
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
 }
 td.pop_up {
    cursor: pointer;
}
td.del {
    cursor: pointer;
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
 img.test_st {
    margin-right: 12px;
    margin-bottom: 12px;
}
.days label{
    width: 100%;
  }
  .days div.col-lg-4{
    margin: 5px;
    padding: 0;
  }
  .days .btn.btn-secondary{
    background-color: #e4e7ea;
    border-color: #e4e7ea;
    color: #555;
  }
  .days .btn.btn-secondary.checkbox-checked.active{
    background-color: #727b84 !important;
    border-color: #727b84 !important;
    color: #fff !important;
  }
  .remove_activity{
    width: 2.5rem;
    height: 2.5rem;
    margin-right: 3px;
    border-radius: 5px;
    cursor: pointer;
    background-color: #f00;
    display: grid;
    align-content: center;
    text-align: center;
    vertical-align: middle;
    color: #fff;
  }
</style>
 
    <?php include("includes1/head.php"); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
$(document).ready(function(){
		$('#remove_image').click(function(){
    var id=$('#id').val();
	// alert(id);
   $.ajax({
            url:'pro_img_delete.php',
           type:'POST',
            data:{id:id},
            success: function(data) {
            // location.reload();

         }
        
        });
    });
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchbox1 tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
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
			<?php 
			 $nq="SELECT * FROM stock_notification as noti inner join products as p on p.id=noti.product_id
			 WHERE noti.merchant_id='".$_SESSION['login']."' AND noti.status='0'  ORDER BY noti.id DESC LIMIT 10";
			 
			 $notifications = mysqli_query($conn,$nq);
					$stock_noti=mysqli_num_rows($notifications);
				if($stock_noti>0){
			?>
			<h2 class="wallet_h text-center">Notifications</h2>
					<div class="row">
						<table class="table table-striped">
							<tr>
								<th>Product Name</th>
								<th>Current Stock</th>   
								
								<th>Notify Date</th>
								<th>Action</th>
							</tr>
							<?php
							
							while($notification = mysqli_fetch_assoc($notifications))
							{
								 $created_on=$notification['created_on'];
								
							?>
							<tr>
								<td><?php echo $notification['product_name']; ?></td>
								<td><?php echo $notification['current_stock']; ?></td>
					
								<td><?php echo $created_on; ?></td>
								<td class="pop_up" data-id="<?php echo $notification['product_id']; ?>" data-name="<?php echo $notification['product_name']; ?>" data-pending="<?php echo $notification['pending_stock']; ?>">Refill</td> 
							</tr>
							<?php
							}

							mysqli_query($conn, "UPDATE notifications SET readStatus='1' WHERE user_id='".$_SESSION['login']."'");
							?>
						</table>
						<?php
						if(mysqli_num_rows($notifications) == 0)
						{
						    echo "<div style='text-align:center;    color: red;
    font-size: 17px;'>No More New Notifications</div>";
						}
						?>
					</div>
				<?php } ?>
                <div class="row" id="main-content" style="padding-top:25px">
                    <br /><br />
                    <input type="text" name="stext" value="" id="myInput" placeholder="Search category" class="form-control">
                    <br />
			<?php if($totalcount>0){ ?>
			<div class="col-sm-2 search-item" style="margin-top:2%;left:80%;">
			<a href="print_stock_report.php"  class="btn btn-primary form-control" >Report</a>
			</div>
		<table class="table table-striped">
    <thead>
      <tr>
        <th>Product id</th>
        <th>Product Name</th>
       
        <th>Current Stock</th>
       <th>Reorder Level</th>
        <th>Category</th>
		    <th>Product Code</th>
		    <th>Product Price</th>
		 
	  	 
        <th>Image</th>
        <th>Code</th>
       
        <th>Action</th>
      </tr>
    </thead>
	  <tbody id='searchbox1'>
	<?php
  
	while ($row=mysqli_fetch_assoc($total_rows)){
		$pending_stock=$row['pending_stock'];
		$reorder_level=$row['reorder_level'];
	?>
  
	<tr style="<?php if($pending_stock<$reorder_level){ echo "color:red;";} ?>">
       <!-- <td class="name" data-id=<?php //echo $row['id']; ?> style="cursor:pointer;"><?php //echo $row['name'];  ?></td> -->

         <!--<td class='status' name='status' onchange="update_product('<?php //echo $row['id'];?>')"></td>-->
        <td><?php echo $row['id'];  ?></td>
        <td><?php echo $row['product_name'];  ?></td>
       
        <td><?php echo $row['pending_stock'];  ?></td>
        <td><?php echo $reorder_level;  ?></td>
		   <td><?php echo $row['category'];  ?></td>
		  <td><?php echo $row['product_type'];  ?></td>
		  <td><?php echo $row['product_price'];  ?></td>
		
		  
      <?php
      if(!empty($row['image']))
      { ?>
              <td><img src="<?php echo $site_url; ?>/images/product_images/<?php echo $row['image'];  ?>" width="50" height="50" ></td>  

    <?php  } 
    else
    { ?>
       <td>       <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="50" height="50" >
</td>

   <?php }
      ?>
      <?php
      if(!empty($row['image']))
      { ?>
              <td><img src="<?php echo $site_url; ?>/images/product_images/<?php echo $row['code'];  ?>" width="50" height="50" ></td>  

    <?php  } 
    else
    { ?>
       <td>       <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="50" height="50" >
</td>

   <?php }
      ?>
      <td class="pop_up" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['product_name']; ?>" data-pending="<?php echo $row['pending_stock']; ?>">Refill</td> 
      <!--td class="pop_up" data-id="<?php echo $row['id']; ?>">Report</td!--> 
      <td class="stock_check" data-id="<?php echo $row['id']; ?>">Stock</td> 
    	  
      <td class="del" data-del="<?php echo $row['id']; ?>">Delete</td>
      <td class="stock" data-id="<?php echo $row['id']; ?>"><button <?php echo ($row['on_stock']) ? 'class="btn btn-success"' : 'class="btn btn-danger"' ?>>Stock</button></td>
     
	 </tr>
	  
      <?php
	}
	  ?>
	  
    </tbody>
  </table>
			<?php  }?>
  
  
  <div style="margin:0px auto;">
 <ul class="pagination">
 <?php
 global $total_page_num ;
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
<div>
<!-- Stock Model !-->
<div class="modal fade" id="StockModel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Inventory Managment</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
        
		 <div class="col-sm-10">
      <form id ="stockdata">
	  <div class="form-group">
			<input class="maintain_stock" type="checkbox" id="maintain_stock" name="maintain_stock"> Maintain Stock<br>
		</div> 
	  <div class="form-group">
										<label>Total Stock</label>
										<input type="Number" readonly id="total_stock" name="total_stock" class="form-control">
										<input type="hidden" name="id" id="stock_product_id">
									</div>
									 <div class="form-group">
										<label>Current Stock</label>
										<input type="Number" readonly id="current_stock" name="current_stock" class="form-control">
									
									</div>
									<div class="form-group">
										<label>Reorder Level</label>
										<input type="Number" name="reorder_level" id="reorder_level" class="form-control">
									</div>
									<div class="form-group">
										<label>Stock Value</label>
										<input type="Number" value="1" id="stock_value" name="stock_value" class="form-control">
									</div>
									<div class="form-group">
									<?php 
									 
									  $supplierdata = mysqli_query($conn, "select * from supplier_list where user_id='".$_SESSION['login']."'");
									?>
										<label>Select Supplier</label>
										 <select id="supplier_id" name="supplier_id"  class="select2 supplier_name form-control" style="width: 100%">
                                    <option>Select Supplier</option>
									<?php while ($ru=mysqli_fetch_assoc($supplierdata)){ ?>
                                    <option supplier_name="<?php echo $ru['supplier_name']; ?>" value="<?php echo $ru['id']; ?>"><?php echo $ru['supplier_name']; ?></option>
									<?php } ?>   
                                </select>
									</div>
									<div class="form-group">
							
										<label>Select Parent Product</label>
										 <select id="parent_id" name="parent_id"  class="select2  form-control" style="width: 100%">
                                    <option>Select Parent Product</option>
									<?php $p_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0 and parent_id='0' order by product_name asc");
  while ($ru=mysqli_fetch_assoc($p_rows)){ ?>
                                    <option  value="<?php echo $ru['id']; ?>"><?php echo $ru['product_name']; ?></option>
									<?php } ?>   
                                </select>
									</div>
    
	  
        </div>
		
        <div class="modal-footer" style="padding-bottom:2px;">
			<button>Submit</button>
<!--
          <button type="submit" class="btn btn-default" data-dismiss="modal" id ="update" onclick="submitmodal()">submit</button>
-->
        </div>
      </form>
	  </div>
      </div>
  
 <div class="modal fade" id="StockModal" role="dialog" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modalcontent">
       <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div> 
</div>
<!--End  Stock Model !-->
 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">   
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Refill Product</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
            <p>Product Name :  <span id="refill_product_name"></span></p>
            <p>Current Stock : <span id="refill_current_stock"></span></p>
		 <div class="col-sm-10">
      <form  method="post" action="refill_stock.php">
      <div class="form-group">     
      <label>Product Count</label>
	     <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo $loginidset; ?>"/>
	     <input type="hidden" id="refill_product_id" name="refill_product_id"/>
		 	<input type="text" name="product_count" id = "product_count" class="form-control" value="" required>
       <input type="hidden" id="id" name="id" value=""> 
      </div>
     
      <div class="form-group">
      <label>Refill Type</label>  
       <input type="radio" name="refill_type" checked="checked"  value="add"> Add &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="refill_type" value="deduct"> Deduct<br>
      </div>
	  <div class="form-group">
      <label>Comment</label>  
      
		<textarea rows="5" cols="5" class="form-control" name="comment"></textarea>
      </div>
	   <div class="form-group">
									<?php 
									 
									  $supplierdata = mysqli_query($conn, "select * from supplier_list where user_id='".$_SESSION['login']."'");
									?>
										<label>Select Supplier</label>
										 <select id="supplier_name" name="supplier_id"  class="select2 supplier_name form-control" style="width: 100%">
                                    <option>Select Supplier</option>
									<?php while ($ru=mysqli_fetch_assoc($supplierdata)){ ?>
                                    <option supplier_name="<?php echo $ru['supplier_name']; ?>" value="<?php echo $ru['id']; ?>"><?php echo $ru['supplier_name']; ?></option>
									<?php } ?>   
                                </select>
									</div>
      
      </div>

     
	  
        </div>
		
        <div class="modal-footer" style="padding-bottom:2px;">
		<input type="submit" class="btn btn-block btn-primary btn_click" name="refill" style="width:48%;" value="Refill"/>
        
        </div>
      </form>
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
					

				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<script>
    $("#add_active_time").on("click", function(e){
      $(".days").fadeToggle();
      e.preventDefault();
    }); 
    $("body").on("click",".remove_activity", function(){
      $(this).parent().parent().remove();
    });
    $(".reset_activity").on("click",function(e){
      e.preventDefault();
      $("#days_container .checkbox-checked.active").each(function(){
        $(this).removeClass("checkbox-checked").removeClass("active");
      });
    });
    $(".save_activity").on('click',function(e){
      e.preventDefault();
          var dictionary_human = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
          var days = [];
          var start_time = $(this).parent().parent().parent().find("input[name='start_time_setup']").val();
          var end_time = $(this).parent().parent().parent().find("input[name='end_time_setup']").val();
          console.log(start_time);
          console.log(end_time);
          $("#days_container .checkbox-checked.active").each(function(){
            days.push($(this).children("input[type='checkbox']").val());
          });
          if(days.length > 0){
            var days_human = [];
            for (var i = 0; i < days.length; i++) {
              // console.log(dictionary_human[days[i] - 1]);
              days_human.push(dictionary_human[days[i] - 1]);
            }
            days_human = days_human.join("-");
            // console.log(days_human);
            var result = '<div>'
            result += '<div class="row" style="margin: 10px 0">'
            result += '<div class="remove_activity">x</div>'
            result += '<div class="col" style="padding: 0 2px"><input type="text" class="form-control" value=' + days_human + ' disabled><input type="hidden" name="days_active[]" value="' + days.join("-") + '"/></div>';
            result += '</div><div class="row" style="margin: -7px 0 0 0;">';
            result += '<div class="col" style="padding: 0 2px;"><input type="text" class="form-control" value="From: ' + start_time + ' To: ' + end_time +  '" disabled></span><input type="hidden" name="start_hours[]" value="' + start_time + '"/><input type="hidden" name="end_hours[]" value="' + end_time + '"/></div>';
            result += '</div>';

            // result += '<div class="col-lg-5" style="padding: 0 2px;margin-top:3px;"><input type="time" class="form-control" value="' + start_time + '" disabled><input type="hidden" name="start_hours[]" value="' + start_time + '"/></div>';
            // result += '<div class="col-lg-5" style="padding: 0 2px;margin-top:3px;"><input type="time" class="form-control" value="' + end_time + '" disabled><input type="hidden" name="end_hours[]" value="' + end_time + '"/></div>';
            result += '</div>';

            $("#activity_group").append(result);
            $("#days_container .checkbox-checked.active").each(function(){
              $(this).removeClass("checkbox-checked").removeClass("active");
            });
            // console.log(result);
          }
        });
    $("input[name='always_active']").on("click", function(){
          // console.log("Clicked");
          if(!$(this).prop("checked")){
            $(".activity_parms").show();
          }else{
            $(".activity_parms").hide();
          }
        });
  $("body").on("click",".stock button", function(){
    $(this).addClass("selected");
    var id = $(this).parent().data('id');
    $.post("update_pro.php", {
      update_stock: true,
      id: id
    }, function(data,success){
      if(data){
        if($(".btn.selected").hasClass("btn-danger")){
          $(".btn.selected").removeClass("btn-danger").addClass("btn-success").removeClass("selected");
        }else{
          $(".btn.selected").removeClass("btn-success").addClass("btn-danger").removeClass("selected");
        }
      }

    });
  })
  $('.btn_click').click(function(e){
	  $this.prop( "disabled", true );
  });
	$("form#data").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    $.ajax({
        url: 'update_pro.php',
        type: 'POST',
        data: formData,
        success: function (data) {
          location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
	$("form#stockdata").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    $.ajax({
        url: 'update_stock.php',
        type: 'POST',
        data: formData,
        success: function (data) {
          location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
   $(".stock_check").click(function(){
	    $("#StockModel").modal("show");
		 var userid=$(this).data("id");
		 $('#stock_product_id').val(userid);
		 $.ajax({
		  
		  url :'update_product.php',
		  type:'POST',
		  dataType : 'json',
		  data:{showid:userid},
		  success:function(response){
			    $("#total_stock").val(response.total_stock);
			    $("#current_stock").val(response.pending_stock);
			    $("#reorder_level").val(response.reorder_level);
				var stock_maintain=response.maintain_stock;
				if(stock_maintain=="on")
				$( "#maintain_stock").prop('checked', true);
			  
			    $("#stock_value").val(response.stock_value);
			    $("#supplier_id").val(response.supplier_id);
			    $("#parent_id").val(response.parent_id);

		    }		  
	  });
   });
   $(".pop_up").click(function(){
	  var userid=$(this).data("id");
	  var product_name=$(this).data("name");
	  var pending=$(this).data("pending");
	  $("#refill_product_name").html(product_name);
	  $("#refill_current_stock").html(pending);
	  $("#refill_product_id").val(userid);
	  $("#myModal").modal("show");
	 
	  
  });

function submitmodal(){

$('#update').on('click', function() {
     form = jQuery("#form").serialize();

      var image =$(this).data("picture");
       //~ alert(image);
       //~ alert(form);
           $.ajax({
               url: 'update_pro.php',
               type: 'POST',
               data: form,
               data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)

        success: function(data) {
        console.log(data);
          }
           });
       });
}

  $('.del').click(function(){
    var id=$(this).data("del");
	if (confirm('Are you sure you want to delete that product?')) {
    // Save it!
	$.ajax({
            url:'pro_delete.php',
           type:'POST',
            data:{userid:id},
            success: function(data) {
            location.reload();

         }
        
        });
} else {
    // Do nothing!
}
   
    });
 
    $('.save').click(function(){
     var id = $(this).data("save_pic")
      
    });



	</script>
</body>

</html>
