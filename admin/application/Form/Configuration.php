<?PHP
class Form_Configuration extends Zend_Form
{  
    public function init()
	{   
		global $mySession;
        $db=new Db();
		
		$ConfigData=$db->runQuery("select * from ".ADMINISTRATOR." where admin_id='1'");	
		
		$SiteTitle=$ConfigData[0]['site_title'];
		$MetaDescription=$ConfigData[0]['site_description'];
		$MetaKeyword=$ConfigData[0]['site_keyword'];
		$AdminEmail=$ConfigData[0]['admin_email'];
		$PaypalEmail=$ConfigData[0]['paypal_email'];
		$PaypalToken=$ConfigData[0]['paypal_token'];
		
		
		
		$site_title= new Zend_Form_Element_Text('site_title');
		$site_title->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Site title is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($SiteTitle);
		
		$site_description= new Zend_Form_Element_Text('site_description');
		$site_description->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Site description is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($MetaDescription);
		
		$site_keyword= new Zend_Form_Element_Text('site_keyword');
		$site_keyword->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Site keyword is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($MetaKeyword);
		
		$admin_email= new Zend_Form_Element_Text('admin_email');
		$admin_email->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Administrator email is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($AdminEmail);

        if(TEESHIRTSCRIPT_ON_DEMO){
            $admin_email->setAttrib("disabled","disabled");
        }

        /*
		$paypal_email= new Zend_Form_Element_Text('paypal_email');
		$paypal_email->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Paypal email address is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($PaypalEmail);
		
		$identity_token= new Zend_Form_Element_Text('identity_token');
		$identity_token->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Paypal email address is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($PaypalToken);*/

		
		$this->addElements(array($site_title,$site_description,$site_keyword,$admin_email/*,$paypal_email,$identity_token*/));
		
	}
}	

?>