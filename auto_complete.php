<?php

include("config.php");
/*$searchTerm = $_['keyword'];
$select =mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2");
while ($row=mysqli_fetch_assoc($select)) 
{
 $data[] = $row['name'];
}

echo json_encode($data);*/

$searchTerm = $_GET['term'];
$select =mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2");
while ($row=mysqli_fetch_assoc($select)) 
{
 $data[] = $row['name'];
}
echo json_encode($data);
exit;
?>
