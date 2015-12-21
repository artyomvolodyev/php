<?php
__autoloadDB('Db');
class Post extends Db
{
  
  public function SaveBlog($dataForm)
	{
		global $mySession;
		$db=new Db();

				$dataInsert['post_image']=$dataForm['image'];
				$dataInsert['look_name']=$dataForm['lookname'];
				$dataInsert['look_desc']=$dataForm['look_description'];
				$dataInsert['color_image']=$dataForm['colorcode'];
				$dataInsert['user_id']='0';
				$dataInsert['date']=date('Y-m-d H:i:s');
				//$dataInsert['date']=date('d:m:y');
				$dataInsert['look_status']=$dataForm['color_status'];
				$db->save(POSTS,$dataInsert);
				return 1;	

	}
	
	public function UpdateBlog($dataForm,$postid)
	{
		global $mySession;
		$db=new Db();
		//echo $postid; die;
		if($dataForm['image']!="" && $dataForm['old_image']!="")
		{
			unlink(SITE_ROOT.'images/postimg/'.$dataForm['old_image']);
		}
		$postimage=$dataForm['old_image'];
		if($dataForm['image']!="")
		{
			$postimage=time()."_".$dataForm['image'];
			@rename(SITE_ROOT.'images/postimg/'.$dataForm['image'],SITE_ROOT.'images/postimg/'.$postimage);
		}
				$dataUpdate['post_image']=$postimage;
				$dataUpdate['look_name']=$dataForm['lookname'];
				$dataUpdate['look_desc']=$dataForm['look_description'];
				$dataUpdate['color_image']=$dataForm['colorcode'];
				//$dataUpdate['user_id']='0';
				$dataUpdate['date']=date('d:m:y');
				$dataUpdate['look_status']=$dataForm['color_status'];
				$conditionUpdate="post_id='".$postid."'";
				//prd($dataUpdate);
				$db->modify(POSTS,$dataUpdate,$conditionUpdate);;
				return 1;	
		
	}


}
