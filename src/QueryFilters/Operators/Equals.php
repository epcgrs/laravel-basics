<?php

namespace Emmanuelpcg\Basics\QueryFilters\Operators;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Emmanuelpcg\Basics\QueryFilters\Filter;

class Equals extends Filter
{
    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return EloquentBuilder|QueryBuilder
     */
    protected function apply($builder)
    {
        return $builder->where($this->columnName(), $this->getValue());
    }
}