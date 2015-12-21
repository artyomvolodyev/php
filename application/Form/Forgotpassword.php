<?php

class Form_Forgotpassword extends Zend_Form

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
		

		if(@$_REQUEST['emailid']!="")
		{
			$emailid-> addValidator('EmailAddress', true)
			->addDecorator('Errors', array('class'=>'errmsg'))
			->addErrorMessage('Please enter a valid email address');
		}
		

	    $this->addElements(array($emailid));

		

	

	}

}