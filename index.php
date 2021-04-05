<?php

declare(strict_types = 1);

require_once 'functions.php';

spl_autoload_register(function ($class) {
	$tokens = explode('\\', $class);
	$classname = array_pop($tokens);
	$tokens = array_map('lowercase', $tokens);
	array_push($tokens, $classname);
	$path = implode(DIRECTORY_SEPARATOR, $tokens) . '.php';

	if (!is_readable($path))
		exit(1);

	require_once $path;
});


function exceptionHandler(\Exception $e): void
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
	
}
catch (\Exception $e) {
	exceptionHandler($e);
	die;
}