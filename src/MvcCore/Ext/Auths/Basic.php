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

namespace MvcCore\Ext\Auths;

/**
 * Responsibility - managing login/logout forms, authentication requests and user instance.
 * - Basic extensible authentication module with sign in and sign out forms
 *   and automatically initialized user instance stored in custom session namespace.
 * - Possibility to configure:
 *   - submit routes to sign in and sign out
 *   - submit success and submit error URL addresses
 *   - form classes
 *   - forms submit's controller class
 *   - user instance class
 *   - wrong credentials timeout
 *   - custom password hash salt
 *   - translator and more...
 */
class Basic implements \MvcCore\Ext\Auths\IBasic {

	/**
	 * MvcCore Extension - Auth - Basic - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.3.1';

	use \MvcCore\Ext\Auths\Basic\PropsGettersSetters;
	use \MvcCore\Ext\Auths\Basic\Handling;
}
