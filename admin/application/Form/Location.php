<?PHP
class Form_Location extends Zend_Form
{  
	public function __construct($y,$countryid,$stateid,$cityid)
	{
		global $mySession;
		$this->init($y,$countryid,$stateid,$cityid);
	}
    public function init($y,$countryid,$stateid,$cityid)
	{ 
		global $mySession;
        $db=new Db();
		
		$country_value="";
		$state_value="";
		$city_value="";
		
		//if($countryid!="")
//		{ //echo $countryid; die;
//				$sql="select * from ".COUNTRIES." where country_id='".$countryid."'"; 
//				$chkQry=$db->runQuery($sql);
//				$country_value=$chkQry[0]['country_name'];
//		}
		
		if($stateid!="")
		{ //echo $countryid; die;
			$sql="select * from ".STATE."";
				//$sql="select * from ".STATE." join ".COUNTRIES." on ".COUNTRIES.".country_id=".STATE.".country_id where state_id='".$stateid."'"; 
				$chkQry=$db->runQuery($sql);
				//$country_value=$chkQry[0]['country_id'];
				$state_value=$chkQry[0]['state_name'];
		}
		
		if($cityid!="")
		{ //echo $countryid; die;
		
				$sql="select * from ".CITIES." join ".STATE." on ".STATE.".state_id=".CITIES.".state_id where city_id='".$cityid."'"; 
				$chkQry=$db->runQuery($sql);
				$state_value=$chkQry[0]['state_id'];
				$city_value=$chkQry[0]['city_name'];
		}
		
		if($y==1)
		{
		$country= new Zend_Form_Element_Text('country');
		$country->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Country Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:250px;")
		->setValue($country_value);
		$this->addElement($country);
		}
		else if($y==2)
		{
		$CountryArr=array();
		$CountryArr[0]['key']="";
		$CountryArr[0]['value']="- - Select   Country- -";
		$CountryData=$db->runQuery("select * from ".COUNTRIES." order by country_name");
			if($CountryData!="" and count($CountryData)>0)
			{
				$i=1;
				foreach($CountryData as $key=>$CountryValues)
				{
				$CountryArr[$i]['key']=$CountryValues['country_id'];
				$CountryArr[$i]['value']=$CountryValues['country_name'];
				$i++;
				}
			}
			
		$country= new Zend_Form_Element_Select('country');
		//$country->setRequired(true)
		$country->addMultiOptions($CountryArr)
		->addValidator('NotEmpty',true,array('messages' =>'Country is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:300px; height:30px;");
		//echo $country_value;die;	
		$country->setValue($country_value);
		$this->addElement($country);
		
			
			
		$state= new Zend_Form_Element_Text('state');
		$state->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'State Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:250px;")	
		->setValue($state_value);
		$this->addElement($state);
		
		}
		
		else if($y==3)
		{
		$StateArr=array();
		$StateArr[0]['key']="";
		$StateArr[0]['value']="- - Select   State- -";
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
		$state->setRequired(true)
		->addMultiOptions($StateArr)
		->addValidator('NotEmpty',true,array('messages' =>'State is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:300px; height:30px;");	
		$state->setValue($state_value);
		$this->addElement($state);
			
			
		$city= new Zend_Form_Element_Text('city');
		$city->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'City Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:250px;")	
		->setValue($city_value);
		$this->addElement($city);
		
		}
		
	}
			
}
?>
