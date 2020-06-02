<?php 

session_start();

include("config.php");

$Merchantid = $_SESSION['login'];

        

	   $sql = "select order_list.id,order_list.section_type,order_list.table_type,sections.name from order_list inner join sections on order_list.section_type=sections.id where order_list.status in(0,2) and order_list.merchant_id='$Merchantid' order by sections.name asc limit 0,30";

	$rel = mysqli_query($conn, $sql);





		while($data = mysqli_fetch_assoc($rel))

		{   

		   // print_R($data);

		    echo ' <input type="button" style="margin: 10px; background-color:#296ca0;" class="btn btn-info" name="tbl" data-id="'.$data['id'].'" data-section="'.$data['section_type'].'"  data-table="'.$data['table_type'].'"  value="'.$data["name"].'-'.$data["table_type"].'">';

		    

		}



?>