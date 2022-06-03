<?php

namespace App\JsonApi;

use Illuminate\Support\Collection;

class Document extends Collection
{

    public static function type(string $type): Document
    {
        return new self([
            'data' => [
                'type' => $type
            ]
        ]);
    }

    public function id($id): Document
    {
        if ($id) {
            $this->items['data']['id'] = (string) $id;
        }

        return $this;
    }

    public function attributes(array $attributes): Document
    {
        unset($attributes['_relationships']);
        $this->items['data']['attributes'] = $attributes;

        return $this;
    }

    public function links(array $links): Document
    {
        $this->items['data']['links'] = $links;

        return $this;
    }

    public function relationships(array $relationships):Document
    {
        foreach ($relationships as $key => $relationship) {
            $this->items['data']['relationships'][$key] = [
                'data' => [
                    'type' => $relationship->getResourceType(),
                    'id' => $relationship->getRouteKey()
                ]
            ];
        }

        return $this;
    }

}
