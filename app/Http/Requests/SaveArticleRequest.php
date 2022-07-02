<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.attributes.title' => [ 'required', 'min:4'],
            'data.attributes.slug' => [
                'required',
                'alpha_dash',
                new Slug(),
                Rule::unique('articles', 'slug')->ignore($this->route('article'))
            ],
            'data.attributes.content' => ['required'],
            'data.relationships.category.data.id' => [
                Rule::requiredIf(! $this->route('article')),
                Rule::exists('categories', 'slug')
            ]
        ];
    }

    public function validated()
    {
        //return parent::validated()['data']['attributes'];
        $data = parent::validated()['data'];
        $attributes = $data['attributes'];

        if (isset($data['relationships'])) {
            $relationships = $data['relationships'];
            $categorySlug = $relationships['category']['data']['id'];
            $category = Category::where('slug', $categorySlug)->first();
            $attributes['category_id'] = $category->id;
        }

        return $attributes;
    }
}
