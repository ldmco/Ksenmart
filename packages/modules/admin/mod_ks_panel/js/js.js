(function($){
	$(document).ready(function(){
		var gridStep = 50;
		/*$('#myicons .icon').draggable({
			grid:[ gridStep,gridStep],
			//helper: "ui-resizable-helper",
	        drag: function(event, ui){
	        }, 
	        stop: function(){
	        	$(this).css({
	            	'top': Math.round(($(this).css('top').replace('px', ''))/gridStep)*gridStep,
	            	'left': Math.round(($(this).css('left').replace('px', ''))/gridStep)*gridStep
	            });
	        	saveData();
	        }
	    });*/
		
		$('#myicons .col1 ').sortable({
			connectWith: '#myicons .column',
			stop: function(event, ui){
		            saveData();
			}
		}).disableSelection();
		
		$('#myicons .col2 ').sortable({
			//distance: 10,
			connectWith: '#myicons .column',
			stop:function(event, ui){
	            saveData();
			}
			
		}).disableSelection();
		
		$('#myicons .col3 ').sortable({
			//distance: 10,
			connectWith: '#myicons .column',
			stop: function(event, ui){ 
				saveData();
			}
		}).disableSelection();
		
		$('#myicons .icon').each(function(idx, div){
			$(div).resizable({
				grid: gridStep,
				helper: "ui-resizable-helper",
				//alsoResize: $(this).find('img'),
				stop: function(event, ui){
				    saveData();
				}
				
			});
		});
		
		var mH = 0;
		$('#myicons .column').each(function(idx, el){
			if ($(el).height() > mH) {
				mH = $(el).height();
			}
		});
		$('#myicons .column').css('height', mH+'px');
		
		
		//$('#myicons').disableSelection();
		function prepareData() {
			var selectors = [ 'width', 'height'];
			var data = {};
			$('#myicons .icon').each(function(idi, icon){
				idi = $(icon).attr('id').replace('kmicon', '');
				var pindex = 'data['+idi+']';
				data[pindex + '[column]'] = $(icon).parent().attr('id').replace('myicons_column_', '');
				//console.log($(icon).parent());
				data[pindex + '[ordering]'] = idi;
				$.each(selectors, function(ids, s){
					data[pindex+'['+s+']'] = $(icon).css(s).replace('px', '');
				});
			});
			return data;
		}
		
		function saveData(){
			
			$.ajax({
				  type: "POST",
				  url: "index.php?option=com_ksenmart&task=panel.save_cpanel_modules",
				  data: prepareData()
				}).done(function( text ) {
				  //alert( tetx );
					if (console && console.log){
						console.log(text);
					}
					
				});
		}
	});
	
})(jQuery);