<?PHP
class Form_Size extends Zend_Form
{  
	public function __construct($sno="")
	{
		$this->myInit($sno);
        parent::__construct();
	}

    public function myInit($sno)
	{   
		global $mySession;
        $db=new Db();
		
        $size_value="";
        $inch_val="";

        if($sno && $sno > 0){
            $qur=$db->runquery("SELECT * FROM  ".TSHIRT_SIZE." where sizeid=".$sno."");
            if($qur!="" and count($qur)>0){
                $size_value=$qur[0]['size'];
                $inch_val=$qur[0]['size_inch'];
            }
        }

		$sizes= new Zend_Form_Element_Text('sizes');
		$sizes->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Size is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($size_value);
		
		$ininches= new Zend_Form_Element_Text('ininches');
		$ininches->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Size in inches is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($inch_val);
	
		$this->addElements(array($sizes,$ininches));
	}
}	

?>