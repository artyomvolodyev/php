<?php
__autoloadDB('Db');
class Blogs extends Db
{
  
  public function SaveBlog($dataForm)
	{
		global $mySession;
		$db=new Db();


		$chkQry=$db->runQuery("select * from ".BLOGS." where blog_title='".$dataForm['blog_title']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
				$dataInsert['blog_title']=$dataForm['blog_title'];
				$dataInsert['blog_description']=$dataForm['blog_description'];
				$dataInsert['user_id']=$mySession->adminId;
				if($mySession->adminId=='1')
				{
					$dataInsert['user_type']='2';
				}
				else
				{
					$dataInsert['user_type']='3';
				}
				$dataInsert['added_date']=date('d:m:y');
				$dataInsert['blog_status']=$dataForm['blog_status'];
				$db->save(BLOGS,$dataInsert);
				return 1;	
		}

	}
	
	public function UpdateBlog($dataForm,$blogid)
	{
		global $mySession;
		$db=new Db();
		
		$chkQry=$db->runQuery("select * from ".BLOGS." where blog_title='".$dataForm['blog_title']."' and blog_id!='".$blogid."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{	 
				$dataUpdate['blog_title']=$dataForm['blog_title'];
				$dataUpdate['blog_description']=$dataForm['blog_description'];
				//$dataUpdate['user_id']='0';
				$dataUpdate['added_date']=date('d:m:y');
				$dataUpdate['blog_status']=$dataForm['blog_status'];				
				$conditionUpdate="blog_id='".$blogid."'";
				//prd($dataUpdate);
				$db->modify(BLOGS,$dataUpdate,$conditionUpdate);;
				return 1;	
		}
	}

   public function Getblogs()
  { 
    global $mySession;
	$db=new Db();	
 	$str="Select *,
	(SELECT count(`comment_id`) s FROM ".BLOG_COMMENTS."
		INNER JOIN ".USERS." ON ".USERS.".user_id = ".BLOG_COMMENTS.".user_id
		 WHERE ".BLOG_COMMENTS.".blog_id= ".BLOGS.".blog_id) as commentcount
	, CONCAT(first_name,' ',last_name) as username from ".BLOGS." left join ".USERS." on ".USERS.".user_id= ".BLOGS.".user_id order by blog_id DESC"; 
	$blogdata=$db->runQuery($str); 
	return $blogdata;
  }

}
