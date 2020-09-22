<?php defined('_JEXEC') or die('Restricted access'); ?>
<span class="linka" rel="popup-window-utmtags">
	<a>UTM-данные</a>
</span>
<div id="popup-window-utmtags" class="popup-window popup-window-utmtags" style="display: none;">
	<div style="width: 460px;height: 175px;margin-left: -230px;margin-top: -137px;">
		<div class="popup-window-inner">
			<div class="heading">
				<h3>UTM-данные</h3>
				<div class="save-close">
					<button class="close" onclick="return false;"></button>
				</div>
			</div>
			<div class="contents">
				<div class="contents-inner">
					<div class="slide_module">
						<div class="row">
							<ul>
								<?php foreach($view->utmtags as $key => $tag): ?>
								<li><b style="width:150px;display:inline-block;"><?php echo JText::_('ksm_plugin_utmtags_'.$key.'_label'); ?>:</b><?php echo $tag; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>