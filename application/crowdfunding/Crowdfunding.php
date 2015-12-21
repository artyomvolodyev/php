<?php
require_once (dirname(__FILE__) . '/stripe/Stripe.php');
class Crowdfunding
{
    public function create_campaign($params)
    {
        include_once(dirname(__FILE__) . '/aweber_api/aweber_api.php');
        date_default_timezone_set('America/Chicago');
        
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $sellingShare = $params['sellprice'] - $params['baseprice'];
        error_log('Crowdfunding->create_campaign selling_share for campaign ID '.$params['lid'].' (price '.$params['sellprice'].' - base price '.$params['baseprice'].'): '.$sellingShare);
        $campaign_update = $pdo->prepare("update " . LAUNCHCAMPAIGN . " set selling_share=? where campaign_id=?");
        $campaign_update->execute(array($sellingShare, $params['lid']));
        
        $campaign_info = $pdo->prepare("select emailid, public_name from " . USERS . " where user_id=?");
        $campaign_info->execute(array($params["creator"]));
        $user = $campaign_info->fetch(PDO::FETCH_ASSOC);

        // Subscribe Seller to Aweber Selling Mailing List
        /*
        $aweber = new AWeberAPI(AWEBER_KEY, AWEBER_SECRET);
        try {
            $account = $aweber->getAccount(AWEBER_ACCESS_KEY, AWEBER_ACCESS_SECRET);

            $listURL = "/accounts/" . AWEBER_ACCOUNT_ID . "/lists/" . AWEBER_SELLERS_LIST_ID;
            $list = $account->loadFromUrl($listURL);

            $params = array(
                'email' => $user["emailid"],
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'ad_tracking' => "campaign ID: " . $params["lid"],
                'misc_notes' => "seller",
                'name' => $user["public_name"]
            );
            $subscribers = $list->subscribers;

            $new_subscriber = $subscribers->create($params);
        } catch (AWeberAPIException $e) {}
        */
        
    }

    public function create_preapproval($params)
    {
        //error_log('Crowdfunding->create_preapproval params: '.print_r($params, true));
        include_once(dirname(__FILE__) . '/aweber_api/aweber_api.php');
        date_default_timezone_set('America/Chicago');

        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $token = json_decode($params["cart_details"]["stripeToken"]);
        Stripe::setApiKey(STRIPE_SECRET_KEY);

        $customer = Stripe_Customer::create(array(
            'email' => $token->email,
            'card'  => $token->id
        ));
        //error_log('Crowdfunding->create_preapproval params: '.print_r($params, true));
        try {

            $sellingShare = $params['camp_details'][0]['selling_share'];
            if($sellingShare <= 0){
                $sellingShare = $params['camp_details'][0]['selling_price'] - $params['camp_details'][0]['base_price'];
                error_log('Crowdfunding->create_preapproval recover a selling share above 0 if possible: '.$sellingShare);
            }
            $preApproveArray = array(
                $params['camp_details'][0]['campaign_id'],
                $params['user'],
                $token->email,
                $params['cart_details']['cfname'] . ' ' . $params['cart_details']['clname'],
                $customer->id,
                $params['camp_details'][0]['selling_price'],
                $params['cart_details']['totquanty'] * $sellingShare,
                $params['cart_details']['total_Value_text'] - $params['cart_details']['totquanty'] * $sellingShare,
                $params['cart_details']['totquanty'],
                $params['sizes']
            );
            //error_log('Crowdfunding->create_preapproval inserted preapproval: '.print_r($preApproveArray, true));
            $preapproval = $pdo->prepare('INSERT INTO preapprovals VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "created", "'.date("Y-m-d H:i:s", time()).'")');
            $preapproval->execute($preApproveArray);
            $insert_id = $pdo->lastInsertId();
            //error_log('Crowdfunding->create_preapproval inserted preapproval has ID: '.$insert_id);

            $campaign_update = $pdo->prepare('UPDATE ' . LAUNCHCAMPAIGN . ' SET sold=sold+? WHERE campaign_id=?');
            $campaign_update->execute(array($params['cart_details']['totquanty'], $params['camp_details'][0]['campaign_id']));

            //error_log('Crowdfunding->create_preapproval update campaign ID '.$params['camp_details'][0]['campaign_id'].' with sold items increase with '.$params['cart_details']['totquanty'].' items');
        }
        catch(Exception $e) {
            error_log('Crowdfunding->create_preapproval exception: '.$e->getMessage());
        }
        // Subscribe user to Aweber Client Mailing List
        /*
        $aweber = new AWeberAPI(AWEBER_KEY, AWEBER_SECRET);
        try {
            $account = $aweber->getAccount(AWEBER_ACCESS_KEY, AWEBER_ACCESS_SECRET);

            $listURL = "/accounts/" . AWEBER_ACCOUNT_ID . "/lists/" . AWEBER_CLIENTS_LIST_ID;
            $list = $account->loadFromUrl($listURL);

            $params = array(
                'email' => (string) $token->email,
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'ad_tracking' => $params["sizes"],
                'misc_notes' => money_format('$%i', $params['cart_details']['total_Value_text']),
                'name' => (string) $token->name
            );
            $subscribers = $list->subscribers;

            $new_subscriber = $subscribers->create($params);
        } catch (AWeberAPIException $e) {}
        */

        header("Location:" . APPLICATION_URL . $params['camp_details'][0]['url']);
    }

    public function cancel_preapproval($params)
    {
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $preapproval = $pdo->prepare('SELECT preapprovals_key FROM preapprovals WHERE preapprovals_id=?');
        $preapproval->execute(array($params['pid']));
        $preapproval_row = $preapproval->fetch(PDO::FETCH_ASSOC);
        $data = array('preapproval_id' => $preapproval_row['preapprovals_key']);

        Stripe::setApiKey(STRIPE_SECRET_KEY);

        $cu = Stripe_Customer::retrieve($preapproval_row['preapprovals_key']);
        $cu->delete();

        $preapproval_delete = $pdo->prepare('DELETE FROM preapprovals WHERE preapprovals_id=?');
        $preapproval_delete->execute(array($params['pid']));
    }

    public function end_campaign($params)
    {
        $preapprovals_arr = array();
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $preapproval = $pdo->prepare('SELECT preapprovals_id FROM preapprovals WHERE preapprovals_campaign=?');
        $preapproval->execute(array($params['cid']));
        while ($preapproval_row = $preapproval->fetch(PDO::FETCH_ASSOC)) {
            $preapprovals_arr[] = $preapproval_row;
        }
        for ($i = 0; $i < count($preapprovals_arr); $i++) {
            $this->cancel_preapproval(array('pid' => $preapprovals_arr[$i]['preapprovals_id']));
        }
    }
/*
    public function confirm_preapproval($params)
    {
        include_once (dirname(dirname(__FILE__)) . '/configs/config.inc.php');
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $preapproval = $pdo->prepare('SELECT preapprovals_key,preapprovals_campaign,preapprovals_qty FROM preapprovals WHERE preapprovals_key=?');
        $preapproval->execute(array($params['preapproval_id']));
        $preapproval_row = $preapproval->fetch(PDO::FETCH_ASSOC);
        $data = array("preapproval_id" => $params['preapproval_id']);

        $wepay = new WePay(WEPAY_ACCESS_TOKEN);
        $wepay->useStaging(WEPAY_CLIENT_ID, WEPAY_CLIENT_SECRET);
        $result = $wepay->request('/preapproval', $data);

        $preapproval_update = $pdo->prepare('UPDATE preapprovals SET preapprovals_name=?, preapprovals_email=?,preapprovals_status="approved" WHERE preapprovals_key=?');
        $preapproval_update->execute(array($result->payer_name, $result->payer_email, $params['preapproval_id']));
        $campaign_update = $pdo->prepare('UPDATE ' . LAUNCHCAMPAIGN . ' SET sold=sold+? WHERE campaign_id=?');
        $campaign_update->execute(array($preapproval_row['preapprovals_qty'], $preapproval_row['preapprovals_campaign']));
    }
*/
    public function collect_payments($params)
    {
        include_once (dirname(dirname(__FILE__)) . '/configs/config.inc.php');
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $payments = $pdo->prepare('
            SELECT
                preapprovals.*,
                ' . LAUNCHCAMPAIGN . '.*,
                ' . USERS . '.emailid AS receiver_email
            FROM
                preapprovals
                LEFT JOIN ' . LAUNCHCAMPAIGN . ' ON preapprovals.preapprovals_campaign=' . LAUNCHCAMPAIGN . '.campaign_id
                LEFT JOIN ' . USERS . ' ON ' . USERS . '.user_id=' . LAUNCHCAMPAIGN . '.user_id
            WHERE
                preapprovals_campaign=?
                AND preapprovals_status!="collected"
        ');
        $payments->execute(array($params['campid']));
        while ($payments_row = $payments->fetch(PDO::FETCH_ASSOC)) {
            $payments_arr[] = $payments_row;
        }
        $order = $pdo->prepare('INSERT INTO order_record VALUES(null,?, ?, ?, ?, ?, ?, ?, NOW(), ?, "")');
        $payments_update = $pdo->prepare('UPDATE preapprovals SET preapprovals_status=? WHERE preapprovals_id=?');
        $errors = 0;

        //print_r($payments_arr);

        Stripe::setApiKey(STRIPE_SECRET_KEY);

        for ($i = 0; $i < count($payments_arr); $i++) {
            if ($payments_arr[$i]["sold"] >= $payments_arr[$i]["goal"]) {
              try {
                  $charge = Stripe_Charge::create(array(
                      'customer' => $payments_arr[$i]['preapprovals_key'],
                      'amount'   => ($payments_arr[$i]['preapprovals_share_app'] + $payments_arr[$i]['preapprovals_share_user']) * 100,
                      'currency' => 'usd'
                  ));

                  if ($charge->paid == 1) {
                      $status = 'collected';
                  } else {
                      $status = 'failure';
                  }

                $order_data = array($payments_arr[$i]['tee_image'], $payments_arr[$i]['preapprovals_name'], $payments_arr[$i]['url'], $payments_arr[$i]['preapprovals_qty'], $payments_arr[$i]['preapprovals_size'], $payments_arr[$i]['preapprovals_price'], $payments_arr[$i]['preapprovals_share_app'] + $payments_arr[$i]['preapprovals_share_user'], $payments_arr[$i]['preapprovals_email']);
                $order->execute($order_data);
              } catch (Exception $e) {
                $status = 'ERROR: ' . $e->response->error_description . "\n";
                $errors++;
              }
            } else {
              try {
                $cu = Stripe_Customer::retrieve($payments_arr[$i]['preapprovals_key']);
                $cu->delete();
              } catch (Exception $e) {}

              $status = 'canceled';
            }

            $payments_update->execute(array($status, $payments_arr[$i]['preapprovals_id']));
        }

        $payments_update->execute(array($status, $payments_arr[$i]['preapprovals_id']));

        return array("attempts" => count($payments_arr), "errors" => $errors);
    }

    public function collect_all()
    {
        include_once (dirname(dirname(__FILE__)) . '/configs/config.inc.php');
        $db_params = parse_ini_file(dirname(dirname(__FILE__)) . '/configs/application.ini');
        $pdo = new PDO("mysql:dbname=" . $db_params['database.params.dbname'] . ";host=" . $db_params['database.params.host'], $db_params['database.params.username'], $db_params['database.params.password']);

        $payments = $pdo->prepare('
            SELECT
                DISTINCT preapprovals_campaign
            FROM
                preapprovals
                LEFT JOIN ' . LAUNCHCAMPAIGN . ' ON preapprovals.preapprovals_campaign=' . LAUNCHCAMPAIGN . '.campaign_id
            WHERE
                preapprovals_status!="collected"
                AND ADDDATE(launch_date, INTERVAL campaign_length DAY)<NOW()
                AND ADDDATE(launch_date, INTERVAL campaign_length DAY)>DATE_SUB(NOW(), INTERVAL 7 DAY)
        ');
        $payments->execute();
        $cids = array();
        while ($payments_row = $payments->fetch(PDO::FETCH_ASSOC)) {
            $cids[] = $payments_row;
        }
        for ($i = 0; $i < count($cids); $i++) {
            $this->collect_payments(array('campid' => $cids[$i]['preapprovals_campaign']));
        }
    }
}
