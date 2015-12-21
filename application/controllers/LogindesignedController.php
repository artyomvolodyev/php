<?php
__autoloadDB('Db');
error_reporting(0);

class LogindesignedController extends AppController
{
	public function indexAction()
	{	
		global $mySession;
		$db=new Db();
		$this->_helper->layout()->setLayout('simplecontent');
		
		$myform=new Form_Logindesigned();
		$this->view->myform=$myform;
		
		$myformsignup=new Form_Signupdesigned();
		$this->view->myformsignup=$myformsignup;
	}
	
	
	

    public function signupAction()
	{
	//global $testvar;
		global $mySession;
		$db=new Db();
		
		$testvar='abctestvar';
		$this->view->testing=$testvar;
		
		
		$myform=new Form_Logindesigned();
		$this->view->myform=$myform;
		
		$this->_helper->layout()->setLayout('simplecontent');
		
		
		
		if ($this->getRequest()->isPost())  //  same as issset post
		{
			$request=$this->getRequest();
			$myformsignup=new Form_Signupdesigned();
			
			if ($myformsignup->isValid($request->getPost()))   // required true is checked.
			{ 
			
			    $dataForm=$myformsignup->getValues();
			    $pass=$dataForm['signuppass'];
				$cnfrm=$dataForm['signupcnfrmpass'];
				if($pass==$cnfrm)
				{
				
					$myObj=new Myaccountdb();          // call to model
					$data=$myObj->signupdb($dataForm);
					if($data==0)
					{
						$mySession->errorMsg="Email already Exisis. Enter Valid Email Address";
						$this->view->myformsignup=$myformsignup;
						$this->render('index');
					}
					else
					{
						//$mySession->errorMsg="login successfull";
						$mySession->errorMsg="A Registration Link sent to your Email";
						$this->_redirect('logindesigned/index');
						//$this->_redirect('myaccount/activecampaign');
					}
				}
				else
				{
					$mySession->errorMsg="password not same";
					$this->view->myformsignup=$myformsignup;
					$this->render('index');
				}
			}
			else
			{	
				$this->view->myformsignup = $myformsignup;
				$this->render('index');
			}
		}
		else
		{			
			$this->_redirect('launchcampaign/index');
		}
	}
	
	
	public function loginAction()
	{
		
		global $mySession;
		$db=new Db();
		
		$this->_helper->layout()->setLayout('simplecontent');
		
		$myformsignup=new Form_Signupdesigned();
		$this->view->myformsignup=$myformsignup;
		
		if ($this->getRequest()->isPost()) 
		{
			//$abc=$this->getRequest()->getParam('abc');
			$request=$this->getRequest();
			$myform = new Form_Login();	
			
			if ($myform->isValid($request->getPost())) 
			{	
			
				$dataForm=$myform->getValues();
				$qury=$db->runquery("Select * from ".USERS." where emailid='".$dataForm['emailid']."' AND password='".$dataForm['pass']."'");
				
				if($qury!="" and count($qury)>0)
				{
					if($qury[0]['user_status']==1)
					{
						$mySession->TeeLoggedID=$qury[0]['user_id'];
						//$this->_redirect('launchcampaign/index'); 
						echo "<script>parent.top.location='".APPLICATION_URL."launchcampaign/index';</script>";
						exit();
					}
					else
					{
						$mySession->errorMsg="Your Account is inactivated by admin";
						$this->_redirect('logindesigned/index');
					}
				}
				else
				{
					$mySession->errorMsg="invalid Email Address & Password";
					$this->_redirect('logindesigned/index');
				}
			}
			else
			{	
			
			   //$mySession->errorMsg="form not valid";	
				$this->view->myform = $myform;
				$this->render('index');
			}
		}
		else
		{
			//$mySession->errorMsg="check out";						
			$this->_redirect('logindesigned/index');
		}
	}
	
}
?>