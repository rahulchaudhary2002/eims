<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Inquiry;
use App\Models\Application;
use App\Models\ConsultancyService;
use App\Models\InstitutionCertification;
use App\Models\InstitutionCourse;
use App\Models\InstitutionProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InstitutionInquiryController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Inquiry::class;
        $this->routeBase = 'inquiries';
        $this->title = 'Inquiry';
        $this->relationships = ['applicable', 'assignedTo'];
        $this->selectOptions = [
            'source'          => Inquiry::SOURCES,
            'status'          => Inquiry::STATUSES,
            'applicable_type' => Application::APPLICABLE_TYPES,
        ];
        $this->fields = [
            'applicable_type' => ['label' => 'Type', 'type' => 'select', 'rules' => ['nullable', 'string']],
            'applicable_id'   => ['label' => 'Item', 'type' => 'select', 'rules' => ['nullable', 'integer', 'min:1']],
            'name' => ['label' => 'Name', 'rules' => ['required', 'string', 'max:255']],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => ['required', 'email', 'max:255']],
            'phone' => ['label' => 'Phone', 'rules' => ['nullable', 'string', 'max:50']],
            'message' => ['label' => 'Message', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
            'source' => ['label' => 'Source', 'type' => 'select', 'rules' => ['nullable', 'string']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'assigned_to' => ['label' => 'Assigned To', 'type' => 'select', 'rules' => ['nullable', 'integer', 'exists:users,id']],
            'last_contacted_at' => ['label' => 'Last Contacted At', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ];
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        if (! empty($data['applicable_type']) && ! empty($data['applicable_id'])) {
            $type = $data['applicable_type'];
            if (array_key_exists($type, Application::APPLICABLE_TYPES)) {
                abort_unless(
                    $type::where('institution_id', $this->activeInstitutionId())->whereKey($data['applicable_id'])->exists(),
                    403
                );
            }
        }

        if (! empty($data['assigned_to'])) {
            abort_unless(User::whereKey($data['assigned_to'])->whereHas('activeInstitutions', fn ($query) => $query->where('institutions.id', $this->activeInstitutionId()))->exists(), 403);
        }

        return parent::forceInstitutionScope($data, $record);
    }

    protected function selectOptions(): array
    {
        $institutionId = $this->activeInstitutionId();

        $applicableItems = collect();
        foreach (Application::APPLICABLE_TYPES as $typeClass => $typeLabel) {
            $typeClass::where('institution_id', $institutionId)->orderBy('title')->get()
                ->each(fn ($item) => $applicableItems->put($item->id, $typeLabel . ': ' . ($item->display_name ?? $item->title)));
        }

        return array_merge($this->selectOptions, [
            'applicable_id' => $applicableItems->all(),
            'assigned_to'   => $this->assignedInstitutionUsers()->pluck('name', 'id')->all(),
        ]);
    }
}
