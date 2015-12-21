<?php
__autoloadDB('Db');
class System extends Db
{
	
	
public function unzip($file){

    $zip=zip_open(realpath(".")."/".$file);
    if(!$zip) {return("Unable to proccess file '{$file}'");}


    $e='';

    while($zip_entry=zip_read($zip)) {

       $zdir=dirname(zip_entry_name($zip_entry));
       $zname=zip_entry_name($zip_entry);

       if(!zip_entry_open($zip,$zip_entry,"r")) {$e.="Unable to proccess file '{$zname}'";continue;}
       if(!is_dir($zdir)) mkdirr($zdir,0777);
       #print "{$zdir} | {$zname} \n";
       $zip_fs=zip_entry_filesize($zip_entry);
       if(empty($zip_fs)) continue;
       $zz=zip_entry_read($zip_entry,$zip_fs);
       $z=fopen($zname,"w");
       fwrite($z,$zz);
       fclose($z);
	   
       zip_entry_close($zip_entry);
	   
    }
    zip_close($zip);

    return($e);
}

public function mkdirr($pn,$mode=null) {

  if(is_dir($pn)||empty($pn)) return true;
  $pn=str_replace(array('/', ''),DIRECTORY_SEPARATOR,$pn);

  if(is_file($pn)) {trigger_error('mkdirr() File exists', E_USER_WARNING);return false;}

  $next_pathname=substr($pn,0,strrpos($pn,DIRECTORY_SEPARATOR));
  if(mkdirr($next_pathname,$mode)) {if(!file_exists($pn)) {return mkdir($pn,$mode);} }
  return false;
}


//public function unzip($file)
//	{
//
//    $zip = zip_open($file);
//    if(is_resource($zip)){
//		
//        $tree = "";
//        while(($zip_entry = zip_read($zip)) !== false){
//            echo "Unpacking ".zip_entry_name($zip_entry)."\n";
//			
//            if(strpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR) !== false){
//                $last = strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR);
//                $dir = substr(zip_entry_name($zip_entry), 0, $last);
//                $file = substr(zip_entry_name($zip_entry), strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR)+1);
//                if(!is_dir($dir)){ 
//                    @mkdir($dir, 0755, true) or die("Unable to create $dir\n");
//                }
//                if(strlen(trim($file)) > 0){
//                    $return = @file_put_contents($dir."/".$file, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
//                    if($return === false){
//                        die("Unable to write file $dir/$file\n");
//                    }
//                }
//            }else{
//                file_put_contents($file, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
//            }
//        }
//    }else{
//        echo "Unable to open zip file\n";
//    }
//} 
//public 	function decompress_first_file_from_zip($ZIPContentStr){
////Input: ZIP archive - content of entire ZIP archive as a string
////Output: decompressed content of the first file packed in the ZIP archive
//    //let's parse the ZIP archive
//    //(see 'http://en.wikipedia.org/wiki/ZIP_%28file_format%29' for details)
//    //parse 'local file header' for the first file entry in the ZIP archive
//    if(strlen($ZIPContentStr)<102){
//        //any ZIP file smaller than 102 bytes is invalid
//        printf("error: input data too short<br />\n");
//        return '';
//    }
//    $CompressedSize=binstrtonum(substr($ZIPContentStr,18,4));
//    $UncompressedSize=binstrtonum(substr($ZIPContentStr,22,4));
//    $FileNameLen=binstrtonum(substr($ZIPContentStr,26,2));
//    $ExtraFieldLen=binstrtonum(substr($ZIPContentStr,28,2));
//    $Offs=30+$FileNameLen+$ExtraFieldLen;
//    $ZIPData=substr($ZIPContentStr,$Offs,$CompressedSize);
//    $Data=gzinflate($ZIPData);
//    if(strlen($Data)!=$UncompressedSize){
//        printf("error: uncompressed data have wrong size<br />\n");
//        return '';
//    }
//    else return $Data;
//}
//
//public function binstrtonum($Str){
////Returns a number represented in a raw binary data passed as string.
////This is useful for example when reading integers from a file,
//// when we have the content of the file in a string only.
////Examples:
//// chr(0xFF) will result as 255
//// chr(0xFF).chr(0xFF).chr(0x00).chr(0x00) will result as 65535
//// chr(0xFF).chr(0xFF).chr(0xFF).chr(0x00) will result as 16777215
//    $Num=0;
//    for($TC1=strlen($Str)-1;$TC1>=0;$TC1--){ //go from most significant byte
//        $Num<<=8; //shift to left by one byte (8 bits)
//        $Num|=ord($Str[$TC1]); //add new byte
//    }
//    return $Num;
//} 
	
	
	
	
	
	
	
	
	public function SaveConfiguration($dataForm)
	{
		global $mySession;
		$db=new Db();
		
		$dataUpdate['site_title']=$dataForm['site_title'];
		$dataUpdate['site_description']=$dataForm['site_description'];
		$dataUpdate['site_keyword']=$dataForm['site_keyword'];
		$dataUpdate['admin_email']=$dataForm['admin_email'];

		/*$dataUpdate['paypal_email']=$dataForm['paypal_email'];
		$dataUpdate['paypal_token']=$dataForm['identity_token'];*/
		//$dataUpdate['paypal_email']=$dataForm['paypal_email'];
		//$dataUpdate['currency_symbol']=$dataForm['currency_symbol'];
		//$dataUpdate['credit_toggle']=$dataForm['credit_toggle'];
		$conditionUpdate="admin_id='1'";
		//prd($dataUpdate);
		$db->modify(ADMINISTRATOR,$dataUpdate,$conditionUpdate);
		return true;
	}
	
	
	
	public function SaveTemplate($dataForm)
	{
		global $mySession;
		$db=new Db();
		$chkQry=$db->runQuery("select * from ".EMAIL_TEMPLATES." where email_subject='".$dataForm['email_subject']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{
		$dataPage['email_subject']=$dataForm['email_subject'];
		$dataPage['template_title']=$dataForm['email_body'];
		$db->save(EMAIL_TEMPLATES,$dataPage);
		return 1;	
		}
	}
	
	
	
	
	
	public function UpdateTemplate($dataForm,$templateId)
	{
		global $mySession;		
		$db=new Db();
		$dataUpdate['email_subject']=$dataForm['email_subject'];
		$dataUpdate['email_body']=$dataForm['email_body'];
		$conditionUpdate="template_id='".$templateId."'";
		$db->modify(EMAIL_TEMPLATES,$dataUpdate,$conditionUpdate);
		
		//Code to send newsletter email to subscribed members
		if(isset($_REQUEST['save_or_send']) && $_REQUEST['save_or_send']=='2')
		{
			$newsuserData=$db->runQuery("select * from ".USERS." where newsletter_subscribe='1'");
			if($newsuserData!="" and count($newsuserData)>0)
			{
				foreach($newsuserData as $key=>$valueUserData)
				{
					SendEmail($valueUserData['email_address'],$dataForm['email_subject'],$dataForm['email_body']);
				}
			}			
		}
		//Code to send newsletter email to subscribed members
		return true;
	}
	public function SavePlan($dataForm)
	{
		global $mySession;
		$db=new Db();
		$chkQry=$db->runQuery("select * from ".SUBSCRIPTIONS." where plan_name='".$dataForm['plan_name']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{
			$planImage="";
			if($dataForm['plan_image']!="")
			{
			$planImage=time()."_".$dataForm['plan_image'];
			@rename(SITE_ROOT.'images/planimg/'.$dataForm['plan_image'],SITE_ROOT.'images/planimg/'.$planImage);
			}
		$dataPlan['plan_name']=$dataForm['plan_name'];
		$dataPlan['plan_image']=$planImage;
		$dataPlan['nof_images']=$dataForm['nof_images'];
		$dataPlan['is_free']=$dataForm['Isfree'];
		if($dataForm['Isfree']==0)
		{
		$dataPlan['plan_price']=$dataForm['plan_price'];
		$dataPlan['plan_validity']=$dataForm['plan_validity'];
		}
		$dataPlan['des_box']=$dataForm['des_box'];
		$dataPlan['featured_business']=$dataForm['featured_business'];
		$dataPlan['offer_coupons']=$dataForm['offer_coupons'];
		$db->save(SUBSCRIPTIONS,$dataPlan);
		return 1;	
		}
	}
	public function UpdatePlan($dataForm,$planId)
	{
		global $mySession;
		$db=new Db();
		$chkQry=$db->runQuery("select * from ".SUBSCRIPTIONS." where plan_name='".$dataForm['plan_name']."' and plan_id!='".$planId."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;	
		}
		else
		{
			$planImage=$dataForm['old_plan_image'];
			if($dataForm['plan_image']!="")
			{
				if($dataForm['old_plan_image']!="")
				{
				unlink(SITE_ROOT.'images/planimg/'.$dataForm['old_plan_image']);
				}
			$planImage=time()."_".$dataForm['plan_image'];
			@rename(SITE_ROOT.'images/planimg/'.$dataForm['plan_image'],SITE_ROOT.'images/planimg/'.$planImage);
			}
		$dataPlan['plan_name']=$dataForm['plan_name'];
		$dataPlan['plan_image']=$planImage;
		$dataPlan['nof_images']=$dataForm['nof_images'];
		$dataPlan['is_free']=$dataForm['Isfree'];
		if($dataForm['Isfree']==0)
		{
		$dataPlan['plan_price']=$dataForm['plan_price'];
		$dataPlan['plan_validity']=$dataForm['plan_validity'];
		}
		$dataPlan['des_box']=$dataForm['des_box'];
		$dataPlan['featured_business']=$dataForm['featured_business'];
		$dataPlan['offer_coupons']=$dataForm['offer_coupons'];
		$conditionUpdate="plan_id='".$planId."'";
		$db->modify(SUBSCRIPTIONS,$dataPlan,$conditionUpdate);
		return 1;	
		}
	}
	
	
	  /**** Manage Add update query ****/
	  public function Updatemanagead($dataForm,$ad_image)
	{
		global $mySession;
		$db=new Db();
		          
		 $dataUpdate['ad_image']=$ad_image;//exit;
		$dataUpdate['ad_url']=$dataForm['ad_url'];
		$conditionUpdate="admin_id='1'";
		$db->modify(ADMINISTRATOR,$dataUpdate,$conditionUpdate);
		return true;
	}
	
	
}
?>