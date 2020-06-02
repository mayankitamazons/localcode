<?php 
session_start();
include("config.php");
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
$Merchantid = $loginidset;
 $invoice = $_POST['invoice_num'];
 $table = $_POST['table_num'];
 $section = $_POST['section_num'];
 $id = $_POST['id'];
  // $sec = $table[0];
  // $tab = $table[1];
  // print_R($table);
  // die;
if (!empty($invoice)) {
    

$data = mysqli_query($conn, "SELECT * FROM order_list WHERE invoice_no='$invoice' and merchant_id='$Merchantid'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
        $section_type = $row['section_type'];
        $merchant_id = $row['merchant_id'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $remark_ids = explode("|",$row['remark']);
        $product_code = explode(",", $row['product_code']);
        $location = isset($row['location']) ? $row['location'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            }
            $array_product_names[$i] = $product_name;
        }

        $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register FROM users where id='$merchant_id'"));
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];
        $register = $ref_result['register'];
        $item = array('register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type, 'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt, 'section_type' => $section_type);
        array_push($array_detail, $item);
    }
    echo json_encode($array_detail);

}
elseif (!empty($id))
{

$data = mysqli_query($conn, "SELECT * FROM order_list WHERE id='$id'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
         $section_type = $row['section_type'];
        $merchant_id = $row['merchant_id'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $remark_ids = explode("|",$row['remark']);
        $product_code = explode(",", $row['product_code']);
        $location = isset($row['location']) ? $row['location'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            }
            $array_product_names[$i] = $product_name;
        }

        $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register FROM users where id='$merchant_id'"));
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];
        $register = $ref_result['register'];
        $item = array('register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type, 'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt, 'section_type' => $section_type);
        array_push($array_detail, $item);
    }
    echo json_encode($array_detail);

}
else
{
    echo"something went worng";
}


?>