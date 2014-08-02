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
                    <div class="content domains">
                        <div class="top clearfix btn-group">
                            <a href="javascript:void(0);" class="btn btn-add new_domain-l">Создать домен</a>
                            <a href="javascript:void(0);" class="btn btn-other l-domain_edit singleLink disable">Изменить</a>
                            <a href="javascript:void(0);" class="btn btn-other l-rerun_domain singleLink disable">Оплатить</a>
                            <a href="javascript:void(0);" class="btn btn-other l-renew_domain singleLink disable">Продлить</a>
                        </div>
                        <div class="list clearfix"><?php echo $this->loadTemplate('list'); ?></div>
                    </div>
				</div>	
			</td>	
		</tr>	
	</table>	
</div>
<script>
    jQuery(document).ready(function(){        
        jQuery('.domains .list thead tr th').on('click', function(){
            NProgress.start();
            var order_field = jQuery(this).data();
            if(Object.keys(order_field).length > 0){
                getDomains(order_field);
            }
        });
    });
</script>