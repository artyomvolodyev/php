<?php
__autoloadDB('Db');

require_once (dirname (dirname (__FILE__)) . '/../../application/configs/crowdfunding.inc.php');

class SystemController extends AppController
{
	public function configurationAction()
	{ /*
		global $mySession;

		$myform=new Form_Configuration();

		$this->view->myform=$myform;
		$this->view->pageHeading="Configuration";*/
		global $mySession;
		$myform=new Form_Configuration();
		$this->view->myform=$myform;
		 
		 
		//$this->view->pageHeading="Configuration";
//		if ($this->getRequest()->isPost())
//		{
//			$request=$this->getRequest();
//			$myform = new Form_Configuration();			
//			if ($myform->isValid($request->getPost()))
//			{				
//				$dataForm=$myform->getValues();
//				$myObj=new System();
//				$Result=$myObj->SaveConfiguration($dataForm);
//				$mySession->sucessMsg ="Configuration saved successfully.";
//			}
//		}
//		 $this->view->myform = $myform;
	}
	public function saveconfigurationAction()
	{
		global $mySession;
		$db=new Db();
		
		$this->view->pageHeading="Configuration";
		
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Configuration();		
			
			if($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();
				$myObj=new System();
				$Result=$myObj->SaveConfiguration($dataForm);
				$mySession->errorMsg ="Configuration saved successfully.";
				$this->_redirect('system/configuration');
			} else {
				$this->view->myform = $myform;
				$this->render('configuration');
			}
		} else {
			$this->_redirect('system/configuration');
		}
	}

	public function emailtemplatesAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Email Templates";
		
		$qry=$db->runquery("select * from ".EMAIL_TEMPLATES." ");
		$this->view->sql=$qry;  
	}
	
	public function viewnewsletterAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Newsletter";
		$qry=$db->runquery("select * from ".NEWSLETTER." ");
		$this->view->sql=$qry;
	}

	public function sendnewsletterAction()
	{
		global $mySession;
		$db=new Db();
		$r=$this->getRequest()->getParam('r');
		$exp=explode("|",$r);
		for($i=0;$i<count($exp)-1;$i++)
		{
            $emailData=$db->runQuery("select * from ".USERS." where user_id='".$exp[$i]."'");

            $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='1'");
            $messageText=$templateData[0]['email_body'];
            $subject=$templateData[0]['email_subject'];

            $messageText=str_replace("[LOGINNAME]",$emailData[0]['email_address'],$messageText);
            $messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
            SendEmail($emailData[0]['email_address'],$subject,$messageText);
		}
		
		$mySession->errorMsg ="Newsletter has been sent successfully.";
		$this->_redirect('system/viewnewsletter');		
	}

	
	public function addAction()
	{
        global $mySession;
        $myform=new Form_Mailtemplate();
        $this->view->myform=$myform;
        $this->view->pageHeading="Add New Template";
	}

	public function savetemplateAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Add New Template";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Mailtemplate();			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new System();
				$Result=$myObj->SaveTemplate($dataForm);
				if($Result==1)
				{
                    $mySession->errorMsg ="New template added successfully.";
                    $this->_redirect('system/emailtemplates');
				} else {
                    $mySession->errorMsg ="Template name you entered is already exists.";
                    $this->view->myform = $myform;
                    $this->render('add');
				}
			} else {
				$this->view->myform = $myform;
				$this->render('add');
			}
		} else {
			$this->_redirect('system/add');
		}
	}

	public function edittemplateAction()
	{
        global $mySession;
        $db=new Db();
        $templateId=$this->getRequest()->getParam('templateId');
        $this->view->templateId=$templateId;
        if($templateId){
            $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='".$templateId."'");
            if($templateData && count($templateData)){
                $this->view->pageHeading = "Edit - ".$templateData[0]['template_title'];
            }
        }else{
            $this->view->pageHeading = "Edit Template";
        }
        $myform=new Form_Mailtemplate($templateId);
        $this->view->myform=$myform;
    }

	public function updatetemplateAction()
	{
		global $mySession;
		$db=new Db();
		$templateId=$this->getRequest()->getParam('templateId'); 
		$this->view->templateId=$templateId;
		$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='".$templateId."'");
		$this->view->pageHeading="Edit - ".$templateData[0]['template_title'];
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Mailtemplate($templateId);			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new System();
				$Result=$myObj->UpdateTemplate($dataForm,$templateId);
				if(isset($_REQUEST['save_or_send']) && $_REQUEST['save_or_send']=='2')
				{
				    $mySession->errorMsg ="Newsletter successfully saved and sent to all subscribed members.";
				} else {
				    $mySession->errorMsg ="Email Template updated successfully.";
				}
				$this->_redirect('system/emailtemplates');
			} else {
				$this->view->myform = $myform;
				$this->render('edittemplate');
			}
		} else {
			$this->_redirect('system/edittemplate/templateId/'.$templateId);
		}
	}

	

	
	public function deletetemplateAction()
	{
		global $mySession;
		$db=new Db();
		$e_id=$this->getRequest()->getParam('eID'); 
	
		$arrId=explode(",",$e_id);
		
        if(count($arrId)>0)
        {
		
            foreach($arrId as $key=>$value)
            {
			
				$userData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id ='".$value."'");					
				
                if($userData!="" and count($userData)>0)
                {
                    $condition1="template_id ='".$value."'";
                    $db->delete(EMAIL_TEMPLATES,$condition1);
                }
			}
        }
        exit();
	}
		
}

	
	
	
	/*
	
	
	
		public function defaultimagesAction()
	{
		global $mySession;
		$db=new Db();
		$myform=new Form_Defaultimages();
		$this->view->myform=$myform;
		$this->view->pageHeading="Manage Default Images";
		$imagesData=$db->runQuery("select * from ".ADMINISTRATOR." ");
		$this->view->imagesData=$imagesData[0];
	}
	public function savedefaultimagesAction()
	{
		global $mySession;
		$db=new Db();		
		$this->view->pageHeading="Manage Default Images";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Defaultimages();
			if ($myform->isValid($request->getPost()))
			{
				$imagesData=$db->runQuery("select * from ".ADMINISTRATOR." ");				
				$dataForm=$myform->getValues();
				$default_business_image=$imagesData[0]['default_business_image'];
				if($dataForm['default_business_image']!="")
				{
					if($default_business_image!="" && file_exists(SITE_ROOT.'images/businesses/'.$default_business_image))
					{
						unlink(SITE_ROOT.'images/businesses/'.$default_business_image);
					}
					$default_business_image="businessDefault".time().$dataForm['default_business_image'];
					@rename(SITE_ROOT.'images/businesses/'.$dataForm['default_business_image'],SITE_ROOT.'images/businesses/'.$default_business_image);
				}
				
				$default_event_image=$imagesData[0]['default_event_image'];
				if($dataForm['default_event_image']!="")
				{
					if($default_event_image!="" && file_exists(SITE_ROOT.'images/events/'.$default_event_image))
					{
						unlink(SITE_ROOT.'images/events/'.$default_event_image);
					}
					$default_event_image="eventDefault".time().$dataForm['default_event_image'];
					@rename(SITE_ROOT.'images/events/'.$dataForm['default_event_image'],SITE_ROOT.'images/events/'.$default_event_image);
				}
								
				$default_male_image=$imagesData[0]['default_male_image'];
				if($dataForm['default_male_image']!="")
				{
					if($default_male_image!="" && file_exists(SITE_ROOT.'images/profileimgs/'.$default_male_image))
					{
						unlink(SITE_ROOT.'images/profileimgs/'.$default_male_image);
					}
					$default_male_image="maleDefault".time().$dataForm['default_male_image'];
					@rename(SITE_ROOT.'images/profileimgs/'.$dataForm['default_male_image'],SITE_ROOT.'images/profileimgs/'.$default_male_image);
				}
				
				$default_female_image=$imagesData[0]['default_female_image'];
				if($dataForm['default_female_image']!="")
				{
					if($default_female_image!="" && file_exists(SITE_ROOT.'images/profileimgs/'.$default_female_image))
					{
						unlink(SITE_ROOT.'images/profileimgs/'.$default_female_image);
					}
					$default_female_image="femaleDefault".time().$dataForm['default_female_image'];
					@rename(SITE_ROOT.'images/profileimgs/'.$dataForm['default_female_image'],SITE_ROOT.'images/profileimgs/'.$default_female_image);
				}
				
				$default_both_image=$imagesData[0]['default_both_image'];
				if($dataForm['default_both_image']!="")
				{
					if($default_both_image!="" && file_exists(SITE_ROOT.'images/profileimgs/'.$default_both_image))
					{
						unlink(SITE_ROOT.'images/profileimgs/'.$default_both_image);
					}
					$default_both_image="bothDefault".time().$dataForm['default_both_image'];
					@rename(SITE_ROOT.'images/profileimgs/'.$dataForm['default_both_image'],SITE_ROOT.'images/profileimgs/'.$default_both_image);
				}
				
				$dataUpdate['default_business_image']=$default_business_image;
				$dataUpdate['default_event_image']=$default_event_image;
				$dataUpdate['default_male_image']=$default_male_image;
				$dataUpdate['default_female_image']=$default_female_image;
				$dataUpdate['default_both_image']=$default_both_image;
				$db->modify(ADMINISTRATOR,$dataUpdate);
				
				$mySession->errorMsg ="Default images updated successfully.";
				$this->_redirect('system/defaultimages');
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('defaultimages');
			}
		}
		else
		{			
			$this->_redirect('system/defaultimages');
		}
	}
	
	
	
	
	public function manageadAction()
	{ 
		global $mySession;
		$db=new Db();
		$myform=new Form_Managead();
		$this->view->myform=$myform;
		$this->view->pageHeading="Manage Ad";
		$imagesData=$db->runQuery("select * from ".ADMINISTRATOR." ");
       	$default_ad_image=$imagesData[0]['ad_image'];
		$this->view->default_ad_image=$default_ad_image;
	}
	
	public function businesslistingAction()
	{
		global $mySession;
		$db=new Db();		
		$myform=new Form_Businesslisting();
		$this->view->myform=$myform;
		$this->view->pageHeading="Upload Business Listing";		
	}

public function savebusinesslistingAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Save Business Listing";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Businesslisting();			
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();
				$xlsfile=$dataForm['excelfile'];
				$zipfile=$dataForm['zipfile'];
				if($zipfile!="")
				{
					 $zip = new ZipArchive;
					 $res = $zip->open(SITE_ROOT."businesslisting/".$zipfile);
					 if	($res === TRUE) 
					 {
						$zip->extractTo(SITE_ROOT.'businesslisting/temp');
						$zip->close();
						$mySession->errorMsg='Data uploaded successfully';
					 } 
					 else 
					 {
						$mySession->errorMsg='Error in uploading';
					 }
				}
//				error_reporting(1);
				ini_set("memory_limit","2048M");
//				require_once SITE_ROOT.'Excel/reader.php';
//				$data = new Spreadsheet_Excel_Reader();

				include_once(SITE_ROOT.'excel_reader2.php');
				$data = new Spreadsheet_Excel_Reader(SITE_ROOT.'businesslisting/'.$xlsfile);
//				$data->read(SITE_ROOT."businesslisting/".$xlsfile);
//				error_reporting(E_ALL ^ E_NOTICE);
				$SheetNo=0;
				$numRows=$data->rowcount(0);
				$numCols=$data->colcount(0);
			    $COUNTER=0;
				for($i=2;$i<=$numRows;$i++)
				{
					
						  
						  if($data->val($i,1,0)!=''){ $busidata['business_title']=strip_magic_slashes($data->val($i,1,0));}
				  		  if($data->val($i,2,0)!='')
						  {
							  $catename=$data->val($i,2,0);
							   $sql="select * from ".SERVICE_CATEGORIES." where category_name='".$catename."'";
							   $result=$db->runQuery($sql);
							   if(count($result)>0)
								   {
									   $busidata['business_category_id']=$result[0]['cat_id'];
								   }
						  }
					     if($data->val($i,4,0)!=''){
							 $sql="select * from ".COUNTRIES." where country_name='".addslashes($data->val($i,4,0))."'";
							 $result=$db->runQuery($sql);
							 if(count($result)>0)
							 {
							   $busidata['country_id']=$result[0]['country_id'];
							 }
						 }
						 
					     if($data->val($i,5,0)!='' and $busidata['country_id']!='' )
						 {						  
						 	$sql="select * from ".STATE." where country_id= ".$busidata['country_id']." and state_name='".addslashes($data->val($i,5,0))."'";
						 	$result=$db->runQuery($sql);
							 if(count($result)>0)
							 {
								$busidata['state_id']=$result[0]['state_id'] ;
							 }
						 }
				
						 $busidata['date_business_added']=date('Y-m-d H:i:s');
						 if($data->val($i,6,0)!=''){  $busidata['city_name']=$data->val($i,6,0);}
					   
					 	 if($data->val($i,3,0)!=''){$businessImage=time()."_".$data->val($i,3,0);
						 copy(SITE_ROOT."businesslisting/temp/".$data->val($i,3,0),SITE_ROOT.'images/businesses/'.$businessImage);		
						 $busidata['Business_image']= $businessImage; 
						 }
						 
						 if($data->val($i,7,0)!=''){ $busidata['zipcode']=$data->val($i,7,0); }
						 
						 if($data->val($i,8,0)!=''){$busidata['description']=$data->val($i,8,0);}
						 if($data->val($i,9,0)!=''){$busidata['search_keywords']=$data->val($i,9,0);}
						 
						 if( $data->val($i,10,0)!=''){$busidata['address']=$data->val($i,10,0);}
						 
						 if($data->val($i,11,0)!=''){$busidata['phone_number']=$data->val($i,11,0);}
						 
						 if($data->val($i,12,0)!=''){$busidata['email_address']=$data->val($i,12,0);}
						 
						 if($data->val($i,13,0)!=''){$busidata['Website']=$data->val($i,13,0);}
						 
				 		 $myLatLongData=getLatLongFromAddress($busidata['country_id'],$busidata['state_id'],$busidata['city_name'],$busidata['address']);
						 $explode=explode("::",$myLatLongData);
						 $Lat=$explode[0];
						 $Long=$explode[1];
						 if($Lat=='' or $Long=='')
						 { 
							$LatLongData=get_url_contents("http://maps.google.com/maps/geo?q=".urlencode( $busidata['zipcode'])."&output=json");			
							$LatLongData=json_decode($LatLongData);
							
							if(count($LatLongData->Placemark)>0)
							{
								$Lat=$LatLongData->Placemark[0]->Point->coordinates[1];
								$Long=$LatLongData->Placemark[0]->Point->coordinates[0];
							}
						 }
					
						 
						 $busidata['business_lat']=$Lat;
						 $busidata['business_long']=$Long;
						 $busidata['business_status']=1;
						
						$sql="select * from ".SERVICE_BUSINESS." where zipcode='".$busidata['zipcode']."' and LOWER(business_title) like '".strtolower(addslashes($busidata['business_title']))."'";
						
						 $duplicatedata=$db->runQuery($sql);
//						 echo count($duplicatedata); exit();
						 if (count($duplicatedata)>0 && $duplicatedata!="")
						 { 
   						   continue;
						 }
						 else
						 {
//						  echo $sql; exit();
						   $db->save(SERVICE_BUSINESS,$busidata);
						   $COUNTER++;
						 }
						 
						 
					}
					if($COUNTER>0)
					{
  		 	          $mySession->errorMsg=$COUNTER.' Records uploaded successfully';					   
					}
			
				$this->_redirect('system/businesslisting');	
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('businesslisting');
			}
		}
		else
		{			
			$this->_redirect('system/businesslisting');
		}

	}
	
	


	public function updatemanageadAction()
	{
		global $mySession;
		$db=new Db();
		$imagesData=$db->runQuery("select * from ".ADMINISTRATOR." ");
		$default_ad_image=$imagesData[0]['ad_image'];
		$this->view->default_ad_image=$default_ad_image;
		$this->view->pageHeading="Manage Ad";
		if ($this->getRequest()->isPost())
		{
	   	   	$request=$this->getRequest();
			$myform = new Form_Managead();			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$ad_image=$default_ad_image;
				if($default_ad_image!="")
				{
					if($default_ad_image!="")
					{
					unlink(SITE_ROOT.'images/adimgs/'.$default_ad_image);
					}
				copy($_FILES['advertise_image']['tmp_name'],SITE_ROOT.'images/adimage/'.$ad_image);
				$ad_image=$_FILES['advertise_image']['name'];
				}				
		    	
				$myObj=new System();
				$Result=$myObj->Updatemanagead($dataForm,$ad_image);
				$mySession->errorMsg ="Add saved successfully.";
				$this->_redirect('system/managead');
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('managead');
			}
		}
		else
		{			
			$this->_redirect('system/managead');
		}
	}
	
	
	
/*	public function updatemanageadAction()
	{
		global $mySession;
		$db=new Db();		
		$this->view->pageHeading="Manage Ad";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Managead();
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();
				$myObj=new System();
				$Result=$myObj->Updatemanagead($dataForm);
				$mySession->errorMsg ="Default images updated successfully.";
				$this->_redirect('system/managead');			
			}
		}
	
	public function importbusinessAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Import Business Listings";
		$myform=new Form_Importbusiness();
		$this->view->myform=$myform;
	}
	public function processimportAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Import Business Listings";
		if ($this->getRequest()->isPost())
		{
	   	   	$request=$this->getRequest();
			$myform = new Form_Importbusiness();			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$ad_image=$default_ad_image;
				if($default_ad_image!="")
				{
					if($default_ad_image!="")
					{
					unlink(SITE_ROOT.'images/adimgs/'.$default_ad_image);
					}
				copy($_FILES['advertise_image']['tmp_name'],SITE_ROOT.'images/adimage/'.$ad_image);
				$ad_image=$_FILES['advertise_image']['name'];
				}				
		    	
				$myObj=new System();
				$Result=$myObj->Updatemanagead($dataForm,$ad_image);
				$mySession->errorMsg ="Add saved successfully.";
				$this->_redirect('system/managead');
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('managead');
			}
		}
		else
		{			
			$this->_redirect('system/managead');
		}
	}
	
	
	public function indexAction()
	{ 
		global $mySession;
		
		$this->view->pageHeading="index";
	}*/
	
	
	
	?>
	
	
	
	
	
	
	
	
	
