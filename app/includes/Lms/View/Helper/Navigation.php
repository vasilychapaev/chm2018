<?php
/**
 * @copyright 2006-2011 LanMediaService, Ltd.
 * @license    http://www.lanmediaservice.com/license/1_0.txt
 * @author Ilya Spesivtsev <macondos@gmail.com>
 * @version $Id: TextBlock.php 700 2011-06-10 08:40:53Z macondos $
 */

class Lms_View_Helper_Navigation extends Zend_View_Helper_Abstract
{
    function navigation($items)
    {
        $liItems = array();
        foreach ($items as $item) {
            $subItems = '';
            if (!empty($item['subitems'])) {
                $subLiItems = array();
                foreach ($item['subitems'] as $subItem) {
                    $subLiItems[] = '<li><a href="' . $subItem['url'] . '" class="pajax">' . $subItem['title'] . '</a></li>';
                }
                $subLiItemsHtml = join("\n", $subLiItems);
                $subItems = <<<TEXT
<div class="submenu-holder">
    <div class="submenu">
        <a class="prev">prev</a>
        <a class="next">next</a>
        <div class="frame">
            <ul>
                $subLiItemsHtml
            </ul>
        </div>
    </div>
</div>
TEXT;
            }
            $activeHtml = !empty($item['active'])? ' class="active" ' : '';
            $liItems[] = '<li ' . $activeHtml . '><a href="' . $item['url'] . '" class="pajax">' . $item['title'] . ' <i class="arrow">&nbsp;</i></a>' . $subItems . '</li>';
            
        }
        $liItemsHtml = join("\n", $liItems);
        $out = <<<TEXT
<nav class='nav-holder'>
    <ul id='nav'>
        $liItemsHtml        
    </ul>
</nav>
TEXT;

        return $out;
    }
    
}


