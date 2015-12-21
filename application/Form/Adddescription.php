<?php
class Form_Adddescription extends Zend_Form
{	 
	public function __construct($cid="",$cidresume="")
	{	
	 
		$this->initMyForm($cid,$cidresume);
	}
	
	public function initMyForm($cid,$cidresume)
	{	
	
		global $mySession;
		$db=new Db();
		
		$title_val = "";
		$description_val = "";
		$url_val = "";
		$camplength_val="";
		$campaign_category_val='';
		
		if($mySession->TeeLoggedID!="")
		{
		if($mySession->camptitl!="" && $mySession->showurl!="")
//if($mySession->camptitl!="" && $mySession->setgoalvalues!="" && $mySession->descrip!="" && $mySession->showurl!="" && $mySession->campaign_category!="")
		
		//$mySession->camplength
		{
			//echo "in form with session values"; die;
			$title_val =$mySession->camptitl;
			$description_val =$mySession->descrip;
			$url_val=$mySession->showurl;
			$camplength_val=$mySession->camplength;
		  $campaign_category_val=$mySession->campaign_category;
			
		}
		}
		if($cid!="")
		{
			
			$qur=$db->runquery("SELECT * FROM  ".LAUNCHCAMPAIGN." WHERE campaign_id='".$cid."'");
			
			if($qur!="" and count($qur)>0)
			{
				//prd($qur);
				$title_val = $qur[0]['title'];
				$description_val = $qur[0]['description'];
				$url_val = $qur[0]['url'];
				$camplength_val=$qur[0]['campaign_length'];
				$campaign_category_val=$qur[0]['campaign_category'];
			}
		}
		
		# FORM ELEMENT:camptitle
		# TYPE : text
		
		$camptitle= new Zend_Form_Element_Text('camptitle');
		$camptitle->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','setgoaltxt')
		->setAttrib("style","width:450px; height:30px;")
		->setvalue($title_val);
		
			
		# FORM ELEMENT:description
		# TYPE : text
		
		$description= new Zend_Form_Element_Textarea('description');
		$description->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','setgoaltxt')
		// ->setAttrib("id","elrte")
		->setAttrib("style","width:450px; height:120px;")
		->setvalue($description_val);
		
		
		$daysArr=array();
		$daysArr[1]['key']="3";
		$daysArr[1]['value']="3 Days";
		$daysArr[2]['key']="7";
		$daysArr[2]['value']="7 Days";
		$daysArr[3]['key']="10";
		$daysArr[3]['value']="10 Days";
		$daysArr[4]['key']="14";
		$daysArr[4]['value']="14 Days";
		$daysArr[5]['key']="21";
		$daysArr[5]['value']="21 Days";
		
		$no_ofdays= new Zend_Form_Element_Select('no_ofdays');
		$no_ofdays->addMultiOptions($daysArr)
		->setAttrib('class','setgoaltxt')
		->setAttrib("style","width:150px; height:30px;")
		->setvalue($camplength_val);
		
		
		# FORM ELEMENT:url
		# TYPE : text
		
		if($cid!="" || $cid!=0)
		{
			$url= new Zend_Form_Element_Text('url');
			$url->setRequired(true)
			->setAttrib('class','setgoaltxt')
			->setAttrib("readonly","readonly")
			->setAttrib("style","width:100px; height:30px;")
			->setvalue($url_val);
		}
		else
		//if($lid!="")
		{
			/*$url= new Zend_Form_Element_Text('url');
			$url->setRequired(true)
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','setgoaltxt')
			->setAttrib("onkeypress","return checkspecchar(event)")
			->setAttrib('onkeyup','uniqueurl(this.value)')
			->setAttrib("style","width:100px; height:30px;")
			->setvalue($url_val);*/

			$url= new Zend_Form_Element_Text('url');
			$url->setRequired(true)
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','setgoaltxt')
			->setAttrib('onblur','uniqueurl(this.value)')
			->setAttrib("style","width:100px; height:30px;")
			->setvalue($url_val);

				
		}
		
	
		
		# FORM ELEMENT:check
		# TYPE : text
		
		$add_checkbox= new Zend_Form_Element_Checkbox('add_checkbox');
		$add_checkbox
		->setAttrib('onclick','opendiv(this.id);');
		
		
		$newcheckbx= new Zend_Form_Element_Checkbox('newcheckbx');
		$newcheckbx
		->setAttrib('onclick','newdivaddr(this.id);');
		
		
		
		   
		    $firstname= new Zend_Form_Element_Text('firstname');
			$firstname
			->addValidator('NotEmpty',true,array('messages' =>'First name is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress');
			
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$firstname->setRequired(true);
			}
			$firstname->setAttrib("style","width:180px; height:30px;");
			
			$this->addElement($firstname);
			
			
			
			
			# FORM ELEMENT:last name
			# TYPE : text
			
			$lastname= new Zend_Form_Element_Text('lastname');
			$lastname
			->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress');
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$lastname->setRequired(true);
			}
			$lastname->setAttrib("style","width:180px; height:30px;");
			
			//if(@$_REQUEST['lastname']!="")
//			{
//			$lastname-> addValidator('Alpha', true)
//			->addDecorator('Errors', array('class'=>'errmsg'))
//			->addErrorMessage('Enter only characters');
//			}
			
			$this->addElement($lastname);
			
			
			
			
			
		    # FORM ELEMENT:address
			# TYPE : text
			
			
			$newaddress= new Zend_Form_Element_Text('newaddress');
			$newaddress
			->addValidator('NotEmpty',true,array('messages' =>'Address is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changepasstextbox');
		
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$newaddress->setRequired(true);
			}
			$this->addElement($newaddress);
			
			# FORM ELEMENT:city 
			# TYPE : text
			
			$newcity= new Zend_Form_Element_Text('newcity');
			$newcity
			->addValidator('NotEmpty',true,array('messages' =>'City is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress');

			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$newcity->setRequired(true);
			}
						
			$newcity->setAttrib("style","width:100px; height:30px;");
			
			//if(@$_REQUEST['newcity']!="")
//			{
//			$newcity-> addValidator('Alpha', true)
//			->addDecorator('Errors', array('class'=>'errmsg'))
//			->addErrorMessage('Enter only characters');
//			}
			
			$this->addElement($newcity);
		
			
				
			# FORM ELEMENT:state 
			# TYPE : text
			
			
				
			$StateArr=array();
			$StateArr[0]['key']="";
			$StateArr[0]['value']="- - Select  State- -";
		
				$StateData=$db->runQuery("select * from ".STATE." order by state_name");
				if($StateData!="" and count($StateData)>0)
				{
					$i=1;
					foreach($StateData as $key=>$StateValues)
					{
					$StateArr[$i]['key']=$StateValues['state_id'];
					$StateArr[$i]['value']=$StateValues['state_name'];
					  $i++;
					}
				}
				
			$newstate= new Zend_Form_Element_Select('newstate');
			$newstate
			->addMultiOptions($StateArr)
			->addValidator('NotEmpty',true,array('messages' =>'State is required.'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changeaddress');
			
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$newstate->setRequired(true);
			}
			
			$newstate->setAttrib("style","width:100px; height:33px;")	
			->setAttrib("onchange","getneighborhoodcities(this.value);");
			$this->addElement($newstate);
						
			# FORM ELEMENT:zipcode 
			# TYPE : text
			
			$newzipcode= new Zend_Form_Element_Text('newzipcode');
			$newzipcode
			->addValidator('NotEmpty',true,array('messages' =>'zip code is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changeaddress')
		    ->setAttrib("onkeypress","return checknummsp(event);");
				
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$newzipcode->setRequired(true);
			}
			
			$newzipcode->setAttrib("style","width:100px; height:30px;");
			
			//if(@$_REQUEST['newzipcode']!="")
//			{
//			$newzipcode-> addValidator('Digits', true)
//			->addDecorator('Errors', array('class'=>'errmsg'))
//			->addErrorMessage('Please enter only numbers');
//			}
//			
			$this->addElement($newzipcode);
			
			
			
			# FORM ELEMENT:instruction 
			# TYPE : text
			
			
			$instruction= new Zend_Form_Element_Textarea('instruction');
			$instruction
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','setgoaltxt');
			
			if(array_key_exists('newcheckbx', $_REQUEST) && $_REQUEST['newcheckbx']==1){
				$instruction->setRequired(true);
			}
			
			$instruction->setAttrib("style","width:450px; height:120px;");
			$this->addElement($instruction);
			$address_val="";
			$val1="";
			$val2="";
			$city_val="";
			$state_val="";
			$zip_val="";
				
			$qur=$db->runquery("SELECT * FROM  ".ADDRESS." WHERE user_id='".$mySession->TeeLoggedID."' ");
			
			//$qur=$db->runquery("select * from ".ADDRESS." join ".STATE." on ".STATE.".state_id=".ADDRESS.".state where user_id='".$mySession->TeeLoggedID."' ");
			
			if($qur!="" and count($qur)>0)
			{
				$public_name=$qur[0]['public_name'];			
				$address_val=explode("||",$qur[0]['address']);
				//prd($address_val);
				//prd($address_val);
				$val1=$address_val[0];
				$val2=$address_val[1];
				$city_val=$qur[0]['city'];
				$state_val=$qur[0]['state'];			
				$zip_val=$qur[0]['zipcode'];
			}
		
		
			# FORM ELEMENT:first name
			# TYPE : text
			
		//	$firstname= new Zend_Form_Element_Text('firstname');
//			$firstname
//			->addValidator('NotEmpty',true,array('messages' =>'First name is required'))
//			->addDecorator('Errors', array('class'=>'errmsg'))
//			->setAttrib('class','changeaddress');
//			
//			if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
//			$firstname->setAttrib("style","width:180px; height:30px;");
//			$this->addElement($firstname);
//			
//			
//			# FORM ELEMENT:last name
//			# TYPE : text
//			
//			$lastname= new Zend_Form_Element_Text('lastname');
//			$lastname
//			->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
//			->addDecorator('Errors', array('class'=>'errmsg'))
//			->setAttrib('class','changeaddress');
//			if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
//			$lastname->setAttrib("style","width:180px; height:30px;");
//			$this->addElement($lastname);
//			
			
			# FORM ELEMENT:address 
			# TYPE : text
			
			$address= new Zend_Form_Element_Text('address');
			$address
			->addValidator('NotEmpty',true,array('messages' =>'Address is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changepasstextbox')
			->setAttrib("readonly","readonly")
			->setValue($val1);
		//	if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
			$this->addElement($address);
			
			
			$address1= new Zend_Form_Element_Text('address1');
			$address1->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changepasstextbox')
			->setAttrib("readonly","readonly")
			->setValue($val2);
			$this->addElement($address1);
			
			
			# FORM ELEMENT:city 
			# TYPE : text
			
			$city= new Zend_Form_Element_Text('city');
			$city
			->addValidator('NotEmpty',true,array('messages' =>'City is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changeaddress')
			->setAttrib("readonly","readonly")
			->setValue($city_val);
			//if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
			$city->setAttrib("style","width:100px; height:30px;");
			$this->addElement($city);
			
			
			
			# FORM ELEMENT:state 
			# TYPE : text
			
			
				
			$StateArr=array();
			$StateArr[0]['key']="";
			$StateArr[0]['value']="- - Select  State- -";
		
				$StateData=$db->runQuery("select * from ".STATE." order by state_name");
				if($StateData!="" and count($StateData)>0)
				{
					$i=1;
					foreach($StateData as $key=>$StateValues)
					{
					$StateArr[$i]['key']=$StateValues['state_id'];
					$StateArr[$i]['value']=$StateValues['state_name'];
					  $i++;
					}
				}
				
				
			$state= new Zend_Form_Element_Select('state');
			$state	->addMultiOptions($StateArr)
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changeaddress')
			->setValue($state_val);
			//if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
			$state->setAttrib("style","width:100px; height:33px;")	
			->setAttrib("onchange","getneighborhoodcities(this.value);");
			$this->addElement($state);
			
			
			# FORM ELEMENT:zipcode 
			# TYPE : text
			
			$zipcode= new Zend_Form_Element_Text('zipcode');
			$zipcode
			->addValidator('NotEmpty',true,array('messages' =>'zip code is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changeaddress')
			->setAttrib("readonly","readonly")
			->setValue($zip_val);
			//if($_REQUEST['add_checkbox']==1)
//			{
//			$instruction->setRequired(true);
//			}
			$zipcode->setAttrib("style","width:100px; height:30px;");
			
			$this->addElement($zipcode);
		
		 // Campaign category
		 
		 	$CampaignCaArr=array();
		$CampaignCaArr[1]['key']="1";
		$CampaignCaArr[1]['value']="Discover";
		$CampaignCaArr[2]['key']="2";
		$CampaignCaArr[2]['value']="Support";
		
		$campaign_category= new Zend_Form_Element_Select('campaign_category');
		$campaign_category->addMultiOptions($CampaignCaArr)
		->setAttrib('class','setgoaltxt')
		->setAttrib("style","width:150px; height:30px;")
		->setvalue($campaign_category_val);
       
		$this->addElements(array($camptitle , $description, $no_ofdays, $url, $newcheckbx, $add_checkbox,$campaign_category));
	}
}