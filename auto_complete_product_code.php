<?php 
	include("config.php");
     $user_id = $_SESSION['mm_id'];
  
	$searchTerm = $_GET['term'];
	$select =mysqli_query($conn,"SELECT * FROM products WHERE user_id = '$user_id' AND product_type LIKE '%".$searchTerm."%' ");
	$data = array();
	while ($row=mysqli_fetch_assoc($select)) {
		$item = array('id' => $row['id'], 'value' => $row['product_type'], 'price' => $row['product_price'], 'name' => $row['product_name'], 'remark' => $row['remark']);
		array_push($data, $item);
	}
	echo json_encode($data);
?>