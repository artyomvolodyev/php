<?php

__autoloadDB('Db');



class IndexController extends AppController
{

	public function indexAction()
	{

//		$this->_helper->layout()->setLayout('simplecontent');

	}


	public function supportAction()
	{
		//prd('hello');
		//$this->_helper->layout()->setLayout('simplecontent');
	}

	public function fundedAction()
	{
        $category=$this->getRequest()->getParam('category');
		$this->view->category=$category;
		//prd('hello');
		//$this->_helper->layout()->setLayout('simplecontent');
	}

	public function logoutAction()
    {
		global $mySession;
		$mySession->TeeLoggedID="";
		unset($mySession->TeeLoggedID);

        $defaultSession = Zend_Session::namespaceGet('default');
        foreach($defaultSession AS $key => $val){
            if($key !== 'adminId'){
                unset($mySession->$key);
            }
        }

        $mySession->errorMsg="You Have Logged Out Successfully.";
		$this->_redirect(APPLICATION_URL.'index/index');
	}



	public function showpaydetailAction()
	{
		global $mySession;
		$this->_helper->layout->setLayout('myaccount');
		echo "Here Are pay details:";
		echo '<form method=post action="https://www.sandbox.paypal.com/cgi-bin/webscr">';
		echo '<input type="hidden" name="cmd" value="_notify-synch">';
		echo '<input type="hidden" name="tx" value="'.$_GET['tx'].'">';
		echo '<input type="hidden" name="at" value="O3SpgShdVMKSFVsXuRIywBKIEovOgzaPPMgi_HgFF329zImkvp1ya0fXBki">';
	//	echo '<input type="hidden" name="business" value="techbizsol@gmail.com">';
		echo '<input type="hidden" name="return" id="return" value="'.APPLICATION_URL.'payment/success/Return/1/detail/detail1"/>';
		echo '<input type="hidden" name="cancel_return" value="'.APPLICATION_URL.'payment/success/Return/0/detail/detail0">';
		echo '<input type="submit" value="PDT">';
		echo '</form>';
	}



}

?>