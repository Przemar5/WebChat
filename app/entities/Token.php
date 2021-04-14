<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Entity;

class Token extends Entity
{
	public string $name;
	public string $value;
	public string $expiry;
	public ?int $user_id;

	public function matches(string $name, string $value, ?int $userId = null): bool
	{
		if (!is_null($userId)) {
			return $name === $this->name && 
				$value === $this->value &&
				$userId === $this->user_id;
		}
		else {
			return $name === $this->name &&
				$value === $this->value;
		}
	}
}