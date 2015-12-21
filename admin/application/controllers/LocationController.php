<?php
__autoloadDB('Db');
class LocationController extends AppController
{
	
	
	public function indexAction()
	{
		
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Country";
		$str1="select * from ".COUNTRIES." order by country_name";
		$sql=$db->runQuery($str1);
		$this->view->sql=$sql;
	}
	
	public function showstateAction()
	{
		
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage State";
		$str1="select * from ".STATE." order by state_name";
		$sql=$db->runQuery($str1);
		$this->view->sql=$sql;
	}
	
	public function showcityAction()
	{
		
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage City";
		$str1="select * from ".CITIES." order by city_name";
		$sql=$db->runQuery($str1);
		$this->view->sql=$sql;
	}
	
	public function addcountryAction()
	{
		//echo "helo"; die; 
		global $mySession; 
	    $db=new Db();
		$this->view->pageHeading="Add New country";
		$myform=new Form_Location("1");
		$this->view->myform=$myform;
	}
	
	public function savecountryAction()
	{
		global $mySession; 
	    $db=new Db();
		$this->view->pageTitle="Add New Country";
		if ($this->getRequest()->isPost())
		{ 
			$request=$this->getRequest();
			$myform = new Form_Location("1");	
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();	
				 	
				$myObj=new Users();
				$Result=$myObj->SaveCountry($dataForm);
				
					if($Result==1)
					{		
					$mySession->sucessMsg ="New Country added successfully.";
					$this->_redirect('location/index');
					}
					else
					{
					$mySession->errorMsg ="Country you have entered already exists.";
					$this->view->myform = $myform;
					$this->render('addcountry');
					}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addcountry');
			}
		}
		else
		{			
			$this->_redirect('location/addcountry');
		}	  	
	}
	
	public function editcountryAction()
	{
	global $mySession;	
	$db=new Db();
	$countryid=$this->getRequest()->getParam('countryid'); 
	$this->view->countryid=$countryid;
	$myform=new Form_Location("1",$countryid);
	$this->view->myform=$myform;
	$this->view->pageTitle="Edit Country";
	//$data=$db->runQuery("select * from ".COUNTRIES." where country_id='".$countryid."'");
//	$this->view->data=$data;
	}
	
	public function updatecountryAction()
	{
		global $mySession;	
		$db=new Db();
		$countryid=$this->getRequest()->getParam('countryid'); 
		$this->view->countryid=$countryid;
		$this->view->pageTitle="Edit Country";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Location("1",$countryid);		
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Users();
				$Result=$myObj->UpdateCountry($dataForm,$countryid);
				if($Result==1)
				{
				$mySession->sucessMsg ="Details updated successfully.";
				$this->_redirect('location/index');
				}
				else
				{	
				}
			}
			else
			{
				$this->view->myform = $myform;
				$this->render('editcountry');
			}
		}
		else
		{
			$this->_redirect('location/editcountry/countryid/'.$countryid);
		}
	}
	
	
	public function editstateAction()
	{
	global $mySession;	
	$db=new Db();
	$stateid=$this->getRequest()->getParam('stateid'); 
	$this->view->stateid=$stateid;
	$myform=new Form_Location("2",0,$stateid);
	$this->view->myform=$myform;
	$this->view->pageTitle="Edit State";
	//$data=$db->runQuery("select * from ".STATE." where state_id='".$stateid."'");
//	$this->view->data=$data;
	}
	
	public function updatestateAction()
	{
		global $mySession;	
		$db=new Db();
		$stateid=$this->getRequest()->getParam('stateid'); 
		$this->view->stateid=$stateid;
		$this->view->pageTitle="Edit State";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Location("2",0,$stateid);		
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Users();
				//prd($dataForm);
				$Result=$myObj->UpdateState($dataForm,$stateid);
				if($Result==1)
				{
				$mySession->sucessMsg ="Details updated successfully.";
				$this->_redirect('location/showstate');
				}
				else
				{	
				}
			}
			else
			{
				$this->view->myform = $myform;
				$this->render('editstate');
			}
		}
		else
		{
			$this->_redirect('location/editstate/stateid/'.$stateid);
		}
	}
	
	
	public function editcityAction()
	{
	global $mySession;	
	$db=new Db();
	$cityid=$this->getRequest()->getParam('cityid'); 
	//echo $cityid; die;
	$this->view->cityid=$cityid;
	$myform=new Form_Location("3",0,0,$cityid);
	$this->view->myform=$myform;
	$this->view->pageTitle="Edit City";
	}
	
	public function updatecityAction()
	{
		global $mySession;	
		$db=new Db();
		$cityid=$this->getRequest()->getParam('cityid'); 
		$this->view->stateid=$cityid;
		$this->view->pageTitle="Edit City";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			//echo $cityid; die;
			$myform = new Form_Location("3",0,0,$cityid);		
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
			
				$myObj=new Users();
				
				$Result=$myObj->UpdateCity($dataForm,$cityid);
				if($Result==1)
				{
				$mySession->sucessMsg ="Details updated successfully.";
				$this->_redirect('location/showcity');
				}
				else
				{	
				}
			}
			else
			{
				$this->view->myform = $myform;
				$this->render('editcity');
			}
		}
		else
		{
			$this->_redirect('location/editcity/cityid/'.$cityid);
		}
	}
	
	
	public function addstateAction()
	{ 
		global $mySession; 
	    $db=new Db();
		$this->view->pageHeading="Add New State";
		$myform=new Form_Location("2");
		$this->view->myform=$myform;
	}
	
	public function savestateAction()
	{
		global $mySession; 
	    $db=new Db();
		$this->view->pageTitle="Add New State";
		
		if ($this->getRequest()->isPost())
		{ 
		
			$request=$this->getRequest();
			$myform = new Form_Location("2");
			
			if ($myform->isValid($request->getPost()))
			{
				
				$dataForm=$myform->getValues();	
				 		
				$myObj=new Users(); 
				$Result=$myObj->SaveState($dataForm);
					if($Result==0)
					{		
						$mySession->sucessMsg ="State You Entered already exists.";
						$this->_redirect('location/showstate');
					}
					if($Result==1)
					{		
						$mySession->sucessMsg ="New State added successfully.";
						$this->_redirect('location/showstate');
					}
			}
			else
			{	
			
				$this->view->myform = $myform;
				$this->render('addstate');
			}
		}
		else
		{			
			$this->_redirect('location/addstate');
		}	  	
	}
	
	public function addcityAction()
	{ 
		global $mySession; 
	    $db=new Db();
		$this->view->pageHeading="Add New City";
		$myform=new Form_Location("3");
		$this->view->myform=$myform;
	}
	
	public function addcityprocessAction()
	{
		//echo "hellooo"; die;
		global $mySession; 
	    $db=new Db();
		
		$this->view->pageTitle="Add New City";
		if ($this->getRequest()->isPost())
		{ 
			$request=$this->getRequest();
			$myform = new Form_Location("3");	
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();	
				 		
				$myObj=new Users(); 
				$Result=$myObj->SaveCity($dataForm);
					if($Result==0)
					{		
					$mySession->sucessMsg ="City you entered already exists.";
					$this->_redirect('location/showcity');
					}
					if($Result==1)
					{		
					$mySession->sucessMsg ="New City added successfully.";
					$this->_redirect('location/showcity');
					}
					
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addcity');
			}
		}
		else
		{			
			$this->_redirect('location/addcity');
		}	  	
	}
	
	
	
	public function editpackageAction()
	{
	global $mySession;	
	$db=new Db();
	$packageid=$this->getRequest()->getParam('packageid');
	$this->view->packageid=$packageid;
	$myform=new Form_Package($packageid);
	$this->view->myform=$myform;
	$this->view->pageTitle="Edit Package";
	$data=$db->runQuery("select * from ".PACKAGES." where package_id='".$packageid."'");
	$this->view->data=$data;
	}
	
	public function updatepackageAction()
	{
		global $mySession;	
		$db=new Db();
		$packageid=$this->getRequest()->getParam('package_id'); 
		$this->view->packageid=$packageid;
		$this->view->pageTitle="Edit Package";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Package($packageid);		
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Package();
				$Result=$myObj->UpdatePackage($dataForm,$packageid);
				if($Result==1)
				{
				$mySession->sucessMsg ="Details updated successfully.";
				$this->_redirect('package/index');
				}
				else
				{	
				}
			}
			else
			{
				$this->view->myform = $myform;
				$this->render('editpackage');
			}
		}
		else
		{
			$this->_redirect('package/editepackage/packageid/'.$packageid);
		}
	}
	
	
	
	
	public function changepackagestatusAction()
		{
			global $mySession;
	  		$db=new Db(); 
			$package_id=$this->getRequest()->getParam('package_id');
	 		$status=$this->getRequest()->getParam('Status');
	 		if($status=='1')
	  			{ 
	  				$status = '0';
	  			}
	 		 else 
	  			{ 
	  				$status = '1';
	  			} 
	 		$data_update['plan_status']=$status; 
	 		$condition="package_id='".$package_id."' ";
	 	    $db->modify(PACKAGES,$data_update,$condition);
	  if($db)
	  {
		  $mySession->sucessMsg ="Status Changed successfully.";
			$this->_redirect('package/index');
	  }
	  exit();
		}
		
	public function deletepackageAction()
	{//echo $_REQUEST['Id']; 
		global $mySession;
		$db=new Db();
		if($_REQUEST['Id']!="")
		{
			$arrId=explode("|",$_REQUEST['Id']);
			if(count($arrId)>0)
			{
				foreach($arrId as $key=>$Id)
				{
					$condition1="package_id='".$Id."'"; 
					$db->delete(PACKAGES,$condition1);
				
				}
			}
		}		
		exit();
	}
	
	public function deletecountryAction()
	{//echo $_REQUEST['Id']; 
		global $mySession;
		$db=new Db();
		if($_REQUEST['Id']!="")
		{
			$arrId=explode("|",$_REQUEST['Id']);
			if(count($arrId)>0)
			{
				foreach($arrId as $key=>$Id)
				{
					$condition1="country_id='".$Id."'"; 
					$db->delete(COUNTRIES,$condition1);
				
				}
			}
		}		
		exit();
	}
	
	public function purchaseAction()
	{
	global $mySession;
	$db=new Db();
	$this->view->pageHeading="Manage Purchase Package";
	$str1="select * from ".PACKAGE_PURCHASE." left join ".PACKAGES." on ".PACKAGES.".package_id=".PACKAGE_PURCHASE.".package_id inner join ".USERS." on  ".USERS.".user_id=  ".				    PACKAGE_PURCHASE.".user_id where ".PACKAGE_PURCHASE.".return_status=1 order by ".PACKAGE_PURCHASE.".purchase_id desc";
	$sql=$db->runQuery($str1);
	$this->view->sql=$sql;	
	}
	}
	?>

	
	
	
