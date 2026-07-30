<?php

namespace App\Http\Requests\Fitness;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExerciseRequest extends FormRequest
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
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $exerciseId = (int) $this->route('exercise')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('exercises', 'name')->where('user_id', $this->user()?->id)->ignore($exerciseId),
            ],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'muscle_group_ids' => ['sometimes', 'array'],
            'muscle_group_ids.*' => [
                'integer',
                Rule::exists('muscle_groups', 'id')->where('user_id', $this->user()?->id),
            ],
        ];
    }
}
