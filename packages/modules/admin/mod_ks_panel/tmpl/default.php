<?php
/**
 *
 * $Id: default.php 1.0.0 2012-11-27 09:24:43 Alexander Polyakov $
 * @package	    Joomla! 
 * @subpackage	Ksenmart Cpanel
 * @version     1.0.0
 * @description 
 * @copyright	  Copyright В© 2012 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Alexander Polyakov
 * @author mail	alx.polyakov@gmail.com
 * @website		  http://alx-polyakov.ru/
 *
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$colnum =0;
?>
<div id="start" class="clearfix">
	<div class="left">
		<h2>Магазин</h2>
		<div id="shop-ico" class="widgets">
		<?php foreach ($items as $v): ?>
		<?php if ($v->column == 1):?>
		<a class="<?php echo $v->class; ?>" href="<?php echo $v->url; ?>" >
			<img src="/administrator/modules/mod_ks_panel/css/i/<?php echo $v->image; ?>" alt="<?php echo $v->title; ?>"/>
			<span><?php echo $v->title; ?></span>
			<div>
			<?php if (isset($v->content)) echo $v->content; ?>
			</div>
			
		</a>
		<?php endif;?>
		<?php endforeach;?>
		</div>
	</div>
	<div class="right">
		<h2>Сайт</h2>
		<div class="main-icons">
			<div class="cont">
		<?php foreach ($items as $v): ?>
		<?php if ($v->column == 2 ):?>
			<a class="<?php echo $v->class; ?>" href="<?php echo $v->url; ?>" >
				<img src="/administrator/modules/mod_ks_panel/css/i/<?php echo $v->image; ?>" alt="<?php echo $v->title; ?>"/>
				<span><?php echo $v->title; ?></span>
				
			</a>
		<?php endif;?>
		<?php endforeach;?>
				
			</div>
			<div class="pref">
				<?php foreach ($items as $v): ?>
		<?php if ($v->column == 3 ):?>
			<a class="<?php echo $v->class; ?>" href="<?php echo $v->url; ?>" >
				<img src="/administrator/modules/mod_ks_panel/css/i/<?php echo $v->image; ?>" alt="<?php echo $v->title; ?>"/>
				<span><?php echo $v->title; ?></span>
				
			</a>
		<?php endif;?>
		<?php endforeach;?>
			</div>
		</div>
		<div class="rr">
			<div id="joom-ico" class="widgets">
			<?php foreach ($items as $v): ?>
			<?php if ($v->column == 4 ):?>
			<a class="<?php echo $v->class; ?>" href="<?php echo $v->url; ?>" >
				<img src="/administrator/modules/mod_ks_panel/css/i/<?php echo $v->image; ?>" alt="<?php echo $v->title; ?>"/>
				<span><?php echo $v->title; ?></span>
				<div>
				<?php if (isset($v->content)) echo $v->content; ?>
				</div>
			</a>
		<?php endif;?>
		<?php endforeach;?>
			</div>
		</div>
	</div>
</div>