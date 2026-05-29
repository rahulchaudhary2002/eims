<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\UsesActiveInstitution;
use App\Models\Admission;
use App\Models\Application;
use App\Models\CommissionInvoice;
use App\Models\CounselingSession;
use App\Models\Inquiry;
use App\Models\InstitutionProgram;
use App\Models\InstitutionReview;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Scholarship;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InstitutionDashboardController extends Controller
{
    use UsesActiveInstitution;

    public function index(): View
    {
        $institutionId = $this->activeInstitutionId();

        $cards = [
            'Total programs' => InstitutionProgram::where('institution_id', $institutionId)->count(),
            'Active programs' => InstitutionProgram::where('institution_id', $institutionId)->where('status', 'open')->count(),
            'Total applications' => Application::where('institution_id', $institutionId)->count(),
            'Pending applications' => Application::where('institution_id', $institutionId)->whereIn('status', ['submitted', 'under_review'])->count(),
            'Approved applications' => Application::where('institution_id', $institutionId)->whereIn('status', ['referred', 'admitted'])->count(),
            'Admissions' => Admission::where('institution_id', $institutionId)->count(),
            'Total inquiries' => Inquiry::where('institution_id', $institutionId)->count(),
            'Pending inquiries' => Inquiry::where('institution_id', $institutionId)->whereIn('status', ['new', 'contacted', 'qualified'])->count(),
            'Counseling sessions' => CounselingSession::where('institution_id', $institutionId)->count(),
            'Published posts' => Post::where('institution_id', $institutionId)->where('is_published', true)->count(),
            'Reviews' => InstitutionReview::where('institution_id', $institutionId)->count(),
            'Average rating' => round((float) InstitutionReview::where('institution_id', $institutionId)->avg('rating'), 1),
            'Active scholarships' => Scholarship::where('institution_id', $institutionId)->where('status', 'active')->count(),
            'Active promotions' => Promotion::where('institution_id', $institutionId)->where('status', 'active')->count(),
            'Commission invoices' => CommissionInvoice::where('institution_id', $institutionId)->count(),
            'Paid commission amount' => (float) CommissionInvoice::where('institution_id', $institutionId)->where('status', 'paid')->sum('commission_amount'),
            'Pending commission amount' => (float) CommissionInvoice::where('institution_id', $institutionId)->whereIn('status', ['draft', 'issued', 'overdue'])->sum('commission_amount'),
        ];

        $charts = [
            'Applications by status' => $this->groupCount(Application::class, 'status'),
            'Applications by month' => $this->monthCount(Application::class),
            'Inquiries by status' => $this->groupCount(Inquiry::class, 'status'),
            'Admissions by month' => $this->monthCount(Admission::class),
            'Program-wise applications' => Application::query()
                ->selectRaw('institution_program_id, count(*) as total')
                ->where('institution_id', $institutionId)
                ->groupBy('institution_program_id')
                ->with('institutionProgram:id,title')
                ->get()
                ->mapWithKeys(fn ($row) => [($row->institutionProgram?->title ?? 'Program #' . $row->institution_program_id) => $row->total])
                ->all(),
            'Scholarship usage' => Scholarship::where('institution_id', $institutionId)->pluck('used_slots', 'title')->all(),
            'Review rating distribution' => $this->groupCount(InstitutionReview::class, 'rating'),
            'Commission invoice status' => $this->groupCount(CommissionInvoice::class, 'status'),
        ];

        return view('institution.modules.dashboard.index', [
            'activeInstitution' => $this->activeInstitution(),
            'cards' => $cards,
            'charts' => $charts,
        ]);
    }

    private function groupCount(string $model, string $column): array
    {
        return $model::query()
            ->select($column, DB::raw('count(*) as total'))
            ->where('institution_id', $this->activeInstitutionId())
            ->groupBy($column)
            ->pluck('total', $column)
            ->all();
    }

    private function monthCount(string $model): array
    {
        return $model::query()
            ->where('institution_id', $this->activeInstitutionId())
            ->latest()
            ->get(['created_at'])
            ->groupBy(fn ($row) => optional($row->created_at)->format('Y-m') ?: 'Unknown')
            ->map->count()
            ->all();
    }
}
