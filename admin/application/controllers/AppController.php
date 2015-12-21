<?php 
class AppController extends Zend_Controller_Action 
{
	
	public function init()
    {
		global $mySession;
		$db=new Db();
		
		$myControllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();		
		$myActionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		$defineData=$db->runQuery("select * from ".ADMINISTRATOR." where admin_id='1'");	
				
		define("ADMIN_EMAIL", array_key_exists('admin_email', $defineData[0]) ? $defineData[0]['admin_email'] : '');
		define("SITE_NAME", array_key_exists('site_title', $defineData[0]) ? $defineData[0]['site_title'] : 'TeeshirtSCRIPT');
		define("SITE_KEYWORD", array_key_exists('site_keyword', $defineData[0]) ? $defineData[0]['site_keyword'] : '');
		define("PRICE_SYMBOL", array_key_exists('currency_symbol', $defineData[0]) ? $defineData[0]['currency_symbol'] : '$');
		
		define("DEFAULT_BUSINESS_IMAGE", array_key_exists('default_business_image', $defineData[0]) ? $defineData[0]['default_business_image'] : 'my_default_business_image.png');
		define("DEFAULT_EVENT_IMAGE", array_key_exists('default_event_image', $defineData[0]) ? $defineData[0]['default_event_image'] : 'my_default_event_image.png');
		define("DEFAULT_MALE_USER_IMAGE", array_key_exists('default_male_image', $defineData[0]) ? $defineData[0]['default_male_image'] : 'my_default_male_image.png');
		define("DEFAULT_FEMALE_USER_IMAGE", array_key_exists('default_female_image', $defineData[0]) ? $defineData[0]['default_female_image'] : 'my_default_female_image.png');
		define("DEFAULT_USER_IMAGE", array_key_exists('default_both_image', $defineData[0]) ? $defineData[0]['default_both_image'] : 'my_default_both_image.png');
				
		if($mySession->adminId == "" && $myControllerName != 'index')
		{				
			$mySession->errorMsg ="Please login now to access administrator control panel.";
			$this->_redirect('index');
		}		
		if($mySession->adminId != "" && ($myControllerName == 'index' && $myActionName == 'index'))
		{	
			$this->_redirect('dashboard');
		}
		
		$this->_helper->layout->setLayout('main');
    }
}
?>