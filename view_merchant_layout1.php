<?php

function toSeconds($date){
  $a = explode(":", $date); 
  // var_dump($a);
  return intval($a[0]) * 3600 + intval($a[1]) * 60; 
}
function isActive($date){
    if($date == 1){
        return true;
    }
    $date = json_decode($date);
    for($i = 0; $i < sizeof($date); $i++) {
        $inDate = false;
        $startHours = '';
        $endHours = '';
        foreach ($date[$i] as $key => $value) {
            if($key == "days" && in_array(date("N"),explode("-", $value))){
                $inDate = true;
            }
            if($inDate && $key == "start"){
                $startHours = $value;
            }else if($inDate && $key == "end"){
                if(toSeconds($startHours) < toSeconds(date("G:i")) && toSeconds(date("G:i")) < toSeconds($value)){
                    return true;
                }
            }
        }
    }
    return false;
}


//$categories = mysqli_query($conn, "SELECT DISTINCT(products.category),created_date FROM products WHERE user_id ='".$id."' and status=0 ORDER BY created_date ASC");
$categories_q = mysqli_query($conn, "SELECT * FROM cat_mater WHERE UserID ='".$id."' and IsEnable=1 ");

if($product['pro_ct'] > 0) { ?>
    <div class="col-md-12 filter-button-group">
        <?php
        $index = 1;
		$s=0;
        $category_a = mysqli_fetch_assoc($categories_q);
        $categories = explode(",",$category_a['CatName']);
        foreach ($categories as $category)
        {
			if($s==0)
					{
						 $master_cat=str_replace("-", " ", $category);
					}
            ?>
            <button  style="background:#51d2b7;border:none;"  class="btn btn-primary master_category_filter" type="button" data-position="<?php echo $index; ?>" data-filter=".<?php echo str_replace(" ","-",$category);?>"><?php echo str_replace("-", " ", $category);?></button>
            <?php
            $s++;
            $index++;
        }
        $index = 1;
        ?>
    </div>
    <div class="col-md-12">
        <div class="sub_category_grid">
            <?php
			$s=0;
            foreach ($categories as $category)
            {
               // echo "SELECT * FROM category WHERE user_id ='".$id."' and catparent='".$index."' and status=0";
			   // die;
                $sub_categories_q = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$id."' and catparent='".$index."' and status=0");
                while ($row = mysqli_fetch_assoc($sub_categories_q))
                {
					
					if($s==0)
					{
						 $sub_cat=$row['category_name'];
					}
                    //if($row['category_name'] == "") continue;
					
                    ?>  
                     
                        <button class="<?php echo str_replace(" ","-",$category);?> category_filter btn btn-primary" type="button" data-filter=".<?php echo str_replace(" ","-",$row['category_name']);?>"><?php  echo str_replace("-", " ", $row['category_name']);?></button>
                      
					<?php  $s++; }
                $index++; 
				$s++;
            }
            ?>

            <?php
            /*while ($row=mysqli_fetch_assoc($categories)){
                if($row['category'] == "") continue;
                if($index == 0) $category= $row['category'];
                $index++;
            ?>
            <button class="btn btn-primary category_filter" type="button" data-filter=".<?php echo $row['category'];?>"><?php echo str_replace("-", " ", $row['category']);?></button>
            <?php }
            */
            ?>
        </div>
    </div>


    <div class="new_grid">


        <?php
        while ($row=mysqli_fetch_assoc($total_rows)){
            ?>
            <?php   if(!empty($row['image'])) {  $item_cat=$row['category']; ?>

                <div class="well col-md-4 element-item <?php echo $row['category'];?>" style="<?php  if (strcasecmp($item_cat, $sub_cat) != 0) { echo "display:none;";} ?>">
                    <form action="product_view.php" method="post" class= "set_calss" data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>" style="background: #51d2b7;    padding: 12px;    border: 1px solid #e3e3e3;    border-radius: 4px;    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                        <?php 
                            if(isActive($row['active_time'])){
                                if($row['on_stock']){
                         ?>
                                    <div class="container_test"> 
                        <?php
                                }else{
                        ?>
                                      <div class='container_test out_of_stock'>      
                        <?php
                                }
                            }else{
                                ?>

                                      <div class='container_test not_available'>      

                                <?php
                            }
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
                        <!-- <p class="mBt10"><?php echo 'Code: '.$row['product_type']; ?></p> -->
                        <p class="mBt10"><?php echo $row['remark']; ?></p>
                        <!--	<p><?php echo 'Category : '.str_replace("-", " ", $row['category']); ?></p>-->
                        <p class="mBt10"></p><?php echo 'Price : Rm'.number_format($row['product_price'],2); ?></p>
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
                            <?php 
                                if(isActive($row['active_time'])){
                                    if(!$row['on_stock']){
                                        ?>
                                            <p class='no_stock_add_to_cart'>Out of stock</p>
                                        <?php
                                    }else{
                            ?>
                            <p class="text_add_cart"  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>" data-name = "<?php echo $row['product_name'] ?>">Add to Cart</p>
                            <p class="quantity">
                                <!--
                                <label>Quantity</label>
                                -->
                                <div style="display:grid;grid-template-columns:.2fr 1fr;align-content:center;vertical-align:center;">
                                    <label>X</label>
                                    <input type="number" value="1" class="quatity" name="quatity" style="height:1.5em">
                                </div>
                            </p>
                        <?php 
                                    }
                                }else{
                                    ?>
                                        <p class='no_stock_add_to_cart'>This product is not available in this moment</p>
                                    <?php
                                }
                     ?>
                        </div>

                    </form>
                </div>


            <?php  }} ?>
			
    </div>
    <?php
}
?>
