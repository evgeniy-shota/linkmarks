<?php

namespace App\Http\Requests\Context;

use Illuminate\Foundation\Http\FormRequest;

class ShowDataContextRequest extends FormRequest
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
            "tagsIncluded" => 'nullable|array',
            "tagsExcluded" => 'nullable|array',
            'discardToContexts' => 'nullable|boolean',
            'discardToBookmarks' => 'nullable|boolean',
            'contextualFiltration' => 'nullable|boolean',
            'groupDeepFiltration' => [
                'exclude_with:contextualFiltration',
                'nullable',
                'boolean'
            ],
        ];
    }
}
