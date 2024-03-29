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

namespace MvcCore\Ext\Auths\Basics\Role;

use \MvcCore\Ext\Database\Attributes as Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\Role` class. Trait contains:
 * - public getter and setter for `$name` instance property.
 * @mixin \MvcCore\Ext\Auths\Basics\Role
 */
trait GettersSetters {

	/**
	 * Get unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @return string
	 */
	public function GetName () {
		return $this->name;
	}

	/**
	 * Set unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @param  string $name
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetName ($name) {
		$this->name = $name;
		return $this;
	}
}
