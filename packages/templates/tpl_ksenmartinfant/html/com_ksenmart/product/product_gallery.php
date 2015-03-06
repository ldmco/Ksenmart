<?php defined('_JEXEC') or die; ?>
<!-- product img-->
<div id="image-block">
	<span id="view_full_size">
		<div id="wrap" class="clearfix" style="position:relative;">
			<a href='<?php echo $this->product->img_link; ?>' class="cloud-zoom clearfix" id='zoom1' rel="position: 'inside' , showTitle: false, adjustX:0, adjustY:0">
				<img id="mousetrap_img" alt="<?php echo $this->product->title; ?>" width="106" height="106" title="<?php echo $this->product->title; ?>" src="<?php echo $this->product->img; ?>" rel="<?php echo $this->product->img; ?>">
				<img id="bigpic" src="<?php echo $this->product->img; ?>" alt="" title="<?php echo $this->product->title; ?>" />
				<span class="mask"></span>
			</a>
		</div>
	 </span>
</div>
<!-- thumbnails -->
<?php if(count($this->images) > 1){ ?>
<div id="views_block" class=" ">
	<a id="view_scroll_left" title="" href="javascript:{}" style="cursor: default; opacity: 0; display: none;">Previous</a>
	<div id="thumbs_list">
		<ul id="thumbs_list_frame" style="width: 570px;">
			<? foreach($this->images as $image){ ?>
			<li>
				<a href="<?php echo $image->img_link; ?>" class="cloud-zoom-gallery" title="" rel="useZoom: 'zoom1', smallImage: '<?php echo $image->img; ?>'">
					<img src="<?php echo $image->img_small; ?>" alt="<?php echo $this->product->title; ?>" />
				</a>
			</li>
			<? } ?>
		</ul>
	</div>
	<a id="view_scroll_right" title="" href="javascript:{}" style="cursor: pointer; opacity: 1; display: block;">Next</a>		
</div>
<? } ?>