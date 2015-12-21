<?php
__autoloadDB('Db');
class DashboardController extends AppController
{
	public function indexAction()
	{
		global $mySession;
		$db=new Db();
	}

	public function descindexAction()
	{
	global $mySession;
	$db=new Db();
	$this->view->pageTitle="Site Description";
	$Data=$db->runQuery("select * from ".SITE_DESC." where desc_id='1'");
	$this->view->Data=$Data;
	}
	public function editdescAction()
	{
	global $mySession;
	$db=new Db();
	$this->view->pageTitle="Edit Description";
	$desc_id=$this->getRequest()->getParam('desc_id'); 
	$this->view->desc_id=$desc_id;
	//$userData=$db->runQuery("select * from ".JOBCATEGORY." where cat_id='".$catId."'");
	$myform=new Form_Sitedesc($desc_id);
	$this->view->myform=$myform;
	}
	public function updatedescAction()
	{
		global $mySession;
		$db=new Db();
		$desc_id=$this->getRequest()->getParam('desc_id'); 
		$this->view->desc_id=$desc_id;
		$this->view->pageTitle="Edit job region";

		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform=new Form_Sitedesc($desc_id);
			$dataForm=$myform->getValues();
			if ($myform->isValid($request->getPost()))
			{ 		
					$dataForm=$myform->getValues();

					$myObj=new Sitedesc();
					$Result=$myObj->Updatedesc($dataForm,$desc_id);
					if($Result>0)
					{
					$mySession->errorMsg ="Site Description updated successfully.";
					$this->_redirect('dashboard/descindex');
					}
					/*else
					{
					$mySession->errorMsg ="Region Name you entered is already exists.";
					$this->view->myform = $myform;
					$this->render('edit');
					}*/
				}
			else
			{	
				$this->view->myform = $myform;
				$this->render('editdesc');
			}
		}
		else
		{			
			$this->_redirect('dashboard/descindex');
		}
	}
	
}
?>