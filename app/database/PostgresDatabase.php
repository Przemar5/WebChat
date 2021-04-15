<?php

declare(strict_types = 1);

namespace App\Database;

use App\Database\DatabaseTemplate;

class PostgresDatabase extends DatabaseTemplate
{
	protected \PDOStatement $stmt;

	protected function __construct(array $connectionDetails = [])
	{
		$connectionDetails['driver'] = 'pgsql';
		$connectionDetails['port'] = $connectionDetails['port'] ?? 5432;

		parent::__construct($connectionDetails);
	}

	public function sendQuery(string $query, ?array $bindings = []): void
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();
	}

	private function bind(array $params): void
	{
		foreach ($params as $key => $value) {
			$this->stmt->bindValue($key, $value, $this->getPdoParamType($value));
		}
	}

	private function getPdoParamType($value = null): int
	{
		switch (gettype($value)) {
			case 'integer':	return \PDO::PARAM_INT;
			case 'string':	return \PDO::PARAM_STR;
			case 'boolean':	return \PDO::PARAM_BOOL;
			case 'null': 	return \PDO::PARAM_NULL;
			default: 		return \PDO::PARAM_NULL;
		}
	}

	public function findOne(
		string $query, 
		?array $bindings = [],
		int $fetchOption = \PDO::FETCH_ASSOC
	)
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetch($fetchOption);
	}

	public function findObject(
		string $query,
		?array $bindings = [],
		string $class = null
	)
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class)[0] ?? null;
	}

	public function findMany(string $query, ?array $bindings = [])
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findManyObjects(
		string $query,
		?array $bindings = [],
		string $class = null
	)
	{
		$this->stmt = $this->database->prepare($query);
		$this->bind($bindings);
		$this->stmt->execute();

		return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
	}

	public function lastInsertId(): int
	{
		return $this->database->lastInsertId();
	}

	public function debug()
	{
		return $this->stmt->debugDumpParams();
	}
}