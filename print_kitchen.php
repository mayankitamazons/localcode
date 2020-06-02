<?php
	include("config.php"); 
	require("fpdf17/fpdf.php");
	header('Content-Type: text/html; charset=utf-8');

	class myPDF extends FPDF{
	    function myCell($w, $h, $x, $t){
	        $height = $h / 3;
	        $first = $height + 2;
	        $second = $height * 3 + 3;
	        $len = strlen($t);
	        if($len > 10){
	            $txt = str_split($t, 10);
	            $this->SetX($x);
	            $this->Cell($w, $first,  $txt[0], '', '', 'C');
	            $this->SetX($x);
	            $this->Cell($w, $second, $txt[1], '', '', 'C');
	            $this->SetX($x);
	            $this->Cell($w, $h, '', 'LTRB', 0, 'C', 0);
	        } else {
	            $this->SetX($x);
	            $this->Cell($w, $h, $t, 'LTRB', 0, 'C', 0);
	        }
	    }
	}
	
	$pdf = new myPDF("P", "mm", array(72.1, 140));
	if(isset($_GET['id'])){
		$order_id = $_GET['id'];
        $merchant = $_GET['merchant'];
		$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$merchant."'"));
		
		$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$order_id."'"));
	    
	    $remarks = explode('|', $order['remark']);
		$pdf->SetMargins(3, 6, 3);
		$pdf->AddPage();
		
		$pdf->SetFont("Arial", "", 13);
		$pdf->SetX(10);
		$pdf->Write(6, "*** Kitchen printout ***");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 8);
		$pdf->Write(5, "Merchant Code:");
		$pdf->SetX(30);
		$pdf->Write(5, $profile['merchant_code']);
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 8);
		$pdf->Write(5, "Inv.No.:");
		$pdf->SetX(18);
		$pdf->Write(5, str_pad($order['invoice_no'], 4, '0', STR_PAD_LEFT));
		$year = substr($order['created_on'], 0, 4);
		$month = substr($order['created_on'], 5, 2);
		$day = substr($order['created_on'], 8, 2);
		$pdf->Ln();
		$pdf->Write(5, "Date:");
		$pdf->SetX(15);
		$pdf->Write(5, $order['created_on']);
		$pdf->Ln();
		$pdf->Write(5, "Table:");
		$pdf->SetX(15);
		$pdf->Write(5, $order['table_type']);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(7, 10, 'No', 1, 0, 'C');
		$pdf->Cell(16, 10, 'Code',1, 0, 'C');
		$pdf->Cell(18, 10, 'Code Image', 1, 0, 'C');
		$pdf->Cell(14, 10, 'Remark', 1, 0, 'C');
		$pdf->Cell(6, 10, 'Qty', 1, 0, 'C');
		/*$pdf->Cell(6, 4, 'Price', 1, 0, 'C');
		$pdf->Cell(7, 4, 'Amount', 1, 0, 'C');*/
		$pdf->Ln();
		$product_ids = "";
		$index = 0;
		$sum = 0;
		$product_ids = explode(",",$order['product_id']);
		$product_qtys = explode(",",$order['quantity']);
		$proudct_amounts = explode(",",$order['amount']);
        $remarks = explode("|", $order['remark']);
        $product_code = explode(",", $order['product_code']);
		for($i = 0; $i < count($product_ids); $i++){
		    if(($i > 0) && ($i % 5 == 0)){
		        $pdf->SetMargins(4, 10, 4);
		        $pdf->AddPage();
		    }
		    $pdf->SetFont("Arial", "", 8);
			$pdf->Cell(7, 12, $i+1, 1, 0, 'C');
			$pdf->SetFont("Arial", "", 8);
			$pdf->Cell(16, 12, $product_code[$i],1, 0, 'C');
			$product_id = $product_ids[$i];
			$products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$product_id."'"));
			$image1 = "";
			if($products['code'] != '')
			    $image1 = 'https://koofamilies.com/images/product_images/'.$products['code'];
			$string = $products['product_name'];
			if(count($products) > 0){
    			$pdf->SetFont("Arial", "", 7);
    			
    			/*$pdf->myCell(15, 12, $pdf->GetX(), $products['product_name']);*/
    			if($image1 != ""){
    			    $pdf->Cell(18, 12, $pdf->Image($image1, $pdf->GetX() + 2, $pdf->GetY() + 1, 14, 10), 1, 0, 'C');
    			} else {
    			    $pdf->Cell(18, 12, '', 1, 0, 'C');
    			}
    			$pdf->Cell(14, 12, $remarks[$i], 1, 0, 'C');
    			$pdf->SetFont("Arial", "", 12);
    			$pdf->Cell(6, 12, $product_qtys[$i], 1, 0, 'C');
    			$sum += $products['product_price'] * $product_qtys[$i];    
			} else {
			    $pdf->SetFont("Arial", "", 4);
    			$pdf->Cell(12, 12, $product_ids[$i], 1, 0, 'C');
    			$pdf->Cell(15, 12);
    			
    			$pdf->SetFont("Arial", "", 10);
    			$pdf->Cell(6, 12, $product_qtys[$i], 1, 0, 'C');
			}
			
			$pdf->Ln();
		}
		$pdf->Ln();
		
		$pdf->Output();
		
	}

	
	//header("LOCATION: http://local.koofamilies.com/orderview.php");
?>
