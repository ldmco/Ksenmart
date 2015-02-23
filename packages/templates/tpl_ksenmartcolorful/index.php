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
        <div class="fixed_block_login_panel"><jdoc:include type="modules" name="fixed_block_login_panel" style="none" /></div>
        <div class="fixed_block_minicart"><jdoc:include type="modules" name="fixed_block_minicart" style="none" /></div>
		<div class="fixed_bloc_left"><jdoc:include type="modules" name="fixed_bloc_left" style="none" /></div>
		<div class="fixed_bloc_right"><jdoc:include type="modules" name="fixed_bloc_right" style="none" /></div>
		<div class="floating-header">
			<div class="inner">
				<div class="logo">
					<a href="<?php echo JURI::root(); ?>" title="<?php echo JFactory::getConfig()->get('sitename'); ?>">
						<img src="<?php echo $this->params->get('site_logo'); ?>" alt="<?php echo JFactory::getConfig()->get('sitename'); ?>" />
					</a>
				</div>
				<div class="menus">
					<jdoc:include type="modules" name="main-menu" style="none" />
				</div>
			</div>
		</div>
		<div class="container">
			<div class="top-head">
				<div class="left-m"><jdoc:include type="modules" name="head_menu_1" style="none" /></div>
				<div class="right-m"><jdoc:include type="modules" name="auth" style="none" /></div>
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
							<jdoc:include type="modules" name="head_block_2" style="none" />
						</div>
						<div class="span6 head_block_3">
							<jdoc:include type="modules" name="minicart" style="none" />
						</div>
					</div>
				</div>
				<nav class="navbar gk-main-menu">
					<div class="navbar-inner">
						<div class="container">
							<jdoc:include type="modules" name="main-menu" style="none" />
						</div>
					</div>
				</nav><!-- /.navbar -->
			</header>
			<jdoc:include type="modules" name="bottom_header" style="none" />
			<section class="row-fluid">
				<?php //if($left_column){ ?>
				<aside class="span3" id="leftSidebar">
					<jdoc:include type="modules" name="categories" style="none" />
					<jdoc:include type="modules" name="right" style="none" />
					<jdoc:include type="modules" name="left" style="none" />
				</aside>
				<?php //} ?>
				<div class="span9 content">
                    <div class="row-fluid">
    				    <jdoc:include type="modules" name="jumbotron" style="xhtml" />
                    </div>
					<div class="row-fluid">
						<jdoc:include type="modules" name="search" />
						<jdoc:include type="modules" name="content_top" style="none" />
					</div>
                    <div class="content_in_wrapp">
						<jdoc:include type="message" />
                        <jdoc:include type="component" />
                    </div>
				</div>
			</section>
			<hr />
			<?php if($this->countModules('content_bottom_1') || $this->countModules('content_bottom_2') || $this->countModules('content_bottom_3')){ ?>
			<div class="row-fluid">
				<jdoc:include type="modules" name="content_bottom_1" style="span4" />
				<jdoc:include type="modules" name="content_bottom_2" style="span4" />
				<jdoc:include type="modules" name="content_bottom_3" style="span4" />
			</div>
			<hr />
			<?php } ?>
		</div>
		<footer class="footer">
			<div class="container">
                <?php if($this->countModules('footer_1')){ ?>
				<div class="row-fluid" id="footer1">
					<jdoc:include type="modules" name="footer_1" style="none" />
				</div>
                <?php } ?>
                <?php if($this->countModules('footer_2')){ ?>
    			<div class="row-fluid" id="footer2">
					<jdoc:include type="modules" name="footer_2" style="none" />
    			</div>
                <?php } ?>
				<?php if($this->countModules('footer3')){ ?>
    			<div class="row-fluid" id="footer3">
					<jdoc:include type="modules" name="footer_2" style="none" />
    			</div>
				<?php } ?>
            </div>
		</footer>
	</body>
</html>