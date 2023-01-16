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

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` class. Trait contains:
 * - Public getters and setters for instance property `$roles`.
 * - Methods to determinate and manipulate with user roles from user instance.
 * @mixin \MvcCore\Ext\Auths\Basics\User
 */
trait RolesMethods {

	/**
	 * Get `TRUE` if given role string or role database id 
	 * is allowed for user, return FALSE` otherwise.
	 * @param  string|NULL $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  int|NULL    $idRole   Role database id, optional.
	 * @return bool
	 */
	public function HasRole ($roleName = NULL, $idRole = NULL) {
		if ($this->admin) return TRUE;
		if (is_int($idRole)) {
			return isset($this->roles[$idRole]);
		} else {
			if (in_array($roleName, array_values($this->roles), TRUE)) 
				return TRUE;
		}
		return FALSE;
	}

	/**
	 * Add role by name or by role database id and name
	 * into roles to allow something for user.
	 * @param  string|NULL $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  int|NULL    $idRole   Role database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function AddRole ($roleName = NULL, $idRole = NULL) {
		if ($roleName !== NULL) {
			if (is_int($idRole)) {
				$this->roles[$idRole] = $roleName;
			} else {
				if (!in_array($roleName, array_values($this->roles), TRUE))
					$this->roles[] = $roleName;
			}
		}
		return $this;
	}

	/**
	 * Remove role by name or by role database id and name
	 * to disallow something for user.
	 * @param  string|NULL $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  int|NULL    $idRole   Role database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function RemoveRole ($roleName = NULL, $idRole = NULL) {
		if (is_int($idRole)) {
			unset($this->roles[$idRole]);
		} else if (
			$roleName !== NULL && 
			in_array($roleName, array_values($this->roles), TRUE)
		) {
			$position = array_search($roleName, $this->roles);
			if ($position !== FALSE) array_splice($this->roles, $position, 1);
		}
		return $this;
	}

	/**
	 * Get array of roles names or array with roles database ids as keys and roles names
	 * as values, describing roles assigned for current user instance.
	 * @return \string[]|array<int, string>
	 */
	public function & GetRoles () {
		return $this->roles;
	}

	/**
	 * Set array of roles names or array with roles database ids as keys and roles names
	 * as values, describing roles assigned for current user instance.
	 * @param  string|\string[]|array<int, string> $roles The roles string, separated by comma character 
	 *                                                    or array of strings or array with roles database 
	 *                                                    ids as keys and roles names as values.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetRoles ($roles) {
		if (is_string($roles)) {
			$this->roles = explode(',', $roles);
		} else if (is_array($roles)) {
			$this->roles = $roles;
		}
		return $this;
	}
}
