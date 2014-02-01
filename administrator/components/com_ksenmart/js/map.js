var KsenmartMap = { 

	options:{
		height:600,
		width:800,
		header_height:50,
		actions_height:50,
		inner_padding:15,
		map_frame:'ksenmart-map',
		map_frame_inner:'ksenmart-map-inner',
		map_header:'ksenmart-map-header',
		map_actions:'ksenmart-map-actions',
		map_layer:'ksenmart-map-layer',
		map_address:'ksenmart-map-to',
		map_center:'ksenmart-map-to-center',
		map_area:'ksenmart-map-to-area',
		map_me:'ksenmart-map-to-me',
		map_ok:'ksenmart-map-ok',
		map_clear:'ksenmart-map-clear',	
		map_search:'ksenmart-map-search',		
		ymap:'',
		ymap_center:[55.76, 37.64],
		ymap_zoom:10,
		ymap_type:'yandex#map',
		ymap_point:false,
		address_field:'toid',
		coords_field:'shipping_coords',
		changable:true
	},
	
	init:function(){
		this.initMapSize();
		this.initMap();
		this.initEvents();
		this.initPoint();
	},
	
	initMapSize:function(){
		var frame_height=this.options.height;
		var frame_width=this.options.width;
		var frame_header_height=this.options.header_height;
		var frame_actions_height=this.options.actions_height;
		var frame_inner_padding=this.options.inner_padding;
		var frame_top=Math.round((window.innerHeight-frame_height)/2);
		var frame_margin_left=Math.round(-frame_width/2);
		var frame_inner_height=frame_height-frame_header_height-frame_inner_padding*2;	
		var frame_inner_width=frame_width-frame_inner_padding*2;	
		var frame_layer_height=frame_inner_height-frame_actions_height;
		document.getElementById(this.options.map_frame).style.height=frame_height+'px';
		document.getElementById(this.options.map_frame).style.width=frame_width+'px';
		document.getElementById(this.options.map_frame).style.top=frame_top+'px';
		document.getElementById(this.options.map_frame).style.marginLeft=frame_margin_left+'px';	
		document.getElementById(this.options.map_frame_inner).style.height=frame_inner_height+'px';	
		document.getElementById(this.options.map_layer).style.height=frame_layer_height+'px';	
		document.getElementById(this.options.map_layer).style.width=frame_inner_width+'px';		
	},
	
	initMap:function(){
		this.options.ymap=new ymaps.Map(this.options.map_layer,{center:this.options.ymap_center,zoom:this.options.ymap_zoom,type:this.options.ymap_type,behaviors: ['default', 'scrollZoom']});
		this.options.ymap.controls.add('zoomControl');
		this.options.ymap.controls.add('typeSelector');
	},
	
	initEvents:function(){
		if (document.getElementById(this.options.map_center))
		{
			document.getElementById(this.options.map_center).onclick=function(){	
				KsenmartMap.options.ymap.setCenter(KsenmartMap.options.ymap_center, 10);
				return false;
			}
		}
		if (document.getElementById(this.options.map_area))
		{		
			document.getElementById(this.options.map_area).onclick=function(){	
				KsenmartMap.options.ymap.setCenter(KsenmartMap.options.ymap_center, 7);
				return false;
			}	
		}
		if (document.getElementById(this.options.map_me))
		{		
			document.getElementById(this.options.map_me).onclick=function(){	
				navigator.geolocation.getCurrentPosition(function(position) {
					KsenmartMap.setPointCoords([position.coords.latitude,position.coords.longitude]);
				});			
				return false;
			}
		}
		if (document.getElementById(this.options.map_ok))
		{		
			document.getElementById(this.options.map_ok).onclick=function(){	
				var address=document.getElementById(KsenmartMap.options.map_address).value;
				KsenmartMap.setPointAddress(address);
				jQuery('#ksenmart-map').modal('hide');
				return false;
			}
		}
		if (document.getElementById(this.options.map_clear))
		{		
			document.getElementById(this.options.map_clear).onclick=function(){	
				KsenmartMap.removePoint();
				return false;
			}
		}
		if (document.getElementById(this.options.map_address))
		{		
			document.getElementById(this.options.map_address).onkeyup=function(event){
				if (event.keyCode=='13')
				{
					var address=this.value;
					document.getElementById(KsenmartMap.options.map_search).style.display='none';
					KsenmartMap.setPointAddress(address);
					return false;			
				}
				if (event.keyCode=='38')
				{
					var search_div=document.getElementById(KsenmartMap.options.map_search);
					var items=search_div.getElementsByTagName('li');			
					if (items.length>0)
					{
						var selected=0;
						for(var k=0;k<items.length;k++)
						{
							if (items[k].className=='ksenmart-map-search-item-active')
							{
								items[k].className='ksenmart-map-search-item';
								selected=k;
							}	
						}
						selected--;
						if (selected>-1)
						{
							items[selected].className='ksenmart-map-search-item-active';
							document.getElementById(KsenmartMap.options.map_address).value=items[selected].innerHTML;
						}	
					}				
					return false;
				}
				if (event.keyCode=='40')
				{
					var search_div=document.getElementById(KsenmartMap.options.map_search);
					var items=search_div.getElementsByTagName('li');
					if (items.length>0)
					{
						var selected=-1;
						for(var k=0;k<items.length;k++)
						{
							if (items[k].className=='ksenmart-map-search-item-active')
							{
								if (k+1<items.length)
									items[k].className='ksenmart-map-search-item';
								selected=k;
							}	
						}
						selected++;
						if (selected<items.length)
						{
							items[selected].className='ksenmart-map-search-item-active';
							document.getElementById(KsenmartMap.options.map_address).value=items[selected].innerHTML;
						}	
					}		
					return false;
				}			
				document.getElementById(KsenmartMap.options.map_search).innerHTML='';
				document.getElementById(KsenmartMap.options.map_search).style.display='block';
				ymaps.geocode(this.value, { results: 5 }).then(function (res) {
					var list=document.createElement('ul');
					res.geoObjects.each(function (obj) {
						var item=document.createElement('li');
						item.className='ksenmart-map-search-item';
						item.innerHTML=obj.properties.get('description')+', '+obj.properties.get('name');
						list.appendChild(item);
					});		
					document.getElementById(KsenmartMap.options.map_search).appendChild(list);				
				}, function (err) {
				});
			}

			document.onclick=function(event){
				if (event.target.className=='ksenmart-map-search-item' || event.target.className=='ksenmart-map-search-item-active')
				{
					KsenmartMap.setPointAddress(event.target.innerHTML);
				}
				document.getElementById(KsenmartMap.options.map_search).style.display='none';			
			}
		}
		
		this.options.ymap.events.add('click', function (event) {
			var coords = event.get('coordPosition');
			KsenmartMap.setPointCoords(coords);
		});		
	},

	initPoint:function(){
		var address=this.getAddress();
		var coords=document.getElementById(KsenmartMap.options.coords_field).value;
		if (coords!='')
			this.setPointCoords(coords);
		else if (address!='')	
			this.setPointAddress(address);
	},
	
	setPointAddress:function(address){
		ymaps.geocode(address).then(function (res) {
			var firstGeoObject = res.geoObjects.get(0);
			if (firstGeoObject)
			{			
				KsenmartMap.removePoint();
				address=firstGeoObject.properties.get('description')+', '+firstGeoObject.properties.get('name');
				KsenmartMap.options.ymap_point=new ymaps.Placemark(
					firstGeoObject.geometry.getCoordinates(),
					{},
					{
						preset: 'twirl#redStretchyIcon',
						draggable: true
					}
				);
				document.getElementById(KsenmartMap.options.coords_field).value=firstGeoObject.geometry.getCoordinates();
                if (document.getElementById(KsenmartMap.options.map_address))
					document.getElementById(KsenmartMap.options.map_address).value=address;
				if (typeof KMSetSessionVariable == "function") 
					KMSetSessionVariable('shipping_coords',firstGeoObject.geometry.getCoordinates());
				KsenmartMap.options.ymap_point.events.add('dragend', function (event) {
					var coords=KsenmartMap.options.ymap_point.geometry.getCoordinates();
					KsenmartMap.setPointCoords(coords);
				});
				KsenmartMap.options.ymap.geoObjects.add(KsenmartMap.options.ymap_point);
				KsenmartMap.centerPoint();
				KsenmartMap.afterSetPoint();
			}	
		}, function (err) {
		});
	},
	
	setPointCoords:function(coords){
		ymaps.geocode(coords).then(function (res) {
			var firstGeoObject = res.geoObjects.get(0);
			if (firstGeoObject)
			{
				KsenmartMap.removePoint();
				var address=firstGeoObject.properties.get('description')+', '+firstGeoObject.properties.get('name');
				KsenmartMap.options.ymap_point=new ymaps.Placemark(
					firstGeoObject.geometry.getCoordinates(),
					{},
					{
						preset: 'twirl#redStretchyIcon',
						draggable: true
					}
				);
				document.getElementById(KsenmartMap.options.coords_field).value=firstGeoObject.geometry.getCoordinates();
				if (document.getElementById(KsenmartMap.options.map_address))
					document.getElementById(KsenmartMap.options.map_address).value=address;
				if (typeof KMSetSessionVariable == "function") 
					KMSetSessionVariable('shipping_coords',firstGeoObject.geometry.getCoordinates());
				KsenmartMap.options.ymap_point.events.add('dragend', function (event) {
					var coords=KsenmartMap.options.ymap_point.geometry.getCoordinates();
					KsenmartMap.setPointCoords(coords);
				});			
				KsenmartMap.options.ymap.geoObjects.add(KsenmartMap.options.ymap_point);
				KsenmartMap.centerPoint();
				KsenmartMap.options.changable = false;
				KsenmartMap.afterSetPoint();
			}	
		}, function (err) {
		});
	},
	
	centerPoint:function(){
		if (this.options.ymap_point!=false)
			this.options.ymap.panTo(this.options.ymap_point.geometry.getCoordinates(), {flying: true,duration:300});
	},
	
	removePoint:function(){
		if (this.options.ymap_point!=false)
			this.options.ymap.geoObjects.remove(this.options.ymap_point);
		this.options.ymap_point=false;
		document.getElementById(KsenmartMap.options.coords_field).value='';
		if (document.getElementById(KsenmartMap.options.map_address))
			document.getElementById(KsenmartMap.options.map_address).value='';		
	},
	
	getAddress:function(){
		var address = '';
		var region  = document.getElementById('region_id');
        var city    = document.getElementById('address_city');
		var street  = document.getElementById('address_street');
		var house   = document.getElementById('address_house');

        address    += region && region.value!=''?jQuery('#region_id option:selected').text()+',':'';
        address    += city && city.value!=''?city.value+',':'';
		address    += street && street.value!=''?street.value+',':'';
		address    += house && house.value!=''?house.value+',':'';
        
		return address;
	},
	
	afterSetPoint:function(){}
}