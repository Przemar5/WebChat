<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

abstract class Router
{
	abstract public function route(): void;

	abstract public function redirect(string $name): void;
}