<?php


require_once __DIR__."/common.php";


$_SESSION['authorize_querystring'] = $_SERVER['QUERY_STRING'];

// 判断如果没有登录则跳转到登录界面
if(!isset($_SESSION['username']) && strpos($_SERVER['REQUEST_URI'], 'login.php') === false) {
    header("Location: ".SERVER_URL.'/login.php');
    exit;
}

$request = OAuth2\Request::createFromGlobals();

$response = new \OAuth2\Response();

if(!$server->validateAuthorizeRequest($request, $response)) {

    $response->send();
    die;
}

if(empty($_POST)) {
    exit('<form method="post">
  <label>是否授权给 '.$_GET['client_id'].'?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>  
    <a href="/login.php?logout=1">退出登录</a>
');
}

// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized);

$response->send();
