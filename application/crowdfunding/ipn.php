<?php
//file_put_contents("out", print_r($_POST, 1));

include_once (dirname(dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
include_once (dirname (__FILE__) . '/Crowdfunding.php');

$crowdfunding = new Crowdfunding();
$crowdfunding->confirm_preapproval($_POST);
?>
