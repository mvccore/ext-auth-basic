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

namespace MvcCore\Ext\Auths\Basics;

/**
 * Responsibility - base role model class.
 */
interface IRole {

	// trait: \MvcCore\Ext\Auths\Basics\Traits\UserAndRole\GettersSetters

	/**
	 * User unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @return ?int
	 */
	public function GetId ();

	/**
	 * Set user unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @param  ?int $id
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetId ($id);

	/**
	 * Get user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * This function is only alias for `$user->GetActive();`.
	 * @return bool
	 */
	public function IsActive ();

	/**
	 * Get user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * @return bool
	 */
	public function GetActive ();

	/**
	 * Set user active state boolean. `TRUE` for active, `FALSE` otherwise.
	 * @param  bool $active `TRUE` by default.
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetActive ($active = TRUE);


	// trait: \MvcCore\Ext\Auths\Basics\Traits\UserAndRole\PermissionsMethods

	/**
	 * Get `TRUE` if given permission string(s) is/are (all or some) allowed for role. 
	 * `FALSE` otherwise. Permission name could contain asterisk char `*` in any place.
	 * @param  string|\string[] $permissionNameOrNames
	 * @param  bool             $allPermissionsRequired `TRUE` by default.
	 * @return bool
	 */
	public function IsAllowed ($permissionNameOrNames, $allPermissionsRequired = TRUE);

	/**
	 * Get `TRUE` if given permission string or permission database id 
	 * is allowed for role, return FALSE` otherwise.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return bool
	 */
	public function HasPermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Add permission by name or by permission database id and name
	 * into permissions to allow something for role.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function AddPermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Remove permission by name or by permission database id and name
	 * to disallow something for role.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for role.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function RemovePermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Get array of strings or array with permissions database ids as keys
	 * and permissions names as values, describing what is allowed to do for role.
	 * @return \string[]|array<int, string>
	 */
	public function & GetPermissions ();

	/**
	 * Set array of strings or array with permissions database ids and names
	 * describing what is allowed to do for role.
	 * @param  string|\string[]|array<int, string> $permissions The permissions string, separated by comma character 
	 *                                                          or array of strings or array with permissions database 
	 *                                                          ids as keys and permissions names as values.
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetPermissions ($permissions);


	// trait: \MvcCore\Ext\Auths\Basics\Traits\GettersSetters

	/**
	 * Get unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @return string
	 */
	public function GetName ();

	/**
	 * Set unique role name.
	 * Example: `"management" | "editor" | "quest"`
	 * @param  string $name
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function SetName ($name);


	// class: \MvcCore\Ext\Auths\Basics\Role

	/**
	 * Get role instance from application roles list. It could be database or any other custom resource.
	 * @param  string $name Role unique name.
	 * @throws \RuntimeException
	 * @return \MvcCore\Ext\Auths\Basics\Role
	 */
	public function GetByName ($roleName);
}
