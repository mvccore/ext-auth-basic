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

namespace MvcCore\Ext\Auths\Basics\User;

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Public getters and setters for instance property `$admin`.
 * @mixin \MvcCore\Ext\Auths\Basics\User
 */
trait AdminMethods {

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function IsAdmin () {
		return $this->admin;
	}

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function GetAdmin () {
		return $this->admin;
	}

	/**
	 * Set user to Administrator. Administrator has always allowed everything.
	 * @param  bool $admin `TRUE` by default.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetAdmin ($admin = TRUE) {
		$this->admin = (bool) $admin;
		return $this;
	}
}
