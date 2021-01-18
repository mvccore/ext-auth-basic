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
 * - `$name` property, it's public getter and setter.
 * - public `IsAllowed()` method.
 */
trait Base {

	/**
	 * Unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @column name
	 * @keyUnique
	 * @var string
	 */
	#[Attrs\Column('name'), Attrs\KeyUnique]
	protected $name = NULL;

	/**
	 * Get unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @return string
	 */
	public function GetName () {
		/** @var $this \MvcCore\Ext\Auths\Basics\Role */
		return $this->name;
	}

	/**
	 * Set unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @param string $name
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetName ($name) {
		/** @var $this \MvcCore\Ext\Auths\Basics\Role */
		$this->name = $name;
		return $this;
	}
}
