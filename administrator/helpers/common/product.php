<?php

defined('_JEXEC') or die;

class KMProducts {

    private static function setProductMainImageToQuery($query) {
        $query->select('(select f.filename from #__ksenmart_files as f where f.owner_id=p.id and owner_type="product" and media_type="image" order by ordering limit 1) as filename');
        $query->select('(select f.folder from #__ksenmart_files as f where f.owner_id=p.id and owner_type="product" and media_type="image" order by ordering limit 1) as folder');
        $query->select('(select f.params from #__ksenmart_files as f where f.owner_id=p.id and owner_type="product" and media_type="image" order by ordering limit 1) as params');
        return $query;
    }

    private static $_property_values = array();
    private static $_images = array();
    private static $_images_products_files = array();

    public static function getProduct($id) {
        $db     = JFactory::getDBO();
        $params = JComponentHelper::getParams('com_ksenmart');
        $query  = $db->getQuery(true);
        $query->select('p.*,m.title as manufacturer_title,u.form1 as unit')->from('#__ksenmart_products as p');
        $query->join("LEFT", "#__ksenmart_manufacturers as m on p.manufacturer=m.id");
        $query->join("LEFT", "#__ksenmart_product_units as u on p.product_unit=u.id");
        $query->where('p.id=' . $id);
        $query->group('p.id');
        $query = KMProducts::setProductMainImageToQuery($query);

        $db->setQuery($query);
        $row = $db->loadObject();

        if($row && !empty($row)) {
            $row->properties = KMProducts::getProperties($id);

            if(empty($row->folder)) {
                $row->folder = 'products';
            }
            if(empty($row->filename)) {
                $row->filename = 'no.jpg';
            }
            $query = $db->getQuery(true);
            $query->select('count(id)')->from('#__ksenmart_products');
            $query->where('parent_id=' . $row->id);
            $db->setQuery($query);
            $row->is_parent = $db->loadResult();

            if($row->product_packaging == 0) {
                $row->product_packaging = 1;
            }
            $row->product_packaging=rtrim(rtrim($row->product_packaging,'0'),'.');

            $row->link  = JRoute::_('index.php?option=com_ksenmart&view=shopproduct&id=' . $row->id . ':' . $row->alias.'&Itemid='.KMSystem::getShopItemid());
            $row->price = KMPrice::getPriceInDefaultCurrency($row->price, $row->price_type);
            $row->val_price_wou = KMPrice::getPriceWithDiscount($row->price, 2);
            if($row->val_price_wou < 0) {
                $row->val_price_wou = 0;
            }
            $row->val_price = KMPrice::showPriceWithTransform($row->val_price_wou);
            $row->old_price = KMPrice::getPriceInDefaultCurrency($row->old_price, $row->price_type);
            $row->val_old_price_wou = KMPrice::getPriceWithDiscount($row->old_price, 2);

            if($row->val_old_price_wou < 0) {
                $row->val_old_price_wou = 0;
            }
            $row->val_old_price      = KMPrice::showPriceWithTransform($row->val_old_price_wou);
            $row->val_diff_price_wou = $row->val_old_price_wou - $row->val_price_wou;
            $row->val_diff_price     = KMPrice::showPriceWithTransform($row->val_diff_price_wou);
            $row->mini_small_img     = KMMedia::resizeImage($row->filename, $row->folder, $params->get('mini_thumb_width'), $params->get('mini_thumb_height'));
            $row->small_img          = KMMedia::resizeImage($row->filename, $row->folder, $params->get('thumb_width'), $params->get('thumb_height'));
            $row->img                = KMMedia::resizeImage($row->filename, $row->folder, $params->get('middle_width'), $params->get('middle_height'));
            $row->rate               = KMProducts::getProductRate($row->id);

            if(!empty($row->folder)) {
                $row->img_link = JURI::root() . 'media/ksenmart/images/' . $row->folder . '/original/' . $row->filename;
            } else {
                $row->img_link = JURI::root() . 'media/ksenmart/images/products/no.jpg';
            }
            $row->add_link_cart = KMFunctions::getAddToCartLink($row->price, 2);
        }
        return $row;
    }
    
    public function generateProductLink($pid){
        if(!empty($pid) && $pid > 0){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('
                    p.id,
                    p.alias
                ')->from('#__ksenmart_products AS p')
                ->where('p.id=' . $db->getEscaped($pid))
            ;

            $db->setQuery($query);
            $product = $db->loadObject();
            if(!empty($product)){
                return JRoute::_('index.php?option=com_ksenmart&view=shopproduct&id=' . $product->id . ':' . $product->alias.'&Itemid='.KMSystem::getShopItemid());
            }
        }
        return null;
    }

    public static function getProductPrices($pid) {
        if(!empty($pid) && $pid > 0) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('
                    p.id,
                    p.price,
                    p.old_price,
                    p.price_type
                ')->from('#__ksenmart_products AS p')
                ->where('p.id=' . $db->getEscaped($pid))
            ;

            $db->setQuery($query);
            return $db->loadObject();
        }
        return false;
    }

    public static function getProperties($pid = 0, $prid = 0, $val_id = 0, $by = 'ppv.product_id', $by_sort = 0) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                p.edit_price,
                p.title,
                p.type,
                p.view,
                p.default,
                p.prefix,
                p.suffix
            ')
            ->from('#__ksenmart_properties AS p')
            ->leftjoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id')
        ;
        if($pid) {
            $query->where('ppv.product_id=' . $pid);
        }

        if($by_sort) {
            switch($by) {
                case 'ppv.id':
                    $query->where('ppv.id=' . $by_sort);
                    break;
                default:
                    $query->where('ppv.product_id=' . $pid);
                    break;
            }
        }
        $query->where('p.published=1')->group('ppv.property_id');

        if($prid) {
            $query->where('ppv.property_id=' . $prid);
        }

        $query->order('p.ordering');
        $db->setQuery($query);
        $properties = $db->loadObjectList();
        $properties = KMProducts::getPropertiesChild($pid, $properties, $val_id);
        return $properties;
    }

    public static function getPropertiesChild($pid, $properties, $val_id) {
        if(!empty($properties)) {
            $where = array();
            $properties_l = count($properties) - 1;
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('
                    pv.id,
                    pv.title,
                    pv.image,
                    pv.property_id,
                    ppv.price
                ')
                ->from('#__ksenmart_property_values AS pv')
                ->leftjoin('#__ksenmart_product_properties_values AS ppv ON ppv.value_id=pv.id')
            ;

            $query->order('pv.ordering');
            $query->group('pv.id');
            
            if($pid) {
                $query->where('ppv.product_id=' . $pid);
            }

            if($val_id) {
                $query->where('pv.id=' . $val_id);
                //$query->where('ppv.value_id=' . $val_id);
            }

            $db->setQuery($query);
            $values = $db->loadObjectList();

            $values_l = count($values);


            for($i = 0; $i < $values_l; $i++) {
                for($j = 0; $j <= $properties_l; $j++) {
                    if($values[$i]->property_id == $properties[$j]->property_id) {
                        $properties[$j]->values[$values[$i]->id] = $values[$i];
                    }
                    continue;
                }
            }
            return $properties;
        }
        return $properties;
    }

    public static function getProductRate($id) {
        $db = JFactory::getDBO();
        $rate = new stdClass();
        $rate->rate = 0;
        $rate->count = 0;
        $query = $db->getQuery(true);
        $query->select('c.rate')->from('#__ksenmart_comments AS c')->where('c.product_id=' . $db->getEscaped($id));
        $db->setQuery($query);
        $comments = $db->loadObjectList();
        $rate->count = count($comments);
        if(!empty($comments)) {
            foreach($comments as $comment) {
                $rate->rate += $comment->rate;
            }
            $rate->rate = $rate->rate / $rate->count;
        }
        return $rate;
    }

    public static function getProductManufacturer($id) {
        $params = JComponentHelper::getParams('com_ksenmart');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('m.*,f.filename,f.folder')->from('#__ksenmart_manufacturers as m');
        $query->join("LEFT", "#__ksenmart_files as f on m.id=f.owner_id and f.owner_type='manufacturer'");
        $query->where('m.id=' . $id);
        $db->setQuery($query);
        $row = $db->loadObject();
        if(count($row) > 0) {
            if($row->filename != '') $row->img = JURI::root() . 'media/ksenmart/images/' . $row->folder . '/original/' . $row->filename;
            $query = $db->getQuery(true);
            $query->select('*')->from('#__ksenmart_countries');
            $query->where('id=' . $row->country);
            $db->setQuery($query);
            $row->country = $db->loadObject();
        }
        return $row;
    }

    public static function incProductHit($id) {
        $db = JFactory::getDBO();
        $query = "update #__ksenmart_products set hits=hits+1 where id='$id'";
        $db->setQuery($query);
        $db->query();
    }

    public static function getPriceWithProperties($product_id, $properties = array(), $price = null) {
        $db = JFactory::getDBO();
        if(empty($price)) {
            $product = KMSystem::loadDbItem($product_id, 'products');
            $price = KMPrice::getPriceInDefaultCurrency($product->price, $product->price_type);
        }
        foreach($properties as $property_id => $values) {
            $query = $db->getQuery(true);
            $query->select('edit_price')->from('#__ksenmart_properties');
            $query->where('id=' . $property_id);
            $db->setQuery($query);
            $edit_price = $db->loadResult();
            if($edit_price == 1) {
                foreach($values as $value_id) {
                    $query = $db->getQuery(true);
                    $query->select('price')->from('#__ksenmart_product_properties_values');
                    $query->where('property_id=' . $property_id)->where('value_id=' . $value_id);
                    $db->setQuery($query);
                    $under_price = $db->loadResult();
                    if($under_price && !empty($under_price)) {
                        $under_price_act = substr($under_price, 0, 1);
                        switch($under_price_act) {
                            case '+':
                                $price += substr($under_price, 1, strlen($under_price) - 1);
                                break;
                            case '-':
                                $price -= substr($under_price, 1, strlen($under_price) - 1);
                                break;
                            case '/':
                                $price = $price / substr($under_price, 1, strlen($under_price) - 1);
                                break;
                            case '*':
                                $price = $price * substr($under_price, 1, strlen($under_price) - 1);
                                break;
                            default:
                                $price += $under_price;
                        }
                    }
                }
            }
        }
        return $price;
    }
    
    public static function getSetRelated($pid, $info_generate = false) {
        $rows = new stdClass;
        if(!empty($pid) && $pid > 0){
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query
                ->select('
                    pr.id,
                    pr.product_id,
                    pr.relative_id,
                    pr.relation_type
                ')
                ->from('#__ksenmart_products_relations AS pr')
                ->where('pr.relation_type="set"')
                ->where('pr.product_id=' . $db->getEscaped($pid))
            ;
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if(!empty($rows)){
                if($info_generate){
                    foreach($rows as &$row){
                        $row = KMProducts::getProduct($row->relative_id);
                    }
                }
            }
        }
        return $rows;
    }
    
    public static function getRelated($pid) {
        $rows = new stdClass;
        if(!empty($pid) && $pid > 0){
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query
                ->select('
                    pr.id,
                    pr.product_id,
                    pr.relative_id,
                    pr.relation_type
                ')
                ->from('#__ksenmart_products_relations AS pr')
                ->where('pr.relation_type="relation"')
                ->where('pr.product_id=' . $db->getEscaped($pid))
            ;
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if(!empty($rows)){
                foreach ($rows as &$row) {
                    $row = KMProducts::getProduct($row->relative_id);
                }
            }
        }
        return $rows;
    }
    
    public static function getSetRelatedIds($pid) {
        $rows = array();
        if(!empty($pid) && $pid > 0){
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query
                ->select('
                    pr.id
                ')
                ->from('#__ksenmart_products_relations AS pr')
                ->where('pr.relation_type="set"')
                ->where('pr.product_id=' . $db->getEscaped($pid))
            ;
            $db->setQuery($query);
            $rows = $db->loadResultArray ();
        }
        return $rows;
    }
}