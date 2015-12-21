<?php
__autoloadDB('Db');
class PagesController extends AppController
{
	public function indexAction()
	{
		
	global $mySession;
	$db=new Db();
	$this->view->pageHeading="Manage Pages";
	$qry="select * from ".PAGES." WHERE page_id<>'17' && page_id<>'18' ";  
	$sql=$db->runquery($qry);
	$this->view->sql=$sql;
	}
	
	
	public function addAction()
	{
		global $mySession;
		$myform=new Form_Pages();
		$this->view->myform=$myform;
		$this->view->pageHeading="Add New Page";
	}
	
	public function addcategoryAction()
	{
		global $mySession;
		$myform=new Form_Category();
		$this->view->myform=$myform;
		$this->view->pageHeading="Add New Category";
	}
	
	
	public function addquestionsAction()
	{
		global $mySession;
		$catId=$this->getRequest()->getParam('catId'); 
		$myform=new Form_Question(0,$catId);
		$this->view->myform=$myform;
		$this->view->pageHeading="Add New Question";
	}
	
	
	public function savepageAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Add New Page";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Pages();			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->SavePage($dataForm);
				if($Result==1)
				{
				$mySession->errorMsg ="New page added successfully.";
				$this->_redirect('pages/index');
				}
				else
				{
				$mySession->errorMsg ="Page name you entered is already exists.";
				$this->view->myform = $myform;
				$this->render('add');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('add');
			}
		}
		else
		{			
			$this->_redirect('pages/add');
		}
	}
	
	
	public function savecategoryAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Add New Category";
		
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Category();			
			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->SaveCategory($dataForm);
				if($Result==1)
				{
					$mySession->errorMsg ="New Category added successfully.";
					$this->_redirect('pages/managecategory');
				}
				else
				{
					$mySession->errorMsg ="Category you entered already exists.";
					$this->view->myform = $myform;
					$this->render('addcategory');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addcategory');
			}
		}
		else
		{			
			$this->_redirect('pages/addcategory');
		}
	}
	
	
	public function savequestionAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Add New Question";
		
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Question();			
			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->SaveQuestion($dataForm, $catId);
				if($Result==1)
				{
					$mySession->errorMsg ="New Question added successfully.";
					$this->_redirect('pages/managecategory');
				}
				else
				{
					$mySession->errorMsg ="Question you entered already exists.";
					$this->view->myform = $myform;
					$this->render('addquestions');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addquestions');
			}
		}
		else
		{			
			$this->_redirect('pages/addquestions');
		}
	}
	
	
	
	public function editAction()
	{
		global $mySession;
		$pageId=$this->getRequest()->getParam('pageId'); 
		$this->view->pageId=$pageId;
		$myform=new Form_Pages($pageId);
		$this->view->myform=$myform;
		$this->view->pageHeading="Edit Page";
	}
	
	
	public function seequestionsAction()
	{
		global $mySession;
		$db=new Db();
		$catId=$this->getRequest()->getParam('catId');
		$this->view->catId=$catId;
		$userData=$db->runQuery("select * from ".QUESTIONS." where cat_id='".$catId."'");	
		$this->view->sql = $userData;
			
	}
	
	
	public function updatepageAction()
	{
		global $mySession;
		$db=new Db();
		$pageId=$this->getRequest()->getParam('pageId'); 
		$this->view->pageId=$pageId;
		$this->view->pageHeading="Edit Page";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Pages($pageId);			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->UpdatePage($dataForm,$pageId);
				if($Result==1)
				{
				$mySession->errorMsg ="Page details updated successfully.";
				$this->_redirect('pages/index');
				}
				else
				{
				$mySession->errorMsg ="Page name you entered is already exists.";
				$this->view->myform = $myform;
				$this->render('edit');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('edit');
			}
		}
		else
		{			
			$this->_redirect('pages/edit/pageId/'.$pageId);
		}
	}
	
	
	public function updatecategoryAction()
	{
		global $mySession;
		$db=new Db();
		$catId=$this->getRequest()->getParam('catId'); 
		$this->view->catId=$catId;
		$this->view->pageHeading="Edit Category";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Category($catId);			
			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
			
				$Result=$myObj->UpdateCategory($dataForm,$catId);
				if($Result==1)
				{
					$mySession->errorMsg ="Category updated successfully.";
					$this->_redirect('pages/managecategory');
				}
				else
				{
				$mySession->errorMsg ="Category name you entered already exists.";
				$this->view->myform = $myform;
				$this->render('editcategory');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('editcategory');
			}
		}
		else
		{			
			$this->_redirect('pages/editcategory/catId/'.$catId);
		}
	}
	
	
	public function updatequestionAction()
	{
		global $mySession;
		$db=new Db();
		$quesId=$this->getRequest()->getParam('quesId'); 
		$catId=$this->getRequest()->getParam('catId'); 
		
		$this->view->quesId=$quesId;
		$this->view->pageHeading="Edit Question";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			
			
			
			$myform = new Form_Question($quesId);	
				
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->UpdateQuestion($dataForm,$quesId);
				if($Result==1)
				{
					$mySession->errorMsg ="Question updated successfully.";
					$this->_redirect('pages/seequestions/catId/'.$catId);
				}
				else
				{
				$mySession->errorMsg ="Question you entered already exists.";
				$this->view->myform = $myform;
				$this->render('editquestion');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('editquestion');
			}
		}
		else
		{			
			$this->_redirect('pages/editquestion/quesId/'.$quesId);
		}
	}
	
	
	public function managecategoryAction()
	{
	
		global $mySession;
		$db=new Db();
		$userData=$db->runQuery("select * from ".CATEGORY."");	
		$this->view->sql = $userData;
			
	}
	
	public function deletecategoryAction()
	{
	
		global $mySession;
		$db=new Db();
		if($_REQUEST['Id']!="")
		{
			$arrId=explode("|",$_REQUEST['Id']);
			if(count($arrId)>0)
			{
				foreach($arrId as $key=>$Id)
				{
					$condition1="cat_id='".$Id."'"; 
					$db->delete(CATEGORY,$condition1);
				}
			}
		}		
		exit();
			
	}
	
	public function deletequestionAction()
	{
	
		global $mySession;
		$db=new Db();
		if($_REQUEST['Id']!="")
		{
			$arrId=explode("|",$_REQUEST['Id']);
			if(count($arrId)>0)
			{
				foreach($arrId as $key=>$Id)
				{
					$condition1="ques_id='".$Id."'"; 
					$db->delete(QUESTIONS,$condition1);
				}
			}
		}		
		exit();		
	}
	
	
	public function editcategoryAction()
	{
		global $mySession;
		$catId=$this->getRequest()->getParam('catId'); 
		$this->view->catId=$catId;
		$myform=new Form_Category($catId);    
		$this->view->myform=$myform;
		$this->view->pageHeading="Edit Category";
	}
	
	public function editquestionAction()
	{
		global $mySession;
		$quesId=$this->getRequest()->getParam('quesId'); 
	    $catId=$this->getRequest()->getParam('catId'); 
		
		$this->view->quesId=$quesId;
		$this->view->catId=$catId;
		
		$myform=new Form_Question($quesId); 
		
		$this->view->myform=$myform;
		$this->view->pageHeading="Edit Question";
	}
	
	
	public function deletepagesAction()
	{
		global $mySession;
		$db=new Db();
		$p_id=$this->getRequest()->getParam('pID'); 
	
		$arrId=explode(",",$p_id);
		
		
			if(count($arrId)>0)
			{
		
			foreach($arrId as $key=>$value)
				{
			
				$userData=$db->runQuery("select * from ".PAGES." where page_id ='".$value."'");					
				
					if($userData!="" and count($userData)>0)
					{
					$condition1="page_id ='".$value."'"; 
					$db->delete(PAGES,$condition1);				
					}
				}
			}
			exit();
	 }
	 
	 
	

}
?>