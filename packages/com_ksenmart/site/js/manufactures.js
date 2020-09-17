jQuery(document).ready(function() {
	
    jQuery('.ksm-brands-pagination a').on('click', function(e) {
        e.preventDefault();

        var letter = jQuery(this).data().letter;
        jQuery(this).parents('ul').children('li').removeClass('active');
        jQuery(this).parent('li').addClass('active');

        jQuery('.ksm-brands-item').show();
        if (letter != 'all') {
            jQuery('.ksm-brands-item:not([data-brands-letter="' + letter + '"])').hide();
        }
    });
	
});