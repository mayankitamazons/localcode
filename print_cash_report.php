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
	if(isset($_GET['type']))
	{
		$limit="0,1000";
	}
	else
	{
		$limit="0,1";
	}
	
	$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$loginidset."'"));
	$merchant_name=$merchant_data['name'];
	$pastquery=mysqli_query($conn,"select *  from cash_system where user_id='$loginidset' and is_active='n' order by id desc limit $limit");
// $totalcount=mysqli_num_rows($query);
	// $pastcount=mysqli_num_rows($pastquery);
	$date_array = array();
	while($row = mysqli_fetch_assoc($pastquery)){
		$opening=number_format($row['opening'],2);
		$sales=number_format($row['sales'],2);
		$cash_in=number_format($row['cash_in'],2);
		$cash_out=number_format($row['cash_out'],2);
		$void_tras=number_format($row['void_tras'],2);
		
		$start_dt=date('d/m/Y h:i A',$row['login_time']);
		$end_dt=date('d/m/Y h:i A',$row['logout_time']);
		// $end_dt=$row['void_tras'];
		$balancepast=$opening+$sales+$cash_in-($cash_out+$void_tras);
		$balancepast=number_format($balancepast,2);
		$item = array("opening"=>$opening,"sales"=>$sales,"cash_in"=>$cash_in,"cash_out"=>$cash_out,"void_tras"=>$void_tras,"balancepast"=>$balancepast,"start_dt"=>$start_dt,"end_dt"=>$end_dt);
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


	

	$pdf = new myPDF("P", "mm", array(82.1,80));

	

		$pdf->AddPage();
	$pdf->SetMargins(2, 10, 2);
	$pdf->SetAutoPageBreak(true, 5);

	$pdf->SetFont("Arial", "B", 9);

	$pdf->SetX(5);
	$pdf->Write(4, 'Cash Summary: ');
	$pdf->SetX(5);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Report: ');
	$pdf->SetX(35);
	$pdf->Write(4, date('m/d/Y H:i:s'));
	$pdf->Ln();

	$pdf->SetFont("Arial", "", 8);
	$pdf->SetX(5);
	$pdf->Write(4, $merchant_name);
	$pdf->Ln();
    
	$pdf->SetFont("Arial", "", 6);
	for($i = 0; $i < count($date_array); $i++){
	$pdf->SetFont("Arial", "", 6);
		$pdf->SetX(5);
	$pdf->Write(4, 'From:');
	$pdf->SetX(25);
	$pdf->Write(4, $date_array[$i]['start_dt'] );
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'To:');
	$pdf->SetX(25);
	$pdf->Write(4, $date_array[$i]['end_dt'] );
	$pdf->Ln();
	
	$pdf->SetX(5);
	$pdf->Write(4, 'Opening Bal:');
	$pdf->SetX(40);
	$pdf->Write(4, $date_array[$i]['opening']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Sales:');
	$pdf->SetX(40);
	$pdf->Write(4, $date_array[$i]['sales']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Cash In: ');
	$pdf->SetX(40);
	$pdf->Write(4,$date_array[$i]['cash_in']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Cash Out: ');
	$pdf->SetX(40);
	$pdf->Write(4,$date_array[$i]['cash_out']);
	$pdf->Ln();

	$pdf->SetX(5);
	$pdf->Write(4, 'Void Tras: ');
	$pdf->SetX(40);
	$pdf->Write(4,$date_array[$i]['void_tras']);
	$pdf->Ln();
	

	$pdf->SetX(5);
	$pdf->Write(4, 'Bal: ');
	$pdf->SetFont("Arial", "B",7);
	$pdf->SetX(40);
	$pdf->Write(4,$date_array[$i]['balancepast']);    
	$pdf->Ln();

		
		
		
		$pdf->Ln();
	}

	

	

	$pdf->Ln();

	$pdf->Output();

?>