<?php
class Form_Setgoal extends Zend_Form
{	 
	public function __construct($price)
	{			
		global $mySession;
		$db=new Db();
		
		$price=$price+2;
				
		$sellingprice= new Zend_Form_Element_Text('sellingprice');
		$sellingprice->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib('class','setgoaltxt')
		
		->setAttrib('onchange','assignprice(event,this.value);')
		->setAttrib('onkeyup','assignprice(this.value);')
		->setAttrib("onkeypress","return checknummsp(event,this.value);")
		
		->setAttrib('onchange','calculate();')
		//->setAttrib('onkeyup','checkprice(this.value);')
		->setAttrib('onfocus','checkprice(this.value);')
		->setAttrib('onchange','checkprice(this.value);')
		
		
		
		->setAttrib("style","width:100px; height:30px;")
		->setValue($price);
		
		  $this->addElements(array($sellingprice));
	}
}