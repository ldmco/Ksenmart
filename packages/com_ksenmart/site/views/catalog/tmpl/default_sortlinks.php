<?php defined('_JEXEC') or die(); ?>
<div class="sort">
	<ul class="unstyled">
		<li class="pull-left"><span><?php echo JText::_('KSM_SORT_BY'); ?>:</span></li>
		<?php foreach($this->sort_links as $type => $sort_link): ?>
		<li class="pull-left sortlink-<?php echo $type; ?>"><?php echo $sort_link['link']; ?> <span class="divider">/</span></li>
		<?php endforeach; ?>
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'grid'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="grid">
                <i class="icon-th-large"></i>
            </a>
        </li>
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'list_ext'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="list_ext">
                <i class="icon-th-list"></i>
            </a>
        </li>
        <li class="pull-right layout_icon<?php echo $this->layout_view == 'list'?' active':''; ?>">
            <a href="javascript:void(0);" class="layout_show" data-layout="list">
                <i class="icon-list"></i>
            </a>
        </li>
	</ul>
</div>