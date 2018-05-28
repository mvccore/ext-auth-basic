<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Auths\Basics;

/**
 * Responsibility - initialize necessary sign out form fields and handle sign out form submit.
 */
class SignOutForm
	extends		\MvcCore\Ext\Form
	implements	\MvcCore\Ext\Auths\Basics\IForm
{
	protected $id = \MvcCore\Ext\Auths\Basics\IForm::HTML_ID_SIGNOUT;

	/** @var \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser */
	protected $user = NULL;

	use			\MvcCore\Ext\Auths\Basics\Form\Base;
	use			\MvcCore\Ext\Auths\Basics\Form\SignOut;
}
