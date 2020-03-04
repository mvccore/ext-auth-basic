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

namespace MvcCore\Ext\Auths\Basics\User;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Instance properties `$aserName`, `$fullName` and `$passwordHash` with their public getters and setters.
 */
trait Base
{
	/**
	 * Unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @var string
	 */
	protected $userName = NULL;

	/**
	 * User full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @var string
	 */
	protected $fullName = NULL;

	/**
	 * Password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @var string|NULL
	 */
	protected $passwordHash = NULL;

	/**
	 * Unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @var string
	 */
	public function GetUserName () {
		return $this->userName;
	}

	/**
	 * Set unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @param string $userName
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function SetUserName ($userName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\IUser */
		$this->userName = $userName;
		return $this;
	}

	/**
	 * User full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @var string
	 */
	public function GetFullName () {
		return $this->fullName;
	}

	/**
	 * Set user full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @param string $fullName
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function SetFullName ($fullName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\IUser */
		$this->fullName = $fullName;
		return $this;
	}

	/**
	 * Password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @var string|NULL
	 */
	public function GetPasswordHash () {
		return $this->passwordHash;
	}

	/**
	 * Set password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @param string|NULL $passwordHash
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function SetPasswordHash ($passwordHash) {
		/** @var $this \MvcCore\Ext\Auths\Basics\IUser */
		$this->passwordHash = $passwordHash;
		return $this;
	}
}
