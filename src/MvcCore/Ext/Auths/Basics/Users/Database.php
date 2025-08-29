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
 * Responsibility - simply and only load user instance from configurable database table structure.
 */
class		Database
extends		\MvcCore\Ext\Auths\Basics\User
implements	\MvcCore\Ext\Auths\Basics\IDatabaseUser {

	/**
	 * Users table nested database structure,
	 * configured by method `SetUsersTableStructure()`
	 * and used by method `GetByUserName()`.
	 * @var array
	 */
	protected static $usersTableStructure = [
		'table'		=> 'users',
		'columns'	=> [
			'id'			=> 'id',
			'active'		=> 'active',
			'userName'		=> 'user_name',
			'passwordHash'	=> 'password_hash',
			'fullName'		=> 'full_name',
		]
	];

	/**
	 * Set database table structure how to load user from db.
	 * @param  ?string   $tableName   Database table name.
	 * @param  ?string[] $columnNames Keys are user class protected properties names in camel case, values are database columns names.
	 * @return void
	 */
	public static function SetUsersTableStructure ($tableName = NULL, $columnNames = NULL) {
		if ($tableName !== NULL) static::$usersTableStructure['table'] = $tableName;
		if ($columnNames !== NULL) {
			$columns = & static::$usersTableStructure['columns'];
			foreach ($columnNames as $propertyName => $columnName)
				$columns[$propertyName] = $columnName;
		}
	}

	/**
	 * Get user model instance from database or any other users list
	 * resource by submitted and cleaned `$userName` field value.
	 * @param  string $userName Submitted and cleaned username. Characters `' " ` < > \ = ^ | & ~` are automatically encoded to html entities by default `\MvcCore\Ext\Auths\Basic` sign in form.
	 * @throws \RuntimeException
	 * @return \MvcCore\Ext\Auths\Basics\User|\MvcCore\Model
	 */
	public static function GetByUserName ($userName) {
		$table = static::$usersTableStructure['table'];
		$columns = (object) static::$usersTableStructure['columns'];
		$sql = implode("\n", [
			"SELECT											",
			"	u.{$columns->id} AS id,						",
			"	u.{$columns->active} AS active,				",
			"	u.{$columns->userName} AS userName,			",
			"	u.{$columns->passwordHash} AS passwordHash,	",
			"	u.{$columns->fullName} AS fullName			",
			"FROM											",
			"	{$table} u									",
			"WHERE											",
			"	u.{$columns->userName} = :user_name AND		",
			"	u.{$columns->active} = :active				",
		]);
		$conn = self::GetConnection();
		if ($conn instanceof \PDO) {
			$pdo = $conn;
		} else if (method_exists($conn, 'GetProvider')) {
			$pdo = $conn->GetProvider();
		} else {
			throw new \RuntimeException("Couldn't initialize `\PDO` connection.");
		}
		if (!$select = $pdo->prepare($sql))
			throw new \RuntimeException(
				implode(' ', $pdo->errorInfo()) . ': ' . $sql, intval($pdo->errorCode())
			);
		$select->execute([
			":user_name"	=> $userName,
			":active"		=> 1,
		]);
		/** @var $user \MvcCore\Ext\Auths\Basics\User|\MvcCore\Model */
		$user = NULL;
		$data = $select->fetch(\PDO::FETCH_ASSOC);
		if ($data) {
			$user = (new static())->SetValues(
				$data, 
				\MvcCore\IModel::PROPS_INHERIT |
				\MvcCore\IModel::PROPS_PROTECTED |
				\MvcCore\IModel::PROPS_CONVERT_UNDERSCORES_TO_CAMELCASE | 
				\MvcCore\IModel::PROPS_INITIAL_VALUES
			);
			return $user;
		}
		return $user;
	}
}
