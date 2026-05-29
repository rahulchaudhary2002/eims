<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Inquiry;
use App\Models\LeadNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionLeadNoteController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = LeadNote::class;
        $this->routeBase = 'lead-notes';
        $this->title = 'Lead Note';
        $this->relationships = ['inquiry', 'user'];
        $this->readOnlyFields = ['user_id'];
        $this->fields = [
            'inquiry_id' => ['label' => 'Inquiry', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:inquiries,id']],
            'note' => ['label' => 'Note', 'type' => 'textarea', 'rules' => ['required', 'string']],
        ];
    }

    protected function resourceQuery(): Builder
    {
        return LeadNote::query()->whereHas('inquiry', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(Inquiry::where('institution_id', $this->activeInstitutionId())->whereKey($data['inquiry_id'] ?? null)->exists(), 403);
        $data['user_id'] = auth('web')->id();

        return $data;
    }

    protected function selectOptions(): array
    {
        return ['inquiry_id' => Inquiry::where('institution_id', $this->activeInstitutionId())->latest()->pluck('name', 'id')->all()];
    }
}
