<?php
include("config.php");
if(isset($_POST['all_invoice']))
{
	$all_invoice=$_POST['all_invoice'];
	 $q="SELECT order_list.*,sections.name as section_name FROM order_list inner join sections on sections.id=order_list.section_type WHERE order_list.id in($all_invoice)";
	
	$data = mysqli_query($conn,$q);
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
		$tbl_val=$row['invoice_no']."-".$row['section_name']."-".$row['table_type'];
        $item = array('tbl_val'=>$tbl_val,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type, 'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt, 'section_type' => $section_type);
        array_push($array_detail, $item);
    }
	if(count($array_detail)>0)
	{
		$result=array('status'=>true,'record'=>$array_detail);
	}
	else
	{
		$result=array('status'=>false,'record'=>'');
	}
	
   echo json_encode($result);
}
?>
