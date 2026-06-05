<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionProgram;
use App\Models\InstitutionProgramSubject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionProgramSubjectController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionProgramSubject::class;
        $this->routeBase = 'program-subjects';
        $this->title = 'Program Subject';
        $this->relationships = ['institutionProgram'];
        $this->fields = [
            'institution_program_id' => ['label' => 'Institution Program', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:institution_programs,id']],
            'subject_name' => ['label' => 'Subject Name', 'rules' => ['required', 'string', 'max:255']],
            'is_optional' => ['label' => 'Optional', 'type' => 'checkbox', 'rules' => ['nullable']],
        ];
    }

    private const PROGRAM_TYPES = ['college', 'university', 'school'];

    protected function beforeAction(): void
    {
        abort_unless(
            in_array($this->activeInstitution()->type, self::PROGRAM_TYPES, true),
            403,
            'Only colleges, universities, and schools can manage program subjects.'
        );
    }

    protected function resourceQuery(): Builder
    {
        return InstitutionProgramSubject::query()
            ->whereHas('institutionProgram', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(
            InstitutionProgram::where('institution_id', $this->activeInstitutionId())->whereKey($data['institution_program_id'] ?? null)->exists(),
            403
        );

        return $data;
    }

    protected function selectOptions(): array
    {
        return [
            'institution_program_id' => InstitutionProgram::with('program')
                ->where('institution_id', $this->activeInstitutionId())
                ->orderBy('title')
                ->get()
                ->mapWithKeys(fn (InstitutionProgram $program) => [$program->id => $program->display_name])
                ->all(),
        ];
    }
}
