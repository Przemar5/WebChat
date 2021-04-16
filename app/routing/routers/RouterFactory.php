<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

use App\Routing\Routers\Router;
use App\Routing\Routers\DefaultRouter;
use App\Routing\Routers\RoleProxyRouter;

class RouterFactory
{
	public static function createDefault(): Router
	{
		$default = new DefaultRouter();
		$roleProxy = new RoleProxyRouter($default);

		return $roleProxy;
	}
}