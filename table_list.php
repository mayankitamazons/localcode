<?php
    include_once('config.php');
    $sectionId = isset($_GET['section_id']) ? $_GET['section_id'] : null;
    include_once('php/SectionTable.php');
    $sectionObj = new SectionTable($conn);
    $sectionFilters = [
        'section_id' => $sectionId,
        'status' => true
    ];
    $sectionsList = (array)$sectionObj->get($sectionFilters);
    echo json_encode($sectionsList);
    exit;