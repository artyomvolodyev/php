<?php
__autoloadDB('Db');
class Campaigndb extends Db
{
		

		
		
		public function emailfriends($dataForm)
		{
			$db = new Db();
			global $mySession;
			
			//$friendsemailid=$dataForm['friendsemailid'];
			
			$friendsemailid = explode(",",$dataForm['friendsemailid']);
			//prd($friendsemailid);	
			//$emailid=$dataForm['signupemailid'];
			
			for($i=0; $i<count($friendsemailid); $i++)
			{
			//$TeeLink='<a href="'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'">'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'</a>';
			
			//$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='9'");
			
			$messageText=$dataForm['content'];
			
			$subject=$dataForm['subject'];
	
			SendEmail($friendsemailid[$i],$subject,$messageText);

			
			}
			return 1;
		}
		
		
		
		
		
		
		//public function updateaddress($dataForm)
//		{
//			//$data_update['public_name']=$dataForm['publicname'];
//			global $mySession;
//			$db = new Db();
//			
//			$fulladdress=$dataForm['address'].'||'.$dataForm['address1'];
//			
//			$data_update['public_name']=$dataForm['publicname'];
//			$data_update['address']=$fulladdress;
//			$data_update['city']=$dataForm['city'];
//			$data_update['state']=$dataForm['state'];
//			$data_update['zipcode']=$dataForm['zipcode'];
//			
//			$condition="user_id='".$mySession->TeeLoggedID."'";
//			//pr($data_update);
////			prd($condition);
//			$db->modify(ADDRESS,$data_update,$condition);
//			
//			return 1;
//		}
		
}






//public function savecampaign($dataForm)
//		{
//			global $mySession;
//			$db = new Db();
//			
//		
//			
//			$data_insert['base_price']=$mySession->baseprice;
//			$data_insert['goal']=$mySession->no_of_t;
//			$data_insert['selling_price']=$mySession->setgoalvalues['sellingprice'];
//			
//			$data_insert['title']=$dataForm['camptitle'];
//			$data_insert['description']=$dataForm['description'];
//			$data_insert['campaign_length']=$dataForm['no_ofdays'];
//			
//			$data_insert['url']=$dataForm['url'];
//			
//			$data_insert['launch_date']=date('Y-m-d H:i:s');
//			
//			$data_insert['campaign_length']=$dataForm['no_ofdays'];
//			$data_insert['user_id']=$mySession->TeeLoggedID;
//			$data_insert['campaign_status']=1;
//			
//			if($_REQUEST['newcheckbx']=='1')
//			{
//				$data_insert['shipping_option']=1;
//				$data_insert['new_address']=1;
//				$dataInsert['user_id']=$mySession->TeeLoggedID;
//				$dataInsert['url']=$dataForm['url'];
//				$dataInsert['fname']=$dataForm['firstname'];
//				$dataInsert['lname']=$dataForm['lastname'];
//				$dataInsert['address']=$dataForm['newaddress'];
//				$dataInsert['city']=$dataForm['newcity'];
//				$dataInsert['state']=$dataForm['newstate'];
//				$dataInsert['zipcode']=$dataForm['newzipcode'];
//				$dataInsert['instruction']=$dataForm['instruction'];
//				
//				//prd($dataInsert);
//				
//				$db->save(SHIPPING_ADDRESS,$dataInsert);
//			}
//			
//			//prd($data_insert);
//			$db->save(LAUNCHCAMPAIGN,$data_insert);
//			
//			
//			return 1;		
//		}