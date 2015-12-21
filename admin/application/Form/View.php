<?php
class Form_View extends Zend_Form
{	 
	public function __construct($campid)
	{	
		
		$this->init($campid);
	}
	public function init($campid)
	{
		global $mySession;
		$db=new Db();
		
		
		$title_val = "";
		$baseprice_val = "";
		$goal_val = "";
		$sold_val = "";
		$sellingprice_val = "";
		$description_val = "";
		$camplength_val ="";
		$url_val ="";
		$launchdate_val = "";
		
		if($campid!="")
		{
			
			$qur=$db->runquery("SELECT * FROM  ".LAUNCHCAMPAIGN." WHERE campaign_id='".$campid."'");
			
			if($qur!="" and count($qur)>0)
			{
				//prd($qur);
				$title_val = $qur[0]['title'];
				$baseprice_val = $qur[0]['base_price'];
				$goal_val = $qur[0]['goal'];
				$sold_val = $qur[0]['sold'];
				$sellingprice_val = $qur[0]['selling_price'];
				$description_val = $qur[0]['description'];
				$camplength_val = $qur[0]['campaign_length'];
				$url_val = $qur[0]['url'];
				$launchdate_val = $qur[0]['launch_date'];
				
			}
		}
		
		
		# FORM ELEMENT:no of tee
		# TYPE : text
		
		$baseprice= new Zend_Form_Element_Text('baseprice');
		$baseprice->setRequired(true)
		->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		->setAttrib("style","width:50px; height:20px;")
		->setvalue($baseprice_val);
		
		$sellingprice= new Zend_Form_Element_Text('sellingprice');
		$sellingprice->setRequired(true)
		->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		
		->setAttrib("style","width:50px; height:20px;")
		->setvalue($sellingprice_val);
		
		# FORM ELEMENT:camptitle
		# TYPE : text
		
		$camptitle= new Zend_Form_Element_Text('camptitle');
		$camptitle->setRequired(true)
		->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		->setAttrib("style","width:250px; height:30px;")
		->setvalue($title_val);
		
			
		# FORM ELEMENT:description
		# TYPE : text
		
		$description= new Zend_Form_Element_Textarea('description');
		$description->setRequired(true)
		->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		// ->setAttrib("id","elrte")
		->setAttrib("style","width:450px; height:120px;")
		->setvalue($description_val);
		
		
		$goal= new Zend_Form_Element_Text('goal');
		$goal->setRequired(true)
		->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		->setAttrib("style","width:50px; height:20px;")
		->setvalue($goal_val);
		
		
		
		$sold= new Zend_Form_Element_Text('sold');
		$sold->setRequired(true)
	     ->setAttrib("class","mws-textinput required")
		->setAttrib("readonly","readonly")
		->setAttrib("style","width:50px; height:20px;")
		->setvalue($sold_val);
		
		
		
		# FORM ELEMENT:url
		# TYPE : text
			$url= new Zend_Form_Element_Text('url');
			$url->setRequired(true)
			->setAttrib("class","mws-textinput required")
			->setAttrib("readonly","readonly")
			->setAttrib("style","width:60px; height:20px;")
		    ->setvalue($url_val);
		
		# FORM ELEMENT:url
		# TYPE : text
			
			$camplength= new Zend_Form_Element_Text('camplength');
			$camplength->setRequired(true)
			->setAttrib("class","mws-textinput required")
			->setAttrib("readonly","readonly")
		
			->setAttrib("style","width:50px; height:20px;")
			->setvalue($camplength_val);
		
		
		# FORM ELEMENT:url
		# TYPE : text
		
			$launchdate= new Zend_Form_Element_Text('launchdate');
			$launchdate->setRequired(true)
			->setAttrib("class","mws-textinput required")
			->setAttrib("readonly","readonly")
		
			->setAttrib("style","width:70px; height:20px;")
		   ->setvalue($launchdate_val);
		
		
		  $this->addElements(array($sellingprice, $baseprice, $camptitle, $camplength, $launchdate, $url, $description, $sold, $goal));
		
	}
}