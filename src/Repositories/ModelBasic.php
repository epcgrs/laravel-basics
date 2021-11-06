<?php

namespace Emmanuelpcg\Basics\Repositories;

use Emmanuelpcg\Basics\Useful\PipeFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

abstract class ModelBasic
{
    use PipeFilters;

    protected $createIfHasKey = false;

    protected abstract function getEntityInstance(): Model;

    protected function __byKey($key): ?Model
    {
        return $this->getEntityInstance()->find($key);
    }

    protected function __save(array $data): ?Model
    {
        $entity = $this->getEntityInstance();

        if( ! empty($data[$this->__getEntityKeyName()]))
        {
            $entityFound = $this->__byKey($data[$this->__getEntityKeyName()]);

            if( ! is_null($entityFound) || ! $this->createIfHasKey)
                $entity = $entityFound;
        }

        return $entity->fill($data)->save() ? $entity : NULL;
    }

    protected function __updateByKey($key, array $data): ?Model
    {
        $data[$this->__getEntityKeyName()] = $key;

        return $this->__save($data);
    }

    protected function __getEntityKeyName(): string
    {
        return $this->getEntityInstance()->getKeyName();
    }

    protected function __create(array $data): ?Model
    {
        $model = $this->getEntityInstance();

        if($model->fill($data)->save())
            return $model;

        return NULL;
    }

    protected function __byColumn(string $column, $value): ?Model
    {
        return $this->getEntityInstance()->where($column, $value)->first();
    }

    protected function __allWhere(string $column, $value): Collection
    {
        return $this->getEntityInstance()->where($column, $value)->get();
    }

    protected function __all(): Collection
    {
        return $this->getEntityInstance()->all();
    }

    protected function __delete($key): bool
    {
        if($model = $this->__byKey($key))
            return $model->delete();

        return FALSE;
    }

    /**
     * @param array $filters
     * @param EloquentBuilder | QueryBuilder | null $builder
     * @return mixed
     */
    protected function __qbApplyFilters(array $filters, $builder = null)
    {
        return $this->pipeApplyFilter(
            $filters,
            $builder ?? $this->getEntityInstance()->query()
        );
    }
}