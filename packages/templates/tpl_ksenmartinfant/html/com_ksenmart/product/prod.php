<div id="primary_block" class="clearfix">
		<!--ADD CUSTOM CLOUD ZOOM!!!-->
<!-- Call quick start function. -->
<!-- right infos-->
<div class="row">
<div id="pb-right-column" class="span4">
		<h1 class="pb-right-colum-h"><?php echo $this->product->title?></h1>
	<!-- product img-->
		<div id="image-block">
			<span id="view_full_size">
				<div id="wrap" class="clearfix" style="top:0px;z-index:auto;position:relative;">
					<a id="zoom1" rel="position: 'inside' , showTitle: false, adjustX:0, adjustY:0" class="cloud-zoom" href="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-thickbox_default.jpg" style="position: relative; display: block;">
						<img id="mousetrap_img" alt="Lock Up Your Daughters - Classic Blue" width="106" height="106" title="Lock Up Your Daughters - Classic Blue" src="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-thickbox_default.jpg" rel="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-thickbox_default.jpg" style="display: block;">
						<img id="bigpic" alt="Lock Up Your Daughters - Classic Blue" title="Lock Up Your Daughters - Classic Blue" src="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-large_default.jpg" rel="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-thickbox_default.jpg" style="display: block;">
						<span class="mask"></span>	
					</a>
					<div class="mousetrap" style="background-image:url(&quot;.&quot;);z-index:999;position:absolute;width:106px;height:106px;left:0px;top:0px;"></div>
				</div>
             </span>
		</div>
	<!-- thumbnails -->
		<div id="views_block" class=" ">
			<a id="view_scroll_left" title="Other views" href="javascript:{}" style="cursor: default; opacity: 0; display: none;">Previous</a>
			<div id="thumbs_list">
				<ul id="thumbs_list_frame" style="width: 570px;">
					<li id="thumbnail_7">
						<a href="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-thickbox_default.jpg" class="cloud-zoom-gallery" title="" rel="useZoom: 'zoom1', smallImage: 'http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-large_default.jpg'">
							<img id="thumb_7" src="http://livedemo00.template-help.com/prestashop_44772/img/p/7/7-medium_default.jpg" alt="">
						</a>
					</li>
				</ul>
			</div>
			<a id="view_scroll_right" title="Other views" href="javascript:{}" style="cursor: pointer; opacity: 1; display: block;">Next</a>		
		</div>
		<p class="resetimg">
        	<span id="wrapResetImages" style="display: none;">
            	<i class="icon-reply"></i>
				<a id="resetImages" href="http://livedemo00.template-help.com/prestashop_44772/index.php?id_product=2&amp;controller=product&amp;id_lang=1" onclick="$('span#wrapResetImages').hide('slow');return (false);">Display all pictures</a>
			</span>
		</p>
		<ul id="usefull_link_block" class="clearfix">
			<li id="favoriteproducts_block_extra_added" class="favorite">
				<i class="icon-heart"></i>Remove this product from my favorite's list. 
			</li>
			<li id="favoriteproducts_block_extra_removed" class="favorite">
				<i class="icon-heart-empty"></i>Add this product to my list of favorites.
			</li>
		</ul>  
	</div>
	<!-- left infos-->
	<div id="pb-left-column" class="span5">
		<h1>Lock Up Your Daughters - Classic Blue</h1>
        <div id="short_description_block">
			<div id="short_description_content" class="rte align_justify">Fashion seems to be, perhaps, one of the most changeable phenomenons of all times. It reflects the changes in society and turns them into modern trends. The more trends appear, the more alterations in the society life take place. Looking back to the days that are gone, it</div>
		</div>
		<!-- add to cart form-->
		<form id="buy_block" action="http://livedemo00.template-help.com/prestashop_44772/index.php?controller=cart" method="post">
			<!-- hidden datas -->
			<p class="hidden">
				<input type="hidden" name="token" value="f443d1f01eeab15a1f70972ca6d4b983">
				<input type="hidden" name="id_product" value="2" id="product_page_product_id">
				<input type="hidden" name="add" value="1">
				<input type="hidden" name="id_product_attribute" id="idCombination" value="">
			</p>
            <div class="product_attributes">
                <div class="row-3">
					<!-- availability -->
					<p id="availability_statut" style="display: none;">
						<span id="availability_label">Availability:</span>
						<span id="availability_value"></span>
					</p>
					<!-- number of item in stock -->
					<p id="pQuantityAvailable">
						<span id="quantityAvailable">92</span>
						<span style="display: none;" id="quantityAvailableTxt">item in stock</span>
						<span id="quantityAvailableTxtMultiple">items in stock</span>
					</p>
					<p class="warning_inline" id="last_quantities" style="display: none">Warning: Last items in stock!</p>
					<p id="product_reference" style="display: none;">
						<label for="product_reference">Reference: </label>
						<span class="editable"></span>
					</p>
                </div>
				<!-- minimal quantity wanted -->
				<p id="minimal_quantity_wanted_p" style="display: none;">
					This product is not sold individually. You must select at least <b id="minimal_quantity_label">1</b> quantity for this product.
				</p>
				<p class="warning_inline" id="last_quantities" style="display: none">Warning: Last items in stock!</p>
			</div>
            <div class="content_prices clearfix">
				<!-- prices -->
				<div class="row-2" style="display:none;">
					<p id="reduction_percent" style="display:none;"><span id="reduction_percent_display" class="price"></span></p>
                    <p id="reduction_amount" style="display:none"><span id="reduction_amount_display" class="price"></span></p>
                </div>
				<div class="row_1">
					<p class="our_price_display">
						<span id="our_price_display">$628.96</span>
					</p>
                    <p id="add_to_cart" class="buttons_bottom_block">
                        <a class="exclusive button btn_add_cart" href="javascript:document.getElementById('add2cartbtn').click();">
							<span>Add to cart</span></a>
							<input id="add2cartbtn" type="submit" name="Submit" value="Add to cart">
                        </p>
                    <!-- quantity wanted -->
					<p id="quantity_wanted_p">
                        <input type="text" name="qty" id="quantity_wanted" class="text" value="1" size="2" maxlength="3">
                        <label>Quantity:</label>
                    </p>
    			</div>
                <div class="other-prices"></div>
            </div>
			<!-- Out of stock hook -->
			<p id="oosHook" style="display: none;">
				<script type="text/javascript">
				$(function(){
					$('a[href=#idTab5]').click(function(){
						$('*[id^="idTab"]').addClass('block_hidden_only_for_screen');
						$('div#idTab5').removeClass('block_hidden_only_for_screen');

						$('ul#more_info_tabs a[href^="#idTab"]').removeClass('selected');
						$('a[href="#idTab5"]').addClass('selected');
					});
				});
				</script>
			</p>
			<div id="product_comments_block_extra">
				<div class="comments_advices">
					<a class="open-comment-form" href="#new_comment_form">Write your review</a>
				</div>
			</div>
			<!--  /Module ProductComments -->
			<p></p>
		</form>
	</div>
</div>
</div>