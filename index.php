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

function dd($text)
{
	if (is_string($text)) {
		die($text);
	}
	else {
		echo "<pre>";
		var_dump($text);
		die;
	}
}

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
define('BASE_URI', 'http://localhost/projects/Chat');

// $table = App\Database\TableFactory::create('users');
// $user = new App\Entities\User();
// $user->login = 'test';
// $user->email = 'test@email.com';
// $user->password = 'password';
// $user->created_on = '2021-04-15 02:15:30';
// $user->last_login = '2021-04-15 02:15:30';

// $table->save($user);

try {
	$router = new \App\Routing\Routers\DefaultRouter();
	$router->route();
}
catch (\Exception $e) {
	exceptionHandler($e);
	die($e->getMessage());
}