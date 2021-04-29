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

/**
 * Responsibility - base user model class features.
 * This trait is necessary to use to be able to login into your app.
 * @mixin \MvcCore\Ext\Auths\Basics\User
 */
trait Features {
	
	use \MvcCore\Ext\Auths\Basics\User\Props;
	use \MvcCore\Ext\Auths\Basics\User\GettersSetters;

	use \MvcCore\Ext\Auths\Basics\UserAndRole\Props;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\GettersSetters;

	use \MvcCore\Ext\Auths\Basics\UserAndRole\PermissionsProps;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\PermissionsMethods;
	
	use \MvcCore\Ext\Auths\Basics\User\RolesProps;
	use \MvcCore\Ext\Auths\Basics\User\RolesMethods;

	use \MvcCore\Ext\Auths\Basics\User\AuthProps;
	use \MvcCore\Ext\Auths\Basics\User\AuthMethods;
}
