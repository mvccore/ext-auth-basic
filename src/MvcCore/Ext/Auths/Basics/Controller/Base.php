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

namespace MvcCore\Ext\Auths\Basics\Controller;

/**
 * Responsibility - handle configured sign-in form and sign-out form submit requests.
 */
trait Base {

	/**
	 * Authentication form submit action to sign in.
	 * Routed by configured route by:
	 * `\MvcCore\Ext\Auths\Basic::GetInstance()->SetSignInRoute(...);`
	 * @return void
	 */
	public function SignInAction () {
		/** @var $this \MvcCore\Ext\Auths\Basics\Controller */
		$form = \MvcCore\Ext\Auths\Basic::GetInstance()->GetSignInForm();
		list ($result,) = $form->Submit();
		if ($result !== \MvcCore\Ext\Form::RESULT_SUCCESS) {
			// here you can count bad login requests
			// to ban danger user for some time or anything else...

		}
		if ($result) $form->ClearSession(); // to remove all submitted data from session
		$form->SubmittedRedirect();
	}

	/**
	 * Authentication form submit action to sign out.
	 * Routed by configured route by:
	 * `\MvcCore\Ext\Auths\Basic::GetInstance()->SetSignOutRoute(...);`
	 * @return void
	 */
	public function SignOutAction () {
		/** @var $this \MvcCore\Ext\Auths\Basics\Controller */
		$form = \MvcCore\Ext\Auths\Basic::GetInstance()->GetSignOutForm();
		list ($result,) = $form->Submit();
		if ($result) $form->ClearSession(); // to remove all submitted data from session
		$form->SubmittedRedirect();
	}
}
