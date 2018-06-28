<?php
require_once __DIR__ . "/app/config.php";

Lms_Application::setRequest();
Lms_Application::prepareApi();
Lms_Debug::debug('Request URI: ' . $_SERVER['REQUEST_URI']);

$db = Lms_Db::get('main');
//$items = $db->select('select name_ru id, name_ru text from chm_commands');
//echo json_encode(['results'=>$items], JSON_UNESCAPED_UNICODE);
//echo '<pre>';print_r($items);


$items = $db->select('select name_ru, flag_img from chm_commands order by name_ru');
$tmp = [];
foreach ($items as $v)
    $tmp[] = array_values($v);
//echo '<pre>';print_r($tmp);
echo json_encode($tmp, JSON_UNESCAPED_UNICODE);

