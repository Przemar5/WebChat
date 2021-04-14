<?php

declare(strict_types = 1);

namespace App\Entities\Factories;

use App\Entities\Token;
use App\Utils\RandomStringGenerator;
use App\Database\Tables\TableFactory;

class TokenFactory
{
	public static function generate(
		int $userId, 
		string $name, 
		?string $expiry = null
	): Token
	{
		if (is_null($expiry)) {
			$expiry = date('Y-m-d H:i:s', strtotime('+2 hours'));
		}
		$token = new Token();
		$token->name = $name;
		$token->value = RandomStringGenerator::generate(120);
		$token->user_id = $userId;
		$token->expiry = $expiry;

		return $token;
	}

	public static function getFromArray(array $data): Token
	{
		$token = new Token();
		$token->name = $data['token_name'];
		$token->value = $data['token_value'];
		$token->user_id;

		return $token;
	}

	public static function getCompatibleWithArray(array $data): ?Token
	{
		$table = TableFactory::create('tokens');

		return $table->find($data);
	}
}