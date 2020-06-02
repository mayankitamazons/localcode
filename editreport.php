<?php
include("config.php");
if ($_GET['state'] == 2) {
    $query="INSERT INTO work_diary (user_id, start_date, end_date)
            VALUES ('".$_SESSION['user_id']."', '".$_GET['startdate']."', 'null');";
} else {
    $query="UPDATE work_diary
            SET end_date = '".$_GET['enddate']."'
            WHERE user_id = '".$_SESSION['user_id']."' AND start_date = '".$_GET['startdate']."';";
}
$result = mysqli_query($conn,$query);
return $result;
?>