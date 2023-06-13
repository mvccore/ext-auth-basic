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

namespace MvcCore\Ext\Auths\Basics\Form;

/**
 * Trait for class `\MvcCore\Ext\Auths\Basics\SignInForm` and `\MvcCore\Ext\Auths\Basics\SignOutForm`. Trait contains:
 * - Protected property `$auth` to use authentication module more flexible for fields init.
 * - method `initAuthHiddenFields()` always called from `Init()` method to init hidden fields for success URL and error url.
 * @mixin \MvcCore\Ext\Auths\Basics\SignInForm|\MvcCore\Ext\Auths\Basics\SignOutForm
 */
trait Base {

	/**
	 * @var \MvcCore\Ext\Auths\Basic
	 */
	protected $auth = NULL;

	/**
	 * Hidden input with success URL value.
	 * @var \MvcCore\Ext\Forms\Fields\Hidden
	 */
	protected $successUrlField = NULL;

	/**
	 * Hidden input with error URL value.
	 * @var \MvcCore\Ext\Forms\Fields\Hidden
	 */
	protected $errorUrlField = NULL;

	/**
	 * Hidden input with source URL value.
	 * @var \MvcCore\Ext\Forms\Fields\Hidden
	 */
	protected $sourceUrlField = NULL;

	/**
	 * Add success and error URL which are used
	 * to redirect user to success URL or error URL
	 * after form is submitted.
	 * @return \MvcCore\Ext\Form
	 */
	protected function initAuthHiddenFields () {
		$urlValidator = (new \MvcCore\Ext\Forms\Validators\Url)
			->SetAllowedHostnames($this->GetRequest()->GetHostName());
		$this->successUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'successUrl',
			'value'			=> $this->auth->GetSignedInUrl(),
			'validators'	=> [$urlValidator],
		]);
		$this->errorUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'errorUrl',
			'value'			=> $this->auth->GetSignErrorUrl(),
			'validators'	=> [$urlValidator],
		]);
		$this->sourceUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'sourceUrl',
			'validators'	=> [$urlValidator],
		]);
		$this->AddFields(
			$this->successUrlField, 
			$this->errorUrlField, 
			$this->sourceUrlField
		);
		return $this;
	}

	/**
	 * Prepare form for rendering.
	 * @param  bool $submit
	 * @return void
	 */
	public function PreDispatch ($submit = FALSE) {
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_INITIALIZED) 
			return;
		parent::PreDispatch($submit);
		if ($submit) {
			$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED;
			return;
		}

		$successUrlValue = $this->successUrlField->GetValue();
		if ($successUrlValue) {
			$this->auth->SetSignedInUrl(rawurlencode($successUrlValue));
			$this->successUrlField->SetValue(rawurlencode($successUrlValue));
		} else {
			$successUrlValue = $this->auth->GetSignedInUrl();
			if (!$successUrlValue)
				$successUrlValue = htmlspecialchars($this->request->GetFullUrl());
			$this->successUrlField->SetValue(rawurlencode($successUrlValue));
		}
		
		$errorUrlValue = $this->errorUrlField->GetValue();
		if ($errorUrlValue) {
			$this->auth->SetSignErrorUrl(rawurlencode($errorUrlValue));
			$this->errorUrlField->SetValue(rawurlencode($errorUrlValue));
		} else {
			$errorUrlValue = $this->auth->GetSignErrorUrl();
			if (!$errorUrlValue)
				$errorUrlValue = htmlspecialchars($this->request->GetFullUrl());
			$this->errorUrlField->SetValue(rawurlencode($errorUrlValue));
		}

		$sourceUrl = $this->request->GetParam('sourceUrl', FALSE, '', 'string');
		while (preg_match("#%[0-9a-fA-F]{2}#", $sourceUrl)) {
			if (json_encode(rawurldecode($sourceUrl)) === FALSE) break;
			$sourceUrl = rawurldecode($sourceUrl);
		}
		$sourceUrl = str_replace('%', '%25', $sourceUrl);

		$toolClass = $this->application->GetToolClass();
		$parsedSourceUrlHost = $toolClass::ParseUrl($sourceUrl, PHP_URL_HOST);
		if ($parsedSourceUrlHost === $this->request->GetHostName()) 
			$this->sourceUrlField->SetValue(rawurlencode($sourceUrl));
	}
}
