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