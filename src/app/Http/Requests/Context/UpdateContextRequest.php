<?php

namespace App\Http\Requests\Context;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateContextRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'parent_context_id' => 'nullable|numeric|integer',
            'order' => 'nullable|numeric|integer',
            'thumbnail_id' => 'nullable|numeric|integer',
            'thumbnailFile' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'webp', 'ico'])->max(2048)],
            'tags' => 'nullable|array',
        ];
    }
}
