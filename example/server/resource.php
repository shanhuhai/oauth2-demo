<?php
/**
 * Author: shanhuhai
 * Date: 09/11/2017 15:00
 * Mail: 441358019@qq.com
 */

require_once __DIR__."/common.php";

$userInfo = [
    'username' => 'shanhuhai',
    'password' => '123123',
    'avatar' => 'http://www.dahouduan.com/wp-content/uploads/2017/11/avatar.jpg',
];

if(!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}

echo json_encode(array('success'=>true, 'userInfo'=>[
    'username'=>$userInfo['username'],
    'avatar'=> $userInfo['avatar']
]));