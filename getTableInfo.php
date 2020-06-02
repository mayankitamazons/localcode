<?php 
$current_time = date('Y-m-d H:i:s');
session_start();
include("config.php");
$id=$_POST['id'];
$section=$_POST['section'];
$mid=$_POST['mid'];
$jsonData = json_decode(file_get_contents('php://input'));
header('Content-type:application/json'); 
 $sql = "select order_list.invoice_no,order_list.id,order_list.section_type,order_list.table_type,sections.name from order_list inner join sections on order_list.section_type=sections.id where order_list.status ='0' and order_list.merchant_id='$mid' AND  order_list.table_type='$id' AND order_list.section_type='$section' order by order_list.invoice_no limit 0,30";

$rel = mysqli_query($conn, $sql);
$str="";
   $toalrows = mysqli_num_rows($rel); 
while($data = mysqli_fetch_assoc($rel))
{   
	$show_value=$data['invoice_no']."-".$data['name']."-".$data['table_type'];
   // print_R($data);
   if($toalrows>1)
   {
	   $t_name="tblbulk";
   }
   else
   {
	   $t_name="tbl";
   }
    $str.= ' <input type="button" style="margin: 10px; background-color:#296ca0;" class="btn btn-info testClass" name="'.$t_name.'" data-invoce="'.$data['invoice_no'].'" data-id="'.$data['id'].'" data-section="'.$data['section_type'].'" data-status="notset" data-table="'.$data['table_type'].'"  value="'.$show_value.'">';
	
		
}
 if($t_name=="tblbulk")
 {
	 $str.='<div class="modal-footer" style="padding-bottom:2px;"><input type="button" id="submit_bulk" class="btn btn-info" value="Submit All"/></div>';
 }
// $merchant_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$mid."'"));
// $pending_time = $merchant_name['pending_time'];
// $alaram_required = $merchant_name['alaram_required'];
// $sstper = $merchant_name['sst_rate'];
// //echo "SELECT s.name, ol.quantity,ol.amount, ol.wallet, ol.location, ol.table_type, ol.remark, ol.invoice_no,ol.order_place,ol.created_on,
//ol.product_code, p.product_name, p.code, p.category, p.product_type, p.product_price FROM order_list ol, products p,sections s where
// s.id=ol.section_type AND p.id=ol.product_id AND ol.merchant_id='$mid' AND ol.status='0' AND ol.section_type='$section'
// AND ol.table_type='$id'";
// $TableResult = mysqli_query($conn, "SELECT s.name, ol.quantity,ol.amount, ol.wallet, ol.location, ol.table_type, ol.remark, ol.invoice_no,ol.order_place,ol.created_on,ol.product_code, p.product_name, p.code, p.category, p.product_type, p.product_price FROM order_list ol, products p,sections s where s.id=ol.section_type AND p.id=ol.product_id AND ol.merchant_id='$mid' AND ol.status='0' AND ol.section_type='$section' AND ol.table_type='$id'");
//  $str="<table style='width:100%' class='table-bordered'>";
// while($r=mysqli_fetch_array($TableResult))
// {
//     $heading="Section: ".$r["name"].". TableNo.: ".$r["table_type"];
//     $created =$r['created_on'];
//     $date=date_create($created);
//     $tm=getDateDiff($current_time,$date);
//     $total=0;
//     $value=$r["amount"];
//     $q=$r["quantity"];
//     $incsst; $g_total;
//     $total =  $total + ($q *$value );
//     if($sstper>0){
//         $incsst = ($sstper / 100) * $total;
//         $incsst=@number_format($incsst, 2);
// 		$incsst=ceil($incsst,0.05);
//         $incsst=@number_format($incsst, 2);
// 		$g_total=@number_format($total+$incsst, 2);
//     }
//     else
//     {
//         $incsst=0;
//         $g_total=$total;
//     }
//     $str.="<tr style='text-align:center'><td><b>Order</b><br>".$created."<br><span style='color:red'>".$tm."</span></td><td><b>InvoiceNo.</b><br>".$r["invoice_no"]."</td></tr>";
//     $str.="</table><table class='table-bordered' style='width:100%'><tr style='text-align:center'><td><b>Unit Amount</b><br>".$r["amount"]."</td><td><b>Qty</b><br>".$q."</td>";
//     $str.="<td><b>Total</b><br>".($value*$q)."</td><td><b>SST 6%</b><br>".$incsst."</td><td><b>Grand Total</b><br>".$g_total."</td></tr>";
//     $str.="</table><table class='table-bordered' style='width:100%'><tr style='text-align:center'>";


// }
// $str=$str."</table>";
echo json_encode(array("responseCode"=>"1","data"=>$str,"section"=>"Table Proccessing"));
?>

<?php
function getDateDiff($current_time1,$date)
{
    $dteDiff  = date_diff($date, date_create($current_time1));
    $diff_day = $dteDiff->d;
    if($diff_day != '0') $diff_day .= ' days ';
    else $diff_day = '';
    $diff_hour = $dteDiff->h;
    if(intval($diff_hour) < 10) $diff_hour = '0'.$diff_hour.':'; else $diff_hour = $diff_hour.':';
    $diff_minute = $dteDiff->i;
    if($diff_minute < 10) $diff_minute = '0'.$diff_minute.':'; else $diff_minute = $diff_minute.':';
    $diff_second = $dteDiff->s;
    if($diff_second < 10) $diff_second = '0'.$diff_second;
    return $diff_time = $diff_day.'&nbsp;&nbsp;&nbsp;'.$diff_hour.$diff_minute.$diff_second;

}
?>

