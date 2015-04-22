<?php
/**
 *
 * $Id: index.php 1.0.0 2013-04-11 19:02:19 Bereza Kirill $
 * @template  	Shoes
 * @version     1.0.0
 * @description 
 * @copyright	  Copyright © 2013 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Bereza Kirill
 * @author        Email	kirill.bereza@zebu.com
 * @website		  http://brainstorage.me/TakT
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$Itemid = JRequest::getInt('Itemid', 1, 'get');
$view   = JRequest::getVar('view', null);
$template_color = $this->params->get('template_color'); 
$vkontakte = $this->params->get('vkontakte', '');
$facebook = $this->params->get('facebook', '');
$twitter = $this->params->get('twitter', '');
$email = $this->params->get('email', '');
$phone = $this->params->get('phone', '');
$address = $this->params->get('address', '');
$copyright = $this->params->get('copyright', '');

$right_column = false;
$left_column = false;

$width_cont = 'span6';

if($this->countModules('right')){
	$right_column = true;
}

if($this->countModules('left')){
	$left_column = true;
}

if(!$right_column || !$left_column){
	$width_cont = 'span9';
}

if(!$right_column && !$left_column){
	$width_cont = 'span12';
}

echo '<?xml version="1.0" encoding="utf-8"?'.">\n"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/colors.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/typography.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $template_color ?>" rel="stylesheet" type="text/css" />
     	<meta name="w1-verification" content="153056983185" />
		
                
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/default.js"></script>
	</head>
	<body>
		<div class="floating-header">
			<div class="inner">
				<div class="logo">
					<a href="<?php echo JURI::root(); ?>" title="<?php echo JFactory::getConfig()->get('sitename'); ?>">
						<img src="<?php echo $this->params->get('site_logo'); ?>" alt="<?php echo JFactory::getConfig()->get('sitename'); ?>" />
					</a>
				</div>
				<div class="menus">
					<jdoc:include type="modules" name="ks-menu" style="none" />
				</div>
			</div>
		</div>
		<div class="container">
			<div class="top-head">
				<div class="left-m"><jdoc:include type="modules" name="ks-info-menu" style="none" /></div>
				<div class="right-m"><jdoc:include type="modules" name="ks-auth" style="none" /></div>
			</div>
			<header class="masthead">
				<div class="row-fluid header">
					<div class="span4 pull-left logo">
						<h1 class="muted">
							<a href="<?php echo JURI::root(); ?>" title="<?php echo JFactory::getConfig()->get('sitename'); ?>">
                                <img src="<?php echo $this->params->get('site_logo'); ?>" alt="<?php echo JFactory::getConfig()->get('sitename'); ?>" />
                            </a>
						</h1>
					</div>
					<div class="span7 pull-right">
						<div class="span6 head_block_2">
							<jdoc:include type="modules" name="ks-clrful-header-info" style="none" />
						</div>
						<div class="span6 head_block_3">
							<jdoc:include type="modules" name="ks-minicart" style="none" />
						</div>
					</div>
				</div>
				<nav class="navbar gk-main-menu">
					<div class="navbar-inner">
						<div class="container">
							<jdoc:include type="modules" name="ks-menu" style="none" />
						</div>
					</div>
				</nav><!-- /.navbar -->
			</header>
			<section class="row-fluid">
				<?php //if($left_column){ ?>
				<aside class="span3" id="leftSidebar">
					<jdoc:include type="modules" name="ks-profile" style="none" />
					<jdoc:include type="modules" name="ks-categories" style="none" />
					<jdoc:include type="modules" name="ks-filters" style="none" />
					<jdoc:include type="modules" name="ks-shipping-info" style="none" />
					<jdoc:include type="modules" name="ks-reviews" style="none" />
				</aside>
				<?php //} ?>
				<div class="span9 content">
					<div class="row-fluid">
						<jdoc:include type="modules" name="ks-search" />
						<jdoc:include type="modules" name="ks-breadcrumbs" />
    				    <jdoc:include type="modules" name="ks-main-banners" style="xhtml" />
						<jdoc:include type="modules" name="ks-main-products-list" style="none" />
					</div>
                    <div class="content_in_wrapp">
						<jdoc:include type="message" />
                        <jdoc:include type="component" />
                    </div>
				</div>
			</section>
			<hr />
		</div>
		<footer class="footer">
			<div class="container">
				<div class="row-fluid" id="footer1">
					<div class="span3">
						<jdoc:include type="modules" name="ks-clrful-footer-info1" style="none" />
					</div>
					<div class="span4">
						<h5>Есть вопросы?</h5>
						<?php if (!empty($phone)): ?>
						<div class="span4" style="margin: 0;">
							Звоните:<br />
							<?php if (!empty($phone)): ?>
								<?php echo $phone; ?><br />
							<?php endif; ?>
							<?php if (!empty($address)): ?>
								<?php echo $address; ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<?php if (!empty($email)): ?>
						<div class="span5">
							Пишите:<br />
							<?php echo $email; ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="span2 pull-right">
						<span>Мы в соцсетях:</span><br />
						<?php if (!empty($vkontakte)): ?>
						<a href="<?php echo $vkontakte; ?>"><img src="/templates/ksenmartcolorful/images/vk.png" alt="" /></a>
						<?php endif; ?>
						<?php if (!empty($facebook)): ?>
						<a href="<?php echo $facebook; ?>"><img src="/templates/ksenmartcolorful/images/fb.png" alt="" /></a>
						<?php endif; ?>
						<?php if (!empty($twitter)): ?>
						<a href="<?php echo $twitter; ?>"><img src="/templates/ksenmartcolorful/images/tw.png" alt="" /></a>
						<?php endif; ?>						
					</div>
					<div class="span2 pull-right">
						<jdoc:include type="modules" name="ks-clrful-footer-info2" style="none" />
					</div>
				</div>
    			<div class="row-fluid" id="footer2">
					<div class="span6">
						<?php echo $copyright; ?>
					</div>
					<div class="span2 pull-right">
						<a href="http://ldm-co.ru" class="ldm" title="L.D.M&amp;Co.">Разработка<br /> шаблона</a>
					</div>					
    			</div>
            </div>
		</footer>
	</body>
</html>