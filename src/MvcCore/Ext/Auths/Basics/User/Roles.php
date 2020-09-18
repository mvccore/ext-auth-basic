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
 * - Instance property `$admin` and `$roles` with their public getters and setters to manipulate with user roles.
 * - Method `IsAllowed()` to get allowed permissions from user instance or from user roles.
 */
trait Roles
{
	/**
	 * `TRUE` if user is administrator. Administrator has always allowed everything.
	 * Default value is `FALSE`.
	 * @var bool
	 */
	protected $admin = FALSE;

	/**
	 * Array of roles names assigned for current user instance.
	 * @var \string[]
	 */
	protected $roles = [];

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function IsAdmin() {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->admin;
	}

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function GetAdmin() {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->admin;
	}

	/**
	 * Set user to Administrator. Administrator has always allowed everything.
	 * @param bool $admin `TRUE` by default.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function SetAdmin ($admin = TRUE) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$this->admin = (bool) $admin;
		return $this;
	}

	/**
	 * Return array of user's roles names.
	 * @return \string[]
	 */
	public function & GetRoles () {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		return $this->roles;
	}

	/**
	 * Set new user's roles or roles names.
	 * @param \string[]|\MvcCore\Ext\Auths\Basics\Role[]|\MvcCore\Ext\Auths\Basics\IRole[] $rolesOrRolesNames
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function SetRoles ($rolesOrRolesNames = []) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$this->roles = [];
		foreach ($rolesOrRolesNames as $roleOrRoleName)
			$this->roles[] = static::getRoleName($roleOrRoleName);
		return $this;
	}

	/**
	 * Add user role or role name.
	 * @param string|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole $roleOrRoleName
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function AddRole ($roleOrRoleName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$roleName = static::getRoleName($roleOrRoleName);
		if (!in_array($roleName, $this->roles, TRUE))
			$this->roles[] = $roleName;
		return $this;
	}

	/**
	 * Get `TRUE` if user has already assigned role or role name.
	 * @param string|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole $roleOrRoleName
	 * @throws \InvalidArgumentException
	 * @return bool
	 */
	public function HasRole ($roleOrRoleName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$roleName = static::getRoleName($roleOrRoleName);
		return in_array($roleName, $this->roles, TRUE);
	}

	/**
	 * Remove user role or role name from user roles.
	 * @param string|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole $roleOrRoleName
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser
	 */
	public function RemoveRole ($roleOrRoleName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		$roleName = static::getRoleName($roleOrRoleName);
		$position = array_search($roleName, $this->roles);
		if ($position !== FALSE) array_splice($this->roles, $position, 1);
		return $this;
	}

	/**
	 * Get role name from given role instance or given role name.
	 * @param string|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole $roleOrRoleName
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	protected static function getRoleName ($roleOrRoleName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User */
		if (is_string($roleOrRoleName)) {
			return $roleOrRoleName;
		} else if ($roleOrRoleName instanceof \MvcCore\Ext\Auths\Basics\IRole) {
			return $roleOrRoleName->GetName();
		} else {
			throw new \InvalidArgumentException(
				'['.get_class()."] Given argument `{$roleOrRoleName}` doesn't "
				."implement interface `\MvcCore\Ext\Auths\Basics\IRole` "
				."or it's not string with role name."
			);
		}
	}
}
