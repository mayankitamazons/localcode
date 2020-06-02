<?php 
session_start();
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
 $_SESSION['mm_id']= $loginidset;
//print_r($_SESSION['remark_data']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>POS</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="pos/theme.css" type="text/css"/>
    <link rel="stylesheet" href="pos/style.css" type="text/css"/>
    <link rel="stylesheet" href="pos/posajax.css" type="text/css"/>
    <script type="text/javascript" src="pos/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="pos/jquery-migrate-1.2.1.min.jsg"></script>
     <script type="text/javascript" src="pos/jquery.keyboard.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.extension-all.min.js"></script>
    <script type="text/javascript" src="pos/jquery.keyboard.min.js"></script>
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<div id="wrapper">
    <header id="header" class="navbar">
        <div class="container">
          
            <div class="col-xs-6"> 
              <div class = "col-xs-3"><a class="btn bblue" style="font-size: 32px;" title="Dashboard" data-placement="bottom" href="dashboard.php">
               <i class="fa fa-dashboard" style="color: #fff;"></i></a></div>

              <div class = "col-sm-3"><a class="navbar-brand" href="dashboard.php"><span class="logo"><span class="pos-logo-lg" style="line-height: 35px;">Koofamilies</span><span class="pos-logo-sm">POS</span></span></a></div>

              </div>
        
            <div class="header-nav">

                <ul class="nav navbar-nav pull-right">
                   <li class="dropdown">
                        <a class="btn bblue pos-tip" title="Orderlist" data-placement="bottom" href="orderview.php">
                           Orderlist
                        </a>
                    </li>

                   <!-- <li class="dropdown">
                        <a class="btn bblue pos-tip" title="Dashboard" data-placement="bottom" href="dashboard.php">
                            <i class="fa fa-dashboard"></i>
                        </a>
                    </li> -->
              
               </ul>

               <!-- <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn bblack" style="cursor: default;"><span id="display_time"></span></a>
                    </li>
                </ul> -->
            </div>
        </div>
    </header>
	<style>
	  .show
	  {
		  background-color: #009c7c;
			color: #fff;
		border: 1px solid #eee;
		cursor: pointer;
		height: 50px;
		margin: 0 0 12px 0;
		width: 6.5%;
		min-width: 20px;
		overflow: hidden;
		display: inline-block;
		font-size: 13px;

	  }
	</style>
	 <div class="container" style="margin-top:2%;background:#51D2B7;">
			<?php
				$sql = mysqli_query($conn,"select * from sections where user_id ='".$_SESSION['mm_id']."'");
                while($data = mysqli_fetch_assoc($sql)){
					// print_r($data);
					$section_name=$data['name'];
				?>
				<h2><?php echo $section_name; ?></h2>
				<div class="row">
				
					<?php for($i=1;$i<=100;$i++)
						{ 
					$url="pos.php?t=".$i."&s=".$section_name;
					?>
					<a href="<?php echo $url; ?>">
						<div class="col-md-1 show">
						  <?php echo $section_name."-".$i; ?>
						</div>
					</a>
					<?php } ?>
				
				</div>
			<?php } ?>
	</div>
</div>
</body>
</html>