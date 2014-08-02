jQuery(document).ready(function(){

	jQuery('body').on('click','#cart .order_button',function(){
		var payment_id=jQuery('.kmcart-payments input:checked').val();
		var min_orderprice=yescredit_payment_min_orderprice[payment_id]['price'];
		if (min_orderprice != undefined)
		{
			var total_cost=jQuery('#total_cost').val();
			var message=yescredit_payment_min_orderprice[payment_id]['message'];
			if (total_cost<min_orderprice)
			{
				KMShowMessage(message);
				return false;
			}
		}
		
		return true;
	});

});