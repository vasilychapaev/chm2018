<?php

class Lms_Controller_Index extends Zend_Controller_Action
{
    function init()
    {
    }

    public function indexAction()
    {
        $this->view->title = "Чемпионат мира по футболу 2018";

//        $items = Lms_Football::getTvshowsToday();
        $items = Lms_Football::getTvshowsNow();
        $this->view->itemsNow = $items;
//        echo '<pre>';print_r($items);exit;

        $items = Lms_Football::getTvshowsFuture();
        $items = array_slice($items, 0, 6);
        $this->view->itemsFuture = $items;

        $items = Lms_Football::getTvshowsArchive();
        $items = array_slice($items, 0, 6);
        $this->view->itemsArchive = $items;

    }


    public function futureAction()
    {
        $this->view->title = "Будущие матчи Чемпионата мира по футболу 2018";

        $items = Lms_Football::getTvshowsFuture();
        $this->view->items = $items;
    }


    public function archiveAction()
    {
        $this->view->title = "Архив Чемпионат мира по футболу 2018";


        $items = Lms_Football::getTvshowsArchive();
        $this->view->items = $items;
    }


    public function matchAction()
    {
        $cmd1 = $this->_getParam('cmd1');
        $cmd2 = $this->_getParam('cmd2');
        $tvshowId = $this->_getParam('tvshow_id');

        if ($item = Lms_Football::countryGetCode($cmd1))
            $this->view->cmd1 = $item;
        if ($item = Lms_Football::countryGetCode($cmd2))
            $this->view->cmd2 = $item;

        if ($this->view->item = Lms_Football::getMatchInfo($tvshowId)) {
            $this->view->title = $this->view->item['name'];

            // есть что смотреть - но юзер не авторизован
            $this->view->user = Lms_User::getUser();
            if (!$this->view->user->getId())
                if ($this->view->item['is_now'] or $this->view->item['is_archive'])
                    $this->_redirect('/login/');


            // todo - remove (tmp.auth)
            Lms_User::setUser(331352);


//            $this->view->stream_url = 'http://by.persik.by:82/live/Ch81/playlist.m3u8?securehash=ThU_HVuuInhaaitiKsNyT7bndj0dfYLI3ZI2dW1evnQ%3D&secureendtime=1529417967&securestarttime=1529331567&secureuserid=331352&securestreams=5&UserID=331352&device_code=web&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%22932%22%7D';
//            $this->view->stream_url = 'http://ertk0.persik.tv:82/live/Ch68/playlist.m3u8?securehash=gv7Qz8wcNSeiWD_f-wAWrYu75zwh3faH_QH07Ef14II%3D&secureendtime=1529418173&securestarttime=1529331773&secureuserid=331352&securestreams=5&UserID=331352&device_code=web&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%2214%22%7D';
//            $this->view->stream_url = 'http://foreign-free.persik.tv:82/live/Ch64/playlist.m3u8?securehash=Cy2w6LJiu1KceWK_Ub_QsAUjvKw2vMcTxgJ3UxTNsGc%3D&secureendtime=1529453879&securestarttime=1529367479&secureuserid=331352&securestreams=5&UserID=331352&device_code=api&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%2210298%22%7D"';
//            var_dump($this->view->stream_url);
            $this->view->stream_url = Lms_Football::getStreamUrl($this->view->item);


            // todo parser "match-info"
            // todo cache

        }

    }


    public function setkaAction()
    {
        $this->view->title = "Турниная сетка Чемпионата мира по футболу 2018";
    }

    public function raspisanieAction()
    {
        $this->view->title = "Расписание матчей Чемпионата мира по футболу 2018";
    }


    public function loginAction()
    {
        $this->view->title = "Вход на сайт";

        if ($this->getRequest()->isPost())
        {
            $email = $this->getRequest()->getPost('email');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $this->view->error = 'Нужен правильный email!';
            else {

                // if user->getByEmail($email) = user.authorize = redirect.back
                // else user.register - send.email - show.thanks

                $user = Lms_Item_User::getByEmail($email);
                if ($user) {
                    Lms_User::setUser($user);
                    var_dump($user->getId());
                    $user->setAuthToken();
                    die('logged');
                }
                else {
                    $this->_helper->viewRenderer->setRender('login-complete');
                    $user = Lms_Item_User::Register($email, 'fifa2018', false);
                }

            }
        }

    }


}