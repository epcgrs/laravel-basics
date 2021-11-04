<?php

namespace Emmanuelpcg\Basics\Useful;

use \Illuminate\Pipeline\Pipeline;

trait PipeFilters
{
    /**
     * Apply QueryFilters To Pipeline
     *
     * @param  array  $applicable
     * @param  mixed  $passable
     * @return mixed
     */
    protected function pipeApplyFilter(array $applicable, $passable)
    {
        return app(Pipeline::class)
            ->send($passable)
            ->through($applicable)
            ->thenReturn();
    }
}