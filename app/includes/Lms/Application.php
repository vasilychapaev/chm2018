<?php
/**
 * Инициализация приложения
 *
 * @copyright 2006-2011 LanMediaService, Ltd.
 * @license    http://www.lanmediaservice.com/license/1_0.txt
 * @author Ilya Spesivtsev <macondos@gmail.com>
 * @version $Id: Application.php 700 2011-06-10 08:40:53Z macondos $
 */

class Lms_Application
{

    private static $_config;

    /**
     * @var Lms_Api_Controller
     */
    private static $_apiController = null;
    /**
     * @var Zend_Controller_Front
     */
    private static $_frontController = null;
    /**
     * @var Zend_Translate
     */
    private static $_translate;
    /**
     * @var Zend_Acl
     */
    private static $_acl;
    /**
     * @var Lms_User
     */
    private static $_user;
    /**
     * @var Lms_MultiAuth
     */
    private static $_auth;
    /**
     * @var Zend_Controller_Request_Http
     */
    private static $_request;

    /**
     * Устройство по-умолчанию
     * @var string
     */
    private static $_defaultDevice = 'api';
    /**
     * Текущее устройство
     * @var string
     */
    private static $_device;

    /**
     * Текущая валюта
     * @var string
     */
    private static $_currency;

    /**
     * Текущий язык
     * @var string
     */
    private static $_lang;
    /**
     * Текущий макет
     * @var string
     */
    private static $_layout;
    /**
     * Базовый URL без учета модификатора языка
     * http://examle.com/root/Url/ru/blah/blah ($_rootUrl = /root/Url)
     * @var string
     */
    private static $_rootUrl;

    /**
     * Массив директорий скриптов шаблона (.phtml)
     * @var array
     */
    private static $_scriptsTemplates;

    /**
     * Массив реальных путей и соответствующих относительных URL
     * публичных файлов шаблона (.css, .js и т.д.)
     * Пример:
     * Array(
     *      [0] => Array
     *          (
     *              [path] => C:/www/english/public/templates/user/ru
     *              [url] => /public/templates/user/ru
     *          )
     *
     *      [1] => Array
     *          (
     *              [path] => C:/www/english/public/templates/user
     *              [url] => /public/templates/user
     *          )
     *
     *      [2] => Array
     *          (
     *              [path] => C:/www/english/public/templates/default.dist/ru
     *              [url] => /public/templates/default.dist/ru
     *          )
     *
     *      [3] => Array
     *          (
     *              [path] => C:/www/english/public/templates/default.dist
     *              [url] => /public/templates/default.dist
     *          )
     *
     *  )
     *
     * @var array
     */
    private static $_publicTemplates;

    /**
     * Время начало работы скрипта
     * @var float
     */
    private static $_mainTimer;

    /**
     * @return float
     */
    public static function getMainTimer()
    {
        return self::$_mainTimer;
    }

    private static $_httpClient;

    private static $_mplayer;

    public static $view;

    /** @var  Lms_Price */
    private static $_price;

    /**
     * @return Lms_Price
     */
    public static function getPrice()
    {
        return self::$_price;
    }

    public static function detectLang()
    {
        self::$_lang = self::$_config['langs']['default'];
        if (!empty($_GET['lang'])) {
            $lang = $_GET['lang'];
            if (array_key_exists($lang, self::$_config['langs']['supported'])) {
                self::$_lang = $lang;
            }
        }
        $locale = new Zend_Locale(self::$_lang);
        Zend_Registry::set('Zend_Locale', $locale);
        return self::$_lang;
    }

    public static function setRequest()
    {
        self::$_request = new Zend_Controller_Request_Http();
    }

    public static function runApi()
    {
        self::setRequest();
        self::prepareApi();
        self::$_apiController->exec();
        self::close();
    }

    public static function prepareApi()
    {
        /**
         * Разъяснение комментариев:
         * self::initYYY()//зависит от XXX
         * Это значит перед запуском, метода YYY, должен отработать метод XXX
         * self::initYYY()//требует XXX
         * Это значит, что для корректной работы сущностей определяемых
         * методом YYY, должен быть проинизиализирован метод XXX (место
         * инициализации не имеет важного значения)
         */

        self::initEnvironmentApi();
        self::initConfig();//зависит от initEnvironment
        self::initSessions();//зависит от initConfig
        self::initDebug();//зависит от initConfig
        self::initErrorHandler();//зависит от initDebug
        self::initDb(); //зависит от initConfig, требует initDebug
        self::initVariables();//зависит от initDb
        self::initFrontController();//зависит от initConfig
        self::initConfigFromDb();//зависит от initDb
        self::initApiController();//зависит от initVariables
        self::initTranslate();//зависит от initApiRequest, initDebug
        self::initAcl();//зависит от initConfig, initVariables, initDb
        self::initRoutes();//зависит от initFrontController
        self::initView();//зависит от initConfig, initFrontController,
                         //initAcl, initTranslate

    }

    public static function prepareCli()
    {
        /**
         * Разъяснение комментариев:
         * self::initYYY()//зависит от XXX
         * Это значит перед запуском, метода YYY, должен отработать метод XXX
         * self::initYYY()//требует XXX
         * Это значит, что для корректной работы сущностей определяемых
         * методом YYY, должен быть проинизиализирован метод XXX (место
         * инициализации не имеет важного значения)
         */

        self::initEnvironmentApi();
        self::initConfig();//зависит от initEnvironment
        self::initDebug();//зависит от initConfig
        self::initErrorHandler();//зависит от initDebug
        self::initDb(); //зависит от initConfig, требует initDebug
        self::initVariables();//зависит от initDb
        self::initConfigFromDb();//зависит от initDb
    }

    public static function initApiController()
    {
        self::$_apiController = Lms_Api_Controller::getInstance();
        self::$_apiController->analyzeHttpRequest();
        self::$_lang = self::$_apiController->getLang();
        if (!self::$_lang) {
            self::$_lang = self::$_config['langs']['default'];
        }
        if (Lms_Application::getConfig('api', 'cache', 'enabled')) {
            self::$_apiController->setCache(Lms_Application::getConfig('api', 'cache', 'instance'), Lms_Application::getConfig('api', 'cache', 'methods'));
        }
    }


    public static function run()
    {
        self::setRequest();
        $response = new Zend_Controller_Response_Http();
        $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        $channel->setRequest(self::$_request);
        $channel->setResponse($response);
        // Start output buffering
        ob_start();
        try {
            self::prepare();
            Lms_Debug::debug('Request URI: ' . $_SERVER['REQUEST_URI']);
            try {
                self::$_frontController->dispatch(self::$_request);
            } catch (Exception $e) {
                Lms_Debug::crit($e->getMessage());
                Lms_Debug::crit($e->getTraceAsString());
            }
            self::close();
        } catch (Exception $e) {
            echo $e->getMessage();
            Lms_Debug::crit($e->getMessage());
            Lms_Debug::crit($e->getTraceAsString());
        }
        // Flush log data to browser
        $channel->flush();
        $response->sendHeaders();
    }

    public static function prepare()
    {
        self::initEnvironment();
        self::initConfig();//зависит от initEnvironment
        self::initSessions();//зависит от initConfig
        self::initDebug();//зависит от initConfig
        self::initErrorHandler();//зависит от initDebug
        self::initDb(); //зависит от initConfig, требует initDebug
        self::initVariables();//зависит от initDb
        self::initConfigFromDb();//зависит от initDb
        self::initFrontController();//зависит от initConfig
        self::initTranslate();//зависит от initFrontController, initDebug
        self::initRoutes();//зависит от initFrontController
        self::initAcl();//зависит от initConfig, initVariables, initDb
        self::initView();//зависит от initConfig, initFrontController,
                         //initAcl, initTranslate
    }

    public static function initEnvironmentApi()
    {
        ini_set('max_execution_time', 0);
    }

    public static function initEnvironment()
    {
        ini_set('max_execution_time', 1000);
        header("Content-type:text/html;charset=UTF-8");
        if(get_magic_quotes_runtime())
        {
            // Deactivate
            set_magic_quotes_runtime(false);
        }
        static $alreadyStriped = false;
        if (get_magic_quotes_gpc() || !$alreadyStriped) {
            $_COOKIE = Lms_Array::recursiveStripSlashes($_COOKIE);
            $_GET = Lms_Array::recursiveStripSlashes($_GET);
            $_POST = Lms_Array::recursiveStripSlashes($_POST);
            $_REQUEST = Lms_Array::recursiveStripSlashes($_REQUEST);
            $alreadyStriped = true;
        }
    }

    public static function initConfig()
    {

        include_once APP_ROOT . "/../config.php";
        include_once APP_ROOT . "/default.settings.php";
        include_once APP_ROOT . "/local.settings.php";
        self::$_config = $config;
    }

    public static function initConfigFromDb()
    {
        $db = Lms_Db::get('main');
        $rows = $db->select('SELECT * FROM config');
        foreach ($rows as $row) {
            switch ($row['type']) {
                case 'array':
                    $value = json_decode($row['value'], true);
                    break;
                case 'scalar':
                default:
                    $value = $row['value'];
                    break;
            }
            $keys = preg_split('{/}', $row['key']);
            switch (count($keys)) {
                case 1:
                    self::$_config[$keys[0]] = $value;
                    break;
                case 2:
                    self::$_config[$keys[0]][$keys[1]] = $value;
                    break;
                case 3:
                    self::$_config[$keys[0]][$keys[1]][$keys[2]] = $value;
                    break;
                case 4:
                    self::$_config[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $value;
                    break;
                case 5:
                    self::$_config[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $value;
                    break;
                default:
                    throw new Lms_Exception("DB-config keys not support deep more 5 subitems");
                    break;
            }
        }
    }

    public static function initSessions()
    {
        Zend_Session::start();
    }

    public static function initDebug()
    {
        Lms_Debug::setLogger(self::$_config['logger']);
        self::$_mainTimer = new Lms_Timer();
        self::$_mainTimer->start();
    }

    public static function initErrorHandler()
    {
        Lms_Debug::initErrorHandler();
    }

    public static function initDb()
    {
        foreach (self::$_config['databases'] as $dbAlias => $dbConfig) {
            Lms_Db::addDb(
                $dbAlias,
                $dbConfig['connectUri'],
                $dbConfig['initSql'],
                $dbConfig['debug']
            );
        }
    }

    public static function initVariables()
    {
        if (self::$_request) {
            self::$_rootUrl = self::$_request->getBaseUrl();
        }
        if (preg_match('{\.php$}i', self::$_rootUrl)) {
            self::$_rootUrl = dirname(self::$_rootUrl);
            self::$_rootUrl = str_replace('\\', '/', self::$_rootUrl);
            self::$_request->setBaseUrl(self::$_rootUrl);
        }
        if (self::$_rootUrl=='/') {
            self::$_rootUrl = '';
        }

        Lms_Item::setDb(Lms_Db::get("main"), Lms_Db::get("main"));
        Lms_Item::setDb(Lms_Db::get("topology"), Lms_Db::get("topology"), ['Point', 'Template', 'Process']);
        Lms_Item_Preloader::setDb(Lms_Db::get("main"));

        Lms_Item_Struct_Generator::setStoragePath(
            APP_ROOT . '/includes/Lms/Item/Struct'
        );

        Lms_Text::setEncoding('UTF-8');
        Lms_Text::enableMultiByte();
        Lms_Api_Formatter_Ajax::setEncoding('UTF-8');
        Lms_Api_Formatter_Json::setEncoding('UTF-8');

        Lms_Thumbnail::setHttpClient(self::getHttpClient());
        Lms_Thumbnail::setThumbnailScript(rtrim(self::$_rootUrl, '/\\') . '/' . self::getConfig('thumbnail', 'script'), self::getConfig('thumbnail', 'key'));
        Lms_Thumbnail::setImageDir(
            rtrim(dirname(APP_ROOT)) . '/media/images'
        );
        Lms_Thumbnail::setThumbnailDir(
            rtrim(dirname(APP_ROOT)) . '/media/thumbnails'
        );
        Lms_Thumbnail::setErrorImagePath(rtrim(dirname(APP_ROOT)) . '/media/error.png');
        Lms_Thumbnail::setCache(self::getConfig('thumbnail', 'cache'));

        Lms_View_Helper_OptimizedHeadScript::setCacheDir(
            rtrim($_SERVER['DOCUMENT_ROOT'] . self::$_rootUrl, '/\\') . '/media/cache/js'
        );
        Lms_View_Helper_OptimizedHeadLink::setCacheDir(
            rtrim($_SERVER['DOCUMENT_ROOT'] . self::$_rootUrl, '/\\') . '/media/cache/css'
        );
        Lms_View_Helper_OptimizedHeadLess::setCacheDir(
            rtrim($_SERVER['DOCUMENT_ROOT'] . self::$_rootUrl, '/\\') . '/media/cache/css'
        );

        Lms_Service_Images::setAppKey(self::getConfig('image_service', 'api_key'));

        self::$_price = new Lms_Price();
        self::$_price->setRatesLoader(['Lms_Application', 'getRates']);

        Lms_Item_ProductOption::setFullDayLag(self::getConfig('billing', 'full_day_lag'));

    }

    public static function initAcl()
    {
        self::$_auth = Lms_MultiAuth::getInstance();

        $cookieManager = new Lms_CookieManager(
            self::$_config['auth']['cookie']['key']
        );
        $authStorage = new Lms_Auth_Storage_Cookie(
            $cookieManager,
            self::$_config['auth']['cookie']
        );
        self::$_auth->setStorage($authStorage);

        self::$_acl = new Zend_Acl();
        self::$_acl->addRole(new Zend_Acl_Role('guest'))
                   ->addRole(new Zend_Acl_Role('user'), 'guest')
                   ->addRole(new Zend_Acl_Role('accountmanager'), 'user')
                   ->addRole(new Zend_Acl_Role('contentmanager'), 'user')
                   ->addRole(new Zend_Acl_Role('moder'), 'user')
                   ->addRole(new Zend_Acl_Role('technician'), 'user')
                   ->addRole(new Zend_Acl_Role('analyst'), 'user')
                   ->addRole(new Zend_Acl_Role('admin'));

        self::$_acl->add(new Zend_Acl_Resource('movie'))
                   ->add(new Zend_Acl_Resource('comment'))
                   ->add(new Zend_Acl_Resource('bookmark'))
                   ->add(new Zend_Acl_Resource('rating'))
                   ->add(new Zend_Acl_Resource('user'))
                   ->add(new Zend_Acl_Resource('image-proxy'))
                   ->add(new Zend_Acl_Resource('email'))
                    ;


        self::$_acl->allow('admin')
                   ->allow('moder', array('movie', 'comment', 'image-proxy'))
                   ->allow('analyst', array('email', 'movie', 'user', 'image-proxy'))
                   ->allow('contentmanager', array('email', 'image-proxy'))
                   ->allow('technician', array('email', 'movie', 'user', 'image-proxy'))
                   ->allow('accountmanager', array('user', 'image-proxy'))
                   ->allow('user', array('bookmark', 'rating'))
                   ->allow('user', array('comment'), 'post')
                   ->allow('guest', array('movie'), 'view');

        Lms_User::setAcl(self::$_acl);
        self::$_user = Lms_User::getUser();
    }

    public static function initFrontController()
    {
        self::$_frontController = Zend_Controller_Front::getInstance();
        $controllerDirectory = APP_ROOT . "/templates/"
                             . self::$_config['template'] . '/controllers';
        self::$_frontController->throwExceptions(false)
                               ->setControllerDirectory($controllerDirectory)
                               ->setDefaultControllerName('index')
                               ->setDefaultAction('index')
                               ->setParams(array());

        if (!self::$_request) {
            Lms_Application::setRequest();
        }
        self::detectLang(self::$_request);
        self::$_frontController->setRequest(self::$_request);
        return self::$_frontController;
    }

    public static function initTranslate()
    {
        $db = Lms_Db::get('main');
        $translation = $db->selectCol('SELECT `key` AS ARRAY_KEY, ?# FROM translate', self::$_lang);
//        print_r($translation);
        self::$_translate = new Zend_Translate('array',
                                                $translation,
                                                self::$_lang);

        self::$_translate->setOptions(
            array('log' => Lms_Debug::getLogger(),
                  'logUntranslated' => true)
        );

    }

    public static function initRoutes()
    {
        self::$_frontController->setDefaultControllerName(self::getConfig('router', 'default', 'controller'))
                               ->setDefaultAction(self::getConfig('router', 'default', 'action'));

        $router = self::$_frontController->getRouter();


        $router->addRoute(
            'future',
            new Zend_Controller_Router_Route('future/*',
                array(
                    'controller' => 'index',
                    'action'     => 'future'
                )
            )
        );


        $router->addRoute(
            'archive',
            new Zend_Controller_Router_Route('archive/*',
                array(
                    'controller' => 'index',
                    'action'     => 'archive'
                )
            )
        );

        $router->addRoute(
            'setka',
            new Zend_Controller_Router_Route('setka/*',
                array(
                    'controller' => 'index',
                    'action'     => 'setka'
                )
            )
        );

        $router->addRoute(
            'raspisanie',
            new Zend_Controller_Router_Route('raspisanie/*',
                array(
                    'controller' => 'index',
                    'action'     => 'raspisanie'
                )
            )
        );


        $router->addRoute(
            'login',
            new Zend_Controller_Router_Route('login/*',
                array(
                    'controller' => 'index',
                    'action'     => 'login'
                )
            )
        );


        $router->addRoute(
            'regcomplete',
            new Zend_Controller_Router_Route('registrationsuccess/*',
                array(
                    'controller' => 'index',
                    'action'     => 'registrationsuccess'
                )
            )
        );


        $router->addRoute(
            'match',
            new Zend_Controller_Router_Route('match/:cmd1/:cmd2/:tvshow_id/*',
                array(
                    'controller' => 'index',
                    'action'     => 'match'
                )
            )
        );

        $router->addRoute(
            'tvshow',
            new Zend_Controller_Router_Route('tvshow/:tvshow_id/*',
                array(
                    'controller' => 'index',
                    'action'     => 'tvshow'
                )
            )
        );


        $router->addRoute(
            'search',
            new Zend_Controller_Router_Route('search/*',
                array(
                    'controller' => 'index',
                    'action'     => 'search'
                )
            )
        );



        $router->addRoute(
            'mailer',
            new Zend_Controller_Router_Route('mailer/*',
                array(
                    'controller' => 'index',
                    'action'     => 'mailer'
                )
            )
        );

        $router->addRoute(
            'dev',
            new Zend_Controller_Router_Route('dev/*',
                array(
                    'controller' => 'index',
                    'action'     => 'dev'
                )
            )
        );


    }

    public static function initView()
    {
        //LIFO order
        self::$_scriptsTemplates = array(
            APP_ROOT . '/templates/' . self::$_config['template']
        );

        //FIFO order
        $relativeTemplateUrls = array(
            '/' . self::$_config['template']
        );

        if (!empty(self::$_config['subtemplate'])) {
            Lms_Debug::debug(self::$_config['subtemplate']);
            self::$_scriptsTemplates[] =  APP_ROOT . '/templates/' . self::$_config['subtemplate'];
            array_unshift($relativeTemplateUrls, '/' . self::$_config['subtemplate']);
        }
        //rsort(self::$_scriptsTemplates);

        self::$_publicTemplates = array();
        foreach ($relativeTemplateUrls as $relativeTemplateUrl) {
            $path = $_SERVER['DOCUMENT_ROOT']
                  . self::$_rootUrl . '/templates' . $relativeTemplateUrl;
            if (is_dir($path)) {
                self::$_publicTemplates[] = array(
                    'path' => $path,
                    'url' => self::$_rootUrl . '/templates' . $relativeTemplateUrl
                );
            }
        }
        //Lms_Debug::debug(print_r(self::$_publicTemplates,1));

        $view = new Zend_View(array('encoding' => 'UTF-8'));

        $view->addHelperPath('Lms/View/Helper', 'Lms_View_Helper');

        foreach (self::$_scriptsTemplates as $scriptTemplate) {
            $view->addScriptPath($scriptTemplate . '/views/scripts')
                 ->addScriptPath($scriptTemplate . '/layouts');
        }
        //Lms_Debug::debug(print_r($view->getScriptPaths(),1));

        $view->supportedLangs = self::$_config['langs']['supported'];
        $view->t = self::$_translate;
        $view->lang = self::$_lang;
        $view->hostUrl = 'http://' . $_SERVER['HTTP_HOST'];
        $view->rootUrl = self::$_rootUrl;
        $view->baseUrl = self::$_request->getBaseUrl();
        $view->user = self::$_user;
        $view->auth = self::$_auth;
        $view->publicTemplates = self::$_publicTemplates;

        $config = self::getConfig();
        if (isset($config['lms_jsf_path'])) {
            $view->lmsJsfPath = $config['lms_jsf_path'];
        } else {
            if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/lms-jsf')) {
                $view->lmsJsfPath = '/lms-jsf';
            } else {
                $view->lmsJsfPath = self::$_rootUrl . '/lms-jsf';
            }
        }
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'viewRenderer'
        );
        $viewRenderer->setView($view);

        Zend_Layout::startMvc();

        $view->tabs = array();

        self::$view = $view;

        return $view;
    }

    public static function close()
    {
        if (self::getConfig('optimize', 'classes_combine')) {
            Lms_NameScheme_Autoload::compileTo(APP_ROOT . '/includes/All.php');
        }

        foreach (self::$_config['databases'] as $dbAlias => $dbConfig) {
            if (Lms_Db::isInstanciated($dbAlias)) {
                $db = Lms_Db::get($dbAlias);
                $sqlStatistics = $db->getStatistics();
                $time = round(1000 * $sqlStatistics['time']);
                $count = $sqlStatistics['count'];
                Lms_Debug::debug(
                    "Database $dbAlias time: $time ms ($count queries)"
                );
            }
        }
        foreach (Lms_Timers::getTimers() as $name => $timer) {
            $time = round(1000 * $timer->getSumTime());
            Lms_Debug::debug(
                'Profiling "' . $name . '": ' . $time . ' ms (' . $timer->getCount() . ')'
            );
        }
        Lms_Debug::debug(
            'Used memory: ' . round(memory_get_usage()/1024) . ' KB'
        );
        self::$_mainTimer->stop();
        $time = round(1000 *self::$_mainTimer->getSumTime());
        Lms_Debug::debug("Execution time: $time ms");
    }

    public static function getLang()
    {
        return self::$_lang;
    }

    public static function setLang($lang)
    {
        self::$_lang = $lang;
    }

    public static function getTranslate()
    {
        return self::$_translate;
    }

    public static function getRequest()
    {
        return self::$_request;
    }

    public static function getConfig($param = null)
    {
        $params = func_get_args();
        $result = self::$_config;
        foreach($params as $param) {
            if (!array_key_exists($param, $result)) {
                return null;
            }
            $result = $result[$param];
        }
        return $result;
    }

    /**
     * @param array $path
     * @param $value
     */
    public static function setConfig($path, $value)
    {
        $current = &self::$_config;
        foreach($path as $key) {
            if (!array_key_exists($key, $current)) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        $current = $value;
    }

    public static function getHttpClient()
    {
        if (!self::$_httpClient) {
            $httpOptions = Lms_Application::getConfig('http_client');
            self::$_httpClient = new Zend_Http_Client(
                null,
                $httpOptions
            );
        }
        return self::$_httpClient;
    }

    public static function getMplayer()
    {
        if (!self::$_mplayer) {
            self::$_mplayer = new Lms_ExternalBin_Mplayer(self::getConfig('mplayer', 'bin'));
            self::$_mplayer->setTempPath(self::getConfig('mplayer','tmp'));
        }
        return self::$_mplayer;
    }

    public static function getLeechProtectionCode($array)
    {
        $str = implode("-", $array);
        $str .= self::getConfig('antileechkey')? self::getConfig('antileechkey') : "secret";
        return md5($str);
    }

    public static function thumbnail($imgPath, $width = 0, $height = 0, $defer = false, $force = true)
    {
        if ($imgPath && !preg_match('{^https?://}i', $imgPath)) {
            $imgPath = rtrim($_SERVER['DOCUMENT_ROOT'] . self::$_rootUrl, '/\\') . '/' . $imgPath;
        }
        try {
            $t = Lms_Thumbnail2::thumbnail($imgPath, $width, $height);
        } catch (Exception $e) {
            Lms_Debug::crit($e->getMessage());
            Lms_Debug::crit($e->getTraceAsString());
            $t = '/media/error.png';
        }
        return $t;
    }

    public static function loadAuthToken()
    {
        if (isset($_POST['auth_token'])) {
            return $_POST['auth_token'];
        }
        if (isset($_GET['auth_token'])) {
            return $_GET['auth_token'];
        }
        if (isset($_COOKIE['auth_token'])) {
            return $_COOKIE['auth_token'];
        }
        return null;
    }

    public static function saveAuthToken($token)
    {
        setcookie("auth_token", $token, time()+1209600, "/");
    }

    public static function setAuthData($login, $pass, $remember = true)
    {
//        session_start();
        Zend_Session::start();
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;
        if ($remember) {
            if (self::getConfig('auth','1.0','cookie','crypt')) {
                $crypter = new Lms_Crypt(
                    self::getConfig('auth','1.0','cookie','mode'),
                    self::getConfig('auth','1.0','cookie','algorithm'),
                    self::getConfig('auth','1.0','cookie','key')
                );
                setcookie("login", base64_encode($crypter->encrypt($login)), time()+1209600);
                setcookie("pass", base64_encode($crypter->encrypt($pass)), time()+1209600);
            } else {
                setcookie("login", $login, time()+1209600);
                setcookie("pass", $pass, time()+1209600);
            }
        }
    }

    public static function _detectNames($names)
    {
        $pureNames["international_name"] = "";
        $pureNames["name"] = "";
        foreach ($names as $name) {
            $eng = 0;
            $rus = 0;
            for ($i = 0; $i < strlen($name);$i++) {
                $num = ord($name{$i});
                if ($num >= 65 && $num <= 122) $eng++;
                if ($num >= 192 && $num <= 255) $rus++;
            }
            if ($rus > $eng) {
                if (strlen($pureNames["name"]) < strlen($name)) $pureNames["name"] = $name;
            } else if (strlen($pureNames["international_name"]) < strlen($name)) $pureNames["international_name"] = $name;
        }
        return $pureNames;
    }


    public static function formatMetainfo($metainfo)
    {
        static $compactCodecs = array(
            'Pulse Code Modulation (PCM)' => 'PCM',
            'MPEG Layer-2 or Layer-1' => 'MP2',
            'Dolby AC3' => 'AC3',
            'Dolby DTS' => 'DTS',
            'MPEG Layer-3' => 'AC3',
            'Advanced Audio Coding' => 'AAC',
            'XviD MPEG-4 (www.xvid.org)' => 'XviD',
            'Intel ITU H.264 Videoconferencing' => 'H.264',
        );

        if (!$metainfo || empty($metainfo['playtime_seconds'])) {
            return null;
        }
        $compactMetainfo = array();
        $s = (int) $metainfo['playtime_seconds'];
        $h = (int) floor($s/3600);
        $m = (int) floor(($s-$h*3600)/60);
        $s = (int) ($s - $h*3600 - $m*60);
        $compactMetainfo['playtime_seconds'] = (int) $metainfo['playtime_seconds'];
        $compactMetainfo['playtime'] = sprintf('%02d:%02d:%02d', $h, $m, $s);;
        $compactMetainfo['format'] = $metainfo['video']['streams'][0]['dataformat'];
        foreach ($metainfo['video']['streams'] as $stream) {
            $videoInfos = array();
            $videoInfos[] = "{$stream['resolution_x']}x{$stream['resolution_y']}";
            $videoInfos[] = floatval($stream['frame_rate']) . " fps";
            $codec = isset($compactCodecs[$stream['codec']])? $compactCodecs[$stream['codec']] : $stream['codec'];
            $videoInfos[] = $codec . ($stream['bitrate']>0? ', ' . round($stream['bitrate']/1000) . ' kbps' : '');
            if ($stream['bitrate']>0) {
                $videoInfos[] = round($stream['bitrate']/($stream['resolution_x']*$stream['resolution_y'])/$stream['frame_rate'], 3) . " bit/pixel";
            }
            $compactMetainfo['video']['label'] = "{$stream['resolution_x']}x{$stream['resolution_y']}";
            $compactMetainfo['video']['info'] = implode(", ", $videoInfos);
        }
        foreach ($metainfo['audio']['streams'] as $streamNum => $stream) {
            $audioInfos = array();
            $audioInfos[] = round($stream['sample_rate']/1000, 1) . " kHz";
            $codec = isset($compactCodecs[$stream['codec']])? $compactCodecs[$stream['codec']] : $stream['codec'];
            $audioInfos[] = $codec;
            $audioInfos[] = $stream['channels'] . "ch";
            if ($stream['bitrate']) {
                $audioInfos[] = "" . round($stream['bitrate']/1000) . " kbps";
            }
            if (isset($stream['lang'])) {
                $audioInfos[] = $stream['lang'];
            }
            if (isset($stream['name'])) {
                $audioInfos[] = $stream['name'];
            }

            $compactMetainfo['audio'][$streamNum]['label'] = $codec;
            if ($stream['bitrate']) {
                $compactMetainfo['audio'][$streamNum]['label'] .= " " . round($stream['bitrate']/1000) . " kbps";
            }
            if (isset($stream['lang'])) {
                $compactMetainfo['audio'][$streamNum]['label'] .= ", " . $stream['lang'];
            }
            $compactMetainfo['audio'][$streamNum]['info'] = implode(", ", $audioInfos);
        }
        return $compactMetainfo;
    }

    public static function getType($path, $isDir)
    {
        $videoExtensions = array('avi', 'mkv', 'mp4', 'mov', 'flv', 'vob');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($isDir) {
            return 'folder';
        } else if (in_array(strtolower($ext), $videoExtensions)) {
            return 'video';
        } else {
            return 'file';
        }
    }

    public static function normalizePath($path)
    {
        $path = rtrim($path, "\\/");
        return preg_replace('{\\\}', '/', $path);
    }

    public static function calcLevel($path)
    {
        $path = self::normalizePath($path);
        return count(preg_split('{/+}', $path));
    }

    public static function isParentDirectory($directory, $subpath)
    {
        $directory = self::normalizePath($directory);
        $subpath = self::normalizePath($subpath);

        if (self::isWindows()) {
            return (stripos($subpath, $directory)===0);
        } else {
            return  (strpos($subpath, $directory)===0);
        }
    }

    public static function getTargetStorage($threshold = 0.02)
    {
        if (!self::getConfig('storages')) {
            return false;
        }
        $maxFree = 0;
        $storages = array();
        foreach (self::getConfig('storages') as $path) {
            $free =  disk_free_space($path)/disk_total_space($path);
            $storages[$path] = $free;
            if ($free > $maxFree) {
                $maxFree = $free;
            }
        }

        foreach ($storages as $path => $free) {
            if ($free < ($maxFree * (1 - $threshold) )) {
                $storages[$path] = null;
            }
        }
        $storages = array_filter($storages);
        $targetStorage = array_rand($storages);
        return self::normalizePath($targetStorage);
    }

    public static function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    public static function prepareTextIndex($text, $type, $id, &$trigramValues, &$suggestionValues)
    {
        if (!trim($text)) {
            return;
        }
        static $stopWords, $db;
        if (!$stopWords) {
            $stopWords = Lms_Application::getConfig('indexing', 'stop_words');
        }
        if (!$db) {
            $db = Lms_Db::get('main');
        }

        $trigrams = array();
        $textLength = Lms_Text::length($text);
        if ($textLength>=3) {
            for ($i=0; $i<=$textLength-3; $i++) {
                $trigram = substr($text, $i, 3);
                $trigramValues[] = sprintf(
                    "(%s, %s, %d)",
                    $db->escape(strtolower($trigram)),
                    $db->escape($type),
                    $id
                );
            }
        }

        preg_match_all('{\w{2,}}', strtolower($text), $words, PREG_PATTERN_ORDER);
        $wordsFiltered = array();
        foreach (array_diff($words[0], $stopWords) as $word) {
            if (!preg_match('{^\d+$}', $word)) {
                $wordsFiltered[] = $word;
            }
        }
        array_unshift($wordsFiltered, strtolower($text));
        //print_r($wordsFiltered);
        foreach ($wordsFiltered as $word) {
            $suggestionValues[] = sprintf(
                "(%s, %s ,%d)",
                $db->escape(trim($word, ' .\'"')),
                $db->escape($type),
                $id
            );
        }
    }
    public static function runTask($task)
    {
        if (Lms_Application::isWindows()) {
            $script = APP_ROOT . '\\tasks\\' . $task;
            if (is_file(APP_ROOT . '\\tasks\\php-forced.bat')) {
                $php = APP_ROOT . '\\tasks\\php-forced.bat';
            } else {
                $php = APP_ROOT . '\\tasks\\php.bat';
            }
            $cmd = 'start ' . escapeshellarg($task) . ' ' . escapeshellarg($php) . ' ' . $script;
            //Lms_Debug::debug($cmd);
            pclose(popen($cmd, "r"));
        } else {
            $script = APP_ROOT . '/tasks/' . $task;
            $php = APP_ROOT . '/tasks/php';
            exec(escapeshellarg($php) . ' ' . escapeshellarg($script) . ' >/dev/null 2>&1 &');
        }
    }

    public static function tryRunTasks($fileRelatedOnly = false)
    {
        if (Lms_Application::getConfig('incoming', 'force_tasks')) {
            self::runTask(self::TASK_FILES_TASKS);
            self::runTask(self::TASK_FILES_METAINFO);
            self::runTask(self::TASK_FILES_FRAMES);
            if (!$fileRelatedOnly) {
                self::runTask(self::TASK_PERSONES_PARSING);
            }
        }
    }

    public static  function pathToLocalizedVideo($url)
    {
        $videoFolder = rtrim(dirname(APP_ROOT)) . '/media/trailers/video';
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $hash = md5($url);

        $path = $videoFolder . "/" . implode("/", str_split(substr($hash, 0, 2))) . "/" . "$hash.{$ext}";
        return $path;
    }

    public static function urlToLocalizedVideo($url)
    {
        $path = self::pathToLocalizedVideo($url);
        $localUrl = self::$_rootUrl . str_replace(dirname(APP_ROOT), '', $path);
        return $localUrl;
    }

    public static function isBynet()
    {
        //return false;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = $_SERVER['HTTP_USER_AGENT'];
            if (stripos($ua, "Googlebot")
                || preg_match('{Yandex}i', $ua)
                || preg_match('{facebook}i', $ua)
                || stripos($ua, "StackRambler")
                || stripos($ua, "Yahoo! Slurp")
                || stripos($ua, "bingbot")
                || stripos($ua, "Mail.RU_Bot")
            ) {
                return true;
            }
        }
        foreach (self::getConfig('allowed_nets') as $net) {
            if (Lms_Ip::ipInNet(Lms_Ip::getIp(), $net)) {
                return true;
            }
        }
        $country = function_exists("geoip_country_code_by_name")? @geoip_country_code_by_name(Lms_Ip::getIp()) : null;
        if (!$country) {
            return true;
        }
        return in_array($country, array("BY", "US", "KZ", "IL", "UZ", "LV", "CA", "KG", "EE", "LT", "MD", "BR", "DE"));

        /*foreach (self::getConfig('bynets') as $net) {
            if (Lms_Ip::ipInNet(Lms_Ip::getIp(), $net)) {
                return true;
            }
        }
        return false;*/
    }

    public static function getRegionIds($cache = true)
    {
        static $regions = null;
        if (!$cache || $regions===null) {
            $ip = Lms_Ip::getIp();
            $geo = function_exists("geoip_country_code_by_name")? @geoip_country_code_by_name($ip) : null;
            $ua = !empty($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : null;
            $regions = Lms_Item_Region::getMatchedRegionIds($geo, $ip, $ua);
        }
        return $regions;
    }

    public static function getCountryCode($cache = true)
    {
        static $code = null;
        if (!$cache || $code===null) {
            $ip = Lms_Ip::getIp();
            if (Lms_Ip::ipInNet($ip, '192.168.0.0/16') || Lms_Ip::ipInNet($ip, '10.0.0.0/8') || Lms_Ip::ipInNet($ip, '127.0.0.0/8')) {
                $code = 'BY';
            } else {
                $code = function_exists("geoip_country_code_by_name")? @geoip_country_code_by_name($ip) : null;
            }
        }
        return $code;
    }

    public static function dateOfWeek($dayNum)
    {
        $currentTvTime = strtotime("-5 hours");
        $currentDay = date('N', $currentTvTime);
        $diff = $currentDay - $dayNum;

        $date = new DateTime();
        $date->setTimestamp($currentTvTime - $diff*3600*24);
        return $date;
    }

    public static function dateIsToday($date)
    {
        $currentTvTime = strtotime("-5 hours");
        $currentDate = date('Y-m-d', $currentTvTime);
        return $currentDate == $date->format('Y-m-d');
    }

    public static function isToday($dayNum)
    {
        $currentTvTime = strtotime("-5 hours");
        $currentDay = date('N', $currentTvTime);
        return $currentDay == $dayNum;
    }

    public static function isSameDate(DateTime $date1, DateTime $date2)
    {
        return $date1->format('Y-m-d') == $date2->format('Y-m-d');
    }

    public static function getMainDomain()
    {
        return str_replace('api.', '', $_SERVER['HTTP_HOST']);
    }

    public static function getCategoryIdByLabel($category)
    {
         switch ($category) {
            case 'series':
                $categoryId = Lms_Item_Movie::CATEGORY_SERIES;
                break;
            case 'tvshows':
                $categoryId = Lms_Item_Movie::CATEGORY_TVSHOW;
                break;
            case 'animation':
                $categoryId = Lms_Item_Movie::CATEGORY_ANIMATION;
                break;
            case 'films':
            default:
                $categoryId = Lms_Item_Movie::CATEGORY_FILM;
                break;
        }
        return $categoryId;
    }

    public static function getCategoryLabelById($categoryId)
    {
        return Lms_Application::getConfig('categories', 'labels', $categoryId);
    }

    public static function timeToInterval($time)
    {
        $interval = preg_replace('{(\d+):(\d+):(\d+)}', 'PT$1H$2M$3S', $time);
        return new DateInterval($interval);
    }

    public static function timeToSeconds($time)
    {
        $seconds = 0;
        if (preg_match('{(\d+):(\d+):(\d+)}', $time, $matches)) {
            $seconds = $matches[1]*3600 + $matches[2]*60 + $matches[3];
        }
        return $seconds;
    }

    public static function setDefaultDevice($device)
    {
        self::$_defaultDevice = $device;
    }

    public static function getDevice()
    {
        if (self::$_device===null) {
            if (!empty($_GET['device'])) {
                self::$_device = Lms_Application::normalizeDevice($_GET['device'], self::$_defaultDevice);
            } else if (!empty($_POST['device'])) {
                self::$_device = Lms_Application::normalizeDevice($_POST['device'], self::$_defaultDevice);
            } else {
                self::$_device = self::$_defaultDevice;
            }
        }
        return self::$_device;
    }

    public static function normalizeDevice($value, $default = 'api')
    {
        $value = strtolower($value);
        static $validDevices = null;
        if ($validDevices === null) {
            $validDevices = Lms_Db::get("main")->selectCol("SELECT device FROM device");
        }
        if (!in_array($value, $validDevices)) {
            return $default;
        } else {
            return $value;
        }
    }

    public static function getUuid()
    {
        static $uuid = null;
        if ($uuid===null) {
            $uuid = !empty($_GET['uuid'])? $_GET['uuid'] : '';
        }
        return $uuid;
    }

    public static function isAppleStaff()
    {
        return (Lms_Application::getCountryCode()=='US' && Lms_Application::getDevice()=='ios');
    }

    public static function isUnetUser()
    {
        static $result = null;
        if ($result===null) {
            $result = false;
            $user = Lms_User::getUser();
            $unet = false;
            if ($user->getId()) {
                $unet = Lms_Item_Unet::selectByUserId($user->getId());
            } else {
                $ip = Lms_Ip::getIp();
                $unet = Lms_Item_Unet::getByIp($ip);
            }
            if ($unet && $unet->getStatus()) {
                $result = true;
            }
        }
        return $result;
    }

    public static function authUnetUser()
    {
        $ip = Lms_Ip::getIp();
        $unet = Lms_Item_Unet::getByIp($ip);
        if ($unet && $unet->getStatus()) {
            $user = Lms_Item::create('User', $unet->getId());
            Lms_User::setUser($user);
            $user->setAuthToken();
        }
    }

    public static function isDrmFree()
    {
        static $result = null;
        if ($result===null) {
            $result = false;
            $uuid = self::getUuid();
            if (Lms_Item_DrmFreeUuid::isUuidFree($uuid)) {
                $result = true;
            }
            if (Lms_Item_DrmFreeNet::isIpFree(Lms_Ip::getIp())) {
                $result = true;
            }
            if (Lms_Application::isAppleStaff()) {
                //TODO: temporary for AppStore
                $result = true;
            }
            if (self::isUnetUser()) {
                $result = true;
            }
        }
        return $result;
    }

    public static function getNearestLocation($cache = true)
    {
        static $location = null;
        if (!$cache || $location===null) {
            $ip = Lms_Ip::getIp();
            $location = 'foreign';
            $countryCode = self::getCountryCode();
            switch ($countryCode) {
                case 'BY':
                    foreach (self::getConfig('bynets') as $net) {
                        if (Lms_Ip::ipInNet($ip, $net)) {
                            $location = 'by';
                            break;
                        }
                    }
                    break;
            }
            foreach (self::getConfig('topology') as $loc => $config) {
                foreach ($config['nets'] as $net) {
                    if (Lms_Ip::ipInNet($ip, $net)) {
                        $location = $loc;
                        if ($location == 'btc') {
                            $location .= rand(1, 3);
                        }
                        break 2;
                    }
                }
            }
        }
        return $location;
    }

    public static function ucfirst($text)
    {
        return Lms_Text::uppercase(Lms_Text::substring($text, 0, 1)) . Lms_Text::substring($text, 1);
    }

    public static function isPersikTv()
    {
        return self::getConfig('is_persik_tv');
    }


    public static function getCurrency($default = null)
    {
        if ($default === null) {
            $default = self::getConfig('currencies', 'default');
        }
        if (self::$_currency===null) {
            $currency = null;

            if (!empty($_COOKIE['currency'])) {
                $currency = $_COOKIE['currency'];
            }
            if (!empty($_GET['currency'])) {
                $currency = $_GET['currency'];
            }
            self::$_currency = self::normalizeCurrency($currency, $default);
        }
        return self::$_currency;
    }

    public static function normalizeCurrency($value, $default = 'byn')
    {
        $value = strtolower($value);
        $valid = array('byn','rub','eur');
        if (!in_array($value, $valid)) {
            return $default;
        } else {
            return $value;
        }
    }

    public static function getCurrencyRate()
    {
        $rate = 1;
        $currency = Lms_Application::getCurrency();
        if ($currency!='byn') {
            $db = Lms_Db::get('main');
            $rate = $db->selectCell('SELECT ?# FROM rates ORDER BY `date` DESC LIMIT 1', $currency);
        }
        return array(
            'currency' => $currency,
            'rate' => $rate
        );
    }

    public static function getRates()
    {
        $db = Lms_Db::get('main');
        return $db->select('SELECT `date` AS ARRAY_KEY, `usd`, `eur`, `rub` FROM rates ORDER BY `date` DESC');
    }

    public static function isPaySysSupported($paySys)
    {
        return self::getConfig('pay_sys_support', $paySys, self::getCurrency());
    }

    public static function isVodOnly()
    {
        return self::getConfig('vod_only');
    }

    public static function getBookmarks()
    {
        if (!Lms_User::getUser()->getId()) {
            return;
        }

        $device = Lms_Application::getDevice();

        $bookmarks = array();
        $sortarray = array();
        if (!Lms_Application::isVodOnly()) {
            foreach (Lms_Item_BookmarkChannel::selectMy(Lms_Application::getRegionIds(), Lms_Application::getDevice()) as $bookmarkChannel) {
                $channel = $bookmarkChannel->getChannel();
                if ($device=='web' || $device=='mobileweb' || $channel->getListed()) {
                    $sortarray[] = $bookmarkChannel->getAddedAt();
                    $bookmarks[] = $channel;
                }
            }

            foreach (Lms_Item_BookmarkTvshow::selectMy(Lms_Application::getRegionIds(), Lms_Application::getDevice()) as $bookmarkTvshow) {
                $sortarray[] = $bookmarkTvshow->getAddedAt();
                $bookmarks[] = $bookmarkTvshow->getTvshow();
            }
        }
        foreach (Lms_Item_BookmarkMovie::selectMy(Lms_Application::getRegionIds(), Lms_Application::getDevice()) as $bookmarkMovie) {
            $sortarray[] = $bookmarkMovie->getAddedAt();
            $bookmarks[] = $bookmarkMovie->getMovie();
        }

        array_multisort($sortarray, SORT_DESC, $bookmarks);

        return $bookmarks;
    }


    public static function getMailFrom()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return self::getConfig('mail', 'from_email_by');
        }

        if (preg_match('{persik\.by}', $_SERVER['HTTP_HOST'])
            && !preg_match('{api\.}', $_SERVER['HTTP_HOST'])
        ) {
            return self::getConfig('mail', 'from_email_by');
        } else {
            return self::getConfig('mail', 'from_email');
        }
    }

    public static function getFrontController()
    {
        return self::$_frontController;
    }

    /**
     * @param $user Lms_Item_User
     * @param $device
     * @param $format
     * @param $type
     * @param $encoding
     * @param $bitrate
     * @param null $regions
     * @param bool $includeUnlisted
     */
    public static function printPlaylist($user, $device, $format, $type, $encoding, $bitrate, $regions = null, $includeUnlisted = false)
    {
        if (!$regions) {
            $regions = self::getRegionIds();
        }
        $channelsIds = $user->availableChannels($device);

        Lms_Item_Preloader::load('Channel', array(), 'channel_id', $channelsIds);

        $charset = $encoding?: 'windows-1251';

        $a = array();
        $allChannels = Lms_Item_Channel::select($total, null, 'name', 'asc', $regions, $device, $includeUnlisted);
        $allChannels = array_merge($allChannels, Lms_Item_Channel::selectBookmarks(Lms_Application::getRegionIds(), $device));
        foreach ($allChannels as $channel) {
            if (($channel->getFree() || in_array($channel->getId(), $channelsIds) || self::isDrmFree()) && $channel->getWeb() && $channel->getStreamUrl()) {
                $priority = $channel->getPlaylistPriority();
                $a[$priority][$channel->getId()] = $channel;
            }
        }

        ksort($a);
        $channels = [];
        foreach ($a as $items) {
            $channels = array_merge($channels, array_values($items));
        }

        switch ($format) {
            case 'xspf':
                header("Content-type: application/xspf+xml; charset=$charset");
                $string = <<<XML
<?xml version="1.0" encoding="$charset"?>
<playlist xmlns="http://xspf.org/ns/0/" xmlns:vlc="http://www.videolan.org/vlc/playlist/ns/0/" version="1">
	<title>persik.by</title>
	<trackList>
	</trackList>
</playlist>
XML;
                $xml = simplexml_load_string($string);

                $n = 1;
                foreach ($channels as $channel) {
                    $name = $channel->getNameEpg()?: $channel->getName();
                    if (!$encoding) {
                        $name = Lms_Text::translit($name);
                    } else {
                        if ($encoding!='UTF-8') {
                            $name = Lms_Translate::translate('UTF-8', 'CP1251', $name);
                        }
                    }
                    $track = $xml->trackList->addChild('track');
                    if ($type == 'proxy') {
                        $track->location = Lms_Streaming::getChannelStbProxyUrl($channel, $user, $bitrate);
                    } else {
                        $track->location = Lms_Streaming::getChannelStbUrl($channel, $user, $bitrate);
                    }
                    $track->addChild('title', $name);
                    $track->addChild('image', "http://persik.by/images/channels/logos/" . $channel->getId() . ".png");
                    $track->addChild('psfile', $name);
                    $extension = $track->addChild('extension');
                    $extension->addAttribute("application", "http://www.videolan.org/vlc/playlist/0");
                    $extension->addChild('vlc:id', $n++, "http://www.videolan.org/vlc/playlist/0");
                    $extension->addChild('shift', "-60");
                }
                $doc = new DOMDocument('1.0');
                $doc->preserveWhiteSpace = false;
                $doc->loadXML($xml->asXML());
                $doc->formatOutput = true;
                echo $doc->saveXML();
                break;

            case 'm3u':
            default:
                header("Content-type: audio/x-mpegurl; charset=$charset");
                echo "#EXTM3U";
                foreach ($channels as $channel) {
                    $name = $channel->getNameEpg()?: $channel->getName();
                    if (!$encoding) {
                        $name = Lms_Text::translit($name);
                    } else {
                        if ($encoding!='UTF-8') {
                            $name = Lms_Translate::translate('UTF-8', 'CP1251', $name);
                        }
                    }

                    echo "\n#EXTINF:0,$name";
                    if ($type == 'proxy') {
                        echo "\n" . Lms_Streaming::getChannelStbProxyUrl($channel, $user, $bitrate);
                    } else {
                        echo "\n" . Lms_Streaming::getChannelStbUrl($channel, $user, $bitrate);
                    }
                }
                echo "\n";
                break;
        }
    }

    /**
     * @param string $mmyy Дата в формате ММГГ, ММ/ГГ, МГГ или М/ГГ
     * @return null|string
     */
    public static function getCardDate($mmyy)
    {
        $mmyyOnlyDigits = preg_replace('{[^\d]}', '', $mmyy);
        if (strlen($mmyyOnlyDigits)==3) {
            $mmyyOnlyDigits = '0' . $mmyyOnlyDigits;
        }

        if (preg_match('{^(\d{2})(\d{2})$}', $mmyyOnlyDigits, $matches)) {
            $m = (int) $matches[1];
            $y = (int) $matches[2];
            if ($y > 30 || $m > 12 || $m < 1) {
                return null;
            }
            $cardDate = sprintf("%04d-%02d-01", 2000 + $y, $m);
            return $cardDate;
        } else {
            return null;
        }
    }

}
