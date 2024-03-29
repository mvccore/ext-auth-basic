<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Auths\Basics;

/**
 * Responsibility - simple class to only load user instance from configurable database table structure.
 */
interface IDatabaseUser {

	/**
	 * Set database table structure how to load user from db.
	 * @param  string|NULL    $tableName   Database table name.
	 * @param  \string[]|NULL $columnNames Keys are user class protected properties names in camel case, values are database columns names.
	 * @return void
	 */
	public static function SetUsersTableStructure ($tableName = NULL, $columnNames = NULL);
}
