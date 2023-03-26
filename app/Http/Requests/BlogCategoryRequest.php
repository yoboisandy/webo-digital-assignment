<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $id = ($this->route('blog_category')?->id);
        return [
            'name' => [
                'required',
                'string',
                'unique:blog_categories,name' . ($id ? ',' . $id : ''),
                'max:255'
            ],
        ];
    }
}
