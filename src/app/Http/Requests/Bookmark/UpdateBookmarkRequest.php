<?php

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateBookmarkRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'context_id' => ['nullable', 'numeric', 'integer'],
            'link' => ['required', 'url', 'max:400'],
            'name' => ['nullable', 'string', 'max:150'],
            'thumbnail_id' => ['nullable', 'numeric', 'integer'],
            'thumbnailFile' => ['nullable', File::types([
                'jpg',
                'jpeg',
                'png',
                'bmp',
                'gif',
                'svg',
                'webp',
                'ico'
            ])->max(2048)],
            'order' => ['nullable', 'numeric', 'integer'],
            'tags' => ['nullable', 'array'],
        ];
    }
}
