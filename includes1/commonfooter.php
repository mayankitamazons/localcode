<!-- Scripts -->
<script src="./Dashboard_files/jquery.min.js.download"></script>
<script src="./Dashboard_files/popper.min.js.download"></script>
<script src="./Dashboard_files/bootstrap.min.js.download"></script>
<script src="./Dashboard_files/jquery.magnific-popup.min.js.download"></script>
<script src="./Dashboard_files/mediaelementplayer.min.js.download"></script>
<script src="./Dashboard_files/metisMenu.min.js.download"></script>
<script src="./Dashboard_files/perfect-scrollbar.jquery.js.download"></script>
<script src="./Dashboard_files/sweetalert2.min.js.download"></script>
<script src="./Dashboard_files/jquery.counterup.min.js.download"></script>
<script src="./Dashboard_files/jquery.waypoints.min.js.download"></script>
<script src="./Dashboard_files/Chart.min.js.download"></script>
<script src="./Dashboard_files/Chart.bundle.min.js.download"></script>
<script src="./Dashboard_files/utils.js.download"></script>
<script src="./Dashboard_files/jquery.knob.min.js.download"></script>
<script src="./Dashboard_files/jquery.sparkline.min.js.download"></script>
<script src="./Dashboard_files/excanvas.js.download"></script>
<script src="./Dashboard_files/mithril.js.download"></script>
<script src="./Dashboard_files/widgets.js.download"></script>
<script src="./Dashboard_files/moment.min.js.download"></script>
<script src="./Dashboard_files/underscore-min.js.download"></script>
<script src="./Dashboard_files/clndr.min.js.download"></script>
<script src="./Dashboard_files/jquery-ui.min.js.download"></script>
<script src="./Dashboard_files/morris.min.js.download"></script>
<script src="./Dashboard_files/raphael.min.js.download"></script>
<script src="./Dashboard_files/daterangepicker.min.js.download"></script>
<script src="./Dashboard_files/slick.min.js.download"></script>
<script src="./Dashboard_files/theme.js.download"></script>
<script src="./Dashboard_files/isotop.min.js"></script>
<script src="./Dashboard_files/custom.js.download"></script>
<script type="text/javascript">
$(document).ready(function(){
	  $('.logout').click(function(e) {
		 e.preventDefault();
		var logout_type = $(this).attr('type');
		// alert(logout_type);
		var data = {logout_type:logout_type};
		$.ajax({
					 url:"logout.php",
					 type:"post",
					 data:data,
					 dataType:'json',
					 success:function(result){
						var data = JSON.parse(JSON.stringify(result));
						if(data.status==true)
						{
						    localStorage.clear();
							localStorage.removeItem("login_cache_id");
							localStorage.removeItem("login_role_id");
							window.location = "login.php"
						}
						else

					{		 alert('Failed to logout');	}
						
					 }
				 });
	});   
	$(".Logoutpop").on('click', function(event){
		 
       $('#LoginModel').modal('show');
	});
});
</script>
