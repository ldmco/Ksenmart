<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
	<?php if (count($this->product->comments) > 0) { ?>
		<?php $i=0;foreach($this->product->comments as $comment) { $i++; ?>
			<article class="item row-fluid reviews" data-id="<?php echo $comment->id; ?>">
				<div class="span2">
					<div class="ava">
						<a href="javascript:void(0)"><img src="<?php echo $comment->logo_thumb; ?>" alt="" class="km_img sm" /></a>
					</div>
				</div>
				<div class="span10">
					<div class="info clearfix">
						<div class="name pull-left">
							<?php if(empty($comment->name)): ?>
								<?php echo $comment->comment_name; ?>
							<?php else: ?>
								<?php echo $comment->name; ?>
							<?php endif; ?>
						</div>
						<div class="rating pull-left">
							<?php for($k=1;$k<6;$k++) { if (floor($comment->rate)>=$k) { ?>
								<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star-small.png" alt="" />
								<?php } else { ?>
									<img src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
								<?php } } ?>
						</div>
					</div>
					<dl class="dl-horizontal">
						<dt><?php echo JText::_('KSM_COMMENTS_LABEL_TEXT'); ?></dt>
						<dd>
							<?php echo $comment->comment; ?>
						</dd>
						<dt class="text-success"><?php echo JText::_('KSM_COMMENTS_LABEL_PLUS'); ?></dt>
						<dd class="text-success">
							<?php echo $comment->good; ?>
						</dd>
						<dt class="text-error"><?php echo JText::_('KSM_COMMENTS_LABEL_MINUS'); ?></dt>
						<dd class="text-error">
							<?php echo $comment->bad; ?>
						</dd>
					</dl>
					<?php if(empty($comment->children) && KSUsers::is_admin()){ ?>
						<div class="reply text-right">
							<a href="javascript:void(0);" title="<?php echo JText::_('KSM_COMMENTS_LABEL_REPLY'); ?>" class="l-reply link_b_border"><?php echo JText::_('KSM_COMMENTS_LABEL_REPLY'); ?></a>
						</div>
						<?php } ?>
				</div>
			</article>
			<?php if(!empty($comment->children)){ ?>
				<article class="item row-fluid reviews reply">
					<div class="span1">
						<div class="ava">
							<a href="javascript:void(0)"><img src="<?php echo $comment->children->logo_thumb; ?>" alt="" class="km_img sm" /></a>
						</div>
					</div>
					<div class="span11">
						<div class="info clearfix">
							<div class="name pull-left">
								<?php echo $comment->children->name; ?>
							</div>
						</div>
						<dl class="dl-horizontal">
							<dt><?php echo JText::_('KSM_COMMENTS_LABEL_TEXT'); ?></dt>
							<dd>
								<?php echo $comment->children->comment; ?>
							</dd>
						</dl>
					</div>
				</article>
				<?php } ?>
					<?php } ?>
						<div class="reply_block hide">
							<form method="POST" data-id="0" data-product_id="<?php echo $this->product->id; ?>">
								<textarea name="reply" required="true"></textarea>
								<button type="submit" class=" btn btn-success"><?php echo JText::_('KSM_COMMENTS_LABEL_SUBMIT'); ?></button>
							</form>
						</div>
						<?php } else { ?>
							<h4 class="text-center"><?php echo JText::_('KSM_COMMENTS_LABEL_NOCOMMENT'); ?></h4>
                        <?php } ?>