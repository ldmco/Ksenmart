<?php defined('_JEXEC') or die; ?>
<div class="form list" id="domaincontact_create_form">
    <div class="row clearfix">
        <label class="inputname">Плательщика</label>
        <select name="customertype" class="js-customertype selDC">
            <option value="company">Компания</option>
            <option value="person" selected="selected">Частное лицо</option>
            <option value="generic">Базовый</option>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Название</label>
        <input class="inputbox" type="text" name="custname" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Фамилия</label>
        <input class="inputbox" type="text" name="lastname_ru" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Имя</label>
        <input class="inputbox" type="text" name="firstname_ru" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Отчество</label>
        <input class="inputbox" type="text" name="middlename_ru" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Страна</label>
        <select class="selDC" name="la_country" required="true" style="width: 284px !important;">
            <?php foreach($this->countries as $key => $country){ ?>
                <option value="<?php echo $key; ?>"<?php echo $key==182?'selected="true"':''; ?>><?php echo $country; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="row clearfix">
        <label class="inputname">Электронная почта</label>
        <input class="inputbox" type="text" name="email" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Номер телефона</label>
        <input class="inputbox" type="text" name="phone" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Мобильный телефон (sms)</label>
        <input class="inputbox" type="text" name="mobile" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Факс</label>
        <input class="inputbox" type="text" name="fax" />
    </div>
    <div class="desc">Паспортные данные</div>
    <div class="row clearfix">
        <label class="inputname">Серия и номер</label>
        <input class="inputbox" type="text" name="passport_series" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Кем выдан</label>
        <input class="inputbox" type="text" name="passport_org" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Дата выдачи</label>
        <input class="inputbox" type="date" name="passport_date" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Дата рождения</label>
        <input class="inputbox" type="date" name="birthdate" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">ИНН</label>
        <input class="inputbox" type="text" name="inn" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Скрыть персональные данные</label>
        <input type="checkbox" name="private" />
    </div>
    <div class="desc">Введите почтовый индекс, область, город и адрес контакта на русском языке если Вы являетесь резидентом РФ.</div>
    <div class="row clearfix">
        <label class="inputname">Индекс</label>
        <input class="inputbox" type="text" name="la_postcode" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Область</label>
        <input class="inputbox" type="text" name="la_state" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Город</label>
        <input class="inputbox" type="text" name="la_city" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Адрес</label>
        <input class="inputbox" type="text" name="la_address" />
    </div>
    <div class="desc">Почтовый адрес администратора домена (адрес может не совпадать с указанным в паспорте местом регистрации, либо учредительными документами). В поле Получатель почты необходимо ОБЯЗАТЕЛЬНО указать фамилию и инициалы получателя либо название организации. </div>
    <div class="row clearfix">
        <label class="inputname">Индекс</label>
        <input class="inputbox" type="text" name="pa_postcode" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Область</label>
        <input class="inputbox" type="text" name="pa_state" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Город</label>
        <input class="inputbox" type="text" name="pa_city" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Адрес</label>
        <input class="inputbox" type="text" name="pa_address" required="true" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Получатель почты</label>
        <input class="inputbox" type="text" name="pa_addressee" required="true" />
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        var params = {
                changedEl: ".selDC", 
            }
        cuSel(params);
        
        var current_popup = jQuery('.popup.domaincontact_create');
        var dataSend = {};
        
        current_popup.parent().on('submit', function(e){
            e.preventDefault();
            
            var form = current_popup.find('#domaincontact_create_form'); 
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.createDomainContact';
            
            var customertype    = form.find('[name="customertype"]').val();
            var custname        = form.find('[name="custname"]').val();
            var lastname_ru     = form.find('[name="lastname_ru"]').val();
            var firstname_ru    = form.find('[name="firstname_ru"]').val();
            var middlename_ru   = form.find('[name="middlename_ru"]').val();
            var la_country      = form.find('[name="la_country"]').val();
            var email           = form.find('[name="email"]').val();
            var phone           = form.find('[name="phone"]').val();
            var mobile          = form.find('[name="mobile"]').val();
            var fax             = form.find('[name="fax"]').val();
            var passport_series = form.find('[name="passport_series"]').val();
            
            var passport_org    = form.find('[name="passport_org"]').val();
            var passport_date   = form.find('[name="passport_date"]').val();
            var birthdate       = form.find('[name="birthdate"]').val();
            var inn             = form.find('[name="inn"]').val();
            var private_i       = form.find('[name="private"]').val();
            var la_postcode     = form.find('[name="la_postcode"]').val();
            var la_state        = form.find('[name="la_state"]').val();
            var la_city         = form.find('[name="la_city"]').val();
            var la_address      = form.find('[name="la_address"]').val();
            var pa_postcode     = form.find('[name="pa_postcode"]').val();
            var pa_state        = form.find('[name="pa_state"]').val();
            
            var pa_city         = form.find('[name="pa_city"]').val();
            var pa_address      = form.find('[name="pa_address"]').val();
            var pa_addressee    = form.find('[name="pa_addressee"]').val();
            
            dataSend.customertype = customertype; 
            dataSend.custname = custname; 
            dataSend.lastname_ru = lastname_ru; 
            dataSend.firstname_ru = firstname_ru;
            dataSend.middlename_ru = middlename_ru; 
            dataSend.la_country = la_country;
            dataSend.email = email;
            dataSend.phone = phone;  
            dataSend.mobile = mobile; 
            dataSend.fax = fax;
            dataSend.passport_series = passport_series; 
            dataSend.passport_org = passport_org;
            dataSend.passport_date = passport_date;
            dataSend.birthdate = birthdate;
            dataSend.inn = inn;
            dataSend.private_i = private_i; 
            dataSend.la_postcode = la_postcode;
            dataSend.la_state = la_state;
            dataSend.la_city = la_city;
            dataSend.la_address = la_address;
            dataSend.pa_postcode = pa_postcode;
            dataSend.pa_state = pa_state;
            dataSend.pa_city = pa_city;
            dataSend.pa_address = pa_address;
            dataSend.pa_addressee = pa_addressee;
            
            jQuery.ajax({
                type: 'POST',
                url: href,
                data: dataSend,
                dataType: 'json',
                success: function(data){
                    if(data.result == 'ok'){
                        dataSend.id = data.id;
                        var dc_cusel = jQuery('#step_page_3 .cusel.domain_contacts');
                        dc_cusel.find('.cusel-scroll-pane#cusel-scroll-cuSel-0').append(jQuery('<span val="'+data.id+'">'+data.name+'</span>'));   
                        var params = {
                            refreshEl: "#step_page_3 .cusel.domain_contacts",
                            visRows: 4
                        };

                        console.log(dc_cusel.find('.cusel-scroll-pane#cusel-scroll-cuSel-0 span').length);
                        if(dc_cusel.find('.cusel-scroll-pane#cusel-scroll-cuSel-0 span').length == 1){
                            dc_cusel.find('.cuselText').text(data.name);
                            dc_cusel.find('input[name="customer"]').val(data.id);
                            jQuery('#step_page_3 .btn-dsbl').removeClass('btn-dsbl');
                        }

                        cuSelRefresh(params);
                        
                        var popup_class = current_popup.attr('class').replace('popup ', '');
                        current_popup.fadeOut(400, function(){
                            jQuery(this).parent().remove();
                            jQuery('.overlay.'+popup_class).fadeOut(400, function(){
                                jQuery(this).remove();
                            });
                        });
                    }
                }
            });
        });
    });
</script>