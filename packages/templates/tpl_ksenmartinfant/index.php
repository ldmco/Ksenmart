<?
defined( '_JEXEC' ) or die( '=;)' );
$view=JRequest::getVar('view','');
$option=JRequest::getVar('option','');

JHTML::_('behavior.modal', 'a.modal');
$menu = & JSite::getMenu();
if ($menu->getActive() == $menu->getDefault()) 
    $fpage=true; 
else
	$fpage=false; 
?>
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="ru"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="ru"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="ru"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="ru"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
	
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="head" />

	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/normalize.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/font.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/global.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/product.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/product_list.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/superfish-modified.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/320.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/480.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?=JURI::base()?>templates/<?php echo $this->template ?>/css/768.css" rel="stylesheet" type="text/css" media="all" />


	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/superfish-modified.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/plugins.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/footable.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/jquery.scrollpane.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/jquery.uniform.js"></script> 
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/jquery.mousewheel.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/mainscript.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/modernizr-2.5.3.min.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/jquery.carouFredSel-6.2.1.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/jquery.touchSwipe.min.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/custom.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/product.js"></script>
	<script src="<?=JURI::base()?>templates/<?php echo $this->template ?>/js/source/plugins.js"></script>

</head>
<body>
	<jdoc:include type="modules" name="ref" />
		<div id="wrapp">
			<div id="wrapp_2">
				<header id="header" class="container ">
					<div id="header-inner" >
						<a id="header_logo" href="<?php echo JURI::root(); ?>" title="<?php echo JFactory::getConfig()->get('sitename'); ?>">
							<img src="<?php echo JURI::root().$this->params->get('site_logo', 'templates/ksenmartinfant/img/logo-1.jpg'); ?>" alt="<?php echo JFactory::getConfig()->get('sitename'); ?>" />
						</a>
						<section id="languages_block_top" class="header-box header-button">
							<div class="phone">
								<jdoc:include type="modules" name="ks-contacts" />
							</div>
						</section>
						<section class="header-box blockpermanentlinks-header">
							<div id="header_links">
								<jdoc:include type="modules" name="ks-menu" />
							</div>
							<div class="mobile-link-top header-button">
								<h4>
									<span class="title-hed"></span><span class="arrow_header_top_menu arrow_header_top"></span>
								</h4>
								<div id="mobilelink" class="list_header">
									<jdoc:include type="modules" name="ks-menu" />
								</div>
							</div>
						</section>
						<section id="search_block_top" class="header-box">
							<jdoc:include type="modules" name="ks-inf-search" />
						</section>
						<section class="blockuserinfo header-box">
							<jdoc:include type="modules" name="ks-auth" />
						</section>
						<section id="header_user" class="blockuserinfo-cart header-box">
							<jdoc:include type="modules" name="ks-minicart" />
						</section>
						<div id="menu-wrap" class="clearfix">
							
							
								<jdoc:include type="modules" name="ks-categories" />
							
						</div>
					</div>
				</header>
				<div class="extra_wrapp">
					<div id="columns" class="container ">
						<div class="row ">  
							<div class="loader_page">
								<jdoc:include type="modules" name="ks-breadcrumbs" style="none" />
								<div id="center_column" class="center_column <?php if ($fpage):?> span12 <?php else : ?> span9 <?php endif;?> clearfix">
									
										<jdoc:include type="modules" name="ks-main-banners" style="none" />
										
										<?php if ($this->countModules( 'ks-inf-main-info1 or ks-inf-main-info2 or ks-inf-main-info3' )) : ?>
											<div id="customcontent_home">
												<ul class="customcontent-home">
													<li class="num-1">
														<jdoc:include type="modules" name="ks-inf-main-info1" style="none" />
													</li>
													<li class="num-2">
														<jdoc:include type="modules" name="ks-inf-main-info2" style="none" />
													</li>
													<li class="num-3">
														<jdoc:include type="modules" name="ks-inf-main-info3" style="none" />
													</li>
												</ul>
											</div>
										<?php endif; ?>
										<jdoc:include type="modules" name="ks-main-products-list" style="none" />
										
									<div class="content_in_wrapp">
										<jdoc:include type="message" />
										<jdoc:include type="component" />
									</div>
								</div>
								<aside id="right_column" class="span3 column right_home">   
									<jdoc:include type="modules" name="ks-filters" style="none" />
								</aside>
							</div>
						</div>
					</div>
					<div class="footer-bg-mob">
						<footer class="container ">
							<div class="modules">
								<div class="block block_category_footer span4">
									<div class="list-footer">
										<jdoc:include type="modules" name="ks-footer-menu" style="none" />
									</div>
								</div>	
								<div class="block block_category_footer span4">
									<div class="list-footer">
										<jdoc:include type="modules" name="ks-footer-categories" style="none" />
									</div>
								</div>		
								<div class="block blocksocial span2">
									<jdoc:include type="modules" name="ks-inf-footer-social" style="none" />
								</div>		
								<div class="block blockcms_footer span2">
									<jdoc:include type="modules" name="ks-inf-footer-contacts" style="none" />
								</div>	
								<br clear="both">
								<div class="bottom_footer row-fluid">
									<jdoc:include type="modules" name="ks-footer-ldm" style="none" />
									<jdoc:include type="modules" name="ks-footer-copyright" style="none" />
								</div>
							</div>
						</footer>
					</div>   
				</div>
			</div>
        </div>
</body>