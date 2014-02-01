<div class="form" id="new_vhost_form">
    <div class="desc">Обратите внимание, что в процессе заказа все цены указываются без всевозможных скидок. Итоговая сумма будет рассчитана на последнем шаге заказа.</div>
    <div class="row clearfix">
        <label class="inputname">Тарифный план</label>
        <select  class="sel" name="price">
            <option value="10">L.D.M. &amp; Co - Standart</option>
            <option value="18">L.D.M. &amp; Co - Premium</option>
            <option value="26">L.D.M. &amp; Co - Privat</option>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Период оплаты</label>
            <select class="sel" name="period">
                <option value="5">1 месяц 300.0000 RUB</option>
                <option value="6">3 месяца 700.0000 RUB</option>
                <option value="7">6 месяцев 1300.0000 RUB</option>
                <option value="8">1 год 2000.0000 RUB</option>
            </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Промо код</label>
        <input type="text" name="promocode" class="inputbox" />
    </div>
    <div class="desc">Введите любое имеющееся у вас доменное имя или доменное имя, которое вы планируете зарегистрировать. Вне зависимости от выбранного доменного имени, на сервере можно будет разместить множество любых других доменов.</div>
    <div class="row clearfix">
        <label class="inputname">Доменное имя</label>
        <input type="text" name="domain" class="inputbox" required="true" autofocus="true" />
    </div>
    <div class="desc">
        <p>Ниже вы можете заказать дополнительные ресурсы для заказываемой услуги. По умолчанию во всех полях введены цифры объёма ресурсов, предусмотренных вашим тарифным планом. При желании вы можете увеличить их.</p>
        <p>В будущем вы самостоятельно сможете добавить или отказаться от любых дополнительных ресурсов.</p>
    </div>
    <div class="row clearfix">
        <label class="inputname">Disk space</label>
        <input type="text" name="addon_11" class="inputbox prepend" required="true" value="3000" />
        <span class="prepend"> Mb</span>
    </div>
    <div class="row clearfix">
        <label class="inputname">IP-address</label>
        <input type="text" name="addon_13" class="inputbox prepend" required="true" value="0" />
        <span class="prepend"> Unit</span>
    </div>
    <div class="row clearfix">
        <label class="inputname">Web-domain limit</label>
        <input type="text" name="addon_14" class="inputbox prepend" required="true" value="2" />
        <span class="prepend"> Unit</span>
    </div>
    <div class="row clearfix">
        <label class="inputname">Database limit</label>
        <input type="text" name="addon_15" class="inputbox prepend" required="true" value="2" />
        <span class="prepend"> Unit</span>
    </div>
    <div class="row clearfix">
        <label class="inputname">Mail box limit</label>
        <input type="text" name="addon_16" class="inputbox prepend" required="true" value="1000" />
        <span class="prepend"> Unit</span>
    </div>
    <div class="row clearfix">
        <label class="inputname">Memory</label>
        <input type="text" name="addon_17" class="inputbox prepend" required="true" value="100" />
        <span class="prepend"> Mb</span>
    </div>
    <input type="hidden" name="autoprolong" value="5" />
    <input type="hidden" name="payfrom" value="account1" />
</div>
<script>
    jQuery(document).ready(function(){
        
    	var params = {
    			changedEl: ".sel", 
    		} 
    	cuSel(params);
        
        var current_popup = jQuery('.popup.new_vhost');
        
        current_popup.parent().on('submit', function(e){

            e.preventDefault();
            NProgress.start();
            
            var form = current_popup.find('#new_vhost_form');

            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createVHost';
            
            var price       = form.find('input[name="price"]').val();
            var period      = form.find('input[name="period"]').val();
            var promocode   = form.find('input[name="promocode"]').val();
            var domain      = form.find('input[name="domain"]').val();
            var addon_11    = form.find('input[name="addon_11"]').val();
            var addon_13    = form.find('input[name="addon_13"]').val();
            var addon_14    = form.find('input[name="addon_14"]').val();
            var addon_15    = form.find('input[name="addon_15"]').val();
            var addon_16    = form.find('input[name="addon_16"]').val();
            var addon_17    = form.find('input[name="addon_17"]').val();
            var autoprolong = form.find('input[name="autoprolong"]').val();
            var payfrom     = form.find('input[name="payfrom"]').val();
            
            var data = {
                    price       : price, 
                    period      : period, 
                    promocode   : promocode, 
                    domain      : domain,
                    addon_11    : addon_11, 
                    addon_13    : addon_13, 
                    addon_14    : addon_14, 
                    addon_15    : addon_15,
                    addon_16    : addon_16, 
                    addon_17    : addon_17,
                    autoprolong : autoprolong,
                    payfrom     : payfrom,
            };

            jQuery.ajax({
    			type: 'POST',
                url: href,
                data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
    			success: function(data){
    			    if(data == ''){
                        var popup       = current_popup;
                        var popup_class = popup.attr('class').replace('popup ', '');
            
                        popup.fadeOut(400, function(){
                            jQuery(this).parent().remove();
                            jQuery('.overlay.'+popup_class).fadeOut(400, function(){
                                jQuery(this).remove();
                            });
                        });
    			    }else{
                        createPopupNotice(data, '.new_vhost');
    			    }
                    NProgress.done();
    			}
    		});
        });
    });
</script>