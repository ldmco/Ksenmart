var maskList, maskOpts;

jQuery(document).ready(function(){
    
    maskList = jQuery.masksSort(jQuery.masksLoad(URI_ROOT + "components/com_ksenmart/js/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    maskOpts = {
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
            }
            jQuery(this).attr("placeholder", jQuery(this).inputmask("getemptymask"));
        }
    };

    jQuery('.ksm-preorder #customer_phone').inputmasks(maskOpts);
	
	jQuery('.ksm-preorder-to-catalog').on('click', function(){
		window.parent.KMClosePopupWindow();
	});
	
});