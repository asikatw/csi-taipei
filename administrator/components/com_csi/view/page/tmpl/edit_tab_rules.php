<?php
/**
 * Part of Component Csi files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

$tab       = $data->tab;
$fieldsets = $data->form->getFieldsets();
?>

<?php echo JHtmlBootstrap::addTab('pageEditTab', $tab, \JText::_($data->view->option . '_EDIT_' . strtoupper($tab))) ?>

<div class="row-fluid">
	<div class="span12">
		<?php echo $this->loadTemplate('fieldset', array('fieldset' => $fieldsets['rules'], 'class' => 'form-horizontal')); ?>
	</div>
</div>

<?php echo JHtmlBootstrap::endTab(); ?>
