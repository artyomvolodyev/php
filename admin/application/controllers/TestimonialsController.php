<?php
__autoloadDB('Db');
class TestimonialsController extends AppController
{
	public function indexAction()
	{
		
		global $mySession;
		$db=new Db();
		$testimonialsData=$db->runQuery("select * from ".TESTIMONIALS."");	
		$this->view->sql = $testimonialsData;
			
	}
	
	
	
	public function addtestimonialsAction()
	{
		global $mySession;
		$myform=new Form_Testimonials();
		$this->view->myform=$myform;
		$this->view->pageHeading="Add New testimonial";
	}
	
	
	
	public function savetestimonialsAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Add New testimonials";
		
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Testimonials();			
			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
				$Result=$myObj->SaveTestimonials($dataForm);
				if($Result==1)
				{
					$mySession->errorMsg ="New Testimonial added successfully.";
					$this->_redirect('testimonials/index');
				}
				else
				{
					$mySession->errorMsg ="Testimonial you entered already exists.";
					$this->view->myform = $myform;
					$this->render('addtestimonials');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addtestimonials');
			}
		}
		else
		{			
			$this->_redirect('testimonials/addtestimonials');
		}
	}
	
	
	public function edittestimonialsAction()
	{
		global $mySession;
		$testimonialsId=$this->getRequest()->getParam('testimonialsId'); 
		//echo "testimonial id: ".$testimonialsId; die;
		
		$this->view->testimonialsId=$testimonialsId;
		$myform=new Form_Testimonials($testimonialsId);    
		$this->view->myform=$myform;
		$this->view->pageHeading="Edit testimonials";
	}
	
	public function updatetestimonialsAction()
	{
		global $mySession;
		$db=new Db();
		echo $testimonialsId;
		$testimonialsId=$this->getRequest()->getParam('testimonialsId'); 
		
		$this->view->testimonialsId=$testimonialsId;
		$this->view->pageHeading="Edit testimonials";
		
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Testimonials();			
			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Pages();
			
				$Result=$myObj->UpdateTestimonials($dataForm,$testimonialsId);
				if($Result==1)
				{
					$mySession->errorMsg ="testimonials updated successfully.";
					$this->_redirect('testimonials/index');
				}
				else
				{
				$mySession->errorMsg ="testimonials name you entered already exists.";
				$this->view->myform = $myform;
				$this->render('edittestimonials');
				}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('edittestimonials');
			}
		}
		else
		{			
			$this->_redirect('testimonials/edittestimonials/testimonialsId/'.$testimonialsId);
		}
	}
	
	
	
	
	public function managecategoryAction()
	{
	
		global $mySession;
		$db=new Db();
		$userData=$db->runQuery("select * from ".CATEGORY."");	
		$this->view->sql = $userData;
			
	}
	
	public function deletetestimonialsAction()
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
					$condition1="testimonials_id='".$Id."'"; 
					$db->delete(TESTIMONIALS,$condition1);
				}
			}
		}		
		exit();
			
	}
	
	

}
?>