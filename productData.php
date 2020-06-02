<?php
include("config.php");
 $_SESSION['mm_id']= $_SESSION['login'];

//$pro_remark = $_POST['remark'];
 $pid = $_POST['p_id'];
 $sp_id = $_POST['sp_id'];
 $rmk_val = $_POST['rmk_val'];
 $last_add_id = $_POST['last_add_id']."_";
 $extra_value = $_POST['extra_value'];
 $varient_type = $_POST['varient_type'];
 $qty = isset($_POST['qty']) ? ($_POST['qty'] < 1 ? 1 : $_POST['qty']) : 1;
 $rm = explode(",", $rmk_val);
 $array_rmk_val = array_filter($rm);
 
 $rnmvalue = array_sum($array_rmk_val);
// print_r($rnmvalue);
 
 //vandhu
 
  $subp_id = explode(",", $sp_id);
  $array_subp = array_filter($subp_id);
  $sbnam = implode(",",$array_subp);

 $extravar = $_POST['extravar'];
 $remark = $_POST['remark'];
   if($remark=='')
	   $remark="Remarks";
 $sql = "select * from products where id=".$pid;
 $rel = mysqli_query($conn, $sql);
 $sub_sql = "select * from sub_products where id IN (".$sbnam.") and product_id ='".$pid."'";
 $sub_rel = mysqli_query($conn, $sub_sql);
    $sub_p = 0;
 if($row = mysqli_fetch_assoc($rel))
 {
 		while($sub_row = mysqli_fetch_array($sub_rel))
        {
            if($sub_row['name']!=""){
 			   
         	$r .='<div>-'.$sub_row['name'].'</div>';
         	
            $varri_id .= $sub_row['id'].',';
  			$sub_p = $sub_p + $sub_row['product_price'];

  		}else{
  			$r .= '<div></div>';
  			$sub_p ='0';
  		}
   
 		}		
		if($extra_value)
			$sub_p=$sub_p+$extra_value;
 	// echo'<tr id='.$row['id'].' class="rd"><td>'.$row['product_name'].''.$r.'<input type="hidden" id="name'.$row['id'].'" name="name[]" value="'.$row['product_name'].'"><input type="hidden" id="p_code'.$row['id'].'" name="p_code[]" value="'.$row['product_type'].'"><input type="hidden" id="subpro_id'.$sub_row['id'].'" name="subpro_id[]" value="'.$varri_id.'"></td><td>'.$row['product_price'].'<input type="hidden" id="price'.$row['id'].'" value="'.$row['product_price'].'" name="price[]"><input type="hidden" name="pro_id[]" id="pro_id" value="'.$row['id'].'"></td><td>'.number_format($sub_p,2).'<input type="hidden" id="subpro_price'.$row['id'].'" name="subpro_price[]" value="'.$sub_p.'"></td><td><input type="text"  class="form-control qd" style="width:65px" id="qty'.$row['id'].'" name="qty[]" onkeyup="changeqty(this)" value="1"></td><td class="text-right"><input type="text" class="form-control subt" style="width:90px" id="subtotal'.$row['id'].'" value="'.number_format($row['product_price']+$rnmvalue+ $sub_p,2).'" name="subtotal[]" readonly></td><td id="re'.$row['id'].'">'.$remark.' '.$extravar.'<input type="hidden" id="remark_val'.$row['id'].'" name="remark_val[]" value="'.$remark.' '.$extravar.'"><input type="hidden" id="rmk_value'.$row['id'].'" name="rmk_val[]" value="'.number_format($row['product_price']+$rnmvalue+ $sub_p,2).'"></td><td  id="'.$row['id'].'" class="text-center" onclick="deleterow(this)" ><i class="fa fa-times tip pointer posdel"  title="Remove" style="cursor:pointer;"></i></td></tr>';
 	// echo'<tr id='.$row['id'].' class="rd pos_data"><td  id="'.$row['id'].'" class="text-center" onclick="deleterow(this)" ><i class="fa fa-times tip pointer posdel"  title="Remove" style="cursor:pointer;"></i></td><td>'.$row['product_name'].''.$r.'<input type="hidden" id="name'.$row['id'].'" name="name[]" value="'.$row['product_name'].'"><input type="hidden" id="p_code'.$row['id'].'" name="p_code[]" value="'.$row['product_type'].'"><input type="hidden" id="subpro_id'.$sub_row['id'].'" name="subpro_id[]" value="'.$varri_id.'"></td><td><input type="text" readonly class="form-control" style="width:55px;" id="price'.$row['id'].'" value="'.$row['product_price'].'" name="price[]"><input type="hidden" name="pro_id[]" id="pro_id" value="'.$row['id'].'"></td><td><input type="text" style="width:65px;" class="form-control" readonly id="subpro_price'.$row['id'].'" name="subpro_price[]" value="'.number_format($sub_p,2).'"></td><td><input type="text"  class="form-control qd" style="width:65px" id="qty'.$row['id'].'" name="qty[]" autofocus  onkeyup="changeqty(this)" value="1"></td><td class="text-right"><input type="text" class="form-control subt" style="width:90px" id="subtotal'.$row['id'].'" value="'.number_format($row['product_price']+$rnmvalue+ $sub_p,2).'" name="subtotal[]" readonly></td><td id="re'.$row['id'].'"><a href="#remarks_area" role="button" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal">'.$remark.' '.$extravar.'</a><input type="hidden" id="remark_val'.$row['id'].'" name="remark_val[]" value="'.$remark.' '.$extravar.'"><input type="hidden" id="rmk_value'.$row['id'].'" name="rmk_val[]" value="'.number_format($row['product_price']+$rnmvalue+ $sub_p,2).'"></td></tr>';
 	echo'<tr id='.$last_add_id.$row['id'].' class="rd pos_data">
	<td  id="'.$last_add_id.$row['id'].'" class="text-center" onclick="deleterow(this)" ><i class="fa fa-times tip pointer posdel"  title="Remove" style="cursor:pointer;"></i></td>
	<td>'.$row['product_name'].''.$r.'<input type="hidden" id="name'.$last_add_id.$row['id'].'" name="name[]" value="'.$row['product_name'].'"><input type="hidden" value="'.$sub_p.'" id="sub'.$last_add_id.$row['id'].'" /><input type="hidden" id="p_code'.$row['id'].'" name="p_code[]" value="'.$row['product_type'].'"><input type="hidden" id="subpro_id'.$last_add_id.$sub_row['id'].'" name="subpro_id[]" value="'.$varri_id.'"></td>
	<td><input type="Number"  class="form-control qd qtyInput" style="width:65px" id="qty'.$last_add_id.$row['id'].'" name="qty[]" value="' . $qty . '"  onkeyup="changeqty(this)" onchange="changeqty(this)"  min="1"></td>
	<td id="re'.$last_add_id.$row['id'].'"><input type="hidden" name="varient_type[]" value="'.$varient_type.'"/><a data_id="'.$last_add_id.$row['id'].'" href="#remarks_area" role="button" class="introduce-remarks btn btn-large btn-primary" data-toggle="modal">'.$remark.' '.$extravar.'</a><input type="hidden" id="remark_val'.$last_add_id.$row['id'].'" name="remark_val[]" value=""><input type="hidden" id="rmk_value'.$last_add_id.$row['id'].'" name="rmk_val[]" value="'.number_format($row['product_price']+$rnmvalue+ $sub_p,2).'"></td>
	
	<td><input type="text" readonly class="form-control jin_price" style="width:55px;" id="price'.$last_add_id,$row['id'].'" value="'.$row['product_price'].'" name="price[]"><input type="hidden" name="pro_id[]" id="pro_id" value="'.$row['id'].'"></td>
	<td><input type="text" style="width:65px;" class="form-control jin_extra_price" readonly id="subpro_price'.$last_add_id.$row['id'].'" name="subpro_price[]" value="'.number_format($sub_p,2).'"></td>
	<td class="text-right">
		<input type="text" class="form-control subt" style="width:90px" id="subtotal'.$last_add_id.$row['id'].'" value="'.number_format(($row['product_price']+$rnmvalue+ $sub_p) * $qty,2).'" name="subtotal[]" readonly>
	</td>
	
	</tr>';   

 }


?>