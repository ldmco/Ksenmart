<?php 
defined( '_JEXEC' ) or die;
?>
<div id="ksenmart-map" class="modal hide fade">
	<div id="ksenmart-map-header" class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>Уточните свой адрес</h3>
	</div>
	<div id="ksenmart-map-inner" class="modal-body">
		<div id="ksenmart-map-actions">
			<input type="text" id="ksenmart-map-to" />
			<div id="ksenmart-map-search"></div>
			<input type="button" id="ksenmart-map-to-center" class="btn" value="Москва">
			<input type="button" id="ksenmart-map-to-area" class="btn" value="Область">
			<input type="button" id="ksenmart-map-to-me" class="btn" value="Найти меня">
			<input type="button" id="ksenmart-map-ok" class="btn btn-success" value="Готово">
			<input type="button" id="ksenmart-map-clear" class="btn btn-warning" value="Сбросить">
		</div>	
		<div id="ksenmart-map-layer"></div>
	</div>
</div>