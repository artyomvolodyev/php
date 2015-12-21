<?PHP
class Form_Icons extends Zend_Form
{ 
	public function __construct($Id="")
	{
        $this->myInit($Id);
	}

    public function myInit($Id)
	{   
        global $mySession;
        $db=new Db();
	
		if($Id != "")
		{
            $adminData=$db->runQuery("select * from ".TSHIRT_ICONS." where id='".$Id."'");
            $title_value=$adminData[0]['title'];
            $oldicon_value=$adminData[0]['icon'];
            $colorcode_value=$adminData[0]['colorcode'];
		}else{
            $title_value = '';
            $oldicon_value = '';
            $colorcode_value = '';
        }
		
		$title= new Zend_Form_Element_Text('title');
		$title->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'First Name is required.'))
            ->addDecorator('Errors', array('class'=>'error'))
            ->setAttrib("class","mws-textinput required")
            ->setValue($title_value);
		
		
		$colorcode= new Zend_Form_Element_Text('colorcode');
		$colorcode->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Please enter color code.'))
            ->addDecorator('Errors', array('class'=>'error'))
            ->setAttrib("class","Expandable mws-textinput required")
            ->setAttrib("onkeypress","return checknummspK(event)")
            ->setValue($colorcode_value);
		$this->addElement($colorcode);

		$this->addElement($title);
		if($Id == "")
		{
            $image=new Zend_Form_Element_File('image');
            $image->setDestination(SITE_ROOT.'images/tshirt-icons/')
            ->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Please select an icon.'))
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setAttrib("class","textbox")
            ->addDecorator('Errors', array('class'=>'error'));
		} else {
            $image=new Zend_Form_Element_File('image');
            $image->setDestination(SITE_ROOT.'images/tshirt-icons/')
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib("class","textbox");
		}
		$this->addElement($image);
		if($Id != "")
		{
            $oldicon= new Zend_Form_Element_Hidden('oldicon');
            $oldicon->setValue($oldicon_value);
            $this->addElement($oldicon);

            $this->addElement("hidden", "foo", array("decorators" => array(
                array(array("img" => "HtmlTag"), array(
                    "tag" => "img",
                    "openOnly" => true,
                    "src" => IMAGES_URL.'tshirt-icons/'.$oldicon_value,
                    "align" => "middle",
                    "class" => "myClass",
                    "style"  => "max-width:200px;max-height:200px;"
                )),
                array("ViewHelper"),
                array(array("span" => "HtmlTag"), array(
                    "tag" => "span",
                    "class" => "myElement",
                    "style" => "text-align:center;display:block;"
                ))
            )));
		}
		
		
		
	}
		
}	

?>