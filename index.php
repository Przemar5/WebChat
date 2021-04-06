<?php

declare(strict_types = 1);

require_once 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('register_globals', '0');

use App\Routing\RoutingFacade;

spl_autoload_register(function ($class) {
	$tokens = explode('\\', $class);
	$classname = array_pop($tokens);
	$tokens = array_map('strtolower', $tokens);
	array_push($tokens, $classname);
	$path = implode(DIRECTORY_SEPARATOR, $tokens) . '.php';

	if (!is_readable($path))
		exit(1);

	require_once $path;
});


function exceptionHandler($e): void
{
	printf("%s was thrown in '%s', line %d: '%s'\r\n",
		get_class($e), 
		$e->getFile(), 
		$e->getLine(), 
		$e->getMessage()
	);

	foreach ($e->getTrace() as $loc) {
		printf("'%s' line %d %s(%s)\r\n", 
			$loc['file'], 
			$loc['line'], 
			$loc['function'],
			implode(", ", $loc['args'] ?? [])
		);
	}
}

set_exception_handler('exceptionHandler');

define('BASE_DIR', __DIR__);
// define('BASE_URI', );


try {
	RoutingFacade::route("text/123");
}
catch (\Exception $e) {
	exceptionHandler($e);
	die;
}