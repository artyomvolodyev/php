<?php
__autoloadDB('Db');

class LaunchcampaignController extends AppController
{
    public function indexAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');

        $this->view->pageTitle="Launch your campaign";

        $qry                 = "select * from " . TSHIRT_PRODUCTS . " where status='1' order by t_sort";
        $product             = $db->runquery($qry);

        $this->view->product = $product;
        $qry2                = "select * from " . TSHIRT_ICONS . " where status='1'";
        $icons               = $db->runquery($qry2);

        $this->view->icons   = $icons;

        if($mySession->mig_id){
            $qryImageSql   = "select * from " . MANAGEIMAGENAME . " WHERE id = ".(int)$mySession->mig_id;
            $qryImage1  = $db->runquery($qryImageSql);
            if($qryImage1 && count($qryImage1)){
                unlink(SITE_ROOT . 'images/usersdesign/' . $qryImage1[0]['frontimage']);
                unlink(SITE_ROOT . 'images/usersdesign/' . $qryImage1[0]['backimage']);
            }
            error_log('LaunchcampaignController->indexAction() Remove manage image');
            $db->delete(MANAGEIMAGENAME, array('id = ?' => $mySession->mig_id));
            $mySession->mig_id = 0;
        }
        if($mySession->lid){
            error_log('LaunchcampaignController->indexAction() Remove campaign draft $lid '.$mySession->lid.', reset $lid session variable');
            $db->delete(LAUNCHCAMPAIGN, array('campaign_id = ?' => $mySession->lid));
            $mySession->lid = 0;
        }

        if($mySession->recreation_product){
            $this->view->recreation_product = $mySession->recreation_product;
        }else{
            $this->view->recreation_product = false;
        }

        $mySession->camptitl = "";
        unset($mySession->camptitl);
        $mySession->descrip = "";
        unset($mySession->descrip);
        $mySession->camplength = "";
        unset($mySession->camplength);
        $mySession->showurl = "";
        unset($mySession->showurl);
    }

    public function recreationAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('main');
        //	echo $_REQUEST['recreation_product'];
    }

    public function templatesAction(){
        // this is needed for loading the HTML/PHP templates for the FancyProductDesigner in version 3.x
        $this->_helper->layout()->disableLayout();
        $fancyProductDesignerTemplate = basename($this->getRequest()->getRequestUri());
        $this->view->fancyProductDesignerTemplate = $fancyProductDesignerTemplate;
    }

    public function campaignendsAction()
    {
        global $mySession;
        $db  = new Db();
        //$sql=$db->runquery("select * from ".LAUNCHCAMPAIGN." where DATE_ADD(launch_date, INTERVAL campaign_length DAY)=curdate() and mail_sent='0'");
        $sql = $db->runquery("select * from launchcampaign where DATE_FORMAT(DATE_ADD(launch_date, INTERVAL campaign_length DAY),'%Y-%m-%d')=curdate() and mail_sent='0'");
        //	echo "select * from launchcampaign where DATE_FORMAT(DATE_ADD(launch_date, INTERVAL campaign_length DAY),'%Y-%m-%d')=curdate()"; die;
        //	echo "select *,DATE_ADD(launch_date, INTERVAL campaign_length DAY) as myEndDate,curdate() as TodayDate from ".LAUNCHCAMPAIGN." where DATE_ADD(launch_date, INTERVAL campaign_length DAY)=curdate()"; die;
        // and mail_sent=0
        foreach ($sql as $key => $value1) {
            if (($value1['sold'] > $value1['goal']) || ($value1['sold'] == $value1['goal'])) {
                $adminemail   = $db->runQuery("select * from " . ADMINISTRATOR . "");
                $adminid      = $adminemail[0]['admin_email'];
                $data         = $db->runQuery("select * from " . USERS . " where user_id='" . $value1['user_id'] . "'");
                $creatorid    = $data[0]['emailid'];
                //echo "admin email ID gggggg : ".$adminid;
                //echo "Creator email ID  ggggg: ".$creatorid; die;
                $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='13'");
                $messageText  = $templateData[0]['email_body'];
                $subject      = $templateData[0]['email_subject'];
                $messageText  = str_replace("[CAMPAIGN_TITLE]", $value1['title'], $messageText);
                $messageText  = str_replace("[SOLD]", $value1['sold'], $messageText);
                $messageText  = str_replace("[GOAL]", $value1['goal'], $messageText);
                $messageText  = str_replace("[NAME]", $adminid, $messageText);
                $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                SendEmail($adminid, $subject, $messageText);
                //  -------------------Mail admin for payment --------------------  //
                $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='17'");
                $messageText  = $templateData[0]['email_body'];
                $subject      = $templateData[0]['email_subject'];
                $messageText  = str_replace("[CAMPAIGN_TITLE]", $value1['title'], $messageText);
                $messageText  = str_replace("[SOLD]", $value1['sold'], $messageText);
                $messageText  = str_replace("[GOAL]", $value1['goal'], $messageText);
                $messageText  = str_replace("[NAME]", $creatorid, $messageText);
                $messageText  = str_replace("[ADMIN]", $adminid, $messageText);
                $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                SendEmail($creatorid, $subject, $messageText);
                //----------------mail to buyers thst shirts r printed-------------------- //
                $qry = $db->runQuery("select * from " . ORDER_RECORD . " where teeurl='" . $value1['url'] . "'");
                if ($qry != "" and count($qry) > 0) {
                    for ($i = 0; $i < count($qry); $i++) {
                        if ($i == 0) {
                            $emailfrnz[$i] = $qry[$i]['emailid'];
                        } else {
                            if (!in_array($qry[$i]['emailid'], $emailfrnz)) {
                                $emailfrnz[$i] = $qry[$i]['emailid'];
                            }
                        }
                    }
                    $emailfrnz_val = implode(",", $emailfrnz);
                    //prd($emailfrnz_val);
                }
                $friendsemailid = explode(",", $emailfrnz_val);
                //prd($friendsemailid);
                for ($i = 0; $i < count($friendsemailid); $i++) {
                    $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='15'");
                    $messageText  = $templateData[0]['email_body'];
                    $subject      = $templateData[0]['email_subject'];
                    $messageText  = str_replace("[NAME]", $friendsemailid[$i], $messageText);
                    $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                    $messageText  = str_replace("[CAMPAIGN_TITLE]", $value1['title'], $messageText);
                    $messageText  = str_replace("[TEEURL]", $TeeLink, $messageText);
                    SendEmail($friendsemailid[$i], $subject, $messageText);
                }
            } else {
                $data         = $db->runQuery("select * from " . USERS . " where user_id='" . $value1['user_id'] . "'");
                $creatorid    = $data[0]['emailid'];
                //echo "Creator email ID vvv : ".$creatorid; die;
                $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='12'");
                $messageText  = $templateData[0]['email_body'];
                $subject      = $templateData[0]['email_subject'];
                $messageText  = str_replace("[CAMPAIGN_TITLE]", $value1['title'], $messageText);
                $messageText  = str_replace("[SOLD]", $value1['sold'], $messageText);
                $messageText  = str_replace("[GOAL]", $value1['goal'], $messageText);
                $messageText  = str_replace("[NAME]", $creatorid, $messageText);
                $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                SendEmail($creatorid, $subject, $messageText);
                //-------------------------------email to buyers --------------//
                $qry = $db->runQuery("select * from " . ORDER_RECORD . " where teeurl='" . $value1['url'] . "'");
                if ($qry != "" and count($qry) > 0) {
                    for ($i = 0; $i < count($qry); $i++) {
                        if ($i == 0) {
                            $emailfrnz[$i] = $qry[$i]['emailid'];
                        } else {
                            if (!in_array($qry[$i]['emailid'], $emailfrnz)) {
                                $emailfrnz[$i] = $qry[$i]['emailid'];
                            }
                        }
                    }
                    $emailfrnz_val = implode(",", $emailfrnz);
                    //prd($emailfrnz_val);
                }
                $friendsemailid = explode(",", $emailfrnz_val);
                //pr($friendsemailid);
                //echo "url : ".$value1['url']; die;
                for ($i = 0; $i < count($friendsemailid); $i++) {
                    $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='16'");
                    $messageText  = $templateData[0]['email_body'];
                    $subject      = $templateData[0]['email_subject'];
                    $messageText  = str_replace("[NAME]", $friendsemailid[$i], $messageText);
                    $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                    $messageText  = str_replace("[CAMPAIGN_TITLE]", $value1['title'], $messageText);
                    SendEmail($friendsemailid[$i], $subject, $messageText);
                }
            }
            $dataUpdate['campaign_status'] = 0;
            $dataUpdate['mail_sent']       = 1;
            $conditionUpdate               = "campaign_id='" . $value1['campaign_id'] . "'";
            //prd($dataUpdate);
            $db->modify(LAUNCHCAMPAIGN, $dataUpdate, $conditionUpdate);
        }

    }

    public function calculateitAction()
    {
        global $mySession;
        $db  = new Db();
        $val = intval($this->getRequest()->getParam('tno'));
        $sel = $db->runquery("SELECT * FROM " . TSHIRT_PRICE . " WHERE campagin_id='" . $mySession->selectedIdValueKM . "'");
        $sql = $db->runquery("SELECT * FROM " . TSHIRT_DISCOUNT . " WHERE no_of_tee='" . $val . "'");
        $regularPrice = $sel[0]['base_price'];
        if (count($sql) > 0) { // "exact match" of discount level
            $discount = $sql[0]['discount_per'];
            $bprice = ($regularPrice * $discount) / 100;
        } else {
            // search for nearest discount level, starting with the one that has the most tees
            $sql1 = $db->runquery("SELECT * FROM " . TSHIRT_DISCOUNT . " WHERE no_of_tee <'" . $val . "' AND no_of_tee > 0 ORDER BY no_of_tee DESC");
            $cnt = count($sql1);
			if($cnt){
				$discount = $sql1[0]['discount_per'];
				$bprice = ($regularPrice * $discount) / 100;
			}else{
				$bprice = 0; // no discount found
			}
        }
        $discountedPrice = $regularPrice - $bprice;
        echo $discountedPrice;
        exit();
    }

    public function trackorderAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $oid                = $this->getRequest()->getParam('oid');
        $this->view->oid    = $oid;
        $myform             = new Form_Trackorder();
        $this->view->myform = $myform;
    }

    public function trackAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $myform             = new Form_Trackorder();
        $this->view->myform = $myform;
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $myform  = new Form_Trackorder();
            if ($myform->isValid($request->getPost())) {
                $dataForm = $myform->getValues();
                $orderno  = $dataForm['orderno'];
                //echo "order number :".$orderno; die;
                $a        = substr($orderno, 4, 4);
                $this->_redirect('launchcampaign/trackorder/oid/' . $a);
            } else {
                //echo "else of is valid";
                $this->view->myform = $myform;
                $this->render('trackorder');
            }
        } else {
            // "else of is post";
            $this->_redirect('launchcampaign/trackorder');
        }
    }

    public function setgoalAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $cid = $this->getRequest()->getParam('cid');


        $lid = $mySession->lid;

        $this->view->cid = $cid;
        $this->view->lid = $lid;

        if(array_key_exists('customimage', $_REQUEST)){
            $mySession->customimage = $this->getRequest()->getParam('customimage');
        }

        if(array_key_exists('front_img', $_REQUEST)){
            $mySession->front_img = trim($this->getRequest()->getParam('front_img'));
        }

        if(array_key_exists('back_img', $_REQUEST)){
            $mySession->back_img = trim($this->getRequest()->getParam('back_img'));
        }

        if(array_key_exists('mig_id', $_REQUEST)){
            $mySession->mig_id = trim($this->getRequest()->getParam('mig_id'));
        }

        if(array_key_exists('recreation_product', $_REQUEST)){
            $mySession->recreation_product = trim($this->getRequest()->getParam('recreation_product'));
        }

        if ($cid != '' && $cid > 0) {
            $myform                        = new Form_Adddescription(0, '');
            $qurKusum                      = $db->runquery("SELECT normalImageData,SelectedProduct FROM  " . LAUNCHCAMPAIGN . " left WHERE campaign_id='" . $cid . "'");
            $sql                           = $db->runquery("select * from " . TSHIRT_PRICE . " where campagin_id='" . $qurKusum[0]['SelectedProduct'] . "'");
            $mySession->recreation_product = '';
            $mySession->priceValueKM       = $_REQUEST['priceValueKM'];
            $mySession->selectedIdValueKM  = $_REQUEST['idValueKM'];
            if ($qurKusum[0]['normalImageData'] != '') {
                $mySession->recreation_product = $qurKusum[0]['normalImageData'];
            }
        } else if ($lid != '' && $lid > 0) {
            error_log('setgoalAction() Reproduction with $lid '.$lid);
            $myform                        = new Form_Adddescription(0, '');
            $qurKusum                      = $db->runquery("SELECT normalImageData,SelectedProduct FROM  " . LAUNCHCAMPAIGN . " left WHERE campaign_id='" . $lid . "'");
            $sql                           = $db->runquery("select * from " . TSHIRT_PRICE . " where campagin_id='" . $qurKusum[0]['SelectedProduct'] . "'");
            if ($qurKusum[0]['normalImageData'] != '') {
                $mySession->recreation_product = $qurKusum[0]['normalImageData'];
            }
        } else {
			$mySession->selectedIdValueKM  = $_REQUEST['idValueKM'];
			$sql = $db->runquery("select * from " . TSHIRT_PRICE . " where campagin_id='" . $mySession->selectedIdValueKM . "'");
		   //$sql = $db->runquery("select * from " . TSHIRT_PRICE . " where campagin_id='27'");
        }
        $this->view->sql = $sql;
        $myform = new Form_Setgoal($sql[0]['base_price']);
        $this->view->myform = $myform;
    }

    public function savebackimageAction()
    {
        global $mySession;
        $db       = new Db();
        /*	 if(isset($_FILES["imageNameHere"]) && !empty($_FILES["imageNameHere"])) {

        // Random name

        $name= $_REQUEST['nameofFileBack']."@".rand(10, 100).'.png';

        $mySession->setBackImageIcon=$name;

        // Move the file

        move_uploaded_file($_FILES["imageNameHere"]['tmp_name'], 'images/usersdesign/'.$name);

        // Return name

        echo $name; exit();

        }*/
        $qryImage = "select * from " . MANAGEIMAGENAME . " ";
        $image    = $db->runquery($qryImage);
        //if(count($image)>0 && $image!='')
        {
            //foreach($qryImage1 as $image)
            {
                // if($mySession->setFrontImageIcon!="" and isset($mySession->setFrontImageIcon) and file_exists(SITE_ROOT.'images/usersdesign/'.$mySession->setFrontImageIcon))
                if ($image[0]['backimage'] != '' && $image[0]['frontimage'] != '')
                // if($mySession->setBackImageIcon!="" and isset($mySession->setBackImageIcon) and file_exists(SITE_ROOT.'images/usersdesign/'.$mySession->setBackImageIcon))
                    {
                    echo $image[0]['backimage'];
                    // $mySession->setBackImageIcon;
                    exit();
                    //unset($mySession->setBackImageIcon);
                } else {
                    if (isset($_REQUEST["image"]) && !empty($_REQUEST["image"])) {
                        // Init dataURL variable
                        $name                        = $_REQUEST['nameofFile'] . "@" . rand(10, 100) . '.png';
                        $mySession->setBackImageIcon = $name;
                        $mySession->dataURLDbback    = $_REQUEST['dataURLDb'];
                        $dataURL                     = $_POST["image"];
                        // Extract base64 data (Get rid from the MIME & Data Type)
                        $parts                       = explode(',', $dataURL);
                        $data                        = $parts[1];
                        // Decode Base64 data
                        $data                        = base64_decode($data);
                        // Save data as an image
                        $fp                          = fopen('images/usersdesign/' . $name, 'w');
                        fwrite($fp, $data);
                        fclose($fp);
                        $data_update['backimage'] = $name;
                        $condition                = "id='" . $image[0]['id'] . "'";
                        $db->modify(MANAGEIMAGENAME, $data_update, $condition);
                        //$db->save(MANAGEIMAGENAME,$data_update);
                        echo $image['backimage'];
                        exit();
                    }
                }
            }
        }
    }

    public function savedesignimageAction(){
        global $mySession;
        $db = new Db();
        $manage_image_name = array();
        if (isset($_REQUEST["frontImage"]) && !empty($_REQUEST["frontImage"])) {
            $frontImageName = $_REQUEST['frontFileName'] . "_" . rand(10, 100) . '.png';
            $frontImage  = $_POST["frontImage"];
            $this->saveDataUrlUserDesign($frontImage, $frontImageName);
            $manage_image_name['frontimage'] = $frontImageName;
        }
        if (isset($_REQUEST["backImage"]) && !empty($_REQUEST["backImage"])) {
            $backImageName = $_REQUEST['backFileName'] . "_" . rand(10, 100) . '.png';
            $backImage  = $_POST["backImage"];
            $this->saveDataUrlUserDesign($backImage, $backImageName);
            $manage_image_name['backimage'] = $backImageName;
        }
        $db->save(MANAGEIMAGENAME, $manage_image_name);
        $manage_image_name['mig_id'] = $db->lastInsertId();
        echo json_encode($manage_image_name);
        exit();
    }

    protected function saveDataUrlUserDesign($dataUrl, $name){
        // Extract base64 data (Get rid from the MIME & Data Type)
        $parts = explode(',', $dataUrl);
        $data = $parts[1];
        $data = base64_decode($data); // Decode Base64 data
        $fp = fopen('images/usersdesign/' . $name, 'w'); // Save data as an image
        fwrite($fp, $data);
        fclose($fp);
        return true;
    }

    public function savefrontimageAction()
    {
        global $mySession;
        $db        = new Db();
        $qryImage  = "select * from " . MANAGEIMAGENAME . " ";
        $qryImage1 = $db->runquery($qryImage);
        if (count($qryImage1) > 0 && $qryImage1 != '') { //echo "jsk";die;
            foreach ($qryImage1 as $image) {
                // if($mySession->setFrontImageIcon!="" and isset($mySession->setFrontImageIcon) and file_exists(SITE_ROOT.'images/usersdesign/'.$mySession->setFrontImageIcon))
                if ($image['frontimage'] != '') {
                    /*  $mySession->setFrontImageIcon='';
                    unset($mySession->setFrontImageIcon);*/
                    echo $mySession->setFrontImageIcon = $image['frontimage'];
                    exit();
                } else {
                    if (isset($_REQUEST["image"]) && !empty($_REQUEST["image"])) {
                        // Init dataURL variable
                        $name                         = $_REQUEST['nameofFile'] . "@" . rand(10, 100) . '.png';
                        $mySession->setFrontImageIcon = $name;
                        $mySession->dataURLDbfront    = $_REQUEST['dataURLDb'];
                        $dataURL                      = $_POST["image"];
                        // Extract base64 data (Get rid from the MIME & Data Type)
                        $parts                        = explode(',', $dataURL);
                        $data                         = $parts[1];
                        // Decode Base64 data
                        $data                         = base64_decode($data);
                        // Save data as an image
                        $fp                           = fopen('images/usersdesign/' . $name, 'w');
                        fwrite($fp, $data);
                        fclose($fp);
                        //prd($name);
                        $data_update['frontimage'] = $name;
                        /*$condition="campaign_id='".$_REQUEST['lid']."'";

                        $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);*/
                        $db->save(MANAGEIMAGENAME, $data_update);
                        echo $mySession->setFrontImageIcon;
                        exit();
                    }
                }
            }
        } else {
            // echo "else";die;
            if (isset($_REQUEST["image"]) && !empty($_REQUEST["image"])) {
                //echo "jsk else";die;
                // Init dataURL variable
                $name                         = $_REQUEST['nameofFile'] . "@" . rand(10, 100) . '.png';
                $mySession->setFrontImageIcon = $name;
                $mySession->dataURLDbfront    = $_REQUEST['dataURLDb'];
                $dataURL                      = $_POST["image"];
                // Extract base64 data (Get rid from the MIME & Data Type)
                $parts                        = explode(',', $dataURL);
                $data                         = $parts[1];
                // Decode Base64 data
                $data                         = base64_decode($data);
                // Save data as an image
                $fp                           = fopen('images/usersdesign/' . $name, 'w');
                fwrite($fp, $data);
                fclose($fp);
                $data_update['frontimage'] = $name;
                /*$condition="campaign_id='".$_REQUEST['lid']."'";

                $db->modify(LAUNCHCAMPAIGN,$data_update,$condition);*/
                $db->save(MANAGEIMAGENAME, $data_update);
                echo $mySession->setFrontImageIcon;
                exit();
            }
        }
    }

    public function adddescriptionAction()
    {
        global $mySession;
        $db              = new Db();
        $login           = $this->getRequest()->getParam('login');
        $this->view->cid = $login;
        if ($login != "") {
            $myform = new Form_Adddescription();
        }
        //resume
        $cid                   = $this->getRequest()->getParam('cid');
        $this->view->cid       = $cid;
        $lid                   = $this->getRequest()->getParam('lid');
        $this->view->lid       = $lid;
        $cidresume             = $this->getRequest()->getParam('cidresume');
        $this->view->cidresume = $cidresume;
        $this->_helper->layout->setLayout('myaccount');
        if ($cidresume != "") {
            $myform   = new Form_Adddescription(0, $cidresume);
            $qurKusum = $db->runquery("SELECT * FROM  " . LAUNCHCAMPAIGN . " WHERE campaign_id='" . $cidresume . "'");
            if ($qurKusum[0]['normalImageData'] != '') {
                $mySession->recreation_product = $qurKusum[0]['normalImageData'];
            }
        }
        if ($cid != "") { //resume
            $myform = new Form_Adddescription($cid);
        } else {
            $myform = new Form_Adddescription(0, 0);
        }
        $this->view->myform = $myform;
    }

    public function storeAction(){

    }

    public function savevalueAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('main');
        $cid    = $this->getRequest()->getParam('cid');
        $myform = new Form_Setgoal();
        if ($this->getRequest()->isPost()) {
            $request   = $this->getRequest();
            //error_log('savevalueAction() request: '.print_r($request, true));
            $no_oftee  = $_REQUEST['no_of_tee'];
            $baseprice = $_REQUEST['basepricefield'];
            if ($myform->isValid($request->getPost())) {
                $dataForm                 = $myform->getValues();
                //error_log('savevalueAction() form valid, values: '.print_r($dataForm, true));
                $mySession->setgoalvalues = $dataForm;
                $mySession->no_of_t       = $no_oftee;
                $mySession->baseprice     = $baseprice;
                if ($cid == "" && $mySession->TeeLoggedID != "") {
                    $myObj = new Launchcampaigndb();
                    $campid  = $myObj->savesetgoal($dataForm);
                    if ($campid != "") {
                        $mySession->lid = $campid;
                        $this->_redirect('launchcampaign/adddescription/lid/' . $campid);
                    } else {
                        $this->_redirect('launchcampaign/adddescription');
                    }
                }else{
                    if ($cid != "") {
                        $this->_redirect('launchcampaign/adddescription/cid/' . $cid);
                    } else {
                        $this->_redirect('launchcampaign/adddescription');
                    }
                }
            } else {
                $this->view->myform = $myform;
                $this->render('setgoal');
            }
        } else {
            $this->_redirect('launchcampaign/setgoal');
        }
    }



    public function launchAction()
    {

        //echo "launch"; die;
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('main');
        $cid                = $this->getRequest()->getParam('cid');
        $lid                = $this->getRequest()->getParam('lid');
        //$myform=new Form_Setgoal();
        $this->view->lid    = $lid;
        $this->view->cid    = $cid;
        //echo "camp id : when launched :".$cid; die;
        //echo "JSK last id : ".$lid; die;
        $myform             = new Form_Adddescription();

        $this->view->myform = $myform;
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $myform  = new Form_Adddescription();
            if ($myform->isValid($request->getPost())) {
                $dataForm = $myform->getValues();
                //echo "inside post";
                //$checkboxval=$_REQUEST['add_checkbox'];
                //echo "value of CB===".$checkboxval; die;
                //pr($dataForm);
                $myObj = new Launchcampaigndb();

                if ($mySession->camptitl != "" && $mySession->setgoalvalues != "" && $mySession->showurl != "") {
                    //echo $mySession->campaign_category."JSK"; prd($mySession);
                    //echo "launch action session values"; die;
                    $data1        = $myObj->saveall();

                    #get followers
                    $followers    = '';
                    $getfollowers = $db->runquery("select * from " . FOLLOW . " where following_id='" . $mySession->TeeLoggedID . "' ");


                    if (count($getfollowers) > 0) {
                        foreach ($getfollowers as $follow) {
                            $followers .= $follow['follower_id'] . ",";
                        }
                        #save for notification
                        $savenotification = array(
                            'n_camp_id' => $data1,
                            'n_user_id' => $followers,
                            'n_date' => date('Y-M-d H:i:s')
                        );
                        $db->save(NOTIFICATION, $savenotification);
                        $campvalue      = $db->runQuery("select title,url,campaign_id from " . LAUNCHCAMPAIGN . " where campaign_id='" . $data1 . "'");
                        $urlPathcapmp   = '<a href="' . APPLICATION_URL . urlencode($campvalue[0]['url']) . '">' . APPLICATION_URL . urlencode($campvalue[0]['url']) . '</a>';
                        $DatauserDetail = $db->runQuery("select * from " . USERS . " where user_id='" . $mySession->TeeLoggedID . "'");
                        $urlPathprofile = '<a href="' . APPLICATION_URL . 'user/view/user_id/' . $DatauserDetail[0]['user_id'] . '">' . APPLICATION_URL . 'user/view/user_id/' . $DatauserDetail[0]['user_id'] . '</a>';
                        foreach ($getfollowers as $follow) {
                            $Data         = $db->runQuery("select * from " . USERS . " where user_id='" . $follow['follower_id'] . "'");
                            $emaid        = $Data[0]['emailid'];
                            $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='19'");
                            $messageText  = $templateData[0]['email_body'];
                            $subject      = $templateData[0]['email_subject'];
                            $messageText  = str_replace("[NAME]", $emaid, $messageText);
                            $messageText  = str_replace("[UNAME]", $DatauserDetail[0]['emailid'] . '(' . $DatauserDetail[0]['public_name'] . ')', $messageText);
                            $messageText  = str_replace("[CAMPAIGN_TITLE]", $campvalue[0]['title'] . '', $messageText);
                            $messageText  = str_replace("[CAMPAIGN_LINK]", $urlPathcapmp, $messageText);
                            $messageText  = str_replace("[PROFILE_LINK]", $urlPathprofile, $messageText);
                            $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                            SendEmail($emaid, $subject, $messageText);
                        }
                        $this->_redirect(APPLICATION_URL . urlencode($dataForm['url']));
                    }
                }
                if ($cid != "") {
                    //echo "here is cid ".$cid; die;
                    $data = $myObj->relaunchcampaign($dataForm, $cid);
                } else {
                    //echo "JSK last id : ".$lid; die;
                    $data         = $myObj->savecampaign($dataForm, $lid);
                    #get followers
                    $followers    = '';
                    $getfollowers = $db->runquery("select * from " . FOLLOW . " where following_id='" . $mySession->TeeLoggedID . "' ");
                    if (count($getfollowers) > 0) {
                        foreach ($getfollowers as $follow) {
                            $followers .= $follow['follower_id'] . ",";
                        }
                        #save for notification
                        $savenotification = array(
                            'n_camp_id' => $lid,
                            'n_user_id' => $followers,
                            'n_date' => date('Y-M-d H:i:s')
                        );
                        $db->save(NOTIFICATION, $savenotification);
                        $campvalue      = $db->runQuery("select title,url,campaign_id from " . LAUNCHCAMPAIGN . " where campaign_id='" . $lid . "'");
                        $urlPathcapmp   = '<a href="' . APPLICATION_URL . urlencode($campvalue[0]['url']) . '">' . APPLICATION_URL . urlencode($campvalue[0]['url']) . '</a>';
                        $DatauserDetail = $db->runQuery("select * from " . USERS . " where user_id='" . $mySession->TeeLoggedID . "'");
                        $urlPathprofile = '<a href="' . APPLICATION_URL . 'user/view/user_id/' . $DatauserDetail[0]['user_id'] . '">' . APPLICATION_URL . 'user/view/user_id/' . $DatauserDetail[0]['user_id'] . '</a>';
                        foreach ($getfollowers as $follow) {
                            $Data         = $db->runQuery("select * from " . USERS . " where user_id='" . $follow['follower_id'] . "'");
                            $emaid        = $Data[0]['emailid'];
                            $templateData = $db->runQuery("select * from " . EMAIL_TEMPLATES . " where template_id='19'");
                            $messageText  = $templateData[0]['email_body'];
                            $subject      = $templateData[0]['email_subject'];
                            $messageText  = str_replace("[NAME]", $emaid, $messageText);
                            $messageText  = str_replace("[UNAME]", $DatauserDetail[0]['emailid'] . '(' . $DatauserDetail[0]['public_name'] . ')', $messageText);
                            $messageText  = str_replace("[CAMPAIGN_TITLE]", $campvalue[0]['title'] . '', $messageText);
                            $messageText  = str_replace("[CAMPAIGN_LINK]", $urlPathcapmp, $messageText);
                            $messageText  = str_replace("[PROFILE_LINK]", $urlPathprofile, $messageText);
                            $messageText  = str_replace("[SITENAME]", SITE_NAME, $messageText);
                            SendEmail($emaid, $subject, $messageText);
                        }
                    }
                    //	$db->save(LAUNCHCAMPAIGN,$data_insert);
                }
                //echo "else";die;
                if ($data == 1) {

					// X001 Preparing the parameters
					$postvals = $request->getPost();
					$payment_params = array
					(
						"lid" => $lid,
						"creator" => $mySession->TeeLoggedID,
						"length" => $postvals["no_ofdays"],
						"tees" => $mySession->no_of_t,
						"baseprice" => 0,
						"sellprice" => $mySession->setgoalvalues["sellingprice"],
						"url" => $postvals["url"],
						"valuekm" => $mySession->selectedIdValueKM
					);
					// End of X001


                    $mySession->errorMsg      = "Campaign Launched successfully";
                    $sql                      = $db->runquery("select * from " . LAUNCHCAMPAIGN . " where user_id='" . $mySession->TeeLoggedID . "' and draft_status='1'");
                    //echo "select * from ".LAUNCHCAMPAIGN." where user_id='".$mySession->TeeLoggedID."'";
                    //echo "url is: ".$dataForm['url']; die;
                    $mySession->setgoalvalues = "";
                    unset($mySession->setgoalvalues);
                    $mySession->no_of_t = "";
                    unset($mySession->no_of_t);
                    $mySession->baseprice = "";
                    unset($mySession->baseprice);
                    $mySession->camptitl = "";
                    unset($mySession->camptitl);
                    $mySession->descrip = "";
                    unset($mySession->descrip);
                    $mySession->camplength = "";
                    unset($mySession->camplength);
                    $mySession->showurl = "";
                    unset($mySession->showurl);
                    $mySession->campaign_category = "";
                    unset($mySession->campaign_category);
                    $mySession->recreation_product = "";
                    unset($mySession->recreation_product);
                    $mySession->setBackImageIcon = "";
                    unset($mySession->setBackImageIcon);
                    $mySession->setFrontImageIcon = "";
                    unset($mySession->setFrontImageIcon);
                    $mySession->customimage = "";
                    unset($mySession->customimage);
                    $mySession->dataURLDbback = "";
                    unset($mySession->dataURLDbback);
                    $mySession->dataURLDbfront = "";
                    unset($mySession->dataURLDbfront);
                    //$this->_redirect('launchcampaign/showcampaign/name/'.$dataForm['url']);



					// X002 getting parameters
					require_once (dirname (dirname (__FILE__)) . '/configs/crowdfunding.inc.php');
					require_once (dirname (dirname (__FILE__)) . '/crowdfunding/Crowdfunding.php');

					$sel = $db->runquery("select * from " . TSHIRT_PRICE . " where campagin_id='" . $payment_params["valuekm"] . "'");
					$sql = $db->runquery("select * from " . TSHIRT_DISCOUNT . " where no_of_tee='" . $payment_params["tees"] . "'");
					if (count($sql) > 0) {
						$bprice = ($sel[0]['base_price'] * $sql[0]['discount_per']) / 100;
						$bprice = $sel[0]['base_price'] - $bprice;
					} else {
						$sql1     = $db->runquery("select * from " . TSHIRT_DISCOUNT . " where no_of_tee <'" . $payment_params["tees"] . "' && no_of_tee!=0");
						$cnt      = count($sql1);
						$discount = $sql1[$cnt - 1]['discount_per'];
						$bprice   = ($sel[0]['base_price'] * $discount) / 100;
						$bprice   = $sel[0]['base_price'] - $bprice;
					}

					$payment_params["baseprice"] = $bprice;
					$crowdfunding = new Crowdfunding ();
					$crowdfunding->create_campaign ($payment_params);
					// End of X002

                    $this->_redirect(APPLICATION_URL . urlencode($dataForm['url']));
                }
            } else {
                //echo"else of in valid";
                $this->view->myform = $myform;
                $this->render('adddescription');
            }
        } else {
            //echo"else of is post"; die;
            $this->_redirect('launchcampaign/adddescription');
        }
        //pr($mySession->camplength);
        //prd($mySession->showurl);
    }

    public function resumeAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $cidresume             = $this->getRequest()->getParam('cidresume');
        $this->view->cidresume = $cidresume;
        $myform                = new Form_Adddescription();
        $this->view->myform    = $myform;
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $myform  = new Form_Adddescription();
            if ($myform->isValid($request->getPost())) {
                $dataForm = $myform->getValues();
                $myObj    = new Launchcampaigndb();
                $data     = $myObj->resumecampaign($dataForm, $cidresume);
                if ($data == 1) {
                    $mySession->errorMsg = "Campaign Launched successfully";
                    $sql                 = $db->runquery("select * from " . LAUNCHCAMPAIGN . " where user_id='" . $mySession->TeeLoggedID . "' and draft_status='1'");
                    //$this->_redirect('launchcampaign/showcampaign/name/'.$dataForm['url']);
                    $this->_redirect(APPLICATION_URL . $dataForm['url']);
                }
            } else {
                $this->view->myform = $myform;
                $this->render('adddescription');
            }
        } else {
            //echo"else of is post"; die;
            $this->_redirect('launchcampaign/adddescription');
        }
    }

    public function editAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $cid                = $this->getRequest()->getParam('cid');
        $this->view->cid    = $cid;
        $qur                = $db->runquery("SELECT * FROM  " . LAUNCHCAMPAIGN . " WHERE campaign_id='" . $cid . "' and draft_status='1'");
        //prd($qur);
        $this->view->data   = $qur;
        $myform             = new Form_Edit($cid);
        $this->view->myform = $myform;
    }

    public function updateeditedAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $cid             = $this->getRequest()->getParam('cid');
        $this->view->cid = $cid;
        if ($this->getRequest()->isPost()) //  same as issset post
            {
            $request = $this->getRequest();
            $myform  = new Form_Edit();
            if ($myform->isValid($request->getPost())) //  required true is checked.
                {
                $dataForm = $myform->getValues();
                $myObj    = new Launchcampaigndb(); // call to model
                $data     = $myObj->editcampdetails($dataForm, $cid);
                if ($data == 1) {
                    $mySession->errorMsg = "Data updated successfully";
                    $this->_redirect('myaccount/activecampaign');
                } else {
                    $mySession->errorMsg = "not successfull";
                    $this->render('activecampaign');
                }
            } else {
                //echo "else of request is valid"; die;
                $this->view->myform = $myform;
                $this->render('edit');
            }
        } else {
            //echo "else of request is post"; die;
            $this->_redirect('launchcampaign/edit');
        }
    }

    public function showcampaignAction()
    {
        global $mySession;
        $db = new Db();
        $this->_helper->layout->setLayout('myaccount');
        $name                = $this->getRequest()->getParam('name');
        $show                = $this->getRequest()->getParam('show');
        $myform1             = new Form_Buy();
        $this->view->myform1 = $myform1;

        if($mySession->TeeLoggedID != "" && $show == "") {
            $sql = $db->runquery("select * from " . LAUNCHCAMPAIGN . " where user_id='" . $mySession->TeeLoggedID . "' and url='" . $name . "'");
        } else {
            $sql = $db->runquery("select * from " . LAUNCHCAMPAIGN . " where url='" . $name . "'");
        }

        $this->view->sql = $sql;
    }

    public function uniqurlAction()
    {
        $db = new Db();
        global $mySession;
        //echo "gfjgfgfj";
        $campurl = $this->getRequest()->getParam('campurl');
        //echo "url : ".$campurl; die;
        //echo ("select * from ".LAUNCHCAMPAIGN." where trim(url)='".trim($campurl)."'");die;
        $chkQry  = $db->runQuery("select * from " . LAUNCHCAMPAIGN . " where trim(url)='" . trim($campurl) . "'");
        //echo "select * from ".LAUNCHCAMPAIGN." where url='".$campurl."'";
        $cnt     = count($chkQry);
        echo $cnt;
        exit();
    }

    public function notificationremoveAction()
    {
        $db = new Db();
        global $mySession;
        $rest_user = $this->getRequest()->getParam("users");
        $n_id      = $this->getRequest()->getParam("n_id");
        $where     = "n_id='" . $n_id . "'";
        if ($rest_user != '') {
            $data = array(
                'n_user_id' => $rest_user
            );
            $db->modify(NOTIFICATION, $data, $where);
        } else {
            $db->delete(NOTIFICATION, $where);
        }
        exit();
    }
}
?>
