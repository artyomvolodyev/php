<?php
class Form_Buy extends Zend_Form
{	 
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		global $mySession;
		$db=new Db();
		
		# FORM ELEMENT:qty 
		# TYPE : text
		$qty= new Zend_Form_Element_Text('qty');
		$qty->setRequired(true)
		//->addValidator('NotEmpty',true,array('messages' =>'Public name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("onchange","return calcurate(this.value);")
		->setAttrib("onkeypress","return checknummsp(event,this.value);")
		/*->setAttrib("onkeyUp","return checkzerostatic(this,this.value);")*/
		->setAttrib("style","width:50px; height:20px;")
		->setValue(1);
		$this->addElement($qty);

		
		# FORM ELEMENT: size
		# TYPE : Select
        $SizeArr=array();
        $sizes=$db->runQuery("select * from ".TSHIRT_SIZE);
        foreach($sizes AS $key=>$size){
            $SizeArr[$key] = array();
            $SizeArr[$key]['key'] = $size['size'];
            $SizeArr[$key]['value'] = $size['size'];
        }

		$size= new Zend_Form_Element_Select('size');
		$size->addMultiOptions($SizeArr)
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("style","width:50px; height:20px;");
		//->setValue($size_value);
		$this->addElement($size);
		
        $PaypalArr=array();
        $PaypalArr[0]['key']="1";
        $PaypalArr[0]['value']="PayPal";
        $PaypalArr[1]['key']="2";
        $PaypalArr[1]['value']="Credit Card";
  
        $paypalchk= new Zend_Form_Element_Radio('paypalchk');
        $paypalchk->addMultiOptions($PaypalArr)
        ->addValidator('NotEmpty',true,array('messages' =>'Payment Mode is required.'))
        ->addDecorator('Errors', array('class'=>'error'))
        ->setRequired(true)
        ->setValue(1);
        $this->addElement($paypalchk);

        # FORM ELEMENT:price
        # TYPE : text
		$price= new Zend_Form_Element_Text('price');
		$price->setRequired(true)
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("style","width:50px; height:20px;");
		$this->addElement($price);
		
		$cusemail="";
		$cusfname="";
		$cuslname="";
        $street1Val="";
        $street2Val="";
        $cityVal="";
        $stateVal="";
        $zipcodeVal="";

		if($mySession->TeeLoggedID!="") {
			$chkQry=$db->runQuery("select * from ".USERS." where  user_id='".(int)$mySession->TeeLoggedID."'");
            if($chkQry && count($chkQry)){
                $cusemail=$chkQry[0]['emailid'];
                $cusfname=$chkQry[0]['public_name'];
            }

            $address=$db->runQuery("select * from ".ADDRESS." where user_id='".(int)$mySession->TeeLoggedID."'");
            if($address && count($address)){
                $streetArr = explode('||', $address[0]['address'], 2);
                if(count($streetArr)>0){
                    $street1Val = $streetArr[0];
                }
                if(count($streetArr)>1){
                    $street2Val = $streetArr[1];
                }
                $nameArr = explode(' ', $address[0]['public_name'], 2);
                if(count($nameArr)>0){
                    $cusfname = $nameArr[0];
                }
                if(count($nameArr)>1){
                    $cuslname = $nameArr[1];
                }
                $cityVal = $address[0]['city'];
                $stateVal = $address[0]['state'];
                $zipcodeVal = $address[0]['zipcode'];
            }
		}
		
		$cemail= new Zend_Form_Element_Text('cemail');
		$cemail->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Email is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("style","width:250px; height:25px;")
		->setValue($cusemail);		
		$this->addElement($cemail);

		$cfname= new Zend_Form_Element_Text('cfname');
		$cfname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'First Name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("style","width:250px; height:25px;")
		->setValue($cusfname);
		$this->addElement($cfname);

		$clname= new Zend_Form_Element_Text('clname');
		$clname->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Last Name is required'))
		->addDecorator('Errors', array('class'=>'errmsg'))
		->setAttrib("style","width:250px; height:25px;")
		->setValue($cuslname);
		$this->addElement($clname);

        $street1= new Zend_Form_Element_Text('street1');
        $street1->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Street is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("style","width:250px; height:25px;")
            ->setValue($street1Val);
        $this->addElement($street1);

        $street2= new Zend_Form_Element_Text('street2');
        $street2->setRequired(true)
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("style","width:250px; height:25px;")
            ->setValue($street2Val);
        $this->addElement($street2);

        $city= new Zend_Form_Element_Text('city');
        $city->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'City is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("style","width:250px; height:25px;")
            ->setValue($cityVal);
        $this->addElement($city);

        $zipcode= new Zend_Form_Element_Text('zipcode');
        $zipcode->setRequired(true)
            ->addValidator('NotEmpty',true,array('messages' =>'Zip code is required'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("style","width:250px; height:25px;")
            ->setValue($zipcodeVal);
        $this->addElement($zipcode);

        $StateArr=array();
        $StateArr[0]['key']="";
        $StateArr[0]['value']="- - Select  State- -";

        $StateData=$db->runQuery("select * from ".STATE." order by state_name");
        if($StateData && count($StateData))
        {
            foreach($StateData as $key=>$StateValues)
            {
                $StateArr[$key+1]['key']=$StateValues['state_id'];
                $StateArr[$key+1]['value']=$StateValues['state_name'];
            }
        }

        $state= new Zend_Form_Element_Select('state');
        $state->setRequired(true)
            ->addMultiOptions($StateArr)
            ->addValidator('NotEmpty',true,array('messages' =>'State is required.'))
            ->addDecorator('Errors', array('class'=>'errmsg'))
            ->setAttrib("style","width:100px; height:33px;")
            ->setAttrib("onchange","getneighborhoodcities(this.value);")
            ->setValue($stateVal);
        $this->addElement($state);

    }
}