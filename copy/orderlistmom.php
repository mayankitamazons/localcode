<?php
   include("config.php");
   $show_pop="n";
  function checkSession(){
  $conn = $GLOBALS['conn'];
  $session = $_COOKIE['session_id'];
  $rw = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM users WHERE session = '$session'"));
  if($rw > 0){
    return true;
  }else{
    return false;
  }
}
// if(!isset($_SESSION['login']) || empty($_SESSION['login']))
// {
  // header("location:logout.php");
// }else{
  // if(!checkSession()){
    // header("location:logout.php");
  // }
// }
    $query="SELECT order_list.*, sections.name as section_name FROM order_list left join sections on order_list.section_type = sections.id WHERE order_list.user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC LIMIT 0,50";

     $total_rows = mysqli_query($conn,$query);
	 
	 $user_order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC limit 0,1"));
	   $totalcount=count($user_order);
	 
	$first_section_id=$user_order['section_type'];
	$first_table_id=$user_order['table_type'];
	  $merchant_id=$user_order['merchant_id'];
	 if($first_section_id=="" || $first_table_id=="")
	 {
		 if($totalcount>0)
		 {
		include_once('php/Section.php');
		// include_once('php/SectionTable.php');

		$sectionsObj = new Section($conn);
		// $sectionTablesObj = new SectionTable($conn);
		$sectionsFilter = [
		  'user_id' => isset($merchant_id) ? $merchant_id : null,
		  'status' => true
		];
		// print_R($sectionsFilter);
		// die;
		$sectionsList = $sectionsObj->getList($sectionsFilter);
		
		 $show_pop="y";
		 $open_order_id=$user_order['id'];
		 }
	 }
	 $created_new =$user_order['created_on'];
      $status1 =$user_order['status'];
   	$_SESSION['mm_id'] = "";
   	$_SESSION['o_id'] = "";
   	?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
    
      <style>
		  .test_product{
		        padding-right: 125px!important;
		    }
		td.products_namess {
            text-transform: lowercase;
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
		 .gr{
		     color:green;
		 }
		 .or{
             color: orange;
         }
		 .red.gr{
		     color:green;
		 }
.location_head{
width:200px;
}
.new_tablee {
    width: 200px!important;
    display: block;
    word-break: break-word;
}
td.test_productss {
    white-space: nowrap;
    /*width: 200px!important;*/
    display: block;
}
th.product_name.test_product {
    width: 200px!important;
}
@media only screen and (max-width: 600px) and (min-width: 300px){
table.table.table-striped {
    white-space: unset!important;
}
}
#SectionModel .btn.btn-secondary{
  background-color: #e4e7ea;
  border-color: #e4e7ea;
  color: #555;
}
#SectionModel .btn.btn-secondary.checkbox-checked.active{
  background-color: #727b84 !important;
  border-color: #727b84 !important;
  color: #fff !important;
}
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
   #SectionModel > .modal-dialog{
    max-width: 90%;
   } 
   .credentials-container{
      width: 100%;
      margin-bottom: 20px;
    }
    .credentials-container > div{
      grid-template-columns: 1fr;
    }
    #reg_field{
      grid-template-columns: 1fr;
    }
    #passwd_field > input{
      grid-column-start: 1 !important;
      grid-column-end: 3 !important;
    }
    #reg_field, #passwd_field{
      width: 100%;
    }
  }
  
#SectionModel .modal-body{
  max-height: 70vh;
  overflow-y: auto;
}
 @media (max-width: 767px) {
  #SectionModel .modal-body{
    max-height: 60vh;
    overflow-y: auto;
  }
  #SectionModel{
	  max-width:400px;
  }
}
      </style>
      <?php include("includes1/head.php"); ?>
      <?php // include("mpush.php"); ?>
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
         <div class="well">
            <h3>Order list</h3>
            <?php
               $dt = new DateTime();
               $today =  $dt->format('Y-m-d');
               $today_order = explode(" ",$created_new);
                if( $today == $today_order[0] && $status1 == 1 ){ ?>
                <div style="display: none;">
<audio autoplay> <source src="<?php echo $site_url;?>/images/sound/doorbell-1.mp3" type="audio/mpeg"> Your browser does not support the audio tag. </audio>
    </div>
    <?php } ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?php echo $language["items"];?></th>
                     <th><?php echo $language["date_of_order"];?></th>
                     <th>No</th>
					 <th class="test_product"><?php echo $language["merchant_name"];?></th>
					 <th><?php echo $language["status"];?></th>
					   <th>Section</th>
					 <th><?php echo $language["table_number"];?></th>
					 <th>Customer Print</th>
					 <th>Invoice Number</th>
					 <th class="location_head"><?php echo $language["location"];?></th>
					 <th><?php echo $language["chat"];?></th>
					 <th><?php echo $language["telephone_number"];?></th>
                     <th><?php echo $language["product_code"];?></th>
                     <th class="product_name test_product"><?php echo $language["product_name"];?></th>
                    <th class="product_name test_product"><?php echo "VARIENT";?></th>
                     <th class="product_name test_product"><?php echo $language["remark"];?></th>
                     <th><?php echo $language["quantity"];?></th>
                     <th>Price</th>
                     <th><?php echo $language["amount"];?></th>
                     <th><?php echo $language["total"]?></th>
                     <th><?php echo $language["mode_of_payment"];?></th>
                     <th><?php echo $language["rating_comment"];?></th>
                     <th><?php echo $language["print"];?></th>
                     <th>K1/K2</th>
                  </tr>
               </thead>
               <?php  $i =1;
                  while ($row=mysqli_fetch_assoc($total_rows)){
				  
				  //print_r($row);
				 // echo "<hr>";
                  	$product_ids = explode(",",$row['product_id']);
                  	$quantity_ids = explode(",",$row['quantity']);
                  	$product_code = explode(",",$row['product_code']);
                  	$remark_ids = explode("|",$row['remark']);
                  	$c = array_combine($product_ids, $quantity_ids);
                  	$amount_val = explode(",",$row['amount']);
                    $amount_data = array_combine($product_ids, $amount_val);
                    $total_data = array_combine($quantity_ids, $amount_val);
                    //var_dump($amount_val);
					// $order_list = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id ='".$_SESSION['login']."' ORDER BY `created_on` DESC");
                    $user_namess = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));
                    $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    $created =$row['created_on'];
                    $date=date_create($created);
					$section_type=$row['section_type'];
					 $section_id=$section_type;
					 $merchant_id=$user_order['merchant_id'];
					 if($section_type)
					 {
					  $section_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id ='".$section_type."'"));
					 
					 }
					 $table_type=$row['table_type'];
                    $new_time = explode(" ",$created);
                    $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));
                    //$account_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT k_merchant FROM k1k2_history WHERE id ='".$row['id']."'"))['account_type'];
                  ?>
               <tbody>
                  <?php

                   if($row['status'] == 1) $callss = "gr";
                     else if($row['status'] == 2) $callss = "or";
                     else $callss = " ";
                     $todayorder = $today == $new_time[0] ? "red" : "";
                   $i1 =1;
                      ?>
                  <tr  data-id="<?php echo $row['id']; ?>" class="<?php echo $todayorder; ?> <?php echo $callss; ?> br_bk" >
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
                    <td><a href="<?php echo $site_url; ?>/view_merchant.php?sid=<?php echo $merchant_name['mobile_number'];?>"><?php echo $merchant_name['name'];  ?></td>
                        
						<td>
                       <?php
                        $sta = $row['status'] == 1 ? "Done" : "Pending"?>
                        <?php if($row['popup']==0 && $row['status'] == 1 )
                        {
                            //echo $row['id'];
                        }
                        ?>
                        <label class= "status" data-id="<?php echo $row['id']; ?>" style="cursor:pointer;"> <?php echo $sta; ?></label>
                     </td>   
					   <td><?php echo $section_type['name'];?></td>
                         <td><?php echo $row['table_type'];?></td>
						  <td>
							 <a class="normal_print" href="#" data-id="<?php echo $row['id']; ?>" data-invoice="<?php echo $row['invoice_no']; ?>">Print Receipt</a>
							  
                            </td>
                         <td><?php echo $row['invoice_no'];?></td>
                         <td class="location_<?php echo $row['id']; ?> new_tablee"><?php echo $row['location'];?></td>
  <td><a target="_blank" href="<?php echo $site_url; ?>/chat/chat.php?sender=<?php echo $_SESSION['login']?>&receiver=<?php echo $row['merchant_id'];?>"><i class="fa fa-comments-o"></i></a></td>
                         <?php if($merchant_name['number_lock'] == 0){?>
                            <td><?php echo $merchant_name['mobile_number'];?></td>
                        <?php } else {?>
                            <td></td>
                        <?php }?>

                     <td>
                        <?php
                           foreach ($product_code as $key)
                           {
                           //$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                if($key == "") echo '- <br>';
                                else echo $key.'<br>'; 
                               
                           }
                           ?>

                     </td>
                     <td class="products_namess test_productss">
                        <?php foreach ($product_ids as $key ){
							if(is_numeric($key)){
                                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
                                echo $product['product_name'].'<br>';
					        }else {
						        echo $key.'<br>';
					        }
                         } ?>
                         </td>
					<td><?php if($row['varient_type']){$v_str=$row['varient_type'];
							$v_array=explode("|",$v_str);
							foreach($v_array as $vr)
							{
								
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										echo $srow['name'];
										echo "&nbsp;&nbsp;";
									}
								}
								 else
								 {
									 echo "</br>";
								 }
								 echo "<br/>";
					} }
							  ?>
							</td>
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
                              if($key == "") echo '0 <br>';
                              else echo $key.'<br>';

                        }    ?>
                    </td>
                    <td>
                        <?php 
                            foreach ($amount_val as $key => $value){ 
                                echo $value.'<br>'; 
                            } 
                        ?>
                    </td>
                     <td><?php 
                          foreach ($amount_val as $key => $value)
                        {
						                 
                        echo $quantity_ids[$key] * $value.'<br>'; 
                        } ?></td>
                     <td class="total_order"><?php 
                        $total = 0;
                        foreach ($amount_val as $key => $value)
                        {
                         $total =  $total + ($quantity_ids[$key] * $value);
                        
                        } 
                        echo  $total;
                           ?> </td>

                     <td><?php echo $row['wallet'];  ?></td>
                     <td>
                        <?php //if($row['status']== '1'){ ?>
							 <label class="dp_lab"  data-id="<?php echo $merchant_name['id'];  ?>" data-oid="<?php echo $total;?>" data-orid="<?php echo $row['id']; ?>">Click Here</label>

                        <?php// }   ?>
                     </td>                
                     <?php if($sta == "Done"){?>
                        <?php $merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$row['id']."'"));	?>
                      <td><a target="_blank" href="print.php?id=<?php echo $row['id'];?>&merchant=<?php echo $merchant['merchant_id']?>">Print</a></td>
                      <td><?php echo $user_name['account_type']; ?></td>
                      <?php }?>
                  </tr>
                  <?php  	 $i++; ?>

               </tbody>
          <?php       }

                     ?>
            </table>
            <div style="margin:0px auto;">
               <ul class="pagination">
                  <?php
                   global $total_page_num ;
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
                <!-- add new code-->
				<!-- edit amount--->
	        <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edit Amount</h4>
                        </div>
                        <div class="modal-body" style="padding-bottom:0px;">

		                    <div class="col-sm-10">
                                <form id ="data">
                                    <div class="form-group">
                                        <label>Amount</label>
		 	                            <input type="text" name="amount" id = "amount" class="form-control" value="" required>
                                        <input type="hidden" id="id" name="id" value="">
                                        <input type="hidden" id="p_id" name="p_id" value="">
                                    </div>
                                </div>
		                    </div>
                        <div class="modal-footer" style="padding-bottom:2px;">
                			<button>Submit</button>
                        </div>
                        </form>
                    </div>
                 <div class="modal fade" id="myModal" role="dialog" >
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content" id="modalcontent">
                       <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
				</div>
			<!-- end edit function--->
	<?php
	$res = mysqli_query($conn,"SELECT * FROM `order_list` WHERE status_change_date = CURDATE() and status = 1 and popup = 1 and user_id ='".$_SESSION['login']."'");
	if(mysqli_num_rows($res)==0)
	{
	    $res = mysqli_query($conn,"SELECT * FROM `order_list` WHERE status_change_date = CURDATE() and status = 1 and popup = 0 and user_id ='".$_SESSION['login']."'");
	    $r = mysqli_fetch_array($res);
	    if(mysqli_num_rows($res)!=0)
	    {
	        include_once 'share_popup.php';
	        //$res = mysqli_query($conn,"UPDATE `order_list` set popup = 1 WHERE status_change_date = CURDATE() and status = 1 and popup = 0 and user_id ='".$_SESSION['login']."'");
	    }
	}
	 ?>
		<!-- end new code--->

      </main>
      </div>
      <!-- /.widget-body badge -->
      </div>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
	  
  <!-- Modal -->
  <div class="modal fade" id="SectionModel" role="dialog" style="">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
		   
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Your order is completed, kindly provide the following details</h4>
        </div>
        <div class="modal-body">
         <form action="sectionsave.php" method="post">
  <div class="form-group">
   <label for="email">Section:</label>
    <!--input type="text" class="form-control" name="section" aria-describedby="emailHelp" value="<?php echo $section_type; ?>" placeholder="Section" required!-->
	  <select name="section_type" class="form-control" data-table-list-url="<?php echo $site_url; ?>/table_list.php">
			  
          <?php foreach($sectionsList as $sectionId => $sectionName): ?>
            <?php
              $isSelected = "";
              if($section_id == $sectionId) {
                $isSelected = "selected";
              }
            ?>   
            <option value="<?php echo $sectionId; ?>" <?php echo $isSelected; ?>><?php echo $sectionName; ?></option>
          <?php endforeach; ?>
        </select>
	<input type="hidden" value="<?php echo $open_order_id; ?>" name="order_id"/>
  
  </div>
  <div class="form-group">
   <label for="email">Table Number:</label>
    <input type="text" class="form-control" name="table_booking" value="<?php echo $table_type; ?>" placeholder="Table Number" required>  
  </div>

 <button style="float:left;" id="sectionsubmit" type="submit" class="btn btn-primary">Save</button>
</form>
        </div>
       
      </div>
      
    </div>
  </div>
   
   </body>
</html>
<script type="text/javascript">
   $(document).ready(function(){
	   var show_pop='<?php echo $show_pop; ?>';
	   if(show_pop=="y")
	   {
			$('#SectionModel').modal('show');
	   }
	     $(".well").on('click','.normal_print', function(e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
			// alert('Making Normal Print');
            $.ajax({
                url : 'functions.php',
                type: 'POST',
                data: { id : id, method: 'getOrderDetail'},
                success:function(data){
                    if( data != null ) {
                        var obj = JSON.parse( data );
                        if( obj.length > 0 ) {
                            var order = obj[0];
                            data = {order: order, method: "normalorder", date : getCurrentDate() , time : getCurrentTime()};
                            $.ajax( {
                                url : "functions.php",
                                type:"post",
                                data : data,
                                dataType : 'json',
                                success : function(data) {
								//	alert('print done');
									var report = JSON.parse( data );
									 alert('Normal print done');
									 tempAlert("Normal print done.",3000);
									// var al = window.open('', 'Normal print done.'); 
									// window.setTimeout(function() {al.close()}, 3000);

							alert(JSON.stringify(data));	
									alert(report.status);
									alert(report.message);
                                    if( ! data || data.indexOf('print_setting_error') > -1 ) {
                                        alert("You need to set print ip address in profile page.");
                                    }
                                    alert(data);
									alert("Your order has been printed.");
                                },
                                error: function(data){
                                    console.log(data);
                                }
                            });
                        }
                    }
                }
            });
        });
        $("#sharepopup").modal({ backdrop: 'static', keyboard: false }); 
        $("#uploaded").click(function(){ $("#upl").click();});
   	   $(".dp_lab").click(function(){
   	var data_id = $(this).attr("data-id");
   	var data_oid = $(this).attr("data-oid");
   	var data_orid = $(this).attr("data-orid");
   
   	 $.ajax({
   		 url : 'update_status.php',
   		 type: 'POST',
   		 data: {id:data_id,oid:data_oid,orid:data_orid},
   		 success:function(data){
   		  window.location = "<?php echo $site_url;?>/rating_menu.php";
   			  console.log(data);
			 // location.reload();
   		 }
   	  
   	  
      });
       });
       });
      
      /* new code */
      $(".pop_up").click(function(){
	  $("#myModal").modal("show");
	  var userid=$(this).data("id");
	   //target:'#picture';

	  $.ajax({
		  
		  url :'update_product.php',
		  type:'POST',
      dataType : 'json',
      data:{showid:userid},
		  success:function(response){
      console.log(response.id);
      //alert(response.id);
      $("#id").val(response.id);
      $("#product_name").val(response.product_name);
      $("#category").val(response.category);
      $("#product_type").val(response.product_type);
      $("#product_price").val(response.product_price);
      $("#remark").val(response.remark);
      $("#img").val(response.image);
      
          
		  }		  
	  });
	 
  });

      
      
      
</script>
<script src="js/jquery.form.js"></script> 
<script> 
        $(document).ready(function() { 
            setInterval(function(){ location.reload(); }, 1000 * 60);
        $("#q1").click(function(){
            $("#q1text").removeClass("disabled");
            $("#q1text").attr("data","checked");
        })
        $(".sharet input[type='radio']").click(function(){
            if($(this).attr("id")!='q1')
            {
                $("#q1text").addClass("disabled");
                $("#q1text").attr("data","unchecked");
            }
        })
        $("#shform").submit(function(e) {
           
            e.preventDefault();
            
           
         });
        $('input[name="facebook"]').click(function(){
            $("#sharebutton").click();
            })
        //return false;
        })
         var progressbar     = $('.progress-bar');


            $(".upload-image").change(function(){
                $('#uploaded').hide()
            	$(".form-horizontal").ajaxForm(
		{
		  target: '.preview',
		  beforeSend: function() {
			$(".progress").css("display","block");
			progressbar.width('0%');
			progressbar.text('0%');
                    },
		    uploadProgress: function (event, position, total, percentComplete) {
		        progressbar.width(percentComplete + '%');
		        progressbar.text(percentComplete + '%');
		     },
		})
		.submit();
            });
        $('#sharebutt').click(function(){
            var img = $('input[name="img"]').val();
            var uid = $('input[name="userid"]').val();
            if($("#q1text").attr('data')=='checked')
            var title = $("#q1text").val();
            else
            var title = $("input[name='q']:checked").val();
            var link = '<?php echo $site_url;?>/share.php?image='+img+'&title='+title;
            var encodlink = "https://www.facebook.com/share.php?u="+encodeURIComponent(link);
            $("#sharebutton").attr("href",encodlink);
            $.ajax({
           type: "POST",
           url: 'share.php',
           data: {img:img,title:title,shareon:'facebook',userid:uid},
           success: function(data)
           {
               $("#popclose").click(); 
           }
        });
        
        
          
   /*adding new update */
    $(".pop_upss").click(function(){
	 $("#myModal").modal("show");
	  var dataid=$(this).data("id");
	  var prodid=$(this).data("prodid");
		alert(dataid);
		alert(prodid);  
	        $("#id").val(dataid);
	        $("#p_id").val(prodid);
	    });
    
    
    
    	$("form#data").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);
    $.ajax({
        url: 'update_amount.php',
        type: 'POST',
        data: formData,
        success: function (data) {
			//alert(data);
        location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
   
        
        
        });
    </script>


<script> window.setInterval('refresh()', 60000); 
 function refresh() {
	  window.location.reload();
	  } 
 </script>
