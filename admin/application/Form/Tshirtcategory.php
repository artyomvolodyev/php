<?PHP
class Form_Tshirtcategory extends Zend_Form
{ 
	public function __construct($Id='')
	{
        $this->init($Id);
	}

    public function init($Id)
	{   
        global $mySession;
        $db = new Db();
	
		if($Id != "")
		{
            $adminData = $db->runQuery("select * from ".TSHIRT_PRODUCTS." where t_cat_id='".$Id."'");
            $qur = $db->runquery("SELECT * FROM  ".TSHIRT_PRICE." where campagin_id=".$Id."");
            $title_value=$adminData[0]['name'];
            $oldicon_value=$adminData[0]['image'];
            $backoldicon_value=$adminData[0]['backimage'];
            $oldfrontShadow_value=$adminData[0]['frontShadow'];
            $oldfrontHeigh_value=$adminData[0]['frontHeigh'];
            $oldbackShadow_value=$adminData[0]['backShadow'];
            $oldbacktHeigh_value=$adminData[0]['backHeigh'];
            $basepric_value=$qur[0]['base_price'];
            $colorcode_value=$adminData[0]['colorcode'];
            $shhpng_val=$qur[0]['shipping_price'];
		}
		
		$colorcode = new Zend_Form_Element_Text('colorcode');
		$colorcode->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Please enter color code.'))
            ->addDecorator('Errors', array('class'=>'error'))
            ->setAttrib("class","Expandable mws-textinput required")
            ->setAttrib("onkeypress","return checknummspK(event)")
            ->setValue($colorcode_value);
		$this->addElement($colorcode);
		
		$title = new Zend_Form_Element_Text('title');
		$title->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Category Name is required.'))
            ->addDecorator('Errors', array('class'=>'error'))
            ->setAttrib("class","mws-textinput required")
            ->setValue($title_value);
		$this->addElement($title);

		if($Id == "")
		{
            $image = new Zend_Form_Element_File('image');
            $image->setDestination(SITE_ROOT.'images/tshirtdesigns/front')
                ->setRequired(true)
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib("class","textbox")
                ->addDecorator('Errors', array('class'=>'error'));
		}else{
            $image = new Zend_Form_Element_File('image');
            $image->setDestination(SITE_ROOT.'images/tshirtdesigns/front')
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib("class","textbox");
		}

		$this->addElement($image);
		
        $frontHeigh = new Zend_Form_Element_File('frontHeigh');
        $frontHeigh->setDestination(SITE_ROOT.'images/tshirtdesigns/front')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setAttrib("class","textbox");
        $this->addElement($frontHeigh);

        $oldfrontHeigh = new Zend_Form_Element_Hidden('oldfrontHeigh');
        $oldfrontHeigh->setValue($oldfrontHeigh_value);
        $this->addElement($oldfrontHeigh);

        $frontShadow = new Zend_Form_Element_File('frontShadow');
        $frontShadow->setDestination(SITE_ROOT.'images/tshirtdesigns/front')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setAttrib("class","textbox");
        $this->addElement($frontShadow);

        $oldfrontShadow = new Zend_Form_Element_Hidden('oldfrontShadow');
        $oldfrontShadow->setValue($oldfrontShadow_value);
        $this->addElement($oldfrontShadow);

        // back
        $backHeigh = new Zend_Form_Element_File('backHeigh');
        $backHeigh->setDestination(SITE_ROOT.'images/tshirtdesigns/back')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setAttrib("class","textbox");
        $this->addElement($backHeigh);

        $oldbackHeigh = new Zend_Form_Element_Hidden('oldbackHeigh');
        $oldbackHeigh->setValue($oldbacktHeigh_value);
        $this->addElement($oldbackHeigh);

        $backShadow = new Zend_Form_Element_File('backShadow');
        $backShadow->setDestination(SITE_ROOT.'images/tshirtdesigns/back')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setAttrib("class","textbox");
        $this->addElement($backShadow);

        $oldbackShadow = new Zend_Form_Element_Hidden('oldbackShadow');
        $oldbackShadow->setValue($oldbackShadow_value);
        $this->addElement($oldbackShadow);

		if($Id != "")
		{
            $oldicon = new Zend_Form_Element_Hidden('oldicon');
            $oldicon->setValue($oldicon_value);
            $this->addElement($oldicon);

            $backoldicon = new Zend_Form_Element_Hidden('backoldicon');
            $backoldicon->setValue($backoldicon_value);
		    $this->addElement($backoldicon);
		}

		$backimage = new Zend_Form_Element_File('backimage');
		$backimage->setDestination(SITE_ROOT.'images/tshirtdesigns/back')
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
        /*	->addValidator('ImageSize',true,array('minwidth' => 50,
                                'maxwidth' => 450,
                                'minheight' =>50,
                                'maxheight' => 450),
                                array('messages' =>'Minimum Height-Width :50px and Maximum Height: 450px and Width:450px'))*/
            ->setAttrib("class","textbox");
		$this->addElement($backimage);
		
        $base_price = new Zend_Form_Element_Text('base_price');
        $base_price->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Base price is required.'))
            ->addDecorator('Errors', array('class'=>'errormsg'))
            ->setAttrib("class","mws-textinput required")
            ->setValue($basepric_value);
        $this->addElement($base_price);

        $shippingprice = new Zend_Form_Element_Text('shippingprice');
        $shippingprice->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Shipping Rate is required.'))
            ->addDecorator('Errors', array('class'=>'errormsg'))
            ->setAttrib("class","mws-textinput required")
            ->setValue($shhpng_val);
        $this->addElement($shippingprice);

    }
		
}	

?>