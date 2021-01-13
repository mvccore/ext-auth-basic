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

namespace MvcCore\Ext\Auths\Basics\UserAndRole;

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Trait for `\MvcCore\Ext\Auths\Basics\User` and `\MvcCore\Ext\Auths\Basics\Role` class. Trait contains:
 * - Instance property `$permissions` with their public getters and setters to manipulate with permissions.
 */
trait Permissions {

	/**
	 * Array of strings describing what is allowed to do for user or role.
	 * @column permissions
	 * @var \string[]
	 */
	#[Attrs\Column('permissions')]
	protected $permissions = [];
	
	/**
	 * Get `TRUE` if given permission string(s) is/are (all or some) allowed for user or user role. 
	 * `FALSE` otherwise. Permission name could contain asterisk char `*` in any place.
	 * @param string|\string[] $permissionNameOrNames
	 * @param bool $allPermissionsRequired `TRUE` by default.
	 * @return bool
	 */
	public function IsAllowed ($permissionNameOrNames, $allPermissionsRequired = TRUE) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		if (property_exists($this, 'admin') && $this->admin) return TRUE;
		$permissionNames = is_array($permissionNameOrNames)
			? $permissionNameOrNames
			: [$permissionNameOrNames];
		$allMatchedPermissionsCount = 0;
		foreach ($permissionNames as $permissionName) {
			$starCharPos = mb_strpos($permissionName, '*');
			if ($starCharPos === FALSE) {
				if (in_array($permissionName, $this->permissions, TRUE)) {
					$allMatchedPermissionsCount++;
					if (!$allMatchedPermissionsCount) break;
				}
			} else {
				$regExpPattern = '#^' . str_replace('*', '.*', $permissionName) . '$#';
				$matchedPermissions = preg_grep($regExpPattern, $this->permissions);
				$matchedPermissionsCount = count($matchedPermissions);
				if ($matchedPermissionsCount > 0) {
					$allMatchedPermissionsCount += $matchedPermissionsCount;
					if (!$allPermissionsRequired) break;
				}
			}
		}
		return $allMatchedPermissionsCount >= count($permissionNames);
	}

	/**
	 * Get `TRUE` if given permission string is allowed for user or role. `FALSE` otherwise.
	 * @param string $permissionName
	 * @return bool
	 */
	public function GetPermission ($permissionName) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		if (property_exists($this, 'admin') && $this->admin) return TRUE;
		if (in_array($permissionName, $this->permissions, TRUE)) return TRUE;
		return FALSE;
	}

	/**
	 * Set `$permissionName` string with `$allow` boolean to allow
	 * or to disallow permission (with `$allow = FALSE`) for user or role.
	 * @param string $permissionName Strings describing what is allowed/disallowed to do for user or role.
	 * @param bool $allow `TRUE` by default.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole
	 */
	public function SetPermission ($permissionName, $allow = TRUE) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		if (!in_array($permissionName, $this->permissions, TRUE) && $allow) {
			$this->permissions[] = $permissionName;
		} else if (in_array($permissionName, $this->permissions, TRUE) && !$allow) {
			$position = array_search($permissionName, $this->permissions);
			if ($position !== FALSE) array_splice($this->permissions, $position, 1);
		}
		return $this;
	}

	/**
	 * Get array of strings describing what is allowed to do for user or role.
	 * @return \string[]
	 */
	public function & GetPermissions() {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		return $this->permissions;
	}

	/**
	 * Set array of strings describing what is allowed to do for user or role.
	 * @param string|\string[] $permissions The permissions string, separated by comma character or array of strings.
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\IUser|\MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole
	 */
	public function SetPermissions ($permissions) {
		/** @var $this \MvcCore\Ext\Auths\Basics\User|\MvcCore\Ext\Auths\Basics\Role */
		if (is_string($permissions)) {
			$this->permissions = explode(',', $permissions);
		} else if (is_array($permissions)) {
			$this->permissions = $permissions;
		}
		return $this;
	}
}
