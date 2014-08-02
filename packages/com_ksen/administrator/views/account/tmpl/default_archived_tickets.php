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
                    <div class="content">
                        <div class="pull-left row">
                            <a href="javascript:void(0);" class="btn btn-other open_tickets-l toggleLink disable">Открыть тикет</a>
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
                                    if($this->archived_tickets){
                                        foreach($this->archived_tickets as $archived_ticket){ ?>
                                        <tr<?php echo isset($archived_ticket->unread)?' class="new"':''; ?> data-id="<?php echo $archived_ticket->id; ?>">
                                            <td class="id"><?php echo $archived_ticket->id; ?></td>
                                            <td class="subject titleTableItem">
                                                <div class="title"><?php echo $archived_ticket->subject; ?></div>
                                                <div class="subBlock"><a href="javascript:void(0);" title="открыть тикет заново" class="open_tickets-l" data-id="<?php echo $archived_ticket->id; ?>">открыть тикет заново</a></div>    
                                            </td>
                                            <td class="category"><?php echo $archived_ticket->category; ?></td>
                                            <td class="datelast"><?php echo $archived_ticket->datelast; ?></td>
                                            <td class="status">
                                                <?php if(isset($archived_ticket->unread)){ ?>
                                                есть ответ
                                                <?php }elseif(isset($archived_ticket->open)){ ?>
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