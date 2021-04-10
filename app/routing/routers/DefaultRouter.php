<?php

declare(strict_types = 1);

namespace App\Routing\Routers;

use App\Routing\RoutingFacade;
use App\Controllers\ControllerFactory;

class DefaultRouter extends Router
{
	private string $controllerNamespace = 'App\\Controllers\\';

	public function route(?Route $route = null): void
	{
		$uri = $_SERVER['PATH_INFO'] ?? '/';

		if (is_null($route)) {
			$route = RoutingFacade::getRouteByUriAndMethod(
				$uri, $_SERVER['REQUEST_METHOD']);
		}

		// If route still doesn't exist
		if (is_null($route)) {
			throw new \Exception("Route not found.");
		}
		$callback = $route->getCallback();
		$args = $route->getArgsForUri($uri);

		if (is_callable($callback)) {
			call_user_func_array($callback, $args);
		}
		elseif (is_string($callback)) {
			$callback = ControllerFactory::createCallbackFromString($callback);

			call_user_func_array($callback, $args);
		}
		else {
			throw new \Exception(
				"Route's callback must be type 'string' or " . 
				"'callable', but it's '" . gettype($callback) . "'.");
		}
	}

	public function redirect(string $name, array $args = []): void
	{
		$route = RoutingFacade::getRouteByName($name);
		$uri = $route->getPreparedUri($args);

		if (headers_sent()) {
			echo '<meta http-equiv="refresh" content="0; url=$uri">';
			echo '<script>window.location.href=' . $uri . '</script>';
		}
		else {
			header('Location: ' . $uri);
		}
	}
}