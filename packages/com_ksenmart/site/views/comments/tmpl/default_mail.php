<table class="cellpadding">
	<tr>
		<td colspan="2">Информация</td>
	</tr>
	<tr>
		<td>Имя:</td>
		<td><?php echo $this->comment->name; ?></td>
	</tr>	
	<tr>
		<td>Комментарий:</td>
		<td><?php echo $this->comment->comment; ?></td>
	</tr>
	<tr>
		<td>Достоинства:</td>
		<td><?php echo $this->comment->good; ?></td>
	</tr>
	<tr>
		<td>Недостатки:</td>
		<td><?php echo $this->comment->bad; ?></td>
	</tr>
</table>	