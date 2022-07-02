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

interface IBasic {

	/**
	 * Return singleton instance. If instance exists, return existing instance,
	 * if not, create new basic authentication module instance, store it and return it.
	 * @param array $configuration Optional configuration passed into method
	 *                             `\MvcCore\Ext\Auths\Basic::__construct($configuration)`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public static function GetInstance ($configuration = []);

	/**
	 * Get expiration time (in seconds) how long to remember the user name in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 1 month (30 days, 2592000 seconds).
	 * @return int
	 */
	public function GetExpirationIdentity ();

	/**
	 * Get expiration time (in seconds) how long to remember the authorization in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 10 minutes (600 seconds).
	 * @return int
	 */
	public function GetExpirationAuthorization ();

	/**
	 * Get full class name to use for user instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IUser`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\User`.
	 * @return string|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function GetUserClass ();

	/**
	 * Get full class name to use for user role class.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IRole`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Role`.
	 * @return string|\MvcCore\Ext\Auths\Basics\IRole
	 */
	public function GetRoleClass ();

	/**
	 * Get full class name to use for controller instance
	 * to submit authentication form(s). Class name has to implement interfaces:
	 * - `\MvcCore\Ext\Auths\Basics\IController`
	 * - `\MvcCore\IController`
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Controller`.
	 * @return string|\MvcCore\Ext\Auths\Basics\IController
	 */
	public function GetControllerClass ();

	/**
	 * Get full class name to use for sign in form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignInForm`.
	 * @return string|\MvcCore\Ext\Auths\Basics\IForm
	 */
	public function GetSignInFormClass ();

	/**
	 * Full class name to use for sign out form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignOutForm`.
	 * @return string|\MvcCore\Ext\Auths\Basics\IForm
	 */
	public function GetSignOutFormClass ();

	/**
	 * Get full URL to redirect user, after sign in
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in form rendered.
	 * @return string|NULL
	 */
	public function GetSignedInUrl ();

	/**
	 * Get full URL to redirect user, after sign out
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign out form rendered.
	 * @return string|NULL
	 */
	public function GetSignedOutUrl ();

	/**
	 * Get full URL to redirect user, after sign in POST
	 * request or sign out POST request was not successful,
	 * for example wrong credentials.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in/out form rendered.
	 * @param string $signErrorUrl
	 * @return string|NULL
	 */
	public function GetSignErrorUrl ();

	/**
	 * Get route instance to submit sign in form into.
	 * Default configured route for sign in request is `/signin` by POST.
	 * @return \MvcCore\Route
	 */
	public function GetSignInRoute ();

	/**
	 * Get route to submit sign out form into.
	 * Default configured route for sign in request is `/signout` by POST.
	 * @return \MvcCore\Route
	 */
	public function GetSignOutRoute ();

	/**
	 * Get configured salt for `passord_hash();` to generate password by `PASSWORD_BCRYPT`.
	 * `NULL` by default. This option is the only one option required
	 * to configure authentication module to use it properly.
	 * @deprecated
	 * @return string|NULL
	 */
	public function GetPasswordHashSalt ();

	/**
	 * Get timeout to `sleep();` PHP script before sending response to user,
	 * when user submitted invalid username or password.
	 * Default value is `3` (3 seconds).
	 * @return int
	 */
	public function GetInvalidCredentialsTimeout ();

	/**
	 * Get configured callable translator to set it into authentication form
	 * to translate form labels, placeholders, buttons or error messages.
	 * Default value is `NULL` (forms without translations).
	 * @return callable|NULL
	 */
	public function GetTranslator ();

	/**
	 * Get authenticated user model instance reference
	 * or `NULL` if user has no username record in session namespace.
	 * If user has not yet been initialized, load the user internally by
	 * `{$configuredUserClass}::SetUpUserBySession();` to try to load
	 * user by username record in session namespace.
	 * @return \MvcCore\Ext\Auths\Basics\User|NULL
	 */
	public function GetUser ();

	/**
	 * Return `TRUE` if user is authenticated/signed in,
	 * `TRUE` if user has any username record in session namespace.
	 * If user has not yet been initialized, load the user internally by
	 * `$auth->GetUser();` to try to load user by username record in session namespace.
	 * @return bool
	 */
	public function IsAuthenticated ();

	/**
	 * Return completed sign in form instance.
	 * Form instance completion is processed only once,
	 * created form instance is stored in `$auth->form` property.
	 * @return \MvcCore\Ext\Auths\Basics\Form\SignIn
	 */
	public function GetSignInForm ();

	/**
	 * Return completed sign out form instance.
	 * Form instance completion is processed only once,
	 * created form instance is stored in `$auth->form` property.
	 * @return \MvcCore\Ext\Auths\Basics\Form\SignOut
	 */
	public function GetSignOutForm ();

	/**
	 * Return `array` with all protected configuration properties.
	 * @return array<string, mixed>
	 */
	public function GetConfiguration ();

	/**
	 * Set expiration time (in seconds) how long to remember the user name in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 1 month (30 days, 2592000 seconds).
	 * @param  int $identityExpirationSeconds
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetExpirationIdentity ($identityExpirationSeconds = 2592000);


	/**
	 * Set expiration time (in seconds) how long to remember the authorization in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 10 minutes (600 seconds).
	 * @param  int $authorizationExpirationSeconds
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetExpirationAuthorization ($authorizationExpirationSeconds = 600);

	/**
	 * Set full class name to use for user instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IUser`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\User`.
	 * @param  string $userClass User full class name implementing `\MvcCore\Ext\Auths\Basics\IUser`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetUserClass ($userClass);

	/**
	 * Set full class name to use for user role class.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IRole`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Role`.
	 * @param  string $roleClass Role full class name implementing `\MvcCore\Ext\Auths\Basics\IRole`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetRoleClass ($roleClass);

	/**
	 * Set full class name to use for controller instance
	 * to submit authentication form(s). Class name has to implement interfaces:
	 * - `\MvcCore\Ext\Auths\Basics\IController`
	 * - `\MvcCore\IController`
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Controller`.
	 * @param  string $controllerClass Controller full class name implementing `\MvcCore\Ext\Auths\Basics\IController`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetControllerClass ($controllerClass);

	/**
	 * Set full class name to use for sign in form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignInForm`.
	 * @param  string $signInFormClass Form full class name implementing `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignInFormClass ($signInFormClass);

	/**
	 * Set full class name to use for sign out form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignOutForm`.
	 * @param  string $signInFormClass Form full class name implementing `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignOutFormClass ($signOutFormClass);

	/**
	 * Set full URL to redirect user, after sign in
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in form rendered.
	 * @param  string|NULL $signedInUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignedInUrl ($signedInUrl);

	/**
	 * Set full URL to redirect user, after sign out
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign out form rendered.
	 * @param  string|NULL $signedOutUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignedOutUrl ($signedOutUrl);

	/**
	 * Set full URL to redirect user, after sign in POST
	 * request or sign out POST request was not successful,
	 * for example wrong credentials.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in/out form rendered.
	 * @param  string|NULL $signErrorUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignErrorUrl ($signErrorUrl);

	/**
	 * Set route instance to submit sign in form into.
	 * Default configured route for sign in request is `/signin` by POST.
	 * @param  string|array<string, string>|\MvcCore\Route $signInRoute
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignInRoute ($signInRoute);

	/**
	 * Set route to submit sign out form into.
	 * Default configured route for sign in request is `/signout` by POST.
	 * @param  string|array<string, string>|\MvcCore\Route $signOutRoute
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignOutRoute ($signOutRoute);

	/**
	 * Set configured salt for `passord_hash();` to generate password by `PASSWORD_BCRYPT`.
	 * `NULL` by default. This option is the only one option required
	 * to configure authentication module to use it properly.
	 * @param  string $passwordHashSalt
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetPasswordHashSalt ($passwordHashSalt);

	/**
	 * Set timeout to `sleep();` PHP script before sending response to user,
	 * when user submitted invalid username or password.
	 * Default value is `3` (3 seconds).
	 * @param  int $seconds
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetInvalidCredentialsTimeout ($seconds = 3);

	/**
	 * Set callable translator to set it into authentication form
	 * to translate form labels, placeholders or buttons.
	 * Default value is `NULL` (forms without translations).
	 * @param  callable $translator
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetTranslator (callable $translator = NULL);

	/**
	 * Set user instance manually. If you use this method
	 * no authentication by `{$configuredUserClass}::SetUpUserBySession();`
	 * is used and authentication state is always positive.
	 * @param  \MvcCore\Ext\Auths\Basics\User|NULL $user
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetUser (\MvcCore\Ext\Auths\Basics\IUser $user = NULL);

	/**
	 * Set up authorization module configuration.
	 * Each array key has to be key by protected configuration property in this class.
	 * All properties are one by one configured by it's setter method.
	 * @param  array<string, mixed> $configuration                     Keys by protected properties names in camel case.
	 * @param  bool                 $throwExceptionIfPropertyIsMissing
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetConfiguration ($configuration, $throwExceptionIfPropertyIsMissing = TRUE);

	/**
	 * Optional alias method if you have user class configured
	 * to database user: `\MvcCore\Ext\Auths\Basics\Users\Database`.
	 * Alias for `\MvcCore\Ext\Auths\Basics\Users\Database::SetUsersTableStructure($tableName, $columnNames);`.
	 * @param string|NULL	$tableName Database table name.
	 * @param string[]|NULL	$columnNames Keys are user class protected properties names in camel case, values are database columns names.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetTableStructureForDbUsers ($tableName, $columnNames);
}
