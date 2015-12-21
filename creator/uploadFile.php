<?php

  if(isset($_FILES["imageNameHere"]) && !empty($_FILES["imageNameHere"])) {
    // Random name
    $name= rand(10, 20).'.png';
    // Move the file
    move_uploaded_file($_FILES["imageNameHere"]['tmp_name'], $name);
    // Return name
    echo $name;
  }