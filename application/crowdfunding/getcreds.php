<?php
include_once(dirname(__FILE__) . '/aweber_api/aweber_api.php');
include_once(dirname(dirname(__FILE__)) . '/configs/crowdfunding.inc.php');

$accessKey = '';
$accessSecret = '';
$list_id = '';

$aweber = new AWeberAPI(AWEBER_KEY, AWEBER_SECRET);
if (!$accessKey || !$accessSecret){
    display_access_tokens($aweber);
}

try {
    $account = $aweber->getAccount($accessKey, $accessSecret);
    $account_id = $account->id;

    if (!$list_id){
        display_available_lists($account);
        exit;
    }

    print "Your script is configured properly! " .
        "You can now start to develop your API calls, see the example in this script.<br><br>" .
        "Be sure to set \$test_email if you are going to use the example<p>";


} catch(AWeberAPIException $exc) {
    print "<h3>AWeberAPIException:<h3>";
    print " <li> Type: $exc->type <br>";
    print " <li> Msg : $exc->message <br>";
    print " <li> Docs: $exc->documentation_url <br>";
    print "<hr>";
    exit(1);
}

function get_self(){
    return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function display_available_lists($account){
    print "Please add one of the lines of PHP Code below to the top of your script for the proper list<br>" .
            "then click <a href=\"" . get_self() . "\">here</a> to continue<p>";

    $listURL ="/accounts/{$account->id}/lists/";
    $lists = $account->loadFromUrl($listURL);
    foreach($lists->data['entries'] as $list ){
        print "\$list_id = '{$list['id']}'; // list name:{$list['name']}\n</br>";
    }
}

function display_access_tokens($aweber){
    if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
//print_r($aweber);
        $aweber->user->requestToken = $_GET['oauth_token'];
        $aweber->user->verifier = $_GET['oauth_verifier'];
        $aweber->user->tokenSecret = $_COOKIE['secret'];

        list($accessTokenKey, $accessTokenSecret) = $aweber->getAccessToken();

        $account = $aweber->getAccount($accessTokenKey, $accessTokenSecret);
        //echo $account->data["id"];

        print    "AWEBER_ACCOUNT_ID = " . $account->data["id"] . "\n<br>" .
                "AWEBER_ACCESS_KEY = '{$accessTokenKey}';\n<br>" .
                "AWEBER_ACCESS_SECRET = '{$accessTokenSecret}';\n<br>" .
                "<br><br>";

        $listURL ="/accounts/" . $account->data["id"] . "/lists/";
        $lists = $account->loadFromUrl($listURL);
        foreach($lists->data['entries'] as $list ){
            print "AWEBER_LIST_ID = {$list['id']} // list name:{$list['name']}\n</br>";
        }
        exit;
    }

    if(!isset($_SERVER['HTTP_USER_AGENT'])){
        print "This request must be made from a web browser\n";
        exit;
    }

    $callbackURL = get_self();
    list($key, $secret) = $aweber->getRequestToken($callbackURL);
    $authorizationURL = $aweber->getAuthorizeUrl();

    setcookie('secret', $secret);

    header("Location: $authorizationURL");
    exit();
}
?>
