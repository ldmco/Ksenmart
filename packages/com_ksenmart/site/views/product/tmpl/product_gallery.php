<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-product-gallery product_gallery">
	<?php if (!empty($this->images)): ?>
        <div class="ksm-product-gallery-bigs">
			<?php foreach ($this->images as $key => $image): ?>
				<?php if (is_string($image->params))
				{
					$image->params = json_decode($image->params);
				}
				?>
                <div class="ksm-product-gallery-big <?php echo $key == 0 ? 'active' : ''; ?>"
                     data-img_id="<?php echo $image->id; ?>">
                    <a <?php echo JMicrodata::htmlProperty('image'); ?> href="<?php echo $image->img_link; ?>"
                                                                        class="highslide"
                                                                        onclick="return hs.expand(this)">
                        <img src="<?php echo $image->img; ?>"
                             title="<?php echo(empty($image->params->title) ? $this->product->title : $image->params->title); ?>"
                             alt="<?php echo(empty($image->params->alt) ? $this->product->title : $image->params->alt); ?>"/>
                    </a>
                </div>
			<?php endforeach; ?>
            <div class="ksm-catalog-item-flags">
				<?php if ($this->product->hot == 1): ?>
                    <span class="ksm-catalog-item-flag-hot"></span>
				<?php endif; ?>
				<?php if ($this->product->recommendation == 1): ?>
                    <span class="ksm-catalog-item-flag-recommendation"></span>
				<?php endif; ?>
				<?php if ($this->product->new == 1): ?>
                    <span class="ksm-catalog-item-flag-new"></span>
				<?php endif; ?>
				<?php if ($this->product->promotion == 1 || ($this->product->old_price > 0 && $this->product->old_price > $this->product->price)): ?>
                    <span class="ksm-catalog-item-flag-promotion"></span>
				<?php endif; ?>
            </div>
        </div>
		<?php if (count($this->images) > 1): ?>
            <div class="ksm-product-gallery-thumbs">
				<?php foreach ($this->images as $key => $image): ?>
                    <div class="ksm-product-gallery-thumb <?php echo $key == 0 ? 'active' : ''; ?>">
                        <a class="ksm-product-gallery-thumb-link" data-img_id="<?php echo $image->id; ?>"><img
                                    src="<?php echo $image->img_small; ?>" alt="<?php echo $this->product->title; ?>"/></a>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
	<?php else: ?>
        <div class="ksm-product-gallery-bigs">
            <div class="ksm-product-gallery-big active">
                <a <?php echo JMicrodata::htmlProperty('image'); ?> title="<?php echo $this->product->title; ?>"
                                                                    href="<?php echo $this->product->img_link; ?>"
                                                                    class="highslide" onclick="return hs.expand(this)">
                    <img src="<?php echo $this->product->img; ?>"
                         title="<?php echo(empty($this->product->params->title) ? $this->product->title : $this->product->params->title); ?>"
                         alt="<?php echo(empty($this->product->params->alt) ? $this->product->title : $this->product->params->alt); ?>"/>
                </a>
            </div>
        </div>
	<?php endif; ?>
</div>
