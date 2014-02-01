jQuery(document).ready(function(){
    jQuery('.money-set').on('click', function(){
        var price = jQuery(this).data().moneySet;
        var form  = jQuery(this).parents('form');
        var i_sum = form.find('input[name="sum"]');

        i_sum.val(price);
    });
    
    jQuery('#quick-payment').on('submit', function(e){
        e.preventDefault();
        
        var sum = jQuery(this).find('input[name="sum"]').val();
        if(sum < 10){
            jQuery(this).find('.error-info').fadeIn();
            return false;
        }
        
        jQuery(this).find('.error-info').fadeOut();
        return true;
    });
});