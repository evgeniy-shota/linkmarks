<?php

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreBookmarkRequest extends FormRequest
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
            'context_id' => ['required', 'numeric', 'integer'],
            'link' => ['required', 'url', 'max:400'],
            'name' => ['sometimes', 'string', 'max:150'],
            'thumbnailFile' => [
                File::types([
                    'jpg',
                    'jpeg',
                    'png',
                    'bmp',
                    'gif',
                    'svg',
                    'webp',
                    'ico'
                ])->max(2048)
            ],
            'thumbnail_id' => ['numeric', 'integer'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'order' => ['sometimes', 'numeric', 'integer'],
        ];
    }
}
