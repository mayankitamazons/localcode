<?php
include("config.php");
// Start of Hire's work
// Load merchant's product with QR
if(!empty($_GET['sid'])){
    $sid = $_GET['sid'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$sid' and user_roles='2'"));
    $merchant_name = $product['name'];
    $_SESSION['invitation_id'] = $product['referral_id'];
    $_SESSION['merchant_id'] = $product['id'];
    $_SESSION['address_person'] = $product['address'] ;
    $_SESSION['latitude'] = $product['latitude'] ; 
    $_SESSION['longitude'] = $product['longitude'] ;
    $_SESSION['IsVIP'] = $product['IsVIP'] ;
    $_SESSION['mm_id']= $product['id'];
     
} 
// End of Hire's work
$bank_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
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
<!--
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
-->

<head>
    <?php include("includes1/head.php"); ?>
	<style>
		.other_products {
    display: flex;
}
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
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	.well {
    width: 100% !important;
    min-height: 20px;
    background-color: transparent!important;
    border: 0px solid #e3e3e3!important;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}
	.well form{
	    min-height: 280px;
	}
	.pro_name
	{
	 text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
    height: 60px;
    }
    .about_mer {
    width: 100%;
}

 .input-controls {
      margin-top: 10px;
      border: 1px solid transparent;
      border-radius: 2px 0 0 2px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      height: 32px;
      outline: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    #searchInput {
      background-color: #fff;
      font-family: Roboto;
      font-size: 15px;
      font-weight: 300;
      margin-left: 12px;
      padding: 0 11px 0 13px;
      text-overflow: ellipsis;
      width: 50%;
    }
    #searchInput:focus {
      border-color: #4d90fe;
    }
    input.quatity {
    width: 90px;
}
.common_quant {
    display: flex;
}
p.quantity {
    margin-top: 7px;
}
.order_product{
    margin-top: 15px;
    margin-left: 10px;
    font-size: 20px;
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 10px;
}
.comm_prd{
    border: 1px #000000 solid;
    padding-left: 15px;
    margin-bottom: 10px;
}
.mBt10{
    margin-bottom: 10px;
}
@media only screen and (max-width: 767px) and (min-width: 300px)  {
    .new_grid{
      grid-template-columns: 1fr 1fr !important;
    }

    .text_add_cart {
        background: #003A66;
        width: 109px;
        text-align: center;
        padding: 10px;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        /* margin-right: 8px; */
        border-radius: 8px;
        margin-left: -10px;
    }
	 .master_category_filter{
        font-size: 1.2rem;
        line-height: 0.8rem;
        margin-bottom: 5px !important;
        padding: 0.5rem 0.5rem;
    }
    .category_filter{
        font-size: 1.2rem;
        line-height:0.8rem;
        margin-bottom: 5px !important;
        padding: 0.4rem 0.9em;
    }
    .order_product{
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 18px;
        padding-left: 5px;
        padding-right: 5px;
    }
    .oth_pr{
        margin-top: 20px !important;
    }

}
.nature_image {
   width: 40px;
   height: 40px;
}


@media only screen and (max-width: 600px) and (min-width: 300px)  {

	.sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }

    .oth_pr{
        margin-top: 26px!important;
    padding: 6px!important;
    }
}

@media only screen and (max-width: 500px) and (min-width: 400px)  {
     .well{
        padding-top: 0px !important;
     }
     .pro_name {
         font-size: 18px;
         margin: 10px 0px 0px;
         height: 35px;
     }
     .set_calss.input-has-value {
        width: 180px;
     }
     
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
  .new_grid{
    grid-template-columns: 1fr 1fr !important;
  }
     .well{
        padding-top: 0px !important;
     }
h4.head_oth {
    font-size: 20px;
}
     .pro_name {
        text-align: center;
        font-size: 14px;
        overflow: hidden;
        /* white-space: nowrap; */
        height: auto;
        /* width: 100px; */
        line-height: 15px;
     }
     .text_add_cart {
         margin: 5px 0px;
         padding: 7px;
     }
     .common_quant {
        display: block;
     }
     .text_add_cart {
         background: #003A66;
         width: 109px;
         text-align: center;
         padding: 10px;
         color: #fff;
         text-transform: uppercase;
         font-weight: 600;
         cursor: pointer;
         /* margin-right: 8px; */
         border-radius: 8px;
         margin-left: -10px;
     }
     .mBt10{
         margin-bottom: 2px;
     }
     .nature_image {
       width: 25px;
       height: 25px;
    }
    .starting-bracket{
        margin-top: 0.8rem;
    }
}
@media only screen and (max-width: 600px) and (min-width: 300px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px!important;
    }
   .text_add_cart {
        padding: 6px;
   }

   .row#main-content {
        margin-right: 0px;
        margin-left: 0px;

    }
    .oth_pr{
	height: 40px;
	}
}
@media only screen and (max-width: 1050px) and (min-width: 992px)  {
   .text_add_cart{width: 100px}
   .text_add_cart {
       width: 125px;
       margin: 0 auto;
   }
   p.quantity {
        margin-left: 35px;
   }
   .common_quant {
        display: block;
   }
   input.quatity {
        width: 130px;
   }
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
   .set_calss.input-has-value {
        width: 173px;
   }
   .about_uss {
        width: 165px;
   }
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
   input.btn.btn-block.btn-primary.submit_button {
        width: 100%!important;
   }
   p.test_testing {
        margin: 2px;
   }
   .text_add_cart {
        margin: 5px auto;
   }
   input.quatity {
        width: 118px;
   }
   .well {
        min-height: 20px;
        padding: 0px 0 0;
   }
   .common_quant {
        display: block;
   }
   .set_calss.input-has-value {
        width: 160px;

   }
   .grid.row {
        margin-left: 18px;
   }
   p {
        margin-bottom: 0;
   }
}

@media only screen and (max-width: 800px) and (min-width: 750px)  {
   .sidebar-expand .main-wrapper {
        margin-left: 0px;
   }
   .pro_name{
       margin-bottom: 0.4em;
       font-size: 18px;
       overflow: hidden;
       white-space: nowrap;
   }
   .common_quant {
        display: block;
   }
   p {
        margin-bottom: 0.4em;
   }
}
@media only screen and (max-width: 800px) and (min-width: 650px)  {
   .common_quant {
        display: block;
   }
   .text_add_cart {
        width: 142px;
   }
}

/* Edited by Sumit */
@media (min-width:768px) and (max-width:1150px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  label {
      font-weight: 600;
      width: 100%;
  }
  .fjhj br {
      display: none;
  }
  .master_category_filter{
      background-color: #545c73;
      border-color: #4a5368;
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
  }
  .master_category_filter:focus, .master_category_filter.focus {
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 0 3px rgba(74, 83, 104, 0.5);
  }
  .master_category_filter:hover {
      color: #fff;
      background-color: #4a5368;
      border-color: #545c73;
  }
}
@media (min-width:200px) and (max-width:767px){
  .total_rat_abt {
      font-size: 14px!important;
      display: flex;
  }
  .well {
      min-height: 20px;
      background-color: transparent!important;
      border: 0px solid #e3e3e3!important;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  }
  .fjhj br {
      display: none;
  }
}

.fjhj br {
    display: none;
}
label {
    font-weight: 600;
    width: 100%;
}
/* Edited by Sumit  */
.introduce-remarks{
  height: 28px;  
  line-height: 12px;
}
input[name='p_total[]'],input[name='p_price[]']{
  text-align: right;
}

/* Style for new products layout */

.new_grid{
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 40px;
  grid-row-gap: 20px;
}

/* End of Style for new products layout */
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

                    <?php
                    //vandhu

                  $id = $_SESSION['mm_id'];

                    
			
                    $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$id."'"));
                    if( isset($_SESSION['login']) ) {
                        $sql_transaction = "SELECT COUNT(id) ordered_num
                            FROM order_list
                            WHERE user_id='".$_SESSION['login']."' and merchant_id = '".$id."' AND STATUS='1'";
                        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));    
                    } else {
                        $result_transaction = '';
                    }
                    
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

                    //FETCH PRODUCT BY USING MERCHAT ID Amit
                    
                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$id."' and status=0"));
                    $total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0");                    
                    $favorite = isset($_SESSION['login']) ? mysqli_query($conn, "SELECT * FROM favorities WHERE user_id = ".$_SESSION['login']." AND favorite_id = ".$id."") : '';
                    $count = $favorite != '' ? mysqli_num_rows($favorite) : 0;
                    ?>
                    
                    
                    <main class="main-wrapper clearfix" style="min-height: 522px;">
                    <div class="row" id="main-content" style="padding-top:25px">
                    <?php
                    if($_SESSION['IsVIP'] ==1){ 
                      ?>
                    
                        <div class="box-right">
                            
                            <div class="title">
                                <div class="title-left"> <img src="new/images/merchant.png"> <div class="title-h">  <a href="#"> Merchant Name : <?php echo $merchant_detail['name'];?></a> </div>  </div> 
                            <div class="title-right"> 
                                <div class="favorite_icon">
                                <?php if($count > 0) {?>
                                <i class="heart fa fa-heart"></i>
                                <?php } else {?>
                                <i class="heart fa fa-heart-o"></i>
                                <?php }?>
                                
                                <h4 class="starting-bracket" style="display: inline-block;">(</h4>
                                <?php if($business1 != ""){ ?>
                                <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business1;?>">
                                <?php }?>
                                <?php if($business2 != ""){ ?>
                                <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business2;?>">
                                <?php }?>
                                <?php if($merchant_detail['account_type'] != ''){?>
                                <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
                                <?php }?>
                                <h4 class="transaction_num"><?php echo $result_transaction['ordered_num'];?>, </h4>
                                <h4 class="favorite_num"><?php echo $result_favorite['favorite_num'];?>)</h4>
                                </div>
                                </div>
                            </div> 
                        </div> 
                        <div class="cont-area3"> 
                        <div class="white-box">	
                        <div class="btns">
                        <div class="main-btn"> <a  href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a> </div>
                        <div class="main-btn1">  <a  href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </div>
                        <?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
                        <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a>
                        <?php }?>
                        </div>
                        
                        
                    
                        <div class="clear-both"> </div>
                        
                        <div class="head-title">Merchant</div>
                        <div class="main-cont">
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently.
                        </div>
                        
                        
                        <div class="grey-bg"> 
                        <div class="grey-left">Merchant Name  </div> 
                        <div class="grey-right"> Product No : 0 </div> 
                        </div> 
                        
                        </div>
                        </div>
                    </div> 
                    
                    <?php }else{ ?> 
                    <div class="col-md-12">
                    <div class="total_rat_abt">
                    <div class="about_uss"><a class="merchant_about" href="<?php echo $site_url; ?>/about_menu.php"><?php echo $language["about_us"]?></a></div>
                    <div class="rating_menuss"><a class="merchant_ratings" href="<?php echo $site_url; ?>/rating_list.php"><?php echo $language["rating"]?> </a></div>
                    <div class="rating_menuss"><a class="merchant_ratings" href="<?php echo $site_url; ?>/location.php?address=<?php echo  $_SESSION['address_person'] ?>"><?php echo $language["location"]?> </a></div>

                     <div class="rating_menuss"><a class="merchant_ratings" onclick="our_stall()">Our Stalls </a></div>
                    
                    <?php if(isset($_SESSION['invitation_id']) && (!isset($_SESSION['login']))){?>
                    <a class="col-md-2" href="signup.php?invitation_id=<?php echo $_SESSION['invitation_id'];?>"><img src="img/join-us.jpg" style="width: 100px;"></a>
                    <?php }?>
                    </div>
                    <h2 style="width:200px;">Merchant</h2>
                    </div>
                    
                    
                    <div class="col-md-12 row favorite" style="margin-left:15px; margin-bottom: 10px; padding-left:0px;" >
                    <div style="clear:both;">
                    <h4 class="favorite_name" style="display: inline-blick;">
                    <a href="javascript:jqcc.cometchat.launch({uid:' <?php echo $merchant_detail['id'];?>'});">Chat with <?php echo $merchant_detail['name'];?> </a></h4>
                    </div>
                    <h4 class="favorite_name" style="display: inline-blick;">Name: <?php echo $merchant_detail['name'];?></h4>
                    <div class="favorite_icon">
                    <?php if($count > 0) {?>
                    <i class="heart fa fa-heart"></i>
                    <?php } else {?>
                    <i class="heart fa fa-heart-o"></i>
                    <?php }?>
                    </div>
                    <h4 class="starting-bracket" style="display: inline-block;">(</h4>
                    <?php if($business1 != ""){ ?>
                    <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business1;?>">
                    <?php }?>
                    <?php if($business2 != ""){ ?>
                    <img style="margin-top:10px;" class="nature_image" src="/img/<?php echo $business2;?>">
                    <?php }?>
                    <?php if($merchant_detail['account_type'] != ''){?>
                    <h4 class="transaction_num"> <?php echo $merchant_detail['account_type'];?>, </h4>
                    <?php }?>
                    <h4 class="transaction_num"><?php if( isset( $result_transaction['ordered_num'] ) ) {echo $result_transaction['ordered_num'];}?>, </h4>
                    <h4 class="favorite_num"><?php if( isset($result_favorite['favorite_num']) ) {echo $result_favorite['favorite_num'];}?>)</h4>
                    </div>
                    <div class="comm_prd">
                    <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>
                    <button class="oth_pr" id="oth_pr" >
                    Order
                    </button>
                    </div>
                     <?php
                        if($merchant_detail['menu_type']==2){
                            include 'view_merchant_layout2.php';
                        } else {
                            include 'view_merchant_layout1.php';
                        }
                    ?>
                   
                   
                    <?php 
					/** 
                    $categories = mysqli_query($conn, "SELECT DISTINCT(products.category) FROM products WHERE user_id ='".$id."' and status=0 ORDER BY created_date ASC");
                    if($product['pro_ct'] > 0) { ?>
                    <div class="col-md-12 filter-button-group">
                    <?php
                    $index = 0;
                    $category = "";
                    while ($row=mysqli_fetch_assoc($categories)){
                        if($row['category'] == "") continue;
                        if($index == 0) $category= $row['category'];
                        $index++;
                    ?>
                    <button class="btn btn-primary category_filter" type="button" data-filter=".<?php echo $row['category'];?>"><?php echo str_replace("-", " ", $row['category']);?></button>
                    <?php }?>
                    </div>
                    
                    <div class="grid row">
                    
                    
                    <?php
                    while ($row=mysqli_fetch_assoc($total_rows)){
                    ?>
                    <?php   if(!empty($row['image'])) { ?>
                    
                    <div class="well col-md-4 element-item <?php echo $row['category'];?>" >
                    <form action="product_view.php" method="post" class= "set_calss" data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" style="background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                    <div class="container_test"> <?php
                    if(!empty($row['image']))
                    { ?>
                    
                    <img src="<?php echo $site_url; ?>/images/product_images/<?php echo $row['image'];  ?>" width="100%" height="150px" class="make_bigger">
                    </a>
                    
                    
                    <?php  }
                    else
                    { ?>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="100%" height="150px" class="make_bigger">
                    <?php }
                    ?></div>
                    <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
                    <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">
                    
                    <p class ="pro_name"><?php echo $row['product_name']; ?></p>
                    <p class="mBt10"><?php echo 'Code: '.$row['product_type']; ?></p>
                    <p class="mBt10"><?php echo $row['remark']; ?></p>
                    <!--	<p><?php echo 'Category : '.str_replace("-", " ", $row['category']); ?></p>-->
                    <p class="mBt10"></p><?php echo 'Price : Rm'.number_format((float)$row['product_price'], 2, '.', ''); ?></p>
                    <!--
                    <p ><?php //echo 'Remark : '.$row['remark']; ?></p>
                    -->
                    <!--
                    <p class="quantity">
                    <label>Quantity</label>
                    <input type="text" class="quatity" name="quatity">
                    </p>
                    -->
                    <div class="common_quant">
                    <p class="text_add_cart"  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" data-name = "<?php echo $row['product_name'] ?>">Add to Cart</p>
                    <p class="quantity">
                    <!--
                    <label>Quantity</label>
                    -->
                    <label>X</label>
                    <input type="number" value="1" class="quatity" name="quatity">
                    </p>
                    </div>
                    
                    </form>
                    </div>
                    
                    
                    <?php  } } ?>
                    </div>
                    <?php 
                    } **/
                    ?>
                </div>
        
        <!-- without picture--->
        <?php
        $total_rows1 = isset($category) ? mysqli_query($conn, "SELECT * FROM products WHERE category = '".$category."' and user_id ='".$id."' and status=0") : [];
        ?>
        <div class="without_picture">
        
        <table class="table table-striped" id="without_table">
        <thead>
        <tr>
         <th>S.no</th>
         <th>Product Name</th>
         <th>Action</th>
         <th>Price</th>
         <th>Remark</th>
         <th>Code</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i =1;
        while ($row=mysqli_fetch_assoc($total_rows1)){
          if($row['image'] == '') {
        
        
        ?>
        	<tr>
        	    <td><?php echo $i; ?> </td>
        	    <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
                <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">
        		<td><?php echo $row['product_name']; ?></td>
        		<td class="text_add_cart_without"  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo number_format((float)$row['product_price'], 2, '.', ''); ?>" data-name = "<?php echo $row['product_name'] ?>" id="text_without">Add to Cart</td>
        		<td><?php echo number_format((float)$row['product_price'], 2, '.', ''); ?></td>
        		<td><?php echo $row['remark']; ?></td>
        		<td><?php echo $row['product_type']; ?></td>
        		
        		</tr>
        <?php  $i++; }
        ?>
        <?php } ?>
        </tbody>
        </table>
        </div>
        
        <div class="comm_prd">
        <h4 class="head_oth"><?php echo $language["order_direct"];?></h4>
        <button class="oth_pr demoorder" id="oth_pr">
          Order
        </button>
        
        </div>
        </main>
        </div>
        <!-- adding new-->
        <?php $profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
        ?>
        <main class="main-wrapper clearfix" style="min-height: 522px;">
        <?php if( !isset($profile_data['user_roles']) || $profile_data['user_roles'] ==  '') { ?>
        <form method="post" action="guest_user.php">
        <?php
        $stl_key = rand();
        $_SESSION['stl_key'] = $stl_key; ?>
        <input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">
        
        
        <?php } else { ?>
        <form method="post" action="order_place.php">
        <?php } ?>
        
       <table class="table table-striped" id="cartsection">
        <thead>
        <tr> 
        <th></th>
        <th><?php echo ucfirst(strtolower($language["product_name"])); ?></th>
        <th>Qty</th>

        <th><?php echo ucfirst(strtolower($language["product_code"])); ?></th>
        
        <th><?php echo ucfirst(strtolower($language["remark"])); ?></th>
        
		<th>Unit Price</th>
        <th>Total</th>
        </tr>
        <tbody id="test"> </tbody>
        </thead>
        
        </table>
      <!--  <div class="row col-md-8" style="float:right;width:40%">
          <div class="col-md-3"><label class="head_loca">Discounted amount</label></div>
         <div class="col-md-5"> <input type="text" class="form-control table" name="table_type" value="<?php  ?>" readonly /></div><br><br>
       </div>-->
        
        <a href="#main-content"><p class="" style="width: 110px !important;font-size: 16px;padding:14px;background-color: #003A66;color: white; font-weight: bold; border-radius: 8px;"> Add more order </p></a> <br/>
        
        
        <div class="location_merchant">
        <label class="head_loca"><?php echo ucfirst(strtolower($language["location"])); ?></label>
        <div class="name_mer">
        <input type="hidden" name="latitude" value="<?php echo $merchant_detail['latitude'];?>">
        <input type="hidden" name="longitude" value="<?php echo $merchant_detail['longitude'];?>">

        <?php 
        // --------------------
        // Start of Hire's code 
        // --------------------
        if(isset($_GET['data']))
        {
          $getdetail=$_GET['data'];
         $getdetail=base64_decode($getdetail);
          $epxplode=explode("hweset",$getdetail);
          $section=$epxplode[0];
          $tablenumber=$epxplode[1];
        }

        if($_SESSION['more_order']=="moreOrder")
        {
           $sql = "SELECT MAX(id) id
            FROM order_list
            WHERE user_id = ".$_SESSION['login']."";
           $invoice_no = mysqli_fetch_assoc(mysqli_query($conn, $sql))['id'];
           $sql3= mysqli_fetch_array(mysqli_query($conn, "select * from order_list where id='$invoice_no'"));

        }
        // --------------------
        // End of Hire's code 
        // --------------------
        ?>

          <input class="form-control comment" name="location" placeholder="location" value="<?php  echo $merchant_detail['google_map']; ?>" required><br><br>
		
	<div style="float:left;width:100%">
	
		<div style="float:left;width:50%">
        		<label><?php echo $language["table_number"];?></label>
       		 	<input type="text" class="form-control table" name="table_type" value="<?php if($_SESSION['more_order'] !=null){echo $sql3['table_type'];} ?>" required /><br><br>
		</div>
		
		<div style="float:left;width:50%;margin-top:20px;">
		 		<label>Section <br></label>
       			 <input type="text" class="form-control table" name="section_type" value="<?php if($_SESSION['more_order'] !=null){echo $sql3['section_type'];} ?>" required /><br><br>
		</div>
		
	</div>	

        <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
        <input type="hidden" name="options" value="" />  
        <input type="submit" class="btn btn-block btn-primary submit_button" id="confm" name="submit" value="<?php echo $language["confirm_order"];?>"><br><br>
        
        </div>
        </div>
        <a href="#cartsection"><img src ="images/shopping-14-512.png" style="width:90px;height:90px;position: fixed;right: 10px;bottom: 70px;"></a>
        </form>
        <?php 
        // ---------------------------
        // Start of DrakkoFire's code 
        // Remark Project
        // ---------------------------
        ?>
        <div id="remarks_area" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Remarks</h4>
                  </div>
                  <div class="modal-body">
                    <?php 
                      $ingredients = explode(",",mysqli_fetch_row(mysqli_query($conn,"SELECT preset_words FROM users WHERE id='$id'"))[0]);
                      foreach ($ingredients as $ingredient) {
                        if(!empty($ingredient)){
                          ?>
                           <div style="margin: 10px 0;" class="btn-group" data-toggle="buttons">
                              <label class="btn btn-secondary">
                                <input type="checkbox" name="ingredient" value="<?php echo $ingredient; ?>" autocomplete="off"> <?php echo str_replace("_", " ", $ingredient); ?>
                              </label>
                            </div>
                            <?php
                        }
                      }

                    ?>
                    <input type="hidden" name="remark" id="remark_input" class="form-control" style="margin: 10px 0" placeholder="Write here your remarks"/>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-success save_close" data-dismiss="modal">Save and close</button>
                      <button type="button" class="btn btn-default manual_input">Manual input</button>
                  </div>
              </div>
          </div>
        </div>
        <?php 
        // ---------------------------
        // Remark project
        // End of DrakkoFire's code 
        // ---------------------------
        ?>
</main>
<!-- /.widget-body badge -->
</div>
    <!-- /.widget-bg -->
<?php } ?>
    <!-- /.content-wrapper -->

    <?php include("includes1/footer.php"); ?>




</body>

</html>


<!----amit----->

<script type="text/javascript">

  function our_stall(){

      $('#our_stall').modal('show');

   
  }

 function  getId(element) {


     // alert("row" + element.closest('tr').rowIndex);
      var id = $(element).closest('tr').find('td:first-child').text();
      $("#merchant_id").val(id);
    $("#sub_mer_form").submit();
    
}
  

</script>

<div class="modal fade" id="our_stall" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sub Merchant List of <?php echo $merchant_detail['name'];?> </h4>
                </div>
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-sm-10">
                            <div class="form-group">
                       

                              <div class="container">
                              
                                <table class="table table-striped submer">
                                  <thead>
                                    <tr>
                                      <th>Submerchant Name</th>
                                      <th>Favorite</th>
                                    </tr>
                                  </thead>
                                 
                                  <tbody> 
                                    <form id="sub_mer_form" action="structure_merchant.php" method="post">

                                      <?php 
                                            $sql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$merchant_detail['name']."' ");
                                            
                                            while($data = mysqli_fetch_array($sql))
                                             {
                                            
                                             $fav=mysqli_query($conn, "SELECT count(*) as number FROM `favorities` WHERE `favorite_id`='".$data['id']."'");
                                             $cu=mysqli_fetch_array($fav);

                                             $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE name='".$data['name']."' ");
                                             $m =mysqli_fetch_array($sql2);
                                            echo'<tr value='.$m['id'].' onclick="getId(this)"><td>'.$data['name'].' </td><td>( '.$cu['number'].' ) <i style="font-size: 17px;" class="heart fa fa-heart"></i> </td><input type="hidden" name="merchant_id" id="merchant_id" value=""><input type="hidden" name="sub_mer_id" id="sub_mer_id" value="sub_mer_id"></tr>';
                                            
                                              }

                                            ?>
                                           
                                     </form> 
                                  
                                  </tbody>
                                 
                                </table>
                              </div>



                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                        <!--<button type="button" class="btn btn-primary" data-dismiss="modal">No</button> <a href="view_merchant.php"><input type="button" class="btn btn-primary" value="Yes"></a>-->
                    </div>
                </form>
            </div>
        </div>
 </div>

<!----end amit------>

  <script>
    // Start of DrakkoFire's code
    // Remark

    $('#remarks_area').on('hide.bs.modal', function (e) {
        $("#remark_input").attr("type","hidden").val('');
    });
    $('#remarks_area').on('click','.save_close', function (e) {
        var selected = [];
        $('div#remarks_area .btn-secondary.checkbox-checked.active').each(function() {
            selected.push($(this).children("input[name='ingredient']").val());
        });
        // console.log(selected);
        if($("#remark_input").val() != ''){
          selected.push($("#remark_input").val().split(' ').join('_'));
        }
        // console.log(selected.toString().split("_").join(" "));
        $("a.introduce-remarks.selected").removeClass("selected").html((selected.toString() == '') ? "Remarks" : selected.toString().split("_").join(" "));
        $("input[name='ingredients'].selected").val('').val(selected).removeClass("selected");
    });
    $(".manual_input").click(function(e){

      if($("#remark_input").attr("type") == "hidden"){
        $("#remark_input").attr("type","text");
      }else if($("#remark_input").attr("type") == "text"){
        $("#remark_input").attr("type","hidden").val('');
      }

      e.preventDefault();
    });
    $("input[type='submit']").click(function(e){
      var remarks = [];
      $('input[name="ingredients"]').each(function() {
           if ($(this).val() != '') {
             remarks.push($(this).val());
           }
        });
      var result = '';
        for (var i = 0; i <= remarks.length - 1; i++) {
          console.log(remarks[i]);
          if(i != remarks.length - 1){
            result += remarks[i] + "|";
          }else{
            result += remarks[i];
          }
        }
      $("input[name='options']").val(result);
      console.log(result);
    });
    $("body").on("click",".introduce-remarks", function(e){
      $(this).addClass("selected");
      $(this).parent().parent().find("input[name='ingredients']").addClass("selected");
      var ingredients = $(this).parent().parent().find("input[name='ingredients']").val();
      $("#remarks_area .modal-body .btn-group .btn-secondary").removeClass("checkbox-checked").removeClass("active");
      e.preventDefault();
    });

    // Remark
    // End of DrakkoFire's code
	$(".text_add_cart").on("click", function(){
		var id = $(this).data("id");
		var code = $(this).data("code");
		var p_price = $(this).data("pr");
		var name = $(this).data("name");
		var quantity = $(this).closest("form").find("input[name='quatity']").val();
        
		var p_total = p_price*quantity;
    p_total = p_total.toFixed(2);
        
    	$("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]' maxlength='3'  value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= "+p_total+" readonly  id='"+id+"_cat_total'></td> </tr>");
		alert('The product added');
	});

	$(".text_add_cart_without").on("click", function(){
		var id = $(this).data("id");
		//~ alert(id);
		var code = $(this).data("code");
		//~ alert(code);
		var p_price = $(this).data("pr");
		//~ alert(p_price);
		var name = $(this).data("name");
		// alert(name);
		var quantity = 1 ;
		//alert(quantity) ;
		if(quantity ==''){
		    
		    var quantity = 1 ;
		}
		var p_total = p_price *quantity ;
    p_total = p_total.toFixed(2);

		$("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:50px;' maxlength='3'  onchange='UpdateTotal("+id+" ,"+p_price+")'  type=number name='qty[]' value="+quantity+" id='"+id+"_test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type='hidden' name='ingredients'/></td><td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a></td>  <td><input style='width:70px;' type='text' name='p_price[]' value= "+p_price+" readonly></td><td><input type='text' style='width:70px;' name='p_total[]' value= "+p_total+" readonly  id='"+id+"_cat_total'></td> </tr>");
		alert('The product added');
	});
  var other_product_id = 1;
	 $(".oth_pr").on("click", function(){
		   $('html,body').animate({
        scrollTop: $("#cartsection").offset().top},
        'slow');  
      	   
		$("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td><input style='width:120px;' type=text  id='other_product_name_"+other_product_id+"' class='other_product_name'><input type='hidden' name='p_id[]' id='other_product_id_"+other_product_id+"'></td> <td><input style='width:50px;' onchange='UpdateTotalCart("+other_product_id+")' id='other_qty_"+other_product_id+"' type=number name='qty[]' value='1'></td> <td><input class='other_product_code' style='width:70px;' type= text name='p_code[]' id='other_product_code_"+other_product_id+"'><input type='hidden' name='ingredients'/></td><td> <a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a></td><td><input style='width:70px;' id='other_product_price_"+other_product_id+"' type='text' name='p_price[]' readonly></td><td><input type='text' style='width:70px;' name='p_total[]' readonly  id='"+other_product_id+"_cat_total'></td></tr>");
      var focus_id="other_product_code_"+other_product_id;
	  document.getElementById(focus_id).focus(); 
	other_product_id++;
    jQuery(".other_product_name").autocomplete({
      source: "auto_complete_product_name.php",
      minLength: 1, 
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
		var qty_id='other_qty_'+id;
		var qty_no=document.getElementById(qty_id).value;
		 var total_cart=qty_no*(ui.item.price);
		 // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
		document.getElementById(cart_id).value =total_cart;
      }
    });
    jQuery(".other_product_name").keyup(function(e){
      var id = $(this).attr('id').split('_')[3];
      $("#other_product_id_"+id).val($(this).val());
    });
    jQuery(".other_product_code").autocomplete({
      source: "auto_complete_product_code.php",
      minLength: 1,
      select: function(event, ui) {
        var id = $(this).attr('id').split('_')[3];
       var qty_id='other_qty_'+id;
		var qty_no=document.getElementById(qty_id).value;
		 var total_cart=qty_no*(ui.item.price);
		 // alert(total_cart);
        $("#other_product_id_"+id).val(ui.item.id);
        $("#other_product_code_"+id).val(ui.item.code);
        $("#other_product_name_"+id).val(ui.item.name);
        $("#other_product_price_"+id).val(ui.item.price);
        $("#other_product_remark_"+id).val(ui.item.remark);
        var cart_id=id+"_cat_total";
		document.getElementById(cart_id).value =total_cart;
        
      }
    });

	});
	
     jQuery(document).on('click', 'button.removebutton', function () {
         alert("Product has Removed");
         jQuery(this).closest('tr').remove();
         return false;
     });

     $()
	</script>

<style>
.category_filter{
    margin-bottom: 10px;
}
.sub_category_grid{
    margin-top: 10px;
}
.other_products{
display:none;
}
.text_add_cart{
    background: #003A66;
    width: 120px;
    text-align: center;
    padding: 10px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-right: 8px;
    border-radius: 8px;
}
.text_add_cart_without{
    background: #003A66;
    width: 120px;
    text-align: center;
    padding: 10px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-right: 8px;
    border-radius: 8px;
}
.comm_prd {
    display: flex;
}
.oth_pr {
    background: #003A66;
    width: 80px;
    text-align: center;
    margin: 10px 10px;
    padding: 10px;
    color: #fff;
    font-size: 20px;
    text-transform: uppercase;
    font-weight: 500;
    cursor: pointer;
    border-radius: 8px;
}
.total_rat_abt {
    font-size: 17px;
    display:flex;
}
.rating_menuss {
   padding: 5px;
    margin-right: 25px;
    padding: 5px;
    background: #00736A;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    border-radius: 10px;
}
.about_uss {
    padding: 7px!important;
    margin-right: 25px;
    padding: 5px;
    background: #00736A;
    width: 120px;
    text-align: center;
    padding: 5px;
    color: #fff!important;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 10px;
    margin-right: 15px;
    border-radius: 10px;

}

a.merchant_about {
    color: #fff;
}
a.merchant_ratings {
    color: #fff;
}

</style>
<script>
$('.make_bigger').click(function() {
  //~ $('.active').not(this).addClass('non_active');
  $('.active').not(this).removeClass('active');
  if ($(this).hasClass('active')) {
    $(this).removeClass('active');
  } else {
    $(this).removeClass('non_active');
    $(this).addClass('active');
  }
});
// init Isotope
var $grid_sub = $('.sub_category_grid').isotope({
    // options
    layoutMode: 'fitRows'
});
var $grid = $('.grid').isotope({
  // options
});
// filter items on button click
$('.master_category_filter').on( 'click', function(e) {
    e.preventDefault();
    var filterValue = $(this).attr('data-filter');
    $grid_sub.isotope({ filter: filterValue });
});
$('.sub_category_grid .category_filter button').on( 'click',function() {
      var filterValue = $(this).attr('data-filter');
      console.log(filterValue);
      $grid.isotope({ filter: filterValue });
});
</script>
<style>
img.active {
  animation: make_bigger 1s ease;
  width: 600px;
  height: 400px;
}

img.non_active {
  animation: make_smaller 1s ease;
  width: 127px;
  height: 128px;
}
@media only screen and (max-width: 750px) and (min-width: 600px)  {
form.set_calss.input-has-value {
<!--
    width: 50%;
-->
    width: 173px;
}
.about_uss {
    width: 165px;
}
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
}
@media only screen and (max-width: 500px) and (min-width: 300px)  {
input.btn.btn-block.btn-primary.submit_button {
    width: 100%!important;
}
.common_quant {
    display: block;
}
form.set_calss.input-has-value {
<!--
    width: 100%;
-->
    width: 170px;
    margin-left: -20px;
}
.grid.row {
    margin-left: 18px;
}
/*.pro_name {
    height: 130px;
}*/
img.make_bigger {
    height: 100px;
}
}
@media only screen and (max-width: 800px) and (min-width: 750px)  {
.sidebar-expand .main-wrapper {
    margin-left: 0px;
}
.common_quant {
    display: block;
}
}
.col-md-4{
  max-width: 100% !important;
}
.well.col-md-4{
  padding: 0 !important;
}

</style>

<script>
$(document).ready(function(){
    //$('.master_category_filter:first-child').trigger('click');
    $('.sub_category_grid .category_filter:first-child button').trigger('click');
    //$('.filter-button-group .category_filter:first-child').trigger('click');
    $(".category_filter button").click(function(e){
        var filterValue = $(this).attr('data-filter');
        $("#without_table tbody").html("");  
		//alert(4);
        var data = {method:"getNoneImageProduct", id: <?php echo $id;?>, category: filterValue.substr(1, filterValue.length)};
        $.ajax({
             url:"functions.php",
             type:"post",
             data:data,
             dataType:'json',
             success:function(result){
                var html="";
                for(var i = 0; i < result.length; i++){
                    html += "<tr>";
                    html += "<td>"+(i + 1)+"</td>";
                    html += "<td>"+result[i]['product_name']+"</td>";
                    html += "<td id='text_without' class='text_add_cart_without' data-id='"+result[i]['id']+"' data-code='"+result[i]['type']+"' data-pr='"+result[i]['price']+"' data-name='"+result[i]['product_name']+"'>Add to cart</td>";
                    html += "<td>"+result[i]['price']+"</td>";
                    html += "<td>"+result[i]['remark']+"</td>";
                    html += "<td>"+result[i]['type']+"</td>";
                    html += "</tr>";
                }
                $("#without_table tbody").html(html);
            	$(".text_add_cart_without").on("click", function(){
            		var id = $(this).data("id");
            		//~ alert(id);
            		var code = $(this).data("code");
            		//~ alert(code);
            		var p_price = $(this).data("pr");
            		//~ alert(p_price);
            		var name = $(this).data("name");
            		//~ alert(name);
            		var quantity = $(this).closest(".well").find("input[name='quatity']").val();
            		if(quantity == null ){
            		    
            		    	var quantity = 1 ; 
            		    
            		}
            		$("#test").append("<tr>  <td><button type='button' class='removebutton'>X</button> </td><td>"+name+"</td><td><input style='width:70px;' type=number name='qty[]' value="+quantity+" id='test_athy'><input type= hidden name='p_id[]' value= "+id+"><input type= hidden name='p_code[]' value= "+code+"><input type= hidden name='p_price[]' value= "+p_price+"><input type='hidden' name='ingredients'/></td>  <td>"+code+"</td><td><a href='#remarks_area' role='button' class='introduce-remarks btn btn-large btn-primary' data-toggle='modal'>Remarks</a></td>  </tr>");
            		alert('The product added');
            	});
                
             }
         });
    });
});
</script>
<script>
// document.getElementById('oth_pr').onclick = function() {
    // document.getElementById('test_code').focus();
// };
// document.getElementById('text_without').onclick = function() {
    // document.getElementById('test_athy').focus();
// };

</script>


<script>
var chat_appid = '52013';
</script>
<?php 
?>

<?php 
	if(isset($_SESSION["merchant_id"]) && $_SESSION["merchant_id"] > 0) { ?>
	 <script>
		var chat_id = "<?php echo $_SESSION["merchant_id"]; ?>";
		var chat_name = "<?php echo $_SESSION["user_name"]; ?>"; 
		var chat_link = "<?php echo $_SESSION["user_link"]; ?>"; //Similarly populate it from session for user's profile link if exists
		var chat_avatar = "<?php echo $_SESSION["user_avatar"]; ?>"; //Similarly populate it from session for user's avatar src if exists
		var chat_role = "<?php echo $_SESSION["user_role"]; ?>"; //Similarly populate it from session for user's role if exists
		var chat_friends = '<?php echo $_SESSION["merchant_id"]; ?>'; //Similarly populate it with user's friends' site user id's eg: 14,16,20,31
		</script>
	<?php } ?>
<script>
(function() {
    var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
    document.getElementsByTagName("head")[0].appendChild(chat_css);
    var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
})();
</script>
<script type="text/javascript">
  var chat_appid = '52013';
  
  var chat_position = 'left';

  (function() {
    var chat_css = document.createElement('link'); chat_css.rel = 'stylesheet'; chat_css.type = 'text/css'; chat_css.href = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.css';
    document.getElementsByTagName("head")[0].appendChild(chat_css);
    var chat_js = document.createElement('script'); chat_js.type = 'text/javascript'; chat_js.src = 'https://fast.cometondemand.net/'+chat_appid+'x_xchat.js'; var chat_script = document.getElementsByTagName('script')[0]; chat_script.parentNode.insertBefore(chat_js, chat_script);
  })();
</script>

<script>
function UpdateTotal(id=0 , uprice= 0){
	var qty = $("#"+id+"_test_athy").val();
	//alert(qty);
	//alert(qty);
	var total =  parseFloat(Number(qty*uprice).toFixed(2));
	$("#"+id+"_cat_total").val(total);
}
function UpdateTotalCart(id=0){
	// var qty = $("#"+id+"_test_athy").val();
	var qty = $("#other_qty_"+id).val();
	var unitprize = $("#other_product_price_"+id).val();
	var total =  parseFloat(Number(qty*unitprize).toFixed(2));
	$("#"+id+"_cat_total").val(total);
	
	
}
</script>

<!-- amit -->


<script type="text/javascript">
    $(window).on('load',function(){

        var d = new Date();
  var weekday = new Array(7);
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var days = weekday[d.getDay()];
    var n = d.getDay();
     var days_count=0;
	 if(days_count>0)
	 {
      <?php
          $dayss= mysqli_fetch_assoc( mysqli_query($conn, "SELECT * FROM set_working_hr WHERE merchant_id='".$_SESSION['merchant_id']."'"));
         $start_day= $dayss['start_day'];
         $end_day= $dayss['end_day'];
        $day_s = date('N', strtotime($start_day));
        $day_e = date('N', strtotime($end_day));
         
       ?>

        var sd = "<?php echo $day_s; ?>";
        var ed = "<?php echo $day_e; ?>";
              if ((n >= sd) && (n <= ed))
              {
                 <?php
                 date_default_timezone_set('Asia/Kolkata');
                  $var = date('H:i');
                  $end= $dayss['end_time'];
                  $start = $dayss['start_time'];
             if(($start < $var) &&($end > $var))
                {
              ?>
                return true;

              <?php }else {?> 
                  $('#stru_merchant_model').modal('show');
                 $( "#confm" ).prop( "disabled", true );
                $("#oth_pr").attr('disabled',true);
                $(".demoorder").attr('disabled',true);
                
              
              <?php }?>
              
              }else
              {
                 alert("CLOSE AT THIS TIME");
                  $( "#confm" ).prop( "disabled", true );
                  $("#oth_pr").attr('disabled',true);
                  $(".demoorder").attr('disabled',true);

  
              }
	 }
        
    });


</script>

  
<div class="modal fade" id="stru_merchant_model" role="dialog">
        <div class="modal-dialog">
           <?php 

            $id = $_SESSION['merchant_id'];
            $info = mysqli_query($conn,"select name from users where id='$id'");
            $info1 = mysqli_fetch_array($info);

            $data = mysqli_query($conn,"select * from set_working_hr where merchant_id ='$id'");
            $row = mysqli_fetch_array($data);
            
            ?>

            <!-- Modal content-->
            <div class="modal-content">
             
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                 
                    <div class="modal-body" style="padding-bottom:0px;">
                        <div class="col-md-12" style="text-align: center;">
                          <h5>Sorry, we are closed at this time, please visit us at : ( <?php echo $row['start_time'];?> AM to <?php echo $row['end_time'];?> ,
                            <?php echo $DAY; ?> of the <?php echo $info1['name']; ?> ) </h5>
                         
                
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:2px;">
                    
                    </div>
               
            </div>
        </div>
 </div>

<!----end amit------>






