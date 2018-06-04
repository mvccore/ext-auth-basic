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

namespace MvcCore\Ext\Auths\Basics\Form;

/**
 * Trait for class `\MvcCore\Ext\Auths\Basics\SignInForm`. Trait contains:
 * - `Init()` method to initialize all necessary sign in form fields.
 * - `Submit()` method to handle signin form submit request (`POST` by default).
 */
trait SignIn
{
	/**
	 * Initialize all form fields, initialize hidden field with
	 * sourceUrl for cases when in request params is any source url param.
	 * To return there after form is submitted.
	 * @return \MvcCore\Ext\Auths\Basics\SignInForm
	 */
	public function Init () {
		parent::Init();
		$this
			->initAuthFormPropsAndHiddenControls()
			->AddField(new \MvcCore\Ext\Forms\Fields\Text([
				'name'			=> 'username',
				'placeholder'	=> 'User',
				'validators'	=> ['SafeString'],
			]))
			->AddField(new \MvcCore\Ext\Forms\Fields\Password([
				'name'			=> 'password',
				'placeholder'	=> 'Password',
				'validators'	=> ['SafeString'],
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
		parent::Submit($rawRequestParams);
		$data = & $this->values;

		if ($this->result) {
			// now sended values are safe strings,
			// try to get use by username and compare password hashes:
			$userClass = $this->auth->GetUserClass();
			$user = $userClass::LogIn(
				isset($data['username']) ? $data['username'] : '', 
				isset($data['password']) ? $data['password'] : ''
			);
			if ($user !== NULL) {
				$user->SetPasswordHash(NULL);
			} else {
				$this->AddError(
					'User name or password is incorrect.',
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
