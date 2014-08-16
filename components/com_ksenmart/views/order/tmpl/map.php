<?php
defined( '_JEXEC' ) or die;
?>
<div id="ksenmart-map">
	<div id="ksenmart-map-inner">
		<div id="ksenmart-map-actions">
			<a id="ksenmart-map-close"><img alt="Закрыть окно" src="<?php echo JURI::root()?>components/com_ksenmart/images/delete.gif"></a>
			<div id="ksenmart-map-status">Укажите адрес доставки</div>
			<div id="ksenmart-map-input">
				<input type="text" id="ksenmart-map-to" />
				<div id="ksenmart-map-search"></div>
				<a id="ksenmart-map-to-moscow">Москва</a>
				<span class="separator">/</span>
				<a id="ksenmart-map-to-region">Область</a>
				<span class="separator">/</span>
				<a id="ksenmart-map-to-me">Найти меня</a>
				<input type="button" id="ksenmart-map-ok" value="Готово">
				<input type="button" id="ksenmart-map-clear" value="Сбросить">
			</div>
		</div>	
		<div id="ksenmart-map-layer"></div>
	</div>
</div>	