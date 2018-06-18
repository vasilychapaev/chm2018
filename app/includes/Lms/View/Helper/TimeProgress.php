<?php
/**
 * View-хелпер
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
class Lms_View_Helper_TimeProgress extends Zend_View_Helper_Abstract
{
    function timeProgress($startDate, $stopDate)
    {
        $result = sprintf(
            "<div class=\"time-progress\" data-start=\"%s\" data-stop=\"%s\"></div>", 
            strtotime($startDate) . '000', 
            strtotime($stopDate) . '000'
        );
        return $result;
    }
    
}
