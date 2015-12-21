<?php



__autoloadDB('Db');



class UserController extends AppController



{



	



	public function indexAction()



	{



		global $mySession;



		$db=new Db();



		



		$this->view->pageTitle="My Contacts";



		$this->_helper->layout->setLayout('myaccount');



						



	}



		



	

	public function hireuserAction()



	{



		global $mySession;



		$db=new Db();



		



		$this->_helper->layout->setLayout('friendprofile');



		



		$id=$this->getRequest()->getParam('user_id');



		$this->view->id=$id;

		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id  where user_id='".$id."' ");die;



		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id where user_id='".$id."' ");die;

	    $sql=$db->runquery("select * from ".USERS." where user_id='".$id."' ");

		$this->view->pageTitle="Hire:".$sql[0]['public_name'];

		$follow=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".following_id=".USERS.".user_id) where following_id='".$id."' ");

		$follower=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".follower_id=".USERS.".user_id) where follower_id='".$id."' ");

		

		$this->view->follower=$follower;

		$this->view->follow=$follow;

		$this->view->dataQry=$sql[0];

		$myform=new Form_Hire();

		

		$this->view->myform=$myform;

		 $loggeduser=$db->runquery("select * from ".USERS." where user_id='".$mySession->TeeLoggedID."' ");





		if ($this->getRequest()->isPost())

		{

			$request=$this->getRequest();

			

			if ($myform->isValid($request->getPost())) 

			{		$dataForm=$myform->getValues();

					$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='18'");

					$emailidcreator=$sql[0]['emailid'];

					

					$messageText=$templateData[0]['email_body'];

					

					$subject=$templateData[0]['email_subject'];

					

					$messageText=str_replace("[NAME]",$useremail[0]['public_name'],$messageText);

					$messageText=str_replace("[MESSAGE]",$dataForm['Message'],$messageText);
					$messageText=str_replace("[SENDEREMAIL]",$loggeduser[0]['emailid'],$messageText);

					

					$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);

					

					//$messageText=str_replace("[ORDERNO]",$orderno,$messageText);

					

					$result=SendEmail($emailidcreator,$subject,$messageText);

					

					if($result)

					{

					$mySession->errorMsg="Mail  Sent Successfully !!!";

					$this->_redirect('user/view/user_id/'.$id);

					}

					else

					{

					$mySession->errorMsg="Email Address is not correct!!!";

					$this->_redirect('user/view/user_id/'.$id);

					}

					

			}

			else

			{		$mySession->errorMsg="Please Fill All The Fields !!!";

					$this->view->myform = $myform;

					$this->view->id=$id;

					$this->view->follower=$follower;

					$this->view->follow=$follow;

					$this->view->dataQry=$sql[0];

					//echo "dfasdf";die;

					$this->render('hireuser');

			}

		}



	

	}



	

			



	



	public function viewAction()



	{



		global $mySession;



		$db=new Db();



		



		$this->_helper->layout->setLayout('friendprofile');



		



		$id=$this->getRequest()->getParam('user_id');



		$this->view->id=$id;



		



		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id  where user_id='".$id."' ");die;



		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id where user_id='".$id."' ");die;







	    $sql=$db->runquery("select * from ".USERS." where user_id='".$id."' ");









	$this->view->pageTitle=$sql[0]['public_name'];



	$this->view->dataQry=$sql[0];

$follow=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".follower_id=".USERS.".user_id) where following_id='".$id."' ");



$this->view->follow=$follow;







$follower=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".following_id=".USERS.".user_id) where follower_id='".$id."' ");



$this->view->follower=$follower;





	}

	public function view_oldAction()



	{



		global $mySession;



		$db=new Db();



		



		$this->_helper->layout->setLayout('friendprofile');



		



		$id=$this->getRequest()->getParam('user_id');



		$this->view->id=$id;



		



		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id  where user_id='".$id."' ");die;



		//echo ("select * from ".USERS." inner join ".COUNTRIES." on ".COUNTRIES.".country_id=".USERS.".country_id where user_id='".$id."' ");die;



		



		$chk=$db->runquery("select * from ".FRIENDS." where request_by='".$mySession->TeeLoggedID."' and request_to='".$id."' ");



		



		



	    $sql=$db->runquery("select * from ".USERS." where user_id='".$id."' ");



	



	$photo=$db->runquery("select *,count(*) from ".PHOTOS." where user_id='".$id."' order by date_added desc ");



	$video=$db->runquery("select *,count(*) from ".VEDIO." where user_id='".$id."' order by date_uploaded desc ");



	$article=$db->runquery("select *,count(*) from ".ARTICLE." where user_id='".$id."' order by date desc ");



	



	$this->view->photo=$photo;



	$this->view->video=$video;



	$this->view->article=$article;



	



	//	echo ("select * from ".USERS." left join ".VEDIO." on  (".USERS.".user_id=".VEDIO.".user_id) left join ".ARTICLE." on (".USERS.".user_id=".ARTICLE.".user_id) left join ".PHOTOS." on (".USERS.".user_id=".PHOTOS.".user_id) where ".USERS.".user_id='".$id."' ");die;



		



		



	



	//prd($sql);



	$this->view->pageTitle=$sql[0]['first_name']." ".$sql[0]['last_name'];



	$this->view->sql=$sql[0];



	$this->view->chk=$chk;



	}



	



	



	public function followAction()



	{







		global $mySession;



		$db=new Db();



		



		$this->_helper->layout->setLayout('myaccount');



			



		$follow=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".following_id=".USERS.".user_id) where follower_id='".$mySession->TeeLoggedID."' ");



		$this->view->follow=$follow;







		



	}



	public function followerAction()



	{







		global $mySession;



		$db=new Db();



		



		$this->_helper->layout->setLayout('myaccount');



		



		$follow=$db->runquery("select *,".USERS.".user_id as user_id from ".FOLLOW." inner join ".USERS." on (".FOLLOW.".follower_id=".USERS.".user_id) where following_id='".$mySession->TeeLoggedID."' ");



		$this->view->follow=$follow;



		



		



	}



	



	public function searchuserAction()



	{



		global $mySession;



		$db=new Db();



		$key=$_REQUEST['x'];



		



			



		$user=$db->runquery("select * from ".USERS." where ( (first_name='".$key."' || (LOWER(first_name) like '".trim(strtolower($key))."%') || (LOWER(first_name) like '%".trim(strtolower($key))."') || (LOWER(first_name) like '%".trim(strtolower($key))."%') ) || (last_name='".$key."' || (LOWER(last_name) like '".trim(strtolower($key))."%') || (LOWER(last_name) like '%".trim(strtolower($key))."') || (LOWER(last_name) like '%".trim(strtolower($key))."%') ) ) ");



		



		



		foreach($user as $user)



		{



			?>



            <div class="contentdivHei">



            	<div class="FL bookimgdiv" align="left">



					<?php



					$img=$user['profile_image'];



                    



                    if($img!="" and file_exists(SITE_ROOT.'images/profileimgs/'.$img) )



                    { 



					?>



                    	<img src="<?=APPLICATION_URL?>image.php?image=<?='images/profileimgs/'.$img?>&height=70&width=100" border="0" class="bookimg" />         



                    <?php 



					} 



                    else 



                    { 



                    ?>



                   		<img src="<?=IMAGES_URL?>images/book.png" class="bookimg">



                    <?php	



					}  



					?>               



                </div>



                



                <div align="left" class="dataContent">



                	<div class="name" style="color:#22B0FF; font-size:20px;">



                	<?=$user['first_name']." ".$user['last_name']?>



                    </div>



                    



                    <div>



                    <?=$user['aboutme']?>



                    </div>



                    



                    <div align="right">



                    



                    <?php



                    $un=$db->runquery("select count(*) from ".FOLLOW." where follower_id='".$mySession->TeeLoggedID."' and following_id='".$user['id']."'  ");



					



					if($un[0]['count(*)']>0)



					{



					?>



                    <a onclick="Unfollow('<?=$user['id']?>')"  class="followText" > UnFollow </a>



                    <?php	



					}



					else



					{



                    ?>



                   <a onclick="followUser('<?=$user['id']?>')"  class="followText" > Follow </a>



                    <?php



					}



					?>



                    </div>



                    



                </div>



            </div>



            



            



             <div class="txtsearch">&nbsp;</div>      



            



            <?php



		}



		



		exit();



		



	}



	



	public function folowuserAction()



	{



		



		global $mySession;



		$db=new Db();



		



		$id=$_REQUEST['id'];



		



		 $chk=$db->runquery("select * from ".FOLLOW." where follower_id='".$mySession->TeeLoggedID."' and following_id='".$id."' ");



		



		if($chk!="" && count($chk)>0)



		{



			$mySession->errorMsg ="You are already Following This user";



		}



		else



		{



		$dataInsert['follower_id']=$mySession->TeeLoggedID;



		$dataInsert['following_id']=$id;



		$dataInsert['date_followed']=new Zend_Db_Expr('NOW()');



				



		$db->Save(FOLLOW,$dataInsert);



		}



		



		$mySession->errorMsg ="User Followed Successfully";



			



		exit();



}







	public function unfollowAction()



	{







		global $mySession;



		$db=new Db();



		



		$id=$this->getRequest()->getParam('id');



		



		$condition_delete="following_id='".$id."' and follower_id='".$mySession->TeeLoggedID."' ";



		$db->delete(FOLLOW,$condition_delete);



		



		$mySession->errorMsg ="User UnFollowed Successfully";



			



		exit();	



	}



	



		



	



}



?>