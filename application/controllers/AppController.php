<?php 

__autoloadDB('Db');

class AppController extends Zend_Controller_Action

{	

	public function init()
    {
		global $mySession;
		$db=new Db();

		$myControllerName=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$myActionName=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		$this->view->myControllerName=$myControllerName;
		$this->view->myActionName=$myActionName;
		$defineData=$db->runQuery("select * from ".ADMINISTRATOR." where admin_id='1'");

		if(isset($mySession->LoggedUserId) and @$mySession->LoggedUserId!="")
		{
			$userData=$db->runQuery("select * from ".USERS." where user_id='".$mySession->LoggedUserId."'");
			$this->view->userData=$userData;
		}

		if(!defined("ADMIN_EMAIL"))
		    define("ADMIN_EMAIL", array_key_exists('admin_email', $defineData[0]) ? $defineData[0]['admin_email'] : '');

		if(!defined("PAYPAL_TOKEN"))
		    define("PAYPAL_TOKEN", array_key_exists('paypal_token', $defineData[0]) ? $defineData[0]['paypal_token'] : '');

		if(!defined("PAYPAL_EMAIL"))
		    define("PAYPAL_EMAIL", array_key_exists('paypal_email', $defineData[0]) ? $defineData[0]['paypal_email'] : '');

		if(!defined("SITE_NAME"))
		    define("SITE_NAME", array_key_exists('site_title', $defineData[0]) ? $defineData[0]['site_title'] : 'TeeshirtSCRIPT');


		//define("PRICE_SYMBOL",$defineData[0]['currency_symbol']);

		$Explode=explode("/",$_SERVER['REQUEST_URI']);
		$SName=$Explode[count($Explode)-1];
		$ChkCampaign=$db->runQuery("select * from ".LAUNCHCAMPAIGN." where trim(url)='".urldecode($SName)."' ");



		if($ChkCampaign!="" and count($ChkCampaign)>0 && $myControllerName=="error" && $myActionName=="error")
		{
            // Routing did not came up with a page, no problem as the URL is related to a campaign URL
            $myform1=new Form_Buy();
			//$this->view->myform1=$myform1;
            $sizes = $db->runQuery("select * from " . TSHIRT_SIZE);
            $this->view->sizes = $sizes;
			$this->view->CampaignData=$ChkCampaign;
			include("application/layouts/scripts/campaign.phtml"); // Load Campaign view!
			exit();
		}

		if(isset($mySession->TeeLoggedID) and @$mySession->TeeLoggedID!="")
		{
			if($myControllerName=="signup" || $myControllerName=="login" || $myControllerName=="forgotpassword")
			{
				$this->_redirect(APPLICATION_URL.'myaccount/profile');
			}

		} else {
			if(($myControllerName=="myaccount") || ($myControllerName=="launchcampaign" && $myActionName=="edit"))        // when loged out and want to go on myacccount then redirect on index /index
            {
			    $this->_redirect(APPLICATION_URL.'index/index');
			}

		}

		if($myControllerName=="launchcampaign" )
		{
			if(stripslashes(array_key_exists('recreation_product', $_POST) ? $_POST['recreation_product'] : '')!='')
			{
                $mySession->recreation_product=stripslashes($_POST['recreation_product']);
			}

			if(stripslashes(array_key_exists('priceValueKM', $_POST) ? $_REQUEST['priceValueKM']!='' : '')
				&& stripslashes(array_key_exists('idValueKM', $_POST) ? $_REQUEST['idValueKM']!='' : ''))
			{
                $mySession->priceValueKM=$_REQUEST['priceValueKM'];
                $mySession->selectedIdValueKM=$_REQUEST['idValueKM'];
			}

		}
		$this->_helper->layout->setLayout('main');

	}

}

?>