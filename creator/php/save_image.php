<?php

/*
*
* An example php that gets the 64 bit encoded PNG URL and creates an image of it
*
*/

//get the base-64 from data
$base64_str = substr($_POST['base64_image'], strpos($_POST['base64_image'], ",")+1);

//decode base64 string
$decoded = base64_decode($base64_str);

//create png from decoded base 64 string and save the image in the parent folder
$result = file_put_contents('../product.png', $decoded);

//send result - number or false
header('Content-Type: application/json');
echo json_encode($result);

?>