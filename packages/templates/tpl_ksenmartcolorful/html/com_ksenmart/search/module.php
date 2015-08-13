<?php defined( '_JEXEC' ) or die; ?>
<div class="relevants">
<?php
    echo $this->loadTemplate('relevant_search');
    echo $this->loadTemplate('cat_search');
    echo $this->loadTemplate('manufacture_search');
?>
</div>
<?php echo $this->loadTemplate('results'); ?>