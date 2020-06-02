<?php
session_start();

include("config.php");

$current_time = date('Y-m-d H:i:s');
if($_SESSION['login']=='')

{

    header('Location: '. $site_url .'/login.php');

    die;

}

$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));

if($profile_data['user_roles']!=2)
{
	header('Location: '. $site_url .'/dashboard.php');

    die;
}
if(isset($_GET['enable']))
	{
				$parentid=$_SESSION['login'];
		
					
						$update="update users set allowstaff=1 where id='$parentid'";
						mysqli_query($conn,$update);
						echo "<script>location.replace('staff.php');</script>";
					
					
		
		
	}
	if(isset($_REQUEST['deleteid']))
	{	
		$deleteid=$_REQUEST['deleteid'];
		$delete="delete from users where id='$deleteid'";
		mysqli_query($conn,$delete);
		echo "<script>location.replace('staff.php');</script>";
		
	}


?>
<script>
function hweset()
{
	if(confirm("Are you sure want to delete?"))	
	{
		return true;	
	}
	else
	{
		return false;	
	}
	
}

</script>

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
		<?php 		 $parentid=$_SESSION['login'];
		
					if(isset($_REQUEST['disable_staffacc']))
					{
						$update="update users set allowstaff=0 where id='$parentid'";
						mysqli_query($conn,$update);
						echo "<script>location.replace('staff.php');</script>";
					
					}
		
					$select="select * from users where id='$parentid'";
					$query=mysqli_query($conn,$select);
					$getdetails=mysqli_fetch_array($query);
					
				//echo $getdetails['allowstaff'];
		?>

        <main class="main-wrapper clearfix" style="min-height: 522px;">

            <div class="row" id="main-content" style="padding-top:25px">
			
	<?php
	
	
	if(($getdetails['allowstaff']==1))
	{
	
	?>

                <div class="well" style="width:100%" style="display:none;">
                     
					 <div>
					 	
						<a href="staff.php?disable_staffacc=1">Please click here to disable staff account for you</a>
					 
					 </div>
                    
                    <div>

                        <h3><?php echo $language['staff_list'];?> <font style="float:right"><a href="addstaff.php">Add staff Account</a></font>  </h3>

                      	

                    </div>

                    <?php

                    $dt = new DateTime();

                    $today =  $dt->format('Y-m-d');

                    ?>

                    <table class="table table-striped">

                        <thead>

                        <tr>

                            <th><?php echo $language["serialnumber"];?></th>

                            <th>Date</th>

                            <th>Username</th>
							
							<th><?php echo $language["telephone_number"]; ?></th>
							<th>Option</th>
                           

			 

                        </tr>

                        </thead>

                       <tbody>
				<?php
					
				$select="select * from users where parentid='$parentid'";
					$query=mysqli_query($conn,$select);	
				
				$i=1;
			while($row=mysqli_fetch_array($query))
			{
				?>	   
					 <tr>
					   <td><?php echo $i++; ?></td>
					   <td><?php echo  date("d-m-Y",$row['joined'])?></td>
					   <td><?php echo  $row['name'];?></td>
					   <td><?php echo  $row['mobile_number'];?></td>
					   <td><a href="stafflogin.php?staffid=<?php echo $row['id']; ?>">View</a>/<a href="editstaff.php?staffid=<?php echo $row['id']; ?>">Edit</a>/<a href="staff.php?deleteid=<?php echo $row['id']; ?>" onClick="return hweset();">Delete</a></td>
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
	
	<?php  } 
	
		else
		{
			echo "<h2 style='text-align:center;margin-left:27%;margin-top:8%'>You disable staff account. For enable staff please <a href='staff.php?enable=1'>click here</a></h2>";
			
		}
	?>

    <!-- /.widget-body badge -->

</div>









<!-- /.widget-bg -->

<!-- /.content-wrapper -->

<?php include("includes1/footer.php"); ?>

</body>

</html>

<script type="text/javascript">



    function copy_orderDetail(id){

        var detailContent = "";

        var username = $("username");

        var dummy = document.createElement("input");

        document.body.appendChild(dummy);

        var product_name = $(".product_name_" + id).html().split("<br>");

        var product_qty = $(".quantity_"+id).html().split("<br>");

        var product_amount = $(".amount_"+id).html().split("<br>");

        dummy.setAttribute("id", "dummy_id");

        var detail = "User Name: " + $(".username_" + id).html() +" ";

        detail += "User Phone: " + $(".userphone_" + id).val() + " ";

        detail += "Merchant Name: " + $(".merchant_" + id).val() + " ";

        detail += "Merchant Phone: " + $(".merchantphone_" + id).val() + " ";

        detail += "Merchant Address: " + $(".merchantaddress_" + id).val() + " ";



        for(var i = 0; i < product_name.length - 1; i++){

            detail += "Product Name: " + product_name[i] + " ";

            detail += "Quantity: " + product_qty[i] + " ";

            var amount = product_amount[i].trim();

            if(product_amount[i].indexOf("class") > -1){

                amount = 0;

            }

            detail += "Amount: " + amount + " ";

        }

        detail += "Total: " + $(".total_" + id).html().trim() + "   ";

        detail += "Table Number: " + $(".table_number_" + id).html().trim() + "   ";

        detail += "Location: " + $(".location_" + id).html().trim() + "   ";

        document.getElementById("dummy_id").value= detail;



        dummy.select();



        document.execCommand("copy");



        alert("Send Delivery Service to Admin!");

    }



    function hasClass(element, className) {

        return (' ' + element.className + ' ').indexOf(' ' + className+ ' ') > -1;

    }



    $(document).ready(function(){



        function handleKeyPress (e) {

            if( hasClass( document.getElementById("myScanModal"), "show" ) ) {

                if (e.keyCode === 13) {

                    var barcodeRead = $("#barcode").val();

                    addOrderToDialog( barcodeRead );

                    $("#barcode").val('');

                }



            }

        }



        $("#add_invoice").click(function() {

            if( hasClass( document.getElementById("myScanModal"), "show" ) ) {

                var barcodeRead = $("#barcode").val();

                addOrderToDialog( barcodeRead );

                $("#barcode").val('');

                $("#barcode").focus();

            }

        });



        function empty(str){

            return !str || !/[^\s]+/.test(str);

        }



        function addOrderToDialog(barcode) {

            var orders = $("#scanned_data tr");

            var ids = [];

            orders.each(function(row, v) {

                $(this).find("td").each(function(cell, v) {

                    if( cell == 2 ) {

                        ids.push($(this).text());

                    }

                });

            });

            var res = barcode.split("-");

            if( res.length > 1 ) {

                var id = res[0];

                if( ids.indexOf(id) > -1 ) {

                    return;

                }

                var invoice_id = res[1];

                if( ! empty( id ) && ! empty( invoice_id ) ) {

                    $.ajax({

                        url : 'functions.php',

                        type: 'POST',

                        data: { id : id, invoice_no : invoice_id, method: 'getOrderDetailByIdAndInvoice'},

                        success:function(data){

                            if( data != null ) {

                                var obj = JSON.parse( data );

                                if( obj.length > 0 ) {

                                    var order = obj[0];

                                    if( parseInt(order['status']) != 0 ) {

                                        return;

                                    }

                                    var total = 0;

                                    var totalQty = 0;

                                    var qtyOfInvoice = 0;

                                    for( var i = 0 ; i < order['product_name'].length ; i ++ ) {

                                        var amount = 0;

                                        if( order['product_qty'][i] && order['product_amt'][i] ) {

                                            amount = order['product_qty'][i] * order['product_amt'][i];

                                        } else {

                                            amount = 0;

                                        }

                                        total += amount;

                                        totalQty += parseInt(order['product_qty'][i]);

                                        qtyOfInvoice += parseInt(order['product_qty'][i]);

                                    }

                                    var total_amount =  empty($("#total_amount").text()) ? 0 : parseFloat($("#total_amount").text());

                                    total_amount += total;

                                    total = total.toFixed(2);

                                    $("#total_amount").text(total_amount.toFixed(2));



                                    var total_qty =  empty($("#total_qty").text()) ? 0 : parseInt($("#total_qty").text());

                                    total_qty += qtyOfInvoice;

                                    $("#total_qty").text(parseInt(total_qty));



                                    var list =

                                        '<tr><td style="padding-left: 5px;width: 10%;">' + parseInt(document.getElementById("scanned_data").childElementCount + 1) + '</td>' +

                                        '<td style="padding-left: 5px;width: 30%;">' + order['invoice_no'] + '</td>' +

                                        '<td style="display: none">' + order['id'] +'</td>' +

                                        '<td style="padding-left: 5px;width: 30%;">' + parseInt(qtyOfInvoice) + '</td>' +

                                        '<td style="padding-left: 5px;">' + total +  '</td></tr>';

                                    $("#scanned_data").append(list);

                                }

                            }

                        }

                    });

                }

            }

        }



        function updateOrder(id) {

            console.log(id);

            $.ajax({

                url: 'update_status.php',

                type: 'POST',

                data: {id: id, status: 1},

                success: function (data) {

                    location.reload();

                }

            });

        }



        document.getElementById("barcode").addEventListener('keypress', handleKeyPress);



        function getCurrentTime() {

            var today = new Date();

            var hh = today.getHours();

            var mm = today.getMinutes();

            var ss = today.getSeconds();

            return hh + ':' + mm + ':' + ss;

        }



        function getCurrentDate() {

            var today = new Date();

            var dd = today.getDate();

            var mm = today.getMonth() + 1; //January is 0!



            var yyyy = today.getFullYear();

            if (dd < 10) {

                dd = '0' + dd;

            }

            switch( mm ) {

                case 1:

                    mm = 'Jan';

                    break;

                case 2:

                    mm = 'Feb';

                    break;

                case 3:

                    mm = 'Mar';

                    break;

                case 4:

                    mm = 'Apr';

                    break;

                case 5:

                    mm = 'May';

                    break;

                case 6:

                    mm = 'Jun';

                    break;

                case 7:

                    mm = 'Jul';

                    break;

                case 8:

                    mm = 'Aug';

                    break;

                case 9:

                    mm = 'Sep';

                    break;

                case 10:

                    mm = 'Oct';

                    break;

                case 11:

                    mm = 'Nov';

                    break;

                case 12:

                    mm = 'Dec';

                    break;

            }

            return dd + '/' + mm + '/' + yyyy;

        }



        $("#scan_order").click(function() {

            $("#myScanModal").modal("show");

            $("#scanned_data").html('');

        });



        $(".print-order").click(function() {

            var id = $(this).attr("data-id");

            $.ajax({

                url : 'functions.php',

                type: 'POST',

                data: { id : id, method: 'getOrderDetail'},

                success:function(data){

                    if( data != null ) {

                        var obj = JSON.parse( data );

                        if( obj.length > 0 ) {

                            var order = obj[0];

                            data = {order: order, method: "pintOrder", date : getCurrentDate() , time : getCurrentTime()};

                            $.ajax( {

                                url : "functions.php",

                                type:"post",

                                data : data,

                                dataType : 'json',

                                success : function(data) {

                                    console.log(data);

                                    if( ! data || data.indexOf('print_setting_error') > -1 ) {

                                        alert("You need to set print ip address in profile page.");

                                    }

                                    alert(data);

                                }});

                        }

                    }

                }

            });

        });

        $(".status").click(function(){

            var data_id = $(this).attr("data-id");

            var status = $(this).attr("status");

            if(status == 0){

                $.ajax({

                    url : 'update_status.php',

                    type: 'POST',

                    data: {id:data_id, status: 2},

                    success:function(data){

                        //~ alert(1);

                        location.reload();

                    }

                });

            }

            if(status == 2){

                $.ajax({

                    url : 'update_status.php',

                    type: 'POST',

                    data: {id:data_id, status: 1},

                    success:function(data){

                        //~ alert(1);

                        location.reload();

                    }

                });

            }

        });

        /*adding new update */

        $(".pop_upss").click(function(){

            $("#myModal").modal("show");

            var dataid=$(this).data("id");

            var prodid=$(this).data("prodid");

            $("#id").val(dataid);

            $("#p_id").val(prodid);

        });



        $("form#scan").submit(function(e) {

            var orders = $("#scanned_data tr");

            orders.each(function(row, v) {

                $(this).find("td").each(function(cell, v) {

                    if( cell == 2 ) {

                        updateOrder($(this).text());

                    }

                });

            });

            $("#myScanModal").modal("hide");

            $("#scanned_data").html('');

            e.preventDefault();

            window.setTimeout( function() { window.location.reload(); }, 2000 );

        });



        $("form#data").submit(function(e) {

			//alert('adf') ;

            console.log(e);

            e.preventDefault();

            var formData = new FormData(this);

            console.log($(this).serialize());

            var data = {amount: $("#amount").val(),p_id: $("#p_id").val(),id: $("#id").val()};

            $.ajax({

                url: 'update_amount.php',

                type: 'post',

                data: $(this).serialize(),

                success: function (data) {

                    console.log(data);

                   // alert(data);

                    location.reload();

                }

            });

        });



    });

</script>

<script> 


    /*window.setInterval('refresh()', 60000);

    function refresh() {

        if( !   hasClass( document.getElementById("myScanModal"), "hide" ) ) {

            window.location.reload();



        }

    }*/
    setInterval(function(){ 
        
        if( !hasClass( document.getElementById("myScanModal"), "show" ) ) {

            window.location.reload();



        }
    }, 
    60000);

</script>

