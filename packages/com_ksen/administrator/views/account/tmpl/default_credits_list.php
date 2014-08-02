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
                    <div class="content credits">
                        <div class="clearfix btn-group top">
                            <a href="javascript:void(0);" class="btn btn-add new_credit-l">Создать платеж</a>
                            <a href="javascript:void(0);" class="btn btn-other payment-l singleLink disable">Оплатить</a>
                            <a href="javascript:void(0);" class="btn btn-remove remove_payment-l toggleLink disable">Удалить</a>
                        </div>
                        <div class="list clearfix">
                            <table class="cat">
                                <thead>
                                    <tr>
                                        <th>Номер счета</th>
                                        <th>Дата</th>
                                        <th>Получатель</th>
                                        <th>Метод оплаты</th>
                                        <th>Сумма</th>
                                        <th>Сумма в валюте платежа</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($this->credits){
                                        foreach($this->credits as $credit){
                                            switch($credit->state){
                                                case 'Оплачен':
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
                                        <tr data-id="<?php echo $credit->id; ?>" data-type="<?php echo strtolower($credit->ctype); ?>" data-state="<?php echo mb_strtolower($credit->state); ?>" class="dblclick">
                                            <td><?php echo $credit->num; ?></td>
                                            <td><?php echo $credit->cdate; ?></td>
                                            <td><?php echo $credit->recipient; ?></td>
                                            <td><?php echo $credit->ctype; ?></td>
                                            <td><?php echo $credit->amount; ?></td>
                                            <td><?php echo $credit->nativeamount; ?></td>
                                            <td<?php echo $class; ?>><?php echo $credit->state; ?></td>
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