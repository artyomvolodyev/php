<?php
class Form_Emailfriends extends Zend_Form
{
	public function __construct($name="")
	{
		$this->init($name);

	}
	public function init($name="")
	{

		global $mySession;
		$db=new Db();

		//echo "url ....  : ".$name; die;

		$emailid_val="";
		$emailfrnz_val="";

	$quremailid=$db->runquery("SELECT emailid FROM  ".ORDER_RECORD." WHERE teeurl='".$name."'");
	//prd($quremailid);
	if($quremailid!="" and count($quremailid)>0)
	{
		for($i=0; $i<count($quremailid); $i++)
		{
			if($i==0)
			{
				$emailfrnz[$i]=$quremailid[$i]['emailid'];
			}
			else
			{
			if(!in_array($quremailid[$i]['emailid'],$emailfrnz))
				{
				$emailfrnz[$i]=$quremailid[$i]['emailid'];
				}

			}
		}
//		prd($emailfrnz);
		$emailfrnz_val = implode(",",$emailfrnz);
	}


	$qur1=$db->runquery("SELECT * FROM  ".LAUNCHCAMPAIGN." WHERE url='".$name."' ");


	$qur=$db->runquery("SELECT * FROM  ".USERS." WHERE user_id='".$qur1[0]['user_id']."'");

	//$qur=$db->runquery("select * from ".ADDRESS." join ".STATE." on ".STATE.".state_id=".ADDRESS.".state where user_id='".$mySession->TeeLoggedID."' ");

	if($qur!="" and count($qur)>0)
	{
		$emailid_val=$qur[0]['emailid'];
	}

		# FORM ELEMENT:public name
		# TYPE : text

		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib('class','changeaddress')
		//->setAttrib('readonly','readonly')
		->setAttrib("style","width:300px; height:30px;")
		->setValue($emailid_val);

		if(@$_REQUEST['emailid']!="")
		{
		$emailid-> addValidator('EmailAddress', true)
		->addDecorator('Errors', array('class'=>'error'))
		->addErrorMessage('Please enter a valid email address');
		}



		# FORM ELEMENT:address
		# TYPE : text

		//$friendsemailid= new Zend_Form_Element_Text('friendsemailid');
//		$friendsemailid->setRequired(true)
//		->addValidator('NotEmpty',true,array('messages' =>'One id is required'))
//		->addDecorator('Errors', array('class'=>'errmsg'))
//		->setAttrib("style","width:300px; height:30px;")
//		->setAttrib('class','changepasstextbox')
//		->setValue($emailfrnz_val);


		$friendsemailid= new Zend_Form_Element_Hidden('friendsemailid');
		$friendsemailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'One id is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:300px; height:30px;")
		->setAttrib('class','changepasstextbox')
		->setValue($emailfrnz_val);


		# FORM ELEMENT:city
		# TYPE : text

		$subject= new Zend_Form_Element_Text('subject');
		$subject->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Subject is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:300px; height:30px;");


		$content= new Zend_Form_Element_Textarea('content');
		$content->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Content is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:300px; height:150px;");



		$this->addElements(array($emailid, $friendsemailid, $subject, $content));



	}
}