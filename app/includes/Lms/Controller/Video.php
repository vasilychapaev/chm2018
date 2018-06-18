<?php

class Lms_Controller_Video extends Zend_Controller_Action
{
    function init()
    {
//        die('video.ctrl - init');
    }

    public function indexAction() {
//        echo 'video.ctrl - index.action';
        die('video.ctrl - index.action');
    }

    public function archiveAction()
    {
        echo 'video.ctrl - archive.action';
        $this->view->title = "Архив матчей Чемпионата мира по футболу 2018";
    }


    public function futureAction()
    {
        die('video.ctrl - future.action');

        $this->view->title = "Будущие записи Чемпионата мира по футболу 2018";
    }



}