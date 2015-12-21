<?PHP
class Form_Mailtemplate extends Zend_Form
{  
	public function __construct($templateId="")
	{
		$this->myInit($templateId);
        parent::__construct();
	}
    public function myInit($templateId)
	{   
		global $mySession;
        $db=new Db();
		
		$EmailSubject="";
        $MessageBody="";

		if($templateId != "") {
		    $templateData = $db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='".$templateId."'");
            if($templateData && count($templateData)){
                $EmailSubject = $templateData[0]['email_subject'];
                $MessageBody = $templateData[0]['email_body'];
            }
		}
		
		$email_subject= new Zend_Form_Element_Text('email_subject');
		$email_subject->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Page title is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:400px;padding:3px;")		
		->setValue($EmailSubject);
		
		$email_body= new Zend_Form_Element_Textarea('email_body');
		$email_body->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Page content is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:90%;height:400px;")
		->setAttrib("id","elrte")->setAttrib("cols","auto")
		->setValue($MessageBody);
		
		$this->addElements(array($email_subject,$email_body));
		
	}
}	

?>