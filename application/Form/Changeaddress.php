<?php
class Form_Changeaddress extends Zend_Form
{	

	public function __construct()
	{	
		$this->init();
	}
	public function init()
	{
		 
		global $mySession;
		$db=new Db();
		
		$public_name="";
		
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
		
		# FORM ELEMENT:public name
		# TYPE : text
		
		$publicname= new Zend_Form_Element_Text('publicname');
		$publicname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Public name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:200px; height:30px;")	
		//->setAttrib('onfocus','checking')
		->setValue($public_name);
		//->setvalue
		
		
		# FORM ELEMENT:address 
		# TYPE : text
		
		$address= new Zend_Form_Element_Text('address');
		$address->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Address is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox')
		->setValue($val1);
		
		
		$address1= new Zend_Form_Element_Text('address1');
		//$address1->setRequired(true)
		$address1->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changepasstextbox')
		->setValue($val2);
		
		
		# FORM ELEMENT:city 
		# TYPE : text
		
		$city= new Zend_Form_Element_Text('city');
		$city->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'City is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:30px;")	
		->setValue($city_val);
		
		if(@$_REQUEST['city']!="")
		{
		$city-> addValidator('Alpha', true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->addErrorMessage('Enter only characters');
		}
		
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
				//if($state_val==$StateArr[$i]['key'])
//			
//				{
//				  $state_val=$StateArr[$i]['value'];
//     			}
				  $i++;
				}
			}
			
		$state= new Zend_Form_Element_Select('state');
		$state->setRequired(true)
		->addMultiOptions($StateArr)
		->addValidator('NotEmpty',true,array('messages' =>'State is required.'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:33px;")	
		->setAttrib("onchange","getneighborhoodcities(this.value);")	
		->setValue($state_val);
		
		
		# FORM ELEMENT:zipcode 
		# TYPE : text
		
		$zipcode= new Zend_Form_Element_Text('zipcode');
		$zipcode->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'zip code is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
	    ->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:30px;")	
		->setValue($zip_val);
		
		if(@$_REQUEST['zipcode']!="")
		{
		$zipcode-> addValidator('Digits', true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->addErrorMessage('Please enter only numbers');
		}
		
		$this->addElements(array($publicname, $address, $address1, $city, $state, $zipcode));

		
	
	}
}