<?php

declare(strict_types = 1);

namespace App\Database;

use App\Database\Query_builders\QueryBuilderTemplate;

abstract class DatabaseTemplate
{
	protected \PDO $database;
	protected QueryBuilderTemplate $queryBuilder;
	protected static DatabaseTemplate $instance;

	protected function __construct(array $connectionDetails = [])
	{
		$dsn = sprintf('%s:host=%s;port=%s;dbname=%s;',
			$this->getDetailOrThrowException(
				$connectionDetails, 'driver', 'Database driver is not specified'),
			$this->getDetailOrThrowException(
				$connectionDetails, 'host', 'Database host is not specified'),
			$this->getDetailOrThrowException(
				$connectionDetails, 'port', 'Database port is not specified'),
			$this->getDetailOrThrowException(
				$connectionDetails, 'dbname', 'Database name is not specified')
		);

		$user = $this->getDetailOrThrowException(
			$connectionDetails, 'username', 'Username is missing');
		$password = $this->getDetailOrThrowException(
			$connectionDetails, 'password', 'Password is missing');

		$this->database = new \PDO($dsn, $user, $password);
		$this->database->setAttribute(
			\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	protected function getDetailOrThrowException(
		array $connectionDetails = [], 
		string $detail, 
		string $msg
	)
	{
		if (!isset($connectionDetails[$detail]))
			throw new \Exception($msg);
		
		return $connectionDetails[$detail];
	}

	public static function getInstance(?array $details = null): self
	{
		if (!isset(static::$instance)) {
			if (is_null($details))
				throw new \Exception('Details array is missing.');

			static::$instance = new static($details);
		}

		return static::$instance;
	}

	abstract public function sendQuery(string $query, ?array $bindings = []);

	abstract public function findOne(
		string $query, 
		?array $bindings = []
	);

	abstract public function findObject(
		string $query,
		?array $bindings = [],
		?string $object = null
	);

	abstract public function findMany(string $query, ?array $bindings = []);

	abstract public function lastInsertId();
}