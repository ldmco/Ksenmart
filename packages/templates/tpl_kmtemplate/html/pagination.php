<?php /** * @copyright   Copyright (C) 2013. All rights reserved. * @license     GNU General Public License version 2 or later; see LICENSE.txt */ defined('_JEXEC') or die;
function pagination_list_footer($list) {
    // Initialize variables
    $lang = &JFactory::getLanguage();
    $html = "<div class=\"list-footer\">\n";

    $html .= "\n<div class=\"limit\">" . JText::_('Display Num') . $list['limitfield'] .
        "</div>";
    $html .= $list['pageslinks'];
    $html .= "\n<div class=\"counter\">" . $list['pagescounter'] . "</div>";

    $html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"" . $list['limitstart'] .
        "\" />";
    $html .= "\n</div>";

    return $html;
}

function pagination_list_render($list) {
    // Initialize variables
    $lang = &JFactory::getLanguage();
    $html = "<ul>";

    $html .= $list['start']['data'];
    $html .= $list['previous']['data'];

    foreach ($list['pages'] as $page) {
        if ($page['data']['active']) {
            
        }

        $html .= $page['data'];

        if ($page['data']['active']) {
            
        }
    }

    $html .= $list['next']['data'];
    $html .= $list['end']['data'];

    $html .= "</ul>";
    return $html;
}

function pagination_item_active(&$item) {
    return '<li><a href="'.$item->link.'" title="'.$item->text.'">'.$item->text.'</a></li>';
}
 
function pagination_item_inactive(&$item) {
	if(is_int($item->text)){
	   $html = '<li class="active"><a href="'.$item->link.'" title="'.$item->text.'">'.$item->text.'</a></li>';
	}else{
	   $html = '<li class="disabled"><a href="'.$item->link.'" title="'.$item->text.'">'.$item->text.'</a></li>';
	}
    return $html;
}