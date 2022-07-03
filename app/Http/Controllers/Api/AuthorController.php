<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorController extends Controller
{
    public function show($author): JsonResource
    {
        $author = User::findOrFail($author);

        return AuthorResource::make($author);
    }

    public function index(): AnonymousResourceCollection
    {
        $authors = User::jsonPaginate();

        return AuthorResource::collection($authors);
    }

}
