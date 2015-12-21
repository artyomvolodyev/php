<?php
//ini_set('post_max_size',"64M");

if(isset($_FILES["CustomImage"]) && !empty($_FILES["CustomImage"])) {
    $uploads_dir = 'images/customimage/';
    $mimeType = $_FILES['CustomImage']['type'];
    $name= 'c_'.date("YmdHis").'_'.uniqid().'.png'; // Random name

    if($mimeType !== 'image/png'){
        error_log('uploadFile.php Got upload of file type '.$mimeType.', full info: '.print_r($_FILES, true));
        $tmpName= 'customtmp_'.uniqid().'.tmp';
        error_log('uploadFile.php File extension not PNG, convert to PNG, store temporarily as: '.$tmpName);
        move_uploaded_file($_FILES["CustomImage"]['tmp_name'], $uploads_dir.$tmpName); // Move the file to uploads folder as tmp file name
        error_log('uploadFile.php Convert to PNG, save as: '.$name);
        imagepng(imagecreatefromstring(file_get_contents($uploads_dir.$tmpName)), $uploads_dir.$name); // Convert and save as PNG
        delete($uploads_dir.$tmpName); // delete tmp image
    }else{
        move_uploaded_file($_FILES["CustomImage"]['tmp_name'], $uploads_dir.$name); // Move the file to uploads folder with final file name
    }

    echo $name; // Return name
}