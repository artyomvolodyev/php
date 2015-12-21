<?php
class Form_Signup extends Zend_Form
{	 
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		global $mySession;
		$db=new Db();

        # FORM ELEMENT:Public Name
        # TYPE : text
        $publicname= new Zend_Form_Element_Text('publicname');
        $publicname->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Public name is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib('class','logintextbox');
		
		
		# FORM ELEMENT:Email ID
		# TYPE : text
		$signupemailid= new Zend_Form_Element_Text('signupemailid');
		$signupemailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email Id is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','logintextbox');
		//->setAttrib("onchange","validval(this.value);")
		
		
		//->setAttrib("onclick","changeborder(this.id);")
	   
	   if(@$_REQUEST['signupemailid']!="")
		{
            $signupemailid-> addValidator('EmailAddress', true)
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->addErrorMessage('Please enter a valid email address');
		}
		
		
		# FORM ELEMENT:password 
		# TYPE : text
		$signuppass= new Zend_Form_Element_Password('signuppass');
		$signuppass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		//->setAttrib("onclick","changeborder(this.id);")
		->setAttrib('class','logintextbox');
		
		if(@$_REQUEST['signuppass']!="")
		{
            $signuppass-> addValidator('StringLength',0,6, true)
             ->addErrorMessage('Password must be atleast 6 characters');
		}
		
		
		
		# FORM ELEMENT: confirm password
		# TYPE : text
		$signupcnfrmpass= new Zend_Form_Element_Password('signupcnfrmpass');
		$signupcnfrmpass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Confirm Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		//->setAttrib("onclick","changeborder(this.id);")
		->setAttrib('class','logintextbox');
		
		if(@$_REQUEST['signupcnfrmpass']!="")
		{
              $signupcnfrmpass-> addValidator('StringLength',0,6, true)
             ->addErrorMessage('Password must be atleast 6 characters');
		}
		
		
		$this->addElements(array($publicname, $signupemailid, $signuppass, $signupcnfrmpass));

		
	
	}
}