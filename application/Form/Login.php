<?php
class Form_Login extends Zend_Form
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
		
		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email Id is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','logintextbox');
		//->setAttrib("onchange","validval(this.value);");
		
		
		
		# FORM ELEMENT:password 
		# TYPE : text
		
		$pass= new Zend_Form_Element_Password('pass');
		$pass->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		//->setAttrib("onclick","changeborder(this.id);")
		->setAttrib('class','logintextbox');
		
		
		
	    $this->addElements(array($emailid, $pass));
		
	
	}
}