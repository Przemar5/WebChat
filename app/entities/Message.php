<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Entities\Entity;
use App\Entities\User;

class Message extends Entity
{
	public string $content;
	public string $created_at;
}