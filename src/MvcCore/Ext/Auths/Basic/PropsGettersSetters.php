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

namespace MvcCore\Ext\Auths\Basic;

/**
 * Trait for `\MvcCore\Ext\Auths\Basic` class. Trait contains:
 * - All static properties.
 * - All instance configurable properties except `protected $autoInit` property from `\MvcCore\Model`.
 * - All instance non-configurable properties for internal use.
 * - Getters for non-configurable and configurable instance properties.
 * - Setters for configurable properties with interface implementation checking for class name properties.
 * - Setters for non-configurable instance properties.
 */
trait PropsGettersSetters {

	/***********************************************************************************
	 *								Static Properties								*
	 ***********************************************************************************/

	/**
	 * Singleton instance of authentication extension module.
	 * @var \MvcCore\Ext\Auths\Basic|NULL
	 */
	protected static $instance = NULL;

	/**
	 * Shortcut for configured core tool class value
	 * from `\MvcCore\Application::GetInstance()->GetToolClass();`.
	 * @var string|NULL
	 */
	protected static $toolClass = NULL;

	/**
	 * Properties names which are internal properties
	 * or internal instances for authentication module,
	 * which are not configuration properties, instance properties only.
	 * This array is used only in `\MvcCore\Ext\Auth::GetConfiguration();`.
	 * @var array
	 */
	protected static $nonConfigurationProperties = [
		'userInitialized', 'application', 'user', 'form',
	];


	/***********************************************************************************
	 *							Configuration Properties							 *
	 ***********************************************************************************/

	/**
	 * Expiration time (in seconds) how long to remember the user name in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 1 month (30 days, 2592000 seconds).
	 * @var int
	 */
	protected $expirationIdentity = 2592000;

	/**
	 * Expiration time (in seconds) how long to remember the authorization in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 10 minutes (600 seconds).
	 * @var int
	 */
	protected $expirationAuthorization = 600;

	/**
	 * Full class name to use for user instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IUser`.
	 * @var string
	 */
	protected $userClass = 'MvcCore\\Ext\\Auths\\Basics\\User';

	/**
	 * Full class name to use for user role class.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IRole`.
	 * @var string
	 */
	protected $roleClass = 'MvcCore\\Ext\\Auths\\Basics\\Role';

	/**
	 * Full class name to use for controller instance
	 * to submit authentication form(s). Class name has to implement interfaces:
	 * - `\MvcCore\Ext\Auths\Basics\IController`
	 * - `\MvcCore\IController`
	 * @var string
	 */
	protected $controllerClass = '//MvcCore\\Ext\\Auths\\Basics\\Controller';

	/**
	 * Full class name to use for sign in form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @var string
	 */
	protected $signInFormClass = 'MvcCore\\Ext\\Auths\\Basics\\SignInForm';

	/**
	 * Full class name to use for sign out form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @var string
	 */
	protected $signOutFormClass = 'MvcCore\\Ext\\Auths\\Basics\\SignOutForm';

	/**
	 * Full URL to redirect user, after sign in
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in form rendered.
	 * @var string|NULL
	 */
	protected $signedInUrl = NULL;

	/**
	 * Full URL to redirect user, after sign out
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign out form rendered.
	 * @var string|NULL
	 */
	protected $signedOutUrl = NULL;

	/**
	 * Full URL to redirect user, after sign in POST
	 * request or sign out POST request was not successful,
	 * for example wrong credentials.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in/out form rendered.
	 * @var string|NULL
	 */
	protected $signErrorUrl = NULL;

	/**
	 * Route to submit sign in form to.
	 * It could be defined only as a string (route pattern),
	 * or as route configuration array or as route instance.
	 * Default match/reverse pattern for route sign request is
	 * `/signin` by POST.
	 * @var string|array|\MvcCore\Route
	 */
	protected $signInRoute = [
		'name'		=> 'auth_signin',
		'match'		=> '#^/signin/?$#',
		'reverse'	=> '/signin',
		'method'	=> \MvcCore\IRequest::METHOD_POST
	];

	/**
	 * Route to submit sign out form into.
	 * It could be defined only as a string (route pattern),
	 * or as route configuration array or as route instance.
	 * Default match/reverse pattern for route sign request is
	 * `/signout` by POST.
	 * @var string|array|\MvcCore\Route
	 */
	protected $signOutRoute = [
		'name'		=> 'auth_signout',
		'match'		=> '#^/signout/?$#',
		'reverse'	=> '/signout',
		'method'	=> \MvcCore\IRequest::METHOD_POST
	];

	/**
	 * Salt for `passord_hash();` to generate password by `PASSWORD_BCRYPT`.
	 * `NULL` by default. This option is the only one option required
	 * to configure authentication module to use it properly.
	 * @var string
	 */
	protected $passwordHashSalt = NULL;

	/**
	 * Timeout to `sleep();` PHP script before sending response to user,
	 * when user submitted invalid username or password.
	 * Default value is `3` (3 seconds).
	 * @var int
	 */
	protected $invalidCredentialsTimeout = 3;

	/**
	 * Callable translator to set it into authentication form
	 * to translate form labels, placeholders, buttons or error messages.
	 * Default value is `NULL` (forms without translations).
	 * @var callable|NULL
	 */
	protected $translator = NULL;

	/**
	 * Pre-route and pre-dispatch application callable handlers priority index.
	 * This property has no setter and getter. It's possible to configure only throw constructor.
	 * @var int
	 */
	protected $preHandlersPriority = 100;


	/***********************************************************************************
	 *							   Internal Properties							   *
	 ***********************************************************************************/

	/**
	 * MvcCore application instance reference from
	 * `\MvcCore\Application::GetInstance()`, because
	 * it's used many times in authentication class.
	 * @var \MvcCore\Application
	 */
	protected $application = NULL;

	/**
	 * User model instance or `NULL` if user has no username record in session namespace.
	 * @var \MvcCore\Ext\Auths\Basics\User|NULL
	 */
	protected $user = NULL;

	/**
	 * Sign in form instance, sign out form instance or any
	 * other authentication form instance in extended classes.
	 * If user is authenticated by username record in session namespace,
	 * there is completed sign out form, if not authenticated, sign in form otherwise etc...
	 * @var \MvcCore\Ext\Auths\Basics\SignInForm|\MvcCore\Ext\Auths\Basics\SignOutForm
	 */
	protected $form = NULL;

	/**
	 * This is only internal semaphore to call
	 * `\MvcCore\Ext\Auths\Basics\User::SetUpUserBySession()`
	 * only once (if result is `NULL`) in request pre-dispatch state.
	 * `TRUE`if method `\MvcCore\Ext\Auth::GetInstance()->GetUser()`
	 * has been called already with any result and also `TRUE` if
	 * method `\MvcCore\Ext\Auth::GetInstance()->SetUser($user)` has been
	 * already called with any first argument `$user` value.
	 * @var bool
	 */
	protected $userInitialized = FALSE;

	/**
	 * This is only internal semaphore to define when to add
	 * sign in or sign out route into router in pre route request state.
	 * If any configured route is for different http method than `POST`,
	 * than this property is set to `TRUE`. If both configured routes
	 * use only `POST` method, this property is automatically `FALSE` to
	 * not add routes for all requests, only for `POST` requests.
	 * Default value is `FALSE` because both default routes use `POST` methods.
	 * @var bool
	 */
	protected $addRoutesForAnyRequestMethod = FALSE;


	/***********************************************************************************
	 *									 Getters									 *
	 ***********************************************************************************/

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
	public function GetExpirationIdentity () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->expirationIdentity;
	}

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
	public function GetExpirationAuthorization () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->expirationAuthorization;
	}

	/**
	 * Get full class name to use for user instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IUser`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\User`.
	 * @return string
	 */
	public function GetUserClass () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->userClass;
	}

	/**
	 * Get full class name to use for user role class.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IRole`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Role`.
	 * @return string
	 */
	public function GetRoleClass () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->roleClass;
	}

	/**
	 * Get full class name to use for controller instance
	 * to submit authentication form(s). Class name has to implement interfaces:
	 * - `\MvcCore\Ext\Auths\Basics\IController`
	 * - `\MvcCore\IController`
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Controller`.
	 * @return string
	 */
	public function GetControllerClass () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->controllerClass;
	}

	/**
	 * Get full class name to use for sign in form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignInForm`.
	 * @return string
	 */
	public function GetSignInFormClass () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->signInFormClass;
	}

	/**
	 * Full class name to use for sign out form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignOutForm`.
	 * @return string
	 */
	public function GetSignOutFormClass () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->signOutFormClass;
	}

	/**
	 * Get full URL to redirect user, after sign in
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in form rendered.
	 * @return string|NULL
	 */
	public function GetSignedInUrl () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->signedInUrl;
	}

	/**
	 * Get full URL to redirect user, after sign out
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign out form rendered.
	 * @return string|NULL
	 */
	public function GetSignedOutUrl () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->signedOutUrl;
	}

	/**
	 * Get full URL to redirect user, after sign in POST
	 * request or sign out POST request was not successful,
	 * for example wrong credentials.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in/out form rendered.
	 * @param string $signErrorUrl
	 * @return string|NULL
	 */
	public function GetSignErrorUrl () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->signErrorUrl;
	}

	/**
	 * Get route instance to submit sign in form into.
	 * Default configured route for sign in request is `/signin` by POST.
	 * @return \MvcCore\Route
	 */
	public function GetSignInRoute () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->getInitializedRoute('SignIn');
	}

	/**
	 * Get route to submit sign out form into.
	 * Default configured route for sign in request is `/signout` by POST.
	 * @return \MvcCore\Route
	 */
	public function GetSignOutRoute () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->getInitializedRoute('SignOut');
	}

	/**
	 * Get configured salt for `passord_hash();` to generate password by `PASSWORD_BCRYPT`.
	 * `NULL` by default. This option is the only one option required
	 * to configure authentication module to use it properly.
	 * @return string|NULL
	 */
	public function GetPasswordHashSalt () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->passwordHashSalt;
	}

	/**
	 * Get timeout to `sleep();` PHP script before sending response to user,
	 * when user submitted invalid username or password.
	 * Default value is `3` (3 seconds).
	 * @return int
	 */
	public function GetInvalidCredentialsTimeout () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->invalidCredentialsTimeout;
	}

	/**
	 * Get configured callable translator to set it into authentication form
	 * to translate form labels, placeholders, buttons or error messages.
	 * Default value is `NULL` (forms without translations).
	 * @return callable|NULL
	 */
	public function GetTranslator () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->translator;
	}

	/**
	 * Get authenticated user model instance reference
	 * or `NULL` if user has no username record in session namespace.
	 * If user has not yet been initialized, load the user internally by
	 * `{$configuredUserClass}::SetUpUserBySession();` to try to load
	 * user by username record in session namespace.
	 * @return \MvcCore\Ext\Auths\Basics\User|NULL
	 */
	public function GetUser () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		if (!$this->userInitialized && $this->user === NULL) {
			$configuredUserClass = $this->userClass;
			$this->user = $configuredUserClass::SetUpUserBySession();
			if ($this->user !== NULL) $this->user->SetPasswordHash(NULL);
			$this->userInitialized = TRUE;
		}
		return $this->user;
	}

	/**
	 * Return `TRUE` if user is authenticated/signed in,
	 * `TRUE` if user has any username record in session namespace.
	 * If user has not yet been initialized, load the user internally by
	 * `$auth->GetUser();` to try to load user by username record in session namespace.
	 * @return bool
	 */
	public function IsAuthenticated () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		return $this->GetUser() !== NULL;
	}

	/**
	 * Return completed sign in or sign out form instance.
	 * Form instance completion is processed only once,
	 * any created form instance is stored in `$auth->form` property.
	 * This method is always called by you, your application
	 * to set form into you custom template to render it for user.
	 * If user is not authenticated, sign in form is returned and
	 * if user is authenticated, opposite sign out form is returned.
	 * This method is only alias to call two other methods:
	 * - `\MvcCore\Ext\Auths\Basic::GetInstance()->GetSignInForm();` for not authenticated users.
	 * - `\MvcCore\Ext\Auths\Basic::GetInstance()->GetSignOutForm();` for authenticated users.
	 * @var \MvcCore\Ext\Auths\Basics\SignInForm|\MvcCore\Ext\Auths\Basics\SignOutForm
	 */
	public function GetForm () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		if ($this->IsAuthenticated()) {
			$form = $this->GetSignOutForm();
		} else {
			$form = $this->GetSignInForm();
		}
		return $form;
	}

	/**
	 * Return completed sign in form instance.
	 * Form instance completion is processed only once,
	 * created form instance is stored in `$auth->form` property.
	 * @return \MvcCore\Ext\Auths\Basics\SignInForm
	 */
	public function GetSignInForm () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		if ($this->form !== NULL) return $this->form;
		$routerClass = $this->application->GetRouterClass();
		$router = $routerClass::GetInstance();
		$route = $this->getInitializedRoute('SignIn')->SetRouter($router);
		$method = $route->GetMethod();
		$appCtrl = $this->application->GetController();
		$formClassType = new \ReflectionClass($this->signInFormClass);
		$this->form = $formClassType->newInstanceArgs([$appCtrl]);
		$this->form
			->AddCssClasses(str_replace('_', ' ', $this->form->GetId()))
			->SetMethod($method !== NULL ? $method : \MvcCore\IRequest::METHOD_POST)
			->SetAction($router->UrlByRoute($route))
			->SetSuccessUrl($this->signedInUrl)
			->SetErrorUrl($this->signErrorUrl);
		if ($this->translator)
			$this->form->SetTranslator($this->translator);
		$this->form->Init();
		return $this->form;
	}

	/**
	 * Return completed sign out form instance.
	 * Form instance completion is processed only once,
	 * created form instance is stored in `$auth->form` property.
	 * @return \MvcCore\Ext\Auths\Basics\SignOutForm
	 */
	public function GetSignOutForm () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		if ($this->form !== NULL) return $this->form;
		$routerClass = $this->application->GetRouterClass();
		$router = $routerClass::GetInstance();
		$route = $this->getInitializedRoute('SignOut')->SetRouter($router);
		$method = $route->GetMethod();
		$appCtrl =  $this->application->GetController();
		$formClassType = new \ReflectionClass($this->signOutFormClass);
		$this->form = $formClassType->newInstanceArgs([$appCtrl]);
		$this->form
			->AddCssClasses(str_replace('_', ' ', $this->form->GetId()))
			->SetMethod($method !== NULL ? $method : \MvcCore\IRequest::METHOD_POST)
			->SetAction($router->UrlByRoute($route))
			->SetSuccessUrl($this->signedOutUrl)
			->SetErrorUrl($this->signErrorUrl);
		if ($this->translator)
			$this->form->SetTranslator($this->translator);
		$this->form->Init();
		return $this->form;
	}

	/**
	 * Return `array` with all protected configuration properties.
	 * @return array
	 */
	public function GetConfiguration () {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$result = [];
		$type = new \ReflectionClass($this);
		/** @var $props \ReflectionProperty[] */
		$props = $type->getProperties(
			\ReflectionProperty::IS_PUBLIC |
			\ReflectionProperty::IS_PROTECTED
		);
		foreach ($props as $prop) {
			if ($prop->isStatic()) continue;
			$name = $prop->getName();
			if (!in_array($name, static::$nonConfigurationProperties, TRUE))
				$result[$name] = $prop->getValue($this);
		}
		return $result;
	}


	/***********************************************************************************
	 *									 Setters									 *
	 ***********************************************************************************/

	/**
	 * Set expiration time (in seconds) how long to remember the user name in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 1 month (30 days, 2592000 seconds).
	 * @param int $expirationIdentity
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetExpirationIdentity ($identityExpirationSeconds = 2592000) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->expirationIdentity = $identityExpirationSeconds;
		return $this;
	}

	/**
	 * Set expiration time (in seconds) how long to remember the authorization in session.
	 * You can use zero (`0`) to browser close moment, but some browsers can
	 * restore previous session after next browser application start. Or anybody
	 * else in your project could use session for storing any information
	 * for some longer time in your application and session cookie could then
	 * exists much longer then browser close moment only.
	 * So better is not to use a zero value.
	 * Default value is 10 minutes (600 seconds).
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetExpirationAuthorization ($authorizationExpirationSeconds = 600) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->expirationAuthorization = $authorizationExpirationSeconds;
		return $this;
	}

	/**
	 * Set full class name to use for user instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IUser`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\User`.
	 * @param string $userClass User full class name implementing `\MvcCore\Ext\Auths\Basics\IUser`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetUserClass ($userClass = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->userClass = $this->checkClassImplementation(
			$userClass, 'MvcCore\\Ext\\Auths\\Basics\\IUser', TRUE
		);
		return $this;
	}

	/**
	 * Set full class name to use for user role class.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IRole`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Role`.
	 * @param string $roleClass Role full class name implementing `\MvcCore\Ext\Auths\Basics\IRole`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetRoleClass ($roleClass = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->userClass = $this->checkClassImplementation(
			$roleClass, 'MvcCore\\Ext\\Auths\\Basics\\IRole', TRUE
		);
		return $this;
	}

	/**
	 * Set full class name to use for controller instance
	 * to submit authentication form(s). Class name has to implement interfaces:
	 * - `\MvcCore\Ext\Auths\Basics\IController`
	 * - `\MvcCore\IController`
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\Controller`.
	 * @param string $controllerClass Controller full class name implementing `\MvcCore\Ext\Auths\Basics\IController`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetControllerClass ($controllerClass = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		if (substr($controllerClass, 0, 2) == '//') {
			$controllerClassToCheck = substr($controllerClass, 2);
		} else {
			$controllerClassToCheck = $controllerClass;
		}
		$controllerClassToCheck = $this->checkClassImplementation(
			$controllerClassToCheck, 'MvcCore\\Ext\\Auths\\Basics\\IController', FALSE
		);
		$controllerClassToCheck = $this->checkClassImplementation(
			$controllerClassToCheck, 'MvcCore\\IController', TRUE
		);
		if ($controllerClassToCheck)
			$this->controllerClass = $controllerClass;
		return $this;
	}

	/**
	 * Set full class name to use for sign in form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignInForm`.
	 * @param string $signInFormClass Form full class name implementing `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignInFormClass ($signInFormClass = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signInFormClass = $this->checkClassImplementation(
			$signInFormClass, 'MvcCore\\Ext\\Auths\\Basics\\IForm', FALSE
		);
		return $this;
	}

	/**
	 * Set full class name to use for sign out form instance.
	 * Class name has to implement interface
	 * `\MvcCore\Ext\Auths\Basics\IForm`.
	 * Default value after authentication module init is
	 * configured to `\MvcCore\Ext\Auths\Basics\SignOutForm`.
	 * @param string $signInFormClass Form full class name implementing `\MvcCore\Ext\Auths\Basics\IForm`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignOutFormClass ($signOutFormClass = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signOutFormClass = $this->checkClassImplementation(
			$signOutFormClass, 'MvcCore\\Ext\\Auths\\Basics\\IForm', FALSE
		);
		return $this;
	}

	/**
	 * Set full URL to redirect user, after sign in
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in form rendered.
	 * @param string|NULL $signedInUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignedInUrl ($signedInUrl = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signedInUrl = $signedInUrl;
		return $this;
	}

	/**
	 * Set full URL to redirect user, after sign out
	 * POST request was successful.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign out form rendered.
	 * @param string|NULL $signedOutUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignedOutUrl ($signedOutUrl = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signedOutUrl = $signedOutUrl;
		return $this;
	}

	/**
	 * Set full URL to redirect user, after sign in POST
	 * request or sign out POST request was not successful,
	 * for example wrong credentials.
	 * If `NULL` (by default), user will be redirected
	 * to the same url, where was sign in/out form rendered.
	 * @param string|NULL $signErrorUrl
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignErrorUrl ($signErrorUrl = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signErrorUrl = $signErrorUrl;
		return $this;
	}

	/**
	 * Set route instance to submit sign in form into.
	 * Default configured route for sign in request is `/signin` by POST.
	 * @param string|array|\MvcCore\Route $signInRoute
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignInRoute ($signInRoute = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signInRoute = $signInRoute;
		$method = NULL;
		if (gettype($signInRoute) == 'array' && isset($signInRoute['method']))
			$method = strtoupper($signInRoute['method']);
		if ($signInRoute instanceof \MvcCore\IRoute)
			$method = $signInRoute->GetMethod();
		if ($method !== \MvcCore\IRequest::METHOD_POST)
			$this->addRoutesForAnyRequestMethod = TRUE;
		return $this;
	}

	/**
	 * Set route to submit sign out form into.
	 * Default configured route for sign in request is `/signout` by POST.
	 * @param string|array|\MvcCore\Route $signOutRoute
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetSignOutRoute ($signOutRoute = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->signOutRoute = $signOutRoute;
		$method = NULL;
		if (gettype($signOutRoute) == 'array' && isset($signOutRoute['method']))
			$method = strtoupper($signOutRoute['method']);
		if ($signOutRoute instanceof \MvcCore\IRoute)
			$method = $signOutRoute->GetMethod();
		if ($method !== \MvcCore\IRequest::METHOD_POST)
			$this->addRoutesForAnyRequestMethod = TRUE;
		return $this;
	}

	/**
	 * Set configured salt for `passord_hash();` to generate password by `PASSWORD_BCRYPT`.
	 * `NULL` by default. This option is the only one option required
	 * to configure authentication module to use it properly.
	 * @param string $passwordHashSalt
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetPasswordHashSalt ($passwordHashSalt = '') {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->passwordHashSalt = $passwordHashSalt;
		return $this;
	}

	/**
	 * Set timeout to `sleep();` PHP script before sending response to user,
	 * when user submitted invalid username or password.
	 * Default value is `3` (3 seconds).
	 * @param int $seconds
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetInvalidCredentialsTimeout ($seconds = 3) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->invalidCredentialsTimeout = $seconds;
		return $this;
	}

	/**
	 * Set callable translator to set it into authentication form
	 * to translate form labels, placeholders or buttons.
	 * Default value is `NULL` (forms without translations).
	 * @param callable $translator
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetTranslator (callable $translator = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->translator = $translator;
		return $this;
	}

	/**
	 * Set user instance manually. If you use this method
	 * no authentication by `{$configuredUserClass}::SetUpUserBySession();`
	 * is used and authentication state is always positive.
	 * @param \MvcCore\Ext\Auths\Basics\User|NULL $user
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetUser (\MvcCore\Ext\Auths\Basics\IUser $user = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->user = $user;
		if ($this->user !== NULL) $this->user->SetPasswordHash(NULL);
		$this->userInitialized = TRUE;
		return $this;
	}

	/**
	 * Set sign in, sign out or any authentication form instance.
	 * Use this method only if you need sometimes to complete different form to render.
	 * @param \MvcCore\Ext\Auths\Basics\SignInForm|\MvcCore\Ext\Auths\Basics\SignOutForm $form
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetForm (\MvcCore\Ext\Auths\Basics\IForm $form) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$this->form = $form;
		return $this;
	}

	/**
	 * Set up authorization module configuration.
	 * Each array key has to be key by protected configuration property in this class.
	 * All properties are one by one configured by it's setter method.
	 * @param array $configuration Keys by protected properties names in camel case.
	 * @param bool $throwExceptionIfPropertyIsMissing
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetConfiguration ($configuration = [], $throwExceptionIfPropertyIsMissing = TRUE) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		foreach ($configuration as $key => $value) {
			$setter = 'Set' . ucfirst($key);
			if (method_exists($this, $setter)) {
				$this->{$setter}($value);
			} else if ($throwExceptionIfPropertyIsMissing) {
				throw new \InvalidArgumentException (
					'['.get_class().'] Property `'.$key.'` has no setter method `'.$setter.'` in class `'.get_class($this).'`.'
				);
			}
		}
		return $this;
	}

	/**
	 * Optional alias method if you have user class configured
	 * to database user: `\MvcCore\Ext\Auths\Basics\Users\Database`.
	 * Alias for `\MvcCore\Ext\Auths\Basics\Users\Database::SetUsersTableStructure($tableName, $columnNames);`.
	 * @param string|NULL	$tableName Database table name.
	 * @param string[]|NULL	$columnNames Keys are user class protected properties names in camel case, values are database columns names.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public function SetTableStructureForDbUsers ($tableName = NULL, $columnNames = NULL) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$userClass = $this->userClass;
		$toolClass = static::$toolClass;
		if ($toolClass::CheckClassInterface($userClass, 'MvcCore\\Ext\\Auths\\Basics\\IDatabaseUser', TRUE, TRUE)) {
			$userClass::SetUsersTableStructure($tableName, $columnNames);
		};
		return $this;
	}

	/**
	 * Check if given class name implements given interface
	 * and optionally if test class implements static interface methods.
	 * If not, thrown an `\InvalidArgumentException` every time.
	 * @param string $testClassName Full test class name.
	 * @param string $interfaceName Full interface class name.
	 * @param bool $checkStaticMethods `FALSE` by default.
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	protected function checkClassImplementation ($testClassName, $interfaceName, $checkStaticMethods = FALSE) {
		/** @var $this \MvcCore\Ext\Auths\Basic */
		$toolClass = static::$toolClass;
		if ($toolClass::CheckClassInterface($testClassName, $interfaceName, $checkStaticMethods, TRUE)) {
			return $testClassName;
		}
		return '';
	}
}
