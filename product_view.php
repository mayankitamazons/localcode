<?php 
   include("config.php");

   if($_POST['p_id'] != "")
   {
   
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
            <?php include("includes1/sidebar.php");
			$m_id = $_POST['m_id'];
			$p_id = $_POST['p_id'];
			$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));
			$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$p_id."'"));	
            ?>
            <?php
							if(isset($error) && $error != "")
							{
								echo "<div class='alert alert-danger'>$error</div>";
							}
							if(isset($success))
							{
								echo "<div class='alert alert-success'>$success</div>";
							}
							?>
            <!-- /.site-sidebar --> 
            <main class="main-wrapper clearfix" style="min-height: 522px;">
               <div class="row" id="main-content" style="padding-top:25px">
                  <div class="col-md-12">
                     <h2><?php echo $product['product_name']; ?></h2>
                  </div>
                  <br>
                  <div class="col-md-6">
                     <?php 
                        
                        if(!empty($product['image']))
                        { ?>
                     <img src="<?php echo $site_url; ?>/images/product_images/<?php echo $product['image'];  ?>" width="100%" height="auto" >
                     <?php  } 
                        else
                        { ?>
                     <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="100%" height="auto" >
                     <?php }
                        ?> 
                  </div>
                  <div class="col-md-6 well">
					    <form id ="data">
                     <p  class="col-md-12"><?php echo 'Category : '.$product['category']; ?></p>
                     <p class="col-md-12"><?php echo 'Product Code : '.$product['product_type']; ?></p>
                     <p class="col-md-12"><?php echo 'Product Price : '.$product['product_price']; ?></p>
                     <p class="col-md-12"> Qty :  <input type="number" min="1"value ="1" name="qty"> </p> 
                       <input type="hidden" id="id" name="m_id" value="<?php echo $m_id;?>"> 
	       <input type="hidden" id="id" name="p_id" value="<?php echo $p_id;?>"> 
	       <input type="hidden" id="id" name="u_id" value="<?php echo $_SESSION['login'];?>"> 
                     <select class="form-control" required="true" name="wallet" id="wallet">
                        <option value="" >Select Wallet</option>
                        <option value="MYR" <?php if ($_POST['wallet']== "MYR") { ?> selected="selected" <?php } ?> >Malaysian Ringgit (<?php echo $balance['balance_myr']; ?>)</option>
                        <option value="USD" <?php if ($_POST['wallet']== "USD") { ?> selected="selected" <?php } ?> >US Dollar (<?php echo $balance['balance_usd']; ?>) </option>
                        <option value="INR" <?php if ($_POST['wallet']== "INR") { ?> selected="selected" <?php } ?> >Chinese Yuan (<?php echo $balance['balance_inr']; ?>)</option>
                     </select>
                     <br>
                     
                     <button class="btn btn-block btn-primary"> Buy </button>
                     <br>
                      </form>
                  </div>
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
<?php } 
   else
   {
   	header("location:merchant_list.php");
   }
   ?>
   
   <script>
	$("form#data").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);


    $.ajax({
        url: 'buy_pro.php',
        type: 'POST',
        data: formData,
        success: function (data) {
             alert(data);
              console.log(data);
             //location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});






	</script>
