<?php

//
// exemple de facture avec mysqli
// gere le multi-page
// Ver 1.0 THONGSOUME Jean-Paul
//


    require("fpdf17/fpdf.php");
    
    // le mettre au debut car plante si on declare $mysqli avant !
    $pdf = new FPDF( 'P', 'mm', 'A4' );
   $pdf = new FPDF("P", "mm", array(72.1, 300));
    
    $pdf->Output();
    // $pdf->Output("I", $nom_file);
?>