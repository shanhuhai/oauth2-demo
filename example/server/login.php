<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>认证中心，用户登录界面</title>

</head>
<body>
<?php
require 'common.php';

if(isset($_SESSION['username'])) {
    header("Location: ".SERVER_URL.'/authorize.php?'.$_SESSION['authorize_querystring']);
    exit;
}

if(isset($_POST['submit'])) {

    $user = $storage->getUser($_POST['username']);
    if(empty($user)) {
        exit('没有此用户');
    }
    if($user['password'] != $_POST['password']) {
        exit('密码不正确');
    }
    $_SESSION = array_merge($_SESSION, $user);
    header("Location: ".SERVER_URL.'/authorize.php?'.$_SESSION['authorize_querystring']);
}
if(isset($_REQUEST['logout'])) {
    unset($_SESSION['username']);
    session_destroy();
}
?>
<h1>认证中心，用户登录界面</h1>

<form method="post">
    用户名：<input type="text" name="username">
    密码： <input type="password" name="password">
    <input type="submit" name="submit" value="登录">
</form>

</body>
</html>