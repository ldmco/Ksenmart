<div class="form qiwi" id="qiwi_form">
    <div class="desc">Укажите десятизначный номер вашего мобильного телефона - это последние десять цифр вашего федерального номера (пример - 9991234567). После нажатия на кнопку "Ok" вам необходимо пройти в личный кабинет своего Qiwi кошелька и оплатить выписанный счет.</div>
    <div class="row clearfix">
        <label class="inputname">Сумма</label>
        <input type="text" name="amount" readonly="true" value="<?php echo $this->credit->amount; ?>" class="inputbox" />
    </div>
    <div class="row clearfix">
        <label class="inputname">Номер мобильного телефона</label>
        <input type="text" name="phoneid" required="true" class="inputbox" />
    </div>
    <div class="row clearfix">
        <label class="inputname" for="alertsms_checkbox">SMS оповещение</label>
        <input class="checkbox" id="alertsms_checkbox" type="checkbox" name="alertsms" />
    </div>
    <input type="hidden" name="elid" value="<?php echo $this->credit->id; ?>" />
</div>