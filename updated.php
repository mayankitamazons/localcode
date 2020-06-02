<?php
$session_file = "./sessioned-user.txt";
if (file_exists($session_file)) {
    $session_user = file_get_contents("./sessioned-user.txt");
}
else {
    exit("[]");
}
$posted = file_get_contents("php://input");

require_once "./config.php";
// $conn = mysqli_connect("localhost", "koofamil_demo", "6bepaAQCM9r-", "koofamil_demo");
if (!$conn)
{
    echo "database error"; die;
}

// Retrieve all order lists
$query = mysqli_query($conn, "SELECT order_list.* FROM order_list where merchant_id='$session_user' ORDER BY `created_on` DESC");

$orders = array();
$now = date_create(date("Y-m-d G:i:s"));

while($r = mysqli_fetch_assoc($query))
{
    $record = date_create($r['created_on']);
    $delay = date_diff($now, $record, true);

    if ($delay->d <= 14)
    {
        $orders[] = $r;
    }
}


header("Content-type: application/json");
echo json_encode($orders);
?>

