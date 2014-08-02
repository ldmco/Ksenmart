<?php defined('_JEXEC') or die; ?>
<?php
	$left_block = KSSystem::loadModules('km-list-left')
?>
<div id="center">
	<table id="cat" width="100%">
		<tr>
            <?php if(!empty($left_block)){ ?>
			<td width="250" class="left-column">
				<div id="tree">
					<form id="list-filters">
						<ul>
							<?php echo $left_block; ?>
						</ul>
					</form>			
				</div>	
			</td>
            <?php } ?>
			<td valign="top">
				<div id="content">
                    <div class="vhosts content">
                        <div class="top clearfix btn-group">
                            <a href="javascript:void(0);" class="btn btn-add new_vhost-l">Заказать хостинг</a>
                            <a href="javascript:void(0);" class="btn btn-other go_vhost-l singleLink disable">Перейти на сервер</a>
                            <a href="javascript:void(0);" class="btn btn-remove remove_vhost-l toggleLink disable">Удалить</a>
                        </div>
                        <div class="list clearfix">
                            <table class="cat">
                                <thead>
                                    <tr>
                                        <th>Код</th>
                                        <th>Доменное имя</th>
                                        <th>Имя пользователя</th>
                                        <th>Тарифный план</th>
                                        <th>Действует до</th>
                                        <th>Состояние</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($this->vhosts){
                                        foreach($this->vhosts as $vhost){
                                            switch($vhost->status){
                                                case 'Активен':
                                                    $class = ' class="state-success"';
                                                break;
                                                case 'Не оплачен':
                                                    $class = ' class="state-no_pay"';
                                                break;
                                                case 'Оплачивается':
                                                    $class = ' class="state-in_process"';
                                                break;
                                            }
                                    ?>
                                        <tr<?php echo isset($vhost->unread)?' class="new"':''; ?> data-id="<?php echo $vhost->id; ?>" data-username="<?php echo $vhost->username; ?>">
                                            <td><?php echo $vhost->id; ?></td>
                                            <td><?php echo $vhost->domain; ?></td>
                                            <td><?php echo $vhost->username; ?></td>
                                            <td><?php echo $vhost->price; ?></td>
                                            <td><?php echo $vhost->expiredate; ?></td>
                                            <td<?php echo $class; ?>><?php echo $vhost->status; ?></td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>	
			</td>	
		</tr>	
	</table>	
</div>