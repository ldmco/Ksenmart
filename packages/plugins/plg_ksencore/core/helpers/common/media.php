<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
class KSMedia {
    
    public static function resizeImage($file, $folder, $width = 120, $height = 120, $iparams = array(), $loc_ext_name_com = null) {
        global $ext_name, $ext_name_com;
        
        $loc_ext_name_com = !empty($loc_ext_name_com) ? $loc_ext_name_com : $ext_name_com;
        
        $params = JComponentHelper::getParams($loc_ext_name_com);
        
        if (!isset($iparams['displace'])) $iparams['displace'] = $params->get('displace', 0);
        if (!isset($iparams['watermark'])) $iparams['watermark'] = $params->get('watermark', 0);
        if (!isset($iparams['halign'])) $iparams['halign'] = $params->get('halign', 'center');
        if (!isset($iparams['valign'])) $iparams['valign'] = $params->get('valign', 'middle');
        
        $iparams['watermark_type'] = $params->get('watermark_type', 0);
        $iparams['watermark_valign'] = $params->get('watermark_valign', 'middle');
        $iparams['watermark_halign'] = $params->get('watermark_halign', 'center');
        $iparams['background_type'] = $params->get('background_type', 'file');
        natsort($iparams);
        $dst_file = basename($file);
        foreach ($iparams as $iparam) {
            $dst_file = JString::str_ireplace('/', '.', $iparam) . '-' . $dst_file;
        }
        
        if (!class_exists('SimpleImage')) {
            require dirname(__file__) . DS . '..' . DS . 'additional' . DS . 'simpleimage.php';
        } /*
        if($width == 0) {
            $width = $params->get('thumb_width', 120);
        }*/
        
        if ($height == 0) {
            $height = $params->get('thumb_height', 120);
        }
        
        $dst_path_segments = array('media', $loc_ext_name_com, 'images', $folder, 'w' . $width . 'x' . 'h' . $height);
        $dst_path = JPATH_ROOT;
        
        foreach ($dst_path_segments as $v) {
            $dst_path.= DS . $v;
            if (!JFolder::exists($dst_path)) {
                JFolder::create($dst_path);
                chmod($dst_path, 0777);
            }
        }
        ($file != '') ? $dst_filename = $dst_path . DS . $dst_file : $dst_filename = $dst_path . DS . 'no.jpg';
        
        if (!JFile::exists($dst_filename)) {
            if ($file != '') {
                $src_filename = JPATH_ROOT . DS . 'media' . DS . $loc_ext_name_com . DS . 'images' . DS . $folder . DS . 'original' . DS . basename($file);
            } else {
                $src_filename = JPATH_ROOT . DS . 'media' . DS . $loc_ext_name_com . DS . 'images' . DS . $folder . DS . 'no.jpg';
            }
            
            if (!JFile::exists($src_filename)) {
                $src_filename = JPATH_ROOT . DS . 'media' . DS . $loc_ext_name_com . DS . 'images' . DS . $folder . DS . 'no.jpg';
            }
            $image = new SimpleImage($src_filename);
            
            if ($width == 0) {
                $ratio = $height / $image->get_height();
                $width = $image->get_width() * $ratio;
            }
            
            if ($image->get_width() > $width || $image->get_height() > $height) {
                $ratio_in = $image->get_width() / $image->get_height();
                if ($iparams['displace'] == 0) {
                    $ratio_out = $width / $height;
                    if ($ratio_in > $ratio_out) {
                        $image->resize((int)$width * $ratio_in, $height);
                    } else {
                        $image->resize($width, $width / $ratio_in);
                    }
                    $x1 = 0;
                    $y1 = 0;
                    switch ($iparams['halign']) {
                        case 'center':
                            $x1 = round($image->get_width() / 2 - $width / 2);
                        break;
                        case 'right':
                            $x1 = round($image->get_width() - $width);
                        break;
                    }
                    switch ($iparams['valign']) {
                        case 'middle':
                            $y1 = round($image->get_height() / 2 - $height / 2);
                        break;
                        case 'bottom':
                            $y1 = round($image->get_height() - $height);
                        break;
                    }
                    $image->crop($x1, $y1, $x1 + $width, $y1 + $height);
                } else {
                    if ($width / $ratio_in > $height) $image->resize($height * $ratio_in, $height);
                    else $image->resize($width, $width / $ratio_in);
                }
            }
            
            if ($iparams['watermark'] == 1) {
                $watermark_image = $params->get('watermark_image', '');
                if (file_exists(JPATH_ROOT . DS . $watermark_image) && is_file(JPATH_ROOT . DS . $watermark_image)) $image->watermark(JPATH_ROOT . DS . $watermark_image, $iparams);
            }
            
            $bg_file = $params->get('background_file', '');
            $image->background($width, $height, $iparams, $bg_file, $params->get('background_color', 'ffffff'));
            //$image->best_fit($width, $height);
            
            if ($iparams['background_type'] == 'file' && (empty($bg_file) || (!empty($bg_file) && !file_exists(JPATH_ROOT . DS . $file)))) $image->save($dst_filename, 'png');
            else $image->save($dst_filename);
        }
        
        return JURI::root() . str_replace(JPATH_ROOT . DS, '', $dst_filename);
    }
	
    public static function setItemMedia($item = null, $owner_type = null) {
        if (!$item) return false;
        
        global $ext_name, $ext_name_com;
        $item->images = array();
        $item->files = array();
        $item->videos = array();
        $owner_id = (int)$item->id;
        
        if (!$owner_type) return $item;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__' . $ext_name . '_files')->where('owner_type=' . $db->quote($owner_type))->where('owner_id=' . $owner_id)->order('ordering');
        $db->setQuery($query);
        $medias = $db->loadObjectList('id');
        
        foreach ($medias as $media) {
            if ($media->media_type == 'image') {
                $item->images[$media->id] = $media;
            }
            if ($media->media_type == 'file') {
                $item->files[$media->id] = $media;
            }
            if ($media->media_type == 'video') {
                $item->videos[$media->id] = $media;
            }
        }
        
        return $item;
    }	
    
    public static function saveItemMedia($id = null, $data = array(), $owner_type = null, $folder = null) {
        
        $owner_id = (int)$id;
        if (!$owner_id) return false;
        
        global $ext_name, $ext_name_com;
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . $ext_name_com . DS . 'tables');
        $db     = JFactory::getDBO();
        $prefix = ucfirst($ext_name);
        $in     = array();
        if (isset($data['images']) && $data['images']) {
            foreach ($data['images'] as $k => $v) {
                $k = (int)$k;
                if ($v['task'] == 'save') {
                    $table = JTable::getInstance('files', $prefix . 'Table', array());
                    
                    $v['owner_type'] = $owner_type;
                    $v['media_type'] = 'image';
                    $v['mime_type']  = 'image/jpeg';
                    $v['folder']     = $folder;
                    if ($k > 0) {
                        $v['id'] = $k;
                    }
                    $v['owner_id'] = $owner_id;
                    $v['params']   = json_encode($v['params']);
                    
                    if (!$table->bindCheckStore($v)) {
                        $this->setError($table->getError());
                        return false;
                    }
                    $in[] = $table->id;
                }
            }
        }
        
        $query = $db->getQuery(true);
        $query
            ->delete($db->qn('#__' . $ext_name . '_files'))
            ->where($db->qn('owner_id') . '=' . $db->q($owner_id))
            ->where($db->qn('owner_type') . '=' . $db->q($owner_type))
            ->where($db->qn('media_type') . '=' . $db->q('image'))
        ;
        if (count($in)) {
            $query
                ->where('id not in (' . implode(', ', $in) . ')')
                ->where($db->qn('id') . ' NOT IN (' . implode(', ', $db->q($in)) . ')')
            ;
        }
        $db->setQuery($query);
        $db->query();
        return true;
    }
    
    public static function deleteItemMedia($id = null, $owner_type = null) {
        
        global $ext_name, $ext_name_com;
        $owner_id = (int)$id;
        if (!$owner_id) return false;
        if (!$owner_type) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__' . $ext_name . '_files')->where('owner_type=' . $db->quote($owner_type))->where('owner_id=' . $owner_id);
        $db->setQuery($query);
        $medias = $db->loadObjectList('id');
        
        foreach ($medias as $media) {
            if ($media->media_type == 'image') {
                KSMedia::deletePhoto($media->filename, $media->folder);
            }
            if ($media->media_type == 'file') {
            }
            if ($media->media_type == 'video') {
            }
        }
        return true;
    }
    
    public static function getThumbDivStyle($width = 0, $heigth = 0) {
        
        global $ext_name, $ext_name_com;
        $params = JComponentHelper::getParams($ext_name_com);
        
        if ($width === 0) {
            $width = $params->get('thumb_width');
        }
        
        if ($heigth === 0) {
            $heigth = $params->get('thumb_height');
        }
        
        $style = array();
        $style[] = 'width:' . $width . 'px!important';
        $style[] = 'height:' . $heigth . 'px!important';
        $style[] = 'overflow:hidden!important';
        $style[] = 'display:inline-block!important';
        
        return implode(';', $style);
    }
    
    public static function getThumbImgStyle($width = 0, $heigth = 0, $iparams = array(), $img) {
        
        global $ext_name, $ext_name_com;
        $params = JComponentHelper::getParams($ext_name_com);
        
        if (!isset($iparams['displace'])) $iparams['displace'] = $params->get('displace', 0);
        if (!isset($iparams['watermark'])) $iparams['watermark'] = $params->get('watermark', 0);
        if (!isset($iparams['halign'])) $iparams['halign'] = $params->get('halign', 'center');
        if (!isset($iparams['valign'])) $iparams['valign'] = $params->get('valign', 'middle');
        
        if ($width === 0) {
            $width = $params->get('thumb_width');
        }
        
        if ($heigth === 0) {
            $heigth = $params->get('thumb_height');
        }
        $img = str_replace(' ', '%20', $img);
        $sizes = getimagesize($img);
        switch ($iparams['halign']) {
            case 'left':
                $marginL = 0;
            break;
            case 'center':
                $marginL = round(($width - $sizes[0]) / 2);
            break;
            case 'right':
                $marginL = round($width - $sizes[0]);
            break;
        }
        switch ($iparams['valign']) {
            case 'top':
                $marginT = 0;
            break;
            case 'middle':
                $marginT = round(($heigth - $sizes[1]) / 2);
            break;
            case 'bottom':
                $marginT = round($heigth - $sizes[1]);
            break;
        }
        
        $style = array();
        $style[] = 'margin-left:' . $marginL . 'px!important';
        $style[] = 'margin-top:' . $marginT . 'px!important';
        
        return implode(';', $style);
    }
    
    public static function deletePhoto($filename, $folder) {
        
        global $ext_name, $ext_name_com;
        $db = JFactory::getDBO();
        $subfolders = scandir(JPATH_ROOT . '/media/' . $ext_name_com . '/images/' . $folder);
        foreach ($subfolders as $subfolder) {
            if ($subfolder != '.' && $subfolder != '..' && is_dir(JPATH_ROOT . '/media/' . $ext_name_com . '/images/' . $folder . '/' . $subfolder)) {
                $files = scandir(JPATH_ROOT . '/media/' . $ext_name_com . '/images/' . $folder . '/' . $subfolder);
                foreach ($files as $file) {
                    if ($subfolder != '.' && $subfolder != '..' && strpos($file, $filename) !== false) unlink(JPATH_ROOT . '/media/' . $ext_name_com . '/images/' . $folder . '/' . $subfolder . '/' . $file);
                }
            }
        }
        $query = $db->getQuery(true);
        $query->delete('#__' . $ext_name . '_files')->where('filename=' . $db->quote($filename));
        $db->setQuery($query);
        $db->query();
        return true;
    }
    
    public static function deleteFile($filename, $folder) {
        
        global $ext_name, $ext_name_com;
        $db = JFactory::getDBO();
        $subfolders = scandir(JPATH_ROOT . '/media/' . $ext_name_com . '/files/' . $folder);
        foreach ($subfolders as $subfolder) {
            if ($subfolder != '.' && $subfolder != '..' && is_dir(JPATH_ROOT . '/media/' . $ext_name_com . '/files/' . $folder . '/' . $subfolder)) {
                $files = scandir(JPATH_ROOT . '/media/' . $ext_name_com . '/files/' . $folder . '/' . $subfolder);
                foreach ($files as $file) {
                    if ($subfolder != '.' && $subfolder != '..' && strpos($file, $filename) === true) unlink(JPATH_ROOT . '/media/' . $ext_name_com . '/files/' . $folder . '/' . $subfolder . '/' . $file);
                }
            }
        }
        $query = $db->getQuery(true);
        $query->delete('#__' . $ext_name . '_files')->where('filename=' . $db->quote($filename));
        $db->setQuery($query);
        $db->query();
        return true;
    }
    
    public static function setItemMainImageToQuery($query, $owner_type = 'product', $l = 'p.', $loc_ext_name = null) {
        global $ext_name, $ext_name_com;
        
        $loc_ext_name = !empty($loc_ext_name) ? $loc_ext_name : $ext_name;
        
        $query->select('(select f.filename from #__' . $loc_ext_name . '_files as f where f.owner_id=' . $l . 'id and owner_type="' . $owner_type . '" and media_type="image" order by ordering limit 1) as filename');
        $query->select('(select f.folder from #__' . $loc_ext_name . '_files as f where f.owner_id=' . $l . 'id and owner_type="' . $owner_type . '" and media_type="image" order by ordering limit 1) as folder');
        $query->select('(select f.params from #__' . $loc_ext_name . '_files as f where f.owner_id=' . $l . 'id and owner_type="' . $owner_type . '" and media_type="image" order by ordering limit 1) as params');
        return $query;
    }
}
