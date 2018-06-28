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
//        $items = Lms_Football::getTvshowsTpl([]);
        $this->view->items = $items;
    }


    public function archiveAction()
    {
        $this->view->title = "Архив Чемпионат мира по футболу 2018";


        $items = Lms_Football::getTvshowsArchive();
        $this->view->items = $items;
    }



    public function searchAction()
    {
        $this->view->title = "Поиск по Чемпионату мира по футболу 2018";
        $this->view->q = $q = $this->_getParam('q');
        if ($q)
        {
            $items = Lms_Football::getTvshowSearch($q);
            $this->view->items = $items;
        }
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

        if ($this->view->item = Lms_Football::getTvshowInfo($tvshowId)) {
            $this->view->title = $this->view->item['name'];

            // есть что смотреть - но юзер не авторизован
            $this->view->user = Lms_User::getUser();
            if (!$this->view->user->getId())
                if ($this->view->item['is_now'] or $this->view->item['is_archive'])
                    {
                      setcookie('need_auth_backurl', $this->getRequest()->getRequestUri(), strtotime('+7 days'), '/');
                      $this->_redirect('/login/');
                    }
// echo '<pre>';print_r($this->view->item);

            // todo - remove (tmp.auth)
            // Lms_User::setUser(331352);
            // var_dump(Lms_User::getUser()->getId());


//            $this->view->stream_url = 'http://by.persik.by:82/live/Ch81/playlist.m3u8?securehash=ThU_HVuuInhaaitiKsNyT7bndj0dfYLI3ZI2dW1evnQ%3D&secureendtime=1529417967&securestarttime=1529331567&secureuserid=331352&securestreams=5&UserID=331352&device_code=web&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%22932%22%7D';
//            $this->view->stream_url = 'http://ertk0.persik.tv:82/live/Ch68/playlist.m3u8?securehash=gv7Qz8wcNSeiWD_f-wAWrYu75zwh3faH_QH07Ef14II%3D&secureendtime=1529418173&securestarttime=1529331773&secureuserid=331352&securestreams=5&UserID=331352&device_code=web&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%2214%22%7D';
//            $this->view->stream_url = 'http://foreign-free.persik.tv:82/live/Ch64/playlist.m3u8?securehash=Cy2w6LJiu1KceWK_Ub_QsAUjvKw2vMcTxgJ3UxTNsGc%3D&secureendtime=1529453879&securestarttime=1529367479&secureuserid=331352&securestreams=5&UserID=331352&device_code=api&r=%7B%22type%22%3A%22channel%22%2C%22id%22%3A%2210298%22%7D"';
            $this->view->stream_url = Lms_Football::getStreamUrl($this->view->item);
            // echo $this->view->stream_url;



            // todo parser "match-info"
            // todo cache

        }

    }

    public function tvshowAction()
    {
        $tvshowId = $this->_getParam('tvshow_id');

        if ($this->view->item = Lms_Football::getTvshowInfo($tvshowId)) {
            $this->view->title = $this->view->item['name'];

            // есть что смотреть - но юзер не авторизован
            $this->view->user = Lms_User::getUser();
            if (!$this->view->user->getId())
                if ($this->view->item['is_now'] or $this->view->item['is_archive']) {
                    setcookie('need_auth_backurl', $this->getRequest()->getRequestUri(), strtotime('+7 days'), '/');
                    $this->_redirect('/login/');
                }

            $this->view->stream_url = Lms_Football::getStreamUrl($this->view->item);


        }
    }


    public function setkaAction()
    {
        $this->view->title = "Турниная сетка Чемпионата мира по футболу 2018";

        // todo cache
//        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/wittiws/phpquery/phpQuery/phpQuery.php';
        $url = 'http://terrikon.com/worldcup-2018';
        $html = file_get_contents($url);
        $document = phpQuery::newDocumentHTML($html);


        $groups = [];

        $cnt = 0;
        $headers = $document->find('.maincol h2');
        foreach ($headers as $el)
        {
            $pq = pq($el);
            $pq->find('a')->remove();
            $h2 = trim($pq->text());
            $h2 = str_replace('ЧМ 2018. ', '', $h2);
            if ($h2) {
                $groups[$cnt]['h2'] = $h2;
                $cnt++;
            }
        }

        $cnt = 0;
        $tables = $document->find('.team-info table.grouptable');
        foreach ($tables as $el)
        {
            $pq = pq($el);
            $pq->addClass('table table-striped table-bordered');
            $pq->find('a')->attr('href', '#');
            $pq->find('tr th:first-child')->remove();
            $pq->find('tr th:nth-child(7)')->remove();
            $pq->find('tr th:nth-child(7)')->remove();
            $pq->find('tr td:first-child')->remove();
            $pq->find('tr td:nth-child(7)')->remove();
            $pq->find('tr td:nth-child(7)')->remove();
            $groups[$cnt]['grouptable'] = $pq->htmlOuter();
            $cnt++;
        }

        $cnt = 0;
        $results = $document->find('.team-info table.gameresult');
        foreach ($results as $el)
        {
            $pq = pq($el);
            $pq->addClass('table table-striped table-bordered');
            $pq->find('a')->attr('href', '#');
            $groups[$cnt]['resulttable'] = $pq->htmlOuter();
            $cnt++;
        }

        $this->view->items = $groups;

    }

    public function raspisanieAction()
    {
        $this->view->title = "Расписание матчей Чемпионата мира по футболу 2018";

        $items = Lms_Football::getTvshowsAll();
        $this->view->items = $items;
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
                    $user->setAuthToken();

                    $sourceHost = $user->getSourceHost();
                    $sourceHost.= ($sourceHost?',':'') . 'russia2018';
                    $user->setSourceHost($sourceHost)->save();

                    $redirectUrl = $_COOKIE['need_auth_backurl']?:'/';
                    setcookie('need_auth_backurl', '', -1);
                    $this->_redirect($redirectUrl);
                }
                else {
                    $this->view->subject = '%F0%9F%8D%91 Подтверждение регистрации на russia2018.persik.tv';

                    if ($user = Lms_Item_User::Register($email, 'fifa2018', false))
                    {
                      $db = Lms_Db::get('main');
                      $db->query("update users set source_host = 'russia2018' where user_id = ?d", $user->getId());

                      $this->_redirect('/registrationsuccess/');

                    }
                }

            }
        }
    }

    /*
     * заглушка "регистрация завершена"
     * на отдельной странице - для метрики
     */
    public function registrationsuccessAction()
    {
      
    }


    /*
     * debug - переотправить почту
     */
    public function mailerAction()
    {
      $transport = Lms_Application::getConfig('mail', 'transport', 'do_not_reply@persik.by');
      // var_dump($transport);

      $db = Lms_Db::get('main');

      $rows = $db->select('SELECT * FROM mails WHERE tries<10 ORDER BY tries LIMIT 20');
      // echo '<pre>';print_r($rows);exit;

      foreach ($rows as $row) {
          $failed = false;
          try {
              if (trim($row['to_email'])) {
                  Lms_Debug::debug(sprintf('Sending email #%d to %s about "%s"', (int)$row['mail_id'], $row['to_email'], $row['subject']));
                  $mail = new Zend_Mail('UTF-8');
                  if ($row['body_text']) {
                      $mail->setBodyText($row['body_text']);
                  }
                  if ($row['body_html']) {
                      $mail->setBodyHtml($row['body_html']);
                  }
                  $mail->setSubject(rawurldecode($row['subject']))
                       ->setFrom($row['from_email'], $row['from_name'])
                       ->addTo($row['to_email'], $row['to_name'])
                       ->send(Lms_Application::getConfig('mail', 'transport', $row['from_email']));
                  Lms_Debug::debug(sprintf("Sending email #%d done", (int)$row['mail_id']));
                  echo sprintf("Sending email #%d done", (int)$row['mail_id']);
              }
          } catch (Exception $e) {
              $failed = true;
              Lms_Debug::debug($e->getMessage());
              var_dump($e->getMessage());exit;
          }

          if ($failed) {
              $db->query('UPDATE ?_mails SET `tries`=`tries`+1 WHERE mail_id=?d', $row['mail_id']);
          } else {

              $db->query('DELETE FROM ?_mails WHERE mail_id=?d', $row['mail_id']);
          }
      }
    }

    public function devAction()
    {
        echo '<pre>';

        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

        $url = 'http://terrikon.com/worldcup-2018';
        $html = file_get_contents($url);

        $saw = new nokogiri($html);
//        var_dump(
//            $saw->get('.maincol h2:first-child')
//            $saw->get('.maincol h2:first-child')->toText()
//            $saw->get('.maincol h2:first-child')->toTextArray()
//            $saw->get('.maincol h2:first-child')->toXml()
//        );

//        foreach ( $saw->get('.maincol h2')->toArray() as $h2)
//            print_r($h2['#text']);
//        foreach ( $saw->get('.maincol h2 a') as $h2) // плейофф
//            print_r($h2['#text'][0]);

//        foreach ( $saw->get('table.grouptable')-> as $tbl)
//            print_r($tbl);

        $groups = [];

        $document = phpQuery::newDocumentHTML($html);

        $cnt = 0;
        $headers = $document->find('.maincol h2');
        foreach ($headers as $el)
        {
            $pq = pq($el);
            $pq->find('a')->remove();
            $h2 = trim($pq->text());
            if ($h2) {
                $groups[$cnt]['h2'] = $h2;
                $cnt++;
            }
        }

        $cnt = 0;
        $tables = $document->find('.team-info table.grouptable');
        foreach ($tables as $el)
        {
            $groups[$cnt]['grouptable'] = pq($el)->htmlOuter();
            $cnt++;
        }

        $cnt = 0;
        $results = $document->find('.team-info table.gameresult');
        foreach ($results as $el)
        {
            $groups[$cnt]['resulttable'] = pq($el)->htmlOuter();
            $cnt++;
        }

        print_r($groups);

        echo '</pre>';
    }





}
