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
 * - Instance property `$admin`.
 * @mixin \MvcCore\Ext\Auths\Basics\User
 */
trait AdminProps {

	/**
	 * `TRUE` if user is administrator. Administrator has always allowed everything.
	 * Default value is `FALSE`.
	 * @column admin
	 * @var bool
	 */
	#[Attrs\Column('admin')]
	protected $admin = FALSE;

}
