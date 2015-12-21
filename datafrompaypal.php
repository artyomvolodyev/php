<?php
function getDataFromPaypal($tx)
{
 $req = 'cmd=_notify-synch';
 $req .= "&tx=".$tx."&at=O3SpgShdVMKSFVsXuRIywBKIEovOgzaPPMgi_HgFF329zImkvp1ya0fXBki";
 $ipnexec = curl_init();
 curl_setopt($ipnexec, CURLOPT_URL, "https://www.sandbox.paypal.com/webscr&"); // test url
 //curl_setopt($ipnexec, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr'); // live url
 curl_setopt($ipnexec, CURLOPT_HEADER, 0);
 curl_setopt($ipnexec, CURLOPT_USERAGENT, 'Server Software: '.@$_SERVER['SERVER_SOFTWARE'].' PHP Version: '.phpversion());
 curl_setopt($ipnexec, CURLOPT_REFERER, $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']);
 curl_setopt($ipnexec, CURLOPT_SSL_VERIFYHOST, 0);
 curl_setopt($ipnexec, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($ipnexec, CURLOPT_POST, 1);
 curl_setopt($ipnexec, CURLOPT_POSTFIELDS, $req);
 curl_setopt($ipnexec, CURLOPT_FOLLOWLOCATION, 0);
 curl_setopt($ipnexec, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ipnexec, CURLOPT_TIMEOUT, 30);
 $ipnresult = trim(curl_exec($ipnexec));
 $ipnresult = "status=".$ipnresult;
 curl_close($ipnexec);
 $parameter_value_array = explode("\n", $ipnresult);
 $value_array =array();
 foreach ($parameter_value_array as $key=>$value) {
 $key_values = explode("=", $value);
 $value_array[$key_values[0]] = $key_values[1];
 }
 if(array_key_exists("status", $value_array) && $value_array['status'] == 'SUCCESS') {
 return $value_array;
 }
}

exit();