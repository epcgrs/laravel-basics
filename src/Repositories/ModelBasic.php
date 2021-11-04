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

    protected bool $createIfHasKey = false;

    protected abstract function getEntityInstance(): Model;

    protected function byKey($key): ?Model
    {
        return $this->getEntityInstance()->find($key);
    }

    protected function save(array $data): ?Model
    {
        $entity = $this->getEntityInstance();

        if( ! empty($data[$this->getEntityKeyName()]))
        {
            $entityFound = $this->byKey($data[$this->getEntityKeyName()]);

            if( ! is_null($entityFound) || ! $this->createIfHasKey)
                $entity = $entityFound;
        }

        return $entity->fill($data)->save() ? $entity : NULL;
    }

    protected function updateByKey($key, array $data): ?Model
    {
        $data[$this->getEntityKeyName()] = $key;

        return $this->save($data);
    }

    protected function getEntityKeyName(): string
    {
        return $this->getEntityInstance()->getKeyName();
    }

    protected function create(array $data): ?Model
    {
        $model = $this->getEntityInstance();

        if($model->fill($data)->save())
            return $model;

        return NULL;
    }

    protected function byColumn(string $column, $value): ?Model
    {
        return $this->getEntityInstance()->where($column, $value)->first();
    }

    protected function allWhere(string $column, $value): Collection
    {
        return $this->getEntityInstance()->where($column, $value)->get();
    }

    protected function all(): Collection
    {
        return $this->getEntityInstance()->all();
    }

    protected function delete($key): bool
    {
        if($model = $this->byKey($key))
            return $model->delete();

        return FALSE;
    }

    /**
     * @param array|null $filters
     * @param QueryBuilder | EloquentBuilder | null $builder
     * @return mixed
     */
    protected function qbApplyFilters(array $filters, $builder = null)
    {
        return $this->pipeApplyFilter(
            $filters,
            $builder ?? $this->getEntityInstance()->query()
        );
    }
}