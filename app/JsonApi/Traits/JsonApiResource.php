<?php

namespace App\JsonApi\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait JsonApiResource
{
    /* esta funcion abstracta, es para obligar a implementar el toJson en los Jsonresources*/
    abstract public function toJsonApi(): array;

    public function toArray($request): array
    {
        return [
            'type' => $this->getResourceType(),
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => $this->filterAttributes($this->toJsonApi()),
            'links' => [
                'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource)
            ]
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header(
            'Location',
            route('api.v1.'.$this->getResourceType().'.show', $this->resource)
        );
    }

    public function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, function ($value) {
            if (request()->isNotFilled('fields')) {
                return true;
            }

            $fields = explode(',', request('fields.'.$this->getResourceType()));

            if ($value === $this->getRouteKey()) {
                return in_array('slug', $fields);
            }

            return $value;
        });
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        $collection = parent::collection($resource);

        $collection->with['links'] = ['self' => $resource->path()];

        return $collection;
    }

}
