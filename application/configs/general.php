<?php
function SetupMagicQuotes($dataArray)
{
	$NewArr=array();
	if(count($dataArray)>0)
	{
		
		foreach($dataArray as $key=>$valueArr)
		{
			$keyData=$valueArr;
			if (get_magic_quotes_gpc())
			{
			$keyData=$keyData;
			}
			else
			{
				$keyData=mysql_escape_string($keyData);
			}
			$NewArr[$key]=$keyData;
		}
		return $NewArr;
	}
	else
	{
	return $dataArray;	
	}
}

function SetupTrim($dataArray)
{
	$NewArr=array();
	if(count($dataArray)>0)
	{
		foreach($dataArray as $key=>$valueArr)
		{
			$keyData=trim($valueArr);
			$NewArr[$key]=$keyData;
		}
		return $NewArr;
	}
	else
	{
	return $dataArray;	
	}
}
function SetupHtmlQuote($dataArray)
{
	$NewArr=array();
	if(count($dataArray)>0)
	{
		
		foreach($dataArray as $key=>$valueArr)
		{
			$keyData=$valueArr;
			if (get_magic_quotes_gpc())
			{
			$keyData=$keyData;
			}
			else
			{
				$keyData=str_replace("'","&apos;",$keyData);
			}
			$NewArr[$key]=$keyData;
		}
		return $NewArr;
	}
	else
	{
	return $dataArray;	
	}
}

function changeDate($date)
{
	$mydate=explode(" ",$date);
	$explode=explode("-",$mydate[0]);
	return trim($explode[2])."-".trim($explode[1])."-".trim($explode[0]);
/*	return trim($explode[2])."-".trim($explode[1])."-".trim($explode[0]);
*/}
function SendEmail($to,$subject,$message_body)
{
global $mySession;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	
$headers .= 'To: '.$to.'' . "\r\n";
$headers .= 'From: '.SITE_NAME.' <'.ADMIN_EMAIL.'>' . "\r\n";
$mailMessage='<div style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:#1F1F1F;">'.$message_body.'</div>';
mail($to, $subject, $mailMessage, $headers);
return true;
}

function xmlstr_to_array($xmlstr) {
  $doc = new DOMDocument();
  $doc->loadXML($xmlstr);
  return domnode_to_array($doc->documentElement);
}
function domnode_to_array($node) {
  $output = array();
  switch ($node->nodeType) {
   case XML_CDATA_SECTION_NODE:
   case XML_TEXT_NODE:
    $output = trim($node->textContent);
   break;
   case XML_ELEMENT_NODE:
    for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
     $child = $node->childNodes->item($i);
     $v = domnode_to_array($child);
     if(isset($child->tagName)) {
       $t = $child->tagName;
       if(!isset($output[$t])) {
        $output[$t] = array();
       }
       $output[$t][] = $v;
     }
     elseif($v) {
      $output = (string) $v;
     }
    }
    if(is_array($output)) {
     if($node->attributes->length) {
      $a = array();
      foreach($node->attributes as $attrName => $attrNode) {
       $a[$attrName] = (string) $attrNode->value;
      }
      $output['@attributes'] = $a;
     }
     foreach ($output as $t => $v) {
      if(is_array($v) && count($v)==1 && $t!='@attributes') {
       $output[$t] = $v[0];
      }
     }
    }
   break;
  }
  return $output;
}
function makeAWSUrl($parameters, $associate_tag=AMAZON_ASSOCIATE_TAG, $access_key=AMAZON_ACCESS_KEY, $secret_key=AMAZON_SCERET_KEY, $aws_version = '2009-06-01') {
	

	//print_r($parameters);
	//echo "<br>".$associate_tag."<br>".$access_key."<br>".$secret_key;
	//exit();


        $host = 'ecs.amazonaws.com';

        $path = '/onca/xml';



        $query = array(        

        'Service' => 'AWSECommerceService',

        'AWSAccessKeyId' => $access_key,

        'AssociateTag' => $associate_tag,

        'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),

        'Version' => $aws_version,

        );



        // Merge in any options that were passed in

        if (is_array($parameters)) {

                $query = array_merge($query, $parameters);

        }



        // Do a case-insensitive, natural order sort on the array keys.

        ksort($query);



        // create the signable string

        $temp = array();



        foreach ($query as $k => $v) {

                $temp[] = str_replace('%7E', '~', rawurlencode($k)) . '=' . str_replace('%7E', '~', rawurlencode($v));

        }



        $signable = implode('&', $temp);



        $stringToSign = "GET\n$host\n$path\n$signable";



        // Hash the AWS secret key and generate a signature for the request.



        $hex_str = hash_hmac('sha256', $stringToSign, $secret_key);



        $raw = '';



        for ($i = 0; $i < strlen($hex_str); $i += 2) {

                $raw .= chr(hexdec(substr($hex_str, $i, 2)));

        }



        $query['Signature'] = base64_encode($raw);

        ksort($query);



        $temp = array();



        foreach ($query as $k => $v) {

                $temp[] = rawurlencode($k) . '=' . rawurlencode($v);

        }



        $final = implode('&', $temp);



        return 'http://' . $host . $path . '?' . $final;

    
}
function getDataFromYelpbyBusiness($name,$city)
{
	$myUrl=trim($name)."-".trim($city);
	$myUrl=str_replace(" ","-",$myUrl);
	$unsigned_url = "http://api.yelp.com/v2/business/".strtolower($myUrl);
	$response=getResponseFromYelp($unsigned_url);
	return $response; 
}
function getReviewfromCitySearch($name,$address,$lat,$long)
{
	$url="http://api.citygridmedia.com/content/reviews/v2/search/where?where=".urlencode($address)."&what=".urlencode($name)."&publisher=".PUBLISHER_CODE;
//$url="http://api.citygridmedia.com/content/reviews/v2/search/latlon?lat=".$lat."&lon=".$long."&radius=10&what=".urlencode($name)."&publisher=10000002697";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml = curl_exec($ch);
	curl_close($ch);
	return xmlstr_to_array($xml);	
}
function getReviewfromYahooLocal($name,$lat,$long)
{
	//$url="http://local.yahooapis.com/LocalSearchService/V3/localSearch?appid=".YAHOO_APP_ID."&query=".urlencode('Oreganos Wood Fired Pizza')."&latitude=37.401704&longitude=-122.114907";
	$url="http://local.yahooapis.com/LocalSearchService/V3/localSearch?appid=".YAHOO_APP_ID."&query=".urlencode($name)."&latitude=".$lat."&longitude=".$long;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml = curl_exec($ch);
	curl_close($ch);
	return xmlstr_to_array($xml);	
}
// We can create our custom functions here
function getResponseFromYelp($unsigned_url)
{
require_once (SITE_ROOT.'OAuth.php');
$consumer_key=CONSUMER_KEY;
$consumer_secret=CONSUMER_SECRET;
$token=CONSUMER_TOKEN;
$token_secret=TOKEN_SECRET;
$token = new OAuthToken($token, $token_secret);
$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);
$oauthrequest->sign_request($signature_method, $consumer, $token);
$signed_url = $oauthrequest->to_url();
$ch = curl_init($signed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch);
curl_close($ch);
$response = json_decode($data);
return $response;	
}
function ResizeAndSaveImage($img, $w, $h) {
	
	$split=explode(".",$img);
	$fileExt=$split[count($split)-1];
		
	$explode=explode("/",$img);
	$imageBaseName=$explode[count($explode)-1];
	$newImageName=time().".".$fileExt;
	$newfilename=SITE_ROOT."images/hairstyles/uploadedphotos/".$newImageName;
	
	//Check if GD extension is loaded
 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
  trigger_error("GD is not loaded", E_USER_WARNING);
  return false;
 }
 
 //Get Image size info
 $imgInfo = getimagesize($img);
 switch ($imgInfo[2]) {
  case 1: $im = imagecreatefromgif($img); break;
  case 2: $im = imagecreatefromjpeg($img);  break;
  case 3: $im = imagecreatefrompng($img); break;
  default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
 }
 
 //If image dimension is smaller, do not resize
 if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
  $nHeight = $imgInfo[1];
  $nWidth = $imgInfo[0];
 }else{
				//yeah, resize it, but keep it proportional
  if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
   $nWidth = $w;
   $nHeight = $imgInfo[1]*($w/$imgInfo[0]);
  }else{
   $nWidth = $imgInfo[0]*($h/$imgInfo[1]);
   $nHeight = $h;
  }
 }
 $nWidth = round($nWidth);
 $nHeight = round($nHeight);
 
 $newImg = imagecreatetruecolor($nWidth, $nHeight);
 
 /* Check if this image is PNG or GIF, then set if Transparent*/  
 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
  imagealphablending($newImg, false);
  imagesavealpha($newImg,true);
  $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
  imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
 }
 imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
 
 //Generate the file, and rename it to $newfilename
 switch ($imgInfo[2]) {
  case 1: imagegif($newImg,$newfilename); break;
  case 2: imagejpeg($newImg,$newfilename);  break;
  case 3: imagepng($newImg,$newfilename); break;
  default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
 }
   
   return $newfilename;
}
  

function ResizeAndSaveImage2Old($ImagePath,$ImageWidth,$ImageHeight)
{
	
	$split=explode(".",$ImagePath);
	$fileExt=$split[count($split)-1];
		
	$explode=explode("/",$ImagePath);
	$imageBaseName=$explode[count($explode)-1];
	$newImageName=time().".".$fileExt;
	$newImagePath=SITE_ROOT."images/hairstyles/uploadedphotos/".$newImageName;
	
	list($UimgWidth,$UimgHeight) = getimagesize($ImagePath);
		
	$ratiow=$UimgWidth/$ImageWidth; 
	$ratioh=$UimgHeight/$ImageHeight;
	if($ratiow>$ratioh)
	{
	$ratioUimage=$ImageWidth/$UimgWidth;
	}
	else
	{
	$ratioUimage=$ImageHeight/$UimgHeight;	
	}
	
	if($UimgWidth > $ImageWidth || $UimgHeight > $ImageHeight) { 
	$newWidthuImage=$UimgWidth * $ratioUimage; 
	$newHeightuImage=$UimgHeight * $ratioUimage; 
	} else {
	$newWidthuImage=$UimgWidth; 
	$newHeightuImage=$UimgHeight;
	} 
	if($fileExt=="jpg" || $fileExt=="jpeg")
	{ 
	header('Content-type: image/jpeg');
	$image_p = imagecreatetruecolor($newWidthuImage, $newHeightuImage);
	$image = TeeImageUtils::imageCreateFromFile($ImagePath);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidthuImage, $newHeightuImage, $UimgWidth, $UimgHeight);
	imagejpeg($image_p,$newImagePath, 100);
	imagedestroy($image_p); 
	}
	if($fileExt=="gif")
	{
	header('Content-type: image/gif');
	$image_p = imagecreatetruecolor($newWidthuImage, $newHeightuImage);
	$image = imagecreatefromgif($ImagePath); 
	$bgc = imagecolorallocate ($image_p, 255, 255, 255);
	imagefilledrectangle ($image_p, 0, 0, $newWidthuImage, $newHeightuImage, $bgc);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidthuImage, $newHeightuImage, $UimgWidth, $UimgHeight);
	imagegif($image_p,$newImagePath,100);
	imagedestroy($image_p); 
	}
	if($fileExt=="png")
	{
	header('Content-type: image/png');
	$image_p = imagecreatetruecolor($newWidthuImage, $newHeightuImage);
	$background = imagecolorallocate($image_p, 255, 255, 255);		
	$image = imagecreatefrompng($ImagePath); 
	imagecolortransparent($image_p,$background);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidthuImage, $newHeightuImage, $UimgWidth, $UimgHeight);
	imagepng($image_p,$newImagePath); 
	imagedestroy($image_p);
	}
	return $newImagePath;
}
function putWaterMark($ImageUser,$ImageHair,$Left,$Top)
{
	header('Content-type: image/jpeg');
	$imagesource =$ImageUser;
	
	$filetype = substr($imagesource,strlen($imagesource)-4,4);
	$filetype = strtolower($filetype);
	
	$filetype1= substr($ImageHair,strlen($ImageHair)-4,4);
	$filetype1= strtolower($filetype1);
	
	$newImageName=SITE_ROOT."images/user_images/hairopinion_".time().$filetype;
	
	if($filetype == ".gif")  $image = @imagecreatefromgif($imagesource);  
	if($filetype == ".jpg")  $image = @imagecreatefromjpeg($imagesource);  
	if($filetype == ".png")  $image = @imagecreatefrompng($imagesource);  
	if (!$image) die(); 
	
	if($filetype1 == ".gif")
	{
	$watermark = @imagecreatefromgif($ImageHair);  
	}	
	if($filetype1 == ".png")
	{
		$watermark = @imagecreatefrompng($ImageHair);  
	}
	
	$imagewidth = imagesx($image); 
	$imageheight = imagesy($image);  
	$watermarkwidth =  imagesx($watermark); 
	$watermarkheight =  imagesy($watermark); 
	//$startwidth = (($imagewidth - $watermarkwidth)/2); 
	//$startheight = (($imageheight - $watermarkheight)/2); 
	imagecopy($image, $watermark,  $Left, $Top, 0, 0, $watermarkwidth, $watermarkheight); 
	imagejpeg($image,$newImageName); 
	imagedestroy($image); 
	imagedestroy($watermark);
	return $newImageName;
}
function getmyTime($format=true)
{
	$sign = "+"; // Whichever direction from GMT to your timezone. + or -
	$h = "12"; // offset for time (hours)
	$dst = true; // true - use dst ; false - don't
	
	if ($dst==true) {
		$daylight_saving = date('I');
		if ($daylight_saving){
			if ($sign == "-"){ $h=$h-1;  }
			else { $h=$h+1; }
		}
	}
	$hm = $h * 60;
	$ms = $hm * 60;
	if ($sign == "-"){ $timestamp = time()-($ms); }
	else { $timestamp = time()+($ms); }
	$gmdate = gmdate("m-d-Y g:i A", $timestamp);
	if($format==true) {
	return $gmdate;
	}
	else {
	return $timestamp;
	}
}
function formatDate($date)
{
	//$date Y-m-d
	$explode=explode("-",$date);
	return $explode[1]."-".$explode[2]."-".$explode[0];
}

function formatDatewithtime($datetime)
{
	//$date Y-m-d
	//$listing = array("Y-m-d"=>"Y-m-d","Y/m/d"=>"Y/m/d","M d, Y"=>"M d, Y","F d, Y"=>"F d, Y","D M d, Y"=>"D M d, Y","l F d, Y"=>"l F d, Y","l F d, Y, h:i:s"=>"l F d, Y, h:i:s","l F d, Y, h:i A"=>"l F d, Y, h:i A");
	/*$explodedt=explode(" ",$datetime);
	$date=$explodedt[0];
	$time=$explodedt[1];
	
	$explode=explode("-",$date);
	return $explode[1]."-".$explode[2]."-".$explode[0]." ".$time;*/
	$format="F d, Y h:i:s";
	//return date($format,$datetime);
	return $datetime;
	
}
function array2json($arr) {
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
} 










//MWW Functions
	function pr($string_to_print) {
		echo "<pre>";
		print_r($string_to_print);
		echo "</pre>";		
	}
	
	function prd($string_to_print) {
		echo "<pre>";
		print_r($string_to_print);
		echo "</pre>";		
		die;
	}
	
	function encrypt($password)
	{
		$len=strlen($password);
		if($len > 0)
		{
			for($i=0;$i<$len;$i++)
			{
				$password[$i]=chr((ord($password[$i])+$len-$i));
			}
			for($i=0;$i<3;$i++)
			{
				$password .= chr(ord($password[$i])+$len);
			}
		}
		
		return $password;
	}
		
	function decrypt($password)
	{
		$len=strlen($password)-3;
		$passwd = "";
		for($i=0;$i<$len;$i++)
		{
			$temp = ord($password[$i])-($len-$i);
			if($temp < 0)
				$temp += 128;
			$passwd .= chr($temp);
		}
		return $passwd;
	}
	
	function isLogged()
	{
		global $mySession;
		
		if (isset($mySession->user['id'])) 
		{	
				return true;
		}
		else 
		{
				return false;
		}
	
	}
	function ArraySearch($haystack, $needle, $index = null) 
	{ 
    $aIt     = new RecursiveArrayIterator($haystack); 
    $it    = new RecursiveIteratorIterator($aIt); 
    
    while($it->valid()) 
    {        
        if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle)) { 
            return $aIt->key(); 
        } 
        
        $it->next(); 
    } 
    
    return false; 
	} 
	function pageauthenticate($url,$event)
	{
		//global $mySession;
//		require_once SITE_PATH . '/models/Menu.php';
//			$Menu = new Menu();	
//			$arr1=$mySession->menu;			
//			$key = ArraySearch($arr1, $url, 'page_url');			
//			
//			if ($key>0)
//			{
//				return true;
//			}else{
//				return false;
//			}
return true;
	}
	function checkevent($url)
	{
		/*global $mySession;
		require_once SITE_PATH . '/models/Menu.php';
		$arr1=$mySession->menu;			
		$key = ArraySearch($arr1, $url, 'page_url');			
		$menuid=0;
		if ($key>0)
		{
			$menuid=$arr1[$key];
		}
		$arr2=$mySession->actionper;			
		$key1 = ArraySearch($arr2, $menuid, 'menu_id');	
		if ($key1>0)		
		{
			return $key1;			
		}else{
			return 0;
		}*/
		return 1;
	}
	
	function sanisitize_input($input_string)
	{
		$san_input=trim(htmlspecialchars(stripslashes($input_string)));
		return $san_input;
	}
	
			
		function implodeData($data)
		{
			$req_value = '';
			foreach ($data as $key=>$value)
			{
		         if(is_array($value))
		         {
		            foreach ($value as $key1=>$value1)
		            {
										if(is_array($value1))
										{
											foreach ($value1 as $key2=>$value2)
											{
													$req_value .= $key2.'=>'.$value2.' , ';
											}
						 			  }
						 			  else
						 			  {
												$req_value .= $key1.'=>'.$value1.' , ';
						 				}
		           }
		         }
		         else
		         {
								$req_value .= $key.'=>'.$value.' , ';
				 			}
			}
			return $req_value;
		}
		
		 
		
		function addslashesInputVar($input_string=null)
		{
			if($input_string)
			{
				$p = array();
				foreach ($input_string as $key=>$value)
				{
					 if(is_array($value))
					 {
					   $temp=array();
					   foreach ($value as $key1=>$value1)
					   {
						$temp[$key1]=addslashes($value1);
					   }
					   $p[$key]=$temp;
					 }
					 else
						$p[$key] = addslashes($value);
				}
				return $p;
			}
		}
		
		function stripslashesInputVar($input_string=null)
		{
			if($input_string)
			{
				$p = array();
				foreach ($input_string as $key=>$value)
				{
					 if(is_array($value))
					 {
					   $temp=array();
					   foreach ($value as $key1=>$value1)
					   {
						$temp[$key1]=stripslashes($value1);
					   }
					   $p[$key]=$temp;
					 }
					 else
						$p[$key] = stripslashes($value);
				}
				return $p;
			}
		}
		
		function unhtmlentities($string)
		{
			$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
			$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);
			return strtr($string, $trans_tbl);
		}
		
		
	
	function dateDiff($startDate,$endDate)
	{
		if($startDate=='0000-00-00'||$startDate=='0000-00-00 00:00:00')
		{
			$startDate=0;
		}
		if($endDate=='0000-00-00'||$endDate=='0000-00-00 00:00:00')
		{
			$endDate=0;
		}
	  	if(trim($startDate)!=0 && $startDate!=NULL)
	 	 {
		  	$startDate = str_replace("/","-",$startDate);
		  	$startDate = explode(' ',$startDate);
		  	$startDate = strtotime($startDate[0]);
	  	 }
	    if(trim($endDate)!=0 && $endDate!=NULL)
	 	 {
		  	$endDate = str_replace("/","-",$endDate);
		  	$endDate = explode(' ',$endDate);
		  	$endDate = strtotime($endDate[0]);
	  	 }
	    //return ($endDate-$startDate);
	    $datediff = $endDate - $startDate ;
	    
	    $day = $datediff / (60*60*24);
	    
	    return $day;
	    
	}
	
	function endDate($date=null,$monthToAdd=null,$yearToAdd=null)
	{
		
		$addDate = 0;
		$addMonth = 0;
		$addYear = 0;
		
		if($date != null && !empty($date) )
		{
			$currentDate = strtotime($date);
		} else {
			$currentDate = time();
		}
	
		if($monthToAdd != null && !empty($monthToAdd))
		{
			$currentDate = strtotime("+".$monthToAdd." month", $currentDate);
		}
		
		if($yearToAdd != null && !empty($yearToAdd))
		{
			$currentDate = strtotime("+".$yearToAdd." year", $currentDate);;
		}		
	
		//pr($currentDate);
		
		$expiredate = date("Y-m-d", $currentDate);
		
		return $expiredate;
		
	}
	
	function findSpaces($value)
		{
		 	if(strpos($value," ")=== false)
		 	{
		 		return false;
		 	}
		 	else
		 	{
		 		return true;
		 	}
		 }
		 
		 
		function createDirectory($dir_name)
		{
			global $CONFIG_VAR;
			if (!file_exists($dir_name))
			{
				mkdir($dir_name, 0777);
				return true;
			}
			else
				return false;
		}
		
		
		function renameDirectory($previousName,$newName)
		{
			global $CONFIG_VAR;
			
			if (rename($previousName,$newName))
				return true;
			else 
				return false;	
		}
		
		
		
		function removeDirectory($dir_name)
		{
			global $CONFIG_VAR;
			
			if ($handle = opendir($dir_name)) 
			{
				while (false !== ($file = readdir($handle))) 
				{
						if($file!='.' && $file!='..')
							unlink($dir_name."/".$file);   						
				}
				closedir($handle);
			}
			if(rmdir($dir_name))
				return true;
			else 
				return false;
		}
		
		
		
		
		function FileExtention($f)
		{
			$arr=explode(".",$f);
			return $arr[count($arr)-1];
		}
		
		
		function createPath($path)
		{
				if(!trim($path))
						return false;
				$dir_ext = substr($path, 0, strrpos($path,'/'));
				if(!is_dir($dir_ext))
				{
					//echo "<BR/>dir_ext:".$dir_ext;
						createPath($dir_ext); //call itself means recursive loop
						if(!is_dir($path))
						{
								mkdir($path);
								chmod($path, 0777);
						}
						return true;
				}
				else
				{
					clearstatcache();
					if(!is_dir($path))
					{
						mkdir($path);
						chmod($path, 0777);
					}
					return true;
				}
		}
		
	function deleteAll($path)
	{
		global $mySession;
		
		
	      foreach(glob($path.'*.*') as $v)
	      {
	      	$org = strstr($v,"_org.");
	      	if($org != false)
	      	{
	      		unlink($v);
	      	}
	      	
	      	$frnd = strstr($v,"_frnd.");
	      	if($frnd != false)
	      	{
	      		unlink($v);
	      	}
	      	
	      	$profile = strstr($v, $mySession->user['Username'].".");
	      	if($profile != false)
	      	{
	      		unlink($v);
	      	}
	      }
	}
	
	function deleteProfileImages($path, $position)
	{
		global $mySession;
		
		
	      foreach(glob($path.'*.*') as $v)
	      {
	      	$org = strstr($v,"_".$position."_org.");
	      	if($org != false)
	      	{
	      		unlink($v);
	      	}
	      	
	      	$pfile = strstr($v,"_".$position.".");
	      	if($pfile != false)
	      	{
	      		unlink($v);
	      	}
	      }
	}
	
	function unsetSessionVars()
	{
		global $mySession;
		
		unset($mySession->errmsg);
		unset($mySession->succmsg);
		unset($mySession->errMsg);
		unset($mySession->errTitle);
		unset($mySession->succTitle);
		unset($mySession->succMsg);
	}
	
	function __autoloadModels($class_name) 
	{
    	require_once APPLICATION_PATH.'application/models/'.$class_name . '.php';
	}
	//spl_autoload_register(__autoloadModels);
	function __autoloadDB($class_name) 
	{
    	require_once APPLICATION_PATH.'application/models/DbTable/'.$class_name . '.php';
	}
	//spl_autoload_register(__autoloadDB);
	
	
	/*function formatDate($date)
	{
		//$returndate = date_format(date_create($date),"M d, Y");
		$returndate = date_format(date_create($date),"m/d/Y");
		//echo $returndate; die;
		return $returndate;
	}*/
	
	//function to create a random value
	function create_random_value($length, $type = 'chars') 
	{
		if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;
	
		$rand_value = '';
		while (strlen($rand_value) < $length) {
		  if ($type == 'digits') {
			$char = rand(0,9);
		  } else {
			$char = chr(rand(0,255));
		  }
		  if ($type == 'mixed') {
			if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
		  } elseif ($type == 'chars') {
			if (eregi('^[a-z]$', $char)) $rand_value .= $char;
		  } elseif ($type == 'digits') {
			if (ereg('^[0-9]$', $char)) $rand_value .= $char;
		  }
		}
	
		return $rand_value;
  	}
	//createjpg($old_image_withpath,$new_image_withpath,$new_height,$new_width);
	function createjpg($old_image, $new_image, $new_height, $new_width)
	{	
			if(copy($old_image,$new_image))
			{
			}
			$destimgthumb=ImageCreateTrueColor($new_width , $new_height) or die("Problem In Creating image"); 
				//echo "<br />destimgthumb =".$destimgthumb ;
			$srcimg=imagecreatefromjpeg($old_image) or die("Problem In opening Source Image"); 
				//echo "<br />srcimg =".$srcimg ; 
			ImageCopyResized($destimgthumb,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg))  or die("Problem In resizing");
			//ImageCopyResized($destimgthumb,$old_image,0,0,0,0,$new_width,$new_height,imagesx($old_image),imagesy($old_image))  or die("Problem In resizing");
					
			 ImageJPEG($destimgthumb,$new_image) or die("Problem In saving"); 	
			//die;
		return; 
	}
	
	//creategif($old_image_withpath,$new_image_withpath,$new_height,$new_width);
	function creategif($old_image, $new_image, $new_height, $new_width)
	{	
		if(copy($old_image,$new_image))
		{
		}
		$destimgthumb=ImageCreateTrueColor($new_width , $new_height) or die("Problem In Creating image"); 
		$srcimg=imagecreatefromgif($old_image) or die("Problem In opening Source Image"); 
		ImageCopyResized($destimgthumb,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg))  or die("Problem In resizing");
		ImageGIF($destimgthumb,$new_image) or die("Problem In saving"); 	
		return; 
	}
	
	//createpng($old_image_withpath,$new_image_withpath,$new_height,$new_width);
	function createpng($old_image, $new_image, $new_height, $new_width)
	{	
		if(copy($old_image,$new_image))
		{
		}
		$destimgthumb=ImageCreateTrueColor($new_width , $new_height) or die("Problem In Creating image"); 
		$srcimg=imagecreatefrompng($old_image) or die("Problem In opening Source Image"); 
		ImageCopyResized($destimgthumb,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg))  or die("Problem In resizing");
		ImagePNG($destimgthumb,$new_image) or die("Problem In saving"); 	
		return; 
	}
	
	
	function sortWorkDate($a=NULL, $b=NULL) 
	{
		 return strcmp($b["DateAdded"],$a["DateAdded"]);
	}
	
	function showLanguageEncode( $string ) {

		return $string;
		if(mb_detect_order($string) ) {
		  return utf8_decode(html_entity_decode(stripslashes($string)));
		} else {
		  return stripslashes(html_entity_decode($string));					  
		}
	}
	
	function myPaging($self,$nume,$start,$limit,$sstring='')
	{
		if($nume!=0)
		{
			$maxpage = ceil($nume/$limit);   
			$current = ($start-1)*$limit;		
	   		$qstring=$sstring;		    	  
			?>		
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td align="left" valign="middle" class="pageText"><strong><span class="span2">Page :&nbsp;&nbsp;</span></strong>
		  <?php
			if($start<=5) 
			{ 
				if($start==1)
				echo "First&nbsp;&nbsp;";
				else
				echo "&nbsp;<a href='$self/start/1/$qstring' class='pagelink'>First</a>&nbsp;&nbsp;"; 
			}
			else 
			echo "&nbsp;<a href='$self/start/1/$qstring' class='pagelink'>First</a>&nbsp;&nbsp;";
			
			$starting=((int)(($start-1)/5)*5)+1;
			if ($starting>5)
			{
				$startpoint=$starting-1;
				$previous=$start-1;
				echo "&nbsp;<a href='$self/start/$previous/$qstring' class='pagelink'>Previous</a>&nbsp;&nbsp;&nbsp;";
			}
			else
			{
				if($start==1)
				echo "Previous&nbsp;&nbsp;&nbsp;";
				else
				{
				$previous=$start-1;
				echo "<a href='$self/start/$previous/$qstring' class='pagelink'>Previous</a>&nbsp;&nbsp;&nbsp;";
				}
			}
			for($i=$starting; $i<=$starting+4; $i++)
			{
				if($start==$i)
					echo "<font class='page'>&nbsp;$i&nbsp;</font>";
				else
				{
					if($i<=$maxpage)
					echo "&nbsp;<a href='$self/start/$i/$qstring' class='pagelink'>$i</a>&nbsp;&nbsp;";
					else
					break;
				}
			}
			if ($starting+4<$maxpage)
			{
				$nextstart=$i/5;
				$next=$start+1;
				echo "&nbsp;&nbsp;&nbsp;<a href='$self/start/$next/$qstring' class='pagelink'>Next</a>&nbsp;";
			}
			else
			{
				if($start==$maxpage)
				echo "&nbsp;&nbsp;&nbsp;Next";
				else
				{
					$next=$start+1;
					echo "&nbsp;&nbsp;&nbsp;<a href='$self/start/$next/$qstring' class='pagelink'>Next</a>&nbsp;";
				}
			}		
			if($start>$maxpage-4)
			{ 
				if($start==$maxpage)
				echo "&nbsp;&nbsp;Last";
				else
				echo "&nbsp;&nbsp;<a href='$self/start/".$maxpage."/$qstring' class='pagelink'>Last</a>&nbsp;";
			}
			else 
			echo "&nbsp;&nbsp;<a href='$self/start/".$maxpage."/$qstring' class='pagelink'>Last</a>&nbsp;"; 
			?>
			</td>
			<?php /*?><td width="200" align="left" valign="middle" class="pageText">
			<div align="right">Showing
			<?php 
			if ($start*10>$nume)
			{
				$upperlimit=$nume;
				echo ($current+1 ." - ". $upperlimit ." of ". $nume); 
			}
			else 
			echo ($current+1 ." - ". $start*10 ." of ". $nume);
			?>
			</div></td><?php */?>
			</tr>
		</table>
		<?php 
		}
	}
	
function sendmail($to,$from,$subject,$mailtext)
{ 
        $mail = new Zend_Mail();
		
		$mail->setBodyText($mailtext);
		  
		$mail->setBodyHtml($mailtext);
		  
		$mail->setFrom($to);
		
		$mail->addTo($from);
		
		$mail->setSubject($subject); 
		
		//$mail->send(); 
		return 1;
 
}
function truncate($text, $length = 100, $options = array()) {
		$default = array(
			'ending' => '...', 'exact' => true, 'html' => false
		);
		$options = array_merge($default, $options);
		extract($options);

		if ($html) {
			if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			$totalLength = mb_strlen(strip_tags($ending));
			$openTags = array();
			$truncate = '';

			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
			foreach ($tags as $tag) {
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
						array_unshift($openTags, $tag[2]);
					} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
						$pos = array_search($closeTag[1], $openTags);
						if ($pos !== false) {
							array_splice($openTags, $pos, 1);
						}
					}
				}
				$truncate .= $tag[1];

				$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
				if ($contentLength + $totalLength > $length) {
					$left = $length - $totalLength;
					$entitiesLength = 0;
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entitiesLength <= $left) {
								$left--;
								$entitiesLength += mb_strlen($entity[0]);
							} else {
								break;
							}
						}
					}

					$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
					break;
				} else {
					$truncate .= $tag[3];
					$totalLength += $contentLength;
				}
				if ($totalLength >= $length) {
					break;
				}
			}
		} else {
			if (mb_strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
			}
		}
		if (!$exact) {
			$spacepos = mb_strrpos($truncate, ' ');
			if (isset($spacepos)) {
				if ($html) {
					$bits = mb_substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
					if (!empty($droppedTags)) {
						foreach ($droppedTags as $closingTag) {
							if (!in_array($closingTag[1], $openTags)) {
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}
				$truncate = mb_substr($truncate, 0, $spacepos);
			}
		}
		$truncate .= $ending;

		if ($html) {
			foreach ($openTags as $tag) {
				$truncate .= '</'.$tag.'>';
			}
		}


		return $truncate;
	}	
	
	function displaySubStringWithStrip($string, $length=NULL)
		{
			if ($length == NULL)
					$length = 50;
		   
			$stringDisplay = substr(strip_tags($string), 0, $length);
			if (strlen(strip_tags($string)) > $length)
				$stringDisplay .= ' ...';
			return $stringDisplay;
		}
	function fun_paginator($ArrData)
	{
		 
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($ArrData));
		
		$currentPage = isset($_GET['p']) ? (int) htmlentities($_GET['p']) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage =12;
		$pager->setItemCountPerPage($itemsPerPage);
		$pager->setPageRange(10);
		
		$pages = $pager->getPages(); 
		$pageLinks = array();
		$separator = ' | ';
		//$this->view->separator=$separator;
		if($pages->pageCount>1) 
		{
			if($pages->current == $pages->first)
			{
				$pageLinks[] = "<li >First</li>";
				$pageLinks[] = "<li >Privious</li>";
			}
			else 
			{
				$previous = $pages->current-1;
				$first = $pages->first;
				$pageLinks[] ="<li  ><a href=\"?p=$first\">First</a></li>";
				$pageLinks[] = "<li><a href=\"?p=$previous\">Privious</a></li>";
			}
			
			for ($x=$pages->firstPageInRange; $x<=$pages->lastPageInRange; $x++) 
			{
			
			
			if ($x == $pages->current) 
				{
					$pageLinks[] = "<li>".$x."</li>";
				} 
			else 
				{
					$q = http_build_query(array('p' => $x));
					$pageLinks[] = "<li><a href=\"?$q\">$x</a></li>";
				}
			}
			
			if($pages->current == $pages->last)
			{
				$pageLinks[] = "<li>Next</li>";
				$pageLinks[] = "<li>Last</li>";
			} 
			else
			{
				$last = $pages->last;
				$next = $pages->current+1;
				$pageLinks[] ="<li><a href=\"?p=$next\">Next</a></li>";
				$pageLinks[] = "<li><a href=\"?p=$last\">Last</a></li>";
			}
		}
		$pagedata['pageLinks']=$pageLinks;
		$pagedata['pagingdata']= $pager->getCurrentItems();
		return $pagedata;
	}
	function printpagelink($pageArray)
		{
			?>
			<div id="pagination" align="right" style="width:100%">
				<ul class="RecPagingUL">
			<?php
				echo implode($pageArray,"");
			?>
				</ul>
			</div>
			<?php
		}
	function getDbPageId($condition="")
		{
			$Controller=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
			$Action=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		
			$db=new Db();
			$criteria=" page_controller='".$Controller."'";
			$criteria = $criteria." and page_action='".$Action."'";
			if($condition="")
				{$criteria = $criteria." and condition='".$condition."'";}
			$pageid=0;
			$Page_Data=$db->runQuery("select * from tbl_page where ".$criteria);
			if(count($Page_Data)>0)
			{
				$pageid=$Page_Data[0]['page_id'];
			}
			return $pageid;
		}
?>	