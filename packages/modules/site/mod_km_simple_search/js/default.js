jQuery(document).ready(function () {
    var search_input = jQuery('.ksm-module-search-input');

    search_input.on('keyup', function (e) {
        if (e.keyCode != 38 && e.keyCode != 40) {
            search(jQuery(this));
        }
    });

    function search($this) {
        var value = $this.val();
        var form = $this.closest('.ksm-module-search');
        var module_title = form.data().moduletitle;
        search_length = value.length;
        if (search_length >= 3) {

            var data = {
                option: 'com_ajax',
                module: 'km_simple_search',
                method: 'getProductsList',
                format: 'json',
                value: value,
                module_title: module_title,
                Itemid: Itemid
            };

            jQuery.ajax({
                url: URI_ROOT + 'index.php', data: data, success: function (response) {
                    form.find('.ksm-module-search-result').html(response.data.html);
                    if (response.data.length > 0) {
                        form.find('.ksm-module-search-result').show();
                    } else {
                        form.find('.ksm-module-search-result').hide();
                    }
                }
            });
        } else {
            form.find('.ksm-module-search-result').hide();
        }
    }

    jQuery(document).click(function (event) {
        if (jQuery('.ksm-module-search-result:visible').length > 0) {
            if (!isChild(event.target, document.getElementById(jQuery('.ksm-module-search-result:visible').attr('id')))) jQuery('.ksm-module-search-result').hide();
        }
    });

});