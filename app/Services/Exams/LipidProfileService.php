<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Models\Exams\LipidProfile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LipidProfileService implements LipidProfileServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, LipidProfile>, 1: array<string, mixed>}
     */
    public function getLipidProfileData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $lipidProfiles = LipidProfile::query()
            ->select('lipid_profiles.*')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereLike('total_cholesterol', "%{$search}%")
                    ->orWhereLike('hdl_cholesterol', "%{$search}%")
                    ->orWhereLike('ldl_cholesterol', "%{$search}%")
                    ->orWhereLike('vldl_cholesterol', "%{$search}%")
                    ->orWhereLike('triglycerides', "%{$search}%");
            }))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'report_date' => 'date',
                'total_cholesterol' => 'number',
                'hdl_cholesterol' => 'number',
                'ldl_cholesterol' => 'number',
                'vldl_cholesterol' => 'number',
                'triglycerides' => 'number',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     total_cholesterol: float|int|null,
         *     hdl_cholesterol: float|int|null,
         *     ldl_cholesterol: float|int|null,
         *     vldl_cholesterol: float|int|null,
         *     triglycerides: float|int|null
         * }> $chartRows
         */
        $chartRows = LipidProfile::query()
            ->selectRaw('DATE(report_date) as label, AVG(total_cholesterol) as total_cholesterol, AVG(hdl_cholesterol) as hdl_cholesterol, AVG(ldl_cholesterol) as ldl_cholesterol, AVG(vldl_cholesterol) as vldl_cholesterol, AVG(triglycerides) as triglycerides')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'total_cholesterol' => ['label' => __('lipid_profile_pages.index.table.columns.total_cholesterol'), 'data' => $row->total_cholesterol],
                'hdl_cholesterol' => ['label' => __('lipid_profile_pages.index.table.columns.hdl_cholesterol'), 'data' => $row->hdl_cholesterol],
                'ldl_cholesterol' => ['label' => __('lipid_profile_pages.index.table.columns.ldl_cholesterol'), 'data' => $row->ldl_cholesterol],
                'vldl_cholesterol' => ['label' => __('lipid_profile_pages.index.table.columns.vldl_cholesterol'), 'data' => $row->vldl_cholesterol],
                'triglycerides' => ['label' => __('lipid_profile_pages.index.table.columns.triglycerides'), 'data' => $row->triglycerides],
            ],
        ])->toArray();

        return [$lipidProfiles, $chartData];
    }
}
