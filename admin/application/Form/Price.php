<?PHP
class Form_Price extends Zend_Form
{  
	public function __construct($sno="")
	{
		$this->init($sno);
	}
    public function init($sno)
	{   
		global $mySession;
        $db=new Db();
		
	$basepric_value="";
	$shhpng_val="";
		
		//echo "serial no. : ".$sno; die;
		
	$qur=$db->runquery("SELECT * FROM  ".TSHIRT_PRICE." where sno=".$sno."");
	
	
	
	if($qur!="" and count($qur)>0)
	{
		$basepric_value=$qur[0]['base_price'];
		
	
		$shhpng_val=$qur[0]['shipping_price'];
	}
		
		$base_price= new Zend_Form_Element_Text('base_price');
		$base_price->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Base price is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
			->setValue($basepric_value);
		
		
		/*$per_profit= new Zend_Form_Element_Text('per_profit');
		$per_profit->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Profit % is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required");*/
		
		$shippingprice= new Zend_Form_Element_Text('shippingprice');
		$shippingprice->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Shipping Rate is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
			->setValue($shhpng_val);
		
	
		$this->addElements(array($base_price,$shippingprice));
		
	}
}	

?>