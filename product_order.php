<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
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
					<div class="container">
					<?php
					   $user_id=$_SESSION['login'];
					   $profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
								// print_R($profile_data);
								// die;
								if($profile_data['user_roles']==5)
								{
									$user_id=$profile_data['parentid'];
								}
								else
								{

									$user_id=$_SESSION['login'];

								}
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
							$user_id=$_SESSION['login'];
						}
						if(isset($_POST['search']))
						{
							$category_id=$_POST['category_id'];
							if($category_id!='-1')
							{
							 $query="select arrange_system.*,products.product_name from arrange_system inner join products on arrange_system.entity_id=products.id where 
							arrange_system.category_id='$category_id' and arrange_system.page_type='p' and arrange_system.user_id='".$user_id."' order by arrange_system.shift_pos asc limit 0,100";
							// die;
						   $productquery = mysqli_query($conn,$query);
						     $num_rows = mysqli_num_rows($productquery);
							$result = mysqli_fetch_all ($productquery, MYSQLI_ASSOC);
							// print_R($result[0]);
							// die;
							}
							
						}
						if(isset($_POST['update']))
						{
							$shift_pos=$_POST['shift_pos'];
							$entity_id=$_POST['entity_id'];
							$category_id=$_POST['category_id'];
							// echo "delete from arrange_system where entity_id='$entity_id' and page_type='p' and user_id='".$_SESSION['login']."'";
							// die;
							$listq = mysqli_query($conn,"delete from arrange_system where entity_id='$entity_id' and page_type='p' and user_id='".$user_id."'");
							// $listq = mysqli_query($conn,"select id from arrange_system where entity_id='$entity_id' and page_type='p' and user_id='".$_SESSION['login']."'");
							$listq = mysqli_query($conn,"delete from arrange_system where shift_pos='$shift_pos' and category_id='$category_id' and page_type='p' and user_id='".$user_id."'");
							// new insert 
							$q="INSERT INTO arrange_system(id,entity_id,user_id,shift_pos,page_type,status,category_id) VALUES (NULL, '$entity_id', '$user_id', '$shift_pos', 'p', 'active','$category_id')";
							mysqli_query($conn,$q);
							$category_id=$_POST['category_id'];
							$query="select arrange_system.*,products.product_name from arrange_system inner join products on arrange_system.entity_id=products.id where 
							products.category_id='$category_id' and arrange_system.user_id='".$user_id."' order by arrange_system.shift_pos asc limit 0,100";
				            $productquery = mysqli_query($conn,$query);
						     $num_rows = mysqli_num_rows($productquery);
							$result = mysqli_fetch_all ($productquery, MYSQLI_ASSOC);
						}  
						
	
					?>
					</div>
					<?php
					  $total_rows = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$user_id."' and status=0");
					?>
					<div class="container" >
					   
					        <div class="well">
							<form method="post">
							       <div class="row">
							       <div class="col-md-6">
								      <div class="form-group row">
											 <label class="col-md-4 form-control-label" for="text-input">Select category</label>
											  <div class="col-md-8 autocomplete">
												
												<select  id='category_id' required name="category_id" class="form-control">
														<option value='-1'>Select category</option>
															<?php 
																while ($row=mysqli_fetch_assoc($total_rows))
															{                                 ?>
																 <option <?php if($category_id==$row['id']){echo "selected";} ?> value="<?php echo $row['id'];?>"><?php echo $row["category_name"];?></option>
																 <br>
															<?php }
																?>
												</select>
											
											  </div>
									  
									
										</div> 
									</div>
									<div class="col-md-6">
								      <div class="form-group row">
											<input type="submit" name="search"  class="btn btn-primary" value="Search"/>
											&nbsp;&nbsp;&nbsp;&nbsp;
										<button id='auto_fill' type="button" class="btn btn-primary">Auto Fill</button>
										<small>Note: On click of auto fill it will rearrage  & replace all data of selected category</small>
										
										</div> 
									</div>
									</div>
						</form>
						
									
									<div class="row">
									<?php if(isset($result)){ 
									
									 foreach ($result as $key => $item) {
									
									   $shift_pos=$item['shift_pos'];
									   $arr[$shift_pos]=$item;
									   
									   }
									   	 // print_R($arr);
									?>
									
									  <?php for($p=1;$p<=50;$p++){ 
									    
									    $list=$arr[$p];
										
										  $shift_pos=$list['shift_pos'];
										  if($shift_pos==$p)
										  {
										  ?>
										   <div class="card col-3">
												<div class="card-body" style="text-align:center;">
												 <label style="text-align:left;">Position <?php echo $p; ?></label>
												<img src="<?php echo $image;?>" style="max-width: 80px;">
												  <p><?php echo $list['product_name']; ?></p>
												
												  
												</div>
												<i  class="add_product"   pos="<?php echo $p; ?>" style="text-align:right">Edit</i>
											</div>
										  <?php }
											  else
											  {
											?>
											 <div class="card col-3 add_product"  data-toggle="modal" data-target="#myModal"  pos="<?php echo $p; ?>">
												<div class="card-body" style="text-align:center;">
												<p> Add </p>
												</div>
											</div>
										 <?php  }
									$i++;} } ?>
										 	
									</div>
								
							
						</div>
						
					
				</div>
				
  <div id="responsive-catelog-model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" style="max-width:1000px !important;">
									
									<form method="post">
									
									  <input  type="hidden" id="data_shift_pos" name="shift_pos"/>
									  <input  type="hidden" id="category_id" name="category_id" value="<?php  echo $category_id;?>"/>
                                        <div class="modal-content catelog_plan_body">
                                           <?php if(isset($_POST['category_id'])){ 
										       $category_id=$_POST['category_id'];
												 $query="select arrange_system.shift_pos,products.id as product_id,products.product_name,products.product_type,products.product_price from products  left join  arrange_system on products.id=arrange_system.entity_id
											   where products.category_id='$category_id' and products.status='0'  and products.user_id='".$_SESSION['login']."' order by shift_pos limit 0,50";
												 $listq = mysqli_query($conn,$query);
												$num_rows = mysqli_num_rows($listq);
												?>
										      <div class="modal-body">
                                                <div class="row">
												
                        <div class="col-12 table-responsive catelog_body">   
						            <label>Select Product For Position <span id="list_shift_pos"></span></label>
									<?php if($num_rows>0){ ?>
                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Position</th>
                                                <th>Product Name</th>
                                                <th>Product Price</th>
                                               
                                                <th>Product  Code</th>
                                                
                                                
                                              
                                            </tr>
                                        </thead>
                                       
                                        <tbody> 
										  
                                           <?php 
										     $i=1;  while ($row=mysqli_fetch_assoc($listq)){  $shift_pos=$row['shift_pos']; ?>
                                            <tr>
                                                <td>  
												
												<input  style="position:static;opacity:1;" id="entity_id"   name="entity_id" type="radio"  value="<?php echo $row['product_id']; ?>" class="custom-control-input"></td>
                                                <td><?php if($shift_pos) echo $shift_pos; else echo "--"; ?></td>
												<td><?php echo $row['product_name']; ?></td>
												<td><?php echo $row['product_price']; ?></td>
												 <td><?php echo $row['product_type']; ?></td>
                                             
                                               
                                             
                                               
                                                
                                             
                                            </tr>
										<?php  $i++;} ?>
                                           
                                            
                                           
                                        </tbody>
                                    </table>
									<?php } else { echo "No Product For selected Category";} ?>
                                </div>

                       
                    
							</div>
							 
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
															<input type="submit" name="update" class="btn btn-danger waves-effect waves-light" value="Save Changes"/>
															 
														</div>
										   <?php } ?>
                                        </div>
									</form>
                                    </div>
          </div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/commonfooter.php"); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
</body>
 <script>
    jQuery(document).ready(function() {
		   $('.add_product').click(function() {
			var pos = $(this).attr("pos");
			// alert(pos);
			$("#list_shift_pos").html(pos);
			$("#data_shift_pos").val(pos);
			$('#responsive-catelog-model').modal('show'); 
		});
		$('#auto_fill').click(function() {
			var category_id=$("#category_id option:selected").val();
			if(category_id=='-1')
			{
				$("#category_id").focus();
			}
			else
			{
				$('#auto_fill').prop("disabled", "disabled");
				 $.ajax({
               url: 'auto_fill.php',  
               type: 'POST',
               data:{auto_category_id:category_id},
                 success: function(data) {
					  alert('All Select  Category Product Auto Fill');
						location.reload();

					}
				   });
			}
		});
  
	});
 </script>
</html>
<style>
select {
    height: 30px;
}
</style>
