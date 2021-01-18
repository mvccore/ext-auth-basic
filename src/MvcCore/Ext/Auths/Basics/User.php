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

use \MvcCore\Ext\Models\Db\Attrs;

/**
 * Responsibility - base user model class.
 * This class is necessary to extend and implement method
 * `GetByUserName()` at least to be able to login into your app.
 * @table users
 */
#[Attrs\Table('users')]
class		User
extends		\MvcCore\Model
implements	\MvcCore\Ext\Auths\Basics\IUser {

	use \MvcCore\Ext\Auths\Basics\User\Features;

	/**
	 * Get user model instance from database or any other users list
	 * resource by submitted and cleaned `$userName` field value.
	 * @param string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @throws \RuntimeException
	 * @return \MvcCore\Ext\Auths\Basics\User
	 */
	public static function GetByUserName ($userName) {
		$selfClass = get_class();
		throw new \RuntimeException(
			'['.$selfClass.'] Method is not implemented. '
			.'Use class `\MvcCore\Ext\Auths\Basics\Users\Database` or '
			.'`\MvcCore\Ext\Auths\Basics\Users\SystemConfig` instead or extend '
			.'class `'.$selfClass.'` and implement method `GetByUserName($userName)` by your own.'
		);
	}
}
