<?php

require_once "./config.php";
$posted = file_get_contents("php://input");
$orders = json_decode($posted);

//$conn = mysqli_connect("localhost", "koofamil_demo", "6bepaAQCM9r-", "koofamil_demo");
if (!$conn)
{
    echo "database error"; die;
}




$query = mysqli_query($conn, "SELECT * FROM order_list");
// print_R($order);
	// die;
foreach ($orders as $order)
{
	
    $update = "UPDATE `order_list` SET " .
                  "`product_id` = '{$order->product_id}', `user_id` = '{$order->user_id}', `merchant_id` = '{$order->merchant_id}'," .
                  "`quantity` = '{$order->quantity}', `amount` = '{$order->amount}', `wallet` = '{$order->wallet}', `created_on` = '{$order->created_on}', " .
                  "`location` = '{$order->location}', `table_type` = '{$order->table_type}', `status` = '{$order->status}', `remark` = '{$order->remark}', " .
                  "`invoice_no` = '{$order->invoice_no}', `popup` = '{$order->popup}', `product_code` = '{$order->product_code}', `status_change_date` = '{$order->status_change_date}', " .
                  "`section_type` = '{$order->section_type}', " .
                  "`printed` = '{$order->printed}' WHERE `order_list`.`id` = ##";

    $sql = "INSERT INTO `order_list` (`id`, `product_id`, `user_id`, `merchant_id`, `quantity`, `amount`, `wallet`, `created_on`, `location`, " .
                                      "`table_type`, `status`, `remark`, `invoice_no`, `popup`, `product_code`, `status_change_date`, `section_type`, `printed`) " .
            "VALUES (NULL, '{$order->product_id}', '{$order->user_id}', '{$order->merchant_id}', '{$order->quantity}', '{$order->amount}', " .
                          "'{$order->wallet}', '{$order->created_on}', '{$order->location}', '{$order->table_type}', '{$order->status}', " .
                          "'{$order->remark}', '{$order->invoice_no}', '{$order->popup}', '{$order->product_code}', '{$order->status_change_date}', '{$order->section_type}', '{$order->printed}')";


  
    $found = false;
    while($row = mysqli_fetch_assoc($query))
    {
        if (strval($row['created_on']) == strval($order->created_on) &&
			strval($row['invoice_no']) == strval($order->invoice_no)
		)
        {
            $update  = str_replace("##", $row["id"], $update);

            $found = true;
            break;
        }
    }

    if ($order->invoice_no == "13" && $found)
    {
       // echo "Found: $update\n";
    }

    if ($found)
    {
        // echo "$update\n";
        mysqli_query($conn, $update);
    }
    else {
        //echo "$sql\n";
        mysqli_query($conn, $sql);
		$inserted_id=mysqli_insert_id($conn);
		$mrchat_id=$order->merchant_id;
		$user_id=$order->user_id;
		// check setting of user is auto print is enabled or not 
		$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting, sst, gst, register FROM users WHERE id='".$mrchat_id."'"));
		if( $ref_result['order_print_setting'] === 'on' ) {
			$register=$ref_result['register'];
			$sst=$ref_result['sst'];
			$gst=$ref_result['gst'];
			$merchant_name=$ref_result['name'];
			$array_product_names;
			$product_ids=explode(",",$order->product_id);
			$product_qty = explode(",", $order->quantity);
			$product_amt = explode(",", $order->amount);
			for($i = 0;  $i < count($product_ids); $i++){
				if(is_numeric($product_ids[$i])){
					$product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
				} else {
					$product_name = $product_ids[$i];
				}
				$array_product_names[$i] = $product_name;
			}
			$order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
			// when new value inserted make auto print of that 
			$item = array('product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $order->product_code, 'table_type' =>$order->table_type,'section_type'=>$order->section_type,'location' => $order->location, 'remark' => $order->remark, 'invoice_no' =>$order->invoice_no, 'status' =>$order->status , 'id' => $inserted_id, 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
			array_push($array_detail, $item);
			$request=json_encode($array_detail);
			$date = date('m/d/Y', time());
			$time = date('h:i:s a', time());
			$post = [
				'order' => $request,
				'method' =>"pintOrder",
				'date' =>$date,
				'time'   => $time,
		];

		$ch = curl_init('https://www.koofamilies.com/functions.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		// execute!
		$response = curl_exec($ch);
        
		// close the connection, release resources used
		curl_close($ch);

		// do anything you want with your response
		var_dump($response);
				}
		else
		{
			// auto print is not enabled do manully print 
		}
		
		
    }
}

//mysqli_close($conn);

?>