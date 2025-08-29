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

namespace MvcCore\Ext\Auths\Basics\UserAndRole;

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` and `\MvcCore\Ext\Auths\Basics\Role` class. Trait contains:
 * - Public getters and setters to manipulate with instance property `$permissions`.
 * @mixin \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
 */
trait PermissionsMethods {

	/**
	 * Get `TRUE` if given permission string(s) is/are (all or some) allowed for user or user role. 
	 * `FALSE` otherwise. Permission name could contain asterisk char `*` in any place.
	 * @param  string|\string[] $permissionNameOrNames
	 * @param  bool             $allPermissionsRequired `TRUE` by default.
	 * @return bool
	 */
	public function IsAllowed ($permissionNameOrNames, $allPermissionsRequired = TRUE) {
		if (property_exists($this, 'admin') && $this->admin) return TRUE;
		$permissionNames = is_array($permissionNameOrNames)
			? $permissionNameOrNames
			: [$permissionNameOrNames];
		$permissionNamesCount = count($permissionNames);
		$allMatchedPermissionsCount = 0;
		foreach ($permissionNames as $permissionName) {
			$starCharPos = mb_strpos($permissionName, '*');
			if ($starCharPos === FALSE) {
				if (in_array($permissionName, array_values($this->permissions), TRUE)) {
					$allMatchedPermissionsCount++;
					if (!$allPermissionsRequired) {
						$allMatchedPermissionsCount = $permissionNamesCount;
						break;
					}
				}
			} else {
				$regExpPattern = '#^' . str_replace('*', '.*', $permissionName) . '$#';
				$matchedPermissions = preg_grep($regExpPattern, array_values($this->permissions));
				$matchedPermissionsCount = count($matchedPermissions);
				if ($matchedPermissionsCount > 0) {
					$allMatchedPermissionsCount += $matchedPermissionsCount;
					if (!$allPermissionsRequired) {
						$allMatchedPermissionsCount = $permissionNamesCount;
						break;
					}
				}
			}
		}
		return $allMatchedPermissionsCount >= $permissionNamesCount;
	}

	/**
	 * Get `TRUE` if given permission string or permission database id 
	 * is allowed for user or role, return FALSE` otherwise.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user or role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return bool
	 */
	public function HasPermission ($permissionName = NULL, $idPermission = NULL) {
		if (property_exists($this, 'admin') && $this->admin) return TRUE;
		if (is_int($idPermission)) {
			return isset($this->permissions[$idPermission]);
		} else {
			if (in_array($permissionName, array_values($this->permissions), TRUE)) 
				return TRUE;
		}
		return FALSE;
	}

	/**
	 * Add permission by name or by permission database id and name
	 * into permissions to allow something for user or role.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user or role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
	 */
	public function AddPermission ($permissionName = NULL, $idPermission = NULL) {
		if ($permissionName !== NULL) {
			if (is_int($idPermission)) {
				$this->permissions[$idPermission] = $permissionName;
			} else {
				if (!in_array($permissionName, array_values($this->permissions), TRUE))
					$this->permissions[] = $permissionName;
			}
		}
		return $this;
	}

	/**
	 * Remove permission by name or by permission database id and name
	 * to disallow something for user or role.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user or role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
	 */
	public function RemovePermission ($permissionName = NULL, $idPermission = NULL) {
		if (is_int($idPermission)) {
			unset($this->permissions[$idPermission]);
		} else if (
			$permissionName !== NULL && 
			in_array($permissionName, array_values($this->permissions), TRUE)
		) {
			$position = array_search($permissionName, $this->permissions);
			if ($position !== FALSE) array_splice($this->permissions, $position, 1);
		}
		return $this;
	}

	/**
	 * Get array of strings or array with permissions database ids as keys
	 * and permissions names as values, describing what is allowed to do for user or role.
	 * @return array|array<int,string>|\string[]
	 */
	public function & GetPermissions () {
		return $this->permissions;
	}

	/**
	 * Set array of strings or array with permissions database ids and names
	 * describing what is allowed to do for user or role.
	 * @param  array|array<int,string>|\string[]|string $permissions The permissions string, separated by comma character 
	 *                                                               or array of strings or array with permissions database 
	 *                                                               ids as keys and permissions names as values.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetPermissions ($permissions) {
		if (is_string($permissions)) {
			$this->permissions = explode(',', $permissions);
		} else if (is_array($permissions)) {
			$this->permissions = $permissions;
		}
		return $this;
	}
}
