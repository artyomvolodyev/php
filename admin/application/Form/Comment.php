<?PHP
class Form_Blog extends Zend_Form
{  
	public function __construct($postId="")
	{
		global $mySession;
		$this->init($blogid);
	}
	
	public function init($postId)
	{   
		global $mySession;
		$db=new Db();        				
		$comment_value="";
		//$blogDescription="";
		
		if($postId!="")
		{
			$blogData=$db->runQuery("select * from ".POST_COMMENT." where post_id='".$postId."'");
			$comment=$blogData[0]['comment'];
		}
		$comments=new Zend_Form_Element_Textarea('comments');
		$comments->setRequired(true)
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:200px;height:100px;")		
		->setValue($comment_value);	
		
	
		$this->addElements(array($comments));		
	}
}
?>