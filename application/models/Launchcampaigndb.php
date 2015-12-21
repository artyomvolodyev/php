<?php

__autoloadDB('Db');

class Launchcampaigndb extends Db
{
	    public function saveall()
		{
			global $mySession;
			$db = new Db();

            error_log('Launchcampaigndb->saveall()');

			$data_insert['user_id']=$mySession->TeeLoggedID;
			$data_insert['base_price']=$mySession->baseprice;
			$data_insert['goal']=$mySession->no_of_t;
			$data_insert['customimage']=$mySession->customimage;
			$data_insert['selling_price']=$mySession->setgoalvalues['sellingprice'];
			$data_insert['title']=$mySession->camptitl;
			$data_insert['description']=$mySession->descrip;
			$data_insert['campaign_length']=$mySession->camplength;
			$data_insert['url']=$mySession->showurl;

			if($mySession->selectedIdValueKM!='' && isset($mySession->selectedIdValueKM)){
			    $data_insert['SelectedProduct']=$mySession->selectedIdValueKM;
            }

			$data_insert['launch_date']=date('Y-m-d H:i:s');

			$qryImage = "select * from ".MANAGEIMAGENAME." WHERE id = ".$mySession->mig_id;

		    $image=$db->runquery($qryImage);
            if($image && count($image)){
                 if($image[0]['frontimage']!=''){
                    $data_insert['tee_image']=$image[0]['frontimage'];
                 }
                 if($image[0]['backimage']!=''){
                    $data_insert['tee_back_image']=$image[0]['backimage'];
                 }
            }

			if(isset($mySession->dataURLDbFront) && $mySession->dataURLDbFront!="")
                $data_insert['dataURLDbFront']=urlencode($mySession->dataURLDbFront);

			if(isset($mySession->dataURLDbBack) && $mySession->dataURLDbBack!="")
                $data_insert['dataURLDbBack']=urlencode($mySession->dataURLDbBack);

			if(isset($mySession->recreation_product) && $mySession->recreation_product!="")
                $data_insert['normalImageData']=$mySession->recreation_product;

			$data_insert['campaign_status']=1;
			$data_insert['draft_status']=1;
			$data_insert['admin_status']=1;
			$data_insert['campaign_category']=$mySession->campaign_category;

            //error_log('Launchcampaigndb->saveall() data_insert: '.print_r($data_insert, true));
			$db->save(LAUNCHCAMPAIGN,$data_insert);
			$launchid = $db->lastInsertId();
            //error_log('Launchcampaigndb->saveall() data_insert launchid: '.$launchid);

            if($mySession->mig_id){
                error_log('Launchcampaigndb->saveall() Remove manage image');
			    $db->delete(MANAGEIMAGENAME, array('id = ?' => $mySession->mig_id));
                $mySession->mig_id = 0;
                unset($mySession->mig_id);
            }

            if($mySession->recreation_product){
                $mySession->recreation_product = false;
                unset($mySession->recreation_product);
            }

			$value=$db->runQuery("select * from ".LAUNCHCAMPAIGN." where campaign_status='1' and campaign_category='1' and campaign_id='".$launchid."'");

			$row2=$db->runQuery("select DATE_ADD('".$value[0]['launch_date']."', INTERVAL '".$value[0]['campaign_length']."' DAY) AS nextDate,DATE_ADD('".$value[0]['launch_date']."', INTERVAL -1 DAY) AS prevDate");

			$Data=$db->runQuery("select * from ".USERS." where user_id='".$mySession->TeeLoggedID."'");
			$emaid=$Data[0]['emailid'];
			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='11'");
			$messageText=$templateData[0]['email_body'];
			$subject=$templateData[0]['email_subject'];
			$messageText=str_replace("[NAME]",$emaid,$messageText);
			$messageText=str_replace("[CAMPAIGN_TITLE]",$mySession->camptitl,$messageText);
			$messageText=str_replace("[TSHIRTS]",$mySession->no_of_t,$messageText);
			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
			//$messageText=str_replace("[ORDERNO]",$orderno,$messageText);
			SendEmail($emaid,$subject,$messageText);

			return $launchid;
		}

		public function savesetgoal($dataForm)
		{
			global $mySession;
			$db = new Db();

			$data_insert['user_id']=$mySession->TeeLoggedID;

			$data_insert['base_price']=$mySession->baseprice;
			$data_insert['goal']=$mySession->no_of_t;
			$data_insert['selling_price']=$mySession->setgoalvalues['sellingprice'];
			$data_insert['customimage']=$mySession->customimage;
            $data_insert['draft_date']=date('Y-m-d H:i:s');

            if($mySession->selectedIdValueKM!='' && isset($mySession->selectedIdValueKM)){
                $data_insert['SelectedProduct']=$mySession->selectedIdValueKM;
            }
			if(isset($mySession->recreation_product) && $mySession->recreation_product!=""){
			    $data_insert['normalImageData']=$mySession->recreation_product;
            }

			$db->save(LAUNCHCAMPAIGN,$data_insert);
			$campid=$db->lastInsertId();
            //error_log('savesetgoal() data_insert: '.print_r($data_insert, true).', created new campaign (draft) id: '.$campid);
			$datainsert['campaign_id']=$campid;
			$datainsert['user_id']=$mySession->TeeLoggedID;
			$db->save(DRAFTS,$datainsert);
			return $campid;
		}


		public function savecampaign($dataForm,$lid)
		{
			global $mySession;
			$db = new Db();
			$data_update['title']=$dataForm['camptitle'];
			$data_update['description']=$dataForm['description'];
			$data_update['campaign_length']=$dataForm['no_ofdays'];

			if($mySession->selectedIdValueKM!='' && isset($mySession->selectedIdValueKM))

			$data_update['SelectedProduct']=$mySession->selectedIdValueKM;

			if(isset($mySession->recreation_product) && $mySession->recreation_product!="")

			$data_update['normalImageData']=$mySession->recreation_product;
			$data_update['customimage']=$mySession->customimage;


			if(isset($mySession->dataURLDbFront) && $mySession->dataURLDbFront!="")

		    $data_update['dataURLDbFront']=urlencode($mySession->dataURLDbFront);

			if(isset($mySession->dataURLDbBack) && $mySession->dataURLDbBack!="")

		    $data_update['dataURLDbBack']=urlencode($mySession->dataURLDbBack);
			$data_update['url']=$dataForm['url'];
			$data_update['launch_date']=date('Y-m-d H:i:s');
			$qryImage="select * from ".MANAGEIMAGENAME." ";
		    $image=$db->runquery($qryImage);
            if($image[0]['frontimage']!='')
			    $data_update['tee_image']=$image[0]['frontimage'];

            if($image[0]['backimage']!='')
                $data_update['tee_back_image']=$image[0]['backimage'];

			//$data_update['user_id']=$mySession->TeeLoggedID;
			$data_update['campaign_status']=1;
			$data_update['draft_status']=1;
			$data_update['admin_status']=1;
			$data_update['campaign_category']=$dataForm['campaign_category'];

			//if($_REQUEST['newcheckbx']=='1')
//			{
//				$data_update['shipping_option']=1;

//				$data_update['new_address']=1;

//				$dataInsert['user_id']=$mySession->TeeLoggedID;

//				$dataInsert['url']=$dataForm['url'];

//				$dataInsert['fname']=$dataForm['firstname'];

//				$dataInsert['lname']=$dataForm['lastname'];

//				$dataInsert['address']=$dataForm['newaddress'];

//				$dataInsert['city']=$dataForm['newcity'];

//				$dataInsert['state']=$dataForm['newstate'];

//				$dataInsert['zipcode']=$dataForm['newzipcode'];

//				$dataInsert['instruction']=$dataForm['instruction'];

//				//prd($dataInsert);

//

//				$db->save(SHIPPING_ADDRESS,$dataInsert);

//			}



			$condition="campaign_id='".$lid."'";

			//$condition="user_id='".$mySession->TeeLoggedID."'";

			//prd($condition);



			/*echo "model save campaign ";

			pr($condition);

			prd($data_update);

			*/

			//prd($data_update);

			$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);



			$db->delete(DRAFTS,$condition);

			$db->delete(MANAGEIMAGENAME);



			$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." where campaign_id='".$lid."'");



			$Data1=$db->runQuery("select * from ".USERS." where user_id='".$Data[0]['user_id']."'");





			$emaid=$Data1[0]['emailid'];





			//echo "email id : ".$emaid; die;

			//$TeeLink='<a href="'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'">'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'</a>';



			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='11'");



			$messageText=$templateData[0]['email_body'];



			$subject=$templateData[0]['email_subject'];



			$messageText=str_replace("[NAME]",$emaid,$messageText);



			$messageText=str_replace("[CAMPAIGN_TITLE]",$dataForm['camptitle'],$messageText);



			$messageText=str_replace("[TSHIRTS]",$Data[0]['goal'],$messageText);



			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);



			//$messageText=str_replace("[ORDERNO]",$orderno,$messageText);



			SendEmail($emaid,$subject,$messageText);

			//$dataupdate['resume_status']=1;



			//$db->modify(DRAFTS,$dataupdate,$condition);



			return 1;

		}




		public function relaunchcampaign($dataForm , $cid)

		{

			global $mySession;

			$db = new Db();



			$data_update['base_price']=$mySession->baseprice;

			$data_update['goal']=$mySession->no_of_t;

			$data_update['selling_price']=$mySession->setgoalvalues['sellingprice'];

			if($mySession->selectedIdValueKM!='' && isset($mySession->selectedIdValueKM))

			$data_update['SelectedProduct']=$mySession->selectedIdValueKM;

			$data_update['title']=$dataForm['camptitle'];

			$data_update['description']=$dataForm['description'];

			$data_update['campaign_length']=$dataForm['no_ofdays'];

			$data_update['customimage']=$dataForm['customimage'];


		/*	if(isset($mySession->setFrontImageIcon) && $mySession->setFrontImageIcon!="")

			$data_update['tee_image']=$mySession->setFrontImageIcon;

			if(isset($mySession->setBackImageIcon) && $mySession->setBackImageIcon!="")

			$data_update['tee_back_image']=$mySession->setBackImageIcon;

			$data_update['url']=$dataForm['url'];*/

			if(isset($mySession->recreation_product) && $mySession->recreation_product!="")

					if(isset($mySession->dataURLDbFront) && $mySession->dataURLDbFront!="")

		    $data_update['dataURLDbFront']=urlencode($mySession->dataURLDbFront);

			if(isset($mySession->dataURLDbBack) && $mySession->dataURLDbBack!="")

		    $data_update['dataURLDbBack']=urlencode($mySession->dataURLDbBack);

				$qryImage="select * from ".MANAGEIMAGENAME." ";

		    $image=$db->runquery($qryImage);

			//foreach($qryImage1 as $image)

			 if($image[0]['frontimage']!='')

			$data_update['tee_image']=$image[0]['frontimage'];

			 if($image[0]['backimage']!='')

			$data_update['tee_back_image']=$image[0]['backimage'];





			$data_update['normalImageData']=$mySession->recreation_product;

			$data_update['launch_date']=date('Y-m-d H:i:s');

			$data_update['campaign_category']=$dataForm['campaign_category'];

			$data_update['campaign_length']=$dataForm['no_ofdays'];

			//$data_update['user_id']=$mySession->TeeLoggedID;

			$data_update['campaign_status']=1;



			$condition="campaign_id='".$cid."'";



			/*echo "model relaunch campaign ";

			pr($condition);

			prd($data_update);

			*/

		//	if($_REQUEST['newcheckbx']=='1')

//			{

//				$chkQry=$db->runQuery("select * from ".SHIPPING_ADDRESS." where url='".$dataForm['url']."'");

//

//				if($chkQry!="" and count($chkQry)>0)

//				{

//					//echo "address exists"; die;

//					$data_update['shipping_option']=1;

//					$data_update['new_address']=1;

//					$dataUpdate['user_id']=$mySession->TeeLoggedID;

//					$dataUpdate['url']=$dataForm['url'];

//					$dataUpdate['fname']=$dataForm['firstname'];

//					$dataUpdate['lname']=$dataForm['lastname'];

//					$dataUpdate['address']=$dataForm['newaddress'];

//					$dataUpdate['city']=$dataForm['newcity'];

//					$dataUpdate['state']=$dataForm['newstate'];

//					$dataUpdate['zipcode']=$dataForm['newzipcode'];

//					$dataUpdate['instruction']=$dataForm['instruction'];

//

//					$conditionUp="url='".$dataForm['url']."'";

//					//pr($dataUpdate);

//					//pr($conditionUp);

//

//					$db->modify(SHIPPING_ADDRESS,$dataUpdate,$conditionUp);

//				}

//				else

//				{

//					$data_insert['shipping_option']=1;

//					$data_insert['new_address']=1;

//					$dataInsert['user_id']=$mySession->TeeLoggedID;

//					$dataInsert['url']=$dataForm['url'];

//					$dataInsert['fname']=$dataForm['firstname'];

//					$dataInsert['lname']=$dataForm['lastname'];

//					$dataInsert['address']=$dataForm['newaddress'];

//					$dataInsert['city']=$dataForm['newcity'];

//					$dataInsert['state']=$dataForm['newstate'];

//					$dataInsert['zipcode']=$dataForm['newzipcode'];

//					$dataInsert['instruction']=$dataForm['instruction'];

//

//					$db->save(SHIPPING_ADDRESS,$dataInsert);

//				}

//

//			}



			$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

				 $db->delete(MANAGEIMAGENAME);

			$Data=$db->runQuery("select * from ".LAUNCHCAMPAIGN." where campaign_id='".$cid."'");



			$Data1=$db->runQuery("select * from ".USERS." where user_id='".$Data[0]['user_id']."'");







			$emaid=$Data1[0]['emailid'];









			//$TeeLink='<a href="'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'">'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'</a>';



			$templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='11'");



			$messageText=$templateData[0]['email_body'];



			$subject=$templateData[0]['email_subject'];



			$messageText=str_replace("[NAME]",$emaid,$messageText);



			$messageText=str_replace("[CAMPAIGN_TITLE]",$dataForm['camptitle'],$messageText);



			$messageText=str_replace("[TSHIRTS]",$mySession->no_of_t,$messageText);



			$messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);



			//$messageText=str_replace("[ORDERNO]",$orderno,$messageText);



			SendEmail($emaid,$subject,$messageText);



			return 1;

		}

		public function resumecampaign($dataForm , $cidresume)

		{

			global $mySession;

			$db = new Db();



			$data_update['title']=$dataForm['camptitle'];

			$data_update['description']=$dataForm['description'];

			$data_update['campaign_length']=$dataForm['no_ofdays'];

			if($mySession->selectedIdValueKM!='' && isset($mySession->selectedIdValueKM))

			$data_update['SelectedProduct']=$mySession->selectedIdValueKM;

			$data_update['url']=$dataForm['url'];



			$data_update['launch_date']=date('Y-m-d H:i:s');



			$data_update['campaign_length']=$dataForm['no_ofdays'];

			$data_update['campaign_status']=1;

			$data_update['draft_status']=1;

			/*	if(isset($mySession->setFrontImageIcon) && $mySession->setFrontImageIcon!="")

			$data_update['tee_image']=$mySession->setFrontImageIcon;

			if(isset($mySession->setBackImageIcon) && $mySession->setBackImageIcon!="")

			$data_update['tee_back_image']=$mySession->setBackImageIcon;*/

			if(isset($mySession->recreation_product) && $mySession->recreation_product!="")

			$data_update['normalImageData']=$mySession->recreation_product;

					if(isset($mySession->dataURLDbFront) && $mySession->dataURLDbFront!="")

		    $data_update['dataURLDbFront']=urlencode($mySession->dataURLDbFront);

			if(isset($mySession->dataURLDbBack) && $mySession->dataURLDbBack!="")

		    $data_update['dataURLDbBack']=urlencode($mySession->dataURLDbBack);

				$qryImage="select * from ".MANAGEIMAGENAME." ";

		    $image=$db->runquery($qryImage);

		//	foreach($qryImage1 as $image)

			 if($image[0]['frontimage']!='')

			$data_update['tee_image']=$image[0]['frontimage'];

			 if($image[0]['backimage']!='')

			$data_update['tee_back_image']=$image[0]['backimage'];

				$data_update['campaign_category']=$dataForm['campaign_category'];



			$condition="campaign_id='".$cidresume."'";



			$data_Update['resume_status']=1;

			$condition_Upp="campaign_id='".$cidresume."'";





		/*	echo "model resume ";

			pr($condition_Upp);

			prd($data_update);*/



			//if($_REQUEST['newcheckbx']=='1')

//			{

//				$chkQry=$db->runQuery("select * from ".SHIPPING_ADDRESS." where url='".$dataForm['url']."'");

//

//				if($chkQry!="" and count($chkQry)>0)

//				{

//					//echo "address exists"; die;

//					$data_update['shipping_option']=1;

//					$data_update['new_address']=1;

//					$dataUpdate['user_id']=$mySession->TeeLoggedID;

//					$dataUpdate['url']=$dataForm['url'];

//					$dataUpdate['fname']=$dataForm['firstname'];

//					$dataUpdate['lname']=$dataForm['lastname'];

//					$dataUpdate['address']=$dataForm['newaddress'];

//					$dataUpdate['city']=$dataForm['newcity'];

//					$dataUpdate['state']=$dataForm['newstate'];

//					$dataUpdate['zipcode']=$dataForm['newzipcode'];

//					$dataUpdate['instruction']=$dataForm['instruction'];

//

//					$conditionUp="url='".$dataForm['url']."'";

//					//pr($dataUpdate);

//					//pr($conditionUp);

//

//					$db->modify(SHIPPING_ADDRESS,$dataUpdate,$conditionUp);

//				}

//				else

//				{

//					$data_update['shipping_option']=1;

//					$data_update['new_address']=1;

//					$dataInsert['user_id']=$mySession->TeeLoggedID;

//					$dataInsert['url']=$dataForm['url'];

//					$dataInsert['fname']=$dataForm['firstname'];

//					$dataInsert['lname']=$dataForm['lastname'];

//					$dataInsert['address']=$dataForm['newaddress'];

//					$dataInsert['city']=$dataForm['newcity'];

//					$dataInsert['state']=$dataForm['newstate'];

//					$dataInsert['zipcode']=$dataForm['newzipcode'];

//					$dataInsert['instruction']=$dataForm['instruction'];

//

//					$db->save(SHIPPING_ADDRESS,$dataInsert);

//				}

//

//			}

			//pr($dataUpdate);

			//prd($conditionUp);



			$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

				 $db->delete(MANAGEIMAGENAME);

			$db->modify(DRAFTS,$data_Update,$condition_Upp);



			return 1;

		}







		public function editcampdetails($dataForm , $cid)

		{

			global $mySession;

			$db = new Db();



			$data_update['title']=$dataForm['camptitle'];

			$data_update['description']=$dataForm['description'];



			$condition="campaign_id='".$cid."'";



			//pr($data_update);

			//pr($condition);

	//	$chkQry=$db->runQuery("select * from ".SHIPPING_ADDRESS." where url='".$dataForm['url']."'");

//

//				if($chkQry!="" and count($chkQry)>0)

//				{

//					$data_update['shipping_option']=1;

//					$dataUpdate['instruction']=$dataForm['instruction'];

//					$dataUpdate['fname']=$dataForm['firstname'];

//					$dataUpdate['lname']=$dataForm['lastname'];

//					$dataUpdate['address']=$dataForm['newaddress'];

//					$dataUpdate['city']=$dataForm['newcity'];

//					$dataUpdate['state']=$dataForm['newstate'];

//					$dataUpdate['zipcode']=$dataForm['newzipcode'];

//

//					$url_val = $dataForm['url'];

//

//					$conditionUp="url='".$dataForm['url']."'";

//					$db->modify(SHIPPING_ADDRESS,$dataUpdate,$conditionUp);

//				}

//				else

//				{

//					$data_update['shipping_option']=1;

//					$data_update['new_address']=1;

//					$dataInsert['user_id']=$mySession->TeeLoggedID;

//					$dataInsert['url']=$dataForm['url'];

//					$dataInsert['fname']=$dataForm['firstname'];

//					$dataInsert['lname']=$dataForm['lastname'];

//					$dataInsert['address']=$dataForm['newaddress'];

//					$dataInsert['city']=$dataForm['newcity'];

//					$dataInsert['state']=$dataForm['newstate'];

//					$dataInsert['zipcode']=$dataForm['newzipcode'];

//					$dataInsert['instruction']=$dataForm['instruction'];

//

//					$db->save(SHIPPING_ADDRESS,$dataInsert);

//				}

				//pr($dataUpdate);

				//prd($conditionUp);



				$db->modify(LAUNCHCAMPAIGN,$data_update,$condition);

				return 1;



		}





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