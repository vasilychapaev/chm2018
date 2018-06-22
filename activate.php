<?php

if (empty($_GET['email']) || empty($_GET['v'])) {
    exit;
}
define('SKIP_DEBUG_CONSOLE', true);

require_once __DIR__ . "/app/config.php";

Lms_Application::setRequest();
Lms_Application::prepareApi();
Lms_Debug::debug('Request URI: ' . $_SERVER['REQUEST_URI']);


$user = Lms_Item_User::getByEmail($_GET['email']);
if (!$user) {
    echo "Bad email";
    exit;
}

if (!$user->getEnabled()) {
    if ($_GET['v']!=$user->getVerificationToken()) {
        echo "Bad code";
        exit;
    }
    $user->activate()
        ->clearAuthTokens()
        ->setAuthToken();

//    if (!$user->getTrial()) {
//        $user->addTrial2();
//    }
}

$redirectUrl = $_COOKIE['need_auth_backurl']?:'/';
setcookie('need_auth_backurl', '', -1);


header('HTTP/1.1 302 Moved Temporary');
header('Location: '.$redirectUrl);
header("Pragma: public");
header("Cache-Control: public");
header("Expires: " . date("r", time() + 600));

Lms_Application::close();
