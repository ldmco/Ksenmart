<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenModelMedia extends JModelKSAdmin { 
	public function __construct($config = array()) {
		parent::__construct($config);
	}
}