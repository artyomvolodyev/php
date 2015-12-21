<?php
__autoloadDB('Db');
class TshirtController extends AppController
{
	public function indexAction()
	{
		global $mySession;
		$db = new Db();
		//$this->view->pageHeading="Manage Price";
	}
	
	public function sizeAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading = "Manage Size";
		$qry = "select * from ".TSHIRT_SIZE."";
		$sql = $db->runquery($qry);
		$this->view->sql = $sql;
	}
	
	public function iconsAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading = "Manage Icons";
		$qry = "select * from ".TSHIRT_ICONS."";
		$sql = $db->runquery($qry);
		$this->view->sql = $sql;
	}

	public function productsAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading = "Manage Tshirt Products";
		$qry = "select * from ".TSHIRT_PRODUCTS." ORDER BY t_sort ASC";
		$sql = $db->runquery($qry);
		$this->view->sql = $sql;
	}

    public function addnewiconAction()
    {
		global $mySession;
		$db = new Db();
		$myform = new Form_Icons("");
		$this->view->myform = $myform;
		$this->view->pageHeading = "Add Icons";
	}
	
    public function tshirtproductAction()
    {
        global $mySession;
        $db = new Db();
        $id = $this->getRequest()->getParam('id');
        if($id != "")  {
            $myform = new Form_Tshirtcategory($id);
            $this->view->myform = $myform;
            $this->view->pageHeading = "Edit Tshirt Products";
            $this->view->id = $id;
            $qry = "select * from ".TSHIRT_PRODUCTS." where t_cat_id='".$id."'";
            $sql = $db->runquery($qry);
            $this->view->sql = $sql[0];
        } else {
            $myform = new Form_Tshirtcategory();
            $this->view->myform = $myform;
            $this->view->pageHeading = "Add Tshirt Products";
        }
    }
		
	public function saveproductAction()
	{
		global $mySession;
		$db = new Db();
		$id = $this->getRequest()->getParam('id');
		
        if($id == ""){
            $this->view->pageHeading = "Add Tshirt Products";
        }else{
             $this->view->pageHeading = "Edit Tshirt Products";
        }
				
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			if($id == ""){
			    $myform = new Form_Tshirtcategory();
            }else{
			    $myform = new Form_Tshirtcategory($id);
            }
			
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				$Result='';

                // front Images
                if($dataForm['image'] != "") {
                    $widthFront = '';
                    $heightFront = '';
                    $profileImagefront = time()."_".$dataForm['image'];
                    error_log('saveproductAction FRONT image: '.$dataForm['image'].', will be renamed to '.$profileImagefront);
                    @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['image'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                    $imagefront = IMAGES_URL."tshirtdesigns/front/".$profileImagefront;
                    $info2 = getimagesize($imagefront);
                    $width2 = $info2 [0];
                    $height2 = $info2 [1];
                    $widthFront= $info2 [0];
                    $heightFront = $info2 [1];

                    if ($width2 > 450 && $height2 > 420) {
                        unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                        $mySession->errorMsg = "Front image height or width exceedes from Maximum-height=420 and/or Maximum-Width=450";
                        $this->view->myform = $myform;
                        if($id != ''){
                            $this->view->id=$id;
                        }
                        $this->_redirect('tshirt/tshirtproduct');
                    }


                    // frontHeigh
                    if($dataForm['frontHeigh'] != "") {
                        $profileImagefront = time()."_FH_".$dataForm['frontHeigh'];
                        error_log('saveproductAction FRONT highlight: '.$dataForm['frontHeigh'].', will be renamed to '.$profileImagefront);
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontHeigh'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                        $imagefront = IMAGES_URL."tshirtdesigns/front/".$profileImagefront;
                        $info2 = getimagesize($imagefront);
                        $width2 = @$info2 [0];
                        $height2 = @$info2 [1];

                        if ($width2 != $widthFront && $height2 != $heightFront) {
                            unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                            $mySession->errorMsg ="Highlighted Front image height or width should be similar to basic image : height=".$heightFront." VS height=".$height2.", width=".$widthFront." VS width=".$width2;
                            $this->view->myform = $myform;
                            if($id != ''){
                                $this->view->id = $id;
                            }
                            $this->_redirect('tshirt/tshirtproduct');
                        }
                    }

                    //shadow front
                    if($dataForm['frontShadow'] != "") {
                        $profileImagefront = time()."_FS_".$dataForm['frontShadow'];
                        error_log('saveproductAction FRONT shadow: '.$dataForm['frontShadow'].', will be renamed to '.$profileImagefront);
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontShadow'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                        $imagefront = IMAGES_URL."tshirtdesigns/front/".$profileImagefront;
                        $info2 = getimagesize($imagefront);
                        $width2 = @$info2 [0];
                        $height2 = @$info2 [1];

                        if($width2 != $widthFront && $height2 != $heightFront) {
                            unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$profileImagefront);
                            $mySession->errorMsg = "Shadow Front image height or width should be similar to basic image : height=".$heightFront." VS height=".$height2.", width=".$widthFront." VS width=".$width2;
                            $this->view->myform = $myform;
                            if($id != ''){
                                $this->view->id = $id;
                            }
                            $this->_redirect('tshirt/tshirtproduct');
                        }
                    }
                }

                // Back
                if($dataForm['backimage'] != "")
                {
                    error_log('saveproductAction BACK image set');
                    $widthBack = '';
                    $heightBack = '';

                    $profileImagefront = time()."_".$dataForm['backimage'];
                    error_log('saveproductAction BACK image: '.$dataForm['backimage'].', will be renamed to '.$profileImagefront);
                    @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backimage'],SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                    $imagefront = IMAGES_URL."tshirtdesigns/back/".$profileImagefront;

                    $info2 = getimagesize($imagefront);
                    $width2 = @$info2 [0];
                    $height2 = @$info2 [1];
                    $widthBack = @$info2 [0];
                    $heightBack = @$info2 [1];

                    if (($width2 > 450 && $height2 > 420)) {
                        unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                        $mySession->errorMsg ="Back image height or width is exceeded from Maximum-height=420, Maximum-Width=450";
                        $this->view->myform = $myform;
                        if($id != ''){
                            $this->view->id = $id;
                        }
                        $this->_redirect('tshirt/tshirtproduct');
                    }

                    // Highlight
                    if($dataForm['backHeigh'] != "") {
                        $profileImagefront=time()."_BH_".$dataForm['backHeigh'];
                        error_log('saveproductAction BACK highlight: '.$dataForm['backHeigh'].', will be renamed to '.$profileImagefront);
                        @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backHeigh'],SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                        $imagefront = IMAGES_URL."tshirtdesigns/back/".$profileImagefront;
                        $info2 = getimagesize($imagefront);
                        $width2 = @$info2 [0];
                        $height2 = @$info2 [1];

                        if($width2 != $widthBack && $height2 != $heightBack) {
                            unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                            $mySession->errorMsg = "Highlighted Back image height or width should be similar to basic image : height=".$heightBack." VS height=".$height2.", width=".$widthBack." VS width=".$width2;
                            $this->view->myform = $myform;
                            if($id != ''){
                             $this->view->id = $id;
                            }
                            $this->_redirect('tshirt/tshirtproduct');
                        }
                    }

                    //shadow
                    if($dataForm['backShadow'] != "") {
                        $profileImagefront = time()."_BS_".$dataForm['backShadow'];
                        error_log('saveproductAction BACK shadow: '.$dataForm['backShadow'].', will be renamed to '.$profileImagefront);
                        @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backShadow'],SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                        $imagefront = IMAGES_URL."tshirtdesigns/back/".$profileImagefront;
                        $info2 = getimagesize($imagefront);
                        $width2 = @$info2 [0];
                        $height2 = @$info2 [1];

                        if($width2 != $widthBack && $height2 != $heightBack)  {
                            unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$profileImagefront);
                            $mySession->errorMsg = "Shadow Front image height or width should be similar to basic image : height=".$heightBack.", Width=".$widthBack;
                            $this->view->myform = $myform;
                            if($id != ''){
                                $this->view->id = $id;
                            }
                            $this->_redirect('tshirt/tshirtproduct');
                        }
                    }
                }

                if($id == "") {
                    // SAVING NEW, PREVIOUSLY NOT EXISTING PRODUCT
                    error_log('saveproductAction, SAVING NEW, PREVIOUSLY NOT EXISTING PRODUCT');

                     // Front Main
                    $profileImage = $dataForm['image'];
                    if($dataForm['image'] != "") {
                        $profileImage = time()."_".$profileImage;
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['image'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImage);
                    }

                    // Front Highlighted
                    $profileImageHeigh = $dataForm['frontHeigh'];
                    if($dataForm['frontHeigh'] != "") {
                        $profileImageHeigh = time()."_FH_".$profileImageHeigh;
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontHeigh'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImageHeigh);
                        $dataInsert['frontHeigh'] = $profileImageHeigh;
                    }

                    // Front Shadow
                    $profileImageShadow = $dataForm['frontShadow'];
                    if($dataForm['frontShadow'] != "") {
                        $profileImageShadow = time()."_FS_".$profileImageShadow;
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontShadow'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImageShadow);
                        $dataInsert['frontShadow'] = $profileImageShadow;
                    }

                    // Back
                    if($_REQUEST['checkData1'] == true && $_REQUEST['checkData1'] == 'backimageData777') {

                        // Back main
                        $backImage = $dataForm['backimage'];
                        if($dataForm['backimage'] != "") {
                            $backImage = time()."_".$backImage;
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backimage'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImage);
                        }

                        // Back Highlighted
                        $backImageHeigh = $dataForm['backHeigh'];
                        if($dataForm['backHeigh'] != "") {
                            $backImageHeigh = time()."_BH_".$backImageHeigh;
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backHeigh'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImageHeigh);
                            $dataInsert['backHeigh'] = $backImageHeigh;
                        }

                        // Back Shadow
                        $backImageShadow = $dataForm['backShadow'];
                        if($dataForm['backShadow'] != "") {
                            $backImageShadow = time()."_BS_".$backImageShadow;
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backShadow'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImageShadow);
                            $dataInsert['backShadow'] = $backImageShadow;
                        }

                    }

                    if($backImage != '' && $_REQUEST['checkData1'] == 'backimageData777') {
                        $dataInsert['backimage'] = $backImage;
                        $dataInsert['totalimage'] = '2';
                    } else {
                        $dataInsert['totalimage'] = '1';
                    }

                    $dataInsert['image'] = $profileImage;
                    $dataInsert['name'] = $dataForm['title'];
                    $dataInsert['colorcode'] = $dataForm['colorcode'];
                    $dataInsert['status'] = 1;
                    $dataInsert['date_added'] = date('y-m-d');
                    //error_log('saveproductAction, dataInsert: '.print_r($dataInsert, true));
                    $Result = $db->save(TSHIRT_PRODUCTS, $dataInsert);
                    $ProductIdKM = $db->lastInsertId();
                    //error_log('saveproductAction, $ProductIdKM: '.$ProductIdKM);

                    if($Result > 0 && $ProductIdKM > 0) {
                        $dataInsertPrice['base_price'] = $dataForm['base_price'];
                        $dataInsertPrice['shipping_price'] = $dataForm['shippingprice'];
                        $dataInsertPrice['campagin_id'] = $ProductIdKM;
                        $db->save(TSHIRT_PRICE,$dataInsertPrice);
                        $mySession->errorMsg = "Added successfully.";
                    }

                } else {

                    // UPDATING ALREADY EXISTING PRODUCT
                    error_log('saveproductAction, UPDATING ALREADY EXISTING PRODUCT');

                     // Front
                    if($dataForm['image'] != "" && $dataForm['oldicon'] != "") {
                        unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['oldicon']);
                    }

                    $profileImage = $dataForm['oldicon'];
                    if($dataForm['image'] != "") {
                        $profileImage=time()."_".$dataForm['image'];
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['image'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImage);
                    }

                    if($dataForm['frontHeigh'] != "" && $dataForm['oldfrontHeigh'] != "") {
                        unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['oldfrontHeigh']);
                    }

                    $profileImageFH = $dataForm['oldfrontHeigh'];
                    if($dataForm['frontHeigh'] != "") {
                        $profileImageFH = time()."_FH_".$dataForm['frontHeigh'];
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontHeigh'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImageFH);
                        $dataInsert['frontHeigh'] = $profileImageFH;
                    }

                    if($dataForm['frontShadow'] != "" && $dataForm['oldfrontHeigh'] != "") {
                        unlink(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['oldfrontHeigh']);
                    }

                    $profileImageFS = $dataForm['oldfrontShadow'];
                    if($dataForm['frontShadow'] != "") {
                        $profileImageFS = time()."_FS_".$dataForm['frontShadow'];
                        @rename(SITE_ROOT.'images/tshirtdesigns/front/'.$dataForm['frontShadow'],SITE_ROOT.'images/tshirtdesigns/front/'.$profileImageFS);
                        $dataInsert['frontShadow'] = $profileImageFS;
                    }

                    if($_REQUEST['checkData1'] == true && $_REQUEST['checkData1'] == 'backimageData777') {
                        $qry = "select * from ".TSHIRT_PRODUCTS." where t_cat_id='".$id."'";
                        $sql = $db->runquery($qry);

                        // Back Image
                        if($dataForm['backimage'] != "" && $dataForm['backoldicon'] != "") {
                            unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backoldicon']);
                        } else if($dataForm['backimage'] == "" && $dataForm['backoldicon'] == "" && $sql[0]['backimage'] != "") {
                             unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$sql[0]['backimage']);
                        }

                        if($dataForm['backoldicon'] != ''){
                            $backImage = $dataForm['backoldicon'];
                        }
                        if($dataForm['backimage'] != "") {
                            $backImage = time()."_".$dataForm['backimage'];
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backimage'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImage);
                        }

                        // Back Highlight
                        if($dataForm['backHeigh'] != "" && $dataForm['oldbacktHeigh'] != "") {
                            unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['oldbacktHeigh']);
                        } else if($dataForm['backHeigh'] == "" && $dataForm['oldbacktHeigh'] == "" && $sql[0]['backHeigh'] != "") {
                             unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$sql[0]['backHeigh']);
                        }
                        if($dataForm['oldbacktHeigh'] != ''){
                            $backImageBH = $dataForm['oldbacktHeigh'];
                        }
                        if($dataForm['backHeigh'] != "")  {
                            $backImageBH = time()."_BH_".$dataForm['backHeigh'];
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backHeigh'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImageBH);
                        }
                        $dataInsert['backHeigh'] = $backImageBH;

                        // Back Shadow
                        if($dataForm['backShadow'] != "" && $dataForm['oldbackShadow'] != "") {
                            unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['oldbackShadow']);
                        } else if($dataForm['backShadow'] == "" && $dataForm['oldbackShadow'] == "" && $sql[0]['backShadow'] != "") {
                             unlink(SITE_ROOT.'images/tshirtdesigns/back/'.$sql[0]['backHeigh']);
                        }
                        if($dataForm['oldbackShadow'] != ''){
                            $backImageBS = $dataForm['oldbackShadow'];
                        }
                        if($dataForm['backShadow'] != "") {
                            $backImageBS = time()."_BS_".$dataForm['backShadow'];
                            @rename(SITE_ROOT.'images/tshirtdesigns/back/'.$dataForm['backShadow'],SITE_ROOT.'images/tshirtdesigns/back/'.$backImageBS);
                        }

                        $dataInsert['backShadow'] = $backImageBS;
                    }

                    if($backImage != '' && $_REQUEST['checkData1'] == 'backimageData777' ) {
                        $dataInsert['backimage'] = $backImage;
                        $dataInsert['totalimage'] = '2';
                    } else {
                        $dataInsert['totalimage'] = '1';
                    }

                    $dataInsert['image'] = $profileImage;
                    $dataInsert['name'] = $dataForm['title'];
                    $dataInsert['colorcode'] = $dataForm['colorcode'];

                    $conditionUpdate = "t_cat_id='".$id."'";

                    //error_log('saveproductAction, dataInsert: '.print_r($dataInsert, true));
                    $Result = $db->modify(TSHIRT_PRODUCTS, $dataInsert, $conditionUpdate);
                    if($Result > 0){
                        $mySession->errorMsg = "Updated successfully.";
                    }
                    $dataUpdatePrice['base_price'] = $dataForm['base_price'];
                    $dataUpdatePrice['shipping_price'] = $dataForm['shippingprice'];

                    //$dataUpdate['page_position']=$dataForm['pageposition'];
                    $conditionUpdatePrice = "campagin_id='".$id."'";

                    $db->modify(TSHIRT_PRICE, $dataUpdatePrice, $conditionUpdatePrice);
                }

                if($Result > 0) {
                    $this->_redirect('tshirt/products');
                } else {
                    $mySession->errorMsg ="Icon already exist with this title";
                    $this->view->myform = $myform;
                    if($id != ''){
                        $this->view->id = $id;
                    }
                    $this->render('tshirtproduct');
                }
				
			} else {
                $mySession->errorMsg = "Fill Required Fields";
				$this->view->myform = $myform;
				if($id != ''){
					$this->view->id = $id;
                }
				$this->render('tshirtproduct');
			}
		} else {
			$this->_redirect('tshirt/products');
		}
	}
	
    public function deletenewproductAction()
    {
        global $mySession;
        $db=new Db();
        if($_REQUEST['Id'] != "") {
            $arrId=explode("|", $_REQUEST['Id']);
            if(count($arrId) > 0) {
                foreach($arrId as $key => $Id)  {
                    $condition1="t_cat_id='".$Id."'";
                    $db->delete(TSHIRT_PRODUCTS,$condition1);
                }
            }
        }
        exit();
    }
			
    public function changeproductstatusAction()
    {
        global $mySession;
        $db=new Db();

        $BcID=$this->getRequest()->getParam('Id');
        $status=$this->getRequest()->getParam('Status');

        if($status == '1') {
            $status = '0';
        } else {
            $status = '1';
        }
        $data_update['status'] = $status;
        $condition="t_cat_id='".$BcID."' ";
        $db->modify(TSHIRT_PRODUCTS,$data_update,$condition);

        if($db) {
            $mySession->errorMsg ="Status Changed successfully.";
            $this->_redirect('tshirt/products');
        }
    }

	
	public function editnewiconAction()
	{
		global $mySession;
		$db=new Db();	
		$id=$this->getRequest()->getParam('id');
		$this->view->id = $id;
		$myform = new Form_Icons($id);
		$this->view->myform = $myform;
		$this->view->pageHeading = "Edit";
	}
	
	public function updatenewiconAction()
	{
		global $mySession;
		$db = new Db();
		$id=$this->getRequest()->getParam('id'); 
		$this->view->sno = $id;
		$this->view->pageHeading = "Edit";
        $myform = new Form_Icons($id);
        $dataForm = $myform->getValues();
		
		if ($this->getRequest()->isPost()) {
            if($dataForm['image'] != ""){
                $profileImagefront = time()."_".$dataForm['image'];
                @rename(SITE_ROOT.'images/tshirt-icons/'.$dataForm['image'],SITE_ROOT.'images/tshirt-icons/'.$profileImagefront);
                $imagefront = IMAGES_URL."tshirt-icons/".$profileImagefront;
                $info2 = getimagesize($imagefront);
                $width2 = @$info2 [0];
                $height2 = @$info2 [1];

                if ($width2 < 30 && $height2 < 30) {
                    unlink(SITE_ROOT.'images/tshirt-icons/'.$profileImagefront);
                    $mySession->errorMsg = "Image height or width is less than from Minimum-height=30px, Minimum-Width=30px";
                    $this->view->myform = $myform;
                    if($id != ''){
                        $this->view->id=$id;
                    }
                    $this->_redirect('tshirt/editnewicon');
                }
            }

			$request = $this->getRequest();
			$myform = new Form_Icons($id);			
			
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				$myObj = new Price();
				$Result = $myObj->Updatenewicon($dataForm,$id);
				if($Result == 1) {
					$mySession->errorMsg = "Icon updated successfully.";
					$this->_redirect('tshirt/icons');	
				} else {
				    //$mySession->errorMsg ="Page name you entered is already exists.";
					$this->view->myform = $myform;
					$this->render('editnewicon');
				}
			} else {
				$this->view->myform = $myform;
				$this->render('editnewicon');
			}
		} else {
			$this->_redirect('tshirt/editnewicon/id/'.$id);
		}
	}
		
    public function deletenewiconAction()
    {
        global $mySession;
        $db = new Db();
        if($_REQUEST['Id'] != "") {
            $arrId = explode("|",$_REQUEST['Id']);
            if(count($arrId) > 0) {
                foreach($arrId as $key => $Id)  {
                    //echo "a=".$Id;
                    $condition1 = "id='".$Id."'";
                    //echo $condition1;
                    $db->delete(TSHIRT_ICONS,$condition1);
                }
            }
        }
        exit();
    }
			
    public function changeiconstatusAction()
    {
        global $mySession;
        $db = new Db();
        $BcID = $this->getRequest()->getParam('Id');
        $status = $this->getRequest()->getParam('Status');
        if($status == '1') {
            $status = '0';
        } else {
            $status = '1';
        }
        $data_update['status'] = $status;
        $condition = "id='".$BcID."' ";
        $db->modify(TSHIRT_ICONS,$data_update,$condition);
        if($db) {
            $mySession->errorMsg = "Status Changed successfully.";
            $this->_redirect('tshirt/icons');
        }
    }

	public function savenewiconAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading="Add Icons";
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			$myform = new Form_Icons("");
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				if($dataForm['image'] != "") {
					$profileImagefront = time()."_".$dataForm['image'];
                    @rename(SITE_ROOT.'images/tshirt-icons/'.$dataForm['image'],SITE_ROOT.'images/tshirt-icons/'.$profileImagefront);
                    $imagefront = IMAGES_URL."tshirt-icons/".$profileImagefront;
                    $info2 = getimagesize($imagefront);
                    $width2 = @$info2 [0];
                    $height2 = @$info2 [1];

                    if ($width2 < 30 && $height2 < 30) {
                        unlink(SITE_ROOT.'images/tshirt-icons/'.$profileImagefront);
                        $mySession->errorMsg = "Image height or width is less than from Minimum-height=30px, Minimum-Width=30px";
                        $this->view->myform = $myform;
                        $this->_redirect('tshirt/addnewicon');
                    }
				}
                $myObj = new Price();
                $Result = $myObj->SaveIcons($dataForm);

                if($Result > 0) {
                    $mySession->errorMsgTitle = "OperazioneRiuscita";
				    $mySession->errorMsg = "Icons Added successfully.";
					    $this->_redirect('tshirt/icons');
				}else{
					$mySession->errorMsg = "Icon already exist with this title";
					$this->view->myform = $myform;
					$this->render('addnewicon');
				}
			} else {
                $mySession->errorMsg = "Fill Required Fields";
				$this->view->myform = $myform;
				$this->render('addnewicon');
			}
		} else {
			$this->_redirect('tshirt/icons');
		}
	}
	
	public function editsizeAction()
	{
		global $mySession;
		$db = new Db();
		$sno=$this->getRequest()->getParam('sno'); 
		$this->view->sno = $sno;
		$myform=new Form_Size($sno);
		$this->view->myform = $myform;
        if($sno && $sno > 0){
            $this->view->pageHeading = "Edit Size";
        }else{
            $this->view->pageHeading = "New Size";
        }
	}
	
	
	public function updatesizeAction()
	{
		global $mySession;
		$db = new Db();
		$sno = $this->getRequest()->getParam('sno');
		$this->view->sno = $sno;
        $this->view->pageHeading = "Edit Size";
		
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			$myform = new Form_Size($sno);			
			
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				$sizeModel = new Size();
				$Result = $sizeModel->saveSize($dataForm,$sno);
				if($Result == 1) {
					$mySession->errorMsg = "Details updated successfully.";
					$this->_redirect('tshirt/size');	
				} else { //$mySession->errorMsg ="Page name you entered is already exists.";
					$this->view->myform = $myform;
					$this->render('editsize');
				}
			} else {
				$this->view->myform = $myform;
				$this->render('editsize');
			}
		} else {
			$this->_redirect('tshirt/editsize/sno/'.$sno);
		}
	}

    public function deletetshirtsizeAction(){
        $this->_helper->layout()->disableLayout();
        global $mySession;
        $db = new Db();
        $sizeModel = new Size();
        $ids = $this->getRequest()->getParam('ids');
        $idArray = explode("|", $ids);
        if($ids != "" && count($idArray)) {
            foreach($idArray as $key => $id){
                $sizeModel->removeSize((int)$id);
            }
            echo "true";
        }else{
            echo "false";
        }
        exit();
    }
	
	public function priceAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading = "Manage Price";
		$qry = "select * from ".TSHIRT_PRICE."";
		$sql = $db->runquery($qry);
		$this->view->sql=$sql;
	}
	
	public function discountAction()
	{
		global $mySession;
		$db = new Db();
		$this->view->pageHeading = "Manage Discount";
		$qry = "select * from ".TSHIRT_DISCOUNT."";
		$sql = $db->runquery($qry);
		$this->view->sql = $sql;
	}

	public function editpriceAction()
	{
		global $mySession;
		$db = new Db();
		$sno = $this->getRequest()->getParam('sno');
		$this->view->sno = $sno;
		$myform = new Form_Price($sno);
		$this->view->myform = $myform;
		$this->view->pageHeading = "Edit Price";
	}
	
	public function updatepriceAction()
	{
		global $mySession;
		$db = new Db();
		
		$sno = $this->getRequest()->getParam('sno');
		$this->view->sno = $sno;
		$this->view->pageHeading = "Edit Price";
		
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			$myform = new Form_Price($sno);			
			
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				$myObj = new Price();
				
				$Result = $myObj->Updateprice($dataForm,$sno);
				if($Result == 1) {
					$mySession->errorMsg = "Price details updated successfully.";
					$this->_redirect('tshirt/price');	
				} else {
				//$mySession->errorMsg ="Page name you entered is already exists.";
					$this->view->myform = $myform;
					$this->render('editprice');
				}
			} else {
				$this->view->myform = $myform;
				$this->render('editprice');
			}
		} else {
			$this->_redirect('tshirt/editprice/sno/'.$sno);
		}
	}

	public function editdiscountAction()
	{
		global $mySession;
		$sno = $this->getRequest()->getParam('sno');
		$this->view->sno = $sno;
		$myform = new Form_Discount($sno);
		$this->view->myform = $myform;
		$this->view->pageHeading = "Edit Discount";
	}
	
	
	public function updatediscountAction()
	{
		global $mySession;
		$db = new Db();
		$sno = $this->getRequest()->getParam('sno');
		$this->view->sno = $sno;
		$this->view->pageHeading="Edit Discount";
		
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			$myform = new Form_Discount($sno);			
			
			if ($myform->isValid($request->getPost())) {
				$dataForm = $myform->getValues();
				$myObj = new Price();
				$Result = $myObj->Updatediscount($dataForm,$sno);
				if($Result == 1) {
					$mySession->errorMsg = "Details updated successfully.";
					$this->_redirect('tshirt/discount');	
				} else {
				    //$mySession->errorMsg ="Page name you entered is already exists.";
					$this->view->myform = $myform;
					$this->render('editdiscount');
				}
			}else{
				$this->view->myform = $myform;
				$this->render('editdiscount');
			}
		} else {
			$this->_redirect('tshirt/editprice/sno/'.$sno);
		}
	}	
	
	
	
	
	public function updaterecordslistingsAction()
    {
        global $mySession;
        $db = new Db();
		
		$listingCounter = 1;
        foreach($_POST['item'] as $recordIDValue) {
			$data_update['t_sort'] = $listingCounter;
			$condition="t_cat_id='".$recordIDValue."' ";
			$Result = $db->modify(TSHIRT_PRODUCTS,$data_update,$condition);	
			
		$listingCounter++; 
					
        }
			
			
				
            
        
        exit();
    }
	
	
	
	
	
	
	
	
}