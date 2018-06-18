<?php 
/**
 * Настройки конфигурации по-умолчанию
 * 
 * @copyright 2006-2011 LanMediaService, Ltd.
 * @license    http://www.lanmediaservice.com/license/1_0.txt
 * @author Ilya Spesivtsev <macondos@gmail.com>
 * @version $Id: default.settings.php 700 2011-06-10 08:40:53Z macondos $
 */

/**
 * Режим вывода ошибок
 */
error_reporting(E_ALL);

@setlocale(LC_ALL, array('ru_RU.CP1251','ru_RU.cp1251','ru_SU.CP1251','ru_RU','ru','russian')); 
@setlocale(LC_NUMERIC, '');

/**
 * Установка временной зоны
 */
date_default_timezone_set('UTC');

/**
 * Инициализация отладки
 */
$config['logger'] = new Zend_Log();
$config['logger']->setEventItem('pid', getmypid());
$config['logger']->setEventItem('ip', Lms_Ip::getIp());


/**
 * Настройка языков
 */
$config['langs']['supported'] = array('ru'=>'Русский', 
                                      'en'=>'English (US)');
$config['langs']['default'] = 'ru';


/**
 * Временная директория для общих нужд
 */
$config['tmp'] = isset($config['tempdir'])? $config['tempdir'] : ((isset($_ENV['TEMP']))? $_ENV['TEMP'] : '/tmp');

$config['optimize']['classes_combine'] = 0;
$config['optimize']['js_combine'] = 0;
$config['optimize']['js_compress'] = 0;
$config['optimize']['css_combine'] = 0;
$config['optimize']['css_compress'] = 0;
$config['optimize']['less_combine'] = 0;
$config['optimize']['disable_epg'] = false;

/**
 * Настройки сервиса парсинга
 */
$config['parser_service']['username'] = '';
$config['parser_service']['password'] = '';
$config['parser_service']['url'] = 'http://service.lanmediaservice.com/2/actions.php';

$config['http_client']['maxredirects'] = 5;
$config['http_client']['timeout'] = 60;
$config['http_client']['useragent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 (.NET CLR 3.5.30729)';
                       

$config['hit_factor'] = 3;//коэффициент скачиваний выше среднего, чтобы считаться хитом

$config['indexing']['stop_words'] = preg_split('{\s+}', 'of the or and in to i ii iii iv v on de la le les no at it na ne vs hd season сезон в на не из от по до за или');

$config['thumbnail']['key'] = md5('d;sfkjas;lvijr');
$config['thumbnail']['script'] = 'thumbnail.php';
$config['thumbnail']['cache'] = false;


$config['auth']['1.0']['cookie'] = array(
    'crypt' => false
);

$config['parsing']['default_engines'] = array(
    'kinopoisk' => true,
    'ozon' => false,
    'world-art' => false,
    'sharereactor' => false,
    'imdb' => false,
);

//коэффициент качество полей данных, учитываемое при автослиянии данных
$config['automerge'] = array();
$config['automerge']['manual'] = array(
    'default' => 10,
);
$config['automerge']['imdb'] = array(
    'default' => 0.9,
    'description' => 0.1,
    'rating_imdb_value' => 2,
    'rating_imdb_count' => 2,
    'mpaa' => 2,
    'poster' => 2,
);

$config['automerge']['kinopoisk'] = array(
    'default' => 1,
    'poster' => 0.5,
    'genres' => 2,
    'countries' => 2,
    'name' => 2,
    'persones' => 2,
);

$config['automerge']['ozon'] = array(
    'default' => 1,
    'genres' => 0,
    'countries' => 0.1,
    'description' => 2,
);

$config['automerge']['world-art'] = array(
    'default' => 1,
    'genres' => 2.1,
    'description' => 2.1,
);


$encodings = array(
    "k" => "KOI8-R",
    "w" => "CP1251",
    "i" => "ISO-8859-5",
    "a" => "CP866",
    "d" => "CP866",
    "m" => "MacCyrillic",
);


$config['download']['escape']['encoding'] = isset($config['enc_ftpforclient'])? (isset($encodings[$config['enc_ftpforclient']])? $encodings[$config['enc_ftpforclient']] : $config['enc_ftpforclient']) : false;

$config['filesystem']['permissions']['directory'] = 0755;
$config['filesystem']['permissions']['file'] = isset($config['folder_rights'])? $config['folder_rights'] : 0644;//from 1.0

Lms_Ufs::setInternalEncoding('CP1251');

Lms_Ufs::setSystemEncoding('CP1251');

Lms_Ufs::addConfig('ls_dateformat_in_iso8601', @$config['ls_dateformat_in_iso8601']);

Lms_Ufs::addConfig('disable_4gb_support', @$config['disable_4gb_support']);

if (!empty($config["dir_extensions"])) {
    foreach ($config["dir_extensions"] as $path => $pathConfig) {
        if (!empty($pathConfig['encoding'])) {
            Lms_Ufs::setEncoding($path, $pathConfig['encoding']);
        }
    }
}

$config['mail']['from_email'] = 'noreply@persik.tv';
$config['mail']['from_email_by'] = 'do_not_reply@persik.by';
$config['mail']['from_name'] = 'Persik';

$config['mail']['transport']['noreply@persik.tv'] = new Zend_Mail_Transport_Smtp('smtp.mail.ru', array(
    'auth' => 'login',
    'username' => 'noreply@persik.tv',
    'password' => '65yc!dzVwVRY',
    'ssl' => 'tls')
);
$config['mail']['transport']['do_not_reply@persik.by'] = new Zend_Mail_Transport_Smtp('10.10.10.41');

$config['mail']['staff'] = 'info@persik.by';

$config['ipay']['salt'] = 'jjjj364_5<>@#985IITr';
$config['ipay']['salt'] = 'hhRFEDHG756<>&45';
//$config['ipay']['srv_no'] = 761;
$config['ipay']['srv_no'] = '404321';
$config['ipay']['provider_url'] = 'http://persik.by';
$config['ipay']['min_amount'] = 0.5;

$config['assist']['api_url'] = 'https://pay109.paysec.by/orderstate/orderstate.cfm';
$config['assist']['pay_url'] = 'https://pay109.paysec.by/pay/order.cfm';
$config['assist']['recurrent_url'] = 'https://pay109.paysec.by/recurrent/rp.cfm';
$config['assist']['username'] = 'VIvashkevich_sale';
$config['assist']['password'] = 'VadimIvashkevich2013';
$config['assist']['secret_key'] = 'BPTc8HCJ';
$config['assist']['test'] = 0;
$config['assist']['merchant_id'] = '659059';
$config['assist']['after_pay_redirect'] = '/';
$config['assist']['private_key'] = APP_ROOT . '/persik-assist-private.key';
$config['assist']['min_amount'] = 0.5;
$config['assist']['url_return_ok'] = 'https://persik.by/assist.php';
$config['assist']['url_return_no'] = 'https://persik.by/';
$config['assist']['recurring_interval'] = 10;

$config['bn']['secret_key'] = 'u0dG5LfWJO';
$config['bn']['pay_url'] = 'https://ui.bn.by/persik.php';

$config['unet']['secret_key'] = 'AR8wxr7NnLhp';

$config['webmoney']['purse'] = 'B227881180206';
$config['webmoney']['success_url'] = 'https://persik.by/';
$config['webmoney']['fail_url'] = 'https://persik.by/';


$config['yandex']['pay_url'] = 'https://money.yandex.ru/eshop.xml';
$config['yandex']['shop_id'] = [
    'byn' => 168922,
    'rub' => 158844,
];
$config['yandex']['scid'] = [
    'byn' => 715747,
    'rub' => 715748,
];
$config['yandex']['secret'] = 'RYLX9UehrCWSfJDb648T';

$config['restrictions'][296] = array(
    'from' => 6*3600,
    'to' => 23 * 3600
);

$config['template'] = 'default';
if (!empty($_SERVER['SERVER_NAME']) && preg_match('{(^m|\.m)\.}i', $_SERVER['SERVER_NAME'])) {
    $config['template'] = 'mobile';
}

if (!empty($_SERVER['SERVER_NAME']) && preg_match('{^bn\.}i', $_SERVER['SERVER_NAME'])) {
    $config['subtemplate'] = 'bn'; 
}

$config['apis']['facebook']  = array(
    'app_id'  => '157556244398673',
    'secret' => '9517654af46a41524df4b492f0c1c4e6',
);

$config['apis']['facebook_mobile']  = array(
    'app_id'  => '201361716705228',
    'secret' => '641f0096c8545e9fbe89fed0df5fbb30',
);

$config['apis']['vkontakte']  = array(
    'client_id'  => '3428232',
    'secret' => 'r9vd8RABIp01e4g0LeGo',
);

$config['apis']['google']  = array(
    'client_id'  => '1089502240794.apps.googleusercontent.com',
    'secret' => 'X1rsl9xfXvy5Q8l5CTl7i_Gj',
);


$config['categories']['labels'] = array(
    Lms_Item_Movie::CATEGORY_FILM => 'films',
    Lms_Item_Movie::CATEGORY_SERIES => 'series',
    Lms_Item_Movie::CATEGORY_ANIMATION => 'animation',
    Lms_Item_Movie::CATEGORY_TVSHOW => 'tvshows',
    Lms_Item_Movie::CATEGORY_CHANNEL => 'channels'
);

$config['vod'] = array(
    'vod_ip' => "93.189.224.40",
    'vod_app' => "vod/_definst_",
);
$config['vod']['masks'] = array(
    '/exds2_raid5/' => ''
);

$config['registration']['trial2'] = 95;

$config['iptv_portal'] = array(
    'username' => 'admin', 
    'password' => 'cA61oCx9zr',
    'auth_uri' => 'https://admin.persik.iptvportal.ru/api/jsonrpc/',
    'jsonsql_uri' => 'https://admin.persik.iptvportal.ru/api/jsonsql/',
);

$config['trial']['term'] = 14;

$config['topology'] = array();
@include_once __DIR__ . '/config.btc.php';
@include_once __DIR__ . '/config.bynets.php';

$config['jtv'] = array(
    'output' => dirname(__DIR__) . '/downloads/jtv.zip',
);

$config['sections'] = array(
    'humor' => array(47, 358, 273, 67),
    'child' => array(283, 304, 274, 82, 341, 379, 357, 80),
    'sport' => 105,
    'business' => 97,
);

$config['adult_genres'] = array(43);

$config['landing']['trial'] = 81;
$config['landing']['trials'] = array(81, 86, 88, 95);

$config['ga']['account'] = 'UA-42884753-1';

$config['is_persik_tv'] = false;
$config['router']['default']['controller'] = 'index';
$config['router']['default']['action'] = 'index';

if (!empty($_SERVER['SERVER_NAME']) && preg_match('{persik\.tv}i', $_SERVER['SERVER_NAME']) && !preg_match('{player\.persik\.tv}i', $_SERVER['SERVER_NAME'])) {
    $config['is_persik_tv'] = true;
    $config['router']['default']['controller'] = 'info';
    $config['router']['default']['action'] = 'landing';
    $config['ga']['account'] = 'UA-64641748-1';
}

$config['currencies'] = array(
    'default' => 'eur',
    'valid' => array('byn','rub','eur')
);

switch (Lms_Application::getCountryCode()) {
    case 'BY':
        $config['currencies']['default'] = 'byn';
        break;
    case 'RU':
        $config['currencies']['default'] = 'rub';
        break;
}

$config['pay_sys_support'] = array(
    'assist' => array('byn' => true, 'rub' => true, 'eur' => true),
    'halva' => array('byn' => true, 'rub' => false, 'eur' => false),
    'ipay' => array('byn' => true, 'rub' => false, 'eur' => false),
    'webmoney' => array('byn' => true, 'rub' => false, 'eur' => false),
    'yandex' => array('byn' => true, 'rub' => false, 'eur' => false),
);

if (!empty($_SERVER['SERVER_NAME']) && preg_match('{(onlineweb\.tv|player\.persik|tv\.unet\.)}i', $_SERVER['SERVER_NAME'])) {
    $config['subtemplate'] = 'lite';    
}

$config['vod_only'] = false;

$config['discounts'] = array(
);

$config['shops']['bonus'] = 86;
$config['shops']['bonus_by_product'][8] = 86;
$config['shops']['bonus_by_product'][22] = 99;

$config['internal']['api_key'] = 'ed24de59a2f1456ecfd5f5120c3c73cc';

$config['image_service']['api_key'] = '3dcbe063446f5438999729c35152b602';

$config['apple_channels'] = [111, 118, 148, 176, 699, 700, 847, 883, 932];

$config['stream_hq_devices'] = ['lg', 'samsung', 'playlist'];

$config['edge']['shared_secret'] = '47HMku0tRwCY2v7av4pw';

$config['billing']['full_day_lag'] = 15 * 60;