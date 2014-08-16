var CurrenciesRatesModule = '';

jQuery(document).ready(function() {

    CurrenciesRatesModule = new KMListModule({
        'module': 'mod_km_currencies_rates',
        'view': 'currencies'
    });

    jQuery('.mod_km_currencies_rates .save').on('click', function() {
        var data = jQuery('#list-filters').serialize();
        data += '&task=save_list_items&table=currencies';
        jQuery.ajax({
            url: 'index.php?option=com_ksenmart',
            data: data,
            async: false
        });
        return false;
    });

});