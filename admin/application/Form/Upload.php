<?PHP
class Form_Upload extends Zend_Form
{ 
	 public function __construct()
	{	//echo"fds"; die;
		$this->init();
	}
    public function init()
	{   
	global $mySession;
	$db=new Db();
	
		$image=new Zend_Form_Element_File('image');
		$image->setDestination(SITE_ROOT.'images/adminpostimg/')
		->setRequired(true)		
		->addValidator('NotEmpty',true,array('messages' =>'Please select a photo.'))
        ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
		->setAttrib("class","textbox")
		->addDecorator('Errors', array('class'=>'error'));
		
		$this->addElement($image);
		}
		
}	

?>