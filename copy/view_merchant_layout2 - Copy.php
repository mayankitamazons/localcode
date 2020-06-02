<style type="text/css">
    .parent-category-menu {
        background-color: #fff;
        padding-top: 6px;
        padding-bottom: 6px;
        -webkit-box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        box-shadow: 0px 3px 8px 0px rgba(82, 63, 105, 0.15);
        position: relative;
    }
    .parent-category-menu a {
        padding: 8px 18px 8px 18px;
        display: inline-block;
        vertical-align: top;
        line-height: normal;
        font-size: 14px;
        color: #4a5368;
        font-weight: 600;
        background-color: transparent;
        border: 0px;
        box-shadow: none;
    }
    .merchant-layout-2 .sub_category_grid{
        background: #e9ebf1;
        margin-top: 0;
    }
    .merchant-layout-2 .sub_category_grid .category_filter{
        margin-right: 0px;
        width: 100%;
        border-bottom: 1px solid rgba(84, 92, 115, 0.14);
    }
    .merchant-layout-2 .sub_category_grid button{
        width: 100%;
        display: block;
        background-color: transparent;
        border: 0;
        color: #4a5368;
        border-radius: 0px;
        box-shadow: none;
        white-space: normal;
        text-align: left;
    }
    .merchant-layout-2 .text_add_cart, .modal-footer .text_add_cart{
        background-color: #50d2b7;
        width: 30px;
        height: 30px;
        font-size: 16px;
        border-radius: 100%;
        text-align: center;
        line-height: normal;
        padding: 6px 0 0 0;
        margin: 0;
        display: inline-block;
        vertical-align: top;
    }
    .merchant-layout-2 .common_quant{
        display: block;
        text-align: right;
    }
    .merchant-layout-2 .grid .grid-item{
        background-color: #ffffff;
        padding: 15px;
        -webkit-box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        box-shadow: 0px 0px 13px 0px rgba(82, 63, 105, 0.05);
        margin-bottom: 15px;
        width: 100%;
    }
    .element-item .introduce-remarks{
        font-size: .8em;
        position: absolute;
        z-index: 10;
        bottom: 0;
        top: 33px;
        right: 6vw;
        width: 5em;
        height: 30px;
        border-radius: 10px;
        box-sizing: border-box;
        padding: 0;
        display: grid;
        align-content: center;
    }
    @media (max-width: 767px) {
        .product_name_field{
            min-height: 3em;
        }
        .parent-category-menu a{
            padding: 8px 12px 8px 12px;
            width: 24%;
        }
        .main-wrapper {
            padding: 0 0 0 15px;
        }
        .merchant-layout-2 .sub_category_grid button {
            font-size: 12px;
        }
        .merchant-layout-2 .sub_category_grid .category_filter {
            padding: 6px 4px;
        }
        .merchant-layout-2 .grid .grid-item{
            padding: 8px;
        }
        .element-item .introduce-remarks{
            position: absolute;
            font-size: .8em;
            bottom: 5px;
            right: 35px;
            margin: 0;
            top: auto;
        }
        .element-item .row .col-12:nth-child(1) .introduce-remarks{
            right: 35px;
            bottom: 5px;
            left: auto;
        }
    }
    @media (max-width: 480px) and (min-width: 315px){
        .wrapper{
            width: 100%;
        }
    }
</style>
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
    <div class="col-md-12 merchant-layout-2">
        <div class="filter-button-group parent-category-menu">
            <?php
            $index = 1;
            $category_a = mysqli_fetch_assoc($categories_q);
            $categories = explode(",",$category_a['CatName']);
			 $query="select id from arrange_system WHERE user_id ='".$id."' and page_type='c' and status='active' group  by entity_id";
			
			$orderquery=mysqli_query($conn,$query);
			$ordercount=mysqli_num_rows($orderquery);
		     $query="select id from arrange_system WHERE user_id ='".$id."' and page_type='p' and status='active' group  by entity_id";
			
			$pquery=mysqli_query($conn,$query);
			$pcount=mysqli_num_rows($pquery);
            foreach ($categories as $category)
            {
                ?>
                 <a href="#" class="master_category_filter" data-position="<?php echo $index; ?>" data-filter=".<?php echo $index; ?>"><?php echo str_replace("-", " ", $category);?></a>
               
			   <?php
                $index++;
            }
            $index = 1;
            ?>
        </div>
        <div class="row no-gutters">
            <div class="col-4 col-sm-3">
                <div class="sub_category_grid">
                    <?php
					$s=0;
                    foreach ($categories as $category)
                    {
						if($ordercount>0)
						{
							 $q="SELECT category.*,arrange_system.shift_pos FROM category inner join arrange_system on  category.id=arrange_system.entity_id
							 WHERE category.user_id ='".$id."' and category.catparent='".$index."' and category.status=0 group by arrange_system.entity_id order by arrange_system.shift_pos asc";
							$sub_categories_q = mysqli_query($conn,$q);
						
						}
						else
						{
							$sub_categories_q = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$id."' and catparent='".$index."' and status=0 ");
						}
                        while ($row = mysqli_fetch_assoc($sub_categories_q))
                        {
							if($s==0)
							{
								 $sub_cat=$row['category_name'];
							}
                            if($row['category_name'] == "") continue;
                            ?>
                                                        <div class="<?php echo $index; ?> category_filter">
                                <button class="btn btn-primary" type="button" data-filter=".<?php echo $row['id'];?>" data-subcategory='<?php echo $row['id']; ?>'><?php echo str_replace("-", " ", $row['category_name']);?></button>
                            </div>
                        <?php $s++;  }
                        $index++;
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
            <div class="col-8 col-sm-9 pl-2">
                <div class="grid">
                    <?php
					$products_id_global = [];
                    $subproducts_global = [];
					if($pcount>0)
					{
						 $q="SELECT products.*,arrange_system.shift_pos FROM products inner join arrange_system on  products.id=arrange_system.entity_id
							 WHERE products.user_id ='".$id."'  and products.status=0 group by arrange_system.entity_id order by arrange_system.shift_pos asc";
						$total_rows = mysqli_query($conn,$q);
					}
					
                    while ($row=mysqli_fetch_assoc($total_rows)){
						// print_R($row);
						// die;
						
                        array_push($products_id_global, $row['id']);
                        ?>
                        <?php   //if(!empty($row['image'])) { ?>

                            <div class="element-item grid-item  <?php echo $row['category_id'];?>" >
                                <form action="#" method="post" class= "set_calss" data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>">
                                    <div class="row no-gutters">
                                    <?php if(!empty($row['image'])) { ?>
                                        <div class="col-5 col-sm-4">
                                            <?php if(isActive($row['active_time'])){
                                                    if($row['on_stock']){
                                             ?>
                                                <div class="container_test">
                                            <?php }else{
                                                ?>
                                                  <div class='container_test out_of_stock'>      
                                            <?php 
                                                    }
                                                }else{
                                                    ?>
                                                  <div class='container_test not_available'>      
                                            <?php
                                                } 
                                            ?>
                                                    <img src="<?php echo $site_url; ?>/images/product_images/<?php echo $row['image'];  ?>" class="img-fluid" >
                                            </div>
                                        </div>
                                        <div class="col-7 col-sm-8 pl-2">
                                    <?php } else { ?>
                                         <div class="col-12">
                                    <?php } ?>
                                            <input type="hidden" id="id" name="m_id" value="<?php echo $id;?>">
                                            <input type="hidden" id="id" name="p_id" value="<?php echo $row['id'];?>">
                                            <?php if(isActive($row['active_time']) && $row['on_stock']){ ?>
                                            <button role="button"  class="pro_status introduce-remarks btn btn-large btn-primary" data-toggle="modal" data-target="#remarks_area" disabled="disabled">Remarks</button>
                                            <input type="hidden" name="single_ingredients" value=""/>
                                            <input type="hidden" name="extra" value=""/>   
                                            <?php } ?>
                                            <p class="mBt10 product_name_field"><strong><?php echo $row['product_name']; ?></strong></p>
                                             <div style="float: left;">
                                                 <p class="mBt10"><?php echo $row['remark']; ?></p>
                                                 <p class="mBt10"><?php echo 'Pr : Rm'.number_format($row['product_price'],2); ?></p>
                                             </div>   
                                            <div class="common_quant">
											<?php if($row['varient_exit']=="y") { $cart_class="with_varient";} else { $cart_class="without_varient";} ?>
                                            <?php 
                                            if(!empty($row['image'])){
                                                if(isActive($row['active_time']) && $row['on_stock']){
                                                ?>
                                                  <p  id="product_child_<?php echo $row['id']?>" class="pro_status text_add_cart <?php echo $cart_class ?>"  data_varient_must='<?php echo $row['varient_must']; ?>' data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>" data-name = "<?php echo $row['product_name'] ?>">
												<i  id="child_<?php echo $row['id']?>" class="fa fa-plus"></i></p>
                                                <p class="quantity">
                                                    <input type="hidden" value="1" class="quatity" name="quatity">
                                                </p>
                                                    <?php } 
                                            }else{
                                                if(isActive($row['active_time'])){
                                                    if($row['on_stock']){
                                                ?>
                                                  <p  id="product_child_<?php echo $row['id']?>" class="pro_status text_add_cart <?php echo $cart_class ?>" data_varient_must='<?php echo $row['varient_must']; ?>'  data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>" data-name = "<?php echo $row['product_name'] ?>">
												<i  id="child_<?php echo $row['id']?>" class="fa fa-plus"></i></p>
                                                <p class="quantity">
                                                    <input type="hidden" value="1" class="quatity" name="quatity">
                                                </p>

                                                <?php
                                                    }else{
                                                        ?>

                                                        <div class="out_of_stock">Out of stock</div>

                                                        <?php
                                                    }
                                                }else{
                                                    ?>

                                                        <div class="out_of_stock">Not available</div>

                                                    <?php
                                                }   
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php  /*}*/
                    }

                    $subproducts_global = array();
                    foreach ($products_id_global as $key => $product_id) {
                        $result = array();
                        $total_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE product_id='".$product_id."'");
                         $ref_result=mysqli_affected_rows($conn);

                        if($ref_result){

                            $res['status']=200;
                            while ($row=mysqli_fetch_assoc($total_rows)){
                                $item = array( "id" => $row['id'], "name" => $row['name'], "product_id" => $product_id,'product_price' => $row['product_price']);
                                array_push($result, $item);
                            }
                            $res['data'] = $result;
                        }else{
                            $res['status'] = 404;
                        }
                        if($res['status'] == 200){
                            array_push($subproducts_global, $res['data']);
                        }
                    }
                    $subproducts_global = json_encode($subproducts_global);
                    ?>
                    <script type="text/javascript">
                        products_id_global.push(<?php echo implode(",",$products_id_global); ?>);
                        subproducts_global = <?php echo $subproducts_global; ?>;
                    </script>
                    
                </div>
            </div>
        </div>

    </div>

    <?php
}
?>