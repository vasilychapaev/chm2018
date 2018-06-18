<?php

class Lms_Controller_Index extends Zend_Controller_Action
{
    function init()
    {
    }
    
    public function indexAction()
    {
        $this->view->title = "Чемпионат мира по футболу 2018";

        $items = Lms_Football::getTvshowsToday();
        $this->view->today = $items;
//        echo '<pre>';print_r($items);exit;

        $items = Lms_Football::getTvshowsFuture();
        $items = array_slice($items, 0, 6);
        $this->view->itemsFuture = $items;

        $items = Lms_Football::getTvshowsArchive();
        $items = array_slice($items, 0, 6);
        $this->view->itemsArchive = $items;

    }


    public function onlineAction()
    {
        $this->view->title = "Прямая трансляция Чемпионата мира по футболу 2018";
        $items = Lms_Football::getTvshowsToday();
        $this->view->today = $items;
    }


    public function todayAction()
    {
        die('222');
        $this->view->title = "Сегодня трансляция Чемпионата мира по футболу 2018";
//        $item = Lms_Football::getTvshowsOnline();
        $items = Lms_Football::getTvshowsToday();
        $this->view->today = $items;
    }




    public function futureAction()
    {
//        die('index.ctrl - future.action');
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
        $this->view->title = "Архив Чемпионат мира по футболу 2018";
        $cmd1 = $this->_getParam('cmd1');
        $cmd2 = $this->_getParam('cmd2');
        $tvshowId = $this->_getParam('tvshow_id');

        if ($item = Lms_Football::countryGetCode($cmd1))
            $this->view->cmd1 = $item;
        if ($item = Lms_Football::countryGetCode($cmd2))
            $this->view->cmd2 = $item;

        if ($item = Lms_Football::getTvshowInfo($tvshowId))
            $this->view->tvshow = Lms_Football::titleParse($item);

        // todo getStream or getVod
//        $tvshow = Lms_Item::create('Tvshow', $tvshowId);
//        $channel = $tvshow->getChannel();
//        $this->view->stream_url = Lms_Streaming::getDvrStreamUrl($channel, $tvshow, null, Lms_Application::getDevice());

        $channel = Lms_Item::create('Channel', 10254);
        $this->view->stream_url = Lms_Streaming::getChannelStreamUrl($channel, null, Lms_Application::getDevice());
        var_dump($this->view->stream_url);

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
    }



}