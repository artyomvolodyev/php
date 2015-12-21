<?php
__autoloadDB('Db');
class UsersController extends AppController
{
	public function indexAction()
	{
	global $mySession;
	$db=new Db();
	
	$this->view->pageHeading="Manage Users";
	
	$sql=$db->runQuery("select * from ".USERS."");
		
	$this->view->sql=$sql;		
	//prd($)
	}

	public function contactAction()
	{
	global $mySession;
	$db=new Db();
	
	$this->view->pageHeading="Contact Sellers [Who launched the campaign]";
	
	$sql=$db->runQuery("select DISTINCT u.* from ".USERS." u INNER JOIN launchcampaign ON launchcampaign.user_id=u.user_id");
		
	$this->view->sql=$sql;		
	//prd($)
	}

	public function buyersAction()
	{
	global $mySession;
	$db=new Db();
	
	$this->view->pageHeading="Contact Buyers";
	
	$sql=$db->runQuery("select * from ".BUYERS);
		
	$this->view->sql=$sql;		
	//prd($)
	}


	public function sendmailAction()
	{
		global $mySession;
		$db=new Db();
	
		$users=array();
		if(!empty($_REQUEST['allusers'])) {
			//process all users
			$sql=$db->runQuery("select * from ".USERS."");
			for($i=0;$i<count($sql);$i++) {
				$temp=array();
				$user_id=$sql[$i]['user_id'];
				$temp['email']=$sql[$i]['emailid'];
				$temp['name']=$sql[$i]['public_name'];				
				$users[]=$temp;
			}

		} else {
			$mailArray=$_REQUEST['rightValues'];
			if(!empty($mailArray)) {
				for($i=0;$i<count($mailArray);$i++) {
					$str=explode("::",$mailArray[$i]);
					$temp=array();				
					$temp['email']=$str[0];
					$temp['name']=$str[2];				
					$users[]=$temp;					
				}

			}
		}

		//send mails
		if(!empty($users)) {
			
			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='20'");
			$messageText=$templateData[0]['email_body'];
			$subject=$templateData[0]['email_subject'];
			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
			$messageText=str_replace("[SITEURL]",APPLICATION_URL,$messageText);

			foreach($users as $user) {
				if(!empty($user['name'])) {
					$message=str_replace("[NAME]",$user['name'],$messageText);
				} else {
					$message=str_replace("[NAME]",$user['email'],$messageText);

				}

				
				$to=$user['email'];
			//	SendEmail($to,$subject,$message);
			}
			$mySession->errorMsg ="Mails has been sent successfully.";
		}			
		$this->_redirect('users/contact');
	}
	
	public function csendmailAction()
	{
		global $mySession;
		$db=new Db();
	
		$users=array();
		if(!empty($_REQUEST['allusers'])) {
			//process all users
			$sql=$db->runQuery("select * from ".BUYERS."");
			for($i=0;$i<count($sql);$i++) {
				$temp=array();
				$user_id=$sql[$i]['user_id'];
				$temp['email']=$sql[$i]['email'];
				$temp['name']=$sql[$i]['name'];				
				$users[]=$temp;
			}

		} else {
			$mailArray=$_REQUEST['rightValues'];
			if(!empty($mailArray)) {
				for($i=0;$i<count($mailArray);$i++) {
					$str=explode("::",$mailArray[$i]);
					$temp=array();				
					$temp['email']=$str[0];
					$temp['name']=$str[2];				
					$users[]=$temp;					
				}

			}
		}

		//send mails
		if(!empty($users)) {
			
			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='21'");
			$messageText=$templateData[0]['email_body'];
			$subject=$templateData[0]['email_subject'];
			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
			$messageText=str_replace("[SITEURL]",APPLICATION_URL,$messageText);

			foreach($users as $user) {
				if(!empty($user['name'])) {
					$message=str_replace("[NAME]",$user['name'],$messageText);
				} else {
					$message=str_replace("[NAME]",$user['email'],$messageText);

				}

				
				$to=$user['email'];
				SendEmail($to,$subject,$message);
			}
			$mySession->errorMsg ="Mails has been sent successfully.";
		}			
		$this->_redirect('users/buyers');
	}

	public function deleteuserAction()
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
					$condition1="user_id='".$Id."'"; 
					$db->delete(USERS,$condition1);
				}
			}
		}		
		exit();
	}
	
	
	public function changeuserstatusAction()
		{
			 global $mySession;
	  $db=new Db(); 
	  $utype=$this->getRequest()->getParam('utype');
	  $BcID=$this->getRequest()->getParam('userId');
	  $status=$this->getRequest()->getParam('Status');
	  
	  	
	  if($status=='1')
	  { 
	   
	  $status = '0';
	  }
	  else 
	  { 
	 
	  $status = '1';
	  } 
	 $data_update['user_status']=$status; 
	 $condition="user_id='".$BcID."' ";
	  $db->modify(USERS,$data_update,$condition);
	  
	  if($db)
	  {
		  $mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('users/index/uType/'.$utype);
			
	  }
	  
	    
	  exit();
		
			
		}
	
	
	

}
?>