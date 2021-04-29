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

namespace MvcCore\Ext\Auths\Basics\User;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Static methods `LogIn()` and `LogOut()` to authenticate or remove user from session namespace.
 * - Static method `EncodePasswordToHash()` to hash password with custom or configured salt and other options.
 * @mixin \MvcCore\Ext\Auths\Basics\User
 */
trait AuthMethods {

	/**
	 * Try to get user model instance from application users list
	 * (it could be database table or system config) by user session namespace
	 * `userName` record if `authenticated` boolean in user session namespace is `TRUE`.
	 * Or return `NULL` for no user by user session namespace records.
	 * @return \MvcCore\Ext\Auths\Basics\User|NULL
	 */
	public static function SetUpUserBySession () {
		$sessionIdentity = static::GetSessionIdentity();
		$sessionAuthorization = static::GetSessionAuthorization();
		$userNameStr = \MvcCore\Ext\Auths\Basics\IUser::SESSION_USERNAME_KEY;
		$authorizedStr = \MvcCore\Ext\Auths\Basics\IUser::SESSION_AUTHORIZED_KEY;
		if (
			isset($sessionIdentity->{$userNameStr}) &&
			isset($sessionAuthorization->{$authorizedStr}) &&
			$sessionAuthorization->{$authorizedStr}
		) {
			$user = static::GetByUserName($sessionIdentity->{$userNameStr});
			$user->passwordHash = NULL;
			if (is_array($user->initialValues) && array_key_exists('passwordHash', $user->initialValues))
				$user->initialValues['passwordHash'] = NULL;
			return $user;
		}
		return NULL;
	}

	/**
	 * Try to get user model instance from application users list
	 * (it could be database table or system config) by submitted
	 * and cleaned `$userName`, hash submitted and cleaned `$password` and try to compare
	 * hashed submitted password and user password hash from application users
	 * list. If password hashes are the same, set username and authenticated boolean
	 * into user session namespace. Then user is logged in.
	 * @param string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @param string $password Submitted and cleaned password. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @return \MvcCore\Ext\Auths\Basics\User|NULL
	 */
	public static function LogIn ($userName = '', $password = '') {
		$user = static::GetByUserName($userName);
		if ($user) {
			if (\PHP_VERSION_ID >= 50500){
				$authenticated = password_verify($password, $user->passwordHash);
			} else {
				$hashedPassword = static::EncodePasswordToHash($password);
				$authenticated = static::hashEquals($user->passwordHash, $hashedPassword);
			}
			if ($authenticated) {
				$sessionIdentity = static::GetSessionIdentity();
				$sessionAuthorization = static::GetSessionAuthorization();
				$userNameStr = \MvcCore\Ext\Auths\Basics\IUser::SESSION_USERNAME_KEY;
				$authorizedStr = \MvcCore\Ext\Auths\Basics\IUser::SESSION_AUTHORIZED_KEY;
				$sessionIdentity->{$userNameStr} = $user->userName;
				$sessionAuthorization->{$authorizedStr} = TRUE;
				$user->passwordHash = NULL;
				if (is_array($user->initialValues) && array_key_exists('passwordHash', $user->initialValues))
					$user->initialValues['passwordHash'] = NULL;
				return $user;
			}
		}
		return NULL;
	}

	/**
	 * Log out user. Set `authenticated` record in user session namespace to `FALSE`
	 * by default. User name should still remain in user session namespace.
	 * If First argument `$destroyWholeSession` is `TRUE`, destroy whole
	 * user session namespace with `authenticated` bool and with `userName` string record.
	 * @param bool $destroyWholeSession
	 * @return void
	 */
	public static function LogOut ($destroyWholeSession = FALSE) {
		$sessionIdentity = static::GetSessionIdentity();
		$sessionAuthorization = static::GetSessionAuthorization();
		if ($destroyWholeSession) {
			$sessionIdentity->Destroy();
			$sessionAuthorization->Destroy();
		} else {
			$authorizedStr = \MvcCore\Ext\Auths\Basics\IUser::SESSION_AUTHORIZED_KEY;
			$sessionAuthorization->{$authorizedStr} = FALSE;
		}
	}

	/**
	 * Get password hash by `password_hash()` with salt
	 * by `\MvcCore\Ext\Auths\Basic` extension configuration or
	 * by custom salt in second agument `$options['salt'] = 'abcdefg';`.
	 * @see http://php.net/manual/en/function.password-hash.php
	 * @param string $password
	 * @param array $options An options for `password_hash()`.
	 * @return string
	 */
	public static function EncodePasswordToHash ($password = '', $options = []) {
		if (!isset($options['salt'])) {
			$configuredSalt = \MvcCore\Ext\Auths\Basic::GetInstance()->GetPasswordHashSalt();
			if ($configuredSalt !== NULL) {
				$options['salt'] = $configuredSalt;
			} else {
				throw new \InvalidArgumentException(
					'['.get_class().'] No option `salt` given by second argument `$options`'
					." or no salt configured by `\\MvcCore\\Ext\\Auth::GetInstance()->SetPasswordHashSalt('...')`."
				);
			}
		}
		if (isset($options['cost']) && ($options['cost'] < 4 || $options['cost'] > 31))
			throw new \InvalidArgumentException(
				'['.get_class().'] `cost` option has to be from `4` to `31`, `' . $options['cost'] . '` given.'
			);
		if (\PHP_VERSION_ID >= 50500) {
			if (\PHP_VERSION_ID >= 80000 && isset($options['salt'])) 
				unset($options['salt']);
			$result = @password_hash($password, PASSWORD_BCRYPT, $options);
		} else {
			$cost = isset($options['cost']) ? $options['cost'] : 10; // PASSWORD_BCRYPT_DEFAULT_COST
			$hashPrefix = sprintf("$2y$%02d$", $cost); // $2y$12$
			$hash = $hashPrefix . $options['salt'] . '$';
			$result = crypt($password, $hash);
		}
		if (!$result || strlen($result) < 60)
			throw new \RuntimeException(
				'['.get_class().'] Hash computed by `password_hash()` is invalid. Try a little bit longer salt.'
			);
		return $result;
	}

	/**
	 * MvcCore session namespace instance
	 * to get/clear username record from session
	 * to load user for authentication.
	 * Session is automatically started if necessary
	 * by `\MvcCore\Session::GetNamespace();`.
	 * @return \MvcCore\Session
	 */
	public static function GetSessionIdentity () {
		if (static::$sessionIdentity === NULL) {
			$sessionClass = self::$_sessionClass ?: (
				self::$_sessionClass = \MvcCore\Application::GetInstance()->GetSessionClass()
			);
			static::$sessionIdentity = $sessionClass::GetNamespace(
				'MvcCore\\Ext\\Auths\\Identity'
			);
			static::$sessionIdentity->SetExpirationSeconds(
				\MvcCore\Ext\Auths\Basic::GetInstance()->GetExpirationIdentity()
			);
		}
		return static::$sessionIdentity;
	}

	/**
	 * Set identity session namespace.
	 * @param \MvcCore\Session $sessionIdentity
	 * @return \MvcCore\Session
	 */
	public static function SetSessionIdentity (\MvcCore\ISession $sessionIdentity) {
		return static::$sessionIdentity = $sessionIdentity;
	}

	/**
	 * MvcCore session namespace instance
	 * to get/clear username record from session
	 * to load user for authentication.
	 * Session is automatically started if necessary
	 * by `\MvcCore\Session::GetNamespace();`.
	 * @return \MvcCore\Session
	 */
	public static function GetSessionAuthorization () {
		if (static::$sessionAuthorization === NULL) {
			$sessionClass = self::$_sessionClass ?: (
				self::$_sessionClass = \MvcCore\Application::GetInstance()->GetSessionClass()
			);
			static::$sessionAuthorization = $sessionClass::GetNamespace(
				'MvcCore\\Ext\\Auths\\Authorization'
			);
			static::$sessionAuthorization->SetExpirationSeconds(
				\MvcCore\Ext\Auths\Basic::GetInstance()->GetExpirationAuthorization()
			);
		}
		return static::$sessionAuthorization;
	}

	/**
	 * Set authorization session namespace.
	 * @param \MvcCore\Session $sessionAuthorization
	 * @return \MvcCore\Session
	 */
	public static function SetSessionAuthorization (\MvcCore\ISession $sessionAuthorization) {
		return static::$sessionAuthorization = $sessionAuthorization;
	}

	/**
	 * Backward compatible hash equals for PHP 5.4.
	 * @param string $hash1
	 * @param string $hash2
	 * @return bool
	 */
	protected static function hashEquals ($hash1, $hash2) {
		if (function_exists('hash_equals')) {
			return hash_equals($hash1, $hash2);
		} else {
			if (strlen($hash1) != strlen($hash2)) {
				return FALSE;
			} else {
				$res = $hash1 ^ $hash2;
				$ret = 0;
				for ($i = strlen($res) - 1; $i >= 0; $i--)
					$ret |= ord($res[$i]);
				return !$ret;
			}
		}
	}
}
