<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Auths\Basics\User;

/**
 * Responsibility - base user model class features.
 * This trait is necessary to use to be able to login into your app.
 */
trait Features {
	
	use \MvcCore\Ext\Auths\Basics\User\Base;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Base;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Permissions;
	use \MvcCore\Ext\Auths\Basics\User\Auth;
	use \MvcCore\Ext\Auths\Basics\User\Roles;
}
