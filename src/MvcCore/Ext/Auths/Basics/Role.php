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

namespace MvcCore\Ext\Auths\Basics;

/**
 * Responsibility - base role model class.
 * This class is necessary to extend and implement method
 * `GetByName()` or more. It's also necessary to implement
 * loading users with roles and their permissions to be able
 * to check user roles and permissions.
 */
class Role
	extends		\MvcCore\Model
	implements	\MvcCore\Ext\Auths\Basics\IRole
{
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Base;
	use \MvcCore\Ext\Auths\Basics\UserAndRole\Permissions;
	use \MvcCore\Ext\Auths\Basics\Role\Base;

	/**
	 * Do not automatically initialize protected properties
	 * `$user->db`, `$user->config` and `$user->resource`.
	 * @var bool
	 */
	protected $autoInit = FALSE;

	/**
	 * Get role instance from application roles list. It could be database or any other custom resource.
	 * @param string $name Role unique name.
	 * @throws \RuntimeException
	 * @return \MvcCore\Ext\Auths\Basics\Role|\MvcCore\Ext\Auths\Basics\IRole
	 */
	public function GetByName ($roleName) {
		$selfClass = get_class();
		throw new \RuntimeException(
			'['.$selfClass.'] Method is not implemented. '
			.'Extend class `'.$selfClass.'` and implement method `GetByName ($roleName)` by your own.'
		);
	}
}
