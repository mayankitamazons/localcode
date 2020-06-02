<?php
include("config.php");

if(isset($_SESSION['login']))
{
	header("location:dashboard.php");
	
}   $email = addslashes($_POST['email']);	 
    if(isset($_POST['email'])) {
        $email = $_POST['email'];
        	$already_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"));

            if( $already_exists > 0 ){
            echo 'false';
        } else {
            echo 'true';
        }
    }
    if(isset($_POST['mobile_number'])) {
        $mobile_number = $_POST['mobile_number'];
        $countrycode = $_POST['countrycode'];
         $cm =	$countrycode.''.$mobile_number;
        	$alreadymob_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm'"));
            if( $alreadymob_exists > 0 ){
            echo 'false';
        } else {
            echo 'true';
        }
    }
?>