<?php
  include('config.php');
  $query="select * from users limit 0,5";
  $exe=mysqli_query($conn,$query);
  $totalcount=mysqli_num_rows($exe);
   if($totalcount>0)
   {
	?>
	<table>
		<tr>
			 <th>No</th>
			 <th>Name</th>
			 <th>Mobile</th>
			 <th>Action</th>
		</tr>
	<?php  
	   $i=1;
	   while($r=mysqli_fetch_assoc($exe))
	   {
		   // print_R($r);
		   // die;
		?>
		 <tr>
			 <td><?php echo $i; ?></td>
			 <td><?php echo $r['name']; ?></td>
			 <td><?php echo $r['mobile_number']; ?></td>
			 <td>
			 <a href="edit.php?id=<?php echo $r['id'];?>">Edit</a>
			 <a href="">Delete</a>
			 <a href="">View</a>
			 </td>
			
		</tr>
	   <?php $i++;} ?>
	   </table>
   <?php } 
		   else
		   {
			   
		   }
		?>

	
		