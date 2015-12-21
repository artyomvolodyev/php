<?PHP
class Form_Sitedesc extends Zend_Form
{  
	public function __construct($desc_id)
	{
		global $mySession;
		$this->init($desc_id);
	}
    public function init($desc_id)
	{
		global $mySession;
        $db=new Db();		
		
		$site_desc_value="";
		if($desc_id!="")
		{
		$adminData=$db->runQuery("select * from ".SITE_DESC." where desc_id='".$desc_id."'");
	    $site_desc_value=$adminData[0]['site_desc'];
		}
		
		$site_desc= new Zend_Form_Element_Textarea('site_desc');
		$site_desc->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Region name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:600px;height:300px")
		->setAttrib("id","elrte")->setAttrib("cols","auto")
		->setAttrib("class","mws-textinput required")
		->setValue($site_desc_value);	
		$this->addElement($site_desc);

	}
}	

?>