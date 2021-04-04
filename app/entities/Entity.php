<?php

declare(strict_types = 1);

namespace App\Entities;

abstract class Entity
{
	public final int $id;

	public function __construct(?int $id = null)
	{
		$this->id = $id;
	}
}