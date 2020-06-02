<?php 

	include("config.php");


	$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
	if($profile_data['user_roles']==5)
	{
		$loginidset=$profile_data['parentid'];
	}
	else
	{

		$loginidset=$_SESSION['login'];

	}
	$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$loginidset."'"));
	$merchant_name=$merchant_data['name'];
	$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0 and parent_id='0' and maintain_stock='on' order by pending_stock asc");
    // $totalcount=mysqli_num_rows($total_rows);
	$date_array = array();
	while($row = mysqli_fetch_assoc($total_rows)){
		$prduct_name=$row['product_name'];
		$pending_stock=$row['pending_stock'];
		$reorder_level=$row['reorder_level'];
		$on_stock=$row['on_stock'];
		if($on_stock)
		{
			$stock="On Stock";
		}
		else
		{
			$stock="Out of Stock";
		}
		$item = array("prduct_name"=>$prduct_name,"pending_stock"=>$pending_stock,"reorder_level"=>$reorder_level,"stock"=>$stock);
		array_push($date_array, $item);
	}
	
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


	

	$pdf = new myPDF("P", "mm", array(72.1,80));

	

	$pdf->AddPage();
	$pdf->SetMargins(2, 10, 2);
	$pdf->SetAutoPageBreak(true, 5);

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(5);
	$pdf->Write(4, $merchant_name);
	$pdf->SetX(5);
	$pdf->Ln();
    
	$pdf->SetX(5);
	$pdf->Write(4,'Latest Stock Records: ');
	$pdf->SetX(5);
	$pdf->Ln(); 
	
	
	$pdf->SetX(35);
	$pdf->Write(4, date('m/d/Y H:i:s'));
	$pdf->Ln();

	
	
	$pdf->Ln();

	

	$pdf->SetX(6);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Cell(5, 5, 'No', 1, 0, 'C');
	$pdf->Cell(22, 5, 'Product Name', 1, 0, 'C');
	$pdf->Cell(16, 5, 'Current Stock', 1, 0, 'C');
	

	$pdf->Ln();
	$pdf->SetFont("Arial", "", 5);
	for($i = 0; $i < count($date_array); $i++){
	
		
		$pdf->SetX(6);
		$pdf->Cell(5, 4, $i + 1, 1, 0, 'C');
		$pdf->Cell(22, 4, $date_array[$i]['prduct_name'], 1, 0, 'C');
		$pdf->Cell(16, 4, $date_array[$i]['pending_stock'], 1, 0, 'C');
		
		
		$pdf->Ln();
	}

	

	

	$pdf->Ln();

	$pdf->Output();

?>