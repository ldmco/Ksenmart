<? defined('_JEXEC') or die;

    $user       = KSUsers::getUser();
    $shop_id    = KSSystem::getShopItemid();
?>
<script>
jQuery(document).ready(function(){
	// Check the initial Poistion of the Sticky Header
	var url=document.location.href;
	var active='';
	url=url.split('#');
	if (url.length>1)
		active=url[1];
	if (active=='')	
		active='profile_info';
	jQuery('.left-menu li[menu="'+active+'"]').addClass('active');
	
	var stickyHeaderTop = $('.left-menu').offset().top-90;
	$(window).scroll(function(){
			if( $(window).scrollTop() > stickyHeaderTop ) {
					$('.left-menu').css({position: 'fixed', top: '90px'});
					$('#alias').css('display', 'block');
			} else {
					$('.left-menu').css({position: 'static', top: '90px'});
					$('#alias').css('display', 'none');
			}
	});
	jQuery('body').animate({'scrollTop':jQuery('.'+active).offset().top-160},500); 
	
	jQuery('.left-menu a').click(function(){
		var menu=jQuery(this).parent().attr('menu');
		jQuery('.left-menu li').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('body').animate({'scrollTop':jQuery('.'+menu).offset().top-90},500); 
		return false;
	});
	
	jQuery('.user-menu a').click(function(){
		var menu=jQuery(this).parent().attr('menu');
		jQuery('.left-menu li').removeClass('active');
		jQuery('.left-menu li[menu="'+menu+'"]').addClass('active');
		jQuery('.user-menu').hide();
		jQuery('body').animate({'scrollTop':jQuery('.'+menu).offset().top-90},500); 
		return false;
	});
	
});
</script>
<div class="left-menu">
	<h3><?php echo $module->title; ?></h3>
	<ul>
		<? if (count($user->favorites)>0){ ?>
		<li menu="profile_favorities"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.$shop_id); ?>#profile_favorities">Избранные товары</a></li>
		<? }
		if (count($user->watched)>0){ ?>
		<li menu="profile_watched"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.$shop_id); ?>#profile_watched">Отслеживаемые скидки</a></li>
		<? } ?>
		<li menu="profile_orders"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.$shop_id); ?>#profile_orders">Мои заказы</a></li>
		<li menu="profile_info"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.$shop_id); ?>#profile_info">Контактная информация</a></li>
	</ul>
</div>	