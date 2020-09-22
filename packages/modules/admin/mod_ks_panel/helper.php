<?php
/**
 *
 * $Id: helper.php 1.0.0 2012-11-27 09:24:43 Alexander Polyakov $
 * @package	    Joomla! 
 * @subpackage	Ksenmart Cpanel
 * @version     1.0.0
 * @description 
 * @copyright	  Copyright © 2012 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Alexander Polyakov
 * @author mail	alx.polyakov@gmail.com
 * @website		  http://alx-polyakov.ru/
 *
 *
*
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
/**
 * Example Module Helper
 *
 * @package		  Joomla!
 * @subpackage	Ksenmart Cpanel
 * @since 		  1.0.0
 * @class       ModKsenmartcpanelHelper
 */ 
 
class ModKsenmartcpanelHelper
{
	/**
	 * Do something getItems method
	 *
	 * @param 	
	 * @return
	 */
	public static $orrerBy = array('column', 'ordering');
	
    public static  function getItems($params = null )
    {
    	$modules = array(
    		array(
    			'title'=>JText::_('KCP_ORDERS'), 'url'=>'index.php?option=com_ksenmart&view=orders',
    			'state' => 1, 'ordering'=>0, 'column'=>'1', 'image'=>'wid-order.png', 'class' => 'double',
    			'method' => 'getOrders' 
    		),
    		array(
    			'title'=>JText::_('KCP_CATALOGUE'), 'url'=>'index.php?option=com_ksenmart&view=catalog',
    			'state' => 1, 'ordering'=>1, 'column'=>'1', 'image'=>'wid-cat.png', 'class' => ''  
    			),
    		array(
    			'title'=>JText::_('KCP_PROPERTIES'), 'url'=>'index.php?option=com_ksenmart&view=properties',
    			'state' => 1, 'ordering'=>1, 'column'=>'1', 'image'=>'wid-option.png', 'class' => '' 
    			),
    		array(
    			'title'=>JText::_('KCP_ACTIONS'), 'url'=> 'index.php?option=com_ksenmart&view=discounts',
    			'state' => 1, 'ordering'=>2, 'column'=>'1', 'image'=>'wid-sale.png' 
    			),
    		array(
    			'title'=>JText::_('KCP_REPORTS'), 'url'=>'index.php?option=com_ksenmart&view=reports',
    			'state' => 1, 'ordering'=>3, 'column'=>'1', 'image'=>'wid-report.png' 
    			),
    		array(
    			'title'=>JText::_('KCP_REVIEWS'), 'url'=>'index.php?option=com_ksenmart&view=comments',
    			'state' => 1, 'ordering'=>7, 'column'=>'1', 'image'=>'wid-comm2.png'
    		),
    		array(
    			'title'=>JText::_('KCP_ARTICLES'),'url'=>'index.php?option=com_content',
    			'state' => 1, 'ordering'=>1, 'column'=>'2', 'image'=>'wid-artcl.png', 'class' => '' 
    		),
    		array(
    			'title'=>JText::_('KCP_MENUS'), 'url'=>'index.php?option=com_menus&view=menus',
    			'state' => 1, 'ordering'=>2, 'column'=>'2', 'image'=>'wid-menu.png', 'class' => '' 
    		),
    		array(
    			'title'=>JText::_('KCP_MODULES'), 'url'=>'index.php?option=com_modules',
    			'state' => 1, 'ordering'=>3, 'column'=>'2', 'image'=>'wid-mods.png', 'class' => '' 
    		),
    		array(
    			'title'=>JText::_('KCP_UPDATES'), 'url'=>'index.php?option=com_installer&view=update',
    			'state' => 1, 'ordering'=>4, 'column'=>'3', 'image'=>'wid-refr.png', 'class' => '' 
    		),
    		array(
    				'title'=>JText::_('KCP_CONFIG'), 'url'=>'index.php?option=com_config',
    				'state' => 1, 'ordering'=>5, 'column'=>'3', 'image'=>'wid-prefs.png', 'class' => ''
    		),
    			
    		array(
    			'title'=>JText::_('KCP_ADDARTICLE'), 'url'=>'index.php?option=com_content&view=article&layout=edit',
    			'state' => 1, 'ordering'=>1, 'column'=>'4', 'image'=>'wid-add.png', 'class' => 'double'
    		),
    		array(
    			'title'=>JText::_('KCP_CONTACTS'), 'url'=>'index.php?option=com_contact',
    			'state' => 1, 'ordering'=>2, 'column'=>'4', 'image'=>'wid-qr.png', 'class' => ''
    		),
    		array(
    			'title'=>JText::_('KCP_COMMENTS'), 'url'=>'index.php?option=com_jcomments',
    			'state' => 1, 'ordering'=>1, 'column'=>'4', 'image'=>'wid-comm.png'
    		),
    		array(
    			'title'=>JText::_('KCP_LEARNING'), 'url'=>'index.php?option=com_admin&view=help',
    			'state' => 1, 'ordering'=>1, 'column'=>'4', 'image'=>'wid-help.png'
    		),
    			
    		
    	);
    	
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$uid = JFactory::getUser()->id;
    	//$query->select('config')->from('#__ksenmart_modcpanel')->where('user_id='.$uid);
    	
    	//$db->setQuery($query);
    	
    	
    	//$config = $db->loadResult();
    	
    	//if (!$config) {
    		//$config = $default;
    	//}
    	//$config = json_decode($config);
    	//$conf = array();
    	//foreach ($config as $k=>$obj) {
    		//$conf[$k] = $obj;
    	//}
    	
    	foreach ($modules as $k=>$m) {
    		$modules[$k] = (object)$m;
    		if (isset($modules[$k]->method) && method_exists('ModKsenmartcpanelHelper', $modules[$k]->method)){
    			$modules[$k]->content = call_user_func(array('ModKsenmartcpanelHelper', $modules[$k]->method ));
    		}
    		if (!isset($modules[$k]->content)){
    			$modules[$k]->content = '<h3>'.$modules[$k]->title.'</h3>';
    		}
    		
    		/*if (is_array($conf)) {
    			
	    		if (array_key_exists($k, $conf)){
	    			
	    			$mconf = $conf[$k];
	    			if (property_exists($mconf, 'ordering')) {
	    				$modules[$k]->ordering =$conf[$k]->ordering;
	    				unset($mconf->ordering);
	    				
	    			}
	    			
	    			if (property_exists($mconf, 'column')) {
	    				$modules[$k]->column =$conf[$k]->column;
	    				unset($mconf->column);
	    			}
	    			$modules[$k]->style =$mconf;
	    			
	    		}
    		}*/
    	}
    	//usort($modules, 'ModKsenmartcpanelHelper::compareRows');
    	return $modules;
      
    }

   
    
    public static function compareRows($r1, $r2) {
    	foreach (self::$orrerBy as $k) {
    		if (!property_exists($r1, $k ) || !property_exists($r2, $k)){
    			return 0;
    		}
    		$ret = strnatcmp($r1->$k, $r2->$k);
    		if ($ret != 0) {
    			return $ret;
    		}
    	}
    	return 0;
    }
 
    public static function getOrders(){
    
    	$db=JFactory::getDBO();
    	$query="select count(id) from #__ksenmart_orders where status_id='1'";
    	$db->setQuery($query);
    	$new_count=$db->loadResult();
    	$query="select count(id) from #__ksenmart_orders where status_id='3'";
    	$db->setQuery($query);
    	$done_count=$db->loadResult();
    	
    	return '<h3>'.JText::_('orders').'</h3>'.'<p class="red">'.JText::_('new_orders').' - '.$new_count.'</p>';
    }
} 

?>