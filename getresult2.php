<?php 
session_start();
include("config.php");
$id=$_SESSION['mm_id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as pro_ct FROM products WHERE user_id ='".$loginidset."' and status=0"));
?>
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

if($product['pro_ct'] > 0) { ?>
   
    <div class="col-md-12 merchant-layout-2">
       
        <div class="row no-gutters">
            <div class="col-4 col-sm-3">
                
            </div>
            <div class="col-12 col-sm-12 pl-2">
                <div class="grid row">
                    <?php
					$products_id_global = [];
                    $subproducts_global = [];
				
						    $q="SELECT products.*,pos_product_system.shift_pos FROM products inner join pos_product_system on  products.id=pos_product_system.entity_id
							 WHERE products.user_id ='".$id."'  and products.status=0 group by pos_product_system.entity_id order by pos_product_system.shift_pos asc";
							
						$total_rows = mysqli_query($conn,$q);
						// die;
				
					
                    while ($row=mysqli_fetch_assoc($total_rows)){
						// print_R($row);
						// die;
						
                        array_push($products_id_global, $row['id']);
                        ?>
                        <?php   //if(!empty($row['image'])) { 
							 if($row['varient_exit']=="y") { $cart_class="with_varient";} else { $cart_class="without_varient";}  ?>
                            <div class="col-md-2 btn-prni <?php echo $row['category_id']." ".$cart_class;?>"  data_varient_must='<?php echo $row['varient_must']; ?>' data-id = "<?php echo $row['id'] ?>" data-code = "<?php echo $row['product_type'] ?>"  data-pr = "<?php echo $row['product_price'] ?>" data-name = "<?php echo $row['product_name'] ?>">
                              <div><?php  echo $row['product_name'];?></div>
                              <div><?php  echo number_format($row['product_price'],2)." $";?></div>
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