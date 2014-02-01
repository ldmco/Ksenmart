<div class="form" id="new_domain_form">
    <div class="desc">Введите необходимое доменное имя и выберите один из доменов верхнего уровня для него. Доменное имя должно содержать от 2 до 63 символов, начинаться и заканчиваться буквой латинского алфавита или цифрой, промежуточными символами могут быть буквы, цифры или дефис; доменное имя не может содержать дефисы одновременно в 3-й и 4-й позициях.</div>
    <div class="row clearfix">
        <label class="inputname">Доменное имя</label>
        <input type="text" name="domain" class="inputbox" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Домен верхнего уровня</label>
        <select class="sel" name="price">
            <option value="180">net.ru</option>
            <option value="32">COM</option>
            <option value="51">com.ru</option>
            <option value="53">info</option>
            <option value="54">me</option>
            <option value="50">msk.ru</option>
            <option value="38">NET</option>
            <option value="28">RU</option>
            <option value="36">SU</option>
            <option value="30">РФ</option>
            <option value="52">xxx</option>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Промо код</label>
        <input type="text" name="promocode" class="inputbox" required="true" />
    </div>
    <input type="hidden" name="operation" value="register" />
</div>
<script>
    jQuery(document).ready(function(){
    	var params = {
    			changedEl: ".sel", 
    		} 
    	cuSel(params);
        
        var current_popup = jQuery('.popup.domain_create');
        
        current_popup.parent().on('submit', function(e){

            e.preventDefault();
            
            var form = current_popup.find('#new_domain_form'); 
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createdomain';
            
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
    			        textarea.val('');
                        location.reload();
    			    }
    			}
    		);
        });
    });
</script>