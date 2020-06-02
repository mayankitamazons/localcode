<?php
	include("config.php"); 
	require("fpdf17/fpdf.php");
	$pdf = new FPDF("P", "mm", array(72.1, 3268));

	
	if(isset($_GET['id'])){
		$order_id = $_GET['id'];
        $merchant = $_GET['merchant'];
		$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$merchant."'"));
		
		$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$order_id."'"));
	    
		$pdf->SetMargins(4, 10, 4);
		$pdf->AddPage();
		// $pdf->Ln();
		$pdf->SetX(20);
		$pdf->SetFont("Arial", "", 8);
		$pdf->Write(4, $profile['company']);
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 6);
		$pdf->SetX(22);
		$pdf->Write(4, "Co.No.:");
		$pdf->SetX(33);
		$pdf->Write(4, $profile['register']);
		$pdf->Ln();
		$len = strlen($profile['google_map']);
		if(($len < 80) && ($len > 50)){
		    $pdf->SetX(5);
    		$pdf->Write(3, $profile['google_map']);
    		$pdf->Ln();
		} else if($len < 30){
		    $pdf->SetX(25);
    		$pdf->Write(3, $profile['google_map']);
    		$pdf->Ln();
		} else {
		    $pdf->SetX(20);
    		$pdf->Write(3, substr($profile['google_map'], 0, $len/2));
    		$pdf->Ln();
    		$pdf->SetX(20);
    		$pdf->Write(3, substr($profile['google_map'], $len/2 + 1, $len));
    		$pdf->Ln();
		}
		
		$pdf->SetX(22);
		$pdf->Write(3, "GST.No.:");
		$pdf->SetX(33);
		if(($profile['gst'] == "") || ($profile['gst'] == null))
		    $pdf->Write(3, "N/A");
		else 
		    $pdf->Write(3, $profile['gst']);
		$pdf->Ln();
		
		$pdf->SetX(22);
		$pdf->Write(3, "SST.No.:");
		$pdf->SetX(33);
		if(($profile['sst'] == "") || ($profile['sst'] == null))
		    $pdf->Write(3, "N/A");
		else 
		    $pdf->Write(3, $profile['sst']);
		$pdf->Ln();
		
		$pdf->SetX(22);
		$pdf->Write(3, "Tel.No.:");
		$pdf->SetX(33);
		$pdf->Write(3, "+".$profile['mobile_number']);
		$pdf->Ln();
		$pdf->SetX(22);
		$pdf->Write(3, "Fax.No.:");
		$pdf->SetX(33);
		$pdf->Write(3, $profile['facsimile_number']);
		$pdf->Ln();
		
		$pdf->SetFont("Arial", "", 9);
		$pdf->SetX(22);
		$pdf->Write(6, "*** Tax Invoice ***");
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 6);
		$pdf->Write(3, "Merchant Code:");
		$pdf->SetX(20);
		$pdf->Write(3, $profile['merchant_code']);
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 6);
		$pdf->Write(3, "Inv.No.:");
		$pdf->SetX(15);
		$year = substr($order['created_on'], 0, 4);
		$month = substr($order['created_on'], 5, 2);
		$day = substr($order['created_on'], 8, 2);
		$pdf->Write(3, str_pad($order['invoice_no'], 4, '0', STR_PAD_LEFT));
		//$pdf->Write(3, str_pad($order['id'], 4, '0', STR_PAD_LEFT));
		$pdf->Ln();
		$pdf->Write(3, "Date:");
		$pdf->SetX(15);
		$pdf->Write(3, $order['created_on']);
		$pdf->Ln();
		$pdf->Write(3, "Table:");
		$pdf->SetX(15);
		$pdf->Write(3, $order['table_type']);
		$pdf->Ln();
		
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(4, 4, 'No', 1, 0, 'C');
		$pdf->Cell(7, 4, 'Code',1, 0, 'C');
		$pdf->Cell(21, 4, 'Description', 1, 0, 'C');
		$pdf->Cell(8, 4, 'Remark', 1, 0, 'C');
		$pdf->Cell(4, 4, 'Qty', 1, 0, 'C');
		$pdf->Cell(9, 4, 'Price(RM)', 1, 0, 'C');
		$pdf->Cell(11, 4, 'Amount(RM)', 1, 0, 'C');
		$pdf->Ln();
		$product_ids = "";
		$index = 0;
		$sum = 0;
		$sum_qty = 0;
		$product_ids = explode(",",$order['product_id']);
		$product_qtys = explode(",",$order['quantity']);
		$proudct_amounts = explode(",",$order['amount']);
        $remarks = explode("|", $order['remark']);
        $product_code = explode(",", $order['product_code']);
		for($i = 0; $i < count($product_ids); $i++){
			$pdf->Cell(4, 3, $i+1, 1, 0, 'C');
			$pdf->Cell(7, 3, $product_code[$i],1, 0, 'C');
			$product_id = $product_ids[$i];
			$products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$product_id."'"));
			if(count($products) > 0){
    			$pdf->SetFont("Arial", "", 4);
    			$pdf->Cell(21, 3, $products['product_name'], 1, 0, 'C');
    			$pdf->SetFont("Arial", "", 5);
    			$pdf->Cell(8, 3, $remarks[$i], 1, 0, 'C');
    			$pdf->Cell(4, 3, $product_qtys[$i], 1, 0, 'C');
    			$pdf->Cell(9, 3, number_format($products['product_price'],2), 1, 0, 'C');
    			$pdf->Cell(11, 3, number_format($products['product_price'] * $product_qtys[$i], 2) , 1, 0, 'C');
    			$sum_qty += $product_qtys[$i];
    			$sum += $products['product_price'] * $product_qtys[$i];    
			} else {
			    $pdf->SetFont("Arial", "", 4);
    			$pdf->Cell(21, 3, $product_ids[$i], 1, 0, 'C');
    			$pdf->SetFont("Arial", "", 5);
    			$pdf->Cell(8, 3, $remarks[$i], 1, 0, 'C');
    			$pdf->Cell(4, 3, $product_qtys[$i], 1, 0, 'C');
    			$pdf->Cell(9, 3, number_format($proudct_amounts[$i], 2), 1, 0, 'C');
    			$pdf->Cell(11, 3, number_format($proudct_amounts[$i] * $product_qtys[$i], 2) , 1, 0, 'C');
    			$sum_qty += $product_qtys[$i];
    			$sum += $proudct_amounts[$i] * $product_qtys[$i];
			}
			
			$pdf->Ln();
		}
		$pdf->SetX(35);
		$pdf->SetFont("Arial", "", 6);
		$pdf->Write(6, "Total:");
		$pdf->SetX(44);
		$pdf->SetFont("Arial", "", 8);
		$pdf->Write(6, $sum_qty);
		$pdf->SetX(59);
		$pdf->SetFont("Arial", "", 6);
		$pdf->Write(6, number_format($sum, 2));
		$pdf->Ln();
		
		$pdf->SetX(31);
		$pdf->Write(6, "Mode Of Payment Credit:");
		$pdf->SetX(56);
		$pdf->Write(6, $order["wallet"]);
		$pdf->Ln();
		$pdf->Write(6, "Goods Sold Are Not Exchange / Please come again. Thank you.");
		$pdf->Ln();
		$pdf->Output();
		
	}

	
	// header("LOCATION: http://local.koofamilies.com/orderview.php");
?>
