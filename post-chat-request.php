<?php
include("config.php");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.cometondemand.net/api/v2/createUser");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: 52013x45cf53ef29068b0e37d03a026248298b'));

$SQL = mysqli_query($conn, "select id , name from users where 0 =0 ") ;
while($USERlist =  mysqli_fetch_assoc($SQL)){


$ID = $USERlist['id'] ; 
$Name = $USERlist['name'] ;

echo $ID ; echo "<br />" ;



curl_setopt($ch, CURLOPT_POSTFIELDS,
"UID=$ID&name=$Name");

// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

}
curl_close ($ch);
?>