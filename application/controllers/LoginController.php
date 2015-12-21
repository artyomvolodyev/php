<?php

__autoloadDB('Db');



class LoginController extends AppController

{

	public function indexAction()

	{	

		global $mySession;

		$db=new Db();

		$launch=$this->getRequest()->getParam('l');
		$this->view->l=$launch;
		
		//if($launch!="")
//		{
//			$mySession->errorMsg ="Login First to launch your campaign";
//		}
		//echo $_SERVER['HTTP_REFERER']; die;
		//$var=$this->getRequest()->getParam('teedesign');

		$this->_helper->layout->setLayout('myaccount');
		
		
		//$mySession->errorMsg ="Login First to launch your campaign";

		$myform=new Form_Login();

		$this->view->myform=$myform;

		

		$myformsignup=new Form_Signup();

		$this->view->myformsignup=$myformsignup;

		

	}

	
	public function savedataAction()

	{	

		global $mySession;

		$db=new Db();
		$this->_helper->layout->setLayout('myaccount');
		//prd($_REQUEST);

	//echo "save data action"; die;
		$mySession->camptitl=$_REQUEST['camptitle'];
				
		$mySession->descrip=$_REQUEST['description'];
		$mySession->camplength=$_REQUEST['no_ofdays'];
		$mySession->showurl=$_REQUEST['url'];
		
		$mySession->campaign_category=$_REQUEST['campaign_category'];
		
		$mySession->errorMsg ="Login First to launch your campaign";
		$this->_redirect('login/index/l/launch');
		
	}
	
	public function followAction()

	{	

		global $mySession;

		$db=new Db();
		$this->_helper->layout->setLayout('myaccount');
				

		$mySession->save_login_url=$_SERVER['HTTP_REFERER'];
		
		$mySession->errorMsg ="Login First to follow user";
		$this->_redirect('login/index/l/follow');
		
	}

	public function hireAction()

	{	

		global $mySession;

		$db=new Db();
		$this->_helper->layout->setLayout('myaccount');
				

		$mySession->save_login_url=$_SERVER['HTTP_REFERER'];
		
		$mySession->errorMsg ="Login First to hire user";
		$this->_redirect('login/index/l/hire');
		
	}


	public function forgotpasswordAction()

	{	

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$myform=new Form_Forgotpassword();

		$this->view->myform=$myform;

	}

	

	

	public function resetAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');
		$this->view->pageTitle="Reset Password";
		$requestId = $this->getRequest()->getParam('requestId');		
	//echo "uu".$requestId;die;
		$chkData=$db->runQuery("select * from ".USERS." where pass_reset='".$requestId."'");

	

		if($chkData!="" and count($chkData)>0)

		{	

			$this->view->requestId=$requestId;

			$myform=new Form_Resetpassword();

			$this->view->myform=$myform;

		}

		else

		{

			$this->view->requestId='Expire';

		}	

		

	}

	

	

	public function getpasswordAction()

	{	

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$myform=new Form_Forgotpassword();

		$this->view->myform=$myform;

		

		if ($this->getRequest()->isPost())

		{

			

			$request=$this->getRequest();

			//$myform = new Form_Forgotpassword();			

			

			if ($myform->isValid($request->getPost()))

			{	

			//echo "kk";die;			

				$dataForm = $myform->getValues();

				//prd($dataForm);

				$myObj=new Users();

				

				$Result = $myObj->CheckForgotpass($dataForm);



					if($Result>0)

					{

					$mySession->errorMsg ="Your password reset information has been sent to your email address.";

					/*$mySession->LoggedUserId=$Result;*/

					$this->_redirect('login/index');

					}

					 

					else

					{

					   $mySession->errorMsg ="Email Address you entered is not registerd.";

					//$this->view->myform = $myform1;

						$this->_redirect('login/forgotpassword');

					//$this->render('index');

					}

			}

			

			

			else

			{	

				$this->view->myform= $myform;

				$this->render('forgotpassword');

			}

			

		}

	}

	

	

	public function processresetAction()

	{//echo "=====aa";die;

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		

		$this->view->pageTitle="Reset Password";

		$requestId=$this->getRequest()->getParam('requestId');
		//echo "rrrr".$requestId;die;		

		

		$chkData=$db->runQuery("select * from ".USERS." where pass_reset='".$requestId."'");

		

		if($chkData!="" and count($chkData)>0)

		{//echo "aa";die;

			$this->view->requestId=$requestId;

			

			if($this->getRequest()->isPost())

			{//echo "yy";die;

			$request=$this->getRequest();

			$myform = new Form_Resetpassword();			

			

			

			if ($myform->isValid($request->getPost()))

			{				

				//echo "validd";die;

				$dataForm=$myform->getValues();

				if($dataForm['newpassword']!=$dataForm['cnfrmpassword'])

				{//echo "kk";die;

					$mySession->errorMsg ="New password and confirm new password should be same.";

					$this->view->myform = $myform;

					$this->render('reset');	

				}

				else

				{//echo "ww";die;

					$myObj=new Users();

					$Result=$myObj->ResetNewPassword($dataForm,$requestId);				

					$mySession->errorMsg ="Your new password reset successfully.";

					$this->_redirect('login/index');

				}

			}

			else

			{	

				//echo "not valid";

				$this->view->myform = $myform;

				$this->render('reset');

			}

			}

			else

			{			

			//echo "error";die;

			$this->_redirect('login/reset');

			}

		}

		else

		{//echo "uu";die;

			$this->_redirect('login/reset');

		}

		

	}

	

	

	public function signupedAction()

	{

		//global $testvar;

		global $mySession;

		$launch=$this->getRequest()->getParam('l');
		$this->view->l=$launch;
		$this->_helper->layout->setLayout('myaccount');

		$db=new Db();
		$testvar='abctestvar';
		$this->view->testing=$testvar;
		$myform=new Form_Login();
		$this->view->myform=$myform;


		if ($this->getRequest()->isPost())  //  same as issset post
		{

			$request=$this->getRequest();
			$myformsignup=new Form_Signup();

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
                    } else {
                        $mySession->errorMsg="An Activation link sent to your email address. Please follow the link in the email to verify your email address and activate your account.";
                        //if($launch!="")
//							{
//								$this->_redirect('login/index/l/launch');
//							}
                        $this->_redirect('login/index');
                        //$this->_redirect('myaccount/activecampaign');
                    }
				} else {
					$mySession->errorMsg="Password not identical";
					$this->view->myformsignup=$myformsignup;
					$this->render('index');
				}
			} else {
				$this->view->myformsignup = $myformsignup;
				$this->render('index');
			}

		} else {
			$this->_redirect('login/index');
		}

	}

	

	public function activateAction()
	{

		global $mySession;
		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');
		$this->view->pageTitle="Account Activation";
		$Id= $this->getRequest()->getParam('Id');
	    $chkActivate=$db->runQuery("select * from ".USERS." WHERE md5(user_id)='".$Id."'");		

		if($chkActivate!="" and count($chkActivate)>0)
		{
			if($chkActivate[0]['active_status']=='0')
			{
                $this->view->aStat="1";
                $dataUpdate['active_status']='1';
                $dataUpdate['user_status']='1';
                $conditionUpdate="user_id='".$chkActivate[0]['user_id']."'";
                $db->modify(USERS,$dataUpdate,$conditionUpdate);
			} else {
                 $this->view->aStat="2";
			}
		} else {
			$this->view->aStat="3";
		}

	}

	

	

	public function loginedAction()

	{

		

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$launch=$this->getRequest()->getParam('l');
		$this->view->l=$launch;
		
		//echo "from launch : ".$launch; die;

		$myformsignup=new Form_Signup();

		$this->view->myformsignup=$myformsignup;

		

		if ($this->getRequest()->isPost()) 

		{

			//$abc=$this->getRequest()->getParam('abc');

			$request=$this->getRequest();

			$myform = new Form_Login();	

			

			if ($myform->isValid($request->getPost())) 

			{	

			

				$dataForm=$myform->getValues();

				$qury=$db->runquery("Select * from ".USERS." where emailid='".$dataForm['emailid']."' AND password='".md5($dataForm['pass'])."'");

				//echo "Select * from ".USERS." where emailid='".$dataForm['emailid']."' AND password='".$dataForm['pass']."'"; die;

				if($qury!="" and count($qury)>0)

				{

					if($qury[0]['user_status']==1)

					{

						if($qury[0]['active_status']==1)

						{
							if($mySession->camptitl!="" && $mySession->showurl!="")
							{
								//$mySession->errorMsg ="Login First to launch your campaign";
								//echo "from launch"; die;
								$mySession->TeeLoggedID=$qury[0]['user_id'];
								$this->_redirect('launchcampaign/adddescription/login/login'); 

							} elseif($mySession->save_login_url!='') {
								//echo "normal login"; die;
								$mySession->TeeLoggedID=$qury[0]['user_id'];
	
								$this->_redirect($mySession->save_login_url); 

							}
							else
							{
								//echo "normal login"; die;
								$mySession->TeeLoggedID=$qury[0]['user_id'];
	
								$this->_redirect('myaccount/profile'); 
							}

						}

						else

						{

							$mySession->errorMsg="Activate your account by clicking on the link sent to your mail";

						    $this->_redirect('login/index');

						}

					}

					else

					{

						$mySession->errorMsg="Your Account is inactivated by admin";

						$this->_redirect('login/index');

					}

				}

				else

				{

					$mySession->errorMsg="Invalid Email Address & Password";

					$this->_redirect('login/index');

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

			$this->_redirect('login/index');

		}

	}
	
	
	public function buyAction()
	 {
		 $db=new Db();
		 global $mySession;
		 
		$myform1=new Form_Buy();  
		$this->view->myform1=$myform1;
		
		
		 
	}

}

?>