<?php

__autoloadDB('Db');

class Myaccountdb extends Db
{

    public function addaddress($dataForm)
    {

        global $mySession;
        $db = new Db();


        $fulladdress=$dataForm['address'].'||'.$dataForm['address1'];
        $data_insert['user_id']=$mySession->TeeLoggedID;
        $data_insert['public_name']=$dataForm['publicname'];
        $data_insert['address']=$fulladdress;
        $data_insert['city']=$dataForm['city'];
        $data_insert['state']=$dataForm['state'];
        $data_insert['zipcode']=$dataForm['zipcode'];
        $db->save(ADDRESS,$data_insert);
        return 1;
    }


    public function updateuser($dataForm)
    {
        global $mySession;
        $db = new Db();
        $chkQry=$db->runQuery("select * from ".USERS." where  	emailid='".mysql_escape_string($dataForm['emailid'])."' and user_id!='".$mySession->TeeLoggedID."'");

        //error_log('Myaccountdb->updateuser dataForm: '.print_r($dataForm, true));
        if($chkQry!="" and count($chkQry)>0)
        {
            return 0;
        } else {

            $data_update['emailid']=$dataForm['emailid'];
            $data_update['public_name']=$dataForm['publicname'];
            $data_update['bio']=$dataForm['bio'];
            if($dataForm['profile_image']!="" && $dataForm['old_profile_image']!="")
            {
                unlink(SITE_ROOT.'images/profileimages/'.$dataForm['old_profile_image']);
            }

            $profileImage=$dataForm['old_profile_image'];
            if($dataForm['profile_image']!="")
            {
                $profileImage=time()."_".$dataForm['profile_image'];
                @rename(SITE_ROOT.'images/profileimages/'.$dataForm['profile_image'],SITE_ROOT.'images/profileimages/'.$profileImage);
            }

            $data_update['profile_image']=$profileImage;
            $condition="user_id='".$mySession->TeeLoggedID."'";
            $db->modify(USERS,$data_update,$condition);
            return 1;
        }
    }

    public function updateaddress($dataForm)
    {
        global $mySession;
        $db = new Db();

        $fulladdress=$dataForm['address'].'||'.$dataForm['address1'];
        $data_update['public_name']=$dataForm['publicname'];
        $data_update['address']=$fulladdress;
        $data_update['city']=$dataForm['city'];
        $data_update['state']=$dataForm['state'];
        $data_update['zipcode']=$dataForm['zipcode'];
        $condition="user_id='".$mySession->TeeLoggedID."'";
        $db->modify(ADDRESS,$data_update,$condition);
        return 1;
    }

    public function changepass($dataForm)
    {
        global $mySession;
        $db = new Db();
        $data_update['password']=md5($dataForm['newpass']);
        $condition="user_id='".$mySession->TeeLoggedID."'";
        $db->modify(USERS,$data_update,$condition);
        return 1;
    }


    public function emailfriends($dataForm)
    {
        $db = new Db();
        global $mySession;
        $friendsemailid = explode(",",$dataForm['friendsemailid']);
        $teeurl=$dataForm['url'];

        for($i=0; $i<count($friendsemailid); $i++)
        {
            //$TeeLink='<a href="'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'">'.APPLICATION_URL.'launchcampaign/showcampaign/name/'.$teeurl.'</a>';
            $TeeLink='<a href="'.APPLICATION_URL.''.$teeurl.'">'.APPLICATION_URL.''.$teeurl.'</a>';
            $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='9'");
            $messageText=$templateData[0]['email_body'];
            $subject=$templateData[0]['email_subject'];
            $messageText=str_replace("[NAME]",$friendsemailid[$i],$messageText);
            $messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
            $messageText=str_replace("[MYID]",$dataForm['emailid'],$messageText);
            $messageText=str_replace("[TEEURL]",$TeeLink,$messageText);
            SendEmail($friendsemailid[$i],$subject,$messageText);
        }
        return 1;
    }

    public function emailorder($orderno,$emaid)
    {
        $db = new Db();
        global $mySession;

        $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='10'");
        $messageText=$templateData[0]['email_body'];
        $subject=$templateData[0]['email_subject'];
        $messageText=str_replace("[NAME]",$emaid,$messageText);
        $messageText=str_replace("[SITENAME]",SITE_NAME,$messageText);
        $messageText=str_replace("[ORDERNO]",$orderno,$messageText);
        SendEmail($emaid,$subject,$messageText);
        return 1;
    }



    public function signupdb($dataForm)
    {
        $db = new Db();
        global $mySession;

        $chckuname=$db->runquery("Select emailid from users where emailid='".$dataForm['signupemailid']."'");
        if(count($chckuname)>0 && $chckuname!="")
        {
            return 0;
        } else {
            $data_insert['public_name']=$dataForm['publicname'];
            $data_insert['emailid']=$dataForm['signupemailid'];
            $data_insert['password']=md5($dataForm['signuppass']);
            $data_insert['user_status']=0;
            $data_insert['active_status']=0;
            $db->save(USERS,$data_insert);
            $userid=$db->lastInsertId();   // only at insert tym
            $emailid=$dataForm['signupemailid'];

            $ActivationLink='<a href="'.APPLICATION_URL.'login/activate/Id/'.md5($userid).'">'.APPLICATION_URL.'login/activate/Id/'.md5($userid).'</a>';
            $templateData=$db->runQuery("select * from ".EMAIL_TEMPLATES." where template_id='2'");
            $messageText=$templateData[0]['email_body'];
            $subject=$templateData[0]['email_subject'];
            $messageText=str_replace("[NAME]", $emailid, $messageText);
            $messageText=str_replace("[SITENAME]", SITE_NAME, $messageText);
            $messageText=str_replace("[LOGINNAME]", $dataForm['signupemailid'], $messageText);
            $messageText=str_replace("[PASSWORD]", $dataForm['signuppass'], $messageText);
            $messageText=str_replace("[SITEURL]", APPLICATION_URL, $messageText);
            $messageText=str_replace("[ACTIVATIONLINK]", $ActivationLink, $messageText);
            SendEmail($dataForm['signupemailid'], $subject, $messageText);
            return 1;
        }

    }
}