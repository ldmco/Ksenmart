<?php defined('_JEXEC') or die;

/**
 * Class Module Helper
 * @author TakT
 */
class MODKSMNewsHelper {

    public static function getEvents($params) {
        $events = file_get_contents('http://joomlalap.ru/index.php?option=com_joomla_lap&task=jlapapi.getEvents&tmpl=jlap&limit='.$params->get('limit', 5));
        return json_decode($events);
    }
}
