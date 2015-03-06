
;(function($){var h=$.scrollTo=function(a,b,c){jQuery(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return jQuery(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=jQuery(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=jQuery(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=jQuery(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!jQuery(a).is('html,body'))return a[scroll]-jQuery(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

;(function(a){var b=a.serialScroll=function(c){return a(window).serialScroll(c)};b.defaults={duration:1e3,axis:"x",event:"click",start:0,step:1,lock:!0,cycle:!0,constant:!0};a.fn.serialScroll=function(c){return this.each(function(){var t=a.extend({},b.defaults,c),s=t.event,i=t.step,r=t.lazy,e=t.target?this:document,u=a(t.target||this,e),p=u[0],m=t.items,h=t.start,g=t.interval,k=t.navigation,l;if(!r){m=d()}if(t.force){f({},h)}a(t.prev||[],e).bind(s,-i,q);a(t.next||[],e).bind(s,i,q);if(!p.ssbound){u.bind("prev.serialScroll",-i,q).bind("next.serialScroll",i,q).bind("goto.serialScroll",f)}if(g){u.bind("start.serialScroll",function(v){if(!g){o();g=!0;n()}}).bind("stop.serialScroll",function(){o();g=!1})}u.bind("notify.serialScroll",function(x,w){var v=j(w);if(v>-1){h=v}});p.ssbound=!0;if(t.jump){(r?u:d()).bind(s,function(v){f(v,j(v.target))})}if(k){k=a(k,e).bind(s,function(v){v.data=Math.round(d().length/k.length)*k.index(this);f(v,this)})}function q(v){v.data+=h;f(v,this)}function f(B,z){if(!isNaN(z)){B.data=z;z=p}var C=B.data,v,D=B.type,A=t.exclude?d().slice(0,-t.exclude):d(),y=A.length,w=A[C],x=t.duration;if(D){B.preventDefault()}if(g){o();l=setTimeout(n,t.interval)}if(!w){v=C<0?0:y-1;if(h!=v){C=v}else{if(!t.cycle){return}else{C=y-v-1}}w=A[C]}if(!w||t.lock&&u.is(":animated")||D&&t.onBefore&&t.onBefore(B,w,u,d(),C)===!1){return}if(t.stop){u.queue("fx",[]).stop()}if(t.constant){x=Math.abs(x/i*(h-C))}u.scrollTo(w,x,t).trigger("notify.serialScroll",[C])}function n(){u.trigger("next.serialScroll")}function o(){clearTimeout(l)}function d(){return a(m,p)}function j(w){if(!isNaN(w)){return w}var x=d(),v;while((v=x.index(w))==-1&&w!=p){w=w.parentNode}return v}})}})(jQuery);


// Serialscroll exclude option bug ?
function serialScrollFixLock(event, targeted, scrolled, items, position)
{
	serialScrollNbImages = jQuery('#thumbs_list li:visible').length;
	serialScrollNbImagesDisplayed = 4;

	var leftArrow = position == 0 ? true : false;
	var rightArrow = position + serialScrollNbImagesDisplayed >= serialScrollNbImages ? true : false;

	jQuery('#view_scroll_left').css('cursor', leftArrow ? 'default' : 'pointer').fadeTo(0, leftArrow ? 0 : 1).css('display', leftArrow ? 'none' : 'block');
	jQuery('#view_scroll_right').css('cursor', rightArrow ? 'default' : 'pointer').fadeTo(0, rightArrow ? 0 : 1).css('display', rightArrow ? 'none' : 'block');
	return true;
}

// Change the current product images regarding the combination selected
function refreshProductImages(id_product_attribute)
{
	jQuery('#thumbs_list_frame').scrollTo('li:eq(0)', 700, {axis:'x'});

	id_product_attribute = parseInt(id_product_attribute);

	if (id_product_attribute > 0 && typeof(combinationImages) != 'undefined' && typeof(combinationImages[id_product_attribute]) != 'undefined')
	{
		jQuery('#thumbs_list li').hide();
		jQuery('#thumbs_list').trigger('goto', 0);
		for (var i = 0; i < combinationImages[id_product_attribute].length; i++)
			if (typeof(jqZoomEnabled) != 'undefined' && jqZoomEnabled) {
				jQuery('#thumbnail_' + parseInt(combinationImages[id_product_attribute][i])).show().children('a.shown').trigger("click");
			}
			else {
				jQuery('#thumbnail_' + parseInt(combinationImages[id_product_attribute][i])).show();
			}
		if (parseInt(jQuery('#thumbs_list_frame >li:visible').length) < parseInt(jQuery('#thumbs_list_frame >li').length))
			jQuery('#wrapResetImages').show('slow');
		else
			jQuery('#wrapResetImages').hide('slow');
	}
	else
	{
		jQuery('#thumbs_list li').show();
		if (parseInt(jQuery('#thumbs_list_frame >li').length) == parseInt(jQuery('#thumbs_list_frame >li:visible').length))
			jQuery('#wrapResetImages').hide('slow');
	}

	var thumb_width = jQuery('#thumbs_list_frame >li').width() + parseInt(jQuery('#thumbs_list_frame >li').css('marginRight'));
	jQuery('#thumbs_list_frame').width((parseInt((thumb_width) * jQuery('#thumbs_list_frame >li').length)) + 'px');
	jQuery('#thumbs_list').trigger('goto', 0);
	serialScrollFixLock('', '', '', '', 0);// SerialScroll Bug on goto 0 ?
}

//To do after loading HTML
jQuery(document).ready(function()
{
	//init the serialScroll for thumbs
	jQuery('#thumbs_list').serialScroll({
		items:'li:visible',
		prev:'#view_scroll_left',
		next:'#view_scroll_right',
		axis:'x',
		offset:0,
		start:0,
		stop:true,
		onBefore:serialScrollFixLock,
		duration:700,
		step: 1,
		lazy: true,
		lock: false,
		force:false,
		cycle:false
	});

	jQuery('#thumbs_list').trigger('goto', 1);// SerialScroll Bug on goto 0 ?
	jQuery('#thumbs_list').trigger('goto', 0);
if (typeof(jqZoomEnabled) != 'undefined' && jqZoomEnabled == false) {

	//hover 'other views' images management
	jQuery('#views_block li a').hover(
		function(){displayImage(jQuery(this));},
		function(){}
	);
}

	jQuery('#resetImages').click(function() {
		refreshProductImages(0);
	});

});
