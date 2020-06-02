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
   
   //added by bala 02/08/2019
   require_once('inc/config.php');
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
       <link href="assets/vendor/jquery-confirm/jquery-confirm.min.css" rel="stylesheet">
       <link href="assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
       <link href="assets/vendor/datatable/css/datatables.min.css" rel="stylesheet">
      <style>
      	.att-output {
			padding-top: 30px;
		}
      </style>
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
      <?php 		 $parentid = $_SESSION['login'];
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
         <div  id="main-content" style="padding-top:25px">
				   <div class="well  col-sm-12">
				     <h3 class="text-center mb-5">Transaction History</h3>
					<form action="" method="post" style="width:100%;" class="frmattendance">
						<input type="hidden" value="<?php echo $profile_data['id']; ?>" name="user_id">
						<div class="row">
							<div class="col-sm-3 search-item">
								<div class="form-group input-has-value">
									<label>From Date: </label>
									<div class="input-group date   input-has-value">
                                        <input type="text" size="16" class="form-control" name="from_date" id="from_date" value="">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-icon" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
								</div>
							</div>
							<div class="col-sm-3 search-item">
								<div class="form-group input-has-value">
									<label>To Date: </label>
									<div class="input-group date   input-has-value">
                                        <input type="text" size="16" class="form-control" name="to_date" id="to_date" value="">
                                        <span class="input-group-addon" style="padding: 0.3rem;">
                                            <button class="btn default date-icon" type="button" style="padding: 0.3rem;">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
								</div>
							</div>
							<div class="col-sm-4 search-item">
								<div class="form-group input-has-value">
									<label>Tel Number: </label>
										<div class="input-group date   input-has-value">
											<input type="text" size="16" class="form-control" name="to_date" id="to_date" value="">
										</div>

								</div>
							</div>
						 </div>
						<div class="row ">
							<div class="col-sm-12 text-right search-item input-has-value">
							     <div style="display: none;" class="alert alert-msg text-center"></div>
								<button type="button"  class="btn btn-success btn-search" id="btn-search">SEARCH</button>
							</div>
							
						</div>
					    <div style="display: none;" class="row att-output">
					    	<div class="col-sm-12">
					    		 <table class="table  tbl-staff-attend table-bordered">
									<thead>
									  <tr class="bg-dark">
									  	<th>Day of week</th>
									  	<th>Date</th>
									  	<th>Staff</th>
									  	<th>Time In</th>
									  	<th>Time Out</th>
									  	<th>Hours Worked</th>
									  </tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										
									</tfoot>
								 </table>

					    	</div>
					    </div>
					
					</form>
					
					
					</div>
					
					
				</div>
      </main>
      <!-- /.widget-bg -->
      <!-- /.content-wrapper -->
      <?php include("includes1/footer.php"); ?>
      <script src="assets/vendor/jquery-confirm/jquery-confirm.min.js"></script>
      <script src="assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
      <script src="assets/vendor/datatable/js/datatables.min.js"></script>
      <script>
		var base_url = "<?php echo BASE_URL; ?>";
		
		(function ($) {
    		"use strict";
    		 $('#from_date').datepicker({
                    format: "yyyy-mm-dd"
                }).on('changeDate', function(ev){
			    $(this).datepicker('hide');
				 $('#to_date').data('datepicker').setStartDate($(this).val());
			}); 
				
				$('#to_date').datepicker({
                    format: "yyyy-mm-dd",
					startDate:$('#from_date').val()
                }).on('changeDate', function(ev){
			    $(this).datepicker('hide');
			}); 
			$('.frmattendance .date-icon').click(function(e) {
				$(this).closest('.date').find('.form-control').focus();
			});
    		$('.frmattendance .btn-search').click(function(e) {
	        	 get_staff_attendance();
	        });
    		function get_staff_attendance()
    		{
				 /*	if($('#staff').val() == "")
				 	{
						 $.alert({
							    title: 'Error',
							     type: 'red',
						        icon: 'fa fa-warning',
							    content: "Please select staff",
							});
							
						return false;
					}
					*/
					if($('#from_date').val() == "")
				 	{
						 $.alert({
							    title: 'Error',
							     type: 'red',
						        icon: 'fa fa-warning',
							    content: "Please select from date",
							});
					  return false;
					}
					if($('#to_date').val() == "")
				 	{
						 $.alert({
							    title: 'Error',
							     type: 'red',
						        icon: 'fa fa-warning',
							    content: "Please select to date",
							});
					  return false;
					}
				 	
				 	$('.att-output').hide();
				 	var formdata = $('.frmattendance').serialize();
				 	var btn = $('.frmattendance .btn-search');
				 	$(btn).attr("disabled", true);
				 	 var loading_txt = '<div class="spinner-border text-info" role="status"></div><div class="">Processing your request</div>';
			        $('.frmattendance div.alert-msg').removeClass('alert-danger alert-success alert-info').html('');
			        $('.frmattendance div.alert-msg').addClass('alert-info').html(loading_txt);
			        $('.frmattendance div.alert-msg').fadeIn();
			        $('.tbl-staff-attend tbody').html('');
				 	
					var url = base_url + '/inc/ajax.php?action=get_staff_attendance'
					var options = {
			            type: 'post',
			            url: url,
			            dataType: 'json',
			            data: formdata,
			            cache: false,
			            beforeSend: function() {
						
			            },
			            success: function(data) {
			                if (data != null) {
			                	 $('.frmattendance div.alert-msg').removeClass('alert-danger alert-success alert-info').html('');
               					 $(btn).attr("disabled", false);
               					 $('.att-output').show();
               					 var html = "";
               					 var total_min_worked = 0;
			                    if (data.success == true) {
			                        $.each(data.ret, function( key, obj ) 
			                        {
			                        
			                        	 total_min_worked += parseInt(obj.min_worked); 
			                        	
			                        	
			                        	html += '<tr>';
			                        	html += '<td class="text-center">' + obj.day_of_week + '</td>';
			                        	html += '<td>' + obj.login_date + '</td>';
			                        	html += '<td>' + obj.staff_name + '</td>';
			                        	html += '<td>' + obj.time_in + '</td>';
			                        	html += '<td>' + obj.time_out + '</td>';
			                        	html += '<td>' + obj.hours +':'+  ("0" + obj.mins).slice(-2) +'</td>';
			                        	html += '</tr>';
			                        });
			                        
			                        if(html == "")
			                        {
										html += '<tr>';
			                        	html += '<td colspan="6" class="text-center">No record found</td>';
			                        	html += '</tr>';
									}
									else
									{
										
										var total_minutes = Math.floor(total_min_worked % 60);
										total_minutes = ("0" + total_minutes).slice(-2);
										var total_hours = Math.floor(total_min_worked / 60);
										
										var tfoot_html = '<tr><td></td><td></td><td></td><td></td>';
										
										 tfoot_html += '<td><strong class="text-dark">Total Hours Worked</strong></td><td class="text-left"><strong class="text-dark">'+ total_hours +':'+  total_minutes +'</strong></td></tr>';
										$('.tbl-staff-attend tfoot').html(tfoot_html);
									}
			                        
			                       
			                        
			                        if ( $.fn.DataTable.isDataTable('.tbl-staff-attend') ) {
									  $('.tbl-staff-attend').DataTable().destroy();
									}

									$('.tbl-staff-attend tbody').empty();
									
									 $('.tbl-staff-attend tbody').html(html);
			                        
			                        $('.tbl-staff-attend').DataTable({
									    "dom": "<'row'<'col-12'il><'col-md-3 col-sm-3'B><'col-md-9 col-sm-9'p>><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable,                  
									    searching: false,
									    paging: false,
									    info: false,
										"ordering": false,
									    responsive: false,
									    "order": [
									        [0, 'asc']
									    ],
									     buttons: [
									            { 
										         extend: 'csv', text: '<i class=" fa fa-download"></i> CSV' , className: 'btn-success' , title: 'Staff Time Sheet' , footer: true
												},
									            { 
										         extend: 'excel', text: '<i class="fa fa-download"></i> EXCEL' , className: 'btn-danger' , title: 'Staff Time Sheet' , footer: true
												}
									        ]
									});
						                                              
										
			                    } else {
			                         $.alert({
										    title: 'Error',
										     type: 'red',
									        icon: 'fa fa-warning',
										    content: data.message,
										});
			                    }
			                }
			            },
			            error: function(request, status, error) {
			                 $('.frmattendance div.alert-msg').removeClass('alert-danger alert-success alert-info').html('');
               					 $(btn).attr("disabled", false);
			                alert(status + ", " + error);
			               
			            }
			        }; // end ajax  
			        $.ajax(options);
			        return false;
			}
    	
		
		})(jQuery);
	</script>
   </body>
</html>
