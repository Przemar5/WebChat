<?php

declare(strict_types = 1);

namespace App\Database\Tables;

use App\Database\Tables\Table;
use App\Entities\User;
use App\Entities\Token;
use App\Entities\Message;
use App\Entities\Room;
use App\Database\Factories\DatabaseAbstractFactory;

class TableFactory
{
	public static function create(string $name): Table
	{
		switch ($name) {
			case 'users': return self::createDefaultTable('users', User::class, 'deleted');
			case 'tokens': return self::createDefaultTable('tokens', Token::class);
			case 'messages': return self::createDefaultTable('messages', Message::class);
			case 'rooms': return self::createDefaultTable('rooms', Room::class);
			default: throw new \Exception(
				"Table identified by '$name' is missing.");
		}
	}

	private static function createDefaultTable(
		string $name, 
		string $class, 
		?string $deleteCol = null
	): Table
	{
		$database = DatabaseAbstractFactory::createDatabase();
		$queryBuilder = DatabaseAbstractFactory::createQueryBuilder();
		return new Table($name, $class, $deleteCol, $database, $queryBuilder);
	}
}