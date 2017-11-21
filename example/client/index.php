<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户信息</title>
</head>
<body>

<?php
require '../../vendor/autoload.php';
define('CLIENT_URL', 'http://oauth2-client.dev');
define('SERVER_URL', 'http://oauth2-server.dev');
define('REDIRECT_URI', CLIENT_URL.'/index.php');
define('RESOURCE_URL', SERVER_URL.'/resource.php');

define('CLIENT_ID', 'testclient');
define('CLIENT_SECRET', 'testpass');


session_start();
function userInfo(){
    if(isset($_SESSION['username'])) {
        return $_SESSION;
    } else {
        return false;
    }
}


if(isset($_REQUEST['logout'])) {
    unset($_SESSION['username']);
    session_destroy();
}


$userInfo = userInfo();
/*
 * 接收用户中心返回的授权码
 */
if (isset($_REQUEST['code']) && $_SERVER['REQUEST_URI']) {
    //将认证服务器返回的授权码从 URL 中解析出来
    $code = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'code=')+5, 40);

    // 拿授权码去申请令牌
    $client = new GuzzleHttp\Client();
    $response = $client->request('POST', SERVER_URL.'/token.php', [
        'auth' => [CLIENT_ID, CLIENT_SECRET],

        'form_params'=> [
            'grant_type'=>'authorization_code',
            'code'=> $code,
            'redirect_uri'=> REDIRECT_URI,
        ]
    ]);



    $response = json_decode($response->getBody(), true);
    // 将令牌缓存到 SESSION中，方便后续访问

 //   var_dump($response);exit;
    $_SESSION['access_token'] = $response['access_token'];



    // 使用令牌获取用户信息
    $response = $client->request('GET', RESOURCE_URL.'?access_token='.$_SESSION['access_token']);
    $response = json_decode($response->getBody(), true);

    $userInfo = $response['userInfo'];
    $_SESSION = array_merge($_SESSION, $userInfo);

}


$auth_url = SERVER_URL."/authorize.php?response_type=code&client_id=testclient&state=xyz&redirect_uri=". REDIRECT_URI;



?>

<?php if($userInfo): ?>
    欢迎 <?php echo $userInfo['username'];?>, 头像 <img src="<?php echo $userInfo['avatar']; ?>" alt="" />
    <a href="/index.php?logout=1">退出登录</a>
<?php else: ?>
    <a href="<?php echo $auth_url ?>">使用媒体云登录</a>
<?php endif;?>
</body>
</html>