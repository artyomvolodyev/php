<?php
__autoloadDB('Db');
error_reporting(0);

class PaymentController extends AppController
{
	public function indexAction()
	{
        global $mySession;
		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$url=$this->getRequest()->getParam('url');
		$this->view->url=$url;

		$myform=new Form_Indexpayement();

		$this->view->myform=$myform;
	}

	public function savevaluesAction()
	{
        //error_log('savevaluesAction REQUEST: '.print_r($_REQUEST, true));
		global $mySession;
		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$url=$this->getRequest()->getParam('url');

		$myform1=new Form_Buy();

		$sql=$db->runquery("select * from ".LAUNCHCAMPAIGN." where url='".$url."'");
		$shhpng=$db->runquery("select * from ".TSHIRT_PRICE." where campagin_id='".$sql[0]['SelectedProduct']."'");

		$shhpprice = $shhpng[0]['shipping_price'];
		$price= $sql[0]['selling_price'];

		if($_REQUEST['countdiv']==0) {
			$pricetot = $_REQUEST['total_Value_text'];
		} else {
			$pricetot = $_REQUEST['total_Value_text']+$shhpprice;
		}

		$cnt=$_REQUEST['countdiv'];
		$qua=0;$size='';

		for($i=1;$i<=$cnt;$i++)
		{
			$qua+=$_REQUEST['qty_'.$i];
			$size.=$_REQUEST['qty_'.$i]."*".$_REQUEST['size'.$i].",";
		}

		$qua+=$_REQUEST['qty'];
		$size.=$_REQUEST['qty']."*".$_REQUEST['size'];
		$qq=$_REQUEST['totquanty'];
		$this->view->url=$url;

		$mySession->sizes=$size;
		$mySession->totalquantity=$qq;
		$mySession->amount=$price;
		$mySession->totalamt=$pricetot;
		$mySession->url=$url;

		if($_REQUEST['paypalchk']==1)
		{
			$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE url='".$url."'");

			// X004 getting parameter for the payment of tees
			$payment_params = array
			(
				"user" => $mySession->TeeLoggedID,
				"sizes" => $mySession->sizes,
				"cart_details" => $_REQUEST,
				"camp_details" => $Data
			);

			require_once (dirname (dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
			require_once (dirname (dirname (__FILE__)) . '/crowdfunding/Crowdfunding.php');

			$crowdfunding = new Crowdfunding ();
			$prekey = $crowdfunding->create_preapproval($payment_params);

            die();

			/*
			echo "<h1 align='center'>You are redirecting Please wait...</h1>";

			echo '<form action="https://www.paypal.com/cgi-bin/webscr" name="payment_frm" id="payment_frm" method="post">';

			//echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" name="payment_frm" id="payment_frm" method="post">';

			//echo '<input type="hidden" name="cmd" value="_xclick">';
			echo '<input type="hidden" name="cmd" value="_ext-enter">';
			echo '<input type="hidden" name="redirect_cmd" value="_xclick">';



			//echo '<input type="hidden" name="business" value="techbizsol@gmail.com">';
			echo '<input type="hidden" name="business" value="'.PAYPAL_EMAIL.'">';

			echo '<input type="hidden" name="no_note" value="1">';
			echo '<input type="hidden" name="image_url" value="'.APPLICATION_URL.'images/logo.png">';
			echo '<input type="hidden" name="item_name" value="'.$sql[0]['url'].'">';
			echo '<input type="hidden" name="package_name" value="">';

			echo '<input type="hidden" name="email" value="'.$_REQUEST['cemail'].'">';
			echo '<input type="hidden" name="first_name" value="'.$_REQUEST['cfname'].'">';
			echo '<input type="hidden" name="last_name" value="'.$_REQUEST['clname'].'">';

			//echo '<input type="hidden" name="amount" value="0.01">';
			echo '<input type="hidden" name="amount" value="'.$mySession->totalamt.'">';

			echo '<input type="hidden" name="currency_code" value="USD">';
			echo '<input type="hidden" name="charset" value="utf-8">';
			echo '<input type="hidden" name="rm" value="2" >';
			echo '<input type="hidden" name="cs" value="1" >';
			echo '<input type="hidden" name="custom" value="'.$url.'" >';
			echo '<input type="hidden" name="upload" value="1">';


			echo '<input type="hidden" name="return" id="return" value="'.APPLICATION_URL.'payment/success/Return/1"/>';
			echo '<input type="hidden" name="cancel_return" value="'.APPLICATION_URL.'payment/success/Return/2/url/'.$url.'">';
			//echo '<input type="hidden" name="notify_url"  value="'.APPLICATION_URL.'payment/notify"/>';



			echo '<input type="submit" value="Paypal" name="btn" style="display:none;" />';

			echo '</form>';
			echo '<script>window.document.payment_frm.submit();</script>';
			*/

		}
	}




public function showAction()
{
	global $mySession;
	$db=new Db();

	$this->_helper->layout->setLayout('myaccount');
}

public function notifyAction() {

    $db=new Db();
	if(!empty($_POST)) {
	$post_str="cmd=_notify-validate";
	foreach($_POST as $key=>$value) {
		$post_str .="&".$key."=".$value;

	}

	$ipnexec = curl_init();

	//curl_setopt($ipnexec, CURLOPT_URL, "https://www.paypal.com/webscr&"); // test url
	//https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate&mc_gross=19.95&protection_eligibility=Eligible&address_status=confirmed&payer_id=LPLWNMTBWMFAY&tax=0.00&address_street=1+Main+St&payment_date=20%3A12%3A59+Jan+13%2C+2009+PST&payment_status=Completed&charset=windows-1252&address_zip=95131&first_name=Test&mc_fee=0.88&address_country_code=US&address_name=Test+User&notify_version=2.6&custom=&payer_status=verified&address_country=United+States&address_city=San+Jose&quantity=1&verify_sign=AtkOfCXbDm2hu0ZELryHFjY-Vb7PAUvS6nMXgysbElEn9v-1XcmSoGtf&payer_email=gpmac_1231902590_per%40paypal.com&txn_id=61E67681CH3238416&payment_type=instant&last_name=User&address_state=CA&receiver_email=gpmac_1231902686_biz%40paypal.com&payment_fee=0.88&receiver_id=S8XGHLYDW9T3S&txn_type=express_checkout&item_name=&mc_currency=USD&item_number=&residence_country=US&test_ipn=1&handling_amount=0.00&transaction_subject=&payment_gross=19.95&shipping=0.00
	curl_setopt($ipnexec, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr'); // live url

	curl_setopt($ipnexec, CURLOPT_HEADER, 0);
	curl_setopt($ipnexec, CURLOPT_USERAGENT, 'Server Software: '.@$_SERVER['SERVER_SOFTWARE'].' PHP Version: '.phpversion());

	curl_setopt($ipnexec, CURLOPT_REFERER, $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']);
	curl_setopt($ipnexec, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ipnexec, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ipnexec, CURLOPT_POST, 1);
	curl_setopt($ipnexec, CURLOPT_POSTFIELDS, $post_str);
	curl_setopt($ipnexec, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ipnexec, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ipnexec, CURLOPT_TIMEOUT, 30);

	$ipnresult = trim(curl_exec($ipnexec));
	$ipnresult = "status=".$ipnresult;
	curl_close($ipnexec);

	if($ipnresult=='VERIFIED') {
		//Check payment status
		if($_POST['payment_status']== 'Completed') {
			$ipn=$db->runQuery("select * from ipn WHERE  	txn_id='".$_POST['txn_id']."'");
			if(empty($ipn)) {
				$uid=0;
				$dataInsert=array();
				$dataInsert['receiver_email']=$_POST['receiver_email'];
				$dataInsert['test_ipn']=$_POST['test_ipn'];
				$dataInsert['txn_id']=$_POST['txn_id'];
				$dataInsert['txn_type']=$_POST['txn_type'];
				$dataInsert['payer_email']=$_POST['payer_email'];
				$dataInsert['payer_id']=$_POST['payer_id'];
				$dataInsert['payer_status']=$_POST['payer_status'];
				$dataInsert['first_name']=$_POST['first_name'];
				$dataInsert['last_name']=$_POST['last_name'];
				$dataInsert['custom']=$_POST['custom'];
				$dataInsert['handling_amount']=$_POST['handling_amount'];
				$dataInsert['item_name']=$_POST['item_name'];
				$dataInsert['item_number']=$_POST['item_number'];
				$dataInsert['mc_currency']=$_POST['mc_currency'];
				$dataInsert['mc_fee']=$_POST['mc_fee'];
				$dataInsert['mc_gross']=$_POST['mc_gross'];
				$dataInsert['payment_date']=$_POST['payment_date'];
				$dataInsert['payment_fee']=$_POST['payment_fee'];
				$dataInsert['payment_gross']=$_POST['payment_gross'];
				$dataInsert['payment_status']=$_POST['payment_status'];
				$dataInsert['payment_type']=$_POST['payment_type'];
				$dataInsert['protection_eligibility']=$_POST['protection_eligibility'];
				$dataInsert['quantity']=$_POST['quantity'];
				$dataInsert['shipping']=$_POST['shipping'];
				$dataInsert['tax']=$_POST['tax'];
				$dataInsert['charset']=$_POST['charset'];
				$dataInsert['verify_sign']=$_POST['verify_sign'];
				$db->Save("ipn",$dataInsert);



				$streetval=str_replace('+',' ',$_POST['address_street']);
				$cityval=str_replace('+',' ',$_POST['address_city']);
				$stateval=str_replace('+',' ',$_POST['address_state']);
				$countryval=str_replace('+',' ',$_POST['address_country']);
				$buyeraddrs=$streetval." , ".$cityval." , ".$stateval." , ".$countryval;


				$emaid=str_replace('%40','@',$_POST['payer_email']);

				//process buyers emails
				$buyers=$db->runQuery("select * from ".BUYERS." WHERE  	email='".$emaid."'");
				if(empty($buyers)) {
					$uid=0;
					$dataInsert=array();
					$dataInsert['email']=$emaid;
					$dataInsert['user_id']=$uid;
					$dataInsert['name']=$emaid;
					$db->Save(BUYERS,$dataInsert);
				}



				$idqry=$db->runquery("select * from ".LAUNCHCAMPAIGN." where url='".$_POST['custom']."'");

				$userid=$idqry[0]['user_id'];

				$useremail=$db->runquery("select * from ".USERS." where user_id='".$userid."'");

				$emailidcreator=$useremail[0]['emailid'];

				$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='14'");



				$messageText=$templateData[0]['email_body'];

				$subject=$templateData[0]['email_subject'];
				$messageText=str_replace("[NAME]",$emailidcreator,$messageText);
				$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);

				SendEmail($emailidcreator,$subject,$messageText);

				$dataInsert['name']=$value_array['first_name']." ".$value_array['last_name'];

				$dataInsert['total_quantity']=$mySession->totalquantity;

				$dataInsert['emailid']=$emaid;

				$dataInsert['teeurl']=$url;

				$dataInsert['size']=$mySession->sizes;

				//$dataInsert['quantity']=$mySession->seeqty;

				$dataInsert['totalamt']=$mySession->totalamt;

				$dataInsert['amount']=$mySession->amount;

				$dataInsert['t_image']='designtee.png';

				$dataInsert['shipping_address']=$buyeraddrs;

				$dataInsert['order_date']=date('Y-m-d H:i:s');

				$db->save(ORDER_RECORD,$dataInsert);

				$id = $db->lastInsertId();
				$orderno='000D'.$id;

				$myObj=new Myaccountdb();
				$data=$myObj->emailorder($orderno,$emaid);
				if($data==1)
				{
					$mySession->errorMsg="Order Placed. Order Number Mailed to you";
				}
			}
		}
		}
	}
}

public function successAction()
{

	global $mySession;
	$db=new Db();

	$userId=$mySession->TeeLoggedID;
	$url=$mySession->urll;
	$qty=$mySession->totalquantity;
	$Return=$this->getRequest()->getParam('Return');

    //error_log('successAction REQUEST: '.print_r($_REQUEST, true));

	if($Return==2)
	{
		$mySession->errorMsg="Your payment is cancelled. Order not placed";
		$this->_redirect(APPLICATION_URL.$url);
	}
	else
	{

		/*Array ( [mc_gross] => 0.01 [protection_eligibility] => Eligible [address_status] => unconfirmed [payer_id] => 64VXBYUMSD8L8 [tax] => 0.00 [address_street] => Majakovskeho 21 [payment_date] => 01:46:48 Jan 27, 2014 PST [payment_status] => Completed [charset] => utf-8 [address_zip] => 36005 [first_name] => Ondrej [mc_fee] => 0.01 [address_country_code] => CZ [address_name] => Ondrej Svarc [notify_version] => 3.7 [custom] => dferer [payer_status] => verified [business] => anjali.compengg@gmail.com [address_country] => Czech Republic [address_city] => Karlovy Vary [quantity] => 1 [payer_email] => 8ndra@seznam.cz [verify_sign] => An5ns1Kso7MWUdW4ErQKJJJ4qi4-Ab9d35PdXbXD6qGTCboyxW3UQppU [txn_id] => 1SM930808W252873L [payment_type] => instant [last_name] => Svarc [address_state] => Rybare [receiver_email] => anjali.compengg@gmail.com [payment_fee] => 0.01 [receiver_id] => E44ASZ38AX3AN [txn_type] => web_accept [item_name] => dferer [mc_currency] => USD [item_number] => [residence_country] => CZ [handling_amount] => 0.00 [transaction_subject] => dferer [payment_gross] => 0.01 [shipping] => 0.00 [auth] => AO7sJT4QNoTT8tCvaDoNoCtQk1eMEEfYpOf3TzR2APnJVz7ovC9Ruoh7CW5vWO.8-TojKE1RSUfEUPkSZnkcgjw ) Array ( [mc_gross] => 0.01 [protection_eligibility] => Eligible [address_status] => unconfirmed [payer_id] => 64VXBYUMSD8L8 [tax] => 0.00 [address_street] => Majakovskeho 21 [payment_date] => 01:46:48 Jan 27, 2014 PST [payment_status] => Completed [charset] => utf-8 [address_zip] => 36005 [first_name] => Ondrej [mc_fee] => 0.01 [address_country_code] => CZ [address_name] => Ondrej Svarc [notify_version] => 3.7 [custom] => dferer [payer_status] => verified [business] => anjali.compengg@gmail.com [address_country] => Czech Republic [address_city] => Karlovy Vary [quantity] => 1 [payer_email] => 8ndra@seznam.cz [verify_sign] => An5ns1Kso7MWUdW4ErQKJJJ4qi4-Ab9d35PdXbXD6qGTCboyxW3UQppU [txn_id] => 1SM930808W252873L [payment_type] => instant [last_name] => Svarc [address_state] => Rybare [receiver_email] => anjali.compengg@gmail.com [payment_fee] => 0.01 [receiver_id] => E44ASZ38AX3AN [txn_type] => web_accept [item_name] => dferer [mc_currency] => USD [item_number] => [residence_country] => CZ [handling_amount] => 0.00 [transaction_subject] => dferer [payment_gross] => 0.01 [shipping] => 0.00 [auth] => AO7sJT4QNoTT8tCvaDoNoCtQk1eMEEfYpOf3TzR2APnJVz7ovC9Ruoh7CW5vWO.8-TojKE1RSUfEUPkSZnkcgjw [PHPSESSID] => db21a73a49d1cf76999ed7b72eac5a2f [__unam] => 7639673-142460e9e5b-75f44219-5 ) Array ( [status] => FAIL )*/
	    if(!empty($_POST) && ($_POST['payment_status']=='Completed')) {

		    //Insert into ipn table
            $dataInsert=array();
            $dataInsert['receiver_email']=$_POST['receiver_email'];
            $dataInsert['test_ipn']=$_POST['test_ipn'];
            $dataInsert['txn_id']=$_POST['txn_id'];
            $dataInsert['txn_type']=$_POST['txn_type'];
            $dataInsert['payer_email']=$_POST['payer_email'];
            $dataInsert['payer_id']=$_POST['payer_id'];
            $dataInsert['payer_status']=$_POST['payer_status'];
            $dataInsert['first_name']=$_POST['first_name'];
            $dataInsert['last_name']=$_POST['last_name'];
            $dataInsert['custom']=$_POST['custom'];
            $dataInsert['handling_amount']=$_POST['handling_amount'];
            $dataInsert['item_name']=$_POST['item_name'];
            $dataInsert['item_number']=$_POST['item_number'];
            $dataInsert['mc_currency']=$_POST['mc_currency'];
            $dataInsert['mc_fee']=$_POST['mc_fee'];
            $dataInsert['mc_gross']=$_POST['mc_gross'];
            $dataInsert['payment_date']=$_POST['payment_date'];
            $dataInsert['payment_fee']=$_POST['payment_fee'];
            $dataInsert['payment_gross']=$_POST['payment_gross'];
            $dataInsert['payment_status']=$_POST['payment_status'];
            $dataInsert['payment_type']=$_POST['payment_type'];
            $dataInsert['protection_eligibility']=$_POST['protection_eligibility'];
            $dataInsert['quantity']=$_POST['quantity'];
            $dataInsert['shipping']=$_POST['shipping'];
            $dataInsert['tax']=$_POST['tax'];
            $dataInsert['charset']=$_POST['charset'];
            $dataInsert['verify_sign']=$_POST['verify_sign'];
            $db->Save("ipn",$dataInsert);


            //Update sold quantaity
            $Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE url='".$url."'");

            $no_ofsold=$Data[0]['sold'];
            $no_ofsold=$no_ofsold + $qty;
            $data_update['sold']=$no_ofsold;
            $condition="url='".$url."'";
            $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

            //process emails
            $value_array=$_POST;
            $streetval=str_replace('+',' ',$value_array['address_street']);
            $cityval=str_replace('+',' ',$value_array['address_city']);
            $stateval=str_replace('+',' ',$value_array['address_state']);
            $countryval=str_replace('+',' ',$value_array['address_country']);
            $buyeraddrs=$streetval." , ".$cityval." , ".$stateval." , ".$countryval;
            $emaid=str_replace('%40','@',$value_array['payer_email']);

            //process buyers emails
            $buyers=$db->runQuery("select * from ".BUYERS." WHERE  	email='".$emaid."'");
            if(empty($buyers)) {
                $uid=0;
                $dataInsert=array();
                $dataInsert['email']=$emaid;
                $dataInsert['user_id']=$uid;
                $dataInsert['name']=$_POST['first_name'].' '.$_POST['last_name'];
                $db->Save(BUYERS,$dataInsert);
            }



            $idqry=$db->runquery("select * from ".LAUNCHCAMPAIGN." where url='".$_POST['custom']."'");
            $userid=$idqry[0]['user_id'];
            $useremail=$db->runquery("select * from ".USERS." where user_id='".$userid."'");
            $emailidcreator=$useremail[0]['emailid'];
            $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='14'");
            $messageText=$templateData[0]['email_body'];
            $subject=$templateData[0]['email_subject'];
            $messageText=str_replace("[NAME]",$emailidcreator,$messageText);
            $messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
            //$messageText=str_replace("[ORDERNO]",$orderno,$messageText);
            SendEmail($emailidcreator,$subject,$messageText);



            $dataInsert=array();
            $dataInsert['name']=$value_array['first_name']." ".$value_array['last_name'];
            $dataInsert['total_quantity']=$mySession->totalquantity;
            $dataInsert['emailid']=$emaid;
            $dataInsert['teeurl']=$url;
            $dataInsert['size']=$mySession->sizes;
            //$dataInsert['quantity']=$mySession->seeqty;
            $dataInsert['totalamt']=$mySession->totalamt;
            $dataInsert['amount']=$mySession->amount;
            $dataInsert['t_image']='designtee.png';
            $dataInsert['shipping_address']=$buyeraddrs;
            $dataInsert['order_date']=date('Y-m-d H:i:s');
            $db->save(ORDER_RECORD,$dataInsert);
            $id = $db->lastInsertId();
            $orderno='000D'.$id;


            $myObj=new Myaccountdb();
            $data=$myObj->emailorder($orderno,$emaid);
            if($data==1)
            {
                $mySession->errorMsg="Order Placed. Order Number Mailed to you";
            }
        } else {
            $mySession->errorMsg="Your payment is cancelled. Order not placed";
            $this->_redirect(APPLICATION_URL.$url);
        }
    }




    $mySession->sizes="";

    unset($mySession->sizes);

    $mySession->urll="";

    unset($mySession->urll);

    $mySession->totalquantity="";

    unset($mySession->totalquantity);

    $mySession->amount="";

    unset($mySession->amount);

    $mySession->totalamt="";

    unset($mySession->totalamt);



    $this->_redirect(APPLICATION_URL.$url);

    //$this->_redirect('payment/register');

}





public function paythroughcreditAction()

{



global $mySession;

$db=new Db();

$this->_helper->layout->setLayout('myaccount');



$url=$this->getRequest()->getParam('url');



if ($this->getRequest()->isPost())

{

$userId=$mySession->TeeLoggedID;

$request=$this->getRequest();







$myform=new Form_Indexpayement();

//$myform=new Form_Indexpayement($url);



if ($myform->isValid($request->getPost()))

{

$dataForm=$myform->getValues();



//$packageId=$this->getRequest()->getParam('packageId');



$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE url='".$url."'");



//$getamount=$mySession->price;



require_once("paypal_pro.inc.php");



$emai_id = $dataForm['emailid'];

//require_once("includes/config.php");





$firstName =urlencode( $dataForm['fname']);

$lastName =urlencode( $dataForm['lname']);

$creditCardType =urlencode( $dataForm['creditcardtype']);

$creditCardNumber = urlencode($dataForm['creditcardno']);

$expDateMonth =urlencode( $dataForm['exprymonth']);

$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

$expDateYear =urlencode( $dataForm['expryyear']);

$cvv2Number = urlencode($dataForm['cvvno']);

$address1 = urlencode($dataForm['address']);

//$address2 = urlencode($_POST['address2']);

$city = urlencode($dataForm['city']);

$state =urlencode( $dataForm['state']);

$zip = urlencode($dataForm['zipcode']);

$amount = $mySession->totalamt;

$currencyCode="USD";

$paymentAction = urlencode("Sale");



if($_POST['recurring'] == 1) // For Recurring

{

$profileStartDate = urlencode(date('Y-m-d h:i:s'));

$billingPeriod = urlencode($_POST['billingPeriod']);// or "Day", "Week", "SemiMonth", "Year"

$billingFreq = urlencode($_POST['billingFreq']);// combination of this and billingPeriod must be at most a year

$initAmt = $amount;

$failedInitAmtAction = urlencode("ContinueOnFailure");

$desc = urlencode("Recurring $".$amount);

$autoBillAmt = urlencode("AddToNextBilling");

$profileReference = urlencode("Anonymous");

$methodToCall = 'CreateRecurringPaymentsProfile';

$nvpRecurring ='&BILLINGPERIOD='.$billingPeriod.'&BILLINGFREQUENCY='.$billingFreq.'&PROFILESTARTDATE='.$profileStartDate.'&INITAMT='.$initAmt.'&FAILEDINITAMTACTION='.$failedInitAmtAction.'&DESC='.$desc.'&AUTOBILLAMT='.$autoBillAmt.'&PROFILEREFERENCE='.$profileReference;

}

else

{

$nvpRecurring = '';

$methodToCall = 'doDirectPayment';

}







$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.         $padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;





/*$paypalPro = new paypal_pro('sdk-three_api1.sdk.com', 'QFZCWN5HZM8VBG7Q', 'A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI', '', '', TRUE, FALSE );

*/



$paypalPro = new paypal_pro('8ndra_api1.seznam.cz', 'J3K2SD2RXGG3ZTUY', 'AFcWxV21C7fd0v3bYYYRCpSSRl31A1bWIW8aO685Dq4z21-5wttwtLoY', '', '', TRUE, FALSE );



$resArray = $paypalPro->hash_call($methodToCall,$nvpstr);



$ack = strtoupper($resArray["ACK"]);



if($ack!="SUCCESS")

{

echo '<table border="0" cellspacing="4" cellpadding="4" align="center" style="padding-top:40px; padding-bottom:80px;">';

//echo "if";

echo '<tr>';

echo '<td colspan="2" style="font-weight:bold; font-size:20px; color:Green" align="center">Error! Please check that u will provide all information correctly :(</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> TIMESTAMP:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["TIMESTAMP"].'</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> Correlation ID:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray['CORRELATIONID'].'</td>';

echo '</tr>';



echo '<tr>';

echo '<td align="left" style="font-size:18px;">ACK:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray['ACK'].'</td>';

echo '</tr>';



echo '<td align="left" style="font-size:18px;"> VERSION:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["VERSION"].'</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> BUILD:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["BUILD"].'</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> L_ERRORCODE0:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["L_ERRORCODE0"].'</td>';

echo '</tr>';

echo '<td align="left" style="font-size:18px;"> L_SHORTMESSAGE0:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["L_SHORTMESSAGE0"].'</td>';

echo '</tr>';

echo '<td align="left" style="font-size:18px;"> L_LONGMESSAGE0:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["L_LONGMESSAGE0"].'</td>';

echo '</tr>';

echo '<td align="left" style="font-size:18px;"> L_SEVERITYCODE0:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["L_SEVERITYCODE0"].'</td>';

echo '</tr>';

echo '</tr>';

echo '<td align="left" style="font-size:18px;"> L_SEVERITYCODE0:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["L_SEVERITYCODE0"].'</td>';

echo '</tr>';



echo '<tr>';

echo '<td align="left" style="font-size:18px;"> AMT:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["AMT"].'</td>';

echo '</tr>';



echo '</table>';

}

else

{

echo '<table border="0" cellspacing="4" cellpadding="4" align="center" style="padding-top:40px; padding-bottom:80px;">';

//echo "success";

echo '<tr>';

echo '<td colspan="2" style="font-weight:bold; font-size:20px; color:Green" align="center">Thank You For Your Payment :)</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> Transaction ID:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$resArray["TRANSACTIONID"].'</td>';

echo '</tr>';

echo '<tr>';

echo '<td align="left" style="font-size:18px;"> Amount:</td>';

echo '<td style="font-size:18px; color:#006633;">'.$currencyCode.$resArray['AMT'].'</td>';

echo '</tr>';



echo '</table>';



$qty=$mySession->totalquantity;



$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE url='".$url."'");

//echo "select * from ".LAUNCHCAMPAIGN." WHERE url='".$url."'"; die;



$no_ofsold = $Data[0]['sold'];

$no_ofsold = $no_ofsold + $qty;



//echo "No. Of T sold :".$no_ofsold; die;



$data_update['sold']=$no_ofsold;

$condition="url='".$url."'";



$addrs=$dataForm['address']." , ".$dataForm['city']." , ".$dataForm['state'];



//echo "address: ".$addrs; die;

//prd($condition);



$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);





$idqry=$db->runquery("select * from ".LAUNCHCAMPAIGN." where url='".$url."'");

$userid=$idqry[0]['user_id'];

$useremail=$db->runquery("select * from ".USERS." where user_id='".$userid."'");

$emailidcreator=$useremail[0]['emailid'];



//echo "after payment mail to : ".$emailidcreator; die;



$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='14'");



$messageText=$templateData[0]['email_body'];



$subject=$templateData[0]['email_subject'];



$messageText=str_replace("[NAME]",$emailidcreator,$messageText);



$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);



//$messageText=str_replace("[ORDERNO]",$orderno,$messageText);

//echo "msg text".$messageText;



SendEmail($emailidcreator,$subject,$messageText);







$dataInsert['name']=$dataForm['fname']." ".$dataForm['lname'];

$dataInsert['total_quantity']=$mySession->totalquantity;

$dataInsert['emailid']=$dataForm['emailid'];

$dataInsert['teeurl']=$url;

$dataInsert['size']=$mySession->sizes;

$dataInsert['t_image']='designtee.png';

//$dataInsert['quantity']=$mySession->seeqty;

$dataInsert['totalamt']=$mySession->totalamt;

$dataInsert['amount']=$mySession->amount;

$dataInsert['shipping_address']=$addrs;

$dataInsert['order_date']=date('Y-m-d H:i:s');





//	prd($dataInsert);



$db->save(ORDER_RECORD,$dataInsert);



$id = $db->lastInsertId();



$orderno='000D'.$id;



//echo "Your Order No. is: ".$orderno;





$myObj=new Myaccountdb();

$data=$myObj->emailorder($orderno,$dataForm['emailid']);

//$mySession->errorMsg="Your Order No. is: ".$orderno;

if($data==1)

{

$mySession->errorMsg="Order Placed. Order Number Mailed to you";

}





$mySession->sizes="";

unset($mySession->sizes);

$mySession->totalquantity="";

unset($mySession->totalquantity);

$mySession->amount="";

unset($mySession->amount);

$mySession->totalamt="";

unset($mySession->totalamt);

}

}

}

}

}
