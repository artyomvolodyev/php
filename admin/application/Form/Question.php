<?PHP
class Form_Question extends Zend_Form
{  
	public function __construct($quesId,$catId)
	{
		
		global $mySession;
		$this->init($quesId,$catId);
	}
    public function init($quesId,$catId)
	{ 
	
		global $mySession;
        $db=new Db();
		
		$catid="";
		$catname_val="";
		$question_val="";
		$answer_val="";
		
	
		if($quesId!="")
		{
		$Data=$db->runQuery("select * from ".QUESTIONS." where ques_id='".$quesId."'");		
		$question_val=$Data[0]['question'];
		$answer_val=$Data[0]['answer'];
		
		}
		
		
		
				$sql="select * from ".CATEGORY." where cat_id='".$catId."'"; 
				//echo "select * from ".CATEGORY." where cat_id='".$catId."'"; die;
				$chkQry=$db->runQuery($sql);
				
				$catname_val=$chkQry[0]['cat_id'];
				
	
		
		
		
	
		$CountryArr=array();
		$CountryArr[0]['key']="";
		$CountryArr[0]['value']="- - Select Category- -";
		$CountryData=$db->runQuery("select * from ".CATEGORY." order by cat_name");
			if($CountryData!="" and count($CountryData)>0)
			{
				$i=1;
				foreach($CountryData as $key=>$CountryValues)
				{
				$CountryArr[$i]['key']=$CountryValues['cat_id'];
				$CountryArr[$i]['value']=$CountryValues['cat_name'];
				$i++;
				}
			}
		

			
		$catname= new Zend_Form_Element_Select('catname');
		$catname
		->addMultiOptions($CountryArr)
		->addValidator('NotEmpty',true,array('messages' =>'Category Name is required.'))
		->addDecorator('Errors', array('class'=>'error'))
		->setAttrib("class","mws-textinput required")
		->setAttrib("style","width:300px; height:30px;");
		//echo $country_value;die;	
		$catname->setValue($catname_val);
		
			
			
		$question= new Zend_Form_Element_Text('question');
		$question->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Question is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($question_val);
		
		$answer= new Zend_Form_Element_Textarea('answer');
		$answer->setRequired(true)
		->addValidator('NotEmpty',true,array('messages' =>'Answer is required.'))
		->addDecorator('Errors', array('class'=>'errormsg'))
		->setAttrib("class","mws-textinput required")
		->setValue($answer_val);
		
		$this->addElements(array($catname,$question, $answer));
		
		}
		
	}
			
?>
