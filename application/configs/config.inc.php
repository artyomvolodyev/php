<?php

$_CONFIG['environment'] = 'development';

// CHANGE THE FOLLOWING URL TO YOUR SITE URL
// IMPORTANT: PLEASE MAKE SURE TO INCLUDE A TRAILING SLASH
// EXAMPLE http://www.your-teeshirt-site.com/
// SHOULD LOOK LIKE: define('APPLICATION_URL', "http://www.your-teeshirt-site.com/");
define('APPLICATION_URL', "http://teeshirt/");

// Base URL and PATHS
// DO NOT CHANGE!
define('APPLICATION_URL_ADMIN', APPLICATION_URL."admin/");
define('IMAGES_URL', APPLICATION_URL.'images/');
define('CSS_URL', APPLICATION_URL.'css/');
define('JS_URL', APPLICATION_URL.'js/');
define('IMAGES_URL_ADMIN', APPLICATION_URL_ADMIN.'images/');
define('CSS_URL_ADMIN', APPLICATION_URL_ADMIN.'css/');
define('JS_URL_ADMIN', APPLICATION_URL_ADMIN.'js/');
$_CONFIG['homeDir'] = realpath(dirname(dirname(dirname(__FILE__)))).'/'; 
define('SITE_ROOT', $_CONFIG['homeDir']);

// Database Tables
// DO NOT CHANGE!
define("ADMINISTRATOR","administrator");
define("EMAIL_TEMPLATES","email_templates");
define("PAGES","pages");
define("SITE_DESC","site_desc");
define("USERS","users");
define("CATEGORY","category");
define("QUESTIONS","questions");
define("COUNTRIES","countries");
define("STATE","state");
define("CITIES","cities");
define("ADDRESS","address");
define("TSHIRT_PRICE","tshirt_price");
define("LAUNCHCAMPAIGN","launchcampaign");
define("SHIPPING_ADDRESS","shipping_address");
define("DRAFTS","drafts");
define("ORDER_RECORD","order_record");
define("PREAPPROVALS","preapprovals");
define("TESTIMONIALS","testimonials");
define("TSHIRT_SIZE","tshirt_size");
define("TSHIRT_DISCOUNT","tshirt_discount");
define("TSHIRT_ICONS","tshirt_icons");
define("TSHIRT_PRODUCTS","tshirt_products");
define("MANAGEIMAGENAME","manageimagename");
define("FOLLOW","follow");
define("NOTIFICATION","notification");
define("BUYERS","buyers");
