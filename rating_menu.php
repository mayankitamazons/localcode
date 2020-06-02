<?php 
include("config.php");
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ");
$user_idd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list"));
if(isset($_POST['submit']) && !empty($_POST['submit'])) 
{ 
	$merchant_id =$_POST['m_id'];
	
	$order_id =$_POST['o_id'] != "" ? $_POST['o_id'] : "";
	$orid =$_POST['orid'];
	$user_id =$_POST['u_id'];
	$rating=$_POST['rating'];  
	$comment=$_POST['comment'];
	$date = date('Y-m-d H:i:s');
	
	mysqli_query($conn, "INSERT INTO  rating SET user_id='".$user_id."',merchant_id='".$merchant_id."',rating='".$rating."', comment='".$comment."',total='".$order_id."',or_id='".$orid."',Created_on='".$date."'");

   
}

 $nature_array = array(
        "Foods and Beverage, such as restaurants, healthy foods, franchise, etc",
        "Motor Vehicle, such as car wash, repair, towing, etc",
        "Hardware, such as household, building, renovation to end users",
        "Grocery Shop such as bread, fish, etc retails shops",
        "Clothes such as T-shirt, Pants, Bra, socks,etc",
        "Business to Business (B2B) including all kinds of businesses"
    );
    $nature_image = array(
        "foods.jpg",
        "car.jpg",
        "household.jpg",
        "grocery.jpg",
        "clothes.jpg",
        "b2b.jpg"
    );
    
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
	
	
<head>
    <?php include("includes1/head.php"); ?>
	<style>
		.create_date
		{
			float: right;
		}
		
		.comment_box {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-top: 15px;
    box-shadow: 0 0 5px 0px;
	}
		.submit_button
		{
			width:25% !important;
		}
		.comment{
			width:90%;
		}
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
	.pro_name
	{   
	 text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
    }
    .about_mer {
    width: 100%;
}
.total_rat_abt {
    font-size: 17px;
    display:flex;
}
.rating_menuss {
    padding: 5px;
    margin-right: 25px;
    padding: 5px;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    background: #00736A;
    border-radius: 8px;
}
.about_uss {
    padding: 5px;
    margin-right: 25px;
    padding: 5px;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    background: #00736A;
    border-radius: 8px;

}

a.merchant_about {
    color: #fff;
}
a.merchant_ratings {
    color: #fff;
}
p.del {
    float: right;
    font-size: 25px;
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

<div class="well col-md-12"> 
	<?php
	    $id = $_SESSION['mm_id'];
	    $oid = $_SESSION['o_id'];
	    $orid = $_SESSION['orid'];
        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
	    
        $sql_transaction = "SELECT COUNT(id) ordered_num 
						FROM order_list
						WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$id."' AND STATUS='1'";
		$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
		$sql_favorite = "SELECT COUNT(id) favorite_num
						FROM favorities
						WHERE favorite_id = '".$id."'";
		$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
												
	    $business1 = "";
	    $business2 = "";
	    for($i = 0; $i < count($nature_array); $i++){
	        if($merchant_detail['business1'] == $nature_array[$i])
	            $business1 = $nature_image[$i];
	        if($merchant_detail['business2'] == $nature_array[$i])
	            $business2 = $nature_image[$i];
	    }
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0"));	
      	$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0");
        $categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$id."' and status=0");
        $order_list = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id = ".$_SESSION['login']."");
            $order_count = mysqli_num_rows($order_list);
	 if($order_count > 0) {
		 $rating_list = mysqli_query($conn, "SELECT * FROM rating WHERE user_id = ".$_SESSION['login']." AND or_id = ".$orid."");
            $rating_count = mysqli_num_rows($rating_list);
		              if($rating_count <= 0) 
        {   
		  ?>
	
 <form action="" method="post">
    <div class="col-md-12 row favorite" style="margin-left:0px; padding-left:0px;">
        <input type="hidden" class="merchant_id" value="<?php echo $id;?>">
        <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'] ?>">
        <?php 
            $favorite = mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."");
            $count = mysqli_num_rows($favorite);
        ?>
        <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_detail['name'];?></h4>
        <div class="favorite_icon">
            <?php if($count > 0) {?>
            <i class="heart fa fa-heart"></i>
            <?php } else {?>
                <i class="heart fa fa-heart-o"></i>
            <?php }?>
        </div>
        <h4 style="display: inline-block;">(</h4>
    	<?php if($business1 != ""){ ?>
    	    <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business1;?>">
    	<?php }?>
    	<?php if($business2 != ""){ ?>
    	    <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business2;?>">
    	<?php }?>
    	<?php if($merchant_detail['account_type'] != ''){?>
		    <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
		<?php }?>
	    <h4 class="transaction_num" style="margin: 1.25rem 0.7rem 0.625rem 0.7rem"><?php echo $result_transaction['ordered_num'];?>, </h4>
	    <h4 class="favorite_num" style="margin: 1.25rem 0.7rem 0.625rem 0.7rem"><?php echo $result_favorite['favorite_num'];?>)</h4>
    </div>
  <div class="total_rat_abt">
		  	<div class="about_uss">
		  	    <a class="merchant_about" href="<?php echo $site_url; ?>/view_merchant.php"><?php echo $language["menu"]?></a>
		  	</div>
			<div class="rating_menuss">
			    <a class="merchant_ratings" href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a>
			</div>
			<div class="rating_menuss">
			    <a class="merchant_ratings" href="<?php echo $site_url; ?>/location.php?address=<?php echo  $_SESSION['address_person']; ?>"><?php echo $language["location"];?> </a>
			</div>
			<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
				<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/birthday-card.gif" style="width: 50px;"></a>
            <?php }?>
		</div>
			
					<h3><?php echo $language["rating"];?></h3>
					
  <br>
  	<br>  
  	 <?php 
  	  //~ echo $_SESSION['login'];
  	  //~ echo '<br>';
  	  //~ echo $user_idd['user_id'];
  	 //if($user_idd['user_id'] == $_SESSION['login'] )  { 
  	 if(!empty($_SESSION['login']) )  { 
		 ?> 
  <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>"> 
    <input type="hidden" id="orid" name="orid" value="<?php echo $orid;?>"> 
  <input type="hidden" id="id" name="o_id" value="<?php echo $oid;?>"> 
  <input type="hidden" id="id" name="u_id" value="<?php echo $_SESSION['login'];?>"> 
  <input type="radio" name="rating" value="Good"> <?php echo $language["good"];?>
  <input type="radio" name="rating" value="Neutral"> <?php echo $language["neutral"];?>
  <input type="radio" name="rating" value="Poor"> <?php echo $language["poor"];?> <br><br>
  
  <textarea class="form-control comment" name="comment" placeholder="Comment" required></textarea> <br><br>
 
  <input type="submit" class="btn btn-block btn-primary submit_button" name="submit" value="<?php echo $language["submit"];?>"><br><br>
  <?php } ?> </form>  
  <?php } }
//~ }
//~ else
//~ {
	
	//~ echo 'Your comment ending date is :  '.$nxt_cmt_date.'.You cannot comment now.';
	
//~ }
?>
<br><br>
  <?php 
	
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Good' and merchant_id='".$id."'" ));
	echo 'Good  ('.$rating['rate'].') ;' ; ?> 
	
	<?php 
	
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Neutral' and merchant_id='".$id."'" ));
	echo 'Normal  ('.$rating['rate'].') ;' ; ?>
	<?php
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Poor' and merchant_id='".$id."'" ));
	echo 'Poor  ('.$rating['rate'].')' ; ?>
		
  <br><br>
  
  <?php
  $rate_comment = mysqli_query($conn, "SELECT * FROM rating WHERE merchant_id ='".$id."' ORDER by `Created_on` DESC");
  ?>
  <?php
  
	while ($row=mysqli_fetch_assoc($rate_comment)){
	
	$name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));	
  ?>
  <div class="comment_box">
<!--
	<p class="del" data-del="<?php //echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></p>
-->
  <p><?php echo 'Total : ' .$row['total'];  ?></p>
  <p><?php echo $row['comment'];  ?></p>
  <p><?php echo 'Rating : ' .$row['rating']; ?></p>
  <span class="name_cm"><?php echo 'Name : ' .$name['name'];  ?></span><span class="create_date"><?php echo $row['Created_on']; ?></span>
  
  </div>
   
 <?php } 	?>

 </div>
</div>
</main>

</div><!--content wrapper--->
</div><!--wrapper--->
<?php include("includes1/footer.php"); ?>
</body>

</html>
<style>
input.btn.btn-block.btn-primary.col-md-5 {
    float: left;
    margin-right: 15px;
    margin-left: 20px;
}

</style>
<!--
<script>
 $('.del').click(function(){
    var id=$(this).data("del");
    //~ alert(id);
   $.ajax({
            url:'comment_delete.php',
           type:'POST',
            data:{userid:id},
            success: function(data) {
             location.reload();

         }
        
        });
    });

</script>
-->
