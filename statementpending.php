<?php
	session_start();
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	include("config.php");
	// error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfiles\SimpleCapabilityProfile;

	if($_POST)
	{
		// print_R($_POST);
		// die;
		$invo = $_POST['invo'];
		foreach($invo as $i)
		{
			$data = mysqli_query($conn, "SELECT * FROM order_list WHERE invoice_no='$i'");
			mysqli_query($conn, "UPDATE order_list SET status='1', status_change_date = CURDATE() WHERE invoice_no='$i'");
			$array_detail = array();
			while ($row=mysqli_fetch_assoc($data)){
				$user_id = $row['user_id'];
				$merchant_id = $row['merchant_id'];
				$varient_type = $row['varient_type'];
				$product_ids = explode(",",$row['product_id']);
				$product_qty = explode(",", $row['quantity']);
				$product_amt = explode(",", $row['amount']);
				$product_code = explode(",", $row['product_code']);
				$remark_ids = explode("|",$row['remark']);
				$location = isset($row['location']) ? $row['location'] : '';
				$section_type = isset($row['section_type']) ? $row['section_type'] : '';
				$table_type = isset($row['table_type']) ? $row['table_type'] : '';
				$user_id = isset($row['user_id']) ? $row['user_id'] : '';
				$array_product_names;
				for($i = 0;  $i < count($product_ids); $i++){
					if(is_numeric($product_ids[$i])){
						$product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
					} else {
						$product_name = $product_ids[$i];
					}
					$array_product_names[$i] = $product_name;
				}
				// $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
				$order_name=$row['user_name'];
				$user_mobile=$row['user_mobile'];
				$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register,address FROM users where id='$merchant_id'"));
				$register = $ref_result['register'];
				$merchant_name = $ref_result['name'];
				$sst = $ref_result['sst'];
				$gst = $ref_result['gst'];  
				if($ref_result['address'])
				{
					$register=$ref_result['address'];
				}
				else
				{
					$register=$ref_result['register'];
				}
				$item = array('merchant_id'=>$merchant_id,'user_mobile'=>$user_mobile,'varient_type'=>$varient_type,'product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
				array_push($array_detail, $item);     
			}  
			// $array_detail
			$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style,printer_profile,usb_name FROM users WHERE id='".$_SESSION['login']."'"));
			// print_R($ref_result);
			// die;
			$ip_address = $ref_result['print_ip_address'];
			 $date = date("Y-m-d");
			 $time = date("h:i A");
				// $time = $_POST['time'];
	// die;
			$print_report=OrderCustomprint($ip_address,$array_detail,$date,$time,$conn,$ref_result);
		}
		 header("Location: ".$site_url."/orderview.php");
		
	}
	function OrderCustomprint($ip_address,$order,$date,$time,$conn,$merchat_detail)
	{     
	     // print_R($order);
		 // die;
	   	if( $order == null ) {
			$res['status']=false;
			$res['message']="Failed to make print Due to Order Blank";
			
			//echo( "empty" );
			} else {
				
				// $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$order['user_id']."'"));
			// if( ! isset( $ref_result['mobile_number'] ) ) {
				// $res['status']=false;
			// $res['message']="User id Not found";
				
			// }
			// print_R($merchat_detail);
			// die;
			 $printer_t=$merchat_detail['printer_profile'];
		      
			try {
					if($printer_t=="usb")
					{
						$usb_name=$merchat_detail['usb_name'];
						$connector = new WindowsPrintConnector($usb_name);
					}
					else
					{
						$connector = new NetworkPrintConnector($ip_address, 9100,5);
					}
				
				
			} catch( Exception $e ) {
				//echo('print_setting_error');
				//echo(print_r($e, true));
				// die();
				$res['status']=false;
				$res['message']="Printer is not connected";
			}
			// $res['status']=false;
			// $res['message']="Printer is not connected";
			//echo(print_r($connector, true));
			
			// print_R($order);
			// die;
			// 3 + 16 + 8 + 5 + 7 + 7
			$mobile = $order['user_mobile'];
			$section_id=$order['section_type'];
			$section_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id='".$order['section_type']."'"));
			// print_R($section_data);
			// die;
			$section_name=$section_data['name'];
			$invoce_id=$order['invoice_no'];
			$order_id=$order['id'];
			$printer = new Printer($connector);
			$printer -> getPrintConnector() -> write(PRINTER::ESC . "B" . chr(4) . chr(1));
			try {
				
				$printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( $order['merchantname'] . "\n" );
				// $printer -> text("\n");
				if($order['register'])
				{
					$printer -> selectPrintMode(Printer::MODE_FONT_A);
					$printer -> setEmphasis(false);
					$printer -> text( '( ' . $order['register'] . ' )' . "\n" );
					// $printer -> text("\n");
				}
				if($order['username'])
				{
					$printer -> setEmphasis(true);
					$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
					$printer -> textChinese( "Customer : " . $order['username'] . "\n" );
					// $printer -> text("\n");
					// $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
					 // $printer -> selectPrintMode(Printer::MODE_FONT_A);
				}
				if($mobile)
				{
					$printer -> setEmphasis(false);
					$printer -> text( 'Phone : ' . $mobile . "\n" );
					// $printer -> text("\n");
				}
				
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> setEmphasis(false);
				// $printer -> text("\n");
				$location = $order['location'];
				$words = explode(" ", $location);
				$rows_locations = [];
				$rows_location = '';
				for( $i = 0 ; $i < sizeof( $words ) ; $i ++ ) {
					
					$word = $words[$i];
					$word .= ' ';
					if( strlen( $rows_location ) + strlen( $word ) < 30 ) {
						$rows_location .= $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					} else {
						array_push( $rows_locations, $rows_location);
						$rows_location = $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					}
				}
				foreach( $rows_locations as $item ) {
					$printer -> text( ' ' . $item . "\n" );
					// $printer -> text("\n");
				}
				if($order['gst'])
				{
				 $printer -> text( 'GST ID : ' . $order['gst'] . "\n" );
				}
				// $printer -> text("\n");
				if($order['sst'])
				{
				 $printer -> text( 'SST NO : ' . $order['sst'] . "\n" );
				}
				// $printer -> text("\n");
				// $printer -> text("\n");
				
				if($order['invoice_no'])
				{
					$printer -> barcode( $order['id'] . '-' . $invoce_id, Printer::BARCODE_CODE39 );  
				}     
				// $printer -> text("\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text(  $order['id'] . '-' . $invoce_id . "\n" );
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text("  Table : " . $order['table_type'] . "  "."Section : " .$section_name. "\n");
				 // $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				// $printer -> text("  Section : " . $order['section_type'] . "\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text("\n"); 
				$printer -> setEmphasis(false);
				$printer -> text( '  ' . $date . ' ' . $time . "\n");
				$printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text( "  No  Name( Code )    Qty  Remark  Price  Amount " . "\n");
				$printer -> text("\n");
				$total = 0;
				$qty_total = 0;
				$v_order=1;
				
				for( $i = 0 ; $i < sizeof( $order['product_name'] ) ; $i ++ ) {
					
					if( $order['product_qty'][$i] && $order['product_amt'][$i] ) {
						$amount = $order['product_qty'][$i] * $order['product_amt'][$i];
					} else {
						$amount = 0;
					}
					$qty_total += $order['product_qty'][$i];
					$total += $amount;
					$amount=number_format($amount,2);
					$total=number_format($total,2);
					$remark = isset($order['remark'][$i]) ? $order['remark'][$i] : '';
					$product_code = isset($order['product_code'][$i]) ? $order['product_code'][$i] : '';
					$product_code  = '(' . $product_code . ')';
					$name = $order['product_name'][$i];
					$product_id = $order['product_ids'][$i];
					$qty = $order['product_qty'][$i];
					$price = $order['product_amt'][$i];
					$name .= $product_code;
					$number = $i + 1;
					$size_number = 3;
					$size_name = 12;
					$size_remark = 7;
					$size_qty = 4;
					$size_price = 6;
					$size_amount = 6;
					$lines = max(intval( strlen($number) / $size_number) , intval( strlen($name) / $size_name) , intval( strlen($remark) / $size_remark) , intval( strlen($qty) / $size_qty) , intval( strlen($price) / $size_price) , intval( strlen($amount) / $size_amount) );
					$lines ++;
					$v_data =mysqli_query($conn, "SELECT varient_id FROM order_varient WHERE product_id='$product_id' and invoice_no='$invoce_id' and v_order='$v_order'");
					for( $j = 0 ; $j < $lines ; $j++) {  
						$number_print = '';
						if( strlen($number) > ($j) * $size_number ) {
							$number_print = substr($number, $j * $size_number, min($size_number, strlen($number) - ($j) * $size_number));
						}
						$number_print = str_pad($number_print,  $size_number, "   ");
						$name_print = '';
						if( strlen($name) > ($j) * $size_name ) {
							$name_print = substr($name, $j * $size_name, min($size_name, strlen($name) - ($j) * $size_name));
						}
						$name_print = str_pad($name_print,  $size_name, "   ");
						$remark_print = '';
						if( strlen($remark) > ($j) * $size_remark ) {
							$remark_print = substr($remark, $j * $size_remark, min($size_remark, strlen($remark) - ($j) * $size_remark));
						}
						$remark_print = str_pad($remark_print,  $size_remark, "   ");
						$qty_print = '';
						if( strlen($qty) > ($j) * $size_qty ) {
							$qty_print = substr($qty, $j * $size_qty, min($size_qty, strlen($qty) - ($j) * $size_qty));
						}
						$qty_print = str_pad($qty_print,  $size_qty, "   ");
						$price_print = '';
						if( strlen($price) > ($j) * $size_price ) {
							$price_print = substr($price, $j * $size_price, min($size_price, strlen($price) - ($j) * $size_price));
						}
						$price_print = str_pad($price_print,  $size_price, "   ");
						$amount_print = '';
						if( strlen($amount) > ($j) * $size_amount ) {
							$amount_print = substr($amount, $j * $size_amount, min($size_amount, strlen($amount) - ($j) * $size_amount));
						}
						$amount_print = str_pad($amount_print,  $size_amount, "   ");
						$printer -> textChinese( '  ' . $number_print . ' ' .  $name_print . '    ' . $qty_print. ' ' . $remark_print . ' ' . $price_print . ' ' . $amount_print . "\n");
					    
					
					}
						while ($srow=mysqli_fetch_assoc($v_data)){
							
						$v_id=$srow['varient_id'];   
						$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id='$v_id'");
						while ($srow1=mysqli_fetch_assoc($sub_rows)){  
							// print_R($srow1); 
							// die;
							  $v_name=$srow1['name'];
							// die;
							 // die;
							if($v_name)
							{
								$printer -> textChinese( '   '.$v_name."\n"); 
							}
							$v_name='';
						}
						$v_name='';
					}
					// $varray=$order['varient'];
					
					// foreach($varray  as $vi)
					// {
						
						// $v_text=$vi['name']."( ".$vi['product_price'].")";
					    // $printer -> textChinese( '   '.$v_text."\n"); 
					// }
					$printer -> text("\n");
					$v_order++;
				}
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( "------------ -------------" . "\n");
				$printer -> text( "Qty: " . $qty_total . "      " . "Total: RM $total" . "   " . "\n");
				$printer -> text( "============ =============" . "\n");
				$printer -> text("\n");
				$printer -> text("\n");
				$printer -> cut( Printer::CUT_FULL, 3 );  
				$printer -> close();
				//echo('success');   
				$order_id=$order['id'];
				 mysqli_query($conn,"UPDATE `order_list` SET `auto_print` = '1' WHERE `order_list`.`id` ='$order_id'");
				$res['status']=true;
				$res['message']="Print Successfully";
			} finally {
				$printer -> close();
			}
		}
		// print_R($res);
			return $res;
	}
	
?>