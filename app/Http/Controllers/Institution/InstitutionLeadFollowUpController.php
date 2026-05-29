<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Inquiry;
use App\Models\LeadFollowUp;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionLeadFollowUpController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = LeadFollowUp::class;
        $this->routeBase = 'lead-follow-ups';
        $this->title = 'Follow Up';
        $this->relationships = ['inquiry', 'assignedTo'];
        $this->selectOptions = ['status' => LeadFollowUp::STATUSES];
        $this->fields = [
            'inquiry_id' => ['label' => 'Inquiry', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:inquiries,id']],
            'assigned_to' => ['label' => 'Assigned To', 'type' => 'select', 'rules' => ['nullable', 'integer', 'exists:users,id']],
            'follow_up_at' => ['label' => 'Follow Up At', 'type' => 'datetime-local', 'rules' => ['required', 'date']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }

    protected function resourceQuery(): Builder
    {
        return LeadFollowUp::query()->whereHas('inquiry', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(Inquiry::where('institution_id', $this->activeInstitutionId())->whereKey($data['inquiry_id'] ?? null)->exists(), 403);
        if (! empty($data['assigned_to'])) {
            abort_unless(User::whereKey($data['assigned_to'])->whereHas('activeInstitutions', fn ($query) => $query->where('institutions.id', $this->activeInstitutionId()))->exists(), 403);
        }

        return $data;
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'inquiry_id' => Inquiry::where('institution_id', $this->activeInstitutionId())->latest()->pluck('name', 'id')->all(),
            'assigned_to' => $this->assignedInstitutionUsers()->pluck('name', 'id')->all(),
        ]);
    }
}
