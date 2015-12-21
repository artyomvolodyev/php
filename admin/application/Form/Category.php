<?PHP
class Form_Category extends Zend_Form
{  
	public function __construct($catId="")
	{
		$this->init($catId);
	}
    public function init($catId)
	{   
		global $mySession;
        $db=new Db();
		
		$catname=""; 
		$catdescription="";
		
		
		
		if($catId!="")
		{
			$Data=$db->runQuery("select * from ".CATEGORY." where cat_id='".$catId."'");		
			$catname=$Data[0]['cat_name'];
			$catdescription=$Data[0]['cat_description'];
		}

		
		$cat_name= new Zend_Form_Element_Text('cat_name');
		$cat_name->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Category name is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($catname);
		
		$cat_description= new Zend_Form_Element_Textarea('cat_description');
		$cat_description->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Category description is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($catdescription);
		
		
	/*	$pagepositionArr=array();
		$pagepositionArr[0]['key']="0";
		$pagepositionArr[0]['value']="Top";
		$pagepositionArr[1]['key']="1";
		$pagepositionArr[1]['value']="Bottom";
		$pageposition= new Zend_Form_Element_Radio('pageposition');
		$pageposition->addMultiOptions($pagepositionArr)
		->setValue($pageposition_value);
*/
		
		$this->addElements(array($cat_name,$cat_description));
		
	}
}	

?>