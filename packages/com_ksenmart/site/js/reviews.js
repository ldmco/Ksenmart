jQuery(document).ready(function(){
	
	jQuery('.ksm-reviews-add').on('click', function(e){
		e.preventDefault();
		
        if (jQuery('.ksm-reviews-add-form').is(':visible')) 
		{
            jQuery('.ksm-reviews-add-form').slideUp(500);
        }
		else 
		{
            jQuery('.ksm-reviews-add-form').slideDown(500);
        }		
	});
	
    jQuery('.ksm-reviews-add-form-row-rate img').on('click', function() {
        var review = jQuery(this).parents('.ksm-reviews-add-form');
        var rate = jQuery(this).data().rate;

        review.find('input[name="rate"]').val(rate);
        review.find('.ksm-reviews-add-form-row-rate img').attr('src', URI_ROOT + 'components/com_ksenmart/images/star2-small.png');

        for (var k = 1; k <= rate; k++) 
		{
            review.find('.ksm-reviews-add-form-row-rate img[data-rate="' + k + '"]').attr('src', URI_ROOT + 'components/com_ksenmart/images/star-small.png');
        }
    });	
	
});