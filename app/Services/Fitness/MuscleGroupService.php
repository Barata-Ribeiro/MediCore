<?php

namespace App\Services\Fitness;

use App\Common\DataTableQuery;
use App\Interfaces\Fitness\MuscleGroupServiceInterface;
use App\Models\Fitness\MuscleGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MuscleGroupService implements MuscleGroupServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return LengthAwarePaginator<int, MuscleGroup>
     */
    public function getMuscleGroupsData(?int $perPage, array $sorting, ?string $search, ?array $filters): LengthAwarePaginator
    {

        return MuscleGroup::query()
            ->whereBelongsTo(auth()->user())
            ->withCount('exercises')
            ->when($search, fn ($q) => $q->whereLike('name', "%{$search}%"))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'name' => 'text',
                'exercises_count' => 'count:exercises',
                'created_at' => 'date',
                'updated_at' => 'date',
            ]))
            ->paginate($perPage ?? 10)
            ->withQueryString();
    }
}
