<?php

namespace App\Http\Requests\Fitness;

use App\Models\Fitness\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateMuscleGroupRequest extends FormRequest
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
        $muscleGroup = $this->route('muscle_group');

        if (! $muscleGroup instanceof MuscleGroup) {
            throw new LogicException('The muscle group route parameter must be model-bound.');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('muscle_groups', 'name')->where('user_id', $this->user()?->id)->ignore($muscleGroup),
            ],
        ];
    }
}
