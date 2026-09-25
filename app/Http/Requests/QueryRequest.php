<?php

namespace App\Http\Requests;

use App\Common\DataTableQuery;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function is_string;

class QueryRequest extends FormRequest
{
    /** Determine whether this query may be validated for the authenticated route. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate the serialized v9 filtering and multi-sort state.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:75', app()->environment('testing') ? '' : 'in:5,10,25,75'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'sorting' => ['sometimes', 'array', 'list', 'max:10'],
            'sorting.*' => ['array:id,desc'],
            'sorting.*.id' => ['required', 'string', 'max:100', 'distinct', 'regex:/^[a-zA-Z0-9_]+$/'],
            'sorting.*.desc' => ['required', 'boolean'],
            'filters' => ['sometimes', 'array', 'list', 'max:20'],
            'filters.*' => ['array:id,operator,value,filterId,joinOperator'],
            'filters.*.id' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/'],
            'filters.*.operator' => ['required', Rule::in(DataTableQuery::OPERATORS)],
            'filters.*.filterId' => ['sometimes', 'string', 'max:100'],
            'filters.*.joinOperator' => ['sometimes', Rule::in(['and', 'or'])],
            'filters.*.value' => ['present', 'nullable'],
        ];
    }

    /** Decode JSON without silently accepting malformed input or legacy delimiter strings. */
    protected function prepareForValidation(): void
    {
        foreach (['filters', 'sorting'] as $key) {
            $value = $this->input($key);
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $this->merge([$key => $decoded]);
                }
            }
        }
    }
}
