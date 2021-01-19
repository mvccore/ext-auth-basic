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

namespace MvcCore\Ext\Auths\Basics\UserAndRole;

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` and `\MvcCore\Ext\Auths\Basics\Role` class. Trait contains:
 * - Public getters and setters for instance properties `$id` and `$active`.
 */
trait GettersSetters {

	/**
	 * User unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @return int|NULL
	 */
	public function GetId () {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		return $this->id;
	}

	/**
	 * Set user unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @param int|NULL $id
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetId ($id) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		$this->id = $id;
		return $this;
	}

	/**
	 * Get user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * This function is only alias for `$user->GetActive();`.
	 * @return bool
	 */
	public function IsActive () {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		return $this->active;
	}

	/**
	 * Get user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * @return bool
	 */
	public function GetActive () {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		return $this->active;
	}

	/**
	 * Set user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetActive ($active) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		$this->active = (bool) $active;
		return $this;
	}
}
