<?php
__autoloadDB('Db');
class Users extends Db
{
	public function UpdateUser($dataForm,$userId)
	{
	

		global $mySession;
		$db=new Db();
		
		
	
 		$sql="select * from ".USERS." where email_address='".$dataForm['email_address']."' and user_id!='".$userId."'"; 
		$chkQry=$db->runQuery($sql);
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;
		}
		else
		{
		if(isset($dataForm['npassword']))
		{
		$dataUpdate['password']=md5($dataForm['npassword']);
		}
		
		if($dataForm['user_pic']!="" && $dataForm['old_profile_image']!="")
		{
			unlink(SITE_ROOT.'images/profileimgs/'.$dataForm['old_profile_image']);
		}
		$profileImage=$dataForm['old_profile_image'];
		if($dataForm['user_pic']!="")
		{
			$profileImage=time()."_".$dataForm['user_pic'];
			@rename(SITE_ROOT.'images/profileimgs/'.$dataForm['user_pic'],SITE_ROOT.'images/profileimgs/'.$profileImage);
		}
		$dataUpdate['image']=$profileImage;
		$dataUpdate['first_name']=$dataForm['first_name'];
		$dataUpdate['last_name']=$dataForm['last_name'];
		//$dataUpdate['password']=$dataForm['password'];
		$dataUpdate['blog_url']=$dataForm['blog_url'];
		$dataUpdate['website_url']=$dataForm['website_url'];
		$dataUpdate['location']=$dataForm['location'];
		$dataUpdate['occupation']=$dataForm['occupation'];
		$dataUpdate['about_me']=$dataForm['about_me'];
		$dataUpdate['email_address']=$dataForm['email_address'];
		$dataUpdate['country']=$dataForm['country'];
		$dataUpdate['gender']=$dataForm['gender'];
		$dataUpdate['dob']=$_REQUEST['dob'];
	 	$conditionUpdate="user_id='".$userId."'";
		$db->modify(USERS,$dataUpdate,$conditionUpdate);
		
		return 1;
		}
	}
	
	public function SaveCountry($dataForm)
	 
	 {
		 global $mySession;
		$db=new Db();
		$dataInsert['country_name']=$dataForm['country'];
		$dataInsert['country_status']='1';
		//prd($dataInsert);
		$db->save(COUNTRIES,$dataInsert);
		return 1;
		
	   }
	   
	 public function UpdateCountry($dataForm,$countryid)
	 
	 {
		 global $mySession;
		$db=new Db();
		$dataUpdate['country_name']=$dataForm['country'];
		$dataUpdate['country_status']='1';
		$conditionUpdate="country_id='".$countryid."'";
		$db->modify(COUNTRIES,$dataUpdate,$conditionUpdate);
		
		return 1;
	   }
	   
	   
	 public function UpdateState($dataForm,$stateid)
	 {
		global $mySession;
		$db=new Db();
		//prd($dataForm);
		//$dataUpdate['country_id']=$dataForm['country'];
		$dataUpdate['state_name']=$dataForm['state'];
		//prd($dataUpdate);
		//$dataUpdate['country_status']='1';
		$conditionUpdate="state_id='".$stateid."'";
		$db->modify(STATE,$dataUpdate,$conditionUpdate);
		
		return 1;
	   }
	   
	   public function UpdateCity($dataForm,$cityid)
	  {
		global $mySession;
		$db=new Db();
		//prd($dataForm);
		$dataUpdate['state_id']=$dataForm['state'];
		$dataUpdate['city_name']=$dataForm['city'];
		//prd($dataUpdate);
		//$dataUpdate['country_status']='1';
		$conditionUpdate="city_id='".$cityid."'";
		$db->modify(CITIES,$dataUpdate,$conditionUpdate);
		
		return 1;
	   }
	   
	   
	 public function SaveState($dataForm)
	 { //echo "save state"; die;
		global $mySession;
		$db=new Db();
		
		$sql="select * from ".STATE." where state_name='".$dataForm['state']."'"; 
		$chkQry=$db->runQuery($sql);
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;
		  
		}
		
		//$dataInsert['country_id']=$dataForm['country'];
		$dataInsert['state_name']=$dataForm['state'];
		//$dataInsert['state_status']='1';
		//$conditionUpdate="country_id='".$countryid."'";
	
		$db->save(STATE,$dataInsert);
		
		return 1;
	  }
	  
	 public function SaveCity($dataForm)
	 { //echo "sfdsd"; die;
		global $mySession;
		$db=new Db();
		
		$sql="select * from ".CITIES." where city_name='".$dataForm['city']."'"; 
		$chkQry=$db->runQuery($sql);
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;
		  
		}
		
		//$dataInsert['country_id']=$dataForm['country'];
		$dataInsert['state_id']=$dataForm['state'];
		$dataInsert['city_name']=$dataForm['city'];
		$db->save(CITIES,$dataInsert);
		return 1;
	  }
}
?>