<?php defined('_JEXEC') or die; ?>
<div class="form" id="new_ticket_form">
    <div class="row clearfix">
        <label class="inputname">Категория</label>
        <select class="sel" style="width: 300px;" name="category" required="true">
            <option value="0">Выберите категорию</option>
            <option value="6">L.D.M. & Co: [3000.00 RUB] Ksenmart - платная поддержка</option>
            <option value="5" selected="">L.D.M. & Co: [0.00 RUB] Ksenmart support</option>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Продукт/Услуга</label>
        <select class="sel" name="product" required="true">
            <option value="0">Выберите категорию</option>
            <?php foreach($this->services as $service){ ?>
            <option value="<?php echo $service->id; ?>"><?php echo $service->name; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="row-fluid">
        <label class="inputname">Тема</label>
        <input type="text" name="subject" class="inputbox_470" required="true" />
    </div>
    <div class="row-fluid">
        <label class="inputname intn_b">Текст сообщения</label>
        <textarea name="text" class="textarea" required="true"></textarea>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
    	var params = {
    			changedEl: ".sel", 
    		} 
    	cuSel(params);
        
        var current_popup = jQuery('.popup.ticket_create');
        
        current_popup.parent().on('submit', function(e){

            e.preventDefault();
            
            var form = current_popup.find('#new_ticket_form'); 
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createTicket';
            
            var category = form.find('input[name="category"]').val();
            var product  = form.find('input[name="product"]').val();
            var subject  = form.find('input[name="subject"]').val();
            var text     = form.find('textarea').val();
            
            var data = {category: category, product: product, subject: subject, text: text};
            
            jQuery.post(
    			href,
                data,
    			function(data){
    			    if(data != ''){
                        //loading_block.fadeOut(400, function(){jQuery(this).remove()});
    			    }else{
    			        form.find('textarea').val('');
                        location.reload();
    			    }
    			}
    		);
        });
    });
</script>