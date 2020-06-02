<?php

require_once "./config.php";
$posted = file_get_contents("php://input");
$orders = json_decode($posted);

$session_file = "./sessioned-user.txt";
if (file_exists($session_file)) {
    $session_user = file_get_contents("./sessioned-user.txt");
}
else {
    exit("[]");
}

if (!$conn)
{
    echo "database error"; die;
}


// print_r($orders->users);
$query = mysqli_query($conn, "SELECT * FROM order_varient WHERE order_varient.merchant_id  = $session_user");
foreach ($orders->order_varients as $order_varient)
{

    $found = false;

    $item = $order_varient;

    while($row = mysqli_fetch_assoc($query))
    {
         if (($row['v_code']) ==($order_varient->v_code))
			{  
				$found = true;
				break;
			}
            
    }

    if (!$found)
    {
        // mysqli_query($conn, $sql);
        $columns = implode(", ",array_keys($item));
        $escaped_values = array_map('mysql_real_escape_string', array_values($item));
        $values  = "'".implode("', '", $escaped_values)."'";
		 $sql = "INSERT INTO `order_varient` (`v_code`,`id`,`product_id`, `varient_id`, `order_id`, `invoice_no`, `merchant_id`, `v_order`) " .
                "VALUES ('{$order_varient->v_code}',NULL, '{$order_varient->product_id}', '{$order_varient->varient_id}', '{$order_varient->order_id}', '{$order_varient->invoice_no}', '{$order_varient->merchant_id}', " .
                "'{$order_varient->v_order}')";

        // $sql = "INSERT INTO `order_varient`($columns) VALUES ($values)";

        mysqli_query($conn, $sql);

    }
}



  
$query = mysqli_query($conn, "SELECT * FROM order_list WHERE order_list.merchant_id  = $session_user");



if (isset($orders->orders)) {
    if ($orders->orders != null) {
        foreach ($orders->orders as $order) {

            $update = "UPDATE `order_list` SET " .
                "`product_id` = '{$order->product_id}', `user_id` = '{$order->user_id}', `merchant_id` = '{$order->merchant_id}'," .
                "`quantity` = '{$order->quantity}', `amount` = '{$order->amount}', `wallet` = '{$order->wallet}', `created_on` = '{$order->created_on}', " .
                "`location` = '{$order->location}', `table_type` = '{$order->table_type}', `status` = '{$order->status}', `remark` = '{$order->remark}', " .
                "`invoice_no` = '{$order->invoice_no}', `popup` = '{$order->popup}', `product_code` = '{$order->product_code}',`status_change_date` = '{$order->status_change_date}', " .
                "`section_type` = '{$order->section_type}',`varient_type` = '{$order->varient_type}' WHERE `order_list`.`id` = ##"; 
    
           $sql = "INSERT INTO `order_list` (`id`, `product_id`, `user_id`, `merchant_id`, `quantity`, `amount`, `wallet`, `created_on`, `location`, " .
                "`table_type`, `status`, `remark`, `invoice_no`, `popup`, `product_code`, `status_change_date`, `section_type`,`varient_type`,`order_place`,`user_name`,`user_mobile`,`wallet_paid_amount`,`discount_amount`,`membership_discount`,`remark_extra`) " .
                "VALUES (NULL, '{$order->product_id}', '{$order->user_id}', '{$order->merchant_id}', '{$order->quantity}', '{$order->amount}', " .
                "'{$order->wallet}', '{$order->created_on}', '{$order->location}', '{$order->table_type}', '{$order->status}', " .
                "'{$order->remark}', '{$order->invoice_no}', '{$order->popup}', '{$order->product_code}', '{$order->status_change_date}', '{$order->section_type}','{$order->varient_type}','{$order->order_place}','{$order->user_name}','{$order->user_mobile}','{$order->wallet_paid_amount}','{$order->discount_amount}','{$order->membership_discount}','{$order->remark_extra}')";

      
            $found = 0;   
            while ($row = mysqli_fetch_assoc($query)) {
                if (strval($row['created_on']) == strval($order->created_on) &&
                    strval($row['invoice_no']) == strval($order->invoice_no)
                ) {
                    switch (intval($order->status)) {
                        case 0:
                            // Do not update nor insert
                            $found = 2;
                            break;
                        case 1:
                            if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                // Update
                                $found = 1;
                            }
                            break;
                        case 2:
                            if (intval($row['status']) == 0) {
                                // Update
                                $found = 1;
                            }
                            break;
                    }
                    $update = str_replace("##", $row["id"], $update);

                    // $found = true;
                    break;
                } else {
                    if (strval($row['merchant_id']) == strval($order->merchant_id) &&
                        strval($row['invoice_no']) == strval($order->invoice_no)) {
                        switch (intval($order->status)) {
                            case 0:
                                // Do not update nor insert
                                $found = 2;
                                break;
                            case 1:
                                if (intval($row['status']) == 0 || intval($row['status']) == 2) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                            case 2:
                                if (intval($row['status']) == 0) {
                                    // Update
                                    $found = 1;
                                }
                                break;
                        }
                        $update = str_replace("##", $row["id"], $update);

                        //$found = true;
                        break;
                    }

                }
            }

            echo "$found\n";
            if ($found == 1) {
                echo "$update\n";
                mysqli_query($conn, $update);
            } else {
                if ($found == 0) {
                    echo "insert\n";
                    mysqli_query($conn, $sql);
					
                } else {
                    // auto print is not enabled do manully print
                }


            }
        }
    }
	
}







?>