<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class MODKSMNewsHelper {

    public static function getEvents($params) {
        $events = file_get_contents('http://joomlalap.ru/index.php?option=com_joomla_lap&task=jlapapi.getEvents&tmpl=jlap&limit='.$params->get('limit', 5));
        return json_decode($events);
    }
}
