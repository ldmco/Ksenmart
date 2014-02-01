hs.graphicsDir = URI_ROOT+'components/com_ksenmart/js/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
	hs.dimmingOpacity = 0.8;
	hs.wrapperClassName = 'borderless floating-caption';
	hs.captionEval = 'this.thumb.alt';
	hs.marginLeft = 100; // make room for the thumbstrip
	hs.marginBottom = 80 // make room for the controls and the floating caption
	hs.numberPosition = 'caption';
	hs.lang.number = '%1/%2';

	// Add the slideshow providing the controlbar and the thumbstrip
	hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: true,
		overlayOptions: {
			className: 'text-controls',
			position: 'bottom center',
			relativeTo: 'viewport',
			offsetX: 50,
			offsetY: -5

		},
		thumbstrip: {
			position: 'middle left',
			mode: 'vertical',
			relativeTo: 'viewport'
		}
	});

	// Add the simple close button
	hs.registerOverlay({
		html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
		position: 'top right',
		fade: 2 // fading the semi-transparent overlay looks bad in IE
	});