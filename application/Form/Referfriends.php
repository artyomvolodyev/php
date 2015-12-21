<?php
class Form_Referfriends extends Zend_Form
{	

	public function __construct()
	{	
		$this->init();
	}
	public function init()
	{
		 
		global $mySession;
		$db=new Db();
		
		$emailid_val="";
		
		
	$qur=$db->runquery("SELECT * FROM  ".USERS." WHERE user_id='".$mySession->TeeLoggedID."' ");
	
	//$qur=$db->runquery("select * from ".ADDRESS." join ".STATE." on ".STATE.".state_id=".ADDRESS.".state where user_id='".$mySession->TeeLoggedID."' ");
	
	if($qur!="" and count($qur)>0)
	{
		$emailid_val=$qur[0]['emailid'];
		
		
		
	}
		
		# FORM ELEMENT:public name
		# TYPE : text
		
		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib('readonly','readonly')
		->setAttrib("style","width:300px; height:30px;")
		->setValue($emailid_val);
		
		
		# FORM ELEMENT:address 
		# TYPE : text
		
		$friendsemailid= new Zend_Form_Element_Text('friendsemailid');
		$friendsemailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'One id is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("style","width:300px; height:30px;")
		->setAttrib('class','changepasstextbox');
		
		//if(@$_REQUEST['friendsemailid']!="")
//		{
//		$friendsemailid-> addValidator('EmailAddress', true)
//		->addDecorator('Errors', array('class'=>'errmsg'))
//		->addErrorMessage('Please enter a valid email address');
//		}
//	
		# FORM ELEMENT:city 
		# TYPE : text
		
		$url= new Zend_Form_Element_Text('url');
		$url->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'URL is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:30px;");
		
		
		
		$this->addElements(array($emailid, $friendsemailid, $url));

		
	
	}
}