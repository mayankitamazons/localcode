<?php 
include("config.php");

// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE
// COLUMN "on_stock" MUST BE CREATED IN PRODUCTS TABLE

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
$categories = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$loginidset."' and status=0");

//~ $total_rows = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$_SESSION['login']."'"));
//$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// $current_id = $bank_data['id'];
	$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0 order by product_name asc");
 
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
			$('.test_st').hide();
			$('#remove_image').hide();

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
                <div class="row" id="main-content" style="padding-top:25px">
                    <br /><br />
                    <input type="text" name="stext" value="" id="myInput" placeholder="Search category" class="form-control">
                    <br />
	<table class="table table-striped">
    <thead>
      <tr>
        <th>Product id</th>
        <th>Product Name</th>
       
        <th>Category</th>
		    <th>Product Code</th>
		    <th>Product Price</th>
		    <th>Printer type</th>
			 <th>Printer Ip Address</th>
	  	  <th>Remark</th>
	  	  <th>Current Stock</th>
		   <th>Vairent Exit</th>
		   <th>Vairent Must</th>
        <th>Image</th>
        <th>Code</th>
       
        <th>Action</th>
      </tr>
    </thead>
	  <tbody id='searchbox1'>
	<?php
  
	while ($row=mysqli_fetch_assoc($total_rows)){
		$cat_id=$row['category_id'];
		if($cat_id=='0')
		{
			$color="red";
		}
		  $parent_id=$row['parent_id'];
		if($parent_id>0)
		{
			$color="blue";
		}
		else
		{
			$color="";
		}
	?>
  
	<tr style="<?php if($color){ echo "color:".$color;} ?>">
       <!-- <td class="name" data-id=<?php //echo $row['id']; ?> style="cursor:pointer;"><?php //echo $row['name'];  ?></td> -->

         <!--<td class='status' name='status' onchange="update_product('<?php //echo $row['id'];?>')"></td>-->
        <td><?php echo $row['id'];  ?></td>
        <td><?php echo $row['product_name'];  ?></td>
		   <td><?php echo $row['category'];  ?></td>
		  <td><?php echo $row['product_type'];  ?></td>
		  <td><?php echo $row['product_price'];  ?></td>
		  <td><?php echo $row['printer_profile']."/ ".$row['usb_name'];  ?></td>
		    <td><?php if($row['print_ip_address']) { echo "Printer 1 :".$row['print_ip_address']; echo "<br/>";}  ?> <?php if($row['printer_ip_2']) { echo "Printer 2 :".$row['printer_ip_2'];}  ?></td>
		
		  <td><?php echo $row['remark'];  ?></td>   
		  <td><?php echo $row['pending_stock'];  ?></td>   
		  <td><?php echo $row['varient_exit'];  ?></td>
		  <td><?php echo $row['varient_must'];  ?></td>
		  
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
      <td class="pop_up" data-id="<?php echo $row['id']; ?>">Edit</td> 
      <td class="stock_check" data-id="<?php echo $row['id']; ?>">Stock</td> 
   <td class="sub_product" data-del="<?php echo $row['id']; ?>"><a href="<?php echo $site_url; ?>/sub_product.php?p_id=<?php echo $row['id']; ?>">Product Varieties</a></td>
     	  
      <td class="del" data-del="<?php echo $row['id']; ?>">Delete</td>
      <td class="stock" data-id="<?php echo $row['id']; ?>"><button <?php echo ($row['on_stock']) ? 'class="btn btn-success"' : 'class="btn btn-danger"' ?>>Stock</button></td>
     
	 </tr>
	  
      <?php
	}
	  ?>
	  
    </tbody>
  </table>
  
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
									<?php $p_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0");
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
          <h4 class="modal-title">Edit Product</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
        
		 <div class="col-sm-10">
      <form id ="data">
      <div class="form-group">
      <label>Product Name</label>
		 	<input type="text" name="productname" id = "product_name" class="form-control" value="" required>
       <input type="hidden" id="id" name="id" value=""> 
      </div>
      <div class="form-group">
      <label>Category</label>
      <select  name="category" class="form-control" id="category">
		<?php while ($row=mysqli_fetch_assoc($categories)){   
		    // $category = str_replace(' ', '-', $row["category_name"]);
		    $category = $row["category_name"];?>
		<option value="<?php echo $category;?>"><?php echo $row["category_name"];?></option>
		<br>
		<?php }?>
	   </select>
      </div>
      <div class="form-group">
      <label>Product Code</label>
    	<input type="text" name="product_type" id = "product_type" class="form-control" value="" required>
      </div>
	   <div class="form-group">
                      <label>Printer Type</label>
										
                                        <select id="printer_profile" class='printer_profile' name="printer_profile" style="">
										 <option value="usb">USB</option>
										  <option value="ip">IP PRINTER</option>
                                           
                                          
                                        </select>
                                    </div>
									<div class="form-group">
										<label>USB Sharing Name </label>
										<input type="text" id='usb_name' name="usb_name" class="form-control">
									  
									</div>  
	    <div class="form-group">
      <label>Print Ip Address</label>
    	<input type="text" name="print_ip_address" id = "print_ip_address" class="form-control">
      </div>
	   <div class="form-group">
      <label>Print Ip Address 2</label>
    	<input type="text" name="printer_ip_2" id = "printer_ip_2" class="form-control">
      </div>
      <div class="form-group">
      <label>Product Price</label>  
    	<input type="text" name="product_price" id = "product_price" class="form-control" value="" required>
      </div>
	  <div class="form-group">
      <label>Discount (in %)</label>
		<input type="text" name="product_discount" id="product_discount" class="form-control" placeholder="Discount on Product in %">
      </div>
      <div class="form-group">
      <label>Remark </label>
    	<input type="text" name="remark" id = "remark" class="form-control" value="">
      </div>
	   <div class="form-group" style="display:none" id="varient_check">
	    <p><input type="checkbox" id="varient_must" name="varient_must">Varient Must</p>
	  </div>
      <div class="form-group">
        <label>Activity <small>(The time in which the product will be active)</small></label>
        <p><input type="checkbox" name="always_active" checked> Always active</p>
      <div class="activity_parms" style="display: none;">
        <div class="activity_parms" style="display: none;padding:0 15px">
          <div class="row form-group">
            <div class="col" style="padding: 0 2px">
              <button id="add_active_time" class="btn btn-primary" data-toggle="modal" data-target="#active_time_modal">Add active time</button>
            </div>
          </div>
          <div class="days" style="display: none;">
            <div id="days_container" class="row justify-content-center">
              <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="1" autocomplete="off">
              Monday
            </label>
          </div>
              <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="2" autocomplete="off">
              Tuesday
            </label>
          </div>
              <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="3" autocomplete="off">
              Wednesday
            </label>
          </div>
              <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="4" autocomplete="off">
              Thursday
            </label>
          </div>
              <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="5" autocomplete="off">
              Friday
            </label>
          </div>
          <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="6" autocomplete="off">
              Saturday
            </label>
          </div>
          <div style="" class="btn-group col-lg-4" data-toggle="buttons">
            <label class="btn btn-secondary">
              <input type="checkbox" name="ingredient" value="7" autocomplete="off">
              Sunday
            </label>
          </div>
            </div>
          <div class="row justify-content-center" style="margin: 10px 0;">
            <div class="col">
              <label>From</label>
              <div class="row">
            <div class="col" style="padding: 0 2px">
              <input type="time" class="form-control" name="start_time_setup" value="00:00">
            </div>
              </div>
            </div>
            <div class="col">
              <label>Until</label>
              <div class="row">
                <div class="col" style="padding: 0 2px">
              <input type="time" class="form-control" name="end_time_setup" value="00:00">
            </div>
              </div>
            </div>
          </div>
          <div class="from-group row justify-content-center" style="margin: 10px 0">
            <div class="col btn-group">
              <button class="save_activity btn btn-primary" style="width: 80%;">Add</button>
              <button class="reset_activity btn btn-secondary" style="width: 50%;">Reset</button>
            </div>
          </div>
        </div>
      </div>
          <div id="activity_group">
            
          </div>
        </div>
      </div>
      </div>

      <div class="form-group">
	  
	    <label>Select Image</label><br>
		<img src="<?php echo $site_url; ?>/images/remove.png" style="width: 24px;height: 24px;margin-left: 12%;" id="remove_image"/>
		
	    <div id ="picture"></div>
	    <input type="file" class="save" name="image_pic"  onclick="save_pic()">
       <input type="hidden" id="img" name="img" value=""> 

		</div>
        <div class="form-group">
          <label>Select Code</label><br>
          <div id ="picture_code"></div>
          <input type="file" class="save" name="image_code"  onclick="save_pic()">
          <input type="hidden" id="img_code" name="img_code" value="">
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
			  // alert(response.product_discount);
			   
			    $("#total_stock").val(response.total_stock);
				var stock_maintain=response.maintain_stock;
				if(stock_maintain=="on")
				$( "#maintain_stock").prop('checked', true);
			  
			    $("#reorder_level").val(response.reorder_level);
			    $("#stock_value").val(response.stock_value);
			    $("#supplier_id").val(response.supplier_id);
			    $("#parent_id").val(response.parent_id);

		    }		  
	  });
   });
  $(".pop_up").click(function(){
	  $( "#varient_must").prop('checked', false);
	  $("#varient_check").hide;
	  $("#myModal").modal("show");
	  var userid=$(this).data("id");
	   //target:'#picture';

	  $.ajax({
		  
		  url :'update_product.php',
		  type:'POST',
      dataType : 'json',
      data:{showid:userid},
		  success:function(response){
      console.log(response);
      $("#activity_group").html("");
      if(response.active_time != 1){
        var dictionary_human = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        var active_time = JSON.parse(response.active_time);
        for (var i = 0; i < active_time.length; i++) {
          var days = [];
          var days_human = [];
          var start_time = active_time[i]['start'];
          var end_time = active_time[i]['end'];
          days.push(active_time[i]['days']);
          var separate_days = active_time[i]['days'].split("-")
          // console.log(separate_days);
          for (var j = 0; j < separate_days.length; j++) {
            days_human.push(dictionary_human[separate_days[j]-1]);
          }
          days_human = days_human.join("-");
          // console.log(days);
          // console.log(days_human);
          $("input[name='always_active']").prop("checked",false);
            var result = '<div>'
            result += '<div class="row" style="margin: 10px 0;">'
            result += '<div class="remove_activity">x</div>'
            result += '<div class="col" style="padding: 0 2px"><input type="text" class="form-control" value=' + days_human + ' disabled><input type="hidden" name="days_active[]" value="' + days.join("-") + '"/></div>';
            result += '</div><div class="row" style="margin: -7px 0 0 0;">';
            result += '<div class="col" style="padding: 0 2px;"><input type="text" class="form-control" value="From: ' + start_time + ' To: ' + end_time +  '" disabled></span><input type="hidden" name="start_hours[]" value="' + start_time + '"/><input type="hidden" name="end_hours[]" value="' + end_time + '"/></div>';
            result += '</div></div>';

          // console.log(result);
          // $("input[name='start_date']").val(active_time['start_date']).attr("min",active_time['start_date']);
          // $("input[name='start_time']").val(active_time['start_time']);
          // $("input[name='end_date']").val(active_time['end_date']).attr("min",active_time['start_date']);
          // $("input[name='end_time']").val(active_time['end_time']);
          // console.log(active_time);
          $("#activity_group").append(result);
        }
        $(".activity_parms").show();
      }else{
        $("input[name='always_active']").prop("checked",true);
        $(".activity_parms").hide();
        $("input[name='start_time']").val("00:00");
        $("input[name='end_time']").val("00:00");
      }
      $("#id").val(response.id);
      $("#product_name").val(response.product_name);
	   $("#product_discount").val(response.product_discount);
      $("#category").val(response.category);
      $("#product_type").val(response.product_type);
      $("#product_price").val(response.product_price);
      $("#print_ip_address").val(response.print_ip_address);
	   $("#printer_ip_2").val(response.printer_ip_2); 
	    $("#printer_profile").val(response.printer_profile);
		var varient_check=response.varient_exit;
		if(varient_check=="y")
		{
			$('#varient_check').show();
		}
		var varient_must=response.varient_must;
		if(varient_must=="y")
		{
			$( "#varient_must").prop('checked', true);
		}
	   $("#usb_name").val(response.usb_name); 
	  
      $("#remark").val(response.remark);
	  if(response.image)
	  {
		$("#img").val(response.image);
	  }
      $("#img_code").val(response.img_code);
      $("#picture").html("");
      $("#picture_code").html("");
       $("#picture").append('<img src="<?php echo $site_url; ?>/images/product_images/'+response.image+' " width="50" height="50"  class="test_st">');
       $("#picture_code").append('<img src="<?php echo $site_url; ?>/images/product_images/'+response.code+' " width="50" height="50"  class="test_st">');
     
     
      console.log(response);
          
		  }		  
	  });
	 
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
