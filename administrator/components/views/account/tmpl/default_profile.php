<div class="cpanel" class="pull-left">
<?php if($this->user_info) { ?>
    <div class="item double settings-l">
        <div class="wrapp clearfix">
            <div class="icon_block avatar">
                <a href="javascript:void(0);" title="Изменить аватар">
                    <?php echo $this->user_info->avatar; ?>
                </a>
            </div>
            <div class="content">
                <div class="title"><?php echo $this->user_info->realname; ?></div>
                <ul>
                    <li><?php echo $this->profile_info->country; ?></li>
                    <?php if(!empty($this->user_info->phone)){ ?>
                    <li><?php echo $this->user_info->phone; ?></li>
                    <?php } ?>
                    <li>
                        <a href="javascript:void(0);"><?php echo $this->user_info->email; ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bottom clearfix">
            <a href="javascript:void(0);" title="Изменить аватар" class="l-avatar_load pull-left">изменить аватар</a>
            <a href="javascript:void(0);" title="Настройки" class="pull-right settings-l">Настройки</a>
        </div>
    </div>
<?php } ?>
    <div class="item getAllMessage">
        <div class="wrapp clearfix">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Мои Сообщения">
                    <img src="./components/com_ksenmart/css/i/mail.png" height="85" />
                </a>
            </div>
            <div class="content">
                <div class="title">Мои  сообщения</div>
            </div>
        </div>
        <div class="bottom clearfix">
            <a href="javascript:void(0);" title="архив" class="pull-right getArchivedTickets">архив</a>
        </div>
    </div>
    <div class="item double getAccountActivity">
        <div class="wrapp clearfix">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Движения по счету">
                    <img src="./components/com_ksenmart/css/i/balans.png" height="85" />
                </a>
            </div>
            <div class="content">
                <div class="title">Движения средств</div>
                <small>На счету</small>
                <div class="title"><?php echo round($this->user_balance->balance, 2); ?> <?php echo mb_substr(mb_strtolower($this->user_balance->currency), 0, 3); ?></div>
            </div>
        </div>
        <div class="bottom clearfix">
            <a href="javascript:void(0);" title="пополнить счет" class="pull-right new_credit-l">пополнить счет</a>
        </div>
    </div>
<!--    <div class="item">
        <div class="wrapp clearfix">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Список лицензий">
                    <img src="http://room9.ldmco.ru//media/ksenmart/images/icons/sendmails.png" height="85" />
                </a>
            </div>
            <div class="content">
                <small>У Вас:</small>
                <div class="title">5 лицензий</div>
            </div>
        </div>
        <div class="bottom clearfix">
            <a href="javascript:void(0);" title="показать все" class="pull-right">показать все</a>
        </div>
    </div>
-->
    <div class="item getVHost">
        <div class="wrapp clearfix full_height">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Хостинг">
                    <img src="./components/com_ksenmart/css/i/Hardware.png" height="85" />
                </a>
            </div>
            <div class="content">
                <div class="title">Хостинг</div>
            </div>
        </div>
    </div>
    <div class="item getDomains">
        <div class="wrapp clearfix full_height">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Домены">
                    <img src="./components/com_ksenmart/css/i/domain.png" height="85" />
                </a>
            </div>
            <div class="content">
                <div class="title">Домены</div>
            </div>
        </div>
    </div>
    <div class="item create_ticket">
        <div class="wrapp clearfix full_height">
            <div class="icon_block">
                <a href="javascript:void(0);" title="Создать тикет">
                    <img src="./components/com_ksenmart/css/i/support.png" height="85" />
                </a>
            </div>
            <div class="content">
                <div class="title">Тех поддержка</div>
            </div>
        </div>
    </div>
</div>