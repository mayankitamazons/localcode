	<?php
include("config.php");
 $_SESSION['mm_id']= $_SESSION['login'];

 $pid = $_POST['p_id'];

 $sql = "select * from products where id=".$pid;
 $rel = mysqli_query($conn, $sql);

 if($row = mysqli_fetch_assoc($rel))
 {
 echo'<tr><td>'.$row['product_name'].'</td><td class="amnt">'.number_format($row['product_price'],2).'</td></tr>';

 }


?>