<?
	defined( '_JEXEC' ) or die( '=;)' );
	//TODO: REFACTOR ON JFORM/JFORMFIELDS
    $user = JFactory::getUser();
?>
<form method="post" id="comment_form" class="form-horizontal">
	<div class="form">
        <?php if(empty($user->name)) { ?>
		<div class="control-group">
			<label class="control-label" for="inputName"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_NAME_LBL'); ?></label>
			<div class="controls">
				<input type="text" id="inputName" name="comment_name" placeholder="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_NAME_LBL'); ?>" required="true" />
			</div>
		</div>
        <?php }else{ ?>
        <input type="hidden" name="comment_name" value="<?php echo $user->name; ?>" />
        <?php } ?>
		<div class="control-group">
			<label class="control-label" for="inputRate"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_RATE_LBL'); ?></label>
			<div class="controls">
				<img rate="1" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img rate="2" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img rate="3" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img rate="4" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img rate="5" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="inputComment"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_COMMENT_LBL'); ?></label>
			<div class="controls">
				<textarea class="textarea" id="inputComment" name="comment_comment" placeholder="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_COMMENT_LBL'); ?>" required="true"></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputRHight"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_HIGHS_LBL'); ?></label>
			<div class="controls">
				<textarea class="textarea" id="inputRHight" name="comment_good" placeholder="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_HIGHS_LBL'); ?>"></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputRBad"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_LOWS_LBL'); ?></label>
			<div class="controls">
				<textarea class="textarea" id="inputRBad" name="comment_bad" placeholder="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_LOWS_LBL'); ?>"></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" class="btn btn-success" value="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_BUTTON_TEXT'); ?>" />
			</div>
		</div>
	</div>
	<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />
	<input type="hidden" name="comment_rate" id="comment_rate" value="" />
	<input type="hidden" name="task" value="product.add_comment" />
</form>	
