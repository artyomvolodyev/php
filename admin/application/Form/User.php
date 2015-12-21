<?PHP
class Form_User extends Zend_Form
{  
	public function __construct($userId)
	{
		global $mySession;
		$this->init($userId);
	}
    public function init($userId)
	{
		global $mySession;
        $db=new Db();		
				if($userId!="")
		{
		$adminData=$db->runQuery("select * from ".USERS." where user_id='".$userId."'");
		
		$first_name_value=$adminData[0]['first_name'];
		$last_name_value=$adminData[0]['last_name'];
		$email_address_value=$adminData[0]['email_address'];
		$old_profile_image_value=$adminData[0]['image'];
		$gender_value=$adminData[0]['gender'];
		//$dob_value=$adminData[0]['dob'];
		$dob_value=changeDate($adminData[0]['dob'],1);
		$location_value=$adminData[0]['location'];
		$country_value=$adminData[0]['country'];
		$blog_url_value=$adminData[0]['blog_url'];
		$website_url_value=$adminData[0]['website_url'];
		$about_me_value=$adminData[0]['about_me'];
		$occupation_value=$adminData[0]['occupation'];
		//$password_value=md5($adminData[0]['password']);
		}
		
		$first_name= new Zend_Form_Element_Text('first_name');
		$first_name->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'First Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setValue($first_name_value);	
		$this->addElement($first_name);
		
		$last_name= new Zend_Form_Element_Text('last_name');
		$last_name->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Last Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setAttrib("onkeypress","return checknummsp1(event);")		
		->setValue($last_name_value);
		$this->addElement($last_name);
		
		$email_address= new Zend_Form_Element_Text('email_address');
		$email_address->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email address is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")	
		->setValue($email_address_value);
		$this->addElement($email_address);
		
		
		if(@$_REQUEST['email_address']!="")
		{
		$email_address-> addValidator('EmailAddress', true)
		->addErrorMessage('Please enter a valid email address');
		}
		
		
		$old_profile_image= new Zend_Form_Element_Hidden('old_profile_image');
		$old_profile_image->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setValue($old_profile_image_value);
		$this->addElement($old_profile_image);
		
		$user_pic= new Zend_Form_Element_File('user_pic');
		$user_pic->setDestination(SITE_ROOT.'images/profileimgs/');
		if($userId=="")
		{
		$user_pic->setRequired(true)		
		->addValidator('NotEmpty',true,array('messages' =>'Please select a photo.'));
		}
		
		$user_pic->addValidator('Extension', false, 'jpg,jpeg,png,gif')
		->setAttrib("class","textbox")
		->addDecorator('Errors', array('class'=>'error'))
		->setValue($image_value);
		$this->addElement($user_pic);
		
		$GenderArr=array();
		$GenderArr[0]['key']="1";
		$GenderArr[0]['value']="Male";
		$GenderArr[1]['key']="2";
		$GenderArr[1]['value']="Female";
		$gender= new Zend_Form_Element_Radio('gender');
		$gender->addMultiOptions($GenderArr)
		->setValue($gender_value);
		$this->addElement($gender);
		
		
		$countryArr=array();
		$sql="select * from ".COUNTRIES;
		
		$countryData=$db->runQuery($sql);
		$countryArr[0]['key']=0;
		$countryArr[0]['value']="---Select Country---";
		$i=1;
		foreach($countryData as $key=>$data)
		{
		 	$countryArr[$i]['key']=$data['country_id'];
			$countryArr[$i]['value']=$data['country_name'];
			$i++;
		}		
		$country= new Zend_Form_Element_Select('country');
		$country->setRequired(true)
		->addMultiOptions($countryArr)
		->addValidator('NotEmpty',true,array('messages' =>'Country is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setValue($country_value);	
		$this->addElement($country);
		
		$password= new Zend_Form_Element_Text('password');
		$password->setAttrib("class","textInput")	
		/*$password->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required.'))
		->addDecorator('Errors', array('class'=>'error'))*/
		->setValue($password_value);	
		$this->addElement($password);
		
		
		$cpassword= new Zend_Form_Element_Password('cpassword');
		$cpassword->setAttrib("class","textInput")
		/*$cpassword->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required.'))
		->addDecorator('Errors', array('class'=>'error'))*/
		->setValue($password_value);	
		$this->addElement($cpassword);
		
		$npassword= new Zend_Form_Element_Password('npassword');
		$npassword->setAttrib("class","textInput")
		/*$npassword->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required.'))
		->addDecorator('Errors', array('class'=>'error'))*/
		->setValue($password_value);	
		$this->addElement($npassword);
		
		if((isset($_REQUEST['changePass']) && $userId!=""))
		{
		$npassword->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Password is required.'))
		->addDecorator('Errors', array('class'=>'error'));
		
		$cpassword->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Confirm password is required.'))
		->addDecorator('Errors', array('class'=>'error'));
		}
		
		$GenderArr=array();
		  $GenderArr[0]['key']="1";
		  $GenderArr[0]['value']="Male";
		  $GenderArr[1]['key']="2";
		  $GenderArr[1]['value']="Female";
		  $gender= new Zend_Form_Element_Radio('gender');
		  $gender->addMultiOptions($GenderArr)
		  ->setRequired(true)
		  ->addValidator('NotEmpty',true,array('messages' =>'Field is required.'))
	      ->addDecorator('Errors', array('class'=>'error'))
	      ->setAttrib("class","textInput")
		  ->setValue($gender_value);
		  $this->addElement($gender);
		
		$location= new Zend_Form_Element_Text('location');
		$location->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Location is required.'))
	    ->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")		
		->setValue($location_value);
		$this->addElement($location);
		
		
		$occupation= new Zend_Form_Element_Text('occupation');
		$occupation->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Occupation is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setValue($occupation_value);
		$this->addElement($occupation);
		
		$about_me= new Zend_Form_Element_Textarea('about_me');
		//$about_me->setRequired(true)
		//->addValidator('NotEmpty',true,array('messages' =>'Please enter this field.'))
		$about_me->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:150px; height:60px;")
		->setValue($about_me_value);
		$this->addElement($about_me);
		
		$blog_url= new Zend_Form_Element_Text('blog_url');
		//$blog_url->setRequired(true)
		//->addValidator('NotEmpty',true,array('messages' =>'Blog URL is required.'))
		$blog_url->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->addValidator('Hostname',true)
		->setAttrib("Placeholder","http://")
		->setValue("$website_url_value");
		$this->addElement($blog_url);
		
		$website_url= new Zend_Form_Element_Text('website_url');
		//$website_url->setRequired(true)
		//->addValidator('NotEmpty',true,array('messages' =>'Website URL is required.'))
		$website_url->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->addValidator('Hostname',true)
		->setAttrib("Placeholder","http://")
		->setValue("$website_url_value");
		$this->addElement($website_url);

		



	}
}	

?>