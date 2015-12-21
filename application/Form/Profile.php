<?php

class Form_Profile extends Zend_Form
{

	public function __construct()
	{
		$this->myInit();
	}

	public function myInit()
	{
		global $mySession;
		$db=new Db();

		$public_name="";
		$emailid_val="";
		$bio_value='';
		$old_profile_image_value='';

		$qur = $db->runquery("SELECT * FROM  ".USERS." WHERE user_id='".$mySession->TeeLoggedID."' ");

		if($qur!="" and count($qur)>0)
        {
            $public_name=$qur[0]['public_name'];
            $emailid_val=$qur[0]['emailid'];
            $bio_value=$qur[0]['bio'];
            $old_profile_image_value=$qur[0]['profile_image'];
        }

		$publicname= new Zend_Form_Element_Text('publicname');
		$publicname->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Public name is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib('class','changepasstextbox')
            ->setValue($public_name);

		$bio= new Zend_Form_Element_Textarea('bio');
		$bio->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Bio is required.'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("class","changepasstextarea")
            ->setAttrib("style","height:150px;width:354px;")
            ->setAttrib("maxlength","300")
            ->setAttrib("placeholder","Max 300 Characters")
            ->setValue($bio_value);

        $profile_image= new Zend_Form_Element_File('profile_image');
        $profile_image->setDestination(SITE_ROOT.'images/profileimages/')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("class","textInput")
            ->setAttrib("style","width:325px");

        $old_profile_image= new Zend_Form_Element_Hidden('old_profile_image');
        $old_profile_image->setValue($old_profile_image_value);

		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Email Id is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib('class','changepasstextbox')
            ->setValue($emailid_val);

		$this->addElements(array($publicname, $emailid, $bio, $profile_image, $old_profile_image));
	}

}