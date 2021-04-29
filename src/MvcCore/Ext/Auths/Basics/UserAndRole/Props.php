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
 * - Instance properties `$id` and `$active`.
 * @mixin \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
 */
trait Props {

	/**
	 * User or role unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @column id
	 * @keyPrimary
	 * @var int|NULL
	 */
	#[Attrs\Column('id'), Attrs\KeyPrimary]
	protected $id = NULL;

	/**
	 * User or role active state boolean.
	 * @column active
	 * @var bool
	 */
	#[Attrs\Column('active')]
	protected $active = TRUE;
}
