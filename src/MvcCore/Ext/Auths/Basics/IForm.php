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

namespace MvcCore\Ext\Auths\Basics;

/**
 * Responsibility - base authentication sign-in/sign-out form class specification.
 */
interface IForm extends \MvcCore\Ext\IForm {

	/**
	 * Default html `<form>` element id for authentication sign in form.
	 */
	const HTML_ID_SIGNIN = 'authentication_signin';

	/**
	 * Default html `<form>` element id for authentication sign out form.
	 */
	const HTML_ID_SIGNOUT = 'authentication_signout';
}
