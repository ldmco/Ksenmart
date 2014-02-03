(function($){
	jQuery.fn.postSend = function(options){
        var options = $.extend( {
            'form'      : null
        }, options);

		var make = function(){

			var link 		= jQuery(this);
			var href 		= link.attr('href');
			var attr 		= '';
            var data        = {};
			var callback 	= 'onAjaxSuccess';

			link.on('click', function(e){
				e.preventDefault();
				attr = link.data();

				if('callback' in attr){
					callback = getCallBack(attr)
				}
                
                data = {'param':attr, 'data': jQuery(options.form).serializeArray()};

				$.post(
					href,
					data,
					eval(callback)
				);
			});
		};

		var onAjaxSuccess = function(data){
			jQuery('#infoBlock').html('Ответ из скрипта по ссылке:');
			jQuery('#result').html(data).show();
		}

		var getCallBack = function(attr){
			for (var key in attr) {
				if(key == 'callback'){
					var callback = attr[key];
					delete attr[key];
					return callback;
				}
			}
		}

		return this.each(make);
	};
})(jQuery);