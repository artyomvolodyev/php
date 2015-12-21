<?php
__autoloadDB('Db');
class CampaignController extends AppController
{

	public function indexAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Campaign";
		$qry="select * from ".LAUNCHCAMPAIGN." where draft_status='1'";
		$sql=$db->runquery($qry);
		$this->view->sql=$sql;
	}


	public function liveAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Live Campaign";
		//("SELECT * , date_add( launch_date, INTERVAL campaign_length DAY ) AS endData FROM ".LAUNCHCAMPAIGN." WHERE (campaign_status = '1' AND draft_status='1') and (goal!=sold) ORDER BY endData DESC");
		//echo ("SELECT * , date_add( launch_date, INTERVAL campaign_length DAY ) AS endData FROM ".LAUNCHCAMPAIGN." WHERE (campaign_status = '1' AND draft_status='1')  ORDER BY endData DESC");
		$qry=("SELECT * , date_add( launch_date, INTERVAL campaign_length DAY ) AS endData FROM ".LAUNCHCAMPAIGN." WHERE (campaign_status = '1' AND draft_status='1') AND date_add( launch_date, INTERVAL campaign_length
DAY ) >= CURDATE( )  ORDER BY endData DESC");
		//prd($qry);
		//$qry="select * from ".LAUNCHCAMPAIGN." where campaign_status='1' and draft_status='1'";

		$sql=$db->runquery($qry);
		$this->view->sql=$sql;

	}

	public function successAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage Successfull Campaign";

		//echo "select * from ".LAUNCHCAMPAIGN." where (campaign_status='0' and draft_status='1') and (goal=sold or sold>goal)"; die;

		//$qry="select * from ".LAUNCHCAMPAIGN." where  (goal=sold or sold>goal)  and goal<>0 ";
		$qry = '
			SELECT
				' . LAUNCHCAMPAIGN . '.*,
				' . USERS . '.public_name AS uname,
				' . USERS . '.emailid AS uemail,
				(
					SELECT
						SUM(preapprovals_share_user)
					FROM
						preapprovals
					WHERE
						preapprovals_campaign=' . LAUNCHCAMPAIGN . '.campaign_id
						AND preapprovals_status="collected"
				) AS ushare,
				(
					SELECT
						SUM(preapprovals_qty)
					FROM
						preapprovals
					WHERE
						preapprovals_campaign=' . LAUNCHCAMPAIGN . '.campaign_id
						AND preapprovals_status!="collected"
				) AS ucancelled
			FROM
				' . LAUNCHCAMPAIGN . '
				LEFT JOIN ' . USERS . ' ON ' . USERS . '.user_id=' . LAUNCHCAMPAIGN . '.user_id
			WHERE
				(' . LAUNCHCAMPAIGN . '.goal=' . LAUNCHCAMPAIGN . '.sold OR ' . LAUNCHCAMPAIGN . '.sold>' . LAUNCHCAMPAIGN . '.goal) AND ' . LAUNCHCAMPAIGN . '.goal<>0
		';
		//echo "select * from ".LAUNCHCAMPAIGN." where (campaign_status='0' and draft_status='1') and (goal=sold or sold>goal)"; die;
		//echo $qry;
		$sql=$db->runquery($qry);
		$this->view->sql=$sql;

	}

	public function profitAction()
	{

		$db=new Db();
		$this->_helper->layout->setLayout('simplecontent');
		$id=$this->getRequest()->getParam("id");
		$this->view->id=$id;
		$sql=$db->runquery("select title from ".LAUNCHCAMPAIGN." where campaign_id='".$id."' ");
		$this->view->pageHeading="Enter Profit For ".$sql[0]['title']." Campaign";
	}

	public function saveprofitAction()
	{
		$db=new Db();
		$this->_helper->layout->setLayout('simplecontent');
		$id=$this->getRequest()->getParam("id");
		$this->view->id=$id;

		if($_REQUEST['profit']!="")
		{
			$dataInsert['profit']=$_REQUEST['profit'];
			$condition="campaign_id='".$id."'";
			$db->modify(LAUNCHCAMPAIGN,$dataInsert,$condition);
		}
			$mySession->errorMsg ="Profit Added Successfully.";
			echo "<script>parent.top.location='".APPLICATION_URL_ADMIN."campaign/success';</script>";
			exit();

	}

	public function unsuccessAction()
	{
		global $mySession;
		$db=new Db();
		$this->view->pageHeading="Manage UnSuccessfull Campaign";
		//$qry="select * from ".LAUNCHCAMPAIGN." where (campaign_status='0' and draft_status='1') and (goal!=sold and sold < goal)";
		$qry=("SELECT * , date_add( launch_date, INTERVAL campaign_length DAY ) AS endData FROM ".LAUNCHCAMPAIGN." WHERE  ((goal!=sold and sold < goal) or goal=0) AND launch_date <> '0000-00-00 00:00:00'  ORDER BY launch_date DESC");

		//echo "select * from ".LAUNCHCAMPAIGN." where (campaign_status='0' and draft_status='1') and (goal!=sold and sold < goal)"; die;
		$sql=$db->runquery($qry);
		$this->view->sql=$sql;
	}

	public function bookingxlsAction()
	{
		global $mySession;
		$db=new Db();
		$name=$this->getRequest()->getParam('name');

		$qry="select * from ".ORDER_RECORD." where teeurl='".$name."'";

		$sql=$db->runquery($qry);
		$this->view->sql=$sql;
	}


	public function emailfriendsAction()
	{
		global $mySession;
		$db=new Db();

		$this->view->pageHeading="Email Buyers";

		$name=$this->getRequest()->getParam('name');
		$this->view->name=$name;
		//echo "url is : ".$name; die;
		//$cid=$this->getRequest()->getParam('cid');
		//$cid->view->myform=$cid;

		$myform=new Form_Emailfriends($name);

		$this->view->myform=$myform;
	}

	public function emailitAction()
	{
		global $mySession;
		//$this->_helper->layout->setLayout('myaccount');
		$db=new Db();


		$myform=new Form_Emailfriends();
		$this->view->myform=$myform;

		if ($this->getRequest()->isPost())
		{

			$request=$this->getRequest();
			$myform=new Form_Emailfriends();

			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();
				//prd($dataForm);
				$friendsemailid = explode(",",$dataForm['friendsemailid']);

					foreach($friendsemailid as $key1=>$value1)
					{

						if(trim($value1)!="")
						{

							if(!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$/', trim($value1)))
							{

 								$mySession->errorMsg="Enter a valid email address";
								$this->_redirect('campaign/emailfriends');
							}
						}

					}

				$myObj=new Campaigndb();
				$data=$myObj->emailfriends($dataForm);

				if($data==1)
				{
						$mySession->errorMsg="Email sent to buyers";
						$this->_redirect('campaign/live');
				}
			}
			else
			{

				$this->view->myform = $myform;
				$this->render('emailfriends');
			}
		}
		else
		{
			$this->_redirect('campaign/emailfriends');
		}
	}



	public function deletecampAction()
	{
		global $mySession;
		$db=new Db();
		if($_REQUEST['Id']!="")
		{
			//echo "delete camp id : ".$_REQUEST['Id']; die;

			$arrId=explode("|",$_REQUEST['Id']);
			if(count($arrId)>0)
			{
				foreach($arrId as $key=>$Id)
				{
					//echo "a=".$Id;
					$condition1="campaign_id='".$Id."'";
					//echo $condition1;
					$db->delete(LAUNCHCAMPAIGN,$condition1);

					$db->delete(DRAFTS,$condition1);
					//$data_update['resume_status']=1;
					//$db->modify(DRAFTS,$data_update,$condition1);
				}
			}
		}
		exit();
	}


	public function viewAction()
	{
		global $mySession;
		$db=new Db();

		$campid=$this->getRequest()->getParam('campid');

		$this->view->campid=$campid;
	}


	public function orderrecordsAction()
	{
		global $mySession;
		$db=new Db();
		$name=$this->getRequest()->getParam('name');
		//echo "select * from ".ORDER_RECORD." where teeurl='".$name."'"; die;
		$qry="select * from ".ORDER_RECORD." where teeurl='".$name."'";

		$sql=$db->runquery($qry);
		$this->view->sql=$sql;
		//$campid=$this->getRequest()->getParam('campid');

		//$this->view->campid=$campid;
	}



	public function changestatusAction()
    {
	  global $mySession;
	  $db=new Db();

	  $BcID=$this->getRequest()->getParam('campId');
	  $status=$this->getRequest()->getParam('Status');


	  if($status=='1')
	  {

	  $status = '0';
	  }
	  else
	  {

	  $status = '1';
	  }
	  $data_update['admin_status']=$status;
	  $condition="campaign_id='".$BcID."' ";
	  $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

	  if($db)
	  {
		  	$mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('campaign/live');

	  }

	  exit();

}

public function sliderstatusAction()
    {
	  global $mySession;
	  $db=new Db();

	  $BcID=$this->getRequest()->getParam('campId');
	  $status=$this->getRequest()->getParam('Status');


	  if($status=='1')
	  {

	  $status = '0';
	  }
	  else
	  {

	  $status = '1';
	  }
	  $data_update['slider_status']=$status;
	  $condition="campaign_id='".$BcID."' ";
	  $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

	  if($db)
	  {
		  	$mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('campaign/success');

	  }

	  exit();

}


public function slidstatus()
    {
	  global $mySession;
	  $db=new Db();

	  $BcID=$this->getRequest()->getParam('campId');
	  $status=$this->getRequest()->getParam('Status');


	  if($status=='1')
	  {

	  $status = '0';
	  }
	  else
	  {

	  $status = '1';
	  }
	  $data_update['slider_status']=$status;
	  $condition="campaign_id='".$BcID."' ";
	  $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

	  if($db)
	  {
		  	$mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('campaign/live');

	  }

	  exit();

}


public function processorderAction()
    {
	  global $mySession;
	  $db=new Db();

	  $BcID=$this->getRequest()->getParam('campId');
	  $status=$this->getRequest()->getParam('Status');


	  if($status=='1')
	  {

	  $status = '0';
	  }
	  else
	  {

	  $status = '1';
	  }
	  $data_update['order_process']=$status;
	  $condition="campaign_id='".$BcID."' ";
	  $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

	  if($db)
	  {
		  	$mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('campaign/success');

	  }

	  exit();

}


}
?>
