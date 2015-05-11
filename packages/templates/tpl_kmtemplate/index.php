<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$app 	= JFactory::getApplication();
$Itemid = $app->input->get('Itemid', 1, 'int');
$view 	= $app->input->get('view', null, 'string');

$sitename 		= $app->getCfg('sitename');
$right_column 	= false;
$left_column 	= false;

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

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.loadCss');

echo '<?xml version="1.0" encoding="utf-8"?'.">\n"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
 
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/colors.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/typography.css" rel="stylesheet" type="text/css" />
                
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/default.js"></script>
	</head>
	<body>
        <div class="fixed_block_login_panel"><jdoc:include type="modules" name="fixed_block_login_panel" style="none" /></div>
        <div class="fixed_block_minicart"><jdoc:include type="modules" name="fixed_block_minicart" style="none" /></div>
		<div class="fixed_bloc_left"><jdoc:include type="modules" name="fixed_bloc_left" style="none" /></div>
		<div class="fixed_bloc_right"><jdoc:include type="modules" name="fixed_bloc_right" style="none" /></div>
		<div class="container">
			<header class="masthead">
				<div class="row-fluid header">
					<div class="span3 pull-left logo">
						<h1 class="muted">
							<a href="<?php echo JURI::root(); ?>" title="<?php echo $sitename; ?>">
                                <img src="<?php echo $this->params->get('site_logo'); ?>" alt="<?php echo $sitename; ?>" />
                            </a>
						</h1>
					</div>
					<div class="span8 pull-right">
						<div class="span4 head_block_1">
							<jdoc:include type="modules" name="head_custom_block" style="none" />
						</div>
						<div class="span4 head_block_2">
							<jdoc:include type="modules" name="head_block_2" style="none" />
						</div>
						<div class="span4 head_block_3">
							<jdoc:include type="modules" name="head_block_3" style="none" />
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
				<?php if($left_column){ ?>
				<aside class="span3" id="leftSidebar">
					<jdoc:include type="modules" name="left" style="none" />
				</aside>
				<?php } ?>
				<div class="<?php echo $left_column?'span9 ':''; ?>content">
                    <div class="row-fluid">
    				    <jdoc:include type="modules" name="jumbotron" style="xhtml" />
                    </div>
					<div class="row-fluid">
						<jdoc:include type="modules" name="content_top" style="none" />
					</div>
                    <div class="content_in_wrapp">
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
            <?php if($this->countModules('content_bottom')){ ?>
            <div class="row-fluid">
                <jdoc:include type="modules" name="content_bottom" />
            </div>
            <?php } ?>
            <hr />
		</div>
		<footer class="footer">
			<div class="container">
                <?php if($this->countModules('footer_1')){ ?>
				<div class="row-fluid footer_menus">
					<jdoc:include type="modules" name="footer_1" style="span3" />
					<jdoc:include type="modules" name="footer_2" style="span3" />
					<jdoc:include type="modules" name="footer_3" style="span3" />
					<jdoc:include type="modules" name="footer_4" style="span3" />
				</div>
                <?php } ?>
                <?php if($this->countModules('copyright')){ ?>
				<div class="row-fluid">
					<div class="copyright">
						<jdoc:include type="modules" name="copyright" style="none" />
					</div>
				</div>
                <?php } ?>
    			<div class="row-fluid" id="footer1">
    				<div class="span3">
    					<h5>Время работы</h5>
    					Прием заказов: круглосуточно<br>
    					Доставка: Пн-Вс, 09:00 - 21:00
    				</div>
    				<div class="span4">
    					<h5>Есть вопросы?</h5>
    					<div class="span4" style="margin:0;">
    						Звоните:<br>
    						8 800 221-21-22
    					</div>
    					<div class="span5">
    						Пишите:<br>
    						<a href="#">inbox@company.net</a>
    					</div>
    				</div>
    				<div class="span2 pull-right">
    					<span>Мы в соцсетях:</span><br>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/vk.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/fb.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/tw.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/od.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/gp.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/mr.png" alt=""></a>
    				</div>
    				<div class="span2 pull-right">
    					<span>Мы принимаем к оплате:</span><br>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/wm.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/yd.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/vs.png" alt=""></a>
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/mc.png" alt=""></a>
    				</div>
    			</div>
    			<div class="row-fluid" id="footer2">
    				<div class="span6">
    					©2001—2012 Компания <a href="#">www.company.net</a><br>
    					Мы предлагаем самые лучшие услуги. Все остальные нам завидуют!
    				</div>
    				<div class="span2 pull-right">
    					<a href="http://ldm-co.ru" class="ldm" title="L.D.Mamp;Co.">Создание<br> сайта</a>
    				</div>
    				<div class="span2 pull-right">
    					<a href="#"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/count.png" alt=""></a>
    				</div>
    			</div>
    			<div class="row-fluid" id="footer3">
    				<div class="span6">
    					<em>Все здесь принадлежит нам. Копирование разрешается только при наличии<br>
    					письменного разрешения заверненного сургучной гербовой печатью.</em>
    				</div>
    			</div>
            </div>
		</footer>
	</body>
</html>