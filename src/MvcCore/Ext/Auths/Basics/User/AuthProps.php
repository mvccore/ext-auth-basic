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
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Static property `$sessionIdentity` with their public setter and getter with expiration settings.
 * - Static property `$sessionAuthorization` with their public setter and getter with expiration settings.
 */
trait AuthProps {

	/**
	 * MvcCore session namespace instance
	 * to get/clear username record from session
	 * to load user for authentication.
	 * @var \MvcCore\Session
	 */
	protected static $sessionIdentity = NULL;

	/**
	 * MvcCore session namespace instance
	 * to get/set authentication boolean record
	 * about authenticated/not authenticated user.
	 * @var \MvcCore\Session
	 */
	protected static $sessionAuthorization = NULL;

	/**
	 * MvcCore cached session class string.
	 * @var string
	 */
	private static $_sessionClass = NULL;
}
