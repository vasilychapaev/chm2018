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
class Lms_View_Helper_PriceArray extends Zend_View_Helper_Abstract
{
    function priceArray($bynValue, $stretch = 1, $currency = null, $date = null)
    {
        if ($currency===null) {
            $currency = Lms_Application::getCurrency();
        }
        return Lms_Application::getPrice()->formatArray($bynValue, $currency, $stretch, $date);
    }
    
}
