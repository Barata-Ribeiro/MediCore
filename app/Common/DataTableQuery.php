<?php

namespace App\Common;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Grammar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DataTableQuery
{
    /** Operators accepted by both the request boundary and the table UI. */
    public const array OPERATORS = [
        'includesString', 'notIncludesString', 'startsWith', 'endsWith',
        'equalsString', 'notEqualsString', 'equals', 'notEquals', 'lessThan',
        'lessThanOrEqualTo', 'greaterThan', 'greaterThanOrEqualTo', 'inRange',
        'isRelativeToToday', 'isEmpty', 'isNotEmpty',
    ];

    /**
     * Apply allow-listed filters and ordered sort clauses inside an existing ownership scope.
     * Types prefixed with "count:" filter relationship counts; "sort" permits ordering only.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>  $filters
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  array<string, string>  $columns
     */
    public static function apply(Builder $query, array $filters, array $sorting, array $columns, bool $defaultDescending = false): void
    {
        foreach ($filters as $index => $filter) {
            self::validateFilter($filter, $columns, $index);
        }

        $query->where(function (Builder $group) use ($filters, $columns): void {
            $join = ($filters[0]['joinOperator'] ?? 'and') === 'or' ? 'orWhere' : 'where';
            foreach ($filters as $filter) {
                $group->{$join}(fn (Builder $clause) => self::filter($clause, $filter, $columns[$filter['id']]));
            }
        });

        $applied = [];
        foreach ($sorting as $sort) {
            if (isset($columns[$sort['id']]) && ! in_array($sort['id'], $applied, true)) {
                $query->orderBy($sort['id'], $sort['desc'] ? 'desc' : 'asc');
                $applied[] = $sort['id'];
            }
        }
        if (! in_array('id', $applied, true)) {
            $query->orderBy($query->getModel()->qualifyColumn('id'), $defaultDescending ? 'desc' : 'asc');
        }
    }

    /**
     * Validate operator compatibility and value shape before constructing SQL.
     *
     * @param  array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}  $filter
     * @param  array<string, string>  $columns
     */
    private static function validateFilter(array $filter, array $columns, int $index): void
    {
        $type = $columns[$filter['id']] ?? '';
        $operators = match (true) {
            $type === 'text' => ['includesString', 'notIncludesString', 'startsWith', 'endsWith', 'equalsString', 'notEqualsString', 'isEmpty', 'isNotEmpty'],
            $type === 'date' => ['equals', 'notEquals', 'lessThan', 'lessThanOrEqualTo', 'greaterThan', 'greaterThanOrEqualTo', 'inRange', 'isRelativeToToday', 'isEmpty', 'isNotEmpty'],
            $type === 'boolean' => ['equals', 'notEquals'],
            $type === 'number', str_starts_with($type, 'count:') => ['equals', 'notEquals', 'lessThan', 'lessThanOrEqualTo', 'greaterThan', 'greaterThanOrEqualTo', 'inRange'],
            default => [],
        };
        $prefix = "filters.$index";
        $rules = [
            "$prefix.id" => [Rule::in(array_keys(array_filter($columns, fn (string $type): bool => $type !== 'sort')))],
            "$prefix.operator" => [Rule::in($operators)],
        ];
        $operator = $filter['operator'];
        if (! in_array($operator, ['isEmpty', 'isNotEmpty'], true)) {
            $valueRule = match (true) {
                $operator === 'isRelativeToToday' => ['integer', 'between:-36500,36500'],
                $type === 'date' => ['date_format:Y-m-d'],
                $type === 'boolean' => [Rule::in(['0', '1', 0, 1, false, true])],
                str_starts_with($type, 'count:') => ['integer', 'min:0'],
                $type === 'number' => ['numeric'],
                default => ['string', 'max:255'],
            };
            if ($operator === 'inRange') {
                $rules["$prefix.value"] = ['required', 'array', 'list', 'size:2'];
                $rules["$prefix.value.*"] = ['nullable', ...$valueRule];
                $rules["$prefix.value.0"] = ["required_without:$prefix.value.1", 'nullable', ...$valueRule];
                $rules["$prefix.value.1"] = ["required_without:$prefix.value.0", 'nullable', ...$valueRule];
            } elseif ($type === 'boolean' && is_array($filter['value'] ?? null)) {
                $rules["$prefix.value"] = ['required', 'array', 'list', 'min:1', 'max:2'];
                $rules["$prefix.value.*"] = ['required', ...$valueRule];
            } else {
                $rules["$prefix.value"] = ['required', ...$valueRule];
            }
        }
        Validator::make(['filters' => [$index => $filter]], $rules)->validate();
    }

    /**
     * Dispatch one validated filter. Each clause stays inside its own parentheses.
     *
     * @param  Builder<Model>  $query
     * @param  array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}  $filter
     */
    private static function filter(Builder $query, array $filter, string $type): void
    {
        $column = $query->getModel()->qualifyColumn($filter['id']);
        $value = $filter['value'] ?? null;
        $operator = $filter['operator'];

        if ($operator === 'inRange') {
            if (($value[0] ?? '') !== '' && $value[0] !== null) {
                self::compare($query, $column, '>=', $value[0], $type);
            }
            if (($value[1] ?? '') !== '' && $value[1] !== null) {
                self::compare($query, $column, '<=', $value[1], $type);
            }

            return;
        }
        if ($operator === 'isRelativeToToday') {
            self::compare($query, $column, '=', CarbonImmutable::today()->addDays((int) $value)->toDateString(), $type);

            return;
        }
        if (in_array($operator, ['isEmpty', 'isNotEmpty'], true)) {
            if ($operator === 'isEmpty') {
                $query->whereNull($column);
                if ($type === 'text') {
                    $query->orWhere($column, '');
                }
            } else {
                $query->whereNotNull($column);
                if ($type === 'text') {
                    $query->where($column, '!=', '');
                }
            }

            return;
        }
        if (is_array($value)) {
            $query->whereIn($column, $value, not: $operator === 'notEquals');

            return;
        }
        $comparison = match ($operator) {
            'equals', 'equalsString' => '=',
            'notEquals', 'notEqualsString' => '!=',
            'lessThan' => '<',
            'lessThanOrEqualTo' => '<=',
            'greaterThan' => '>',
            'greaterThanOrEqualTo' => '>=',
            default => null,
        };
        if ($comparison !== null) {
            self::compare($query, $column, $comparison, $value, $type);

            return;
        }

        // Use an explicit escape character consistently across SQLite, MySQL and PostgreSQL.
        $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], (string) $value);
        $pattern = match ($operator) {
            'startsWith' => "$escaped%",
            'endsWith' => "%$escaped",
            default => "%$escaped%",
        };
        $comparison = $operator === 'notIncludesString' ? 'NOT LIKE' : 'LIKE';
        $query->whereRaw(self::textExpression($column, $comparison, true), [$pattern]);
    }

    /**
     * Apply a scalar, calendar-day or relationship-count comparison with bound values.
     *
     * @param  Builder<Model>  $query
     */
    private static function compare(Builder $query, string $column, string $operator, mixed $value, string $type): void
    {
        if ($type === 'text') {
            $query->whereRaw(self::textExpression($column, $operator), [$value]);

            return;
        }
        match (true) {
            $type === 'date' => $query->whereDate($column, $operator, $value),
            str_starts_with($type, 'count:') => $query->has(substr($type, 6), $operator, (int) $value),
            default => $query->where($column, $operator, $value),
        };
    }

    /**
     * Compile a case-insensitive comparison using the connection's identifier quoting.
     * The column has already passed the allow-list; the value is always a separate binding.
     */
    private static function textExpression(string $column, string $operator, bool $pattern = false): Expression
    {
        return new class($column, $operator, $pattern) implements Expression
        {
            public function __construct(private string $column, private string $operator, private bool $pattern) {}

            /** Compile the identifier and operator without interpolating the filter value. */
            public function getValue(Grammar $grammar): string
            {
                $operator = match ($this->operator) {
                    '!=' => '!=',
                    'LIKE' => 'LIKE',
                    'NOT LIKE' => 'NOT LIKE',
                    default => '=',
                };
                $escape = $this->pattern ? " ESCAPE '!'" : '';

                return 'LOWER('.$grammar->wrap($this->column).") $operator LOWER(?)$escape";
            }
        };
    }
}
