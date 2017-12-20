<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    protected $request;

    protected $filters = [];
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function apply($builder)
    {
        $this->builder = $builder;
        $this->getFilters()
            ->filter(function ($filter) {
                return method_exists($this, $filter);
            })->each(function ($filter, $value) {
                $this->$filter($value);
            });
        return $this->builder;
    }

    protected function getFilters()
    {
        return collect($this->request->only($this->filters))->flip();
    }
}
