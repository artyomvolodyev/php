<?php
class Form_Signupdesigned extends Zend_Form
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
		
		$signupemailid= new Zend_Form_Element_Text('signupemailid');
		$signupemailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email Id is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','logintextbox')
		->setAttrib("onchange","validval(this.value);")
		
		
		//->setAttrib("onclick","changeborder(this.id);")
		;
		
		
		# FORM ELEMENT:password 
		# TYPE : text
		
		$signuppass= new Zend_Form_Element_Password('signuppass');
		$signuppass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		//->setAttrib("onclick","changeborder(this.id);")
		->setAttrib('class','logintextbox');
		
		
		
		# FORM ELEMENT: conform password 
		# TYPE : text
		
		
		$signupcnfrmpass= new Zend_Form_Element_Password('signupcnfrmpass');
		$signupcnfrmpass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Confirm Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		//->setAttrib("onclick","changeborder(this.id);")
		->setAttrib('class','logintextbox');
		
		$this->addElements(array($signupemailid, $signuppass, $signupcnfrmpass));

		
	
	}
}