<?php

declare(strict_types = 1);

namespace App\Entities\Factories;

use App\Entities\Token;
use App\Utils\RandomStringGenerator;
use App\Database\Tables\TableFactory;

class TokenFactory
{
	public static function generate(
		string $name, 
		?int $userId = null, 
		string $expiry = '+2 hours'
	): Token
	{
		$token = new Token();
		$token->name = $name;
		$token->value = RandomStringGenerator::generate(60);
		$token->user_id = $userId;
		$token->expiry = date('Y-m-d H:i:s', strtotime($expiry));

		return $token;
	}

	public static function getByNameValueUserId(
		string $name, 
		string $value, 
		?int $userId = null
	): Token
	{
		$token = new Token();
		$token->name = $name;
		$token->value = $value;
		$token->user_id = $userId;

		return $token;
	}

	public static function getCompatibleWithArray(array $data): ?Token
	{
		$table = TableFactory::create('tokens');

		return $table->find($data);
	}
}