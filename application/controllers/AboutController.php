<?php
__autoloadDB('Db');
class AboutController extends AppController
{
	
	public function indexAction()
	{
		global $mySession;
		$db=new Db();
		$this->_helper->layout->setLayout('myaccount');
		$page=$this->getRequest()->getParam('page');
		$this->view->page=$page;
		$qry="select * from ".PAGES." WHERE page='".$page."'";  
		
		$sql=$db->runquery($qry);
		//prd($sql);
		$this->view->sql=$sql;
	}
	
	
}