<?php

declare(strict_types = 1);

namespace App\Utils;

use App\Storage\Session;

class Notification
{
	private static string $errorPrefix = 'error_';

	public static function addSuccess(string $msg): void
	{
		Session::set('success', $msg);
	}

	public static function getSuccess(): ?string
	{
		return Session::get('success');
	}

	public static function popSuccess(): ?string
	{
		return Session::pop('success');
	}

	public static function addError(string $name, string $msg): void
	{
		$key = self::$errorPrefix . $name;
		Session::set($key, $msg);
	}

	public static function getError(string $name): ?string
	{
		$key = self::$errorPrefix . $name;

		return Session::get($key);
	}

	public static function popError(string $name): ?string
	{
		$key = self::$errorPrefix . $name;

		return Session::pop($key);
	}
}