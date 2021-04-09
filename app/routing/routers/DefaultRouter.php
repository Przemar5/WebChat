<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

use App\Routing\RoutingFacade;

class DefaultRouter extends Router
{
	public function route(?Route $route = null): void
	{
		if (is_null($route)) {
			$route = RouterFacade::getRouteByUriAndMethod(
				$_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
		}
		$
	}

	public function redirect(string $name, array $args = []): void
	{
		// header()
	}
}