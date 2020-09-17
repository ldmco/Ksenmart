<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div data-step_id="<?php echo $this->stepsinfo->current_step; ?>" class="ksm-cart-order-steps default_steps">
	<?php if ($this->stepsinfo->steps_counts > 1): ?>
        <div class="ksm-cart-order-steps-head">
			<?php
			$counter = 1;
			foreach ($this->stepsinfo->steps as $key => $step):
				if ($step): ?>
					<?php $class = ''; ?>
					<?php $this->stepsinfo->current_step == $key ? $class .= 'active' : ''; ?>
					<?php $this->stepsinfo->current_step > $key ? $class .= ' complete' : ''; ?>
                    <div class="ksm-cart-order-steps-head-step <?php echo $class; ?> step-count-<?php echo $this->stepsinfo->steps_counts; ?>">
                        <span class="ksm-cart-order-steps-head-count"><?php echo ($this->stepsinfo->current_step <= $key) ? $counter : ''; ?></span>
						<?php if ($key != 3): ?>
							<?php echo JText::_('KSM_CART_STEP_' . $key); ?>
						<?php else: ?>
							<?php echo JText::_('KSM_CART_STEP_' . $this->stepsinfo->shipping_step_name); ?>
						<?php endif; ?>
                    </div>
					<?php $counter++;
				endif;
			endforeach; ?>
        </div>
	<?php endif; ?>
	<?php echo $this->loadTemplate('step_' . $this->stepsinfo->current_step); ?>
</div>