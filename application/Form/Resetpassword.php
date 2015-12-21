<?php

class Form_Resetpassword extends Zend_Form

{	 

	public function init()

	{

		global $mySession;

		$db=new Db();

		

		

		# FORM ELEMENT:Email ID

		# TYPE : text

		

		$newpassword= new Zend_Form_Element_Password('newpassword');

		$newpassword->setRequired(true)

		->addValidator('NotEmpty',true,array('messages' =>'password is required'))

		->addDecorator('Errors', array('class'=>'errmsg'))

		->setAttrib('class','logintextbox');
		
		
	if(@$_REQUEST['newpassword']!="")

		{

		$newpassword-> addValidator('StringLength',0,6, true)

		 ->addErrorMessage('Password must be atleast 6 characters');

		}
		

	

		

		# FORM ELEMENT:password 

		# TYPE : text
		$cnfrmpassword= new Zend_Form_Element_Password('cnfrmpassword');

		$cnfrmpassword->setRequired(true)

		->addValidator('NotEmpty',true,array('messages' =>'Conform Password is required'))

		->addDecorator('Errors', array('class'=>'errmsg'))

		//->setAttrib("onclick","changeborder(this.id);")

		->setAttrib('class','logintextbox');
		

		if(@$_REQUEST['cnfrmpassword']!="")

		{

		$cnfrmpassword-> addValidator('StringLength',0,6, true)

		 ->addErrorMessage('Password must be atleast 6 characters');

		}
		

		

		

		

		$this->addElements(array($newpassword, $cnfrmpassword));



		

	

	}

}