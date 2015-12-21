<?php
__autoloadDB('Db');
class Users extends Db
{

	public function CheckForgotpass($dataForm)
	{
		global $mySession;
		$db=new Db();
		
		$chkQry=$db->runQuery("select * from ".USERS." where emailid='".mysql_escape_string($dataForm['emailid'])."'");
	
		if($chkQry!="" and count($chkQry)>0)
		{
			$dataUpdate['pass_reset']=md5($chkQry[0]['user_id']);
			
			$conditionUpdate="user_id='".$chkQry[0]['user_id']."'";
			
			$db->modify(USERS,$dataUpdate,$conditionUpdate);
			
			//code to send password reset email
			
			$emailid=$chkQry[0]['emailid'];
			
			$Urls='<a href="'.APPLICATION_URL.'login/reset/requestId/'.md5($chkQry[0]['user_id']).'">'.APPLICATION_URL.'login/reset/requestId/'.md5($chkQry[0]['user_id']).'</a>';
			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='3'");
			
			$messageText=$templateData[0]['email_body'];
			
			$messageText=str_replace("[NAME]",$emailid,$messageText);
			
			$messageText=str_replace("[PASSWORDRESETURL]",$Urls,$messageText);
			
			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
			
			$subject="forgotpassword mail";
			//echo $messageText; exit;
			SendEmail($dataForm['emailid'],$subject,$messageText);
			//code to send password reset email
			return $chkQry[0]['user_id'];
		}
		
	   }
	   
	   
	public function ResetNewPassword($dataForm,$requestId)
	{ 
		global $mySession;
		$db=new Db();
		$chkData=$db->runQuery("select * from ".USERS." where pass_reset='".$requestId."'");
		if($chkData!="" and count($chkData)>0)
		{
			$dataUpdate['password']=md5($dataForm['newpassword']);
			$dataUpdate['pass_reset']="";
			$conditionUpdate="user_id='".$chkData[0]['user_id']."'";
			//prd($dataUpdate);
			$db->modify(USERS,$dataUpdate,$conditionUpdate);	
		}
		return 1;
	}
}
?>