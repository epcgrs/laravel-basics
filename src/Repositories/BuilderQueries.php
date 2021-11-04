<?php

namespace Emmanuelpcg\Basics\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BuilderQueries
{
    public function builderIf()
    {
        /**
         * Eloquent builder if function
         *
         * @param $condition true|false statement
         * @param $column
         * @param $operator
         * @param $value
         */
        Builder::macro('if', function ($condition, $column, $operator, $value) {
            if ($condition) {
                return $this->where($column, $operator, $value);
            }

            return $this;
        });
    }
}