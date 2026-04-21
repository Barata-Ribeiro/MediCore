<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class LocaleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, In|string>|string>
     */
    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', Rule::in(['en', 'pt_BR'])],
        ];
    }
}
