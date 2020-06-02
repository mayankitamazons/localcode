<?php

require_once "./config.php";
$session_file = "./sessioned-user.txt";
if (file_exists($session_file)) {
    $session_user = file_get_contents("./sessioned-user.txt");
}
else {
    exit("[]");
}
$posted = file_get_contents("php://input");

if ($posted != "")
    file_put_contents("./post.txt", $posted);
else
    exit("[]");


$localOrders = json_decode($posted);

// Retrieve the non-zero status
$zeros = array();
$completes = array();
$locals = array();

foreach ($localOrders as $order)
{
    /*
    if ($order->status == "0")
    {
        $zeros[] = $order;
    }
    if ($order->status == "2") {
        $completes[] = $order;
    }
    */
    $locals[] = $order;
}

function UpdateOrder($orderid, $orderstatus)
{
    global  $conn;
    //$conn = mysqli_connect("localhost", "root", "", "koofamil_demo");
    $sql = "UPDATE order_list SET status = $orderstatus WHERE id = $orderid";

    //echo $sql . "\n";

    $query = mysqli_query($conn, $sql);
    //mysqli_close($conn);
}

// Retrieve all order lists
$query = mysqli_query($conn, "SELECT order_list.*, users.mobile_number FROM order_list inner join users on order_list.user_id = users.id where order_list.merchant_id='$session_user' ORDER BY `created_on` DESC");

$orders = array();
$now = date_create(date("Y-m-d G:i:s"));

while($r = mysqli_fetch_assoc($query))
{
    $record = date_create($r['created_on']);
    $delay = date_diff($now, $record, true);

    if ($delay->d <= 2)
    {
        $orders[] = $r;
    }

}

$remotes = array();

foreach ($orders as $order)
{
    $found = false;
    $record = null;
    foreach ($locals as $local)
    {

        if ($local->merchant_id == $order["merchant_id"] &&
            $local->user_id == $order["user_id"] &&
            $local->product_id == $order["product_id"] &&
            $local->invoice_no == $order["invoice_no"]
        )
        {
            $found = true;
            $record = $local;
            break;
        }
    }

    if ($found)
    {
        //echo $order["status"] . " - " . $record->status;
        switch (strval($order["status"]))
        {
            case "0":
                switch (strval($record->status))
                {
                    case "1":
                    case "2":
                        // Update online
                        UpdateOrder($order["id"], $record->status);
                        break;
                    default:
                        break;
                }
                break;
            case "1":
                // Do nothing
                break;
            case "2":
                if (strval($record->status) == "1")
                {
                    // Update online
                    UpdateOrder($order["id"], $record->status);
                }
                break;
        }
    }
    else {
        $remotes[] = $order;
    }
}

//print_r($remotes);
// exit("Quit");

// // Retrieve all sub varient 
$query = mysqli_query($conn, "SELECT order_varient.* FROM order_varient where merchant_id='$session_user'");

$order_varient = array();
$now = date_create(date("Y-m-d G:i:s"));

while($r = mysqli_fetch_assoc($query))
{
	$record = date_create($r['created']);
    $delay = date_diff($now, $record, true);

    if ($delay->d <= 2)
    {
        $order_varient[] = $r;
    }
    // $order_varient[] = $r;
}  
   

header("Content-type: application/json");

$inserted = array("orders" => $remotes, "order_varients" => $order_varient);
echo json_encode($inserted);  

?>