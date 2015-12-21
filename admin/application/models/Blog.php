<?php
__autoloadDB('Db');
class Blog extends Db
{
  
  public function SaveBlog($dataForm)
	{
		global $mySession;
		$db=new Db();
		$sql="select * from ".BLOGPOST." where blog_image='".$dataForm['image']."'"; 
		$chkQry=$db->runQuery($sql);
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;
		}
		else
				$dataInsert['blog_image']=$dataForm['image'];
				$dataInsert['blog_title']=$dataForm['blogtitle'];
				$dataInsert['blog_desc']=$dataForm['blog_description'];
				$dataInsert['blog_status']=$dataForm['blog_status'];
				$dataInsert['blog_date']=date('Y-m-d H:i:s');
				$db->save(BLOGPOST,$dataInsert);
				return 1;	

	}
	
	public function UpdateBlog($dataForm,$blogid)
	{
		global $mySession;
		$db=new Db();
		
		if($dataForm['image']!="" && $dataForm['old_image']!="")
		{
			unlink(SITE_ROOT.'images/blogimg/'.$dataForm['old_image']);
		}
		$blogimage=$dataForm['old_image'];
		if($dataForm['image']!="")
		{
			$blogimage=time()."_".$dataForm['image'];
			@rename(SITE_ROOT.'images/blogimg/'.$dataForm['image'],SITE_ROOT.'images/blogimg/'.$blogimage);
		}
				$dataUpdate['blog_image']=$blogimage;
				$dataUpdate['blog_title']=$dataForm['blogtitle'];
				$dataUpdate['blog_desc']=$dataForm['blog_description'];
				$dataUpdate['blog_status']=$dataForm['blog_status'];
				//$dataUpdate['user_id']='0';
				
				
				$conditionUpdate="blog_id='".$blogid."'";
				//prd($dataUpdate);
				$db->modify(BLOGPOST,$dataUpdate,$conditionUpdate);;
				return 1;	
		
	}


}
