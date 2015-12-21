<?PHP
class Form_Blog extends Zend_Form
{  
	public function __construct($blogid)
	{
		//echo "dfs"; die;
		global $mySession;
		$this->init($blogid);
	}

	public function init($blogid)
	{   //echo $blogid; die;
		global $mySession;
		$db=new Db();
		
		$image_value="";
		$blogtitle_value="";
		$blogdesc_value="";
		$status_value="";
		$blog_value="";
		$old_image_value="";
		
		if($blogid!="")
		{
			
			$Data=$db->runQuery("select * from ".BLOGPOST." where blog_id='".$blogid."'");
			//$image_value=$Data[0]['post_image'];
			$blogtitle_value=$Data[0]['blog_title'];
			$blogdesc_value=$Data[0]['blog_desc'];
			$image_value=$Data[0]['blog_image'];
			$status_value=$Data[0]['blog_status'];
			$old_image_value=$Data[0]['blog_image'];

		}
		
		$old_image= new Zend_Form_Element_Hidden('old_image');
		$old_image->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setValue($old_image_value);
		$this->addElement($old_image);
		
		$image=new Zend_Form_Element_File('image');
		$image->setDestination(SITE_ROOT.'images/blogimg/');
		
		if($blogid=="")
		{
		$image->setRequired(true)	
		
		->addValidator('NotEmpty',true,array('messages' =>'Please select a photo.'));
		}
		
		$image->addValidator('Extension', false, 'jpg,jpeg,png,gif')
		->setAttrib("class","textbox")
		->addDecorator('Errors', array('class'=>'error'))
			/*->addValidator('ImageSize',true,array('minwidth' => 500,
                            'maxwidth' => 2000,
                            'minheight' => 500,
                            'maxheight' => 1000),array('messages' =>'Please select a photo.'))
		*/
		->setValue($image_value);
		$this->addElement($image);
						
		$blogtitle= new Zend_Form_Element_Text('blogtitle');
		$blogtitle->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:165px;")
		->setValue($blogtitle_value);
		$this->addElement($blogtitle);
		
		$blog_description= new Zend_Form_Element_Textarea('blog_description');
		$blog_description->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Please enter this field.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","textInput")
		->setAttrib("style","width:170px; height:60px;")
		->setValue($blogdesc_value);
		$this->addElement($blog_description);
		//echo "dfs"; die;
		
		
		$StatusArr=array();
		$StatusArr[0]['key']="1";
		$StatusArr[0]['value']="Active";
		$StatusArr[1]['key']="0";
		$StatusArr[1]['value']="Inactive";
		
		$blog_status= new Zend_Form_Element_Select('blog_status');
		$blog_status->addMultiOptions($StatusArr)
		->setAttrib("class","textInput")
		->setValue($status_value);
		$this->addElement($blog_status);
		
		
	}
}
?>