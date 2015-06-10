<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
class JFormFieldKSLayoutModules extends JFormField {
    
    public $type = 'KSLayoutModules';
    public $views = array(
        'catalog' => array(
			'catalog' => array(
				'_item' => array(
					'_image'
				),
				'_sortlinks',
				'_pagination'
			),
			'category' => array(
				'_item' => array(
					'_image'
				),
				'_sortlinks',
				'_pagination'
			),
			'manufacturer' => array(
				'_item' => array(
					'_image'
				),
				'_sortlinks',
				'_pagination'
			),
			'country' => array(
				'_item' => array(
					'_image'
				),
				'_sortlinks',
				'_pagination'
			)			
		),
        'product' => array(
			'product' => array(
				'_gallery',
				'_info',
				'_prices',
				'_tabs',
				'_related'
			),
			'parent_product_list' => array(
				'_gallery',
				'_info',
				'_prices',
				'_tabs',
				'_related'
			),
			'parent_product_select' => array(
				'_gallery',
				'_info',
				'_prices',
				'_tabs',
				'_related'
			),
			'child' => array(
				'_gallery',
				'_info',
				'_prices',
				'_tabs',
				'_related'
			),
			'set' => array(
				'_gallery',
				'_info',
				'_prices',
				'_tabs',
				'_related'
			)			
		)
    );
    
    public function getInput() {
		$document = JFactory::getDocument();
		$items = is_array($this->value) ? $this->value : array();
		$keys = array_keys($items);
		$keys[] = 0;
		$count_new_layout = max($keys) + 1;
        $layout_options = $this->getLayoutOptions();
		$position_options = $this->getPositionOptions();
		$event_options = array(
            JHtml::_('select.option', 'before', JText::_('ksm_plugin_modules_event_before')) ,
            JHtml::_('select.option', 'after', JText::_('ksm_plugin_modules_event_after'))		
		);
		
        $html = '<style>.form-horizontal .controls {margin:0px;}</style>';
        $html.= '<table class="table table-striped layouts" id="articleList">';
        $html.= '	<thead>';
        $html.= '		<tr>';
        $html.= '			<th class="title">' . JText::_('ksm_plugin_modules_layout') . '</th>';
        $html.= '			<th width="30%" class="nowrap hidden-phone">' . JText::_('ksm_plugin_modules_module_position') . '</th>';
        $html.= '			<th width="30%" class="nowrap hidden-phone">' . JText::_('ksm_plugin_modules_event') . '</th>';
        $html.= '			<th width="10%" class="hidden-phone"></th>';
        $html.= '		</tr>';
        $html.= '	</thead>';
		$html.= '	<tr class="add-layout">';
		$html.= '		<td class="title">' . JHtml::_('select.genericlist', $layout_options, '', 'id="layout-select"', 'value', 'text') . '</td>';
		$html.= '		<td width="30%" class="nowrap hidden-phone">' . JHtml::_('select.genericlist', $position_options, '', 'id="position-select"', 'value', 'text') . '</td>';
		$html.= '		<td width="30%" class="nowrap hidden-phone">' . JHtml::_('select.genericlist', $event_options, '', 'id="event-select"', 'value', 'text') . '</td>';
		$html.= '		<td width="10%" class="hidden-phone"><a class="btn btn-primary btn-add"><span class="icon-new"></span>'.JText::_('ksm_plugin_modules_add').'</a></td>';
		$html.= '	</tr>';		
		$html.= '	<tr class="layout-mask" style="display:none;">';
		$html.= '		<td class="title"><span></span><input type="hidden"></td>';
		$html.= '		<td width="30%" class="nowrap hidden-phone position"><span></span><input type="hidden"></td>';
		$html.= '		<td width="30%" class="nowrap hidden-phone event"><span></span><input type="hidden"></td>';
		$html.= '		<td width="10%" class="hidden-phone"><a class="btn btn-primary btn-del"><span class="icon-delete"></span>'.JText::_('ksm_plugin_modules_delete').'</a></td>';
		$html.= '	</tr>';		
		
        foreach ($items as $key => $item) {
            $html.= '<tr>';
            $html.= '	<td class="title"><span>' . JText::_('ksm_plugin_modules_layout_'.$item['layout']) . '</span><input type="hidden" name="'.$this->name.'['.$key.'][layout]" value="'.$item['layout'].'"></td>';
            $html.= '	<td width="30%" class="nowrap hidden-phone"><span>' . $item['position'] . '</span><input type="hidden" name="'.$this->name.'['.$key.'][position]" value="'.$item['position'].'"></td>';
            $html.= '	<td width="30%" class="nowrap hidden-phone"><span>' . JText::_('ksm_plugin_modules_event_'.$item['event']) . '</span><input type="hidden" name="'.$this->name.'['.$key.'][event]" value="'.$item['event'].'"></td>';
            $html.= '	<td width="10%" class="hidden-phone"><a class="btn btn-primary btn-del"><span class="icon-delete"></span>'.JText::_('ksm_plugin_modules_delete').'</a></td>';
            $html.= '</tr>';
        }
        
        $html.= '</table>';
		
		$script = '
		jQuery(document).ready(function(){
			
			var count_new_layout = '.$count_new_layout.';
			
			jQuery(".layouts .btn-add").click(function(){
				var layout_block = jQuery(".layouts .layout-mask").clone().appendTo(".layouts").removeClass("layout-mask");
				var layout = jQuery("#layout-select option:selected").val();
				var layout_name = "'.$this->name.'["+count_new_layout+"][layout]";
				var layout_text = Joomla.JText._("ksm_plugin_modules_layout_"+layout);
				var position = jQuery("#position-select option:selected").val();
				var position_name = "'.$this->name.'["+count_new_layout+"][position]";
				var event = jQuery("#event-select option:selected").val();
				var event_text = jQuery("#event-select option:selected").text();
				var event_name = "'.$this->name.'["+count_new_layout+"][event]";				
				
				layout_block.find(".title span").text(layout_text);
				layout_block.find(".title input").attr("name", layout_name);
				layout_block.find(".title input").val(layout);
				layout_block.find(".position span").text(position);
				layout_block.find(".position input").attr("name", position_name);
				layout_block.find(".position input").val(position);
				layout_block.find(".event span").text(event_text);
				layout_block.find(".event input").attr("name", event_name);
				layout_block.find(".event input").val(event);
				
				layout_block.show();
				count_new_layout++;
				
				return false;
			});
			
			jQuery("body").on("click", ".layouts .btn-del", function(){
				jQuery(this).parents("tr:first").remove();
				
				return false;
			});
			
		});
		';
		$document->addScriptDeclaration($script);
        
        return $html;
    }
    
    public function getLabel() {
        $html = '';
        
        return $html;
    }
    
    protected function getPositionOptions() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select('DISTINCT(position) as value')
			->select('position as text')
			->from($db->quoteName('#__modules'))
			->where($db->quoteName('client_id') . ' = 0')
			->order('position');

		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		if (count($options))
		{
			if (strlen($options[0]->text) < 1)
			{
				array_shift($options);
			}
		}

		return $options;
	}
    
    protected function getLayoutOptions() {
        $options = array();
		foreach($this->views as $view => $layouts){
			$items = $this->getLayouts($layouts, 0, $view, array());
			$options = array_merge($options, $items);
		}
        
        return $options;
    }
    
    private function getLayouts($layouts, $level, $prefix, $items) {
        foreach ($layouts as $key => $layout) {
			if (is_array($layout)){
				$items[] = JHtml::_('select.option', $prefix.$key, str_repeat('- ', $level) . JText::_('ksm_plugin_modules_layout_'.$prefix.$key));
				JText::script('ksm_plugin_modules_layout_'.$prefix.$key);
				$items = $this->getLayouts($layout, $level + 1, $prefix.$key, $items);
			} else {
				JText::script('ksm_plugin_modules_layout_'.$prefix.$layout);
				$items[] = JHtml::_('select.option', $prefix.$layout, str_repeat('- ', $level) . JText::_('ksm_plugin_modules_layout_'.$prefix.$layout));				
			}
        }
		
        return $items;
    }
}
