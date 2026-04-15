<?php

namespace App\Http\Requests\Auth;

use Laravel\Fortify\Http\Requests\VerifyEmailRequest as FortifyVerifyEmailRequest;

class VerifyEmailRequest extends FortifyVerifyEmailRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expires' => ['required', 'integer'],
            'signature' => ['required', 'string'],
        ];
    }
}
