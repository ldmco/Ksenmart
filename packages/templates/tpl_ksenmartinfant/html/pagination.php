<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
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
    $html = "<div id='pagination'><ul class='pagination'>";

    $html .= $list['start']['data'];
    $html .= $list['previous']['data'];

    foreach ($list['pages'] as $page) {
        //if ($page['data']['active']) {
            
        //}

        $html .= $page['data'];

        //if ($page['data']['active']) {
            
        //}
    }

    $html .= $list['next']['data'];
    $html .= $list['end']['data'];

    $html .= "</ul></div>";
    return $html;
}

function pagination_item_active(&$item) {
    return '<li><a class="button" href="'.$item->link.'" title="'.$item->text.'">'.$item->text.'</a></li>';
}
 
function pagination_item_inactive(&$item) {
	if(is_int($item->text)){
	   $html = '<li class="active"><span class="button">'.$item->text.'</span></li>';
	}else{
	   $html = '<li class="disabled"><span class="button">'.$item->text.'</span></li>';
	}
    return $html;
}