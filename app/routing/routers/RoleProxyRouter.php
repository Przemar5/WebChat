<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

class RoleProxyRouter extends Router
{
	public function route(): void;

	public function redirect(string $name): void;
}