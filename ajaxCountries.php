<?php
require_once __DIR__ . "/app/config.php";

Lms_Application::setRequest();
Lms_Application::prepareApi();
Lms_Debug::debug('Request URI: ' . $_SERVER['REQUEST_URI']);

$db = Lms_Db::get('main');
$items = $db->select('select name_ru id, name_ru text from chm_commands');

//echo '<pre>';print_r($items);
echo json_encode(['results'=>$items]);