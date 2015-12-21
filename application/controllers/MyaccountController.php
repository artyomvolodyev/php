<?php

__autoloadDB('Db');

class MyaccountController extends AppController

{



	public function indexAction()

	{

		global $mySession;

		$this->_helper->layout->setLayout('myaccount');

		$db=new Db();

	}



	public function activecampaignAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$userid=$mySession->TeeLoggedID;





		//pr("select * from ".LAUNCHCAMPAIGN." WHERE user_id='".$userid."' and campaign_status='1'");

		//prd('');





		$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE user_id='".$userid."' and campaign_status='1'");





		//$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE user_id='".$userid."' and campaign_status='1'");



		 //prd($Data);

		 if($Data!="" and count($Data)>0)

		 {

			 $this->view->Data=$Data;





		//$purdate=explode(" ",$Data[0]['launch_date']);



		//$row1=$db->runQuery("select DATE_ADD('".$Data[0]['launch_date']."', INTERVAL '".$Data[0]['campaign_length']."' DAY) AS nextDate,DATE_ADD('".$Data[0]['launch_date']."', INTERVAL -1 DAY) AS prevDate");

//

//		$enddate=$row1[0]['nextDate'];

//

//		echo "end date is:".$enddate;

//

//		$newdat2=explode(" ",$enddate);

//		$newdat3=explode("-",$newdat2[0]);                            // comes in Y-M-D format

//		$newdat4=$newdat3[2]."/".$newdat3[1]."/".$newdat3[0];                // D/m/Y format

//		$this->view->newdat4=$newdat4;

//

//		$diff = strtotime($enddate) - strtotime(date('Y-m-d H:i:s'));

//

//		$day_difference = floor($diff / (60*60*24));

//

//		$this->view->day_difference=$day_difference;

//

//		echo "............day diffrenc is:".$day_difference;

//

//		if(count($Data)>0)

//		{

//			if($day_difference<=0)

//			{

//				//$this->view->day_difference=$day_difference;

//				$dataUpdate['campaign_status']=0;

//				$conditionUpdate="user_id='".$userid."'";

//				$db->modify(LAUNCHCAMPAIGN,$dataUpdate,$conditionUpdate);

//

//			}

//			else

//			{

//

//				 $years = floor($diff / (365*60*60*24));

// 				 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

//				 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

// 				 $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24- $days* 60*60*24)/(60*60));

//

//			}

//		}





	    }

		else

		{

			$nocamp='nocampaign';

			$this->view->nocamp=$nocamp;



		}

}









	public function endedcampaignAction()

	{

		global $mySession;

		$db=new Db();



		$this->_helper->layout->setLayout('myaccount');



		$userid=$mySession->TeeLoggedID;



		//$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE user_id='".$userid."' and campaign_status='1'");



		$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." WHERE user_id='".$userid."' and campaign_status='0' and draft_status='1'");



		 //prd($Data);

		 if($Data!="" and count($Data)>0)

		 {

			 $this->view->Data=$Data;

	     }

		else

		{

			$nocamp='nocampaign';

			$this->view->nocamp=$nocamp;



		}

	}









	public function draftsAction()

	{

		global $mySession;

		$db=new Db();



		$this->_helper->layout->setLayout('myaccount');



		$Data=$db->runquery("select * from ".LAUNCHCAMPAIGN." where user_id='".$mySession->TeeLoggedID."' and (title='' || description='' || campaign_length='' || url='') and draft_status='0'");



		//echo "select * from ".LAUNCHCAMPAIGN." where user_id='".$mySession->TeeLoggedID."' and (title='' || description='' || campaign_length='' || url='') and draft_status='0'"; die;





		if($Data!="" and count($Data)>0)

		 {

			 $this->view->Data=$Data;

	     }

		else

		{

			$nocamp='nocampaign';

			$this->view->nocamp=$nocamp;



		}



	}





	public function deleteAction()

	{

		global $mySession;

		$db=new Db();



		$this->_helper->layout->setLayout('myaccount');
		$cid=$this->getRequest()->getParam('cid');

		//echo "camp id for deleting : ".$cid; die;

		$condition="campaign_id='".$cid."'";

		$db->delete(DRAFTS,$condition);



		$db->delete(LAUNCHCAMPAIGN,$condition);



		$mySession->errorMsg="Draft Deleted";

		$this->_redirect('myaccount/drafts');



	}





	public function endAction()

	{

		global $mySession;

		$db=new Db();



		$this->_helper->layout->setLayout('myaccount');

		$cid=$this->getRequest()->getParam('cid');



		$this->view->cid=$cid;

	}



	public function endprocessAction()

	{

		global $mySession;

		$db=new Db();

		$cid=$this->getRequest()->getParam('cid');

		// X004 releasing the finds
		require_once (dirname (dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
		require_once (dirname (dirname (__FILE__)) . '/crowdfunding/Crowdfunding.php');

		$crowdfunding = new Crowdfunding ();
		$crowdfunding->end_campaign (array("cid" => $cid));
		// X004 END

		$data_update['campaign_status']=0;

	    $data_update['campaign_length']=0;



		$condition="campaign_id='".$cid."'";

		$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

		$mySession->errorMsg="Campaign Ended";

		$this->_redirect('myaccount/activecampaign');



	}





	public function referfriendsAction()

	{

		global $mySession;

		$this->_helper->layout->setLayout('myaccount');

		$db=new Db();

		$myform=new Form_Referfriends();

		$this->view->myform=$myform;

	}





	public function emailAction()

	{

		global $mySession;

		$this->_helper->layout->setLayout('myaccount');

		$db=new Db();







		$myform=new Form_Referfriends();

		$this->view->myform=$myform;



		if ($this->getRequest()->isPost())

		{

			//echo "is post"; die;

			$request=$this->getRequest();

			$myform=new Form_Referfriends();



			if ($myform->isValid($request->getPost()))

			{

				$dataForm=$myform->getValues();



				$friendsemailid = explode(",",$dataForm['friendsemailid']);



					//prd($friendsemailid);

					//for($i=0; $i<count($friendsemailid); $i++)

					foreach($friendsemailid as $key1=>$value1)

					{



						if(trim($value1)!="")

						{

							//if(!preg_match('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?^', trim($value1)))

							if(!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$/', trim($value1)))

							{



 								$mySession->errorMsg="Enter a valid email address";

								//$this->render('referfriends');

								$this->_redirect('myaccount/referfriends');

								//$invalid_ids=$invalid_ids.",".$invalidemailId;



							}

						}



					}



				//for($i=0; $i<count($friendsemailid); $i++)

//				{

//					if(!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$', trim($friendsemailid[$i])))

//					{

//						echo "aaa"; die;

//					}

//					else

//					{

//						$mySession->errorMsg="Enter a valid email address";

//						$this->render('referfriends');

//					}

//				}



				$myObj=new Myaccountdb();

				$data=$myObj->emailfriends($dataForm);



				if($data==1)

				{

						$mySession->errorMsg="Email sent to your friends";

						$this->_redirect('myaccount/activecampaign');

				}

			}

			else

			{

				//echo "is valid else";

				$this->view->myform = $myform;

				$this->render('referfriends');

			}

		}

		else

		{

			//echo "get request else";

			$this->_redirect('myaccount/referfriends');

		}

	}





	public function emailfriendsAction()

	{

		global $mySession;

		$db=new Db();



		$name=$this->getRequest()->getParam('name');

		$this->view->name=$name;

		//echo "url is : ".$name; die;

		$this->_helper->layout->setLayout('myaccount');

		//$cid=$this->getRequest()->getParam('cid');

		//$cid->view->myform=$cid;



		$myform=new Form_Emailfriends($name);



		$this->view->myform=$myform;

	}





	public function emailitAction()

	{

		global $mySession;

		$this->_helper->layout->setLayout('myaccount');

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



				$friendsemailid = explode(",",$dataForm['friendsemailid']);



					foreach($friendsemailid as $key1=>$value1)

					{



						if(trim($value1)!="")

						{



							if(!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$/', trim($value1)))

							{



 								$mySession->errorMsg="Enter a valid email address";

								$this->_redirect('myaccount/emailfriends');

							}

						}



					}



				$myObj=new Launchcampaigndb();

				$data=$myObj->emailfriends($dataForm);



				if($data==1)

				{

						$mySession->errorMsg="Email sent to your friends";

						$this->_redirect('myaccount/activecampaign');

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

			$this->_redirect('myaccount/emailfriends');

		}

	}









	public function profileAction()

	{
		global $mySession;


		$db=new Db();

		//echo $_SERVER['PHP_SELF']."/atoz"; die;

		//echo $_SERVER['SCRIPT_FILENAME']; die;

		$chkQry=$db->runQuery("select * from ".USERS." where  user_id='".$mySession->TeeLoggedID."'");

		$this->_helper->layout->setLayout('myaccount');

		$myform=new Form_Profile();

		$this->view->myform=$myform;

		$this->view->userdatapic=$chkQry[0];

	}



	public function changepasswordAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		$myform=new Form_Changepassword();

		$this->view->myform=$myform;

	}



	public function updatechangepasswordAction()

	{

		global $mySession;

		$db=new Db();



		$this->_helper->layout->setLayout('myaccount');

		$myform=new Form_Changepassword();



		$this->view->myform=$myform;




		if ($this->getRequest()->isPost())

		{

			$request=$this->getRequest();

			$myform=new Form_Changepassword();



			if ($myform->isValid($request->getPost()))

			{

				$dataForm=$myform->getValues();



				$qury=$db->runquery("Select * from ".USERS."");

				//$password=$qury[0]['password'];



				if($dataForm['currentpass']==$qury[0]['password'])

				{



					$pass=$dataForm['newpass'];

					$cnfrm=$dataForm['cnfrmnewpass'];

					if($pass==$cnfrm)

					{

						$myObj=new Myaccountdb();          // call to model

						$data=$myObj->changepass($dataForm);



						if($data==1)

						{

							$mySession->errorMsg="Password updated successfully";

							$this->_redirect('myaccount/changepassword');

						}

					}

					else

					{

						$mySession->errorMsg="password is not same";

						$this->render('changepassword');

					}

				}

				else

				{

					$mySession->errorMsg="Your Current Password does not match";

					$this->render('changepassword');

				}



			}

			else

			{

				$this->view->myform = $myform;

				$this->render('changepassword');

			}

		}

		else

		{

			$this->_redirect('myaccount/changepassword');

		}

    }



	public function changeaddressAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');



		$myform=new Form_Changeaddress();

		$this->view->myform=$myform;

	}



	public function addaddressAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');



		if ($this->getRequest()->isPost())

		{

			$request=$this->getRequest();

			$myform=new Form_Changeaddress();



			if ($myform->isValid($request->getPost()))

			{

				$dataForm=$myform->getValues();



				$myObj=new Myaccountdb();

				$data=$myObj->addaddress($dataForm);



				if($data==1)

				{

						$mySession->errorMsg="Address Added";

						$this->_redirect('myaccount/profile');

				}

			}

			else

			{

				$this->view->myform = $myform;

				$this->render('changeaddress');

			}

		}

		else

		{

			$this->_redirect('myaccount/changeaddress');

		}

	}





	public function updateaddressAction()

	{

		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');



		$myform=new Form_Changeaddress();

		$this->view->myform = $myform;

	}



   public function updateaddrsprocessAction()

   {

   		global $mySession;

		$db=new Db();

		$this->_helper->layout->setLayout('myaccount');

		if ($this->getRequest()->isPost())  //  same as issset post

		{



			$request=$this->getRequest();

			$myform = new Form_Changeaddress();



			if ($myform->isValid($request->getPost()))   //  required true is checked.

			{

				$dataForm=$myform->getValues();

				$myObj=new Myaccountdb();          // call to model

				$data=$myObj->updateaddress($dataForm);

				if($data==1)

				{

					$mySession->errorMsg="You have changed your address";

					$this->_redirect('myaccount/profile');

				}

				else

				{

					$mySession->errorMsg="not successfull";

					$this->render('profile');

				}

			}

			else

			{

				$this->view->myform = $myform;

				$this->render('profile');

			}

		}

		else

		{

			$this->_redirect('myaccount/profile');

		}

	}





	  public function updateuserAction()
   	  {
   		global $mySession;
		$db=new Db();
		$this->_helper->layout->setLayout('myaccount');

		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();

			$myform = new Form_Profile();
            //error_log('MyaccountController->updateuserAction request: '.print_r($request, true));

			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();
                //error_log('MyaccountController->updateuserAction form valid, dataForm: '.print_r($dataForm, true));

				$myObj=new Myaccountdb();
				$data=$myObj->updateuser($dataForm);

				if($data==1)
				{
					$mySession->errorMsg="Profile Updated Successfully";
					$this->_redirect('myaccount/profile');
				} else {
					$mySession->errorMsg="User Already Exist With This Email !!!";
					$this->render('profile');
				}
			} else {
				//$mySession->errorMsg="not successfull ====>";
				$this->view->myform = $myform;
				$this->render('profile');
			}

		} else {
			//echo "else of request is post"; die;
			$this->_redirect('myaccount/profile');
		}

	}









}

?>
