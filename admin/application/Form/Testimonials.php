<?PHP
class Form_Testimonials extends Zend_Form
{  
	public function __construct($testimonialsId)
	{
		$this->init($testimonialsId);
	}
    public function init($testimonialsId)
	{   
		global $mySession;
        $db=new Db();
		
		$catname=""; 
		$catdescription="";
		$catsitename="";
		$catimage="";
		
		if($testimonialsId!="")
		{
			$Data=$db->runQuery("select * from ".TESTIMONIALS." where testimonials_id='".$testimonialsId."'");		
			
			//echo "select * from ".TESTIMONIALS." where testimonials_id='".$testimonialsId."'"; die;
			
			$catname=$Data[0]['testimonials_name'];
			$catdescription=$Data[0]['testimonials_data'];
			$catsitename=$Data[0]['testimonials_sitename'];
			$catimage=$Data[0]['testimonials_image'];
		}

		
		$cat_name= new Zend_Form_Element_Text('cat_name');
		$cat_name->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Name is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($catname);
		
		
		$cat_sitename= new Zend_Form_Element_Text('cat_sitename');
		$cat_sitename->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Name is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($catsitename);
		
		
		$cat_description= new Zend_Form_Element_Textarea('cat_description');
		$cat_description->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'description is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($catdescription);
		
		$image=new Zend_Form_Element_File('image');
		$image->setDestination(SITE_ROOT.'images/');
		$image->addValidator('Extension', false, 'jpg,jpeg,png,gif')
		->setAttrib("class","textbox")
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setValue($catimage)
		//->setAttrib("class","mytext1")
		->setAttrib("style","width:200px;height:25px;");
		//$this->addElement($image);
	/*	$pagepositionArr=array();
		$pagepositionArr[0]['key']="0";
		$pagepositionArr[0]['value']="Top";
		$pagepositionArr[1]['key']="1";
		$pagepositionArr[1]['value']="Bottom";
		$pageposition= new Zend_Form_Element_Radio('pageposition');
		$pageposition->addMultiOptions($pagepositionArr)
		->setValue($pageposition_value);
*/
		
		$this->addElements(array($cat_name,$cat_sitename,$image,$cat_description));
		
	}
}	

?>