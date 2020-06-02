<?php 
include("config.php");

	$total_rows = mysqli_query($conn, "SELECT * FROM subscription ");
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
 
    <?php include("includes1/head.php"); ?>
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
	<table class="table table-striped">
    <thead>
      <tr>
        <th>Subscription Name</th>
        <th>Subscription Rate</th>
		    <th>Subscription type</th>

        <th>Action</th>
      </tr>
    </thead>
	
	<?php
  
	while ($row=mysqli_fetch_assoc($total_rows)){
   
	?>
    <tbody>
      <tr>

        <td><?php echo $row['subscription_name'];  ?></td>
		   <td><?php echo $row['subscription_rate'];  ?></td>
		  <td><?php echo $row['subscription_qyt'];  ?></td>
      <td class="sub_pop_up" data-id="<?php echo $row['id']; ?>">Edit</td>  
      </tr>
	  
      <?php
	}
	  ?>
	  
    </tbody>
  </table>
  
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
<div>
	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Product Subscription</h4>
        </div>
        <div class="modal-body" style="padding-bottom:0px;">
        
		 <div class="col-sm-10">

		    <!--<textarea id='updated_status' class="form-control" value=""> </textarea> -->

      <form id ="data">
      <div class="form-group">
      <div class="form-group">

                    <input type="hidden" id="id" name="id" value=""> 

                    <label>Subscription Name</label>
                    <input type="text" name="productname" id="product_name" class="form-control" value="" required>
                   </div>
                  <div class="form-group">
                    <label>Subscription Rate</label>
                    <input type="number" name="category" id="category" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                    <label>Subscription type</label>
                    <input type="text" name="product_type" id="product_type" class="form-control" value="" required>
                  </div>
                  <br>

		</div>

        </div>
		</div>
        <div class="modal-footer" style="padding-bottom:2px;">
			<button class="update">Submit</button>
        </div>
      </form>
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
  $(".sub_pop_up").click(function(){
	  $("#myModal").modal("show");
	  var userid = $(this).data("id");
    //alert(userid);
    $.ajax({
		  
		  url :'sub_edit.php',
		  type:'POST',
      dataType : 'json',
      data:{showid:userid},
		  success:function(response){
			//alert(response);
      console.log(response);
      $("#id").val(response.id);
      $("#product_name").val(response.subscription_name);
      $("#category").val(response.subscription_rate);
      $("#product_type").val(response.subscription_qyt);
          
		  }		  
	  }); 
  });

$('.update').on('click', function() {
     form = jQuery("#data").serialize();
           $.ajax({
               url: 'sub_update.php',
               type: 'POST',
               data: form,

        success: function(data) {
        console.log(data);
         //alert(data);
         //location.reload();
          }
           });
       });
	</script>
</body>

</html>
