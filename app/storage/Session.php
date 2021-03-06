<?php

declare(strict_types = 1);

namespace App\Storage;

use App\Storage\Storage;

class Session implements Storage
{
	public static function start(string $name): void
	{
		session_name($name);
		session_cache_limiter('nocache');
		session_start();
		session_regenerate_id();
	}

	public static function get(string $name)
	{
		if (isset($_SESSION[$name]))
			return $_SESSION[$name];
	}

	public static function set(string $name, $value): void
	{
		$_SESSION[$name] = $value;
	}

	public static function pop(string $name)
	{
		if (isset($_SESSION[$name])) {
			$value = $_SESSION[$name];
			unset($_SESSION[$name]);

			return $value;
		}
	}

	public static function isset(string $name): bool
	{
		return isset($_SESSION[$name]);
	}

	public static function unset(string $name): void
	{
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}
}