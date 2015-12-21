<?php
class Form_Indexpayement extends Zend_Form
{
	public function __construct()
	{
		$this->init();
	}
	public function init()
	{
		
		# FORM ELEMENT:FName 
		# TYPE : text
		
		$fname= new Zend_Form_Element_Text('fname');
		$fname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'First Name is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
		->setAttrib("onkeypress","return checkcharonly(event);")
		->setAttrib('class','changeaddress');
		
		
		# FORM ELEMENT:LName 
		# TYPE : text
		
		$lname= new Zend_Form_Element_Text('lname');
		$lname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Last Name is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
			->setAttrib("onkeypress","return checkcharonly(event);")
		->setAttrib('class','changeaddress');
		
		# FORM ELEMENT:Companyname
		# TYPE : text
		
		$emailid= new Zend_Form_Element_Text('emailid');
		$emailid->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Company Name is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
		->setAttrib('class','changeaddress');
		
		if(@$_REQUEST['emailid']!="")
		{
		$emailid-> addValidator('EmailAddress', true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->addErrorMessage('Please enter a valid email address');
		}
		
		# FORM ELEMENT:address 
		# TYPE : text
		
		$address= new Zend_Form_Element_Text('address');
		$address->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Address is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib('class','changeaddress');
		
		
		# FORM ELEMENT:City
		# TYPE : text
		
		$city= new Zend_Form_Element_Text('city');
		$city->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'City is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
			->setAttrib("onkeypress","return checkcharonly(event);")
		->setAttrib('class','changeaddress');
		
		
		# FORM ELEMENT:State
		# TYPE : text
		
		$state= new Zend_Form_Element_Text('state');
		$state->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'State is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
			->setAttrib("onkeypress","return checkcharonly(event);")
		->setAttrib('class','changeaddress');
		
		# FORM ELEMENT:zipcode 
		# TYPE : text
		
		$zipcode= new Zend_Form_Element_Text('zipcode');
		$zipcode->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'zip code is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
			->setAttrib("onkeypress","return checknummsp(event);")
		->setAttrib('class','changeaddress');
		
		# FORM ELEMENT:telephn 
		# TYPE : text
		
		$phnno= new Zend_Form_Element_Text('phnno');
		$phnno->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Phone number is required'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("onchange","validfname(this.value);")
		->setAttrib("onkeypress","return checknummsp(event);")
		->setAttrib('class','changeaddress');
		
		
		# FORM ELEMENT:credit card
		# TYPE : text
		
		$creditcardno= new Zend_Form_Element_Text('creditcardno');
		$creditcardno->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Credit Card number is required'))
		->addDecorator('Errors', array('class'=>'error'))
		
		->setAttrib('class','changeaddress');
		
		# FORM ELEMENT:card type
		# TYPE : text
		
		$cardArr=array();
		$cardArr[1]['key']="Visa";
		$cardArr[1]['value']="Visa ";
		$cardArr[2]['key']="Maestro";
		$cardArr[2]['value']="Maestro";
		$cardArr[3]['key']="Master Card";
		$cardArr[3]['value']="Master Card";
		
		$creditcardtype= new Zend_Form_Element_Select('creditcardtype');
		$creditcardtype->addMultiOptions($cardArr)
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:33px;")	
		->setRequired(true);
		
		# FORM ELEMENT:expdate
		# TYPE : text
		
		$dateArr=array();
		/*$dateArr[0]['key']="";
		$dateArr[0]['value']="1";*/
		for($i=1;$i<=12;$i++)
		   {
			$dateArr[$i]['key']=$i;
			 $dateArr[$i]['value']=$i;
		   }
		  
	    $exprydate= new Zend_Form_Element_Select('exprymonth');
		$exprydate->addMultiOptions($dateArr)
		->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Expiry date is required'))
		->setAttrib("style","width:100px; height:33px;")
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib('class','changeaddress');
		
		$yearArr=array();
		//$yearArr[0]['key']="";
//		$yearArr[0]['value']="2005";
		for($i=2005;$i<=2030;$i++)
		   {
			$yearArr[$i]['key']=$i;
			$yearArr[$i]['value']=$i;
		   }
		   
	    $expryyear= new Zend_Form_Element_Select('expryyear');
		$expryyear->addMultiOptions($yearArr)
		->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Expiry date is required'))
		->setAttrib("style","width:130px; height:30px;")
		->setAttrib('class','changeaddress')
		->setAttrib("style","width:100px; height:33px;")	
		->addDecorator('Errors', array('class'=>'error'));
		
		
		# FORM ELEMENT:cvv2no
		# TYPE : text
		
		$cvvno= new Zend_Form_Element_Text('cvvno');
		$cvvno->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'CVV2 number is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("onchange","validfname(this.value);")
		->setAttrib("onkeypress","return checknummsp(event);")
		->setAttrib('class','changeaddress');
		
		// ->addDecorator('Errors', array('class'=>'error'))
//		->setAttrib("style","width:820px; height:50px;")
		
	    $this->addElements(array($fname, $lname, $emailid, $uname, $address, $city, $state, $zipcode, $phnno, $creditcardno, $creditcardtype, $exprydate, $expryyear, $cvvno));
		
	}	
}