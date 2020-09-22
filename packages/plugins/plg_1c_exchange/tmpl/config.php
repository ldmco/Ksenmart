<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app    = JApplicationCms::getInstance('site');
$router = $app::getRouter('site');
$uri    = $router->build('index.php?option=com_ksenmart&view=catalog&Itemid=' . KSSystem::getShopItemid());
$path   = str_replace(JUri::base(true) . '/', JUri::root(), $uri->getPath());
?>
<form method="post" class="form">
    <table class="cat" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th align="left" style="position:relative;">
				<?php echo JText::_('ksm_exportimport_1c_exchange_settings') ?>
            </th>
        </tr>
        <thead>
        <tbody>
        <tr>
            <td class="rightcol" style="background:#f9f9f9!important;padding:15px 10px;">
                <div class="row">
					<?php echo JText::_('ksm_exportimport_1c_exchange_path') ?>: <?php echo $path; ?>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="type" value="1c_exchange"/>
    <input type="hidden" name="step" value="saveconfig"/>
</form>