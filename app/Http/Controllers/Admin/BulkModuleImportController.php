<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliation;
use App\Models\Course;
use App\Models\Institution;
use App\Models\InstitutionCategory;
use App\Models\InstitutionType;
use App\Models\Level;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Support\SimpleXlsxBuilder;
use App\Support\SimpleXlsxReader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class BulkModuleImportController extends Controller
{
    public function index(): View
    {
        return view('admin.modules.bulk-import.index');
    }

    public function store(Request $request, SimpleXlsxReader $reader): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx|max:10240',
        ]);

        try {
            $sheets = $reader->read($request->file('file')->getRealPath());
        } catch (Throwable $exception) {
            return redirect()->route('admin.bulk-import.index')
                ->with('error', 'The uploaded Excel file could not be read. Please use the sample template.');
        }

        $mappedSheets = $this->mapSheets($sheets);
        if (empty($mappedSheets)) {
            $found = array_keys($sheets);
            $foundText = empty($found) ? 'none' : implode(', ', $found);
            return redirect()->route('admin.bulk-import.index')
                ->with('error', 'No supported sheets found. Detected sheets: ' . $foundText . '. Use: Affiliations, Institution Types, Institution Categories, Program Categories, Levels, Institutions, Programs, Courses.');
        }

        $summary = [
            'affiliations' => 0,
            'institution_types' => 0,
            'institution_categories' => 0,
            'program_categories' => 0,
            'levels' => 0,
            'institutions' => 0,
            'programs' => 0,
            'courses' => 0,
        ];
        $skipped = [];

        DB::transaction(function () use ($mappedSheets, &$summary, &$skipped): void {
            $summary['affiliations'] = $this->importAffiliations($mappedSheets['affiliations'] ?? [], $skipped);
            $summary['institution_types'] = $this->importInstitutionTypes($mappedSheets['institution_types'] ?? [], $skipped);
            $summary['institution_categories'] = $this->importInstitutionCategories($mappedSheets['institution_categories'] ?? [], $skipped);
            $summary['program_categories'] = $this->importProgramCategories($mappedSheets['program_categories'] ?? [], $skipped);
            $summary['levels'] = $this->importLevels($mappedSheets['levels'] ?? [], $skipped);
            $summary['institutions'] = $this->importInstitutions($mappedSheets['institutions'] ?? [], $skipped);
            $summary['programs'] = $this->importPrograms($mappedSheets['programs'] ?? [], $skipped);
            $summary['courses'] = $this->importCourses($mappedSheets['courses'] ?? [], $skipped);
        });

        return redirect()->route('admin.bulk-import.index')
            ->with('success', 'Bulk import completed successfully.')
            ->with('import_summary', $summary)
            ->with('import_skipped', $skipped);
    }

    public function template(SimpleXlsxBuilder $builder): BinaryFileResponse
    {
        $sheets = [
            'Affiliations' => [
                ['name', 'code', 'description', 'is_active'],
                ['Tribhuvan University', 'TU', 'Nepal national university', '1'],
                ['Pokhara University', 'PU', 'Autonomous public university', '1'],
            ],
            'Institution Types' => [
                ['name', 'slug'],
                ['College', 'college'],
                ['University', 'university'],
            ],
            'Institution Categories' => [
                ['name', 'slug'],
                ['Engineering', 'engineering'],
                ['Management', 'management'],
            ],
            'Program Categories' => [
                ['name', 'slug'],
                ['IT', 'it'],
                ['Management', 'management'],
            ],
            'Levels' => [
                ['name', 'code', 'description', 'order', 'is_active'],
                ['Bachelor', 'BACH', 'Undergraduate level', '1', '1'],
                ['Master', 'MSTR', 'Graduate level', '2', '1'],
            ],
            'Institutions' => [
                ['name', 'address', 'phone', 'email', 'website', 'established_year', 'type', 'category', 'affiliations', 'is_active'],
                ['Prime College', 'Kathmandu', '01-4440000', 'info@prime.edu', 'https://prime.edu', '2001', 'College', 'Management', 'Tribhuvan University, Pokhara University', '1'],
                ['NCIT', 'Balkumari, Lalitpur', '01-5186358', 'info@ncit.edu', 'https://ncit.edu', '2009', 'College', 'Engineering', 'Pokhara University', '1'],
            ],
            'Programs' => [
                ['name', 'code', 'description', 'level', 'affiliation', 'category', 'fee', 'duration', 'is_active'],
                ['BCA', 'BCA-001', 'Bachelor in Computer Application', 'Bachelor', 'Tribhuvan University', 'IT', '550000', '4 years', '1'],
                ['BBA', 'BBA-001', 'Bachelor in Business Administration', 'Bachelor', 'Tribhuvan University', 'Management', '450000', '4 years', '1'],
            ],
            'Courses' => [
                ['name', 'code', 'description', 'programs', 'is_active'],
                ['Database Management Systems', 'DBMS-101', 'Core database concepts', 'BCA', '1'],
                ['Business Communication', 'BCOM-101', 'Business communication fundamentals', 'BBA', '1'],
            ],
        ];

        $path = $builder->build($sheets);

        return response()->download($path, 'admin-module-bulk-import-sample.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * @param  array<string, array<int, array<string, string>>>  $sheets
     * @return array<string, array<int, array<string, string>>>
     */
    private function mapSheets(array $sheets): array
    {
        $aliases = [
            'affiliations' => ['affiliations', 'affiliation'],
            'institution_types' => ['institution_types', 'institution_type', 'institution types', 'institution type', 'types', 'type'],
            'institution_categories' => ['institution_categories', 'institution_category', 'institution categories', 'institution category', 'categories', 'category'],
            'program_categories' => ['program_categories', 'program_category', 'program categories', 'program category'],
            'levels' => ['levels', 'level'],
            'institutions' => ['institutions', 'institution'],
            'programs' => ['programs', 'program'],
            'courses' => ['courses', 'course'],
        ];

        $mapped = [];
        foreach ($sheets as $sheetName => $rows) {
            $normalized = strtolower(trim($sheetName));
            $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized) ?? '';
            $normalized = trim($normalized, '_');
            $humanReadable = str_replace('_', ' ', $normalized);

            foreach ($aliases as $module => $moduleAliases) {
                if (in_array($normalized, $moduleAliases, true) || in_array($humanReadable, $moduleAliases, true)) {
                    $mapped[$module] = $rows;
                    break;
                }
            }
        }

        return $mapped;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importAffiliations(array $rows, array &$skipped): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Affiliations row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            Affiliation::updateOrCreate(
                ['name' => $name],
                [
                    'code' => $this->nullableString($row['code'] ?? ''),
                    'description' => $this->nullableString($row['description'] ?? ''),
                    'is_active' => $this->parseBoolean($row['is_active'] ?? null, true),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importInstitutionTypes(array $rows, array &$skipped): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Institution Types row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            $slug = $this->nullableString($row['slug'] ?? '') ?? Str::slug($name);

            InstitutionType::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importInstitutionCategories(array $rows, array &$skipped): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Institution Categories row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            $slug = $this->nullableString($row['slug'] ?? '') ?? Str::slug($name);

            InstitutionCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importProgramCategories(array $rows, array &$skipped): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Program Categories row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            $slug = $this->nullableString($row['slug'] ?? '') ?? Str::slug($name);

            ProgramCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importLevels(array $rows, array &$skipped): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Levels row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            Level::updateOrCreate(
                ['name' => $name],
                [
                    'code' => $this->nullableString($row['code'] ?? ''),
                    'description' => $this->nullableString($row['description'] ?? ''),
                    'order' => (int) ($row['order'] ?? 0),
                    'is_active' => $this->parseBoolean($row['is_active'] ?? null, true),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importInstitutions(array $rows, array &$skipped): int
    {
        $count = 0;

        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Institutions row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            $typeValue = trim($row['type'] ?? $row['institution_type'] ?? '');
            $categoryValue = trim($row['category'] ?? $row['institution_category'] ?? '');

            if ($typeValue === '' || $categoryValue === '') {
                $skipped[] = 'Institutions row ' . ($row['__row_number'] ?? '?') . ': type and category are required.';
                continue;
            }

            $institutionType = InstitutionType::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($typeValue)])
                ->orWhereRaw('LOWER(slug) = ?', [strtolower(Str::slug($typeValue))])
                ->first();

            if (!$institutionType) {
                $skipped[] = 'Institutions row ' . ($row['__row_number'] ?? '?') . ': type "' . $typeValue . '" not found.';
                continue;
            }

            $institutionCategory = InstitutionCategory::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($categoryValue)])
                ->orWhereRaw('LOWER(slug) = ?', [strtolower(Str::slug($categoryValue))])
                ->first();

            if (!$institutionCategory) {
                $institutionCategory = InstitutionCategory::create([
                    'name' => $categoryValue,
                    'slug' => Str::slug($categoryValue),
                ]);
            }

            $affiliationIds = null;
            if (array_key_exists('affiliations', $row)) {
                $affiliationNames = $this->parseDelimitedValues($row['affiliations'] ?? '');
                if (empty($affiliationNames)) {
                    $affiliationIds = [];
                } else {
                    $affiliationIds = Affiliation::query()
                        ->whereIn(DB::raw('LOWER(name)'), array_map('strtolower', $affiliationNames))
                        ->orWhereIn(DB::raw('LOWER(code)'), array_map('strtolower', $affiliationNames))
                        ->pluck('id')
                        ->all();

                    if (count($affiliationIds) !== count($affiliationNames)) {
                        $skipped[] = 'Institutions row ' . ($row['__row_number'] ?? '?') . ': one or more affiliations not found.';
                        continue;
                    }
                }
            }

            $institution = Institution::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'type' => $institutionType->slug,
                    'address' => $this->nullableString($row['address'] ?? ''),
                    'phone' => $this->nullableString($row['phone'] ?? ''),
                    'email' => $this->nullableString($row['email'] ?? ''),
                    'website' => $this->nullableString($row['website'] ?? ''),
                    'established_year' => $this->nullableInteger($row['established_year'] ?? ''),
                    'status' => $this->parseBoolean($row['is_active'] ?? null, true) ? 'active' : 'inactive',
                ]
            );

            if (is_array($affiliationIds)) {
                $institution->affiliations()->sync($affiliationIds);
            }

            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importPrograms(array $rows, array &$skipped): int
    {
        $count = 0;

        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                $skipped[] = 'Programs row ' . ($row['__row_number'] ?? '?') . ': name is required.';
                continue;
            }

            $levelValue = trim($row['level'] ?? $row['level_name'] ?? '');
            $categoryValue = trim($row['category'] ?? $row['program_category'] ?? '');
            $feeValue = trim((string) ($row['fee'] ?? ''));

            if ($levelValue === '' || $categoryValue === '' || $feeValue === '') {
                $skipped[] = 'Programs row ' . ($row['__row_number'] ?? '?') . ': level, category and fee are required.';
                continue;
            }

            $level = Level::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($levelValue)])
                ->orWhereRaw('LOWER(slug) = ?', [strtolower(Str::slug($levelValue))])
                ->orWhereRaw('LOWER(code) = ?', [strtolower($levelValue)])
                ->first();

            if (!$level) {
                $skipped[] = 'Programs row ' . ($row['__row_number'] ?? '?') . ': level "' . $levelValue . '" not found.';
                continue;
            }

            $category = ProgramCategory::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($categoryValue)])
                ->orWhereRaw('LOWER(slug) = ?', [strtolower(Str::slug($categoryValue))])
                ->first();

            if (!$category) {
                $category = ProgramCategory::create([
                    'name' => $categoryValue,
                    'slug' => Str::slug($categoryValue),
                ]);
            }

            $affiliationId = null;
            $affiliationValue = trim($row['affiliation'] ?? $row['affiliation_name'] ?? '');
            if ($affiliationValue !== '') {
                $affiliation = Affiliation::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($affiliationValue)])
                    ->orWhereRaw('LOWER(code) = ?', [strtolower($affiliationValue)])
                    ->orWhereRaw('LOWER(slug) = ?', [strtolower(Str::slug($affiliationValue))])
                    ->first();

                if (!$affiliation) {
                    $skipped[] = 'Programs row ' . ($row['__row_number'] ?? '?') . ': affiliation "' . $affiliationValue . '" not found.';
                    continue;
                }
                $affiliationId = $affiliation->id;
            }

            Program::updateOrCreate(
                ['name' => $name],
                [
                    'code' => $this->nullableString($row['code'] ?? ''),
                    'description' => $this->nullableString($row['description'] ?? ''),
                    'level_id' => $level->id,
                    'affiliation_id' => $affiliationId,
                    'category_id' => $category->id,
                    'fee' => (float) $feeValue,
                    'duration' => $this->nullableString($row['duration'] ?? ''),
                    'is_active' => $this->parseBoolean($row['is_active'] ?? null, true),
                ]
            );

            $count++;
        }

        return $count;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @param  array<int, string>  $skipped
     */
    private function importCourses(array $rows, array &$skipped): int
    {
        $count = 0;

        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            $code = trim($row['code'] ?? '');

            if ($name === '' || $code === '') {
                $skipped[] = 'Courses row ' . ($row['__row_number'] ?? '?') . ': name and code are required.';
                continue;
            }

            $programValues = $this->parseDelimitedValues($row['programs'] ?? '');
            if (empty($programValues)) {
                $skipped[] = 'Courses row ' . ($row['__row_number'] ?? '?') . ': programs is required.';
                continue;
            }

            $programIds = Program::query()
                ->whereIn(DB::raw('LOWER(name)'), array_map('strtolower', $programValues))
                ->orWhereIn(DB::raw('LOWER(code)'), array_map('strtolower', $programValues))
                ->orWhereIn(DB::raw('LOWER(slug)'), array_map(static fn ($value) => strtolower(Str::slug($value)), $programValues))
                ->pluck('id')
                ->all();

            if (count(array_unique($programIds)) !== count($programValues)) {
                $skipped[] = 'Courses row ' . ($row['__row_number'] ?? '?') . ': one or more programs not found.';
                continue;
            }

            $course = Course::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'description' => $this->nullableString($row['description'] ?? ''),
                    'is_active' => $this->parseBoolean($row['is_active'] ?? null, true),
                ]
            );

            $course->programs()->sync($programIds);
            $count++;
        }

        return $count;
    }

    private function parseBoolean(?string $value, bool $default): bool
    {
        $value = strtolower(trim((string) $value));
        if ($value === '') {
            return $default;
        }

        return in_array($value, ['1', 'true', 'yes', 'y', 'on'], true);
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function nullableInteger(?string $value): ?int
    {
        $value = trim((string) $value);
        return $value === '' ? null : (int) $value;
    }

    /**
     * @return array<int, string>
     */
    private function parseDelimitedValues(?string $value): array
    {
        $parts = preg_split('/[,;|]+/', (string) $value) ?: [];
        $parts = array_map(static fn ($item) => trim((string) $item), $parts);
        $parts = array_values(array_filter($parts, static fn ($item) => $item !== ''));

        return array_values(array_unique($parts));
    }
}
