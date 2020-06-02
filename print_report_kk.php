<?php 

	include("config.php");
    function ceiling($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil(round($number/$significance))*$significance) : false;
	}


	$start_dt = $_GET['start_dt'];

	$end_dt = $_GET['end_dt'];

	$user = $_GET['user'];
	$sql = "SELECT * FROM users WHERE id = '$user'";
	$user_info = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $sstper = $user_info['sst_rate'];
	   
	$sql = "

		SELECT *

	    FROM order_list

	    WHERE created_on >= '$start_dt' AND created_on <= '$end_dt' AND merchant_id='$user'";

	$result = mysqli_query($conn, $sql);

	$reports = array();
	$invoice_no = array();
	$date_array = array();
	$invoice_amounts = array();
	$idx = 0;
	while($row = mysqli_fetch_assoc($result)){
		$idx++;
		$products = explode(",", $row['product_id']);

		$qtys = explode(",", $row['quantity']);

		$amounts = explode(",", $row['amount']);

		array_push($invoice_no, str_pad($row['invoice_no'], 4, '0', STR_PAD_LEFT));
		array_push($date_array, str_pad($row['created_on'], 4, '0', STR_PAD_LEFT));
		$temp_amount = 0;
		for($i = 0; $i < count($products); $i++){

			$product_id = $products[$i];

			$sql = "SELECT *

	                FROM products

	                WHERE id = '$product_id'";

	        $product = mysqli_fetch_assoc(mysqli_query($conn, $sql));


	        if($product['product_name'] != ""){
	        	$item = array(
		        	'id' => $product_id,

		        	'name' => $product['product_name'],

		        	'category' => $product['category'],

		        	'qty' => $qtys[$i],

		        	'amounts' => $amounts[$i] * $qtys[$i],

		        	'date' => substr($row['created_on'], 0, 10)

		        );
		        $temp_amount += $amounts[$i];
		        array_push($reports, $item);
	        }
	        
		}
		array_push($invoice_amounts, $temp_amount);

	}

	function cmp($a, $b){

	    return strcmp($a['category'], $b['category']);

	}

	$total_amounts = 0;
	for($i = 0; $i < count($reports); $i++){
		$total_amounts += $reports[$i]['amounts'];
	}

	usort($reports, "cmp");
	require("fpdf17/fpdf.php");

	class myPDF extends FPDF{
	    function myCell($w, $h, $x, $t){
	        $height = $h / 3;
	        $first = $height + 2;
	        $second = $height * 3 + 3;
	        $len = strlen($t);
	        if($len > 15){
	            $txt = str_split($t, 15);
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


	

	$pdf = new myPDF("P", "mm", array(72.1, 300));

	

	$pdf->AddPage();
	$pdf->SetMargins(2, 10, 2);
	$pdf->SetAutoPageBreak(true, 5);

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(5);
	$pdf->Write(4, 'Sales Summary: ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Report: ');
	$pdf->SetX(35);
	$pdf->Write(4, date('m/d/Y H:i:s'));
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 8);
	$pdf->SetX(5);
	$pdf->Write(4, $user_info['name']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Counter: N/A');
	$pdf->SetX(45);
	$pdf->Write(4, 'Receiptist : N/A');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'From:');
	$pdf->SetX(25);
	$pdf->Write(4, $start_dt );
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'To:');
	$pdf->SetX(25);
	$pdf->Write(4, $end_dt );
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Card/Bank online:');
	$pdf->SetX(40);
	$pdf->Write(4, '0');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Cash:');
	$pdf->SetX(40);
	$pdf->Write(4, $total_amounts);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Wallet: ');
	$pdf->SetX(40);
	$pdf->Write(4, '0');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Creditor: ');
	$pdf->SetX(40);
	$pdf->Write(4, '0');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Service Tax charge: ');
	$pdf->SetX(40);
	$pdf->Write(4, '0');
	$pdf->Ln();
	
	$pdf->SetX(5);
	$pdf->Write(4, 'GST: ');
	$pdf->SetX(40);
	$pdf->Write(4, '0');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Sales Total: ');
	$pdf->SetX(40);
	$pdf->Write(4, $total_amounts);
	$pdf->Ln();
	if($sstper>0)
	{
		$incssttotal = ($sstper / 100) * $total_amounts;
		$incssttotal=ceiling($incssttotal,0.05);
		 $g_total_amounts=$incssttotal+$total_amounts;
		
		$pdf->SetX(5);
		$pdf->Write(4, 'SST Total ');
		$pdf->SetX(40);
		$pdf->Write(4,$incssttotal);
		$pdf->Ln();
		
		$pdf->SetX(5);
		$pdf->Write(4, 'Grand Sales Total: ');
		$pdf->SetX(40);
		$pdf->Write(4,$g_total_amounts);
		$pdf->Ln();
	}

	$pdf->Ln();

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(24);
	$pdf->Write(4, 'Sales Report ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Report: ');
	$pdf->SetX(35);
	$pdf->Write(4, date('m/d/Y H:i:s'));
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 8);
	$pdf->SetX(5);
	$pdf->Write(4, $user_info['name']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Counter: N/A');
	$pdf->SetX(45);
	$pdf->Write(4, 'Receiptist : N/A');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'From:');
	$pdf->SetX(25);
	$pdf->Write(4, $start_dt );
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'To:');
	$pdf->SetX(25);
	$pdf->Write(4, $end_dt );
	$pdf->Ln();
	$pdf->Ln();

	$pdf->SetX(6);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Cell(5, 5, 'No', 1, 0, 'C');
	$pdf->Cell(22, 5, 'Date Time', 1, 0, 'C');
	$pdf->Cell(16, 5, 'Invoice No', 1, 0, 'C');
	if($sstper>0)
	$pdf->Cell(10, 5, 'SST', 1, 0, 'C');	
	$pdf->Cell(14, 5, 'Grand Total', 1, 0, 'C');

	$pdf->Ln();
	$pdf->SetFont("Arial", "", 5);
	for($i = 0; $i < count($invoice_no); $i++){
		if($sstper>0)
		{
		$incssttotal = ($sstper / 100) * $invoice_amounts[$i];
		$incssttotal=ceiling($incssttotal,0.05);
		$g_total=$incssttotal+$invoice_amounts[$i];
		}
		else
		{
			$g_total=$invoice_amounts[$i];
		}
		$pdf->SetX(6);
		$pdf->Cell(5, 4, $i + 1, 1, 0, 'C');
		$pdf->Cell(22, 4, $date_array[$i], 1, 0, 'C');
		$pdf->Cell(16, 4, $invoice_no[$i], 1, 0, 'C');
		if($sstper>0)
		$pdf->Cell(10, 4, number_format($incssttotal, 2), 1, 0, 'C');
		$pdf->Cell(14, 4, number_format($g_total, 2), 1, 0, 'C');
		
		$pdf->Ln();
	}

	$pdf->SetFont("Arial", "", 8);
	$pdf->SetX(38);

	$pdf->Write(4, 'Total:');

	$pdf->SetX(50);
    if($sstper>0)
	$pdf->Write(4, number_format($g_total_amounts,2));
	else
	$pdf->Write(4, number_format($total_amounts,2));	

	$pdf->Ln();
	
	$pdf->Ln();
	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(5);
	$pdf->Write(4, 'Service Tax Collection Summary: ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 7);
	$pdf->SetX(5);
	$pdf->Write(4, 'Tax Code');
	$pdf->SetX(22);
	$pdf->Write(4, 'Exclu. Tax Amt');
	$pdf->SetX(50);
	$pdf->Write(4, 'Tax');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Item Total');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Item not included');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Total');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(5);
	$pdf->Write(4, 'GST Collection Summary: ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 7);

	$pdf->SetX(5);
	$pdf->Write(4, 'Tax Code');
	$pdf->SetX(22);
	$pdf->Write(4, 'Exclu. Tax Amt');
	$pdf->SetX(50);
	$pdf->Write(4, 'Tax');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();


	$pdf->SetX(5);
	$pdf->Write(4, 'Item Total');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Item not included');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Total');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(24);
	$pdf->Write(4, 'Sales Report ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Report: ');
	$pdf->SetX(35);
	$pdf->Write(4, date('m/d/Y h:m:s'));
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 8);
	$pdf->SetX(5);
	$pdf->Write(4, $user_info['name']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Counter: N/A');
	$pdf->SetX(45);
	$pdf->Write(4, 'Receiptist : N/A');
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'From:');
	$pdf->SetX(25);
	$pdf->Write(4, $start_dt );
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'To:');
	$pdf->SetX(25);
	$pdf->Write(4, $end_dt );
	$pdf->Ln();

	$total_qty = 0;

	$total_price = 0;

	$category = "";

	$category_items = array();
	$category_names = array();
	function cmp_by_id($a, $b){

	    return strcmp($a['id'], $b['id']);

	}

	$index = 0;
	for($i = 0; $i < count($reports); $i++){
		if($category != $reports[$i]['category']){
			$category = $reports[$i]['category'];
			array_push($category_items, array());
			if($i != 0){
				$index++;
			}
			if($reports[$i]['name'] != ""){
				array_push($category_items[$index], $reports[$i]);
				array_push($category_names, $reports[$i]['category']);
			}
			
			
		} else {
			if($reports[$i]['name'] != ""){
				array_push($category_items[$index], $reports[$i]);
			}
			
		}
	}
	for($i = 0; $i < count($category_items); $i++){
		usort($category_items[$i], "cmp_by_id");
	}

	// /var_dump($category_items[2]);
	for($i = 0; $i < count($category_items); $i++){

		//usort($category_items[$i], "cmp_by_id");

		$pdf->Ln();

		$pdf->SetX(5);

		$pdf->SetFont("Arial", "", 8);

		$pdf->Write(4, 'Category: ');

		$pdf->SetX(24);

		$pdf->Write(4, $category_names[$i]);

		$pdf->Ln();


		$product_id = "";
		$similar_index = 1;
		$similar_products = array();
		$similar_qty = 0;
		$similar_amounts = 0;
		$similar_flag = false;

		$total_qty = 0;
		$total_amount = 0;
		for($j = 0; $j < count($category_items[$i]); $j++){	
			$total_qty += $category_items[$i][$j]['qty'];
			$total_amount += $category_items[$i][$j]['amounts'];
			if($product_id != $category_items[$i][$j]['id']){

				if($j != 0){
					$pdf->SetX(5);

					$pdf->Cell(6, 6, $similar_index, 1, 0, 'C');

					$pdf->myCell(34, 6, $pdf->GetX(), $temp_name);

					$pdf->Cell(6, 6, $similar_qty, 1, 0, 'C');

					$pdf->Cell(8, 6, number_format($similar_amounts, 2), 1, 0, 'C');
					if($sstper>0)
					{
						$incssimilar = ($sstper / 100) * $similar_amounts;
						$similar_total = $incssimilar+$similar_amounts;
						$pdf->Cell(8, 6, number_format($similar_total, 2), 1, 0, 'C');
						$similar_total=0;
					}

					$pdf->Ln();

					$similar_index++;
				}

				$similar_qty = $category_items[$i][$j]['qty'];
				$similar_amounts = $category_items[$i][$j]['amounts'];

				$temp_name = $category_items[$i][$j]['name'];

				$product_id = $category_items[$i][$j]['id'];
				
				$index++;

				//$total_qty += $similar_qty;
				//var_dump($category_items[$i][$j]['category']);

			} else {
				//var_dump($category_items[$i][$j]['category']);
				$similar_flag = true;
				$similar_qty += $category_items[$i][$j]['qty'];
				$similar_amounts += $category_items[$i][$j]['amounts'];

				// /$total_qty += $similar_qty;
			}
		}
		$pdf->SetX(5);

		$pdf->Cell(6, 6, $similar_index, 1, 0, 'C');

		$pdf->myCell(34, 6, $pdf->GetX(), $temp_name);

		$pdf->Cell(6, 6, $similar_qty, 1, 0, 'C');

		$pdf->Cell(8, 6, number_format($similar_amounts, 2), 1, 0, 'C');

		$pdf->Ln();


		$pdf->SetX(30);

		$pdf->Write(4, 'Total:');

		$pdf->SetX(38);

		$pdf->Write(4, $total_qty);

		$pdf->SetX(50);

		$pdf->Write(4, number_format($total_amount, 2));
		if($sstper>0)
		{
				$s_incssttotal = ($sstper / 100) * $total_amount;
				$s_incssttotal=ceiling($s_incssttotal,0.05);
				$s_total_amounts=$s_incssttotal+$total_amount;
			$pdf->Write(4," ".number_format($s_total_amounts, 2));
		}
	}

	
	/*for($i = 0; $i < count($reports); $i++){

		if($category != $reports[$i]['category']){

			if($i != 0){

				$pdf->SetX(35);

				$pdf->Write(4, 'Total:');

				$pdf->SetX(46);

				$pdf->Write(4, $total_qty);

				$pdf->SetX(52);

				$pdf->Write(4, $total_price);

				$pdf->Ln();

			}

			$index = 1;

			$category = $reports[$i]['category'];

			$pdf->Ln();

			$pdf->SetX(5);

			$pdf->SetFont("Arial", "", 8);

			$pdf->Write(4, 'Category: ');

			$pdf->SetX(24);

			$pdf->Write(4, $category);

			$pdf->Ln();

			$total_qty = $reports[$i]['qty'];

			$total_price = $reports[$i]['amounts'];

			array_push($category_items, $reports[$i]);

		} else {

			$total_qty += $reports[$i]['qty'];

			$total_price += $reports[$i]['amounts'];

		}

		$pdf->SetX(5);

		$pdf->Cell(6, 6, $index, 1, 0, 'C');

		$pdf->myCell(34, 6, $pdf->GetX(), $reports[$i]['name']);

		$pdf->Cell(6, 6, $reports[$i]['qty'], 1, 0, 'C');

		$pdf->Cell(8, 6, $reports[$i]['amounts'], 1, 0, 'C');

		$pdf->Ln();


		$index++;

	}

	if(count($reports) > 0){

		$pdf->SetX(35);

		$pdf->Write(4, 'Total:');

		$pdf->SetX(46);

		$pdf->Write(4, $total_qty);

		$pdf->SetX(51);

		$pdf->Write(4, $total_price);

	}*/
	

	$pdf->Ln();

	$pdf->Output();

?>