<?php
session_start();
include("config.php");

if(isset($_POST['del_id']))
{
	$id =$_POST['del_id'];	
	$test = mysqli_query($conn, "UPDATE users SET mian_merchant='' WHERE id='".$id."'");
	if($test)
	{
		echo "YES";
	}else
	{
		echo"NO";
	}


}
else{

$searchTerm = $_GET['term'];

$searchTerm = $_GET['term'];
$select =mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%".$searchTerm."%' and user_roles=2 and id !=".$_SESSION['login']."");
while ($row=mysqli_fetch_assoc($select)) 
{
 $data[] = $row['name'];
}
echo json_encode($data);
exit;

}

?>
