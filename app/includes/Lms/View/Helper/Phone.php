<?php
/**
 * View-хелпер для форматирования номера телефона
 * 
 */
 
class Lms_View_Helper_Phone extends Zend_View_Helper_Abstract
{
    function phone($number)
    {
        $formattedNumber = preg_replace('{\D}', '', (string) $number);
        $formattedNumber = preg_replace('/^([\d\*]+)([\d\*]{3})([\d\*]{2})([\d\*]{2})$/', '$1 $2-$3-$4', $formattedNumber);
        $formattedNumber = preg_replace('/^375([\d\*]{2}) (.*?)$/', '+375 ($1) $2', $formattedNumber);

        return $formattedNumber;
    }
    
}
