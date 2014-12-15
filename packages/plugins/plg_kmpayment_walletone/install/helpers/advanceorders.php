<?php defined('_JEXEC') or die;

KSSystem::import('helpers.mainhelper');
class KSMAdvanceOrders extends KSMainhelper {

	private static $_params      = null;
	private static $_states_type = [];

	public static function getAdnvenceProduct($aoid, $conditions = []){
		$product = new stdClass;
		if($aoid > 0){
			
			$table      = JTable::getInstance('AdvanceOrdersProducts', 'KsenMartTable');
			$conditions = array_merge($conditions, ['id' => $aoid]);

			$table->load($conditions);
			$product = $table->getProperties();
			$product = JArrayHelper::toObject($product);
			
			if($table->getDBO()->getAffectedRows()){
	            
		        $product->price     = KSMPrice::getPriceInCurrentCurrency($product->price, $product->price_type);
		        $product->val_price = KSMPrice::showPriceWithTransform($product->price);

	            $product->mini_small_img = KSMedia::resizeImage($product->filename, 'products', self::getParams()->get('mini_thumb_width'), self::getParams()->get('mini_thumb_height'), []);
	            $product->small_img      = KSMedia::resizeImage($product->filename, 'products', self::getParams()->get('thumb_width'), self::getParams()->get('thumb_height'), []);
	            $product->img            = KSMedia::resizeImage($product->filename, 'products', self::getParams()->get('middle_width'), self::getParams()->get('middle_height'), []);
            	$product->state          = self::getProductState($product->state);

				return $product;
			}
		}
		return false;
	}

	public static function getParams(){
		if(!self::$_params){
			self::$_params = JComponentHelper::getParams('com_ksenmart');
		}

		return self::$_params;
	}

    public static function getProductState($state){
    	if(!self::$_states_type){
    		self::_setProductStates();
    	}
    	if(!isset(self::$_states_type[$state])){
    		return $state;
    	}
        return self::$_states_type[$state];
    }

    private static function _setProductStates(){
		return self::$_states_type = [
	        '0' => JText::_('PLG_REQUESTS_PRODUCT_ADVANCE_STATUS_DISABLE'),
	        '1' => JText::_('PLG_REQUESTS_PRODUCT_ADVANCE_STATUS_ACTIVE'),
	        '-1' => JText::_('PLG_REQUESTS_PRODUCT_ADVANCE_STATUS_MODERATION'),
	    ];
    }
}