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

namespace MvcCore\Ext\Auths\Basics\User;

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Instance properties `$aserName`, `$fullName` and `$passwordHash` with their public getters and setters.
 */
trait Base {

	/**
	 * Unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @column user_name
	 * @keyUnique
	 * @var string
	 */
	#[Attrs\Column('user_name'), Attrs\KeyUnique]
	protected $userName = NULL;

	/**
	 * User full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @column full_name
	 * @var string
	 */
	#[Attrs\Column('full_name')]
	protected $fullName = NULL;

	/**
	 * Password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @column password_hash
	 * @var string|NULL`
	 */
	#[Attrs\Column('password_hash')]
	protected $passwordHash = NULL;

	/**
	 * Unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @var string
	 */
	public function GetUserName () {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->userName;
	}

	/**
	 * Set unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tom@gmail.com"`
	 * @param string $userName
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetUserName ($userName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
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
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->fullName;
	}

	/**
	 * Set user full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @param string $fullName
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetFullName ($fullName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
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
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->passwordHash;
	}

	/**
	 * Set password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @param string|NULL $passwordHash
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetPasswordHash ($passwordHash) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$this->passwordHash = $passwordHash;
		return $this;
	}
}
