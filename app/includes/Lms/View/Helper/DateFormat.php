<?php
/**
 * View-хелпер для форматирования даты
 * 
 *
 * @copyright 2006-2010 LanMediaService, Ltd.
 * @license    http://www.lms.by/license/1_0.txt
 * @author Ilya Spesivtsev
 * @version $Id: Image.php 291 2009-12-28 12:55:20Z macondos $
 * @category   Lms
 * @package    Zend_View
 * @subpackage Helper
 */
 
/**
 * @category   Lms
 * @package    Zend_View
 * @subpackage Helper
 */
class Lms_View_Helper_DateFormat extends Zend_View_Helper_Abstract
{
    function dateFormat($str, $format = 'd.m.Y')
    {
        return date($format, strtotime($str));
    }
    
}
