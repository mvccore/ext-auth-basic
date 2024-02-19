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
 * - Instance property `$permissions`.
 * @mixin \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
 */
trait PermissionsProps {

	/**
	 * Array of strings or array with permissions ids as keys and
	 * permissions names as values, describing what is allowed to do for user or role.
	 * @column permissions
	 * @var array|array<int,string>|\string[]
	 */
	#[Attrs\Column('permissions')]
	protected $permissions = [];
}
