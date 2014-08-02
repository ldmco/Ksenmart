<script>
    var auth = <?php echo $this->auth; ?>;
</script>
<?php
    if(
        $this->layout == 'ticket' ||
        $this->layout == 'ticket_create' || 
        $this->layout == 'credit_create' || 
        $this->layout == 'domain_create' || 
        $this->layout == 'credit_qiwi_pay' || 
        $this->layout == 'vhost_create' || 
        $this->layout == 'profile' || 
        $this->layout == 'settings' ||
        $this->layout == 'domain_rerun' || 
        $this->layout == 'domain_edit' ||
        $this->layout == 'domain_renew' || 
        $this->layout == 'avatar_load'
    ){
	   echo $this->loadTemplate($this->layout);
       return;
    }
?>
<?php if($this->layout != 'default'){ ?>
    <div class="clearfix panel">
        <div class="pull-left">
            <?php echo KSSystem::loadModules('ks-top-left'); ?>
        </div>
        <div class="pull-right">
            <?php echo KSSystem::loadModules('ks-top-right'); ?>
        </div>
        <div class="row-fluid">
            <?php echo KSSystem::loadModules('ks-top-bottom'); ?>
        </div>
    </div>
    <?php echo $this->loadTemplate($this->layout); ?>
<?php } ?>