<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

use App\Routing\Routers\Router;

abstract class ProxyRouter extends Router
{
	private Router $next;

	public function __construct(Router $next)
	{
		$this->next = $next;
	}
}