<?php

function view(string $path, array $args = []): void
{
	if (!file_exists($path)) {
		throw new \Exception("File '$path' not found.");
	}
	elseif (!is_readable($path)) {
		throw new \Exception("File '$path' is not readable.");
	}
	else {
		extract($args);
		require $path;
	}
}

function route(string $name, array $args = []): ?string
{
	$route = RoutingFacade::getRouteByName($name);
	return ($route) ? $route->getPreparedUri($args) : null;
}

function redirect(string $name, array $args = []): void
{
	$router = RouterFactory::createDefault();
	$router->redirect($name, $args);
}