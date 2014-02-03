<div class="account_info_block clearfix">
    <div class="item">
        <a href="javascript:void(0);" title="Профиль пользователя" class="settings-l">
            <?php echo $account_info->user_info->realname; ?>
        </a>
    </div>
    <div class="item">
        <a href="index.php?option=com_ksenmart&view=account&layout=credits_list" title="" class="getAccountActivity-none">
            <?php echo number_format($account_info->user_balance_info->balance, 2, ',', ' '); ?> <?php echo mb_substr(mb_strtolower($account_info->user_balance_info->currency), 0, 3); ?>
        </a>
    </div>
    <div class="item">
        <a href="index.php?option=com_ksenmart&view=account&layout=tickets_list" class="getAllMessage-none">
            <?php echo $account_info->user_open_tickets == 0?'Мои сообщения':$account_info->user_open_tickets.' новых сообщений'; ?>
        </a>
    </div>
    <div class="item">
        <a href="index.php?option=com_ksenmart&task=account.logout" class="" title="Выход">Выход</a>
    </div>
</div>