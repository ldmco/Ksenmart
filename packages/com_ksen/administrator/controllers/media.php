<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class KsenControllerMedia extends KsenController {
	
	function readdir() {
		$user = JFactory::getUser();
		JFactory::getDocument()->setMimeEncoding('application/json');
		echo json_encode(($user->getAuthorisedGroups()));
		JFactory::getApplication()->close();
	}
	
	function upload_image() {
		
		$user = JFactory::getUser();
		JFactory::getDocument()->setMimeEncoding('application/json');
		if ($user->guest) {
			echo json_encode(array(
				'error' => 'no permissions'
			));
			
			return JFactory::getApplication()->close();
		}
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$file = JRequest::getVar('Filedata', '', 'files', 'array');
		$extension = JRequest::getVar('extension', null);
		//mime_content_type()
		
		if (($imginfo = getimagesize($file['tmp_name'])) === false) {
			echo json_encode(array(
				'error' => 'not an image'
			));
			
			return JFactory::getApplication()->close();
		}
		$params = JComponentHelper::getParams('com_media');
		$allowed_mime = explode(',', $params->get('upload_mime'));
		$illegal_mime = explode(',', $params->get('upload_mime_illegal'));
		
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$type = finfo_file($finfo, $file['tmp_name']);
			if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
				echo json_encode(array(
					'error' => 'dont try to fuck the server'
				));
				
				return JFactory::getApplication()->close();
			}
			finfo_close($finfo);
		} elseif (function_exists('mime_content_type')) {
			$type = mime_content_type($file['tmp_name']);
			if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
				echo json_encode(array(
					'error' => 'COM_KSM_ERROR_WARNINVALID_MIME'
				));
				
				return JFactory::getApplication()->close();
			}
		} elseif (!$user->authorise('core.manage')) {
			echo json_encode(array(
				'error' => 'COM_KSM_ERROR_WARNNOTADMIN'
			));
			
			return JFactory::getApplication()->close();
		}
		
		$to = JRequest::getString('to', '');
		$folder = JRequest::getString('folder', '');
		$path = JPATH_ROOT;
		$to = explode("/", $to);
		
		foreach ($to as $s) {
			$path = $path . DS . $s;
			if (!JFolder::exists($path)) {
				if (!JFolder::create($path)) {
					echo json_encode(array(
						'error' => 'COM_KSM_ERROR_CREATEDIR'
					));
					
					return JFactory::getApplication()->close();
				}
			}
		}
		
		
		try {
			$imageObject = new JImage();
			$imageObject->loadFile($file['tmp_name']);
		}
		catch(Exception $e) {
			echo json_encode(array(
				'error' => $e->getMessage()
			));
			
			return JFactory::getApplication()->close();
		}
		$pathinfo = pathinfo($file['name']);
		$fileName = $path . DS . microtime(true) . '.' . $pathinfo['extension'];
		
		
		try {
			$imageObject->toFile($fileName, IMAGETYPE_JPEG, array(
				'quality',
				100
			));
			//$imageObject->save($fileName, 100);
			
		}
		catch(Exception $e) {
			echo json_encode(array(
				'error' => $e->getMessage()
			));
			
			return JFactory::getApplication()->close();
		}
		$url = KSMedia::resizeImage(basename($fileName) , $folder, null, null, null, $extension);
		
		$model = $this->getModel('media');
		$model->form = 'images';
		$item = new stdClass();
		$table = $model->getTable('Files');
		$table->media_type = 'images';
		$table->filename = str_replace(JPATH_ROOT, '', $fileName);
		$table->folder = $folder;
		$table->id = '{id}';
		$item->images = array(
			0 => $table
		);
		$form = $model->getForm();
		$form->setFieldAttribute('images', 'extension', $extension);
		$form->bind($item);
		$html = $form->getInput('images');
		echo json_encode(array(
			'error' => '',
			'filename' => str_replace(JPATH_ROOT, '', $fileName) ,
			'url' => $url,
			'html' => $html
		));
		JFactory::getApplication()->close();
	}
	
	function delete_photo() {
		$filename = JRequest::getVar('filename', '');
		$folder = JRequest::getVar('folder', '');
		$extension = JRequest::getVar('extension', null);
		$model = $this->getModel('product');
		KSMedia::deletePhoto($filename, $folder, $extension);
		JFactory::getApplication()->close();
	}
	
	function upload_file() {
		$user = JFactory::getUser();
		JFactory::getDocument()->setMimeEncoding('application/json');
		if ($user->guest) {
			echo json_encode(array(
				'error' => 'no permissions'
			));
			
			return JFactory::getApplication()->close();
		}
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$file = JRequest::getVar('Filedata', '', 'files', 'array');
		
		if (!$user->authorise('core.manage')) {
			echo json_encode(array(
				'error' => 'COM_KSM_ERROR_WARNNOTADMIN'
			));
			
			return JFactory::getApplication()->close();
		}
		
		$to = JRequest::getString('to', '');
		$path = JPATH_ROOT;
		$to = explode("/", $to);
		
		foreach ($to as $s) {
			$path = $path . DS . $s;
			if (!JFolder::exists($path)) {
				if (!JFolder::create($path)) {
					echo json_encode(array(
						'error' => 'COM_KSM_ERROR_CREATEDIR'
					));
					
					return JFactory::getApplication()->close();
				}
			}
		}
		
		$pathinfo = pathinfo($file['name']);
		$fileName = $path . DS . microtime(true) . '.' . $pathinfo['extension'];
		copy($file['tmp_name'], $fileName);
		
		echo json_encode(array(
			'error' => '',
			'filename' => str_replace(JPATH_ROOT, '', $fileName) ,
			'url' => $url,
			'title' => $pathinfo['filename']
		));
		JFactory::getApplication()->close();
	}
	
	function delete_file() {
		$filename = JRequest::getVar('filename', '');
		$folder = JRequest::getVar('folder', '');
		KSMedia::deleteFile($filename, $folder);
		JFactory::getApplication()->close();
	}
}
