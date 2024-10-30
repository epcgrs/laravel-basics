<?php

namespace Emmanuelpcg\Basics\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

abstract class Filter
{
    protected bool $ignoreEmpty = true;
    protected ?string $columnName = null;

    /**
     * Aplica o filtro ao builder.
     *
     * @param EloquentBuilder|QueryBuilder $builder
     * @return EloquentBuilder|QueryBuilder
     */
    protected abstract function apply($builder);

    /**
     * Processa o request e aplica o filtro, se aplicável.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->canApply()) {
            return $next($request);
        }

        $builder = $next($request);

        return $this->apply($builder);
    }

    /**
     * Obtém o nome do filtro em snake_case a partir do nome da classe.
     *
     * @return string
     */
    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }

    /**
     * Obtém o nome da coluna para aplicar o filtro.
     *
     * @return string
     */
    protected function columnName(): string
    {
        return $this->columnName ?? $this->filterName();
    }

    /**
     * Obtém o valor do filtro do request.
     *
     * @return mixed
     */
    protected function getValue(): mixed
    {
        return request()->query($this->columnName()) 
               ?? request()->input($this->columnName());
    }

    /**
     * Verifica se o filtro pode ser aplicado, dependendo da presença do valor.
     *
     * @return bool
     */
    protected function canApply(): bool
    {
        $value = $this->getValue();

        return filled($value) || (! $this->ignoreEmpty && $value === '');
    }
}