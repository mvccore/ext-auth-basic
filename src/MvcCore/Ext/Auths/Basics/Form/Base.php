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
 * Trait for class `\MvcCore\Ext\Auths\Basics\SignInForm` and `\MvcCore\Ext\Auths\Basics\SignOutForm`. Trait contains:
 * - Protected property `$auth` to use authentication module more flexible for fields init.
 * - method `initAuthFormPropsAndHiddenControls()` always called from `Init()` method to init hidden fields for success URL and error url.
 */
trait Base
{
	/**
	 * @var \MvcCore\Ext\Auths\Basic|\MvcCore\Ext\Auths\Basics\IAuth
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
	 * @return \MvcCore\Ext\Form|\MvcCore\Ext\Forms\IForm
	 */
	protected function initAuthFormPropsAndHiddenControls () {
		/** @var $this \MvcCore\Ext\Forms\IForm */
		$this->auth = \MvcCore\Ext\Auths\Basic::GetInstance();
		$this->successUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'successUrl',
			'value'			=> $this->auth->GetSignedInUrl(),
			'validators'	=> ['Url'],
		]);
		$this->errorUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'errorUrl',
			'value'			=> $this->auth->GetSignErrorUrl(),
			'validators'	=> ['Url'],
		]);
		$this->sourceUrlField = new \MvcCore\Ext\Forms\Fields\Hidden([
			'name'			=> 'sourceUrl',
			'validators'	=> ['Url'],
		]);
		$this->AddFields($this->successUrlField, $this->errorUrlField, $this->sourceUrlField);
		return $this;
	}

	/**
	 * Prepare form for rendering.
	 * @return \MvcCore\Ext\Form|\MvcCore\Ext\Forms\IForm
	 */
	public function PreDispatch () {
		/** @var $this \MvcCore\Ext\Forms\IForm */
		if ($this->dispatchState > 1) return $this;
		parent::PreDispatch();

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
		while (preg_match("#%[0-9a-zA-Z]{2}#", $sourceUrl)) 
			$sourceUrl = rawurldecode($sourceUrl);
		$parsedSourceUrl = parse_url($sourceUrl);
		if (
			$parsedSourceUrl !== NULL && 
			isset($parsedSourceUrl['host']) && 
			$parsedSourceUrl['host'] === $this->request->GetHostName()
		) 
			$this->sourceUrlField->SetValue(rawurlencode($sourceUrl));
		return $this;
	}
}
