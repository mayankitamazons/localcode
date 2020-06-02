<?php 
include("config.php");
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ");


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
	
	   $order_list = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id = ".$_SESSION['login']."");
            $order_count = mysqli_num_rows($order_list);
 if($order_count > 0) 
 
 {
	  $rating_list = mysqli_query($conn, "SELECT * FROM rating WHERE user_id = ".$_SESSION['login']." AND or_id = ".$orid."");
            $rating_count = mysqli_num_rows($rating_list);
             if($rating_count <= 0) 
        {     
	?>
	
 <form action="" method="post">
    <div class="col-md-6 row favorite" style="margin-left:0px; padding-left:5px;">
        <input type="hidden" class="merchant_id" value="<?php echo $id;?>">
        <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'] ?>">
        <?php 
            $favorite = mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."");
            $count = mysqli_num_rows($favorite);
        ?>
        <h4 class="favorite_name" style="display: inline-blick;">Merchant Name: <?php echo $merchant_detail['name'];?></h4>
        <div class="favorite_icon">
            <?php if($count > 0) {?>
            <i class="heart fa fa-heart"></i>
            <?php } else {?>
                <i class="heart fa fa-heart-o"></i>
            <?php }?>
        </div>
    </div>
  <div class="total_rat_abt">
		  	<div class="about_uss">
		  	    <a class="merchant_about" href="http://kooexchange.com/demo/view_merchant.php">Menu</a>
		  	</div>
			<div class="rating_menuss">
			    <a class="merchant_ratings" href="http://kooexchange.com/demo/about_menu.php">About Us </a>
			</div>
			<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
				<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/birthday-card.png" style="width: 50px;"></a>
            <?php }?>
		</div>
			
					<h3>Rating</h3>
					
  <br>
  	<br>
  <input type="hidden" id="m_id" name="m_id" value="<?php echo $id;?>"> 
  <input type="hidden" id="o_id" name="o_id" value="<?php echo $oid;?>"> 
  <input type="hidden" id="orid" name="orid" value="<?php echo $orid;?>"> 
  <input type="hidden" id="u_id" name="u_id" value="<?php echo $_SESSION['login'];?>"> 
  <input type="radio" name="rating" value="Good"> Good
  <input type="radio" name="rating" value="Neutral"> Neutral
  <input type="radio" name="rating" value="Poor"> Poor <br><br>
  <textarea class="form-control comment" name="comment" placeholder="Comment"></textarea> <br><br>
  <input type="submit" class="btn btn-block btn-primary submit_button" name="submit" value="Submit"><br><br>
  </form> 
  <?php
 }
}
//~ }
//~ else
//~ {
	
	//~ echo 'Your comment ending date is :  '.$nxt_cmt_date.'.You cannot comment now.';
	
//~ }
?>
<br><br>
  <?php 
	
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Good' and merchant_id='".$id."'" ));
	echo 'Good : ('.$rating['rate'].')' ; ?> 
	
	<?php 
	
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Neutral' and merchant_id='".$id."'" ));
	echo 'Normal : ('.$rating['rate'].')' ; ?>
	<?php
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Poor' and merchant_id='".$id."'" ));
	echo 'Poor : ('.$rating['rate'].')' ; ?>
		
  <br><br>
  
  <?php
  $rate_comment = mysqli_query($conn, "SELECT * FROM rating WHERE merchant_id ='".$id."' ORDER by `Created_on` DESC");
  ?>
  <?php
  
	while ($row=mysqli_fetch_assoc($rate_comment)){
		//~ print_r($row);
	$name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));	
  if($row['status']=='0') {
  ?>
  <div class="comment_box">
  <p class="del" data-del="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></p>
<!--
    <td class="del" data-del="<?php //echo $row['id']; ?>">Delete</td>
-->
  <p><?php echo 'Total : ' .$row['total'];  ?></p>
  <p><?php echo $row['comment'];  ?></p>
  <p><?php echo 'Rating : ' .$row['rating']; ?></p>
  <span class="name_cm"><?php echo 'Name : ' .$name['name'];  ?></span><span class="create_date"><?php echo $row['Created_on']; ?></span>
  
  </div>
   
 <?php } }	?>

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
<script>
 $('.del').click(function(){
    var id=$(this).data("del");
    alert(id);
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
