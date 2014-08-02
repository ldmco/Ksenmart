jQuery(document).ready(function(){
    
    var maskList = jQuery.masksSort(jQuery.masksLoad("components/com_ksenmart/js/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    var maskOpts = {
        inputmask: {
            definitions: {
                '#': {
                    validator: "[0-9]",
                    cardinality: 1
                }
            },
            //clearIncomplete: true,
            showMaskOnHover: false,
            autoUnmask: true
        },
        match: /[0-9]/,
        replace: '#',
        list: maskList,
        listKey: "mask",
        onMaskChange: function(maskObj, completed) {
            if (completed) {
                var hint = maskObj.name_ru;
                if (maskObj.desc_ru && maskObj.desc_ru != "") {
                    hint += " (" + maskObj.desc_ru + ")";
                }
                jQuery("#descr").html(hint);
            } else {
                jQuery("#descr").html("Введите номер");
            }
            jQuery(this).attr("placeholder", jQuery(this).inputmask("getemptymask"));
        }
    };

    jQuery('body').on('change', '#phone_mask', function() {
        if (jQuery('#phone_mask').is(':checked')) {
            jQuery('#customer_phone').inputmasks(maskOpts);
        } else {
            jQuery('#customer_phone').inputmask("+[####################]", maskOpts.inputmask)
            .attr("placeholder", jQuery('#customer_phone').inputmask("getemptymask"));
            jQuery("#descr").html("Введите номер");
        }
    });

    jQuery('#phone_mask').change();
	
});