<?php

namespace App\Http\Controllers;

use App\Models\ItAsset;
use App\Services\ItAssetWorkbookImporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ItAssetController extends Controller
{
    public function index(Request $request): View
    {
        $assets = $this->filteredQuery($request)
            ->orderBy(
                $this->sortColumn($request),
                $request->input('direction') === 'desc' ? 'desc' : 'asc'
            )
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        $summaryQuery = $this->filteredQuery($request);
        $summary = [
            'total' => (clone $summaryQuery)->count(),
            'assigned' => (clone $summaryQuery)
                ->where('status', 'like', 'assigned')
                ->count(),
            'stock' => (clone $summaryQuery)
                ->where('status', 'like', 'stock')
                ->count(),
            'attention' => $this->attentionQuery(clone $summaryQuery)->count(),
            'networked' => (clone $summaryQuery)
                ->where(function (Builder $query): void {
                    $query->whereNotNull('ip_address')
                        ->where('ip_address', '<>', '')
                        ->orWhere(function (Builder $query): void {
                            $query->whereNotNull('mac_address')
                                ->where('mac_address', '<>', '');
                        });
                })
                ->count(),
            'branches' => (clone $summaryQuery)
                ->whereNotNull('branch')
                ->where('branch', '<>', '')
                ->distinct()
                ->count('branch'),
        ];

        return view('it-assets.index', [
            'assets' => $assets,
            'summary' => $summary,
            'categories' => $this->filterOptions('category'),
            'statuses' => $this->filterOptions('status'),
            'branches' => $this->filterOptions('branch'),
        ]);
    }

    public function create(): View
    {
        return view('it-assets.create', ['itAsset' => new ItAsset]);
    }

    public function store(Request $request): RedirectResponse
    {
        $asset = ItAsset::create($this->validated($request));

        return redirect()->route('it-assets.show', $asset)
            ->with('success', 'IT asset added successfully.');
    }

    public function show(ItAsset $itAsset): View
    {
        return view('it-assets.show', compact('itAsset'));
    }

    public function edit(ItAsset $itAsset): View
    {
        return view('it-assets.edit', compact('itAsset'));
    }

    public function update(Request $request, ItAsset $itAsset): RedirectResponse
    {
        $itAsset->update($this->validated($request));

        return redirect()->route('it-assets.show', $itAsset)
            ->with('success', 'IT asset updated successfully.');
    }

    public function destroy(ItAsset $itAsset): RedirectResponse
    {
        $itAsset->delete();

        return redirect()->route('it-assets.index')
            ->with('success', 'IT asset removed from the active inventory.');
    }

    public function import(Request $request, ItAssetWorkbookImporter $importer): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'max:10240', 'extensions:xls,xlsx'],
        ], [
            'import_file.required' => 'Choose an IT asset workbook to upload.',
            'import_file.extensions' => 'The IT asset workbook must be an .xls or .xlsx file.',
            'import_file.max' => 'The IT asset workbook may not be larger than 10 MB.',
        ]);

        $file = $request->file('import_file');

        try {
            $count = $importer->import(
                $file->getRealPath(),
                false,
                $file->getClientOriginalName()
            );
        } catch (InvalidArgumentException|RuntimeException $exception) {
            return redirect()->route('it-assets.index')
                ->withErrors(['import_file' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('it-assets.index')
                ->withErrors([
                    'import_file' => 'The workbook could not be imported. Check its columns and try again.',
                ]);
        }

        return redirect()->route('it-assets.index')
            ->with('success', "Imported {$count} IT asset ".str('record')->plural($count).'.');
    }

    private function filteredQuery(Request $request): Builder
    {
        return ItAsset::query()
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->trim().'%';

                $query->where(function (Builder $query) use ($term): void {
                    $query->where('asset_tag', 'like', $term)
                        ->orWhere('asset_name', 'like', $term)
                        ->orWhere('category', 'like', $term)
                        ->orWhere('status', 'like', $term)
                        ->orWhere('condition', 'like', $term)
                        ->orWhere('branch', 'like', $term)
                        ->orWhere('assigned_user', 'like', $term)
                        ->orWhere('department', 'like', $term)
                        ->orWhere('location', 'like', $term)
                        ->orWhere('serial_number', 'like', $term)
                        ->orWhere('brand', 'like', $term)
                        ->orWhere('model', 'like', $term)
                        ->orWhere('ip_address', 'like', $term)
                        ->orWhere('mac_address', 'like', $term)
                        ->orWhere('supplier', 'like', $term)
                        ->orWhere('remarks', 'like', $term);
                });
            })
            ->when(
                $request->filled('category'),
                fn (Builder $query) => $query->where('category', trim((string) $request->input('category')))
            )
            ->when(
                $request->filled('status'),
                fn (Builder $query) => $query->where('status', trim((string) $request->input('status')))
            )
            ->when(
                $request->filled('branch'),
                fn (Builder $query) => $query->where('branch', trim((string) $request->input('branch')))
            )
            ->when($request->filled('state'), function (Builder $query) use ($request): void {
                match ($request->input('state')) {
                    'attention' => $this->attentionQuery($query),
                    'networked' => $query->where(function (Builder $query): void {
                        $query->whereNotNull('ip_address')
                            ->where('ip_address', '<>', '')
                            ->orWhere(function (Builder $query): void {
                                $query->whereNotNull('mac_address')
                                    ->where('mac_address', '<>', '');
                            });
                    }),
                    'unassigned' => $query->where(function (Builder $query): void {
                        $query->whereNull('assigned_user')
                            ->orWhere('assigned_user', '');
                    }),
                    default => null,
                };
            });
    }

    private function attentionQuery(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('status', 'like', '%repair%')
                ->orWhere('condition', 'like', '%damage%')
                ->orWhere('condition', 'like', '%not working%')
                ->orWhere('condition', 'like', '%minor issue%')
                ->orWhere('condition', 'like', '%repair%');
        });
    }

    private function sortColumn(Request $request): string
    {
        $allowed = [
            'id',
            'asset_name',
            'category',
            'status',
            'condition',
            'branch',
            'assigned_user',
            'created_at',
        ];

        return in_array($request->input('sort'), $allowed, true)
            ? $request->input('sort')
            : 'id';
    }

    /** @return Collection<int, string> */
    private function filterOptions(string $column)
    {
        return ItAsset::query()
            ->whereNotNull($column)
            ->where($column, '<>', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = Validator::make($request->all(), [
            'asset_tag' => ['nullable', 'string', 'max:150'],
            'asset_name' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'condition' => ['nullable', 'string', 'max:150'],
            'branch' => ['nullable', 'string', 'max:150'],
            'assigned_user' => ['nullable', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:190'],
            'serial_number' => ['nullable', 'string', 'max:190'],
            'brand' => ['nullable', 'string', 'max:120'],
            'model' => ['nullable', 'string', 'max:190'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'mac_address' => ['nullable', 'string', 'max:50'],
            'purchase_date' => ['nullable', 'string', 'max:50'],
            'warranty_start' => ['nullable', 'string', 'max:50'],
            'warranty_end' => ['nullable', 'string', 'max:50'],
            'supplier' => ['nullable', 'string', 'max:190'],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ])->validate();

        foreach ($data as $attribute => $value) {
            if (is_string($value)) {
                $value = trim($value);
                $data[$attribute] = $value === '' && $attribute !== 'category' ? null : $value;
            }
        }

        return $data;
    }
}
