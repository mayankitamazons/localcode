	<?php
include("config.php");
 $_SESSION['mm_id']= $_SESSION['login'];

 $pid = $_POST['p_id'];

 $sql = "select * from sub_products where product_id=".$pid;
 $rel = mysqli_query($conn, $sql);

 while($row = mysqli_fetch_assoc($rel))
 {
 echo'<div id="prodct_cart_'.$row['id'].'" data-name="'.$row['name'].'" data-id="'.$row['id'].'" data-price="'.$row['product_price'].'" class="ingredient product_cart">
                               <button type="button" class="btnextra btn-info remove-ingredient" data-name="'.$row['name'].'" data-id="'.$row['id'].'" data-price="'.$row['product_price'].'" aria-label="Close">
                                  <span aria-hidden="true"><i class="fa fa-plus"></i></span>
                               </button>
                                 <span class="ingredient-name">'.$row['name'].' &nbsp; Price Rm '.$row['product_price'].'</span>
                            </div>';
 }


?>