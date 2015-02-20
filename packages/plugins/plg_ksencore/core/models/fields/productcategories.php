<?php defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');
class JFormFieldProductCategories extends JFormFieldCheckboxes {

    protected $type = 'ProductCategories';
    private $tree = array();
    private $menu = array();

    public function getInput() {
        $this->buildCategoriesTree();
        if($this->menu) $this->makeCategoriesTree($this->menu[0]);
        $path = $this->getPath();
        $defname = str_replace('[]', '[default]', $this->name);
        $html = '<ul ng-controller="CategoryChangeCtrl">';
        if(count($this->tree) > 0) {
            foreach($this->tree as $category) {
                $checked = '';
                $fchecked = '';
                $disabled = ' disabled="disabled" ';
                if($category->selected) {
                    $checked = ' checked="checked" ';
                    $disabled = '';
                    if($this->value[$category->id]->is_default) {
                        $category->class .= ' favorite';
                        $fchecked = ' checked="checked" ';
                    }
                }
                $html .= '<li class="' . $category->class . '" ng-click="selectCat($event)">';
                $html .= '	<label class="cat-lbl">' . $category->title;
                $html .= '	 	<input type="checkbox" ' . $checked . ' value="' . $category->id . '" name="' . $this->name . '" onclick="setCategoryActive(this);" />';
                if($category->deeper) $html .= '	 <a href="javascript:void(0);" class="sh ' . (in_array($category->id, $path) ? 'hides' : 'show') . '" ng-class="{\'hides\':collapseChildCats_'.$category->id.',\'show\':!collapseChildCats_'.$category->id.'}" ng-click="$event.stopPropagation(); collapseChildCats_'.$category->id.' = ! collapseChildCats_'.$category->id.'"></a>';
                $html .= '	</label>';
                $html .= '	<label class="def-lbl" ng-click="setCategoryDefault($event)">';
                $html .= '		<input type="radio" value="' . $category->id . '" name="' . $defname . '" onclick="setCategoryFavorite(this);" ' . $disabled . $fchecked . ' />';
                $html .= '	</label>';
                if($category->deeper) {
                    $html .= '<ul class="' . (in_array($category->id, $path) ? 'opened' : '') . '" ng-show="collapseChildCats_'.$category->id.'">';
                } elseif($category->shallower) {
                    $html .= '</li>';
                    $html .= str_repeat('</ul></li>', $category->level_diff);
                } else {
                    $html .= '</li>';
                }
            }
        } else {
            $html .= '<li>';
            $html .= '<label>' . JText::_('KS_CATEGORIES_NO_ITEMS') . '</label>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        $script = '
		jQuery(document).ready(function(){
				
			jQuery("body").on("click", ".ksm-slidemodule-productcategories ul li a.show", function(){
				jQuery(this).removeClass("show");
				jQuery(this).addClass("hides");
				jQuery(this).parents("li:first").find("ul:first").addClass("opened");		
				jQuery(this).parents("li:first").find("ul:first").slideDown(300);
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-productcategories ul li a.hides", function(){
				jQuery(this).removeClass("hides");
				jQuery(this).addClass("show");
				jQuery(this).parents("li:first").find("ul:first").removeClass("opened");		
				jQuery(this).parents("li:first").find("ul:first").slideUp(300);
				return false;
			});	
			
		});

		function setCategoryActive(obj){
			var item=jQuery(obj).parents("li:first");
			var data = {};
			data["task"]="catalog.get_properties";
			data["id"]=jQuery("#jform_id").val();
			data["categories"]=[];
			data["active_properties"]=[];
			if (item.is(".active"))
			{
				item.removeClass("active");
				item.removeClass("favorite");
				item.find("input[type=radio]:first").attr("disabled","disabled");
				item.find("input[type=radio]:first").removeAttr("checked");
				jQuery(".ksm-slidemodule-productcategories ul li.active:first").addClass("favorite");
				jQuery(".ksm-slidemodule-productcategories ul li.active:first input[type=radio]:first").attr("checked","checked");
			}
			else
			{
				item.addClass("active");
				item.find("input[type=radio]:first").removeAttr("disabled");
				if (jQuery(".ksm-slidemodule-productcategories ul li.active").length==1)
				{
					item.addClass("favorite");
					item.find("input[type=radio]:first").attr("checked","checked");
				}	
			}	
			jQuery(".ksm-slidemodule-productcategories input[type=\'checkbox\']:checked").each(function(){
				data["categories"].push(jQuery(this).val());
			});
			jQuery(".property-ul:visible").each(function(){
				data["active_properties"].push(jQuery(this).attr("property_id"));
			});			
			jQuery.ajax({
				url:"index.php?option=com_ksenmart",
				data:data,
				dataType:"json",
				async:false,				
				success:function(responce){
					jQuery(".properties").replaceWith(responce.html);
				}
			});			
		}
		
		function setCategoryFavorite(obj){
			var item=jQuery(obj).parents("li:first");
			if (!item.is(".favorite"))
			{
				jQuery(".ksm-slidemodule-productcategories ul li").removeClass("favorite");
				item.addClass("favorite");
			}
		}		
		';
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($script);
        return $html;
    }

    function buildCategoriesTree() {
        
        $option          = JFactory::getApplication()->input->get('option', 'com_ksenmart', 'string');
        $component_name = str_replace('com_', '', $option);
        $prefix          = ucfirst($component_name);
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__'.$component_name.'_categories')->order('ordering');
        $db->setQuery($query);
        $categories = $db->loadObjectList('id');
        $top_parent = (object)array('id' => 0, 'children' => array());
        $menu = array(0 => $top_parent);
        
        foreach($categories as $id => $category) {
            if(array_key_exists($id, $this->value)) $category->selected = true;
            else  $category->selected = false;
            if(isset($menu[$id])) $category->children = $menu[$id]->children;
            else  $category->children = array();
            $menu[$id] = $category;
            if(!isset($menu[$category->parent_id])) {
                $menu[$category->parent_id] = new stdClass();
                $menu[$category->parent_id]->children = array();
            }
            $menu[$category->parent_id]->children[] = $category;
        }
        $this->menu = $menu;
    }

    function getPath() {
        $path = array();
        $get_path = false;
        $level = false;
        for($k = count($this->tree) - 1; $k >= 0; $k--) {
            if($get_path && ($this->tree[$k]->level < $level || !$level)) {
                $path[] = $this->tree[$k]->id;
                $level = $this->tree[$k]->level;
            }
            if($this->tree[$k]->id == $this->value) {
                $get_path = true;
                $level = $this->tree[$k]->level;
            }
            if($level == 1) $get_path = false;
        }

        return $path;
    }

    function makeCategoriesTree($category, $level = 1) {
        if(isset($category->children) && !empty($category->children)) {
            foreach($category->children as $child) {
                $child->level = $level;
                $child->deeper = false;
                $child->shallower = false;
                $child->level_diff = 0;
                $child->class = array_key_exists($child->id, $this->value) ? ' active' : '';
                if(isset($this->tree[count($this->tree) - 1])) {
                    $this->tree[count($this->tree) - 1]->deeper = ($child->level > $this->tree[count($this->tree) - 1]->level);
                    $this->tree[count($this->tree) - 1]->shallower = ($child->level < $this->tree[count($this->tree) - 1]->level);
                    $this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - $child->level);
                }
                $this->tree[] = $child;
                if(isset($this->tree[count($this->tree) - 1])) {
                    $this->tree[count($this->tree) - 1]->deeper = (1 > $this->tree[count($this->tree) - 1]->level);
                    $this->tree[count($this->tree) - 1]->shallower = (1 < $this->tree[count($this->tree) - 1]->level);
                    $this->tree[count($this->tree) - 1]->level_diff = ($this->tree[count($this->tree) - 1]->level - 1);
                }
                $this->makeCategoriesTree($this->menu[$child->id], $level + 1);
            }
        }
    }
}