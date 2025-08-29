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
 * Responsibility - base user model class.
 */
interface IUser {

	/**
	 * User session namespace key to get username string.
	 */
	const SESSION_USERNAME_KEY = 'userName';

	/**
	 * User session namespace key to get authenticated boolean.
	 */
	const SESSION_AUTHORIZED_KEY = 'authorized';


	// trait: \MvcCore\Ext\Auths\Basics\Traits\UserAndRole\GettersSetters

	/**
	 * User unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @return ?int
	 */
	public function GetId () ;

	/**
	 * Set user unique id, representing primary key in database
	 * or sequence number in system config.
	 * Example: `0 | 1 | 2...`
	 * @param  ?int $id
	 * @return \MvcCore\Ext\Auths\Basics\User
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
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetActive ($active = TRUE);


	// trait: \MvcCore\Ext\Auths\Basics\Traits\User\GettersSetters

	/**
	 * Unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tomflidr@gmail.com"`
	 * @var string
	 */
	public function GetUserName ();

	/**
	 * Set unique user name to log in. It could be email,
	 * unique user name or anything uniquelse.
	 * Example: `"admin" | "john" | "tomflidr@gmail.com"`
	 * @param  string $userName
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetUserName ($userName);

	/**
	 * User full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom Flidr"`
	 * @var string
	 */
	public function GetFullName ();

	/**
	 * Set user full name string to display in application
	 * for authenticated user at sign out button.
	 * Example: `"Administrator" | "John" | "Tom"`
	 * @param  string $fullName
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetFullName ($fullName);

	/**
	 * Password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @var ?string
	 */
	public function GetPasswordHash ();

	/**
	 * Set password hash, usually `NULL`. It exists only for authentication moment.
	 * From moment, when is user instance loaded with password hash by session username to
	 * moment, when is compared hashed sent password and stored password hash.
	 * After password hashes comparison, password hash is un-setted.
	 * @param  ?string $passwordHash
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetPasswordHash ($passwordHash);


	// trait: \MvcCore\Ext\Auths\Basics\Traits\User\AuthMethods

	/**
	 * Try to get user model instance from application users list
	 * (it could be database table or system config) by user session namespace
	 * `userName` record if `authenticated` boolean in user session namespace is `TRUE`.
	 * Or return `NULL` for no user by user session namespace records.
	 * @return ?\MvcCore\Ext\Auths\Basics\User
	 */
	public static function SetUpUserBySession ();

	/**
	 * Try to get user model instance from application users list
	 * (it could be database table or system config) by submitted
	 * and cleaned `$userName`, hash submitted and cleaned `$password` and try to compare
	 * hashed submitted password and user password hash from application users
	 * list. If password hashes are the same, set username and authenticated boolean
	 * into user session namespace. Then user is logged in.
	 * @param  ?string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @param  ?string $password Submitted and cleaned password. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @return ?\MvcCore\Ext\Auths\Basics\User
	 */
	public static function LogIn ($userName, $password);

	/**
	 * Log out user. Set `authenticated` record in user session namespace to `FALSE`
	 * by default. User name should still remain in user session namespace.
	 * If First argument `$destroyWholeSession` is `TRUE`, destroy whole
	 * user session namespace with `authenticated` bool and with `userName` string record.
	 * @param  bool $keepIdentityData
	 * @return void
	 */
	public static function LogOut ($keepIdentityData = FALSE);

	/**
	 * Get password hash by `password_hash()` with salt
	 * by `\MvcCore\Ext\Auths\Basic` extension configuration or
	 * by custom salt in second argument `$options['salt'] = 'abcdefg';`.
	 * @see http://php.net/manual/en/function.password-hash.php
	 * @param  string $password
	 * @param  array  $options An options for `password_hash()`.
	 * @return string
	 */
	public static function EncodePasswordToHash ($password = '', $options = []);

	/**
	 * MvcCore session namespace instance
	 * to get/clear username record from session
	 * to load user for authentication.
	 * Session is automatically started if necessary
	 * by `\MvcCore\Session::GetNamespace();`.
	 * @return \MvcCore\Session
	 */
	public static function GetSessionIdentity ();

	/**
	 * Set identity session namespace.
	 * @param  \MvcCore\Session $sessionIdentity
	 * @return \MvcCore\Session
	 */
	public static function SetSessionIdentity (\MvcCore\ISession $sessionIdentity);

	/**
	 * MvcCore session namespace instance
	 * to get/clear username record from session
	 * to load user for authentication.
	 * Session is automatically started if necessary
	 * by `\MvcCore\Session::GetNamespace();`.
	 * @return \MvcCore\Session
	 */
	public static function GetSessionAuthorization ();

	/**
	 * Set authorization session namespace.
	 * @param  \MvcCore\Session $sessionAuthorization
	 * @return \MvcCore\Session
	 */
	public static function SetSessionAuthorization (\MvcCore\ISession $sessionAuthorization);


	// trait: \MvcCore\Ext\Auths\Basics\Traits\User\RolesMethods

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function IsAdmin ();

	/**
	 * Get if user is Administrator. Administrator has always allowed everything.
	 * @return bool
	 */
	public function GetAdmin ();

	/**
	 * Set user to Administrator. Administrator has always allowed everything.
	 * @param  bool $admin `TRUE` by default.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetAdmin ($admin = TRUE);

	/**
	 * Get `TRUE` if given role string or role database id 
	 * is allowed for user, return FALSE` otherwise.
	 * @param  ?string $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idRole   Role database id, optional.
	 * @return bool
	 */
	public function HasRole ($roleName = NULL, $idRole = NULL);

	/**
	 * Add role by name or by role database id and name
	 * into roles to allow something for user.
	 * @param  ?string $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idRole   Role database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function AddRole ($roleName = NULL, $idRole = NULL);

	/**
	 * Remove role by name or by role database id and name
	 * to disallow something for user.
	 * @param  ?string $roleName Role name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idRole   Role database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function RemoveRole ($roleName = NULL, $idRole = NULL);

	/**
	 * Get array of roles names or array with roles database ids as keys and roles names
	 * as values, describing roles assigned for current user instance.
	 * @return \string[]|array<int, string>
	 */
	public function & GetRoles ();

	/**
	 * Set array of roles names or array with roles database ids as keys and roles names
	 * as values, describing roles assigned for current user instance.
	 * @param  string|\string[]|array<int, string> $roles The roles string, separated by comma character 
	 *                                                    or array of strings or array with roles database 
	 *                                                    ids as keys and roles names as values.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetRoles ($roles);

	
	// trait: \MvcCore\Ext\Auths\Basics\Traits\UserAndRole\PermissionsMethods

	/**
	 * Get `TRUE` if given permission string(s) is/are (all or some) allowed for user or user role. 
	 * `FALSE` otherwise. Permission name could contain asterisk char `*` in any place.
	 * @param  string|\string[] $permissionNameOrNames
	 * @param  bool             $allPermissionsRequired `TRUE` by default.
	 * @return bool
	 */
	public function IsAllowed ($permissionNameOrNames, $allPermissionsRequired = TRUE);

	/**
	 * Get `TRUE` if given permission string or permission database id 
	 * is allowed for user, return FALSE` otherwise.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return bool
	 */
	public function HasPermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Add permission by name or by permission database id and name
	 * into permissions to allow something for user.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function AddPermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Remove permission by name or by permission database id and name
	 * to disallow something for user.
	 * @param  ?string $permissionName Permission name, optional, describing what is allowed/disallowed to do for user.
	 * @param  ?int    $idPermission   Permission database id, optional.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function RemovePermission ($permissionName = NULL, $idPermission = NULL);

	/**
	 * Get array of strings or array with permissions database ids as keys
	 * and permissions names as values, describing what is allowed to do for user.
	 * @return \string[]|array<int, string>
	 */
	public function & GetPermissions ();

	/**
	 * Set array of strings or array with permissions database ids and names
	 * describing what is allowed to do for user.
	 * @param  string|\string[]|array<int, string> $permissions The permissions string, separated by comma character 
	 *                                                          or array of strings or array with permissions database 
	 *                                                          ids as keys and permissions names as values.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public function SetPermissions ($permissions);


	// class: \MvcCore\Ext\Auths\Basics\User

	/**
	 * Get user model instance from database or any other users list
	 * resource by submitted and cleaned `$userName` field value.
	 * @param  string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public static function GetByUserName ($userName);
}
