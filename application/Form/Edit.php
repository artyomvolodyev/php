<?php
class Form_Edit extends Zend_Form
{	 
	public function __construct($cid)
	{	
		$this->myInit($cid);
	}

	public function myInit($cid)
	{
		global $mySession;
		$db=new Db();

		$public_name="";
		$description_val="";
		$shippingaddr="";
		$firstname_val="";
		$lastname_val="";
		$address_val="";
		$city_val="";
		$state_val="";
		$zipcode_val="";
		$url_val="";
        $instruction_val = '';
		
		
		$qur=$db->runquery("SELECT * FROM  ".LAUNCHCAMPAIGN." WHERE campaign_id='".$cid."' ");
		
		
		
		if($qur!="" and count($qur)>0)
	    {
		
			$public_name=$qur[0]['title'];
			
			$url_val=$qur[0]['url'];
			
			$description_val=$qur[0]['description'];
			
			$shippingaddr_val=$qur[0]['new_address'];
			
			
		}
	
		
	
		$url= new Zend_Form_Element_Hidden('url');
		$url
		->setValue($url_val);
		$this->addElement($url);
		
		
		# FORM ELEMENT:camptitle
		# TYPE : text
		
		$camptitle= new Zend_Form_Element_Text('camptitle');
		$camptitle->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','setgoaltxt')
		->setAttrib("style","width:450px; height:30px;")
		->setValue($public_name);
		
			
		# FORM ELEMENT:description
		# TYPE : text
		
		$description= new Zend_Form_Element_Textarea('description');
		$description->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','setgoaltxt')
		// ->setAttrib("id","elrte")
		->setAttrib("style","width:450px; height:120px;")
		->setValue($description_val);
		
	
		
		# FORM ELEMENT:check
		# TYPE : text
		
		$shippingaddr= new Zend_Form_Element_Checkbox('shippingaddr');
		$shippingaddr
		->setAttrib('onclick','opendiv(this.id);');


		if($shippingaddr_val==1)
		{
			$qur1=$db->runquery("SELECT * FROM  ".SHIPPING_ADDRESS." WHERE url='".$url_val."' ");
			//prd($qur1);
			
			if($qur1!="" and count($qur1)>0)
			{
			
				$firstname_val=$qur1[0]['fname'];
				
				$lastname_val=$qur1[0]['lname'];
				
				$address_val=$qur1[0]['address'];
				
				$city_val=$qur1[0]['city'];
				
				$state_val=$qur1[0]['state'];
				
				$instruction_val=$qur1[0]['instruction'];
				
				$zipcode_val=$qur1[0]['zipcode'];
			}
		 }
		
		   
		    $firstname= new Zend_Form_Element_Text('firstname');
			$firstname
			->addValidator('NotEmpty',true,array('messages' =>'First name is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress');
			
			if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
			{
			  $firstname->setRequired(true);
			}
			$firstname->setAttrib("style","width:180px; height:30px;")
			->setValue($firstname_val);
			$this->addElement($firstname);
			
			
			
			
			# FORM ELEMENT:last name
			# TYPE : text
			
			$lastname= new Zend_Form_Element_Text('lastname');
			$lastname
			->addValidator('NotEmpty',true,array('messages' =>'Last name is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress');
					if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
					{
					  $lastname->setRequired(true);
					}
			$lastname->setAttrib("style","width:180px; height:30px;")
			->setValue($lastname_val);
			$this->addElement($lastname);
			
			
			
			
			
		    # FORM ELEMENT:address
			# TYPE : text
			
			
			$newaddress= new Zend_Form_Element_Text('newaddress');
			$newaddress
			->addValidator('NotEmpty',true,array('messages' =>'Address is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','changepasstextbox')
			->setValue($address_val);
		
			if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
			{
			  $newaddress->setRequired(true);
			}
			
			$this->addElement($newaddress)
			;
			
			# FORM ELEMENT:city 
			# TYPE : text
			
			$newcity= new Zend_Form_Element_Text('newcity');
			$newcity
			->addValidator('NotEmpty',true,array('messages' =>'City is required'))
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib("onkeypress","return checkcharonly(event);")
			->setAttrib('class','changeaddress')
			->setValue($city_val);

					if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
					{
					  $newcity->setRequired(true);
					}
						
			$newcity->setAttrib("style","width:100px; height:30px;");
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
			->setAttrib('class','changeaddress')
			->setValue($state_val);
			
						if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
						{
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
		    ->setAttrib("onkeypress","return checknummsp(event);")
			->setValue($zipcode_val);
				
						if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
						{
							$newzipcode->setRequired(true);
						}
			
			$newzipcode->setAttrib("style","width:100px; height:30px;");	
			$this->addElement($newzipcode);
			
			
			
			# FORM ELEMENT:instruction 
			# TYPE : text
			
			
			$instruction= new Zend_Form_Element_Textarea('instruction');
			$instruction
			->addDecorator('Errors', array('class'=>'errmsg'))
			->setAttrib('class','setgoaltxt')
			->setValue($instruction_val);
			
			if(array_key_exists('shippingaddr', $_REQUEST) && $_REQUEST['shippingaddr']==1)
			{
			$instruction->setRequired(true);
			}
			$instruction->setAttrib("style","width:450px; height:120px;");
			$this->addElement($instruction);
			
			
			

		$this->addElements(array($camptitle , $description, $shippingaddr));
	}
}