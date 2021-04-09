<?php

declare(strict_types = 1);

namespace App\Routing;

class RoutingFacade
{
	public static function getRouteByName(string $name): Route
	{
		$routes = self::getRoutes();

		foreach ($routes as $route) {
			if ($route->getName() === $name)
				return $route;
		}
	}

	public static function getRouteByUriAndMethod(
		string $uri, 
		string $method
	)
	{
		$routes = self::getRoutes();

		foreach ($routes as $route) {
			if ($route->matchByUriAndMethod($uri, $method))
				return $route;
		}
	}

	// public static function route(string $name, array $args = [])
	// {
	// 	$routes = self::getRoutes();

	// 	foreach ($routes as $route) {
	// 		if ($route->matchByUriAndMethod($uri, $method)) {
	// 			$uri = $route->getPreparedUri($args);
	// 			// Route
	// 		}
	// 	}
	// }
	
	public static function getRoutes(): array
	{
		$filename = BASE_DIR . '/config/routes.json';

		if (!file_exists($filename)) {
			throw new \Exception(
				"File '" . $filename . "' is missing.");
		}
		elseif (!is_readable($filename)) {
			throw new \Exception(
				"File '" . $filename . "' is not readable.");
		}
		$file = file_get_contents($filename);
		$data = json_decode($file);

		return array_map(fn($r) => new Route($r), $data);
	}
}