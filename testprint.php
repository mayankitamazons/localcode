<?php
include("config.php");
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$prdouct_str="1,2,3";

$product_result = mysqli_fetch_all(mysqli_query($conn, "SELECT products.id,products.print_ip_address,products.product_name FROM products WHERE products.id in($prdouct_str)"));
$count_all=$product_result[0];
// print_R($product_result);  
echo "<pre>".print_r($product_result,true)."</pre>";


$arr = array();
$product_qty=array(4,2,3);
$i=0;
foreach ($product_result as $key => $item) {
	// print_R($item[1]);
	
	 $arr[$item[1]][$key]=$item[0]."_".$product_qty[$i]."_".$item[2];
	$i++;
}
 $date ="21";
    $time ="12 pm";
// $sorted_array=ksort($arr, SORT_NUMERIC);

echo "<pre>".print_r($arr,true)."</pre>";

foreach($arr as $kp=>$p)
{
	
	singleorderprint($kp,$p);
}
function singleorderprint($printer_ip,$data)
{
   print_R($data);
   die;
}
die;

?>