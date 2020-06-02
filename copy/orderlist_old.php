<?php 
   include("config.php");
     $total_rows = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC");
   	
   	$_SESSION['mm_id'] = "";
   	$_SESSION['o_id'] = "";
   	?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
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
         td {
    border-right: 2px solid #efefef;
		}
		th { 
    border-right: 2px solid #efefef;
}
tr.br_bk {
    border-bottom: 3px double #000;
}
.table tbody + tbody {
    border: none!important;
}
         tr.red {
         color: red;
         }
         label.dp_lab {
         cursor: pointer;
         }
         .pagination {
         display: inline-block;
         padding-left: 0;
         margin: 20px 0;
         border-radius: 4px;
         }
         .pagination>li {
         display: inline;
         }
         .pagination>li:first-child>a, .pagination>li:first-child>span {
         margin-left: 0;
         border-top-left-radius: 4px;
         border-bottom-left-radius: 4px;
         }
         .pagination>li:last-child>a, .pagination>li:last-child>span {
         border-top-right-radius: 4px;
         border-bottom-right-radius: 4px;
         }
         .pagination>li>a, .pagination>li>span {
         position: relative;
         float: left;
         padding: 6px 12px;
         margin-left: -1px;
         line-height: 1.42857143;
         color: #337ab7;
         text-decoration: none;
         background-color: #fff;
         border: 1px solid #ddd;
         }
         .pagination a { 
         text-decoration: none !important;
         }
         .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
         z-index: 3;
         color: #fff;
         cursor: default;
         background-color: #337ab7;
         border-color: #337ab7;
         }
         .product_name{
		     width: 100%;
		 }
		  .total_order{
		 font-weight:bold;
		 }
      </style>
      <?php include("includes1/head.php"); ?>
   </head>
   <body class="header-light sidebar-dark sidebar-expand pace-done">
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
         <div class="well" style="width:100%">
            <h3><?php echo $language["order_list"];?></h3>
            <?php 
               $dt = new DateTime();
               $today =  $dt->format('Y-m-d');
               ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?php echo $language["items"];?></th>
                     <th><?php echo $language["date_of_order"];?></th>
                     <th>No</th>
					 <th><?php echo $language["merchant_name"];?></th>
					 <th><?php echo $language["telephone_number"];?></th>
                     <th><?php echo $language["product_code"];?></th>
                     <th class="product_name"><?php echo $language["product_name"];?></th>
                     <th class="product_name"><?php echo $language["remark"];?></th>
                     <th><?php echo $language["quantity"];?></th>
                     <th><?php echo $language["amount"];?></th>
                     <th><?php $language["total"];?></th>
                     <th><?php $language["location"];?></th>
                     <th><?php $language["table_number"];?></th>
                     <th><?php $language["mode_of_payment"];?></th>
                     <th><?php $language["rating_comment"];?></th>
                     <th>Status</th>
                     <th>Print</th>
<!--
                     <th> Action</th>
-->
                  </tr>
               </thead> 
               <?php  $i =1;
                  while ($row=mysqli_fetch_assoc($total_rows)){
                  	$product_ids = explode(",",$row['product_id']);
                  	$quantity_ids = explode(",",$row['quantity']);
                  	
                  	$remark_ids = explode("|",$row['remark']);
                  	$c = array_combine($product_ids, $quantity_ids);

                  $user_namess = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));	
                   $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    $created =$row['created_on'];                  
                    $date=date_create($created);
                   
                    $new_time = explode(" ",$created);                  
                  ?>
               <tbody>
                  <?php 
                  
                   $callss = $row['status'] == 1 ? "gr" : " ";
                     $todayorder = $today == $new_time[0] ? "red" : "";
                   $i1 =1; 
                      ?>
                  <tr class="<?php echo $todayorder; ?> <?php echo $callss; ?> br_bk" >
                     <td><?php echo  $i; ?></td>
                     <td><?php echo date_format($date,"Y/m/d");  ?>
                     <?php echo '<br>'; echo $new_time[1] ?>
                                         </td>
                     <td><?php
                        foreach ($quantity_ids as $key => $val)
                        {
                         
                        echo $i1; echo '<br>';
                         $i1++;
                        }
                        ?></td>
                         <td><?php echo $merchant_name['name'];  ?></td>
                         <td><?php echo $merchant_name['mobile_number'];  ?></td>

                     <td> 
                        <?php  
                           foreach ($product_ids as $key)
                           {			
                           $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                           echo $product['product_type'].'<br>'; }  
                           ?>
                           
                     </td>
                     <td><?php foreach ($product_ids as $key )
                        {
                         $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));	
                         echo $product['product_name'].'<br>'; } 
                         ?> </td>
                     <td>
                        <?php  
                           foreach ($remark_ids as $vall)
                           {
                           
                           	echo $vall.'<br>';
                           
                           }
                           ?>
                     </td>
                     <td><?php foreach ($quantity_ids as $key)
                        {
                              echo $key.'<br>';
                              
                        }    ?></td>
                  
                     <td><?php 
                      $q_id = 0;
                        foreach ($product_ids as $key)
                        {
                         $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));	
                        
                        
                        
                        echo $quantity_ids[$q_id] * $product['product_price'].'<br>'; 
                          $q_id++;
                        } ?></td>
                     <td class="total_order"><?php 
                        $total = 0;
                          $t_id = 0;
                        foreach ($product_ids as $key )
                        {
                        $products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));	
                         $total =  $total + ($products['product_price'] *$quantity_ids[$t_id]);
                        
                          $t_id++;
                        } 
                        echo  $total;
                           ?> </td>
                     <td><?php echo $row['location'];?></td>
                     <td><?php echo $row['table_type'];?></td>
                     <td><?php echo $row['wallet'];  ?></td>
                     <td>
                        <?php //if($row['status']== '1'){ ?>
                        <label class="dp_lab"  data-id="<?php echo $merchant_name['id'];  ?>" data-oid="<?php echo $total;?>">Click Here</label>
                        <?php// }   ?>
                     </td>
                     <td>
                      
                        <?php $sta = $row['status'] == 1 ? "Done" : "Pending"?>
                        <label class= "status" data-id="<?php echo $row['id']; ?>" style="cursor:pointer;"> <?php echo $sta; ?></label>
                     </td>
                     <?php if($sta == "Done"){?>
                        <?php $merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$row['id']."'"));	?>
                        <td><a href="print.php?id=<?php echo $row['id'];?>&merchant=<?php echo $merchant['merchant_id']?>">Print</a></td>
                      <?php }?>
<!--
                     <td>
                        <?php //print_r($row['id']); ?>
                        <button type='button' class='removebutton'>Payment</button> 
                     </td>
-->
                  </tr>
                  <?php  	 $i++; ?>
                
               </tbody>
          <?php       }
                     
                     ?>
            </table>
            <div style="margin:0px auto;">
               <ul class="pagination">
                  <?php
                     for($i = 1; $i <= $total_page_num; $i++)
                     {
                      if($i == $page)
                      {
                       $active = "class='active'";
                      }
                      else
                      {
                       $active = "";
                      }
                      echo "<li $active><a href='?page=$i'>$i</a></li>";
                     }
                     ?>
               </ul>
            </div>
            <div>
      </main>
      </div>
      <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
   </body>
</html>
<script type="text/javascript">
   $(document).ready(function(){
      
   	   $(".dp_lab").click(function(){
   	var data_id = $(this).attr("data-id");
   	var data_oid = $(this).attr("data-oid");
   
   	 $.ajax({
   		 url : 'update_status.php',
   		 type: 'POST',
   		 data: {id:data_id,oid:data_oid},
   		 success:function(data){
   		  window.location = "http://kooexchange.com/demo/rating_menu.php";
   			  console.log(data);
   		 }
   	  
   	  
      });
       });
       });
      
</script>
<script type="text/javascript">
   $(document).ready(function(){
      
   	   $(".status").click(function(){
   	var data_id = $(this).attr("data-id");
   
   	 //~ alert(data_id);
   	
   	
   	 $.ajax({
   		 url : 'update_status.php',
   		 type: 'POST',
   		 data: {id:data_id},
   		 success:function(data){
   			    location.reload();
   
   			  console.log(data);
   		 }
   	  
   	  
      });
       });
       });
      
</script>
