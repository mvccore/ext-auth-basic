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
 * Responsibility - base authentication signin/signout form class specification.
 */
interface IForm
{
	/**
	 * Default html `<form>` element id for authentication sign in form.
	 */
	const HTML_ID_SIGNIN = 'authentication_signin';

	/**
	 * Default html `<form>` element id for authentication sign out form.
	 */
	const HTML_ID_SIGNOUT = 'authentication_signout';

	/**
	 * Set form id, required to configure.
	 * Used to identify session data, error messages,
	 * CSRF tokens, html form attribute id value and much more.
	 * @requires
	 * @param string $id
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetId ($id);

	/**
	 * Set form submitting URL value.
	 * It could be relative or absolute, anything
	 * to complete classic html form attribute `action`.
	 * @param string|NULL $url
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetAction ($url = NULL);

	/**
	 * Set form http submitting method.`POST` by default. 
	 * Use `GET` only if form data contains only ASCII characters.
	 * Possible values: `'POST' | 'GET'`
	 * You can use constants:
	 * - `\MvcCore\Ext\Forms\IForm::METHOD_POST`
	 * - `\MvcCore\Ext\Forms\IForm::METHOD_GET`
	 * @param string $method
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetMethod ($method = \MvcCore\Ext\Forms\IForm::METHOD_POST);

	/**
	 * Set form HTML element css classes strings.
	 * All previously defined css classes will be removed.
	 * Default value is an empty array to not render HTML `class` attribute.
	 * You can define css classes as single string, more classes separated 
	 * by space or you can define css classes as array with strings.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetCssClasses ($cssClasses);

	/**
	 * Set form success submit URL string to redirect after, relative or absolute,
	 * to specify, where to redirect user after form has been submitted successfully.
	 * It's required to use `\MvcCore\Ext\Form` like this, if you want to use method
	 * `$form->SubmittedRedirect();`, at the end of custom `Submit()` method implementation,
	 * you need to specify at least success and error URL strings.
	 * @param string|NULL $url
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetSuccessUrl ($url = NULL);

	/**
	 * Set form error submit URL string, relative or absolute, to specify,
	 * where to redirect user after has not been submitted successfully.
	 * It's not required to use `\MvcCore\Ext\Form` like this, but if you want to use method
	 * `$form->SubmittedRedirect();` at the end of custom `Submit()` method implementation,
	 * you need to specify at least success and error URL strings.
	 * @param string|NULL $url
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetErrorUrl ($url = NULL);

	/**
	 * Set translator to translate field labels, options, placeholders and error messages.
	 * Translator has to be `callable` (it could be `closure function` or `array`
	 * with `classname/instance` and `method name` string). First argument
	 * of `callable` has to be a translation key and second argument
	 * has to be language string (`en`, `de` ...) to translate the key into.
	 * Result of `callable` object has to be a string - translated key for called language.
	 * @param callable|NULL $handler
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & SetTranslator (callable $translator = NULL);

	/**
	 * Render whole `<form>` with all content into HTML string to display it.
	 * - If form is not initialized, there is automatically
	 *   called `$form->Init();` method.
	 * - If form is not pre-dispatched for rendering, there is
	 *   automatically called `$form->Predispatch();` method.
	 * - Create new form view instance and set up the view with local
	 *   context variables.
	 * - Render form naturally or by custom template.
	 * - Clean session errors, because errors should be rendered
	 *   only once, only when it's used and it's now - in this rendering process.
	 * @return string
	 */
	public function Init ();

	/**
	 * Add multiple fully configured form field instances,
	 * function have infinite params with new field instances.
	 * @param \MvcCore\Ext\Forms\IField[] $fields,... Any `\MvcCore\Ext\Forms\IField` fully configured instance to add into form.
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & AddFields (/* ...$fields */);

	/**
	 * Add fully configured form field instance.
	 * @param \MvcCore\Ext\Forms\IField $field
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & AddField (\MvcCore\Ext\Forms\IField $field);

	/**
	 * Process standard low level submit process.
	 * If no params passed as first argument, all params from object
	 * `\MvcCore\Application::GetInstance()->GetRequest()` are used.
	 * - If fields are not initialized - initialize them by calling `$form->Init();`.
	 * - Check max. post size by php configuration if form is posted.
	 * - Check cross site request forgery tokens with session tokens.
	 * - Process all field values and their validators and call `$form->AddError()` where necessary.
	 *	 `AddError()` method automatically switch `$form->Result` property to zero - `0`, it means error submit result.
	 * Return array with form result, safe values from validators and errors array.
	 * @param array $rawRequestParams optional
	 * @return array Array to list: `array($form->Result, $form->Data, $form->Errors);`
	 */
	public function Submit (array & $rawRequestParams = []);

	/**
	 * Clear form values to empty array and clear form values in form session namespace,
	 * clear form errors to empty array and clear form errors in form session namespace and
	 * clear form CSRF tokens clear CRSF tokens in form session namespace.
	 * @return \MvcCore\Ext\Forms\IForm
	 */
	public function & ClearSession ();

	/**
	 * Call this function in custom `\MvcCore\Ext\Form::Submit();` method implementation
	 * at the end of custom `Submit()` method to redirect user by configured success/error/prev/next
	 * step URL address into final place and store everything into session.
	 * You can also to redirect form after submit by yourself.
	 * @return void
	 */
	public function SubmittedRedirect ();

	/**
	 * Rendering process alias for `\MvcCore\Ext\Form::Render();`.
	 * @return string
	 */
	public function __toString ();

	/**
	 * Render whole `<form>` with all content into HTML string to display it.
	 * - If form is not initialized, there is automatically
	 *   called `$form->Init();` method.
	 * - If form is not pre-dispatched for rendering, there is
	 *   automatically called `$form->Predispatch();` method.
	 * - Create new form view instance and set up the view with local
	 *   context variables.
	 * - Render form naturally or by custom template.
	 * - Clean session errors, because errors should be rendered
	 *   only once, only when it's used and it's now - in this rendering process.
	 * @return string
	 */
	public function Render ($controllerDashedName = '', $actionDashedName = '');
}
