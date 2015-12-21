<?php
__autoloadDB('Db');
class Pages extends Db
{
	public function SavePage($dataForm)
	{
		global $mySession;
		$db=new Db();
		$chkQry=$db->runQuery("select * from ".PAGES." where page_title='".$dataForm['page_title']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{
		$dataPage['page_title']=$dataForm['page_title'];
		$dataPage['page_content']=$dataForm['page_content'];
		$dataPage['page_position']='1';
		$db->save(PAGES,$dataPage);
		return 1;	
		}
	}
	public function UpdatePage($dataForm,$pageId)
	{
		global $mySession;
		$db=new Db();

		$chkQry=$db->runQuery("select * from ".PAGES." where page_title='".$dataForm['page_title']."' and page_id!='".$pageId."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{
		$dataUpdate['page_title']=$dataForm['page_title'];
		$dataUpdate['page_content']=$dataForm['page_content'];
		//$dataUpdate['page_position']=$dataForm['pageposition'];
		$conditionUpdate="page_id='".$pageId."'";
		$db->modify(PAGES,$dataUpdate,$conditionUpdate);
		return 1;	
		}
	}
	
	
	public function UpdateCategory($dataForm,$catId)
	{
		global $mySession;
		$db=new Db();

		
		$chkQry=$db->runQuery("select * from ".CATEGORY." where cat_name='".$dataForm['cat_name']."' and cat_id!='".$catId."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataUpdate['cat_name']=$dataForm['cat_name'];
			$dataUpdate['cat_description']=$dataForm['cat_description'];
			
			$conditionUpdate="cat_id='".$catId."'";
			
			$db->modify(CATEGORY,$dataUpdate,$conditionUpdate);
			return 1;	
		}
	}
	public function UpdateTestimonials($dataForm,$testimonialsId)
	{
		global $mySession;
		$db=new Db();
		
		$chkQry=$db->runQuery("select * from ".TESTIMONIALS." where testimonials_name='".$dataForm['cat_name']."' and testimonials_id!='".$testimonialsId."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataUpdate['testimonials_name']=$dataForm['cat_name'];
			$dataUpdate['testimonials_sitename']=$dataForm['cat_sitename'];
			$dataUpdate['testimonials_image']=$dataForm['image'];
			$dataUpdate['testimonials_data']=$dataForm['cat_description'];
			
			$conditionUpdate="testimonials_id='".$testimonialsId."'"; 
			//pr($dataUpdate);
			//prd($conditionUpdate);
			$db->modify(TESTIMONIALS,$dataUpdate,$conditionUpdate);
			return 1;	
		}
	}
	
	public function UpdateQuestion($dataForm,$quesId)
	{
		global $mySession;
		$db=new Db();
		 
	
		
		$chkQry=$db->runQuery("select * from ".QUESTIONS." where question='".$dataForm['question']."' and ques_id!='".$quesId."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataUpdate['question']=$dataForm['question'];
			$dataUpdate['answer']=$dataForm['answer'];
			
			$conditionUpdate="ques_id='".$quesId."'";
			
			
			$db->modify(QUESTIONS,$dataUpdate,$conditionUpdate);
			return 1;	
		}
	}
	
	
	public function SaveCategory($dataForm)
	{
		global $mySession;
		$db=new Db();
		
		$chkQry=$db->runQuery("select * from ".CATEGORY." where cat_name='".$dataForm['cat_name']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataInsert['cat_name']=$dataForm['cat_name'];
			$dataInsert['cat_description']=$dataForm['cat_description'];
			
			$db->save(CATEGORY,$dataInsert);
			return 1;	
		}
	}
	
	public function SaveTestimonials($dataForm)
	{
		global $mySession;
		$db=new Db();
		
		$chkQry=$db->runQuery("select * from ".TESTIMONIALS." where testimonials_name='".$dataForm['testimonials_name']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataInsert['testimonials_name']=$dataForm['cat_name'];
			$dataInsert['testimonials_sitename']=$dataForm['cat_sitename'];
			$dataInsert['testimonials_image']=$dataForm['image'];
			$dataInsert['testimonials_data']=$dataForm['cat_description'];
			
			$db->save(TESTIMONIALS,$dataInsert);
			return 1;	
		}
	}
	
	
	public function SaveQuestion($dataForm,$catId)
	{
		global $mySession;
		$db=new Db();
		
		
		
		$chkQry=$db->runQuery("select * from ".QUESTIONS." where question='".$dataForm['question']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
			$dataInsert['question']=$dataForm['question'];
			$dataInsert['answer']=$dataForm['answer'];
			$dataInsert['cat_id']=$dataForm['catname'];
			//$condition="cat_id='".$catId."'";
			
			
			
			$db->save(QUESTIONS,$dataInsert);
			return 1;	
		}
	}
}
?>