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
                    <div class="content tickets_list">
                        <div class="clearfix btn-group top">
                            <a href="javascript:void(0);" class="btn btn-add create_ticket">Создать сообщение</a>
                            <a href="javascript:void(0);" class="btn btn-other archived-l toggleLink disable">Поместить в архив</a>
                        </div>
                        <div class="list">
                            <table class="cat">
                                <thead>
                                    <tr>
                                        <th>Код запроса</th>
                                        <th>Тема сообщения</th>
                                        <th>Категория</th>
                                        <th>Дата</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($this->tickets){
                                        foreach($this->tickets as $ticket){ ?>
                                        <tr<?php echo isset($ticket->unread)?' class="new"':''; ?> data-id="<?php echo $ticket->id; ?>">
                                            <td class="id"><?php echo $ticket->id; ?></td>
                                            <td class="subject titleTableItem">
                                                <div class="title"><?php echo $ticket->subject; ?></div>
                                                <div class="subBlock"><a href="javascript:void(0);" class="archived-l" title="поместить в архив" data-id="<?php echo $ticket->id; ?>">поместить в архив</a></div>    
                                            </td>
                                            <td class="category"><?php echo $ticket->category; ?></td>
                                            <td class="datelast"><?php echo $ticket->datelast; ?></td>
                                            <td class="status">
                                                <?php if(isset($ticket->unread)){ ?>
                                                есть ответ
                                                <?php }elseif(isset($ticket->open)){ ?>
                                                ждет ответа
                                                <?php }else{ ?>
                                                закрыт
                                                <?php } ?>
                                            </td>
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