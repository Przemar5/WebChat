<?php

declare(strict_types = 1);

namespace App\Database\Tables;

use App\Entities\Entity;
use App\Database\Factories\DatabaseAbstractFactory;
use App\Database\DatabaseTemplate;
use App\Database\Query_builders\QueryBuilderTemplate;

class Table
{
	protected string $name;
	protected string $class;
	protected ?string $softDeleteCol = null;
	protected DatabaseTemplate $database;
	protected QueryBuilderTemplate $queryBuilder;

	public function __construct(
		string $name, 
		string $class, 
		?string $softDeleteCol = null,
		DatabaseTemplate $database,
		QueryBuilderTemplate $queryBuilder
	)
	{
		$this->name = $name;
		$this->class = $class;
		$this->softDeleteCol = $softDeleteCol;
		$this->database = $database;
		$this->queryBuilder = $queryBuilder;
	}

	public function find(array $data = [])
	{
		if (!is_null($this->softDeleteCol)) {
			$data[$this->softDeleteCol] = false;
		}
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->where($data);
		$this->queryBuilder->limit(1);

		return $this->database->findObject(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings(),
			$this->class
		);
	}

	public function findMany(array $data = []): ?array
	{
		if (!is_null($this->softDeleteCol)) {
			$data[$this->softDeleteCol] = false;
		}
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->where($data);

		return $this->database->findManyObjects(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings(),
			$this->class
		);
	}

	public function findRaw(array $data = []): ?array
	{
		if (!is_null($this->softDeleteCol)) {
			$data[$this->softDeleteCol] = false;
		}
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->where($data);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function findManyRaw(array $data = []): ?array
	{
		if (!is_null($this->softDeleteCol)) {
			$data[$this->softDeleteCol] = false;
		}
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->where($data);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function save(Entity $entity): void
	{
		if (isset($entity->id)) {
			$this->update($entity);
		}
		else {
			$this->insert($entity);
		}
	}

	public function insert(Entity $entity): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->insert((array) $entity);
		$this->queryBuilder->in($this->name);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function update(Entity $entity): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update((array) $entity);
		$this->queryBuilder->where(['id' => $entity->id]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function delete(array $data): void
	{
		if (is_null($this->softDeleteCol)) {
			$this->hardDelete($data);
		}
		else {
			$this->softDelete($data);
		}
	}

	public function hardDelete(array $data): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function softDelete(array $data): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update([$this->softDeleteCol => true]);
		$this->queryBuilder->where($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}