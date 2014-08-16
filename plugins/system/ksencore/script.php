<?php defined('_JEXEC') or die;

class plgSystemKsencoreInstallerScript {

    function postflight($type, $parent) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__extensions')->set('enabled=1')->where('type=' . $db->q('plugin'))->where('element=' . $db->q('ksencore'));
        $db->setQuery($query)->execute();

    }
}