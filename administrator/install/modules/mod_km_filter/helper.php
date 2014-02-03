<?php defined('_JEXEC') or die;

class modKsenmartSearchHelper {

    public $price_min = null;
    public $price_max = null;
    public $manufacturers = array();
    public $countries = array();
    public $properties = array();

    function init() {
        $params = JComponentHelper::getParams('com_ksenmart');
        $app    = JFactory::getApplication();
        $db     = JFactory::getDBO();
        $jinput = $app->input;
        $categories             = $jinput->get('categories', array(), 'array');
        $session_manufacturers  = $jinput->get('manufacturers', array(), 'array');
        $session_countries      = $jinput->get('countries', array(), 'array');
        $session_properties     = $jinput->get('properties', array(), 'array');

        $cats = array();
        $manufacturers = array();
        $ids = array();
        foreach ($categories as $cat) {
            $tmp = $this->getChildCats($cat);
            $cats = array_merge($cats, $tmp);
        }
        $where = array(1);
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('min(p.price/c.rate)')->from('#__ksenmart_products as p');
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->join("LEFT", "#__ksenmart_currencies as c on p.price_type=c.id");
        $sql->where(implode(' and ', $where));
        $db->setQuery($sql);
        $this->price_min = $db->loadResult();

        $where = array(1);
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('max(p.price/c.rate)')->from('#__ksenmart_products as p');
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->join("LEFT", "#__ksenmart_currencies as c on p.price_type=c.id");
        $sql->where(implode(' and ', $where));
        $db->setQuery($sql);
        $this->price_max = $db->loadResult();

        $where = array();
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $where[] = "(m.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('m.*')->from('#__ksenmart_manufacturers as m');
        $sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->where(implode(' and ', $where));
        $sql->group('m.id');
        $db->setQuery($sql);
        $this->manufacturers = $db->loadObjectList();
        foreach ($this->manufacturers as &$manufacturer) {
            $manufacturer->selected = false;
            if (in_array($manufacturer->id, $session_manufacturers)) $manufacturer->selected = true;
        }

        $where = array();
        if ($params->get('show_out_stock') != 1) $where[] = "(p.published=1)";
        $where[] = "(m.published=1)";
        $sql = $db->getQuery(true);
        $sql->select('c.*')->from('#__ksenmart_countries as c');
        $sql->join("INNER", "#__ksenmart_manufacturers as m on m.country=c.id");
        $sql->join("INNER", "#__ksenmart_products as p on p.manufacturer=m.id");
        if (count($cats) > 0) {
            $sql->join("INNER", "#__ksenmart_products_categories as pc on p.id=pc.product_id");
            $where[] = "(pc.category_id in (" . implode(',', $cats) . "))";
        }
        $sql->where(implode(' and ', $where));
        $sql->group('c.id');
        $db->setQuery($sql);
        $this->countries = $db->loadObjectList();
        foreach ($this->countries as &$country) {
            $country->selected = false;
            if (in_array($country->id, $session_countries)) $country->selected = true;
        }
        
        $this->properties = KMProducts::getProperties();
        foreach ($this->properties as &$property) {
            if(!empty($property->values)){
                foreach($property->values as &$value) {
                    $value->selected = false;
                    if (in_array($value->id, $session_properties)){
                        $value->selected = true;
                    }
                }
            }
        }
    }

    function getChildCats($catid) {
        $db = JFactory::getDBO();
        $return = array();
        $return1 = array();
        $return[] = $catid;
        $sql = $db->getQuery(true);
        $sql->select('id')->from('#__ksenmart_categories')->where('parent=' . $catid);
        $db->setQuery($sql);
        $cats = $db->loadObjectList();
        if (count($cats) > 0) {
            foreach ($cats as $cat) {
                $return1 = $this->getChildCats($cat->id);
                if (count($return1) > 0) {
                    foreach ($return1 as $r1) $return[] = $r1;
                }
            }
        }
        return $return;
    }

}
