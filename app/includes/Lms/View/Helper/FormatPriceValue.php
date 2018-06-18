<?php
/**
 * View-хелпер для форматирования цен
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
class Lms_View_Helper_FormatPriceValue extends Zend_View_Helper_Abstract
{
    function formatPriceValue($value, $currency = 'byn', $stretch = 1)
    {
        list($value, $symbol, $direction) = Lms_Application::getPrice()->formatValue($value, $currency, $stretch);
        if ($direction==1) {
            return $value . ' ' . $symbol;
        } else if ($direction==2) {
            return $symbol . $value;
        }
    }
}
