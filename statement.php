
<?php

session_start();
include("config.php");
if($_SESSION['login']=='')
{
    header('Location: '. $site_url .'/login.php');
    die;
}

date_default_timezone_set('Asia/Kolkata');
$timestamp = time(); 
$current_time = date("F d, Y h:i:s A", $timestamp); 


   // print_R($_POST);
   // die;
//print_r($username);
     $statementid = $_POST['statementid'];
     $Merchantid = $_SESSION['login'];
     $paid = $_POST['paid'];
     $change = $_POST['change'];
     $chan = number_format($change,2);
     $tol_qty = $_POST['tol_qty'];
     $tol_mnt = $_POST['tol_mnt1'];

    $product_name = $_POST['product_name'];
    $product_code = $_POST['product_code'];
	  // $product_code = explode(",", $product_code);
	 // print_R($product_code);
	 // die;
    $remarks = $_POST['remark'];
    $user = $_POST['user'];
    $tablety = $_POST['tablety'];
    $invo = $_POST['invo'];
    $orderid = $_POST['orderid'];
    //print_r($orderid);
     $count = count($orderid);
    $section = $_POST['section'];
   $i=0;	
	foreach($section as $s)
	{
		 $s_id=$s;
		 $sql = "select name from sections where id='$s_id'";
		$rel = mysqli_query($conn, $sql);
		$sdata=mysqli_fetch_array($rel);
		// print_R($sdata);
		$sd[$i]=$sdata['name'];
		$i++;
	}
	// print_R($sd);
	// die;
    $qtyno = $_POST['qtyno'];
    $total = $_POST['total']; 
$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$Merchantid."'"));
 $str = implode (", ", $invo);
  $s = implode (", ", $user);
   $tbl = implode(", ", $tablety);
   $sec = implode(", ", $sd);

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
	
	$pdf->SetMargins(4, 10, 4);
		$pdf->AddPage();
		$pdf->Ln();
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
		// $current_time;
		$pdf->Write(3, str_pad($str, 4, '0', STR_PAD_LEFT));
		//$pdf->Write(3, str_pad($order['id'], 4, '0', STR_PAD_LEFT));
		$pdf->Ln();
		$pdf->Write(3, "Date:");
		$pdf->SetX(15);
		$pdf->Write(3, $current_time);
		$pdf->Ln();
		$pdf->Write(3, "Table:");
		$pdf->SetX(15);
		$pdf->Write(3, $tbl);
		$pdf->Ln();
		$pdf->Write(3, "Section:");
		$pdf->SetX(15);
		$pdf->Write(3, $sec);
		$pdf->Ln();
		$pdf->SetFont("Arial", "", 5);
		$pdf->Cell(4, 4, 'No', 1, 0, 'C');
		$pdf->Cell(10,4, 'Code',1, 0, 'C');
		$pdf->Cell(21, 4, 'Product', 1, 0, 'C');
		$pdf->Cell(8, 4, 'QTY', 1, 0, 'C');
		$pdf->Cell(11, 4, 'Remark', 1, 0, 'C');
		$pdf->Cell(15, 4, 'Amount(RM)', 1, 0, 'C');
		$pdf->Ln();
		 for($i=0;$i<$count;$i++) {
			 $pdf->Cell(4, 4, $i+1, 1, 0, 'C');
			  $p_code=explode(",",$product_code[$i]);
			  // $p_code=implode("<br/>",$p_code);
			  $p_name=explode(",",$product_name[$i]);
			  $pdf->Cell(10,4, $p_code[0],1, 0, 'C');
			  $pdf->Cell(21,4, $p_name[0],1, 0, 'C');
			  $pdf->Cell(8,4, $qtyno[$i],1, 0, 'C');
			  $pdf->Cell(11,4, $remarks[$i],1, 0, 'C');
			  $pdf->Cell(15,4, $total[$i],1, 0, 'C');
			$pdf->Ln();   
			$value='';
		 }
		
		// $pdf->Write(3, "Sub Total:");
		   $pdf->Cell(4, 4,'', 1, 0, 'C');
		    $pdf->Cell(10,4,'',1, 0, 'C');
		  $pdf->Cell(21,4,"",1, 0, 'C');
		   $pdf->Cell(8,4,"",1, 0, 'C');
		    $pdf->Cell(11,4,"Sub Total :",1, 0, 'C');
			 $pdf->Cell(15,4, $tol_mnt,1, 0, 'C');
			 $pdf->Ln();   
			   $pdf->Cell(4, 4,'', 1, 0, 'C');
		    $pdf->Cell(10,4,'',1, 0, 'C');
		  $pdf->Cell(21,4,"",1, 0, 'C');
		   $pdf->Cell(8,4,"",1, 0, 'C');
		    $pdf->Cell(11,4,"Paid :",1, 0, 'C');
			 $pdf->Cell(15,4, $paid,1, 0, 'C');
			  $pdf->Ln();      
			   $pdf->Cell(4, 4,'', 1, 0, 'C');
		    $pdf->Cell(10,4,'',1, 0, 'C');
		  $pdf->Cell(21,4,"",1, 0, 'C');
		   $pdf->Cell(8,4,"",1, 0, 'C');
		    $pdf->Cell(11,4,"Change :",1, 0, 'C');
			 $pdf->Cell(15,4,(abs($tol_mnt - $paid)),1, 0, 'C');
		   $pdf->Ln();
		// $pdf->SetX(20);
		// $pdf->Write(3, $tol_mnt);
		// $pdf->Ln();
		// $pdf->SetFont("Arial", "", 6);
		// $pdf->Write(3, "Paid:");
		
		// $pdf->SetX(15);
		// $pdf->Write(3, $paid);
		// $pdf->Ln();
		// $pdf->Write(3, "Change:");
		// $pdf->SetX(15);
		// $pdf->Write(3,(abs($tol_mnt - $paid)));
		  $pdf->Ln();
		$pdf->Write(6, "This is computer generated invoice no signature required.");
		$pdf->Ln();
	$pdf->Output();


?>	


