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
	$total_rows = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$loginidset."' and status=0 ORDER BY created_date DESC");
 
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
		<th>id</th>
        <th>Category Name</th>
        <th>Action</th>
      </tr>
    </thead>
	
	<?php
 $i =1;
 	while ($row=mysqli_fetch_assoc($total_rows))
	{
	?>
    <tbody>
      <tr>
        <td><?php  echo $i ?></td>
		   <td><?php echo $row['category_name'];  ?></td>
      <td class="pop_up" data-id="<?php echo $row['id']; ?>">Edit</td>  
      <td class="del" data-del="<?php echo $row['id']; ?>">Delete</td>
      </tr>
	    <?php  	 $i++; ?>
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
          
        <?php
        $Cat_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cat_mater WHERE UserID='".$_SESSION['login']."'"));
        $catData = explode(',' , $Cat_data['CatName']) ;
        ?>
        <div class="form-group">
        <label>category Name</label>
        <select name ="catparent" required id="catparent">
        <option>Select Parent Category</option> 
        <?php
        $Count = 1 ; 
        foreach($catData as $Catname){
        ?>
        <option value='<?php echo $Count ?>'><?php echo $Catname ?></option>   
        <?php
        $Count = $Count + 1  ;
        }
        
        ?>
        </select>
        </div>
									
      <div class="form-group">
      <label>Category Name</label>
		 	<input type="text" name="categoryname" id = "categoryname" class="form-control" value="" required>
      <input type="hidden" id="id" name="id"  value=""> 
	<input type="hidden" id="category_name" name="category_name"  value=""> 

      
      </div>
        </div>
		</div>
        <div class="modal-footer" style="padding-bottom:2px;">
			<button>Submit</button>
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
	$("form#data").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);
    //alert(formData);
    $.ajax({
        url: 'update_cat.php',
        type: 'POST',
        data: formData,
        success: function (data) {
           location.reload();
           console.log(data);          
         //~ location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
  $(".pop_up").click(function(){
	  $("#myModal").modal("show");
	  var userid=$(this).data("id");
	  $.ajax({
		  
		  url :'update_category.php',
		  type:'POST',
      dataType : 'json',
      data:{showid:userid},
		  success:function(response){
      // alert(response.catparent);
      $("#id").val(response.id);
      $("#categoryname").val(response.category_name); 
      $("#category_name").val(response.category_name); 
       $("#catparent").val(response.catparent); 
      console.log(response);          
		  }		  
	  });
	 
  });
//~ function submitmodal(){
//~ $('#update').on('click', function() {
	//~ alert('hello');
     //~ form = jQuery("#form").serialize();
           //~ $.ajax({
               //~ url: 'update_cat.php',
               //~ type: 'POST',
               //~ data: form,
               //~ data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        //~ success: function(data) {
        //~ console.log(data);
        //~ alert(data);
          //~ }
           //~ });
       //~ });
//~ }
  $('.del').click(function(){
    var id=$(this).data("del");
   $.ajax({
            url:'cat_delete.php',
           type:'POST',
            data:{userid:id},
            success: function(data) {
            location.reload();
         }
        
        });
    });
 
    $('.save').click(function(){
     var id = $(this).data("save_pic")
      
    });
	</script>
</body>

</html>
<style>
td.pop_up {
    width: 12px;
}
</style>