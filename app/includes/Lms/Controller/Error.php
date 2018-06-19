<?php 

class Lms_Controller_Error extends Zend_Controller_Action
{
    public function errorAction()
    {
//        Lms_Debug::info('error action 404');
//        die('error action 404');
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
//                die('exception no ctrl/action');
                // ошибка 404 - не найден контроллер или действие
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');

                // ... получение данных для отображения...
                $this->view->title = "404 Not Found";
                $this->view->controller = 'error';
                break;
            default:
//                die('exception default');
                Lms_Debug::crit($errors->exception->getMessage());
                Lms_Debug::crit($errors->exception->getTraceAsString());
                // ошибка приложения; выводим страницу ошибки,
                // но не меняем код статуса
                break;
        }
    }
}