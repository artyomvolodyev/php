<?php
__autoloadDB('Db');
class CommentController extends AppController
{
  public function indexAction()
  {
	
		global $mySession; 
	    $db=new Db();
		$this->view->pageHeading="Comments";   
		  	$post_id=$this->getRequest()->getParam('post_id');
			//echo "select * from ".POST_COMMENT." AS pc LEFT JOIN ".POSTS." as p on pc.post_id=p.post_id WHERE ".POST_COMMENT.".post_id='".$post_id."'"; die;
	    $sql=$db->runQuery("select * from ".POST_COMMENT." AS pc LEFT JOIN ".POSTS." as p on pc.post_id=p.post_id WHERE pc.post_id='".$post_id."'");
		$this->view->sql=$sql;	
		
		$sql1=$db->runQuery("select * from ".USERS." as u JOIN ".POST_COMMENT." as p on u.user_id= p.user_id WHERE p.post_id='".$post_id."'");
		 $this->view->sql1=$sql1;
		  //prd($sql1);	
		 //$this->view->username=$sql1[0]['first_name']." ".$sql1[0]['last_name'];		
	
		//$Obj=new Blogs(); 
	    //$blogdata=$Obj->Getblogs(); 
	   // $this->view->blogdata=$blogdata;			
		//$ArrPg=fun_paginator($blogdata);			
		//$this->view->pageLinks=$ArrPg['pageLinks'];
		//$this->view->pagingdata=$ArrPg['pagingdata'];	  
  }	 
  public function changecommentstatusAction()
		{
			 global $mySession;
	  $db=new Db(); 
	 // echo $post_id; die;
	   $post_id=$this->getRequest()->getParam('post_id');
	  $BcID=$this->getRequest()->getParam('comment_id');
	  $status=$this->getRequest()->getParam('Status');
	  
	  	
	  if($status=='1')
	  { 
	   
	  $status = '0';
	  }
	  else 
	  { 
	 
	  $status = '1';
	  } 
	 $data_update['comment_status']=$status; 
	 $condition="comment_id='".$BcID."' ";
	  $db->modify(POST_COMMENT,$data_update,$condition);
	  
	  if($db)
	  {
		  $mySession->errorMsg ="Status Changed successfully.";
			$this->_redirect('comment/index/post_id/'.$post_id);
			
	  }
	  
	    
	  exit();
		
			
		}
		public function deletecommentAction()
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
					$condition1="comment_id='".$Id."'"; 
					$db->delete(POST_COMMENT,$condition1);
				}
			}
		}		
		exit();
	}
	
  

	
	

}
?>