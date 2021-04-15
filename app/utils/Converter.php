<?php

declare(strict_types = 1);

namespace App\Utils;

class Converter
{
	public static function jsonToArray(string $json): array
	{
		return json_decode($json, true);
	}
}