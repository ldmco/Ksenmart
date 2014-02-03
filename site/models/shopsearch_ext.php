<?php defined('_JEXEC') or die('=;)');

class KsenMartModelShopSearch extends JModel {
    
    public $_params = null;
    public $_correct_string = null;
    public $_limit_relevant    = null;
    public $_limit_categories  = null;
    public $_limit_manufacture = null;
    public $_limit_products    = null;
    
    public function __construct(){
        
        $ajax_search = JRequest::getVar('ajax_search', 0);
        
        if($ajax_search){
            $module          = JModuleHelper::getModule('ksenmart_simple_search');
            $this->_params   = new JRegistry();
            $this->_params->loadString($module->params);
            
            $this->_limit_relevant      = $this->_params->get('count_relevants', 3);
            $this->_limit_categories    = $this->_params->get('count_categories', 1);
            $this->_limit_manufacture   = $this->_params->get('count_manufactured', 1);
            $this->_limit_products      = $this->_params->get('count_result', 5);
        }else{
            $this->_params = JComponentHelper::getParams('com_ksenmart');
            
            $this->_limit_relevant      = $this->_params->get('count_relevants', 3);
            $this->_limit_categories    = $this->_params->get('count_categories', 1);
            $this->_limit_manufacture   = $this->_params->get('count_manufactured', 1);
            $this->_limit_products      = 0;
        }

        parent::__construct();
    }
    
    private function correctString($string){
        $search = array(
            "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
            "ф","ы","в","а","п","р","о","л","д","ж","э",
            "я","ч","с","м","и","т","ь","б","ю"
        );
        $replace = array(
            "q","w","e","r","t","y","u","i","o","p","[","]",
            "a","s","d","f","g","h","j","k","l",";","'",
            "z","x","c","v","b","n","m",",","."
        );
        
        return str_replace($replace, $search, $string);
    }
    
    public function getProductSearch($value, $correct = false){
        
        if(mb_strlen($value, 'utf-8') >= 4){
            if($correct){
                $value = $this->correctString($value);
                $this->_correct_string = $value;
            }
            $morph_search = str_replace(' ', '% ', $value);
            
            $query = "
                SELECT 
                    p.id, p.title, p.alias, p.price, i.filename, i.folder, pc.category_id,
                    MATCH
                        (p.title) 
                    AGAINST 
                        ('".$morph_search."%' IN BOOLEAN MODE) AS rel 
                FROM 
                    #__ksenmart_products AS p 
                LEFT JOIN
                    #__ksenmart_products_categories AS pc ON p.id = pc.product_id
                LEFT JOIN
                    #__ksenmart_files AS i ON p.id=i.owner_id
                LEFT JOIN
                    #__ksenmart_categories AS c ON pc.category_id=c.id
                WHERE MATCH
                    (p.title) 
                AGAINST 
                    ('".$morph_search."%' IN BOOLEAN MODE)
                GROUP BY
                    p.id
                ORDER BY rel DESC
            ";
        }else{
            $query = $this->_db->getQuery(true);
            $query
                ->select('p.id, p.title, p.alias, p.price, i.filename, i.folder, pc.category_id')
                ->from('#__ksenmart_products AS p')
                ->leftjoin('#__ksenmart_products_categories AS pc ON p.id = pc.product_id')
                ->leftjoin('#__ksenmart_files AS i ON p.id=i.owner_id')
                ->leftjoin('#__ksenmart_categories AS c ON pc.category_id=c.id')
                ->where('p.title LIKE \'%'.$value.'%\' OR p.content LIKE \'%'.$value.'%\' OR p.introcontent LIKE \'%'.$value.'%\' OR c.title LIKE \'%'.$value.'%\' OR p.tag LIKE \'%'.$value.'%\'')
                ->where('p.published=1')
                ->order('p.title ASC')
                ->group('p.id')
            ;
        }

        $this->_db->setQuery($query, 0, $this->_limit_products);
        $results = $this->_db->loadObjectList();
        
        if(!empty($results)){
            return $results;
        }
        
        return false;
    }
    
    public function getProductsObject($p_ids){
        foreach ($p_ids as &$item) {
            $item = KsenmartHelper::getProduct($item->id);
        }
        return $p_ids;
    }
    
    public function setRelevants($value){
        $word_array = explode(' ', $value);
        $word_last = $word_array[count($word_array)-1];
        if(mb_strlen($word_last) < 4){
            unset($word_array[count($word_array)-1]);
            sort($word_array);
        }else{
            $this->setSearchResult($value);
            $this->removeIrrelevantSearches($value);
        }
    }
    
    public function getCountRelevantsResult($value){
        $morph_search   = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                COUNT(p.id) AS count 
            FROM 
                #__ksenmart_products AS p 
            LEFT JOIN
                #__ksenmart_products_categories AS pc ON p.id = pc.product_id
            LEFT JOIN
                #__ksenmart_categories AS c ON pc.category_id=c.id
            WHERE MATCH
                (p.title, p.content, p.introcontent, p.tag, c.title, p.product_code) 
            AGAINST 
                ('".$morph_search."*' IN BOOLEAN MODE)
            GROUP BY
                p.id
        ";
        
        $this->_db->setQuery($query);
        $p_ids = $this->_db->loadObjectList();
        
        return count($p_ids);
    }
    
    public function getProductTotalCategory($id){
        $query  = $this->_db->getQuery(true);
        
        $query
            ->select('COUNT(p.id) AS count')
            ->from('#__ksenmart_products_categories AS pc')
            ->leftjoin('#__ksenmart_products AS p ON pc.product_id=p.id')
            ->where('p.published=1')
            ->where('pc.category_id='.$id)
        ;
        $this->_db->setQuery($query);
        $counts = $this->_db->loadObject();
        
        return $counts->count;
    }
    
    public function getProductTotalManufacture($id){
        $query  = $this->_db->getQuery(true);
        
        $query
            ->select('COUNT(p.id) AS count')
            ->from('#__ksenmart_products AS p')
            ->where('p.published=1')
            ->where('p.manufacturer='.$id)
        ;
        $this->_db->setQuery($query);
        $counts = $this->_db->loadObject();
        
        return $counts->count;
    }
    
    public function getCatSearch($value){
        
        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                c.id AS cat_id, 
                c.title 
            FROM 
                #__ksenmart_categories AS c 
            WHERE 
                c.title 
            LIKE
                '".$value."%'
        ";
        
        $this->_db->setQuery($query, 0, $this->_limit_categories);
        $results = $this->_db->loadObjectList();
        
        if(!empty($results)){
            return $results;
        }
        
        return false;
    }
    
    public function getManufactureSearch($value){

        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                m.id, 
                m.title 
            FROM 
                #__ksenmart_manufacturers AS m 
            WHERE 
                m.title 
            LIKE
                '".$value."%'
        ";
        
        $this->_db->setQuery($query, 0, $this->_limit_manufacture);
        $results = $this->_db->loadObjectList();
        
        if(!empty($results)){
            //return $this->generateRelevantResult($results, $value, 'производитель', './modules/mod_ksenmart_simple_search/images/icon_manufacture.png');
            return $results;
        }
        
        return false;
    }
    

    public function getRelevantSearches($value){

        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                id, title, hit  
            FROM 
                #__ksenmart_searches_query 
            WHERE 
                title 
            LIKE
                '".$value."%'
            GROUP BY 
                hit
            DESC
        ";
        
        $this->_db->setQuery($query, 0, $this->_limit_relevant);
        $results = $this->_db->loadObjectList();
        
        if(!empty($results)){
            //return $this->generateRelevantResult($results, $value, 'частый запрос', './modules/mod_ksenmart_simple_search/images/icon_search.png');;
            return $results;
        }
        
        return false;
    }
    
    private function removeIrrelevantSearches($value){

        $query = $this->_db->getQuery(true);
        
        $value_length = mb_strlen($value);
        for($i=1; $i <= $value_length-1; $i++){
            $title = mb_substr($value, 0, $i*(-1));
            $conditions = array("title='".$title."'");
            
            $query->delete($this->_db->quoteName('#__ksenmart_searches_query'));
            $query->where($conditions);
    
            $this->_db->setQuery($query);

                $result = $this->_db->query();
        } 
    }
    
    private function setSearchResult($value){
        $like_search_id = $this->getLikeSearchId($value);
        
        $query_insert = "
            INSERT INTO 
                #__ksenmart_searches_query 
            SET 
                hit = 1, 
                title='".$value."', 
                id=".$like_search_id." ON DUPLICATE KEY UPDATE hit = hit + 1
        ";

        $this->_db->setQuery($query_insert);
        try{
            $result = $this->_db->query();
        }catch (Exception $e){
            // catch any database errors.
        }
    }
    
    private function getLikeSearchId($value){

        $query = $this->_db->getQuery(true);
        $query
            ->select('id')
            ->from('#__ksenmart_searches_query')
            ->where("title ='".$value."'");
        ;
        
        $this->_db->setQuery($query, 0, 1);
        $results = $this->_db->loadObject();
        
        if($this->_db->getAffectedRows()){
            return $results->id;
        }else{
            return 0;
        }
    }
    
    function setImages($products) {
        $params = JComponentHelper::getParams('com_ksenmart');
        foreach($products as $product){
            $product->small_img = KsenMartHelper::createProductThumb($product->filename, $product->folder, 32, 32);
        }

        return $products;
    }
}