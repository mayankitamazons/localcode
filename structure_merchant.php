<?php 
include("config.php");  

    
	$user_mobiless = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ");
    $product = "";
	if(!empty($_POST['countrycode']))  
	{
        $mobil_num =  $_POST['countrycode'].''. $_POST['merchant'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$mobil_num' and user_roles='2'"));	
        $_SESSION['merchant_id'] = $product['id'];
        
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id']; 
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ; 
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
     else if(!empty($_POST['merchant_id']))
    {
		$m_u_id = $_POST['merchant_id'];
		
		
		$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE name='$m_u_id' and user_roles='2'"));	
		$merchant_name = $product['name'];
		
        $_SESSION['merchant_id'] =  $product['id'];
		$_SESSION['invitation_id'] = $product['referral_id'];
		$_SESSION['address_person'] = $product['address'] ;
		$_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
	} 
	else if(!empty($_POST['merchant_address'])){
	    $m_address = $_POST['merchant_address'];
	    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE merchant_url='$m_address' and user_roles='2'"));
	    $merchant_name = $product['name'];
	    $_SESSION['invitation_id'] = $product['referral_id'];
		$_SESSION['address_person'] = $product['address'] ;
		$_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
	}
	else if(!empty($_GET['favorite_id'])){
        $id = $_GET['favorite_id'];
        $_SESSION['merchant_id'] = $id;
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
         
    } else if(!empty($_GET['merchant_id'])){
        $id = $_GET['merchant_id'];
        $_SESSION['merchant_id'] = $id;
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
         $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
    else if(!empty($_GET['sid'])){
        $sid = $_GET['sid'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$sid' and user_roles='2'"));
        $merchant_name = $product['name'];
        $_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['merchant_id'] = $product['id'];
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
         
    } else if(isset($_SESSION['merchant_id'])){
        $m_u_id = $_SESSION['merchant_id'];
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$m_u_id' and user_roles='2'"));	
		$merchant_name = $product['name'];
		$_SESSION['invitation_id'] = $product['referral_id'];
        $_SESSION['address_person'] = $product['address'] ;
        $_SESSION['latitude'] = $product['latitude'] ; 
        $_SESSION['longitude'] = $product['longitude'] ;
        $_SESSION['IsVIP'] = $product['IsVIP'] ;
    }
    //~ if(!isset($_SESSION['merchant_id'])){
		//~ header("location:merchant_find.php");
	//~ }    
    /*if($product == NULL){
        header("location:merchant_find.php?error_type=1"); 
    }*/
     else {
		header("location:merchant_find.php");
	}    
    if($product == NULL){
        header("location:merchant_find.php?error_type=1"); 
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
             $mar_id = $product['id'];
            $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE `userid` = $mar_id"));   
            ?>
            
            <div class="box-right">
			
			<?php 
			$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$mar_id."'"));
			$sql_transaction = "SELECT COUNT(id) ordered_num 
			FROM order_list
			WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$mar_id."' AND STATUS='1'";
			$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
			$sql_favorite = "SELECT COUNT(id) favorite_num
			FROM favorities
			WHERE favorite_id = '".$mar_id."'";
			$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
				
			$business1 = "";
			$business2 = "";
			for($i = 0; $i < count($nature_array); $i++){
			if($merchant_detail['business1'] == $nature_array[$i])
			$business1 = $nature_image[$i];
			if($merchant_detail['business2'] == $nature_array[$i])
			$business2 = $nature_image[$i];
			}
			// $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$mar_id."' and status=0"));	
			//$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$mar_id."' and status=0");
			//$categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$mar_id."' and status=0");
			?>
			<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
			<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>">

			<img src="img/join-us.jpg" style="width: 100px;">
			</a>
			<?php }?>

            <div class="title">
            <div class="title-left"> <img src="new/images/mail.png"> <div class="title-h">  <a href="#"> Merchant Name:<span>  <?php echo $merchant_name ?></span> </a> </div>  </div> 
            <div class="title-right"> 
			
			<div class="favorite_icon">
                                <?php if($count > 0) {?>
                                <i class="heart fa fa-heart"></i>
                                <?php } else {?>
                                    <i class="heart fa fa-heart-o"></i>
                                <?php }?>
                                <h4 class="starting-bracket white" style="display: inline-block;">(</h4>
                        	<?php if($business1 != ""){ ?>
                        	    <img style="margin-top:0px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">
                        	<?php }?>
                        	<?php if($business2 != ""){ ?>
                        	    <img style="margin-top:0px;" class="nature_image" src="<?php echo $site_url;?>/img<?php echo $business2;?>">
                        	<?php }?>
                        	 
                        	<?php if($merchant_detail['account_type'] != ''){?>
							    <h4 class="transaction_num white kType"> <?php echo $merchant_detail['account_type'];?>, </h4>
							<?php }?>
                    	   <h4 class="transaction_num white" ><?php echo $result_transaction['ordered_num'];?>, </h4>
                    	    <h4 class="favorite_num white" ><?php echo $result_favorite['favorite_num'];?>)</h4>
                            </div>
			</div> 
            </div> 
            <div class="cont-area"> 
            <div class="head-title">Mykluang Coffee </div> 
            <div class="tabs">
            <div class="tab"> <a href="#" onclick="window.location.href = 'view_merchant.php';" > <img src="new/images/tab-01.png"> </a> </div>
            <div class="tab"> <a href="#" onclick="window.location.href = 'payment_menu.php';"> <img src="new/images/tab-02.png"> </a> </div>
            </div>
            <div class="tabs">
            <div class="tab"> <a href="#" onclick="window.location.href = 'rating_list.php';"> <img src="new/images/tab-03.png"> </a> </div>
            <div class="tab"> <a href="#" onclick="window.location.href = 'about_menu.php';"> <img src="new/images/tab-04.png"> </a> </div>
            </div>	
            <div class="tabs-02">
            <div class="tab-main"> <a href="#" onclick="window.location.href = 'location.php?address=<?php echo  $_SESSION['address_person'] ?>';"> <img src="new/images/tab-05.png"> </a> </div>
            </div>
            </div>
            </div> 
            <?php
            }
            else{
            ?>
           	<div class="col-md-12 test_wel_not" id="test_wel_not">
					
    					<?php  $mar_id = $product['id'];
                                $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE `userid` = $mar_id"));?>
<!--
                           <div class="heading">WELCOME NOTE</div>
-->
                           <div class="welccc_nottt"><?php echo $about['welcome_note']; ?></div>
<!--
                           <div class="logo_head">LOGO</div>
-->
                        <?php if(!empty($about['image'])){ ?>
                            <div class="logo_img"> <img src="<?php echo $site_url; ?>/images/about_images/<?php echo $about['image'];  ?>" width="100px" height="100px" ></div>
                        <?php }  else { ?>
    	                    <img src="img/No_image_available.svg" width="100px" height="100px" >
    	                <?php } ?>
   
					</div>
					
				   
					<div class="col-md-12" style="margin-bottom:10px; padding-right: 0px;">
						<?php 
						    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$mar_id."'"));
						    if( isset( $_SESSION['login'] ) ) {
                                $sql_transaction = "SELECT COUNT(id) ordered_num 
                							FROM order_list
                							WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$mar_id."' AND STATUS='1'";						        
                				$result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
						    } else {
						        $sql_transaction = '';
						        $result_transaction = '';
						    }
                    		
                    		$sql_favorite = "SELECT COUNT(id) favorite_num
                    						FROM favorities
                    						WHERE favorite_id = '".$mar_id."'";
                    		$result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
                    												
                    	    $business1 = "";
                    	    $business2 = "";
                    	    for($i = 0; $i < count($nature_array); $i++){
                    	        if($merchant_detail['business1'] == $nature_array[$i])
                    	            $business1 = $nature_image[$i];
                    	        if($merchant_detail['business2'] == $nature_array[$i])
                    	            $business2 = $nature_image[$i];
                    	    }
                           // $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$mar_id."' and status=0"));	
                          	//$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$mar_id."' and status=0");
                            //$categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$mar_id."' and status=0");
						?>
						<?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
							<a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>">
							   
							    <img src="img/join-us.jpg" style="width: 100px;">
							</a>
                        <?php }?>
                        <div class="col-md-12 favorite" style="padding: 0px !important;">
                            <?php 
                            if (!empty($_SESSION['login'])){
                                $favorite = mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$mar_id."");
                                $count = mysqli_num_rows($favorite);
                                
                                 $about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$mar_id.""));
                                 } ?>

                            <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_name;?></h4>
                            <div style="clear:both;">
                             <h4 class="favorite_name" style="display: inline-blick;">
                                 <a href="javascript:jqcc.cometchat.launch({uid:'<?php echo $mar_id;?>'});">Chat with <?php echo $merchant_name ?></a></h4><br/>
                            </div>
                            <?php  //print_r($_SESSION); ?>
                            <script type="text/javascript">
                            var chat_appid = '52013';
                            var chat_id = '<?php echo $_SESSION['login'] ;?>';
                            var chat_name = '<?php echo $_SESSION['name'];?>';
                            var chat_position = 'left';
                            
                            (function() {
                            var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
                            document.getElementsByTagName("head")[0].appendChild(chat_css);
                            var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
                            })();
                            </script>

                            <br>
                            <div class="hint" style="display:inline-block;">
                                <span style="display:inline-block;">
                                    <div class="tri_div test_fav1" style="left:22px;"></div>
                                    <div class="test_fav_1">Click here to add me as your "Favorite"</div>
                                </span>
                                <span style="display:inline-block;">
                                    <div class="tri_div trail2 test_mobile" style="left:121px;"></div>
                                   <div class="test_fav_2"> Number of transaction that <br>you have ordered with this merchant</div>
                                </span>
                                <span style="display:inline-block;">
                                    <div class="tri_div trail2 trail_test" style="left:15px;"></div>
                                    <div class="test_fav_3">Number of members who have added as  <br> Favorite
									</div>
                                </span>
                            </div>
                            <div class="favorite_icon">
                                <?php if(isset($count) && $count > 0) {?>
                                <i class="heart fa fa-heart" data-toggle="tooltip"></i>
                                <?php } else {?>
                                    <i class="heart fa fa-heart-o" data-toggle="tooltip"></i>
                                <?php }?>
                                <h4 class="starting-bracket white" style="display: inline-block;">(</h4>
                            	<?php if($business1 != ""){ ?>
                            	    <img style="margin-top:0px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business1;?>">
                            	<?php }?>
                            	<?php if($business2 != ""){ ?>
                            	    <img style="margin-top:0px;" class="nature_image" src="<?php echo $site_url;?>/img/<?php echo $business2;?>">
                            	<?php }?>
                         
                            	<?php if($merchant_detail['account_type'] != ''){?>
    							    <h4 class="transaction_num white kType"><?php echo $merchant_detail['account_type'];?>,</h4>
    							<?php }?>
                        	      	 <h4 class="transaction_num white" data-toggle="tooltip"><?php if(isset($result_transaction['ordered_num'])) {echo $result_transaction['ordered_num'];}?>, </h4>
                        	    <h4 class="favorite_num white" data-toggle="tooltip"><?php echo $result_favorite['favorite_num'];?>)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php 
                            if($product['id'] !=""){
								$_SESSION['mm_id'] = $product['id'];
							}
							else if($_SESSION['mm_id'] != "")
							{
								$_SESSION['mm_id'];
							}
							else
							{
							header("location:merchant_find.php");
							}
						?>
						 <input onclick="window.location.href = 'view_merchant.php';" type="button" class="btn btn-block btn-primary col-md-5" name="Menu" value="<?php echo $language["menu"];?>" style="margin-top: 0.5em;"> 
						 <input onclick="window.location.href = 'payment_menu.php';" type="button" class="btn btn-block btn-primary col-md-5" name="Payment" value="<?php echo $language["payment"];?>"> 
						 <input onclick="window.location.href = 'rating_list.php';" type="button" class="btn btn-block btn-primary col-md-5" name="Rating" value="<?php echo $language["rating"] ?>">
						 <input onclick="window.location.href = 'about_menu.php';" type="button" class="btn btn-block btn-primary col-md-5" name="About us" value="<?php echo $language["about_us"];?>">
                         <input onclick="window.location.href = 'location.php?address=<?php echo  $_SESSION['address_person'] ?>';" type="button" class="btn btn-block btn-primary col-md-5" name="location" value="<?php echo $language['location'];?>">
						 </div>
                <?php } ?>
                	<input type="hidden" class="merchant_id" value="<?php echo $mar_id;?>">
                    <input type="hidden" class="user_id" value="<?php echo $_SESSION['login'] ?>">
</div><!--content wrapper--->
<?php include("includes1/footer.php"); ?>
</div><!--wrapper--->

</body>

</html>

	
<style>
.col-md-5 {
    float: left;
    margin-right: 15px;
    margin-left: 20px;
}
.heading {
    font-size: 18px;
    font-weight: 600;
}
.logo_head {
    font-size: 18px;
    font-weight: 600;
}
main.main-wrapper.clearfix{    
    background: url(../images/background/menss.jpg); 
    background-size: cover; 
    background-repeat: no-repeat; 
}
h4.favorite_name {    color: white;    background: #0000003b;}
.hint span{
    background: #fff;
    color: black;
    font-size: 10px;
    position:relative;
    border-radius:0.8em;
    padding: 6px;
    
}
.hint span .tri_div{
    content: '';
    position:absolute;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 24px solid #fff;
    top: 55px;
}
.hint span .tri_div.trail2{
    top: 55px;
    display:none;
}

@media only screen and (max-width: 400px) and (min-width: 300px){
	.test_fav_3 {
    width: 90px!important;
    margin-left: 0px!important; 
}

i.heart.fa.fa-heart {
	padding-right: 0px!important; 
}
.test_fav_2 {
    width: 85px!important;
     margin-left: 0px!important; 
}
.test_fav_1 {
    width: 73px!important;
    margin-left: 0px!important; 
}
.trail_test {
        top: 70px!important;
    left: 45px!important;
}
.tri_div.trail2.test_mobile {
    left: 80px!important;
    top: 71px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-50deg);
    border-top: 56px solid #fff!important;
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
    left: 40px!important;
}
.tri_div.test_fav1 {
    left: 32px !important;
    border-top: 20px solid #fff!important;
}
}
@media only screen and (max-width: 650px) and (min-width: 600px){
.test_mobile {
         border-top: 42px solid #fff!important;
    left: 57px!important;
    top: 48px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-30deg);
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
	border-top: 38px solid #fff!important;
    left: 10px!important;
}
}
@media only screen and (max-width: 600px) and (min-width: 400px){
	.test_fav_3 {
    width: 90px!important;
    margin-left: 0px!important; 
}
.favorite .favorite_icon i {
    padding-top: 15px;
    font-size: 30px;
    margin-right: 10px;
}
i.heart.fa.fa-heart {
    padding-right: 17px;
}
h4.starting-bracket.white {
    margin-left: -16px;
}
.test_fav_2 {
    width: 85px!important;
     margin-left: 0px!important; 
}
.test_fav_1 {
    width: 73px!important;
    margin-left: 0px!important; 
}
.trail_test {
        top: 70px!important;
    left: 45px!important;
}
.tri_div.trail2.test_mobile {
    left: 58px!important;
    top: 71px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-50deg);
    border-top: 56px solid #fff!important;
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
    left: 40px!important;
}
.tri_div.test_fav1 {
    left: 32px !important;
    border-top: 20px solid #fff!important;
}

}

@media only screen and (max-width: 900px) and (min-width: 600px){
.test_fav_1 {
    width: 80px!important;
    margin-left: 0px!important;
}
i.heart.fa.fa-heart {
    padding-right: 0px!important;
}
.test_fav_2 {
    width: 105px!important;
    margin-left: 0px!important;
}
.test_fav_3 {
    width: 96px!important;
    margin-left: 0!important;
}
.test_mobile {
       border-top: 40px solid #fff!important;
    left: 84px!important;
    top: 66px!important;
    transform: rotate(-30deg);
    -ms-transform: rotate(-30deg);
    -webkit-transform: rotate(-30deg);
}
.tri_div.trail2.trail_test {
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 64px!important;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
	border-top: 38px solid #fff!important;
    left: 10px!important;
}
.tri_div.test_fav1 {
    left: 31px!important;
    border-top: 20px solid #fff!important;
}
}
@media only screen and (max-width: 900px) and (min-width: 360px){
    .favorite .favorite_icon i {
        padding-top: 15px;
        font-size: 30px;
    }
    .starting-bracket{
        font-size: 30px;
    }
    .nature_image{
        width: 30px;
        height: 30px;
        margin-top: -10px !important;
    }
    .transaction_num{
        font-size: 18px;
    }
    .favorite_num{
        font-size: 18px;
    }
    .main-wrapper{
        padding: 0 1rem 2.5rem;
    }
    
    
    /*.kType{
        font-size: 12px;
    }*/
}

.welccc_nottt {
    color: #fff;
    background: #00000012;
    margin-bottom: 12px;
    width: 200px;
}
.test_fav_1 {
    width: 100px;
    margin-left: 8px;
}
.test_fav_2 {
    width: 150px;
    margin-left: 8px;
}
.test_fav_3 {
    width: 150px;
    margin-left: 8px;
}
i.heart.fa.fa-heart {
    padding-right: 20px;
}
.tri_div.trail2.trail_test {
    
    transform: rotate(35deg);
    -ms-transform: rotate(35deg);
    -webkit-transform: rotate(35deg);
    top: 50px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 35px solid #fff;
 } 
@media only screen and (max-width: 1400px) and (min-width: 1200px){
.tri_div.trail2.test_mobile {
    left: 144px!important;
}
.tri_div.trail2.trail_test {
    transform: none;
    -ms-transform: none;
    -webkit-transform: none!important;
    top: 50px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 29px solid #fff;
    left: 15px!important;
}
}
</style>
 

<script src="js/jquery.min.js"></script>
<script>
    $(function(){
    
      setTimeout(function(){  
          $("#test_wel_not").hide();
      }, 5000);
    
    });
    $(document).ready(function(){
       /* $(".transaction_num").hover(function(e){
            $(".hint").css("display", "block");
            //$('[data-toggle="tooltip"]').tooltip(); 
        }, function(e){
            $(".hint").css("display", "none");
        });*/
        //
    });
</script>



<!-- USER MANAGEMENT CODE -->

<!-- Refer to User Management guide for this code -->

<!-- LAUNCH COMETCHAT CODE -->

