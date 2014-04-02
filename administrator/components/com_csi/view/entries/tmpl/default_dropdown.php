<?php
/**
 * Part of Component Csi files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Bootstrap\Dropdown;

// No direct access
defined('_JEXEC') or die;

/**
 * Prepare data for this template.
 *
 * @var $data Windwalker\Data\Data
 * @var $grid Windwalker\View\Helper\GridHelper
 * @var $item Windwalker\Data\Data
 */
$grid = $data->grid;
$item = $grid->current;

// Duplicate
Dropdown::duplicate($grid->row, 'entries.batch');

Dropdown::divider();

// Published & Unpublished
if ($item->state)
{
	Dropdown::unpublish($grid->row, 'entries.state');
}
else
{
	Dropdown::publish($grid->row, 'entries.state');
}

// Trash & Delete
if (JDEBUG || $data->state->get('filter.entry.state') == -2)
{
	Dropdown::addCustomItem(\JText::_('JTOOLBAR_DELETE'), 'delete', $grid->row, 'entries.state.delete');
}
else
{
	Dropdown::trash($grid->row, 'entries.state');
}

// Render it
echo Dropdown::render();
