<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GitHubVersionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'version' => 'required|regex:/^v[0-9]+.[0-9]+.[0-9]+$/',
        ];
    }
}
