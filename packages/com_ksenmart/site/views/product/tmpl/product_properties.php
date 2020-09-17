<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="ksm-product-properties">
	<?php if (count($this->product->properties)): ?>
        <h3><?php echo JText::_('KSM_PRODUCT_PROPERTIES_TITLE'); ?></h3>
	<?php endif; ?>
	<?php foreach ($this->product->properties as $property) { ?>
		<?php if (!empty($property->values) && $property->type != 'none') { ?>
			<?php if ($property->type == 'text') { ?>
                <div class="ksm-product-property ksm-product-info-row">
                    <label class="ksm-product-info-row-label"><?php echo $property->title ?>:</label>
                    <div class="ksm-product-info-row-control">
						<?php foreach ($property->values as $value) { ?>
							<?php echo $value->title; ?>
						<?php } ?>
                    </div>
                </div>
			<?php } elseif ($property->type == 'select') { ?>
				<?php if ($property->view == 'select') { ?>
                    <div class="ksm-product-property ksm-product-info-row">
                        <label class="ksm-product-info-row-label"><?php echo $property->title ?>:</label>
                        <div class="ksm-product-info-row-control">
							<?php if (count($property->values) > 1) { ?>
                                <select class="sel" data-prop_id="<?php echo $property->property_id; ?>"
                                        name="property_<?php echo $this->product->id . "_" . $property->property_id; ?>"
                                        required="true">
                                    <option value=""><?php echo JText::_('KSM_PRODUCT_PROPERTY_CHOOSE'); ?></option>
									<?php foreach ($property->values as $value) { ?>
                                        <option <?php echo($property->default == $value->id ? 'selected' : ''); ?>
                                                value="<?php echo $value->id; ?>"><?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?></option>
									<?php } ?>
                                </select>
							<?php } else { ?>
								<?php foreach ($property->values as $value) { ?>
									<?php echo $value->title; ?>
								<?php } ?>
                            <?php } ?>
                        </div>
                    </div>
				<?php } elseif ($property->view == 'radio' || $property->view == 'checkbox') { ?>
                    <div class="ksm-product-property ksm-product-info-row">
                        <label class="ksm-product-info-row-label"><?php echo $property->title ?>:</label>
                        <div class="ksm-product-info-row-control">
							<?php $i = 0;
							foreach ($property->values as $value) { ?>
                                <label>
                                    <input <?php echo($property->default == $value->id ? 'checked' : ''); ?>
                                            type="<?php echo $property->view; ?>"
                                            data-prop_id="<?php echo $property->property_id; ?>"
                                            name="property_<?php echo $this->product->id; ?>_<?php echo $property->property_id; ?>"
                                            value="<?php echo $value->id; ?>"/>
									<?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?>
                                </label>
								<?php $i++;
							} ?>
                        </div>
                    </div>
				<?php } elseif ($property->view == 'text_inline') { ?>
                    <div class="ksm-product-property ksm-product-info-row">
                        <label class="ksm-product-info-row-label"><?php echo $property->title ?>:</label>
                        <div class="ksm-product-info-row-control">
							<?php $texts = array(); ?>
							<?php foreach ($property->values as $value) { ?>
								<?php $texts[] = $value->title; ?>
							<?php } ?>
							<?php echo $property->prefix; ?><?php echo implode(',', $texts); ?><?php echo $property->suffix; ?>
                        </div>
                    </div>
				<?php } elseif ($property->view == 'text_row') { ?>
                    <div class="ksm-product-property ksm-product-info-row">
                        <label class="ksm-product-info-row-label"><?php echo $property->title ?>:</label>
                        <div class="ksm-product-info-row-control">
                            <ul>
								<?php foreach ($property->values as $value) { ?>
                                    <li>
										<?php echo $property->prefix; ?><?php echo $value->title; ?><?php echo $property->suffix; ?>
                                    </li>
								<?php } ?>
                            </ul>
                        </div>
                    </div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>