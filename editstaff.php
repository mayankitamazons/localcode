<?php
session_start();

include("config.php");

$current_time = date('Y-m-d H:i:s');
if($_SESSION['login']=='')

{

    header('Location: '. $site_url .'/login.php');

    die;

}






?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">



<head>

    <style>

        .no-close .ui-dialog-titlebar-close {

            display: none;

        }

        .test_product{

            padding-right: 125px!important;

        }

        td.products_namess {

            text-transform: lowercase;

        }

        tr {

            border-bottom: 2px solid #efefef;

        }

        .well {

            min-height: 20px;

            padding: 19px;

            margin-bottom: 20px;

            background-color: #fff;

            border: 1px solid #e3e3e3;

            border-radius: 4px;

            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

        }

        td {

            border-right: 1px solid #efefef;

        }

        th {

            border-right: 1px solid #efefef;

        }

        tr.fdfd {

            border-bottom: 3px double #000;

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

        tr.red {

            color: red;

        }

        label.status {

            cursor: pointer;

        }

        td {

            border-right: 2px solid #efefef;

        }

        th {

            border-right: 2px solid #efefef;

        }

        .gr{

            color:green;

        }

        .or{

            color: orange !important;

        }

        .red.gr{

            color:green;

        }

        .product_name{

            width: 100%;

        }

        .total_order{

            font-weight:bold;

        }

        p.pop_upss {

            display: inline-block;

        }

        .location_head{

            width:200px;

        }

        .new_tablee {

            width: 200px!important;

            display: block;

            word-break: break-word;

        }

        td.test_productss {

            white-space: nowrap;

            /*width: 200px!important;*/

            display: block;

        }

        th.product_name.test_product {

            width: 200px!important;

        }



        @media only screen and (max-width: 600px) and (min-width: 300px){

            table.table.table-striped {

                white-space: unset!important;

            }





    </style>



    <?php include("includes1/head.php"); 
	
	$staffid=$_REQUEST['staffid'];
	
	?>

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

                <div class="well" style="width:100%">
                     
                    
					<?php
		
			
	if(isset($_REQUEST['submit']))
	{
			
 
	$name = addslashes($_POST['name']);
	
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	$cpassword = addslashes($_POST['cpassword']);
	
	$mobile_number = addslashes($_POST['mobile_number']);
	$account_type = addslashes($_POST['account_type']);
	$permissionapp=$_POST['permissionapp'];
	$order_print_setting=$_POST['order_print_setting'];
	$order_print_live_setting=$_POST['order_print_live_setting'];
	$permission_set=serialize($permissionapp);
	
	$cm =	$mobile_number;

	$error = "";
	
	if($name == "")
	{
		$error .= "Name cannot be Empty.<br>";
	}
	
	$already_exists1 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' && user_roles= '$user_role' && id !='$staffid'" ));

	 if($already_exists1 > 0)
	 {
		 $error .= "Mobile Number Already Exists.<br>";
	 }

	if(!empty($password) && !empty($cpassword) && (strlen($password) >= 15 || strlen($password) <= 7))
	{
		$error .= "Password must be between 8 and 15.<br>";
	}
	
	if($error == "")
	{
		
		
		
	
     
        
	
	   
	   
	    mysqli_query($conn, "Update users SET order_print_setting='$order_print_setting',order_print_live_setting='$order_print_live_setting',name='$name',account_type='$account_type',email='$email',mobile_number='$cm',permission_set='$permission_set' where id='$staffid'");
		
		if(!empty($password) && !empty($cpassword) )
		{
			mysqli_query($conn, "Update users SET password='$password' where id='$staffid'");
		
		}
        
		
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];

	
		
					echo "<script>location.replace('staff.php');</script>";
		
		

	}
	else
	{
		echo "<strong>$error</strong>";
	
	}
		
	}	
	

	
	
	
 $select="select * from users where id='$staffid' ";
$query=mysqli_query($conn,$select);
while($rowset=mysqli_fetch_array($query))
{
	$name=$rowset['name'];
	$email=$rowset['email'];
	$mobile_number=$rowset['mobile_number'];
	
	$account_type=$rowset['account_type'];
	$permissionapp=$rowset['permission_set'];
	$permissionapp=unserialize($permissionapp);
	$order_print_setting = $rowset['order_print_setting'];
	$order_print_live_setting = $rowset['order_print_live_setting'];	

}


?>


                    <div>

                        <h3>Edit staff Account </h3>
						
						
						<form method="post" id="koosignup">
								<div class="login-top sign-top" style="width: 60%;

margin-left: 28%;">
									
									<input type="hidden" name="user_role" value="3">
									
									<div class="form-group input-has-value">
											
											<input type="text" class="name active" placeholder="User Name Here" name="name" value="<?php echo  $name ; ?>" id="reg_name" />
									</div>
									
									
								
									
									<div class="form-group input-has-value">
									
									
									
										
                                     <input type="text" class="mobile_number" placeholder="<?php echo $language["telephone_number"];?> " name="mobile_number" id="reg_mobnum"  value="<?php echo $mobile_number; ?>"/> 
									</div>
								
										
										<div class="form-group input-has-value">
									
											  <input type="text"   placeholder="Email Id" name="email" value="<?php echo $email; ?>"  />
                                        
									</div>
									
									
										<div class="form-group input-has-value">
									
											  <select name="account_type" class="form-control">
        								     <option value="">Non K1 / K2</option>
	                                         <option value="K1"<?php if ($account_type=="K1"){echo "selected=selected";} else { echo " "; } ?>>K1</option>
	                                         <option value="K2" <?php if ($account_type=="K2"){echo "selected=selected";} else { echo " "; } ?>>K2</option>
	                                         <option value="K1 &amp; K2" <?php if ($account_type=="K1 &amp; K2"){echo "selected=selected";} else { echo " "; } ?>>K1 &amp; K2</option>
	                                    </select>
                                        
									</div>
									
									
										
										
								
									<div class="form-group input-has-value">
									
											  <input type="password" class="password" id="Password"  placeholder="Password" name="password"  />
                                         <input type="password" name="cpassword" id="cpassword"  placeholder="Confirm Password" class="col-md-9" >
									</div>
									<div class="form-group">
										<label>Order Print Setting</label><br>
										<input class="order_print_setting" type="checkbox" name="order_print_setting" <?php if($order_print_setting) echo "checked='checked'";?> >Auto Print Invoice<br>
										<input class="order_print_setting" type="checkbox" name="order_print_live_setting"  <?php if($order_print_live_setting) echo "checked='checked'";?>>Auto Print Live order<br>
										
									</div>  
									<div class="form-group input-has-value">
									<div class="row">
									  <div class="col-md-6">
									  <label>Dashboard: </label>  <input type="checkbox" name="permissionapp[]" value="8" <?php if(in_array(8,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Add Product: </label>  <input type="checkbox" name="permissionapp[]" value="9" <?php if(in_array(9,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>View Product: </label>  <input type="checkbox" name="permissionapp[]" value="10" <?php if(in_array(10,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>Add Category: </label>  <input type="checkbox" name="permissionapp[]" value="11" <?php if(in_array(11,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>Add Master: </label>  <input type="checkbox" name="permissionapp[]" value="12" <?php if(in_array(12,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>View Category: </label>  <input type="checkbox" name="permissionapp[]" value="13" <?php if(in_array(13,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>Subscription: </label>  <input type="checkbox" name="permissionapp[]" value="14" <?php if(in_array(14,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
											<label>About Us: </label>  <input type="checkbox" name="permissionapp[]" value="15" <?php if(in_array(15,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Report: </label>  <input type="checkbox" name="permissionapp[]" value="16" <?php if(in_array(16,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										  <label>Shift Report: </label>  <input type="checkbox" name="permissionapp[]" value="17" <?php if(in_array(17,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										
										
									  </div>
									  <div class="col-md-6">
										<label>Order List : </label>  <input type="checkbox" name="permissionapp[]" value="1" <?php if(in_array(1,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>My Community : </label> <input type="checkbox" name="permissionapp[]" value="2" <?php if(in_array(2,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Referral List : </label>  <input type="checkbox" name="permissionapp[]" value="3" <?php if(in_array(3,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>K Type : </label> <input type="checkbox" name="permissionapp[]" value="4" <?php if(in_array(4,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Profile : </label> <input type="checkbox" name="permissionapp[]" value="5" <?php if(in_array(5,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Inverstor Relations : </label> <input type="checkbox" name="permissionapp[]" value="6" <?php if(in_array(6,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Contact Us : </label> <input type="checkbox" name="permissionapp[]" value="7" <?php if(in_array(7,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Cash System : </label> <input type="checkbox" name="permissionapp[]" value="18" <?php if(in_array(18,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Product Order: </label> <input type="checkbox" name="permissionapp[]" value="19" <?php if(in_array(19,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Pos Product Order: </label> <input type="checkbox" name="permissionapp[]" value="20" <?php if(in_array(20,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Invetory: </label> <input type="checkbox" name="permissionapp[]" value="21" <?php if(in_array(21,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Stock Report: </label> <input type="checkbox" name="permissionapp[]" value="22" <?php if(in_array(22,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
										<label>Supplier: </label> <input type="checkbox" name="permissionapp[]" value="23" <?php if(in_array(23,$permissionapp)) { echo "checked=checked";} else{ echo " ";} ?>><br>
									
									  </div>
									</div>
									
										
									
									</div>
                                    
										<div class="form-group input-has-value">
											<input type="submit" value="Submit" style="padding:14px;" name="submit">
										
										</div> 
                                      
                                       
                                       
        								
        								
        							
        								
                                           
                                        
                                    </div>
								</form>

                      	

                    </div>

                    <?php

                    $dt = new DateTime();

                    $today =  $dt->format('Y-m-d');

                    ?>

                   


                    

                    <div>

                        

                        

                        

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



