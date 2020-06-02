<?php 
include_once 'config.php';
if(isset($_POST['userid']))
{
    $userid = $_POST['userid'];
    $title = $_POST['title'];
    $img = $_POST['img'];
    $shareon = $_POST['shareon'];
    $d = date("Y-m-d");
    mysqli_query($conn,"INSERT into share_social values(NULL,'$userid','$title','$img','$shareon','$d')");
    mysqli_query($conn,"UPDATE `order_list` set popup = 1 WHERE status_change_date = CURDATE() and status = 1 and popup = 0 and user_id =$userid");
}
else
{
if($_GET)
{
    $title = $_GET['title'];
    $image = "https://kooexchange.com/demo/upload/".$_GET['image'];
    
}
?>
<html>
<head>
	<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:url" content="https://kooexchange.com/demo/share.php?title=<?php echo $title; ?>&image=<?php echo $image; ?>" />
<meta property="og:description" content="<?php echo $title; ?>" />
<meta property="article:published_time" content="<?php echo date("Y-m-d h:i:m") ?>" />
<meta property="og:site_name" content="Kooexchange.com" />
<meta property="og:image" content="<?php echo $image; ?>" />
<meta property="og:image:width" content="600" />
<meta property="og:image:height" content="400" />
<meta property="og:locale" content="en_US" />
<meta name="twitter:site" content="<?php echo $title; ?>" />
<meta name="twitter:text:title" content="<?php echo $title; ?>" />
<meta name="twitter:card" content="<?php echo $title; ?>" />
<meta property="article:publisher" content="<?php echo $image; ?>" />

</head>
<body>
    
</body>
</html>
<?php
}

?>
<script>window.location.href="https://kooexchange.com/demo/";</script>