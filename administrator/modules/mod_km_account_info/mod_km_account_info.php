<script>
    var root = '<?php echo JURI::base(); ?>';
</script>
<?php defined('_JEXEC') or die;

    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'modules/mod_km_account_info/css/default.css');
    $document->addStyleSheet(JURI::base().'modules/mod_km_account_info/css/jquery.Jcrop.min.css');
    
    $document->addScript(JURI::base() . 'modules/mod_km_account_info/js/jquery.Jcrop.min.js', 'text/javascript', true);
    $document->addScript(JURI::base() . 'modules/mod_km_account_info/js/default.js', 'text/javascript', true);
    
    $account     = KMSystem::getController('account');
    if(!$account->checkAuthorize()){
        require_once dirname(__FILE__).'/tmpl/login.php';
    }else{
        $account_info = $account->getAccountInfo();
        require_once dirname(__FILE__).'/tmpl/default.php';
    }
?>