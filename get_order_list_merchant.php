<?php 
	
	include("config.php");
	
      $merchant_id = $_POST['merchant'];
	   
	// $merchant_id = 634;
	$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$merchant_id."'"));
	// print_r($merchant_detail);
	// die;
	 $sstper = $merchant_detail['sst_rate'];
	
	$query="SELECT order_list.*, sections.name as section_name FROM order_list left join sections on order_list.section_type = sections.id WHERE merchant_id ='".$merchant_id."' ORDER BY `created_on` DESC LIMIT 0,50";

	$total_rows = mysqli_query($conn,$query);

	$dt = new DateTime();

    $today =  $dt->format('Y-m-d');

	$result = array();

	$current_time = date('Y-m-d H:i:s');
	while ($row=mysqli_fetch_assoc($total_rows)){
		
        $product_ids = explode(",",$row['product_id']);

        $quantity_ids = explode(",",$row['quantity']);

        $amount_val = explode(",",$row['amount']);

        $product_code = explode(",",$row['product_code']);

        $amount_data = array_combine($product_ids, $amount_val);

        $total_data = array_combine($quantity_ids, $amount_val);



        $created =$row['created_on'];

        $remark_ids = explode("|",$row['remark']);

        $new_time = explode(" ",$created);

        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$row['product_id']."'"));



        $user_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));



       

        $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['merchant_id']."'"));

        $date=date_create($created);

       $status=$row['status'];
	   if($status==1)
	   {
		  $callss = "gr"; $s_color="green";  
	   } else if($status==2)
	   {
		    $callss = "or"; 
			$s_color="";
	   }  	else {
			$callss = " ";
				$s_color="red";
	   }
        

        $todayorder = $today == $new_time[0] ? "red" : "";

      	$dteDiff  = date_diff($date, date_create($current_time));
      	$diff_day = $dteDiff->d;
      	if($diff_day != '0') $diff_day .= ' days ';
      	else $diff_day = '';
      	$diff_hour = $dteDiff->h;
      	if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
      	$diff_minute = $dteDiff->i;
      	if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute.':';
      	$diff_second = $dteDiff->s;
      	if($diff_second < 10) $diff_second = '0'.$diff_second;
      	$diff_time = $diff_day.'<br>'.$diff_hour.$diff_minute.$diff_second;


        $i1 =1;

        if($row['status'] == 0) $sta = "Pending";

        else if($row['status'] == 1) $sta = "Done";

        else $sta = "Accepted";

        $quantities = "";
        foreach ($quantity_ids as $key){

        	$quantities .= $key . '<br>';
        }

        $product_name = "";
        foreach ($product_ids as $key ){

            if(is_numeric($key))

            {

                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));


                $product_name .= $product['product_name'].'<br>';

            }

            else

            {
            	$product_name .= $key .'<br>';

            }

        }

        $remarks = "";
        foreach ($remark_ids as $val) {
        	$remarks .= $val.'<br>';
		}

		$product_key = "";
        foreach ($product_code as $key) {
        	$product_key .= $key."<br>";
        }

        $amount_value = "";
        foreach ($amount_val as $key => $value){

        	$amount_value .= @number_format($value, 2).'<br>';

        }

        $q_id = 0;

        $quantity_val = '';
        foreach ($amount_val as $key => $value){

            $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));

            if($value == '0') { ?>

            <?php  }

            if( $quantity_ids[$key] && $value ) {

                // $quantity_val .= @number_format($quantity_ids[$key] * $value, 2).'<br>';
                $quantity_val .= @number_format($value, 2).'<br>';

            } else {

               	$quantity_val .= '<p class="pop_upss" data-id=' . $row['id'] . '  style="margin-bottom: 0px;display:block;" data-prodid="' . $key . '""><i class="fa fa-pencil-square-o" aria-hidden="true"></i>0</p>';

            }

            $q_id++;

        }

        $total = 0;

        $total_val = "";

        foreach ($amount_val as $key => $value){

            if( $quantity_ids[$key] && $value ) {

                $total =  $total + ($quantity_ids[$key] *$value );

            } 

        }

         $total_val .= @number_format($total, 2);
		
		 if($sstper>0){ 
				 $incsst = ($sstper / 100) * $total_val;
				// $incsst=@number_format($incsst, 2);
				$incsst=ceiling($incsst,0.05);
				$incsst=@number_format($incsst, 2);
				 $g_total=@number_format($total_val+$incsst, 2);
				
			}
		else{
			$incsst=0;
			$g_total=0;
			$sstper=0;
		}
		// echo $g_total;
		// echo $g_total;
		// die;
		
        $lock_mobile = "";
    	$lock_mobile = $row['user_mobile'];
		$account_type = "";
		// if($sta == "Done"){
			// $account_type = $user_name['account_type'];
		// }else {
			// $account_type = "";
		// }
		$account_type = "";
		$varient_type = "";
		 if($row['varient_type'])
							 {
							$v_str=$row['varient_type'];
							$v_array=explode("|",$v_str);
							foreach($v_array as $vr)
							{
								
								if($vr)
								{
									$v_match=$vr;
									$v_match = ltrim($v_match, ',');
									$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
									while ($srow=mysqli_fetch_assoc($sub_rows)){
										 $varient_type.=$srow['name'];
										$varient_type.="&nbsp;&nbsp;";
									}
								}
								 else
								 {
									 // echo "</br>";
								 }
								 $varient_type.="<br/>";
							}
							 }
		else
		{    
			$varient_type='';
		}
        $item = array("sstper"=>$sstper,"incsst"=>$incsst,"g_total"=>$g_total,"varient_type"=>$varient_type,"id" => $row['id'], "invoice_no" => $row['invoice_no'], 
        				's_color'=>$s_color,'callss' => $callss, "todayorder" => $todayorder, 'order_from'=>$row['order_place'],
        				'merchant_name' => $merchant_name['name'], 'user_mobile_number' => $row['user_mobile'], 'merchant_mobile_number' => $merchant_name['mobile_number'], 'merchant_google_map' => $merchant_name['google_map'], 'date' => date_format($date,"m/d/Y"), 'new_time' => $new_time[1], 'status' => $row['status'], 'diff_time' => $diff_time, 'user_name' => $row['user_name'], 'sta' => $sta, 'section_type'=>$row['section_name'],'table_type' => $row['table_type'], 'product_name' => $product_name, 'remark' => $remarks, 'product_code' => $product_key, 'amount_val' => $amount_value, 'quantity_val' => $quantity_val, 'total_val' => $total_val, 'wallet' => $row['wallet'], 'location' => $row['location'], 'lock_mobile' => $lock_mobile, 'user_id' => $row['user_id'], 'quantities' => $quantities, 'account_type' => $account_type);
        
		array_push($result, $item);
    }
    echo json_encode($result);
   function ceiling($number, $significance = 1)
								{
									return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
								}
?>