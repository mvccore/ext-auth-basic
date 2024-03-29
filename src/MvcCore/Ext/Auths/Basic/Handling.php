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
 * - Static `GetInstance()` method to return singleton instance.
 * - Constructor to init default config props and to assign pre-route and pre-dispatch application handlers.
 * - Protected methods to handle:
 *   - Pre-route handler - to init sign-in/sign-out form URL addresses and routes if necessary.
 *   - Pre-dispatch handler - to assign user instance to prepared controller to dispatch if possible.
 * @mixin \MvcCore\Ext\Auths\Basic
 */
trait Handling {

	/**
	 * Return singleton instance. If instance exists, return existing instance,
	 * if not, create new basic authentication module instance, store it and return it.
	 * @param array $configuration Optional configuration passed into method
	 *							 `\MvcCore\Ext\Auths\Basic::__construct($configuration)`.
	 * @return \MvcCore\Ext\Auths\Basic
	 */
	public static function GetInstance ($configuration = []) {
		/** @var \MvcCore\Ext\Auths\Basics\Auth $result */
		if (self::$instance === NULL) {
			$result = new static($configuration);
			self::$instance = $result;
		} else {
			$result = self::$instance;
		}
		return $result;
	}

	/**
	 * Create new Auth service instance.
	 * Initialize class definition properties into full class names
	 * if their values have no backslash inside.
	 * Set up MvcCore application instance reference and set up pre route handler
	 * to add authentication routes when necessary and user instance when necessary.
	 */
	public function __construct ($config = []) {
		self::$instance = $this;
		// set up possible configuration
		if ($config) $this->SetConfiguration($config);
		// set up application reference
		$this->application = \MvcCore\Application::GetInstance();
		// set up tools class
		static::$toolClass = $this->application->GetToolClass();
		$this->application
			// add sing in or sing out forms routes, complete form success and error addresses
			->AddPreRouteHandler(function () {
				$this->preRouteHandler();
			}, $this->preHandlersPriority)
			// try to set up user instance into dispatched controller instance if user is not null
			->AddPreDispatchHandler(function () {
				$this->preDispatchHandler();
			}, $this->preHandlersPriority);
	}

	/**
	 * Process necessary operations before request is routed by core router:
	 * - Every time try to load user by stored session username from any previous request(s).
	 * - If request could target any authentication route or request is post:
	 *   - Set up sign-in form success url, sign-out form success URL and error
	 *	 URL for both (sign in and sign out) forms, all URLs as current request URL by default.
	 *	 If any URL is configured already, nothing is changed.
	 *   - Set up sign in or sign out route into router, only route which
	 *	 is currently necessary by authenticated/not authenticated user.
	 * @return void
	 */
	protected function preRouteHandler () {
		$this->GetUser();
		$this->preRouteHandlerSetUpUrlAdresses();
		if (
			$this->addRoutesForAnyRequestMethod ||
			$this->application->GetRequest()->GetMethod() == \MvcCore\IRequest::METHOD_POST
		) {
			$this->preRouteHandlerSetUpRouter();
		}
	}

	/**
	 * Try to set up authenticated user into controller instance dispatched by core.
	 * @return void
	 */
	protected function preDispatchHandler () {
		if ($this->user !== NULL)
			$this->application->GetController()->SetUser($this->user);
	}

	/**
	 * Set up sign in form success url, sign out form success URL and error
	 * URL for both sign in/out forms, as current request URL by default.
	 * If any URL is configured already, nothing is changed.
	 * @return void
	 */
	protected function preRouteHandlerSetUpUrlAdresses () {
		$currentFullUrl = $this->application->GetRequest()->GetFullUrl();
		if ($this->signedInUrl === NULL)	$this->signedInUrl	= $currentFullUrl;
		if ($this->signedOutUrl === NULL)	$this->signedOutUrl	= $currentFullUrl;
		if ($this->signErrorUrl === NULL)	$this->signErrorUrl	= $currentFullUrl;
	}

	/**
	 * Set up sign in or sign out route into router, only route which
	 * is currently by authenticated/not authenticated user necessary
	 * to process in `$router->Route();` processing.
	 * @return void
	 */
	protected function preRouteHandlerSetUpRouter () {
		$routerClass = $this->application->GetRouterClass();
		$router = $routerClass::GetInstance();
		/**
		 * There is necessary all the time to add both POST routes.
		 * Because there could be possible strange POST request to SignIn route,
		 * when user is authenticates already (for example in another browser tab),
		 * or there could be possible strange POST request to SignOut route,
		 * after user session has been expired and there is submitted sign out form.
		 */
		/*if ($this->IsAuthenticated()) {
			$router->AddRoute(
				$this->getInitializedRoute('SignOut'), NULL, TRUE
			);
		} else {*/
			$router->AddRoute(
				$this->getInitializedRoute('SignIn'), NULL, TRUE
			);
			$router->AddRoute(
				$this->getInitializedRoute('SignOut'), NULL, TRUE
			);
		//}
	}

	/**
	 * Prepare configured route record into route instance if record is string or array.
	 * @param string $routeName
	 * @param string $actionName
	 * @return \MvcCore\Route
	 */
	protected function getInitializedRoute ($actionName) {
		$routeName = lcfirst($actionName) . 'Route';
		$rawRoute = $this->{$routeName};
		if ($rawRoute instanceof \MvcCore\IRoute) {
			return $rawRoute;
		} else {
			$routerClass = $this->application->GetRouterClass();
			$router = $routerClass::GetInstance();
			$ctrlParamName = $router::URL_PARAM_CONTROLLER;
			$actionParamName = $router::URL_PARAM_ACTION;
			$routeInitData = [
				$ctrlParamName		=> $this->controllerClass, 
				$actionParamName	=> $actionName
			];
			$routeClass = $this->application->GetRouteClass();
			$route = $routeClass::CreateInstance(
				gettype($rawRoute) == 'array'
					? array_merge($routeInitData, $rawRoute)
					: array_merge(['pattern' => $rawRoute], $routeInitData)
			);
			$this->{$routeName} = $route;
			return $route;
		}
	}
}
