<div class="row-fluid">
    <p class="lead add_shop_review pull-right clearfix"><span>+</span> <a href="javascript:void(0);" class="link_b_border lrg">Добавить отзыв о магазине</a></p>
</div>
<div class="review add noTransition row-fluid" style="display: none;">
	<form method="post" action="#tab4">
		<div class="control-group">
			<div class="controls">
				<img data-rate="1" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="2" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="3" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="4" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
				<img data-rate="5" src="<?php echo JURI::root()?>components/com_ksenmart/images/star2-small.png" alt="" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<textarea name="review" placeholder="Отзыв о магазине" required="true"></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls top">
				<button type="submit" class="button btn btn-success">Добавить</button>
			</div>
		</div>
        <input type="hidden" name="rate" id="comment_rate" value="0" />
		<input type="hidden" name="task" value="profile.add_shop_review" />
	</form>
    <hr />
</div>