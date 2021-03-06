<?php

namespace App\JsonApi;

use Blueprint\Builder;
use Closure;
use Illuminate\Support\Str;

class JsonApiQueryBuilder
{
    public function allowedSorts(): Closure
    {
        return function ($allowedSorts){
            /** @var Builder $this */
            if (request()->filled('sort')) {
                $sortFields = explode(',', request()->input('sort'));

                $allowedSorts = ['title', 'content'];

                foreach ($sortFields as $sortField) {
                    $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

                    $sortField = ltrim($sortField, '-');

                    abort_unless(in_array($sortField, $allowedSorts), 400);
                    /** @var Builder $this */
                    $this->orderBy($sortField, $sortDirection);
                }
            }

            return $this;
        };
    }

    public function allowedFilters(): Closure
    {
        return function ($allowedFilters) {
            /** @var Builder $this */
            foreach (request('filter', []) as $filter => $value) {
                //dump($filter); //year
                //dump($value);
                abort_unless(in_array($filter, $allowedFilters), 400);

                $this->hasNamedScope($filter)
                    ? $this->{$filter}($value)
                    : $this->where($filter,'LIKE', '%'.$value.'%');

            }
            /** retornar el builder para seguir encadenando metodos. */
            return $this;
        };
    }

    public function allowedIncludes(): Closure
    {
        return function($allowedIncludes) {
            /** @var Builder $this */
        if (request()->isNotFilled('include')) {
            return $this;
        }
        $includes = explode(',', request()->input('include'));
        //dd($includes);
        foreach ($includes as $include) {
            abort_unless(in_array($include, $allowedIncludes), 400);
            $this->with($include);
        }

        return $this;
        };
    }

    public function jsonPaginate(): Closure
    {
        return function () {
            /** @var Builder $this */
            return $this->paginate(
                $perPage = request('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends(request()->only('sort', 'filter','page.size'));
        };
    }

    public function sparseFieldset(): Closure
    {
        return function () {
            /** @var Builder $this */
            if (request()->isNotFilled('fields')) {
                return $this;
            }

            //Obtener los valores por separado
            $fields = (explode(',', request('fields.'.$this->getResourceType())));

            $routeKeyName = $this->model->getRouteKeyName();

            if (! in_array($routeKeyName, $fields)) {
                $fields[] = 'slug';
            }
            return $this->addSelect($fields);

        };
    }

    public function getResourceType(): Closure
    {
        return function () {
            /** @var Builder $this */

            if (property_exists($this->model, 'resourceType')) {
                return $this->model->resourceType;
            }

            return $this->model->getTable();
        };
    }
}
