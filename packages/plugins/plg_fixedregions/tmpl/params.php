<?php defined('_JEXEC') or die('Restricted access'); ?>

<script>
    var currency_code = '<?php echo $view->currency_code; ?>';
    var KSM_SHIPPINGS_SHIPPING_DELIVERY_FROM = '<?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_FROM'); ?>';
    var KSM_SHIPPINGS_SHIPPING_DELIVERY_TO = '<?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_TO'); ?>';
    var KSM_SHIPPINGS_SHIPPING_DELIVERY_DAYS = '<?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_DAYS'); ?>';
    var KSM_SHIPPINGS_FIXEDREGIONS_NEXT_WEIGHT = '<?php echo JText::_('KSM_SHIPPINGS_FIXEDREGIONS_NEXT_WEIGHT'); ?>';
</script>
<div class="set">
    <h3 class="headname"><?php echo JText::_('KSM_SHIPPING_ALGORITHM'); ?></h3>
    <div class="lists">
        <div class="row">
            <ul class="regions-params-ul">
				<?php foreach ($view->params as $region_id => $region_info): ?>
					<?php
					$cost        = isset($region_info['cost']) ? $region_info['cost'] : $region_info;
					$fromdate    = isset($region_info['fromdate']) ? $region_info['fromdate'] : 0;
					$todate      = isset($region_info['todate']) ? $region_info['todate'] : 0;
					$weight_cost = isset($region_info['weight_cost']) ? $region_info['weight_cost'] : 0;
					?>

					<?php if (empty($view->regions[$region_id])) continue; ?>

                    <li region_id="<?php echo $region_id; ?>" country_id="<?php echo $view->regions[$region_id]->country_id; ?>">
                        <div class="line">
                            <label class="inputname"><?php echo $view->regions[$region_id]->title; ?></label>
                            <input style="width:100px;" type="text" class="inputbox" name="jform[params][<?php echo $region_id; ?>][cost]" value="<?php echo $cost; ?>">
                            <span><?php echo $view->currency_code; ?></span>
                            <label style="width:100px;"class="inputname"><?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_FROM'); ?></label>
                            <input style="width:30px;" type="text" class="inputbox" name="jform[params][<?php echo $region_id; ?>][fromdate]" value="<?php echo $fromdate; ?>">
                            <label style="width:30px;" class="inputname"><?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_TO'); ?></label>
                            <input style="width:30px;" type="text" class="inputbox" name="jform[params][<?php echo $region_id; ?>][todate]" value="<?php echo $todate; ?>">
                            <label style="width:40px;" class="inputname"><?php echo JText::_('KSM_SHIPPINGS_SHIPPING_DELIVERY_DAYS'); ?></label>
                        </div>
                        <div class="line">
                            <label class="inputname"><?php echo JText::_('KSM_SHIPPINGS_FIXEDREGIONS_NEXT_WEIGHT'); ?></label>
                            <input style="width:100px;" type="text" class="inputbox" name="jform[params][<?php echo $region_id; ?>][weight_cost]" value="<?php echo $weight_cost; ?>">
                            <span><?php echo $view->currency_code; ?></span>
                        </div>
                    </li>
                    <li class="no-regions" style="<?php echo (count($view->params) > 0 ? 'display:none;' : ''); ?>">
                        <div class="line">
                            <label class="inputname"><?php echo JText::_('KSM_SHIPPINGS_SHIPPING_NO_REGIONS'); ?></label>
                            <p>&nbsp;</p>
                        </div>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>