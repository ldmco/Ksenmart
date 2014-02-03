jQuery(document).ready(function(){
    jQuery('.js-letters a').on('click', function(e){
        e.preventDefault();
        
        var letter = jQuery(this).data().letter;
        
        jQuery('.js-brands ul').show();
        if(letter != 'all'){
            jQuery('.js-brands ul:not([data-brands-letter="'+letter+'"])').hide();
        }
    });
});