<?php

$method = isset($_POST['_method']) ? $_POST['_method'] : null;

if(strtolower($method) == 'delete') {
	$id = $_POST['id'];
	$product_id = $_POST['product_id'];
	include_once('php/Subproduct.php');
	$sectionObj = new Subproduct($conn);
    if($sectionObj->delete($id)) {
		$url=$site_url."/sub_product.php?p_id=".$product_id."&success=Sub Product delete successfully.";
        redirectToUrl($url);
        exit;  
    }
	$url=$site_url."/sub_product.php?p_id=".$product_id."&error=Sub Product could not be deleted. Please try again.";
    redirectToUrl($url);
    exit;
	
}
redirectToUrl($site_url.'/sub_product.php');
exit;