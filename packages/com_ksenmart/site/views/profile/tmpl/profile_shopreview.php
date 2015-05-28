<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<article class="item row-fluid reviews wrap_rvw_block" data-id="<?php echo $this->user_review->id; ?>">
	<div class="span3 review_product">
		<div class="img">
             <a href="javascript:void(0);" title="<?php echo $this->user_review->name; ?>">
                <img src="<?php echo $this->params->get('printforms_company_logos', 'media/ksenmart/default_logo.png'); ?>" alt="<?php echo $this->user_review->name; ?>" />
            </a>
        </div>
	</div>
	<div class="span9 content_review_wrapp">
		<dl class="dl-horizontal">
            <dt><?php echo JText::_('KSM_PROFILE_REVIEWS_RATE'); ?></dt>
            <dd class="rating">
				<?php for($k=1; $k<6; $k++){
					if(floor($this->user_review->rate) >= $k){ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
				<?php }else{ ?>
				<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<?php }
				} ?>
            </dd>
		  <dt><?php echo JText::_('KSM_PROFILE_REVIEWS_COMMENT'); ?></dt>
		  <dd class="quick_edit magical_text noTransition" data-type="comment" data-title="<?php echo JText::_('KSM_PROFILE_REVIEWS_EDIT'); ?>" contenteditable="false"><?php echo nl2br($this->user_review->comment); ?></dd>
		</dl>
        <div class="toolbar hide">                
            <ul class="inline">
                <li>
                    <a href="javascript:void(0);" class="link_b_border save_dynamic_link save_shop_review"><i class="icon-ok"></i> <?php echo JText::_('KSM_PROFILE_REVIEWS_SAVE'); ?></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="link_b_border cancel_edit"><i class="icon-remove"></i> <?php echo JText::_('KSM_PROFILE_REVIEWS_CANCEL'); ?></a>
                </li>
            </ul>
        </div>
	</div>
</article>