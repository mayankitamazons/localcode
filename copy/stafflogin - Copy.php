<?php
session_start();

include("config.php");

$current_time = date('Y-m-d H:i:s');
if($_SESSION['login']=='')

{

    header('Location: '. $site_url .'/login.php');

    die;

}






?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">



<head>

    <style>

        .no-close .ui-dialog-titlebar-close {

            display: none;

        }

        .test_product{

            padding-right: 125px!important;

        }

        td.products_namess {

            text-transform: lowercase;

        }

        tr {

            border-bottom: 2px solid #efefef;

        }

        .well {

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

            border-right: 1px solid #efefef;

        }

        th {

            border-right: 1px solid #efefef;

        }

        tr.fdfd {

            border-bottom: 3px double #000;

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

        tr.red {

            color: red;

        }

        label.status {

            cursor: pointer;

        }

        td {

            border-right: 2px solid #efefef;

        }

        th {

            border-right: 2px solid #efefef;

        }

        .gr{

            color:green;

        }

        .or{

            color: orange !important;

        }

        .red.gr{

            color:green;

        }

        .product_name{

            width: 100%;

        }

        .total_order{

            font-weight:bold;

        }

        p.pop_upss {

            display: inline-block;

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
		<?php 		
		
					
					$staffid=$_GET['staffid'];
					$select="select * from stafflogin where staff_id='$staffid'";
					$query=mysqli_query($conn,$select);
					
					
				
		?>

        <main class="main-wrapper clearfix" style="min-height: 522px;">

            <div class="row" id="main-content" style="padding-top:25px">
			
	
                <div class="well" style="width:100%" >
                     
					<h1> List of login and logout time</h1>
                    
                  

                    <?php

                    $dt = new DateTime();

                    $today =  $dt->format('Y-m-d');

                    ?>

                    <table class="table table-striped">

                        <thead>

                        <tr>

                            <th><?php echo $language["serialnumber"];?></th>

                            <th>Login Date</th>

                            <th>logout Date</th>
							
							
                           

			 

                        </tr>

                        </thead>

                       <tbody>
				<?php
				
				
				$i=1;
			while($row=mysqli_fetch_array($query))
			{
				?>	   
					 <tr>
					   <td><?php echo $i++; ?></td>
					   <td><?php echo  date("d-m-Y h:i:s",$row['logintime'])?></td>
					   <td><?php echo  date("d-m-Y h:i:s",$row['logouttime'])?></td>
					  
					 </tr>
					 
			<?php } ?>		 
					
					
                        </tbody>

                    </table>



                    <div style="margin:0px auto;">

                        <ul class="pagination">

                            <?php

                            /*for($i = 1; $total_page_num && $i <= $total_page_num; $i++) {

                             if($i == $page) {

                              $active = "class='active'";

                             }

                             else {

                              $active = "";

                             }

                             echo "<li $active><a href='?page=$i'>$i</a></li>";

                            }*/

                            ?>

                        </ul>


                    </div>

                    <div>

                        <div class="modal fade" id="myScanModal" role="dialog">

                            <div class="modal-dialog">

                                <!-- Modal content-->

                                <div class="modal-content" style="width: 100%; min-height: 500px;max-height: 600px;border-radius: 4px;background-color: transparent;    padding: 0px;">

                                    <div class="modal-header" style="padding: 3px 3px 3px 16px;background-color: #99e1dc57;margin: 0px;">

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        <h4 class="modal-title" style="color: #3a3939c4;">Statement</h4>

                                    </div>

                                    <div style="height: 550px;background-color: #99e1dc;padding: 10px;">

                                        <div class="inline fields">

                                            <div style="display: inline-block;height: 50px;">

                                                <label style="display: inline-block;height: 50px;">Barcode</label>

                                                <input type="text" id="barcode" autofocus style="display: inline-block;height: 50px;">

                                            </div>

                                            <div style="display: inline-block;height: 50px;">

                                                <button style="width: 100px; height: 50px;background-color: #99e1dc;" id="add_invoice">Add</button>

                                            </div>

                                        </div>

                                        <form id="scan" style="height: 476px; padding-top: 10px;">

                                            <table style="width: 100%;">

                                                <thead>

                                                <thead style="background-color: #e8dfdf;">

                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 10%;">No</th>

                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">InvoiceNumber</th>

                                                <th style="border-right: 2px solid #afa4a4;padding-left: 5px;width: 30%;">Qty</th>

                                                <th style="padding-left: 5px;">Amount</th>

                                                </thead>

                                                </thead>

                                            </table>

                                            <div class="modal-body" style="padding-bottom:0;height: 357px;padding: 0; overflow: auto;background-color: white;">



                                                <table style="width: 100%;border-style: outset;border-color: #f1f1f1ab;border-width: thin;">



                                                    <tbody style="width: 100%;" id="scanned_data">



                                                    </tbody>

                                                </table>

                                            </div>



                                            <div style="padding-top: 5px;    display: flex;">

                                                <span style="font-size: 20px; width: 40%;    border: 1px solid;padding-left: 4px; border-bottom-left-radius: 2px; border-top-left-radius: 2px;">Total</span>

                                                <span id="total_qty" style="font-size: 20px; width: 30%;    border: 1px solid;padding-left: 4px; border-left: none;"></span>

                                                <span id="total_amount" style="font-size: 20px;width: 30%;        border: 1px solid;padding-left: 4px; border-left: none; border-bottom-right-radius: 2px; border-top-right-radius: 2px;"></span>

                                            </div>



                                            <div class="modal-footer" style="padding-bottom:2px; border-top: none;padding: 0px;padding-top: 5px;">

                                                <button style="width:200px;height:50px;background-color: #99e1dc;">Submit</button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="modal fade" id="myModal" role="dialog">

                            <div class="modal-dialog">

                                <!-- Modal content-->

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        <h4 class="modal-title">Edit Amount</h4>

                                    </div>

                                    <form id ="data">

                                        <div class="modal-body" style="padding-bottom:0px;">

                                            <div class="col-sm-10">

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

                            </div>

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

        </main>

    </div>

    <!-- /.widget-body badge -->

</div>









<!-- /.widget-bg -->

<!-- /.content-wrapper -->

<?php include("includes1/footer.php"); ?>

</body>

</html>


