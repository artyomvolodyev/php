<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 11/17/15
 * Time: 4:15 PM
 */

class StoreController extends AppController{

    public function indexAction(){
        echo "AAAAAA!!!";
        $data = new Launchcampaigndb();

        global $mySession;
        $db = new Db();
//        $this->_helper->layout->setLayout('myaccount');

        $this->view->pageTitle="Store";

        $qry                 = $db->runQuery("select * from " . LAUNCHCAMPAIGN );
        $this->view->qry = $qry;

    }


    public function trackorderAction()
    {

    }

} 