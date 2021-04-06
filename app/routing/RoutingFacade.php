<?php

declare(strict_types = 1);

namespace App\Routing;

class RoutingFacade
{
	public static function route(string $uri)
	{
		$uri = 'test/123';
		$routes = self::getRoutes();

		foreach ($routes as $route) {
			$pattern = addcslashes($route->pattern, '/');
			echo preg_match('/^' . $pattern . '$/u', $uri);
		}
	}
	
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

		return $data;
	}
}