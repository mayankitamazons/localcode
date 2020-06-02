<?php

$method = isset($_POST['_method']) ? $_POST['_method'] : null;

if(strtolower($method) == 'delete') {
	$id = $_POST['id'];
	include_once('php/Supplier.php');
	$sectionObj = new Supplier($conn);
    if($sectionObj->delete($id)) {
        redirectToUrl($site_url.'/supplier.php?success=Supplier delete successfully.');
        exit;
    }
    redirectToUrl($site_url.'/supplier.php?error=Supplier could not be deleted. Please try again.');
    exit;
	
}
redirectToUrl($site_url.'/supplier.php');
exit;