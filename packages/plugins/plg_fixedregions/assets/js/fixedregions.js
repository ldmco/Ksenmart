jQuery(document).ready(function(){

    jQuery(".all-countries").click(function(){
        if (jQuery(this).is(".active"))
        {
            jQuery("#popup-window3 li").each(function(){
                if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
                    removeFixedRegionsCountry(jQuery(this).attr("country_id"));
            });
        }
    });

    jQuery("#popup-window3 li").click(function(){
        if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
            removeFixedRegionsCountry(jQuery(this).attr("country_id"));
    });

    jQuery("body").on("click", ".ksm-slidemodule-countries .countries li i", function(){
        var country_id=jQuery(this).parents("li").attr("country_id");
        removeFixedRegionsCountry(country_id);
    });

    jQuery(".all-regions").click(function(){
        if (jQuery(this).is(".active"))
        {
            jQuery(this).parent().find("li").each(function(){
                if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
                    removeFixedRegionsRegion(jQuery(this).attr("region_id"));
            });
        }
        else
        {
            jQuery(this).parent().find("li").each(function(){
                if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
                    addFixedRegionsRegion(jQuery(this).attr("region_id"));
            });
        }
    });

    jQuery("#popup-window4 .regions-row li").click(function(){
        if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
            removeFixedRegionsRegion(jQuery(this).attr("region_id"));
        else if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
            addFixedRegionsRegion(jQuery(this).attr("region_id"));
    });

    jQuery("body").on("click", ".ksm-slidemodule-regions .regions i", function(){
        var region_id=jQuery(this).parents("li").attr("region_id");
        removeFixedRegionsRegion(region_id);
    });

    function removeFixedRegionsCountry(country_id)
    {
        jQuery(".regions-params-ul li[country_id="+country_id+"]").each(function(){
            removeFixedRegionsRegion(jQuery(this).attr("region_id"));
        });
    }

    function addFixedRegionsRegion(region_id)
    {
        var html="";
        var title=jQuery("#popup-window4 li[region_id="+region_id+"] span").text();
        var country_id=jQuery("#popup-window4 li[region_id="+region_id+"]").attr("country_id");
        html+="<li region_id="+region_id+" country_id="+country_id+">";
        html+="		<div class=\'line\'>";
        html+="			<label class=\'inputname\'>"+title+"</label>";
        html+="			<input style=\'width:100px;\' type=\'text\' class=\'inputbox\' name=\'jform[params]["+region_id+"][cost]\' value=\'0\'>";
        html+="			<span>" + currency_code + "</span>";
        html+="			<label style=\'width:100px;\' class=\'inputname\'>" + KSM_SHIPPINGS_SHIPPING_DELIVERY_FROM + "</label>";
        html+="			<input style=\'width:30px;\' type=\'text\' class=\'inputbox\' name=\'jform[params]["+region_id+"][fromdate]\' value=\'0\'>";
        html+="			<label style=\'width:30px;\' class=\'inputname\'>" + KSM_SHIPPINGS_SHIPPING_DELIVERY_TO + "</label>";
        html+="			<input style=\'width:30px;\' type=\'text\' class=\'inputbox\' name=\'jform[params]["+region_id+"][todate]\' value=\'0\'>";
        html+="			<label style=\'width:40px;\' class=\'inputname\'>" + KSM_SHIPPINGS_SHIPPING_DELIVERY_DAYS + "</label>";
        html+="		</div>";
        html+="		<div class=\'line\'>";
        html+="			<label class=\'inputname\'>"+KSM_SHIPPINGS_FIXEDREGIONS_NEXT_WEIGHT+"</label>";
        html+="			<input style=\'width:100px;\' type=\'text\' class=\'inputbox\' name=\'jform[params]["+region_id+"][weight_cost]\' value=\'0\'>";
        html+="			<span>" + currency_code + "</span>";
        html+="		</div>";
        html+="</li>";
        jQuery(".regions-params-ul .no-regions").hide();
        jQuery(".regions-params-ul").append(html);
    }

    function removeFixedRegionsRegion(region_id)
    {
        jQuery(".regions-params-ul li[region_id="+region_id+"]").remove();
        if (jQuery(".regions-params-ul li").length==1)
            jQuery(".regions-params-ul .no-regions").show();
    }

});