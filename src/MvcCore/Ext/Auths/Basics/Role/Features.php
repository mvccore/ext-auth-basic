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

/**
 * Responsibility - base role model trait.
 * This trait is necessary to use and implement method
 * `GetByName()` or more. It's also necessary to implement
 * loading users with roles and their permissions to be able
 * to check user roles and permissions.
 */
trait Features {
	
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Base;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Permissions;
	use \MvcCore\Ext\Auths\Basics\Role\Base;
}