<?php
$conn = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");
if(!$conn)
{
	// Header('Location: '.$_SERVER['PHP_SELF']);
echo "database error"; die;
}
$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='670'"));
print_R($p_detail);
die;
?>