<?php
__autoloadDB('Db');
class PostController extends AppController
{
/* Posted Job */
		public function indexAction()
	{
		//echo "sfs"; die;
	global $mySession;
	$db=new Db();
	$this->view->pageHeading="Manage Post";
	$str="Select *,
	(SELECT count(`comment_id`) s FROM ".POST_COMMENT."
	INNER JOIN ".USERS." ON ".USERS.".user_id = ".POST_COMMENT.".user_id
	WHERE ".POST_COMMENT.".post_id= ".POSTS.".post_id) as commentcount
	, email_address as username from ".POSTS." left join ".USERS." on ".USERS.".user_id= ".POSTS.".user_id order by post_id DESC"; 
	 $sql=$db->runQuery($str); 
	 $this->view->sql=$sql;	
	}
	public function uploadindexAction()
	{
		
	global $mySession;
	$db=new Db();
	$this->view->pageHeading="Manage Home Images";
	
	$sql=$db->runQuery("select * from ".ADMINPOST);

	 //$sql=$db->runQuery($sql); 
	 $this->view->sql=$sql;	
	}
	
		public function uploadAction()
	{ 
	
		global $mySession;
		$db=new Db();
		
		$this->view->pageHeading="What are you wearing today?";
		$myform=new Form_Upload();  
		//echo"fds"; die;
		$this->view->myform=$myform;
 }  
	public function addphotoAction()
			{
		    $db=new Db();
			$this->view->pageHeading="What are you wearing today?";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Upload();	
	
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();	
											
				$myObj=new Upload();
					
				$Result=$myObj->SaveImage($dataForm);
									
					if($Result==1)
					{
						//echo "dsg"; die;
					$mySession->sucessMsg ="Image saved successfully.";
					$this->_redirect('post/uploadindex');
					}
					else
					{
					$mySession->errorMsg ="Image you have entered already exists.";
					$this->view->myform = $myform;
					$this->render('upload');
					}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('upload');
			}
		}
		else
		{			
			$this->_redirect('post/upload');
		}	  	
			}
			
		
	public function deletepostAction()
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
					$condition1="post_id='".$Id."'"; 
					$db->delete(POSTS,$condition1);
					$db->delete(POST_COMMENT,$condition1);
				}
			}
		}		
		exit();
		
	}
		public function addnewAction()
	{
		
		global $mySession; 
	    $db=new Db();
		$this->view->pageHeading="Add New Blog";
			//echo "dfs"; die;   	
		$myform=new Form_Post();
		
		$this->view->myform=$myform;
			
	}
	
	public function savenewAction()
	{
		
		global $mySession; 
	    $db=new Db();
		$this->view->pageTitle="Add Blog";
		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Post();	
	
			if ($myform->isValid($request->getPost()))
			{
				$dataForm=$myform->getValues();							
				$myObj=new Post();
				$Result=$myObj->SaveBlog($dataForm);
					//echo "dafds"; die;							
					if($Result==1)
					{
					$mySession->errorMsg ="New blog added successfully.";
					$this->_redirect('post/index');
					}
					else
					{
					$mySession->errorMsg ="Blog title you have entered already exists.";
					$this->view->myform = $myform;
					$this->render('addnew');
					}
			}
			else
			{	
				$this->view->myform = $myform;
				$this->render('addnew');
			}
		}
		else
		{			
			$this->_redirect('post/addnew');
		}	  	
	}
	public function editpostAction()
	{
	global $mySession;	
	$db=new Db();
	
	$postid=$this->getRequest()->getParam('postid');
	$this->view->postid=$postid;
	
	$myform=new Form_Post($postid);
	$this->view->myform=$myform;
	$this->view->pageTitle="Edit Post";
	$data=$db->runQuery("select * from ".POSTS." where post_id='".$postid."'");
	$this->view->data=$data;
	//echo $postid; die;
	}
	
	public function updatepostAction()
	{
		global $mySession;	
		$db=new Db();
		
		$postid=$this->getRequest()->getParam('post_id'); 
		$this->view->postid=$postid;
		$this->view->pageTitle="Edit Color";
		//echo $postid; die;	  

		if ($this->getRequest()->isPost())
		{
			$request=$this->getRequest();
			$myform = new Form_Post($postid);			
			if ($myform->isValid($request->getPost()))
			{				
				$dataForm=$myform->getValues();
				$myObj=new Post();
				$Result=$myObj->UpdateBlog($dataForm,$postid);
				if($Result==1)
				{
				$mySession->errorMsg ="Details updated successfully.";
				$this->_redirect('post/index');
				}
				else
				{
				}
			}
			else
			{
				$this->view->myform = $myform;
				$this->render('editpost');
			}
		}
		else
		{
			$this->_redirect('post/editpost/postid/'.$postid);
		}
	}
	
	public function changeuserstatusAction()
		{
			 global $mySession;
	  $db=new Db(); 
	  $utype=$this->getRequest()->getParam('utype');
	  $BcID=$this->getRequest()->getParam('post_id');
	  $status=$this->getRequest()->getParam('Status');
	  
	  	
	  if($status=='1')
	  { 
	   
	  $status = '0';
	  }
	  else 
	  { 
	 
	  $status = '1';
	  } 
	 $data_update['look_status']=$status; 
	 $condition="post_id='".$BcID."' ";
	  $db->modify(POSTS,$data_update,$condition);
	  
	  if($db)
	  {
		  $mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('post/index/uType/'.$utype);
			
	  }
	  
	    
	  exit();
		
			
		}
public function changeimagestatusAction()
		{
			 global $mySession;
	  $db=new Db(); 
	 
	  $BcID=$this->getRequest()->getParam('id');
	  $status=$this->getRequest()->getParam('Status');
	  
	  	
	  if($status=='1')
	  { 
	   
	  $status = '0';
	  }
	  else 
	  { 
	 
	  $status = '1';
	  } 
	 $data_update['image_status']=$status; 
	 $condition="id='".$BcID."' ";
	  $db->modify(ADMINPOST,$data_update,$condition);
	  
	  if($db)
	  {
		  $mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('post/uploadindex');
			
	  }
	  
	    
	  exit();
		
			
		}
		public function deleteimageAction()
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
					$condition1="id='".$Id."'"; 
					$db->delete(ADMINPOST,$condition1);
				}
			}
		}		
		exit();
		
	}

	
}
	?>

	
	
	
	