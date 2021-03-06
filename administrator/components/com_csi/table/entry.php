<?php
/**
 * Part of Component Csi files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Csi\Helper\EnginepageHelper;
use Csi\Helper\PageHelper;
use Windwalker\Joomla\DataMapper\DataMapper;
use Windwalker\Table\Table;

// No direct access
defined('_JEXEC') or die;

/**
 * Entry Table class.
 *
 * @since 1.0
 */
class CsiTableEntry extends Table
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct('#__csi_entries');
	}

	/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 *
	 * @throws  InvalidArgumentException
	 * @throws  RuntimeException
	 * @throws  UnexpectedValueException
	 */
	public function load($keys = null, $reset = true)
	{
		return parent::load($keys, $reset);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  InvalidArgumentException
	 */
	public function bind($src, $ignore = array())
	{
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		return parent::check();
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/store
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}

	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  UnexpectedValueException
	 */
	public function delete($pk = null)
	{
		try
		{
			$this->_db->transactionStart(true);

			$result = parent::delete($pk);

			if (!$result)
			{
				return $result;
			}

			// Prepare Mappers
			$taskMapper = new DataMapper('#__csi_tasks', 'id', $this->_db);
			$enginepageMapper = new DataMapper('#__csi_enginepages');
			$pageMapper = new DataMapper('#__csi_pages');
			$queueMapper = new DataMapper('#__csi_queues');
			$resultMapper = new DataMapper('#__csi_results');
			$historyMapper = new DataMapper('#__csi_histories');

			// Delete tasks
			$taskMapper->delete(array('entry_id' => $this->id));

			// Delete pages
			$pageMapper->delete(array('entry_id' => $this->id));

			// Delete Results
			$resultMapper->delete(array('entry_id' => $this->id));

			// Delete enginepages
			$enginepageMapper->delete(array('entry_id' => $this->id));

			// Delete queues
			$queueMapper->delete(array('entry_id' => $this->id));

			// Delete histories
			$historyMapper->delete(array('entry_id' => $this->id));

			// Delete page files
			$folder = JPATH_ROOT . '/' . PageHelper::getFileFolder() . '/' . $this->id;

			if (is_dir($folder))
			{
				\JFolder::delete($folder);
			}

			// Delete Enginepage
			$folder = JPATH_ROOT . '/' . EnginepageHelper::getFileFolder() . '/' . $this->id;

			if (is_dir($folder))
			{
				\JFolder::delete($folder);
			}
		}
		catch (\Exception $e)
		{
			$this->_db->transactionRollback(true);

			throw $e;
		}

		$this->_db->transactionCommit(true);

		return true;
	}
}
