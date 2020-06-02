<?php

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
	$sectionId = $_GET['requested_section_id'];
	include_once('php/SectionTable.php');
	$sectionObj = new SectionTable($conn);
    if($sectionObj->toggleStatus($id)) {
        redirectToUrl($site_url.'/section_tables.php?section_id='.$sectionId.'&success=Table status has been changed.');
        exit;
    }
    redirectToUrl($site_url.'/section_tables.php?section_id='.$sectionId.'&error=Table status could not be change. Please try again.');
    exit;
	
}
redirectToUrl($site_url.'/section_tables.php?section_id='.$sectionId);
exit;