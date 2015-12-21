#/usr/local/bin/php
<?php
include_once (dirname(dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
include_once (dirname (__FILE__) . '/Crowdfunding.php');

$crowdfunding = new Crowdfunding();
$result = $crowdfunding->collect_all ();
?>
