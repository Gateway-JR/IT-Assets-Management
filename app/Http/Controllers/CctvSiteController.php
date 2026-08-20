<?php

namespace App\Http\Controllers;

use App\Models\CctvSite;
use App\Services\CctvInventoryWorkbookImporter;
use App\Services\CctvInventoryXlsExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CctvSiteController extends Controller
{
    public function index(Request $request): View
    {
        $sites = $this->filteredQuery($request)
            ->orderBy($this->sortColumn($request), $request->input('direction') === 'desc' ? 'desc' : 'asc')
            ->paginate(15)
            ->withQueryString();

        $totals = CctvSite::query()
            ->selectRaw('COUNT(*) as records')
            ->selectRaw('COALESCE(SUM(total_cameras), 0) as total')
            ->selectRaw('COALESCE(SUM(online_cameras), 0) as online')
            ->selectRaw('COALESCE(SUM(offline_cameras), 0) as offline')
            ->selectRaw('COALESCE(SUM(recording_issue_cameras), 0) as issues')
            ->selectRaw('COALESCE(SUM(nvr_hdd_capacity_gb), 0) as storage_capacity')
            ->selectRaw('COUNT(nvr_hdd_capacity_gb) as storage_records')
            ->first();

        $branchCount = CctvSite::query()
            ->get(['branch', 'region', 'province'])
            ->unique(fn (CctvSite $site): string => implode('|', [
                strtolower(trim((string) $site->branch)),
                strtolower(trim((string) $site->region)),
                strtolower(trim((string) $site->province)),
            ]))
            ->count();

        $totalCameras = (int) $totals->total;
        $storageCapacity = (float) $totals->storage_capacity;
        $summary = [
            'branches' => $branchCount,
            'records' => (int) $totals->records,
            'total' => $totalCameras,
            'online' => (int) $totals->online,
            'offline' => (int) $totals->offline,
            'issues' => (int) $totals->issues,
            'availability' => $totalCameras > 0
                ? round(((int) $totals->online / $totalCameras) * 100, 1)
                : 0.0,
            'storage_capacity_tb' => round($storageCapacity / 1024, 1),
            'storage_records' => (int) $totals->storage_records,
        ];

        $branchOverview = $this->filteredQuery($request)
            ->get([
                'id',
                'branch',
                'region',
                'province',
                'business_unit',
                'total_cameras',
                'online_cameras',
                'offline_cameras',
                'recording_issue_cameras',
                'nvr_status',
            ])
            ->groupBy(fn (CctvSite $site): string => implode('|', [
                strtolower(trim((string) $site->branch)),
                strtolower(trim((string) $site->region)),
                strtolower(trim((string) $site->province)),
            ]))
            ->map(function ($records): array {
                /** @var CctvSite $site */
                $site = $records->first();
                $total = (int) $records->sum('total_cameras');
                $online = (int) $records->sum('online_cameras');
                $offline = (int) $records->sum('offline_cameras');
                $issues = (int) $records->sum('recording_issue_cameras');
                $nvrAttention = $records->filter(
                    fn (CctvSite $record): bool => $record->nvr_status !== null && ! $record->nvr_is_healthy
                )->count();

                return [
                    'branch' => $site->branch,
                    'region' => $site->region ?: 'Region not reported',
                    'province' => $site->province ?: 'Province not reported',
                    'business_units' => $records->pluck('business_unit')->filter()->unique()->count(),
                    'records' => $records->count(),
                    'total' => $total,
                    'online' => $online,
                    'offline' => $offline,
                    'issues' => $issues,
                    'nvr_attention' => $nvrAttention,
                    'availability' => $total > 0 ? round(($online / $total) * 100, 1) : 0.0,
                ];
            })
            ->sortByDesc('total')
            ->values();

        $regionOverview = $branchOverview
            ->groupBy('region')
            ->map(function ($branches, string $region): array {
                $total = (int) $branches->sum('total');
                $online = (int) $branches->sum('online');

                return [
                    'region' => $region,
                    'branches' => $branches->count(),
                    'total' => $total,
                    'online' => $online,
                    'offline' => (int) $branches->sum('offline'),
                    'issues' => (int) $branches->sum('issues'),
                    'availability' => $total > 0 ? round(($online / $total) * 100, 1) : 0.0,
                ];
            })
            ->sortByDesc('total')
            ->values();

        $attentionBranches = $branchOverview
            ->filter(fn (array $branch): bool => $branch['offline'] > 0
                || $branch['issues'] > 0
                || $branch['nvr_attention'] > 0)
            ->sort(function (array $left, array $right): int {
                return [$right['offline'], $right['issues'], $right['nvr_attention'], $right['total']]
                    <=> [$left['offline'], $left['issues'], $left['nvr_attention'], $left['total']];
            })
            ->values();

        return view('dashboard.index', [
            'sites' => $sites,
            'summary' => $summary,
            'branchOverview' => $branchOverview,
            'regionOverview' => $regionOverview,
            'attentionBranches' => $attentionBranches,
            'regions' => CctvSite::query()->whereNotNull('region')->distinct()->orderBy('region')->pluck('region'),
            'provinces' => CctvSite::query()->whereNotNull('province')->distinct()->orderBy('province')->pluck('province'),
            'businessUnits' => CctvSite::query()->whereNotNull('business_unit')->distinct()->orderBy('business_unit')->pluck('business_unit'),
        ]);
    }

    public function create(): View
    {
        return view('cctv-sites.create', ['cctvSite' => new CctvSite]);
    }

    public function store(Request $request): RedirectResponse
    {
        $site = CctvSite::create($this->validated($request));

        return redirect()->route('cctv-sites.show', $site)
            ->with('success', 'Inventory site added successfully.');
    }

    public function show(CctvSite $cctvSite): View
    {
        return view('cctv-sites.show', compact('cctvSite'));
    }

    public function edit(CctvSite $cctvSite): View
    {
        return view('cctv-sites.edit', compact('cctvSite'));
    }

    public function update(Request $request, CctvSite $cctvSite): RedirectResponse
    {
        $cctvSite->update($this->validated($request));

        return redirect()->route('cctv-sites.show', $cctvSite)
            ->with('success', 'Inventory site updated successfully.');
    }

    public function destroy(CctvSite $cctvSite): RedirectResponse
    {
        $cctvSite->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Inventory site removed from the active inventory.');
    }

    public function export(Request $request, CctvInventoryXlsExporter $exporter): StreamedResponse
    {
        $sites = $this->filteredQuery($request)
            ->orderBy($this->sortColumn($request), $request->input('direction') === 'desc' ? 'desc' : 'asc')
            ->get();
        $filename = 'gateway-cctv-inventory-'.now()->format('Y-m-d').'.xls';

        return response()->streamDownload(
            fn () => $exporter->write($sites, 'php://output'),
            $filename,
            [
                'Content-Type' => 'application/vnd.ms-excel',
                'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
            ]
        );
    }

    public function downloadImportTemplate(): BinaryFileResponse
    {
        $path = base_path('Gateway_CCTV_Monitoring_Template.xlsx');

        abort_unless(is_file($path), 404, 'The CCTV import template is unavailable.');

        return response()->download(
            $path,
            'Gateway_CCTV_Monitoring_Template.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    public function import(Request $request, CctvInventoryWorkbookImporter $importer): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'max:10240', 'extensions:xls,xlsx'],
        ], [
            'import_file.required' => 'Choose a completed CCTV inventory workbook to upload.',
            'import_file.extensions' => 'The inventory workbook must be an .xls or .xlsx file.',
            'import_file.max' => 'The inventory workbook may not be larger than 10 MB.',
        ]);

        $file = $request->file('import_file');

        try {
            $count = $importer->import(
                $file->getRealPath(),
                false,
                $file->getClientOriginalName()
            );
        } catch (InvalidArgumentException|RuntimeException $exception) {
            return redirect()->route('dashboard')
                ->withErrors(['import_file' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('dashboard')
                ->withErrors([
                    'import_file' => 'The workbook could not be imported. Check the template and try again.',
                ]);
        }

        return redirect()->route('dashboard')
            ->with('success', "Imported {$count} CCTV inventory ".str('record')->plural($count).'.');
    }

    private function filteredQuery(Request $request): Builder
    {
        return CctvSite::query()
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->trim().'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('branch', 'like', $term)
                        ->orWhere('region', 'like', $term)
                        ->orWhere('province', 'like', $term)
                        ->orWhere('business_unit', 'like', $term)
                        ->orWhere('assigned_tech', 'like', $term)
                        ->orWhere('vendor', 'like', $term)
                        ->orWhere('nvr_brand', 'like', $term)
                        ->orWhere('nvr_model', 'like', $term)
                        ->orWhere('nvr_rlp', 'like', $term)
                        ->orWhere('nvr_status', 'like', $term)
                        ->orWhere('remarks', 'like', $term)
                        ->orWhere('distribution_summary', 'like', $term);
                });
            })
            ->when($request->filled('region'), fn (Builder $query) => $query->where('region', trim((string) $request->input('region'))))
            ->when($request->filled('province'), fn (Builder $query) => $query->where('province', trim((string) $request->input('province'))))
            ->when($request->filled('business_unit'), fn (Builder $query) => $query->where('business_unit', trim((string) $request->input('business_unit'))))
            ->when($request->filled('health'), function (Builder $query) use ($request): void {
                match ($request->input('health')) {
                    'healthy' => $query->where('total_cameras', '>', 0)
                        ->where('offline_cameras', 0)
                        ->where('recording_issue_cameras', 0)
                        ->where(function (Builder $query): void {
                            $query->whereNull('nvr_status')
                                ->orWhereRaw('LOWER(nvr_status) = ?', ['operational'])
                                ->orWhereRaw('LOWER(nvr_status) LIKE ?', ['%good%']);
                        }),
                    'offline' => $query->where('offline_cameras', '>', 0),
                    'recording' => $query->where('recording_issue_cameras', '>', 0),
                    'nvr' => $query->whereNotNull('nvr_status')
                        ->whereRaw('LOWER(nvr_status) <> ?', ['operational'])
                        ->whereRaw('LOWER(nvr_status) NOT LIKE ?', ['%good%']),
                    default => null,
                };
            });
    }

    private function sortColumn(Request $request): string
    {
        $allowed = ['id', 'branch', 'region', 'total_cameras', 'nvr_hdd_capacity_gb'];

        return in_array($request->input('sort'), $allowed, true)
            ? $request->input('sort')
            : 'branch';
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required', 'string', 'max:150'],
            'region' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'business_unit' => ['nullable', 'string', 'max:120'],
            'assigned_tech' => ['nullable', 'string', 'max:150'],
            'total_cameras' => ['required', 'integer', 'min:0', 'max:65535'],
            'online_cameras' => ['required', 'integer', 'min:0', 'max:65535'],
            'offline_cameras' => ['required', 'integer', 'min:0', 'max:65535'],
            'recording_issue_cameras' => ['required', 'integer', 'min:0', 'max:65535'],
            'nvr_status' => ['nullable', 'string', 'max:100'],
            'storage_status' => ['nullable', 'string', 'max:100'],
            'storage_used_gb' => ['nullable', 'numeric', 'min:0'],
            'recording_days' => ['nullable', 'string', 'max:100'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'nvr_brand' => ['nullable', 'string', 'max:120'],
            'nvr_model' => ['nullable', 'string', 'max:150'],
            'nvr_rlp' => ['nullable', 'string', 'max:150'],
            'nvr_hdd_capacity' => ['nullable', 'string', 'max:100'],
            'nvr_hdd_capacity_gb' => ['nullable', 'numeric', 'min:0'],
            'distribution_status' => ['nullable', 'string', 'max:100'],
            'remarks' => ['nullable', 'string', 'max:5000'],
            'distribution_summary' => ['nullable', 'string', 'max:2000'],
        ]);

        $validator->after(function ($validator) use ($request): void {
            $allocated = $request->integer('online_cameras')
                + $request->integer('offline_cameras');

            if ($request->integer('total_cameras') !== $allocated) {
                $validator->errors()->add(
                    'total_cameras',
                    'Total cameras must equal the online and offline camera counts combined.'
                );
            }

            if ($request->integer('recording_issue_cameras') > $request->integer('total_cameras')) {
                $validator->errors()->add(
                    'recording_issue_cameras',
                    'Recording issue cameras cannot exceed the total camera count.'
                );
            }

            if ($request->filled('storage_used_gb')
                && $request->filled('nvr_hdd_capacity_gb')
                && (float) $request->input('storage_used_gb') > (float) $request->input('nvr_hdd_capacity_gb')) {
                $validator->errors()->add(
                    'storage_used_gb',
                    'Storage used cannot be greater than the NVR HDD capacity.'
                );
            }
        });

        return $validator->validate();
    }
}
