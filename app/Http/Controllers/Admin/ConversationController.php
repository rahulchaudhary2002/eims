<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConversationRequest;
use App\Http\Requests\Admin\UpdateConversationRequest;
use App\Models\Conversation;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Conversation::with(['student', 'institution'])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', \App\Models\Student::class)]);
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $conversations = $query->latest()->paginate(20)->withQueryString();
        $institutions  = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students      = Student::orderBy('name')->get(['id', 'name']);
        $types         = Conversation::TYPES;

        return view('admin.modules.conversations.index', compact('conversations', 'institutions', 'students', 'types'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students     = Student::orderBy('name')->get(['id', 'name']);
        $types        = Conversation::TYPES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedStudentId     = $request->input('student_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.conversations.create', compact(
            'institutions', 'students', 'types', 'selectedInstitutionId', 'selectedStudentId'
        ));
    }

    public function store(StoreConversationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $conversation = Conversation::create($data);

        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Conversation created successfully.');
    }

    public function show(Conversation $conversation): View
    {
        $this->authorizeConversationAccess($conversation);
        $conversation->load(['student', 'institution', 'messages' => fn ($q) => $q->with('sender')->oldest()]);

        // Mark student messages as read when admin views the conversation
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_type', \App\Models\Student::class)
            ->update(['read_at' => now()]);

        $query = Conversation::with(['student', 'institution', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', \App\Models\Student::class)])
            ->latest();
        $this->applyInstitutionScope($query);
        $conversations = $query->limit(50)->get();

        return view('admin.modules.conversations.show', compact('conversation', 'conversations'));
    }

    public function edit(Conversation $conversation): View
    {
        $this->authorizeConversationAccess($conversation);
        $conversation->load(['student', 'institution']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students     = Student::orderBy('name')->get(['id', 'name']);
        $types        = Conversation::TYPES;

        return view('admin.modules.conversations.edit', compact('conversation', 'institutions', 'students', 'types'));
    }

    public function update(UpdateConversationRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversationAccess($conversation);
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $conversation->update($data);

        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Conversation updated successfully.');
    }

    public function destroy(Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversationAccess($conversation);
        $conversation->delete();

        return redirect()->route('admin.conversations.index')
            ->with('success', 'Conversation deleted successfully.');
    }

    private function institutionDropdownQuery(): Builder
    {
        $query = Institution::orderBy('name');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('id', $scope)
                    ->whereHas('users', fn (Builder $q) => $q->where('users.id', auth('web')->id())->wherePivot('is_active', true));
            }
        }

        return $query;
    }

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }
    }

    private function authorizeConversationAccess(Conversation $conversation): void
    {
        if ($conversation->institution_id) {
            $this->authorizeInstitution((int) $conversation->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this conversation.');
    }

    private function authorizeInstitution(int $institutionId): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this institution.'
        );
    }

    private function currentInstitutionIsAssigned(): bool
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
