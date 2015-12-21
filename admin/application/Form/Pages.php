<?PHP
class Form_Pages extends Zend_Form
{  
	public function __construct($pageId="")
	{
		$this->init($pageId);
	}
    public function init($pageId)
	{   
		global $mySession;
        $db=new Db();
		
		$PageTitle="";$PageContent="";//$pageposition_value="";
		if($pageId!="")
		{
		$PageData=$db->runQuery("select * from ".PAGES." where page_id='".$pageId."'");		
		$PageTitle=$PageData[0]['page_title'];
		$PageContent=$PageData[0]['page_content'];
		//$pageposition_value=$PageData[0]['page_position'];
		}
		
		$page_title= new Zend_Form_Element_Text('page_title');
		$page_title->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Page title is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($PageTitle);
		
		$page_content= new Zend_Form_Element_Textarea('page_content');
		$page_content->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Page content is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setAttrib("id","elrte")->setAttrib("cols","auto")
		->setAttrib("style","width:90%;")
		->setValue($PageContent);
		
		
	/*	$pagepositionArr=array();
		$pagepositionArr[0]['key']="0";
		$pagepositionArr[0]['value']="Top";
		$pagepositionArr[1]['key']="1";
		$pagepositionArr[1]['value']="Bottom";
		$pageposition= new Zend_Form_Element_Radio('pageposition');
		$pageposition->addMultiOptions($pagepositionArr)
		->setValue($pageposition_value);
*/
		
		$this->addElements(array($page_title,$page_content));
		
	}
}	

?>