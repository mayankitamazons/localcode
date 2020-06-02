<?php 
    header('Content-type: application/json');
    $ds          = DIRECTORY_SEPARATOR;
    $storeFolder = 'uploads/';

    if (!empty($_FILES)) {
        $tempFile       = $_FILES['file']['tmp_name'];  
        $filename       = $_FILES['file']['name'];     
        $targetPath     = $storeFolder;
        $path_parts     = pathinfo($_FILES["file"]["name"]);
        $extension      = $path_parts["extension"];
        $targetFile     = $targetPath . $filename;
        move_uploaded_file($tempFile, $targetFile); //6
        echo json_encode(['target_file' => $targetFile]);
    }
?>