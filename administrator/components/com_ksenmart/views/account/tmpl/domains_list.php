<table class="cat">
    <thead>
        <tr>
            <th data-by="id">Код</th>
            <th data-by="name">Имя</th>
            <th data-by="expire">Дата окончания регистрации</th>
            <th data-by="autoperiod">Автопродление</th>
            <th data-by="domainstatus">Статус домена</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($this->domains){
            foreach($this->domains as $domain){
                switch($domain->domainstatus){
                    case 'Обрабатывается (На регистрации)':
                        $class = ' class="state-in_process"';
                    break;
                    case 'Делегирован (Активен)':
                        $class = ' class="state-success"';
                    break;
                    case 'Зарегистрирован (Неделегирован)':
                        $class = ' class="state-no_pay"';
                    break;
                    case 'Удален':
                        $class = ' class="state-in_process"';
                    break;
                    case 'Не оплачен':
                        $class = ' class="state-no_pay"';
                    break;
                    default:
                        $class = ' class="state"';
                    break;
                }
        ?>
            <tr<?php echo isset($domain->unread)?' class="new"':''; ?> data-id="<?php echo $domain->id; ?>" class="dblclick">
                <td><?php echo $domain->id; ?></td>
                <td><?php echo $domain->name; ?></td>
                <td><?php echo $domain->expire; ?></td>
                <td><?php echo $domain->autoperiod; ?></td>
                <td<?php echo $class; ?>><?php echo $domain->domainstatus; ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>