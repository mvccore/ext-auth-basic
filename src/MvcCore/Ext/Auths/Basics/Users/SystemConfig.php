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

namespace MvcCore\Ext\Auths\Basics\Users;

/**
 * Responsibility - simply and only load user instance from system config, ids by users list sequence.
 */
class SystemConfig extends \MvcCore\Ext\Auths\Basics\User {

	/**
	 * Get user model instance from system config or any other users list
	 * resource by submitted and cleaned `$userName` field value.
	 * @param string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public static function GetByUserName ($userName) {
		$result = NULL;
		$configClass = \MvcCore\Application::GetInstance()->GetConfigClass();
		$allSysConfigCredentials = $configClass::GetConfigSystem()->users;
		foreach ($allSysConfigCredentials as $key => $sysConfigCredentials) {
			if ($sysConfigCredentials->userName === $userName) {
				$result = (new static())
					->SetId($key)
					->SetUserName($sysConfigCredentials->userName)
					->SetFullName($sysConfigCredentials->fullName)
					->SetPasswordHash($sysConfigCredentials->passwordHash);
				break;
			}
		}
		return $result;
	}
}
