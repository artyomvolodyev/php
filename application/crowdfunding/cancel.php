<?php
include_once (dirname(dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
include_once (dirname (__FILE__) . '/Crowdfunding.php');

//print_r ($_GET);

$crowdfunding = new Crowdfunding();
$crowdfunding->cancel_preapproval($_GET);
header ('Location: ' . $_GET['redirect']);
?>
