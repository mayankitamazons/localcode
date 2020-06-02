<?php
session_start();

include("config.php");
$product_id=$_GET['p_id'];
$_SESSION['product_id']=$product_id;
$productdetail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$_SESSION['product_id']."'"));
// print_R($productdetail);
// die;
$product_name=$productdetail['product_name'];
$_SESSION['product_name']=$product_name;
$current_time = date('Y-m-d H:i:s');
if($_SESSION['login']=='') {
    header('Location: '. $site_url .'/login.php');
    die;
}
$action = isset($_GET['action']) ? $_GET['action'] : null;
?>

<!DOCTYPE html>

<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>

    <link rel="stylesheet" type="text/css" href="css/sections.css">

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
                <?php  
                    if($action == 'create') {  
                        include "sub_product/create.php";
                    } else if($action == 'delete') {
                        include 'sub_product/delete.php';
                    } else if($action == 'edit') {
                        include 'sub_product/edit.php';
                    } else if($action == 'toggle-status') {
                        include 'sub_product/toggle_status.php';
                    } else {
                        include 'sub_product/listing.php';
                    }
                ?>
            </div>
        </main>
    </div>
    <!-- /.widget-body badge -->
</div>
<!-- /.content-wrapper -->

<?php include("includes1/footer.php"); ?>
</body>
</html>

