<?php 
    include("config.php");
	$LSession = "" ;
	if(isset($_SESSION['login'])){
		
		$LSession  = $_SESSION['login'] ;
	}
    $user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$LSession."'"))['mobile_number'];
    
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
@media (max-width: 650px) and (min-width: 360px) {
    .nature_image{
        width: 25px;
        height: 25px;
    }
    .starting-bracket{
        margin-top: 0.8rem;
    }
}
 @media only screen and (max-width: 650px) and (min-width: 360px)  {
.tot_wer {
    display: block!important;
}
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
					  
        <?php
        if($_SESSION['IsVIP'] ==1){ 
        ?>	
        
        <div class="box-right">
        <div class="title">
        <div class="title-left"> <img src="new/images/merchant.png"> <div class="title-h">  <a href="#"> Merchant Name</a> </div>  </div> 
        <div class="title-right"> <a href="#"> <img src="new/images/heart.png"> </a>  </div> 
        </div> 
        <div class="cont-area1"> 
        
		
        <div class="btns">
        <a href="<?php echo $site_url; ?>/view_merchant.php"><div class="main-btn"> <?php echo $language["menu"];?></div> </a>
        <a href="<?php echo $site_url; ?>/rating_list.php"> <div class="main-btn1"> <?php echo $language["rating"];?> </div>  </a>
        </div>
        <div class="clear-both"> </div> 
        <div class="head-title"><?php echo $language["about_us"];?></div>
        <div class="main-cont">
        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently.
        </div>
		
		
		<?php   	 
        $id = $_SESSION['mm_id'];; 
        $about_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE userid='".$id."'"));
        $link=$about_detail['link']; 
        $about_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
        ?> 
        <div class="white-box">
        <div class="grey-title"> <?php echo $language["background_of_the_merchant"];?></div>
        <div class="red-bg"> 
        <div class="red-hd">Description : <?php echo $about_detail['description'];  ?></div> 
        <div class="red-mid"> Link: <?php  echo "<a href='".$link."'>".$link."</a>"; ?></div> 
        <div class="red-right"> Welcom Note: <?php echo $about_detail['welcome_note'];  ?> </div> 
		<?php if(!empty($about_detail['video_upload'])){ ?>
        <div class="tot_wer">
        <video width="320" height="240" controls>
        <source src="<?php echo $site_url; ?>/images/videos/<?php echo $about_detail['video_upload'];  ?>" type="video/mp4">
        </video>
        <?php } else { } ?>
        <?php if(!empty($about_detail['image'])){ ?>
        <img src="<?php echo $site_url; ?>/images/about_images/<?php echo $about_detail['image'];  ?>" width="100%" height="150px" class="make_bigger">
        <?php } else { }  ?>
        </div> 
        <!--div class="bar-bttm"> 
        <div class="bar-title">QR Code </div>
        <div class="bar-area"> <a href="#"> <img src="new/images/bar-code.png"> </a></div> 
        </div> 
        </div!-->
        </div>
        </div> 
	
        <?php
        }else{
        ?>
        <div class="about_mer">
        <div class="col-md-12">
        <?php 
        $id = $_SESSION['mm_id'];
        $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
        
        $sql_transaction = "SELECT COUNT(id) ordered_num 
        FROM order_list
        WHERE user_id='".$LSession."' and merchant_id = '".$id."' AND STATUS='1'";
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
        ?>
        <div class="col-md-12 row favorite" style="margin-left:0px; padding-left:0px; padding-right: 0px;">
        <input type="hidden" class="merchant_id" value="<?php echo $id;?>">
        <input type="hidden" class="user_id" value="<?php echo $LSession ?>">
        <?php 
        $favorite = mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$LSession." AND favorite_id = ".$id."");
        $count = @mysqli_num_rows($favorite);
        ?>
        <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_detail['name'];?></h4>
        <div class="favorite_icon">
        <?php if($count > 0) {?>
        <i class="heart fa fa-heart"></i>
        <?php } else {?>
        <i class="heart fa fa-heart-o"></i>
        <?php }?>
        </div>
        <h4 style="display: inline-block;" class="starting-bracket">(</h4>
        <?php if($business1 != ""){ ?>
        <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business1;?>">
        <?php }?>
        <?php if($business2 != ""){ ?>
        <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business2;?>">
        <?php }?>
        <?php if($merchant_detail['account_type'] != ''){?>
        <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
        <?php }?>
        <h4 class="transaction_num" style=""><?php echo $result_transaction['ordered_num'];?>, </h4>
        <h4 class="favorite_num" style="margin-right: 0px;"><?php echo $result_favorite['favorite_num'];?>)</h4>
        </div>
        <div class="total_rat_abt">
        <div class="about_uss">
        <a class="merchant_about" href="<?php echo $site_url; ?>/view_merchant.php"><?php echo $language["menu"];?></a>
        </div>
        <div class="rating_menuss">
        <a class="merchant_ratings" href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"];?> </a>
	</div>
        <div class="rating_menuss">
        <a class="merchant_ratings" href="<?php echo $site_url; ?>/location.php?address=<?php echo  $_SESSION['address_person']; ?>"><?php echo $language["location"];?> </a>
        </div>
        <?php if(isset($_SESSION['invitation_id']) && (!isset($LSession))){?>
        <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 60px;"></a>
        <?php }?>
        </div> 
        <h2><?php echo $language["about_us"];?></h2>
        
        
        <?php   	 
        $id = $_SESSION['mm_id'];; 
        $about_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE userid='".$id."'"));
        $link=$about_detail['link']; 
        $about_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
        
        ?> 
        <div class="comment_box about_cmt">
        <h4> <?php echo $language["background_of_the_merchant"];?></h4>
        <p> <?php echo $about_detail['description'];  ?></p>
        <p> <?php  echo "<a href='".$link."'>".$link."</a>"; ?></p>
        <p> <?php echo $about_detail['welcome_note'];  ?></p>
        <?php if(!empty($about_detail['video_upload'])){ ?>
        <div class="tot_wer">
        <video width="320" height="240" controls>
        <source src="<?php echo $site_url; ?>/images/videos/<?php echo $about_detail['video_upload'];  ?>" type="video/mp4">
        </video>
        <?php } else { } ?>
        <?php if(!empty($about_detail['image'])){ ?>
        <img src="<?php echo $site_url; ?>/images/about_images/<?php echo $about_detail['image'];  ?>" width="100%" height="150px" class="make_bigger">
        <?php } else { }  ?>
        </div>
        </div>
        </div><!--col-md-12--> 
        </div><!--about_mer-->
        
        <?php } ?>
        
        
   <?php //print_r($about_detail);?>
				
<h3 class="text_qrcode">QR Code</h3>
		<br>
		<div class="col-md-3"></div>
		<div class="well col-md-6">
						<div style="margin:10px">
							<img src="qrcode/qrcode.php?text=<?php echo $about_user['mobile_number']; ?>" style="width:100%" class="text_qrcode">
						</div>
					</div>
							
					</div><!--content-->
					
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
h3.text_qrcode {
    width: 100%;
}
img.make_bigger {
    width: 345px;
    display: block;
    height: 210px;
        margin-top: 30px;
}

video {
    margin-right: 15px;
}
.tot_wer {
    display: flex;
}
</style>
