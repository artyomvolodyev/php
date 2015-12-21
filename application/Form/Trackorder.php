<?php
class Form_Trackorder extends Zend_Form
{	

	public function __construct()
	{	
		$this->init();
	}
	public function init()
	{
		
		global $mySession;
		$db=new Db();
	
		# FORM ELEMENT:public name
		# TYPE : text
		
		$orderno= new Zend_Form_Element_Text('orderno');
		$orderno->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' => 'Order No. is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:200px; height:30px;");	
		//->setAttrib('onfocus','checking')
		
		
		$this->addElement($orderno);

		
	
	}
}