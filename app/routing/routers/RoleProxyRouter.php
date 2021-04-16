<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

class RoleProxyRouter extends ProxyRouter
{
	public function route(): void;

	public function redirect(string $name): void;
}