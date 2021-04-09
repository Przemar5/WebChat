<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Entity;

class Room extends Entity
{
	public final int $id;
	public string $name;
}