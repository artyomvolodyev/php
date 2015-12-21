<?php
class Form_Register extends Zend_Form
{	 
	public function __construct($val)
	{	
		$this->init($val);
	}
	public function init($val)
	{
		global $mySession;
		$db=new Db();
		
		
	
		# FORM ELEMENT:Email ID
		# TYPE : text
		
		$fname= new Zend_Form_Element_Text('fname');
		$fname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'First name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox');
		
		//if($val==1)
//		{
//			$fname->setValue($mySession->fname);
//		}
		
		# FORM ELEMENT:Email ID
		# TYPE : text
		
		$lname= new Zend_Form_Element_Text('lname');
		$lname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox');
		
		
			  $PaypalArr=array();
			  $PaypalArr[0]['key']="1";
			  $PaypalArr[0]['value']="PayPal";
			  $PaypalArr[1]['key']="2";
			  $PaypalArr[1]['value']="Credit Card";
  
  
	  $paypalchk= new Zend_Form_Element_Radio('paypalchk');
	  $paypalchk->addMultiOptions($PaypalArr)
	  ->addValidator('NotEmpty',true,array('messages' =>'Payment Mode is required.'))
	  ->addDecorator('Errors', array('class'=>'error'))
	  ->setRequired(true);
	  
	
		//$paypalchk= new Zend_Form_Element_Checkbox('paypalchk');
//		$paypalchk
//		->setRequired(true)
//		->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
//		->addDecorator('Errors', array('class'=>'errmsg'));
//		
//		
//		$creditchk= new Zend_Form_Element_Checkbox('creditchk');
//		$creditchk
//		->setRequired(true)
//		->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
//		->addDecorator('Errors', array('class'=>'errmsg'));
		
		
		# FORM ELEMENT:password 
		# TYPE : text
		
		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email Id is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox');
		
		if(@$_REQUEST['emailid']!="")
		{
		$emailid-> addValidator('EmailAddress', true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->addErrorMessage('Please enter a valid email address');
		}
		
		$this->addElements(array($fname,$lname,$emailid, $paypalchk));

		
	
	}
}