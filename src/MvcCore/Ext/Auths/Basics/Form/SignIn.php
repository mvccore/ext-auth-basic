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

namespace MvcCore\Ext\Auths\Basics\Form;

/**
 * Trait for class `\MvcCore\Ext\Auths\Basics\SignInForm`. Trait contains:
 * - `Init()` method to initialize all necessary sign in form fields.
 * - `Submit()` method to handle sign-in form submit request (`POST` by default).
 */
trait SignIn {

	/**
	 * Initialize all form fields, initialize hidden field with
	 * sourceUrl for cases when in request params is any source URL param.
	 * To return there after form is submitted.
	 * @param bool $submit
	 * @return \MvcCore\Ext\Auths\Basics\SignInForm
	 */
	public function Init ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\Auths\Basics\SignInForm */
		parent::Init($submit);
		$this
			->initAuthFormPropsAndHiddenControls()
			->AddField(new \MvcCore\Ext\Forms\Fields\Text([
				'name'			=> 'username',
				'placeHolder'	=> 'User',
				'validators'	=> ['SafeString'],
			]))
			->AddField(new \MvcCore\Ext\Forms\Fields\Password([
				'name'			=> 'password',
				'placeHolder'	=> 'Password',
				// do not use 'SafeString' here - it converts special chars in 
				// password string into entities:
				'validators'	=> [], 
			]))
			->AddField(new \MvcCore\Ext\Forms\Fields\SubmitButton([
				'name'			=> 'send',
				'value'			=> 'Sign In',
				'cssClasses'	=> ['button'],
			]));
		return $this;
	}

	/**
	 * Sign in submit - if there is any user with the same password imprint
	 * store user in session for next requests, if there is not - wait for
	 * three seconds and then go to error page.
	 * @param array $rawRequestParams
	 * @return array
	 */
	public function Submit (array & $rawRequestParams = []) {
		/** @var $this \MvcCore\Ext\Auths\Basics\SignInForm */
		parent::Submit($rawRequestParams);
		$data = & $this->values;
		
		if ($this->result) {
			// now sent values are safe strings,
			// try to get use by username and compare password hashes:
			$userClass = $this->auth->GetUserClass();
			$user = $userClass::LogIn(
				isset($data['username']) ? $data['username'] : '', 
				isset($data['password']) ? $data['password'] : ''
			);
			if ($user === NULL) {
				$errorMsg = 'User name or password is incorrect.';
				if ($this->translate)
					$errorMsg = $this->Translate('User name or password is incorrect.');
				$this->AddError(
					$errorMsg,
					['username', 'password']
				);
			}
		}

		$successUrl = (isset($data['sourceUrl']) 
			? $data['sourceUrl'] 
			: (isset($data['successUrl']) 
				? $data['successUrl'] 
				: ''));
		if ($successUrl) $this->SetSuccessUrl($successUrl);
		if (isset($data['errorUrl']) && $data['errorUrl'])
			$this->SetErrorUrl($data['errorUrl']);
		if (!$this->result)
			sleep($this->auth->GetInvalidCredentialsTimeout());

		return [
			$this->result,
			$this->values,
			$this->errors
		];
	}
}
