<?php
error_reporting(0);
include("config.php");
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
 $_SESSION['mm_id']= $loginidset;
// $_SESSION['mm_id']= $_SESSION['login'];
$id = $_SESSION['mm_id'];
require_once("dbcontroller.php");
require_once("pagination.class.php");
$db_handle = new DBController();
$perPage = new PerPage();

 //$sql = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$id."' and status=0 ");

if($_POST['search']!="" )
{
	$ser = "And p.product_name like'%".$_POST['search']."%' or p.product_type like'%".$_POST['search']."%' ";
}
else
{
	$ser = "";
}

 $sql = "SELECT ac.*,p.*
            FROM pos_product_system ac
          INNER JOIN products p on ac.entity_id = p.id
             WHERE   ac.user_id ='".$id."' ".$ser."
             GROUP BY ac.shift_pos ORDER BY  ac.shift_pos ASC"; 
   
$paginationlink = "getresult.php?page=";	
$pagination_setting = $_GET["pagination_setting"];
				
$page = 1;
if(!empty($_GET["page"])) {
$page = $_GET["page"];
}

$start = ($page-1)*$perPage->perpage;
if($start < 0) $start = 0;

$query =  $sql . " limit " . $start . "," . $perPage->perpage; 
$faq = $db_handle->runQuery($query);

if(empty($_GET["rowcount"])) {
$_GET["rowcount"] = $db_handle->numRows($sql);
}

if($pagination_setting == "prev-next") {
	$perpageresult = $perPage->getPrevNext($_GET["rowcount"], $paginationlink,$pagination_setting);	
} else {
	$perpageresult = $perPage->getAllPageLinks($_GET["rowcount"], $paginationlink,$pagination_setting);	
}


$output = '';
	if(!empty($perpageresult)) {
$output .= '<div id="pagination">' . $perpageresult . '</div></br></br>';
}
foreach($faq as $k=>$v) {
 $output .= '<div id="searchData"></div><div class="question col-xs-4 col-lg-2 col-md-3"><input type="hidden" id="rowcount" name="rowcount" value="' . $_GET["rowcount"] . '" /><div class="col-xs-2 col-lg-2 col-md-3 text-center" ><button  id="pro'.$faq[$k]['id'].'" type="button"  data-pro-id="'.$faq[$k]['id'].'" data-toggle="modal" data-target="#ProductModel"  value="'.$faq[$k]['id'].'"  class="btn-prni btn-work" ><div id="'.$faq[$k]['id'].'" style="padding: 8px;"><div>'.$faq[$k]['product_name'].'</div><div>'.$faq[$k]['code'].'</div><div>$'.number_format($faq[$k]['product_price'],2).'</div></div><div style="margin-top: 14px;margin-bottom: 7px;"><input type="hidden" id="remark_'.$faq[$k]['id'].'"><input type="hidden" id="extra_'.$faq[$k]['id'].'"><input type="hidden" id="extraprice_'.$faq[$k]['id'].'"><!--<a role="button" class="pro_status introduce-remarks bnt btn-large btn-primary selected" data-toggle="modal" data-book-id="'.$faq[$k]['id'].'" data-target="#remarks_area" >Remarks</a>--></div></button></div></div>';
// $output .= '<div class="answer">' . $faq[$k]["code"] . '</div>';
}

print $output;
?>
