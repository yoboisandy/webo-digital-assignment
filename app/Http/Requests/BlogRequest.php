<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BlogRequest extends FormRequest
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
        $uniqueSlug = 'unique:blogs,slug' . ($this->id ? ',' . $this->id : '');
        return [
            'id' => ['nullable', 'exists:blogs,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', $uniqueSlug,  'max:255'],
            'image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'content' => ['required'],
            'blog_category_id' => ['required', 'exists:blog_categories,id'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
