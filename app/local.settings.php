<?php
/**
 * @copyright 2006-2011 LanMediaService, Ltd.
 * @license    http://www.lanmediaservice.com/license/1_0.txt
 * @author Ilya Spesivtsev <macondos@gmail.com>
 * @version $Id: local.settings.dist.php 700 2011-06-10 08:40:53Z macondos $
 */

date_default_timezone_set('Europe/Minsk');

$logDir = dirname(__FILE__) . '/logs/';
if (defined('LOGS_SUBDIR')) {
    $logDir .= rtrim(LOGS_SUBDIR, '/') . '/';
}
$writer = new Zend_Log_Writer_Stream(
    $logDir . 'error.' . date('Y-m-d') . '.log'
);
$config['logger']->addWriter($writer);
$format = '%timestamp% %ip%(%pid%) %priorityName% (%priority%): %message%'
    . PHP_EOL;
$formatter = new Zend_Log_Formatter_Simple($format);
$writer->setFormatter($formatter);
$filter = new Zend_Log_Filter_Priority(Zend_Log::NOTICE);
$writer->addFilter($filter);


$writer = new Zend_Log_Writer_Stream(
    $logDir . 'debug.' . date('Y-m-d') . '.log'
);
$config['logger']->addWriter($writer);
$format = '%timestamp% %ip%(%pid%) %priorityName% (%priority%): %message%'
    . PHP_EOL;
$formatter = new Zend_Log_Formatter_Simple($format);
$writer->setFormatter($formatter);


$config['databases']['main'] = array(
    'connectUri' => 'mysqli://rakovsky:Per$ikRul1t@10.10.10.31/persik?persist=1',
//    'connectUri' => 'mysqli://root:root@localhost/persik?persist=1',
    'initSql' => "SET NAMES utf8",
    'debug' => 0
);
$config['databases']['topology'] = array(
//    'connectUri' => 'mysqli://rakovsky:Per$ikRul1t@10.10.10.31/topology?persist=1',
    'connectUri' => 'mysqli://root:root@localhost/topology?persist=1',
    'initSql' => "SET NAMES utf8",
    'debug' => 0
);


$config['auth']['cookie']['key'] = md5($config['databases']['main']['connectUri']);

$config['optimize']['classes_combine'] = 0;

$config['auth']['1.0']['cookie'] = array(
    'crypt' => true,
    'mode' => Lms_Crypt::MODE_ECB,
    'algorithm' => 'blowfish',
    'key' => 'vb=%v1#W1'
);


$cache = Zend_Cache::factory(
    'Core',
//    'Memcached',
    'File',
    array(
        'lifetime' => null,
        'automatic_serialization' => true
    ),
    array(
//        'servers' => array(array('host' => 'localhost', 'port' => 11211, 'persistent' => true,))
        'cache_dir' => '/Users/chapaev/Sites/persik/persik-client/app/cache/'
    )
);

$config['thumbnail']['cache'] = $cache;

$config['api']['cache']['enabled'] = true;
$config['api']['cache']['instance'] = $cache;
$config['api']['cache']['methods']['Video.getOnair'] = array(
    'lifetime' => 5
);



$config['optimize']['classes_combine'] = 0;

//Lms_Ufs::setEncoding('c:/Users/macondos/Videos/storage', 'CP1251');


$config['files']['tth']['enabled'] = true;
/*
$config['files']['tth']['bin'] = '/usr/bin/nice -n 20 /usr/local/bin/tthsum';
*/

$config['update']['backup_path'] = 'z:/tmp/backup';


$config['symlinks'] = array('/www/apps/manager/src' => '/manager');
$config['symlinks'] = array('/www/media' => '/media');

$config['tmp'] = '/www/persik/tmp';

$config['webmoney']['purse'] = 'B227881180206';

$config['allowed_nets'] = array(
    '127.0.0.1/8',
    '192.168.0.0/16',
    '10.0.0.0/8',
    '95.167.79.66/32',
    '77.220.135.204/32',
    '82.142.137.2/32',
    '31.168.67.88/32',
    '112.153.226.1/31',
    '180.66.71.1/31',
);

$config['feedback']['email'] = 'info@persik.by';
//$config['feedback']['email'] = 'macondos@gmail.com';

$config['incoming']['root_dirs'][] = '/www/test';

$config['loadbalancer'] = "http://lb.persik.by:8080/BeesmartProxy/loadbalancer";

$config['optimize']['classes_combine'] = 0;
$config['optimize']['js_combine'] = 1;
$config['optimize']['js_compress'] = 0;
$config['optimize']['css_combine'] = 1;
$config['optimize']['css_compress'] = 1;
$config['optimize']['less_combine'] = 1;

if (!empty($_SERVER['SERVER_NAME']) && preg_match('{(^new|\.new)\.}i', $_SERVER['SERVER_NAME'])) {
    $config['template'] = 'new';
}
$config['restrictions'] = array();

if (!empty($_SERVER['SERVER_NAME']) && preg_match('{^dev\.}i', $_SERVER['SERVER_NAME'])) {
    $config['subtemplate'] = 'dev';
}

$config['persik_by_include_ips'][] = '178.121.23.167/32';//гомель

/*
$config['pay_sys_support'] = array(
    'assist' => array('byr' => false, 'byn' => true, 'rub' => true, 'eur' => true),
    'ipay' => array('byr' => false, 'byn' => true, 'rub' => false, 'eur' => false),
    'webmoney' => array('byr' => false, 'byn' => true, 'rub' => false, 'eur' => false),
    'easypay' => array('byr' => false, 'byn' => true, 'rub' => false, 'eur' => false),
    'infolan' => array('byr' => false, 'byn' => true, 'rub' => false, 'eur' => false),
);*/

//$config['infolan']['nets'][] = '178.172.206.143/32';
$config['apple_channels'] = [111, 118, 148, 176, 699, 700, 847, 883, 932];

$config['shops']['products']['fake1'] = [
    'main' => 37,
];
$config['shops']['products']['fake2'] = [
    'main' => 37,
];
$config['shops']['products']['fake3'] = [
    'main' => 37,
];
$config['shops']['products']['fake4'] = [
    'main' => 37,
];

$config['shops']['products_visible'][1] = [
    'fake1' => 'Подписка 1 месяц',
    'fake2' => '6 месяцев',
    'fake3' => '1 год',
    'fake4' => '3 года',
];

$config['infolan']['nets'][] = '213.184.224.164/32';


$config['mail']['transport']['noreply@persik.tv'] = new Zend_Mail_Transport_Smtp('81.177.100.189');
//$config['mail']['transport']['do_not_reply@persik.by'] = new Zend_Mail_Transport_Smtp('10.10.10.48');


//$config['pay_sys_support']['yandex'] = ['byn' => false, 'rub' => false, 'eur' => false];