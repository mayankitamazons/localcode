<?php

$method = isset($_POST['_method']) ? $_POST['_method'] : null;

$requestedSectionId = isset($_GET['requested_section_id']) ? $_GET['requested_section_id'] : null;
if(strtolower($method) == 'delete') {
	$id = $_POST['id'];
	include_once('php/SectionTable.php');
	$sectionObj = new SectionTable($conn);
    if($sectionObj->delete($id)) {
        redirectToUrl($site_url.'/section_tables.php?section_id='.$requestedSectionId.'&success=Table delete successfully.');
        exit;
    }
    redirectToUrl($site_url.'/section_tables.php?section_id='.$requestedSectionId.'&error=Table could not be deleted. Please try again.');
    exit;
	
}
redirectToUrl($site_url.'/section_tables.php?section_id='.$requestedSectionId);
exit;