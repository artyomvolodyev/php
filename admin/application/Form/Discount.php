<?PHP
class Form_Discount extends Zend_Form
{  
	public function __construct($sno="")
	{
		$this->init($sno);
	}
    public function init($sno)
	{   
		global $mySession;
        $db=new Db();
		
	$tee_value="";
	$disc_val="";
		
		//echo "serial no. : ".$sno; die;
		
	$qur=$db->runquery("SELECT * FROM  ".TSHIRT_DISCOUNT." where sno=".$sno."");
	
	//$qur=$db->runquery("select * from ".ADDRESS." join ".STATE." on ".STATE.".state_id=".ADDRESS.".state where user_id='".$mySession->TeeLoggedID."' ");
	
	if($qur!="" and count($qur)>0)
	{
		$tee_value=$qur[0]['no_of_tee'];
		
	
		$disc_val=$qur[0]['discount_per'];
	}
	
		
		$nooftee= new Zend_Form_Element_Text('nooftee');
		$nooftee->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Number of Tee is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($tee_value);
		
		
		$per_discount= new Zend_Form_Element_Text('per_discount');
		$per_discount->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Discount % is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($disc_val);
		
	
		$this->addElements(array($nooftee,$per_discount));
		
	}
}	

?>