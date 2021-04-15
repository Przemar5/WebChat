<?php

declare(strict_types = 1);

namespace App\Database\Factories;

use App\Database\DatabaseTemplate;
use App\Database\MySQLDatabase;
use App\Database\PostgresDatabase;
use App\Database\Query_builders\QueryBuilderTemplate;
use App\Database\Query_builders\MySQLQueryBuilder;
use App\Database\Query_builders\PostgresQueryBuilder;
use App\Utils\Converter;

class DatabaseAbstractFactory
{
	public static function createDatabase(): DatabaseTemplate
	{
		$config = file_get_contents('./config/database.json');
		$details = Converter::jsonToArray($config);

		switch ($details['driver']) {
			case 'mysql': 	return MySQLDatabase::getInstance($details);
			case 'pgsql': 	return PostgresDatabase::getInstance($details);
			default: 		return MySQLDatabase::getInstance($details);
		}
	}

	public static function createQueryBuilder(): QueryBuilderTemplate
	{
		$config = file_get_contents('./config/database.json');
		$details = Converter::jsonToArray($config);

		switch ($details['driver']) {
			case 'mysql': 		return new MySQLQueryBuilder();
			case 'pgsql': 		return new PostgresQueryBuilder();
			default: 			return new MySQLQueryBuilder();
		}
	}
}