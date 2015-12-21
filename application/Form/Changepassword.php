<?php
class Form_Changepassword extends Zend_Form
{	 
	public function __construct()
	{	
		
		$this->init();
	}
	public function init()
	{
		global $mySession;
		$db=new Db();
		
		
		# FORM ELEMENT:Email ID
		# TYPE : text
		
		$currentpass= new Zend_Form_Element_Password('currentpass');
		$currentpass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Current Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox')
		;
		
		
		
		# FORM ELEMENT:password 
		# TYPE : text
		
		$newpass= new Zend_Form_Element_Password('newpass');
		$newpass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'New Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox')
		;
		if(@$_REQUEST['newpass']!="")
		{
		$newpass->addValidator('StringLength',0,6, true)
		        ->addErrorMessage('Password must be atleast 6 characters');
		} 
		
		
		
		# FORM ELEMENT: conform password 
		# TYPE : text
		
		
		$cnfrmnewpass= new Zend_Form_Element_Password('cnfrmnewpass');
		$cnfrmnewpass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Confirm New Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox')
		;
		if(@$_REQUEST['cnfrmnewpass']!="")
		{
		$cnfrmnewpass->addValidator('StringLength',0,6, true)
		             ->addErrorMessage('Password must be atleast 6 characters');
		} 
		
		
		$this->addElements(array($currentpass, $newpass, $cnfrmnewpass));

		
	
	}
}