<?php

declare(strict_types = 1);

namespace App\Database\Tables;

use App\Database\Tables\Table;
use App\Entities\User;
use App\Entities\Token;
use App\Entities\Message;
use App\Entities\Room;

class TableFactory
{
	public static function create(string $name): Table
	{
		switch ($name) {
			case 'users': return new Table('users', User::class, 'deleted');
			case 'tokens': return new Table('tokens', Token::class);
			case 'messages': return new Table('messages', Message::class);
			case 'rooms': return new Table('rooms', Room::class);
			default: throw new \Exception(
				"Table identified by '$name' is missing.");
		}
	}
}