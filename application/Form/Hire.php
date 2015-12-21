<?php

class Form_Hire extends Zend_Form

{	



	public function __construct()

	{	

		$this->init();

	}

	public function init()

	{

		 

		global $mySession;

		$db=new Db();



		$Message= new Zend_Form_Element_Textarea('Message');

		$Message->setRequired(true)

		->addValidator('NotEmpty',true,array('messages' =>'Message is required.'))

		->addDecorator('Errors', array('class'=>'errmsg'))

		->setAttrib("class","changepasstextarea")

		->setAttrib("style","height:106px;width:733px;")

		->setAttrib("placeholder","Message")

		->setValue($Message_value);

		$this->addElement($Message);

		

		

		



		

	

	}

}