<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\CommissionInvoice;
use App\Models\CommissionPayment;
use App\Models\ConsultancyDestination;
use App\Models\ConsultancyService;
use App\Models\Conversation;
use App\Models\CounselingSession;
use App\Models\Faculty;
use App\Models\Inquiry;
use App\Models\Institution;
use App\Models\InstitutionDocument;
use App\Models\InstitutionFollower;
use App\Models\InstitutionProfile;
use App\Models\InstitutionProgram;
use App\Models\InstitutionProgramSubject;
use App\Models\InstitutionReview;
use App\Models\InstitutionSubscription;
use App\Models\LeadFollowUp;
use App\Models\LeadNote;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostMedia;
use App\Models\PostReaction;
use App\Models\Program;
use App\Models\Promotion;
use App\Models\Referral;
use App\Models\ReferralAgreement;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipCashback;
use App\Models\Student;
use App\Models\StudentAcademicRecord;
use App\Models\StudentCompareItem;
use App\Models\StudentDocument;
use App\Models\StudentFavoriteInstitution;
use App\Models\StudentProfile;
use App\Models\StudentRecommendation;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserInstitution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. ADMIN USER ──────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@eims.test'], [
            'name'              => 'Admin User',
            'password'          => Hash::make('password'),
            'is_super_admin'    => true,
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // ── 2. INSTITUTION STAFF USERS ─────────────────────────────────
        $staffUsers = [];
        $staffData = [
            ['name' => 'Ramesh Sharma',   'email' => 'ramesh@eims.test'],
            ['name' => 'Sita Thapa',      'email' => 'sita@eims.test'],
            ['name' => 'Bikash Karki',    'email' => 'bikash@eims.test'],
            ['name' => 'Anita Shrestha',  'email' => 'anita@eims.test'],
        ];
        foreach ($staffData as $d) {
            $staffUsers[] = User::firstOrCreate(['email' => $d['email']], [
                'name'              => $d['name'],
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        }

        // ── 3. FACULTIES ───────────────────────────────────────────────
        $faculties = [];
        $facultyNames = [
            'Management',
            'Science & Technology',
            'Humanities & Social Science',
            'Education',
            'Medical & Health Sciences',
            'Engineering',
            'Law',
            'Agriculture',
        ];
        foreach ($facultyNames as $name) {
            $faculties[] = Faculty::firstOrCreate(['slug' => Str::slug($name)], [
                'name'      => $name,
                'is_active' => true,
            ]);
        }

        // ── 4. PROGRAMS ────────────────────────────────────────────────
        $programsData = [
            ['Bachelor of Business Administration (BBA)', 'bachelor', 'Management'],
            ['Bachelor of Business Studies (BBS)',        'bachelor', 'Management'],
            ['Master of Business Administration (MBA)',   'master',   'Management'],
            ['Bachelor of Science (BSc CSIT)',            'bachelor', 'Science & Technology'],
            ['Bachelor of Information Technology (BIT)',  'bachelor', 'Science & Technology'],
            ['Bachelor of Engineering (BE Computer)',     'bachelor', 'Engineering'],
            ['Bachelor of Civil Engineering (BCE)',       'bachelor', 'Engineering'],
            ['Bachelor of Medicine (MBBS)',               'bachelor', 'Medical & Health Sciences'],
            ['Bachelor of Nursing (BN)',                  'bachelor', 'Medical & Health Sciences'],
            ['Bachelor of Education (BEd)',               'bachelor', 'Education'],
            ['Bachelor of Arts (BA)',                     'bachelor', 'Humanities & Social Science'],
            ['Bachelor of Laws (LLB)',                    'bachelor', 'Law'],
        ];
        $programs = [];
        $facultyMap = collect($faculties)->keyBy('name');
        foreach ($programsData as [$name, $level, $facultyName]) {
            $programs[$name] = Program::firstOrCreate(['slug' => Str::slug($name)], [
                'name'       => $name,
                'level'      => $level,
                'faculty_id' => $facultyMap[$facultyName]?->id,
                'is_active'  => true,
            ]);
        }

        // ── 5. INSTITUTIONS ────────────────────────────────────────────
        $institutionsData = [
            [
                'type'              => 'university',
                'name'              => 'Tribhuvan University',
                'slug'              => 'tribhuvan-university',
                'code'              => 'TU001',
                'email'             => 'info@tu.edu.np',
                'phone'             => '01-4331717',
                'website'           => 'https://tribhuvan-university.edu.np',
                'short_description' => 'Nepal\'s oldest and largest university established in 1959.',
                'established_year'  => 1959,
                'province'          => 'Bagmati',
                'district'          => 'Kathmandu',
                'city'              => 'Kirtipur',
                'status'            => 'active',
                'is_verified'       => true,
                'is_featured'       => true,
            ],
            [
                'type'              => 'college',
                'name'              => 'Ace Institute of Management',
                'slug'              => 'ace-institute-of-management',
                'code'              => 'AIM001',
                'email'             => 'info@ace.edu.np',
                'phone'             => '01-4441234',
                'website'           => 'https://ace.edu.np',
                'short_description' => 'Premier management college in Kathmandu Valley.',
                'established_year'  => 1998,
                'province'          => 'Bagmati',
                'district'          => 'Kathmandu',
                'city'              => 'Kathmandu',
                'status'            => 'active',
                'is_verified'       => true,
                'is_featured'       => true,
            ],
            [
                'type'              => 'college',
                'name'              => 'Pokhara University College of Science',
                'slug'              => 'pu-college-of-science',
                'code'              => 'PUC001',
                'email'             => 'info@pucs.edu.np',
                'phone'             => '061-523456',
                'website'           => 'https://pucs.edu.np',
                'short_description' => 'Leading science and technology college in Gandaki Province.',
                'established_year'  => 2002,
                'province'          => 'Gandaki',
                'district'          => 'Kaski',
                'city'              => 'Pokhara',
                'status'            => 'active',
                'is_verified'       => true,
                'is_featured'       => false,
            ],
            [
                'type'              => 'consultancy',
                'name'              => 'EduPath Consultancy',
                'slug'              => 'edupath-consultancy',
                'code'              => 'EPC001',
                'email'             => 'info@edupath.com.np',
                'phone'             => '01-4567890',
                'short_description' => 'Expert education consultancy for abroad and local admissions.',
                'established_year'  => 2010,
                'province'          => 'Bagmati',
                'district'          => 'Kathmandu',
                'city'              => 'Thamel',
                'status'            => 'active',
                'is_verified'       => true,
                'is_featured'       => false,
            ],
            [
                'type'              => 'institute',
                'name'              => 'National Medical College',
                'slug'              => 'national-medical-college',
                'code'              => 'NMC001',
                'email'             => 'info@nmc.edu.np',
                'phone'             => '051-523000',
                'short_description' => 'Renowned medical college affiliated with BP Koirala Institute.',
                'established_year'  => 1995,
                'province'          => 'Madhesh',
                'district'          => 'Parsa',
                'city'              => 'Birgunj',
                'status'            => 'active',
                'is_verified'       => true,
                'is_featured'       => true,
            ],
        ];

        $institutions = [];
        foreach ($institutionsData as $d) {
            $institutions[] = Institution::firstOrCreate(['slug' => $d['slug']], array_merge($d, [
                'verified_at' => now()->subMonths(2),
            ]));
        }

        // ── 6. INSTITUTION PROFILES ────────────────────────────────────
        foreach ($institutions as $i => $inst) {
            InstitutionProfile::firstOrCreate(['institution_id' => $inst->id], [
                'facilities'        => ['Library', 'Computer Lab', 'Cafeteria', 'Parking'],
                'achievements'      => ['Best Institution Award 2022', 'ISO 9001 Certified'],
                'has_hostel'        => $i % 2 === 0,
                'has_library'       => true,
                'has_lab'           => true,
                'has_cafeteria'     => $i % 3 !== 0,
                'has_scholarship'   => true,
                'has_sports'        => $i % 2 === 0,
                'facebook_url'      => 'https://facebook.com/' . Str::replace('-', '', $inst->slug),
            ]);
        }

        // ── 7. INSTITUTION DOCUMENTS ───────────────────────────────────
        foreach ($institutions as $inst) {
            InstitutionDocument::firstOrCreate(
                ['institution_id' => $inst->id, 'document_type' => 'registration'],
                [
                    'title'     => 'Registration Certificate',
                    'file_path' => 'institution-docs/sample-registration.pdf',
                    'status'    => 'active',
                ]
            );
        }

        // ── 8. USER–INSTITUTION LINKS ──────────────────────────────────
        $roles = ['owner', 'admin', 'admission_officer', 'counselor'];
        foreach ($institutions as $i => $inst) {
            $user = $staffUsers[$i % count($staffUsers)];
            UserInstitution::firstOrCreate(
                ['user_id' => $user->id, 'institution_id' => $inst->id],
                [
                    'role'       => $roles[$i % count($roles)],
                    'is_primary' => true,
                    'is_active'  => true,
                    'joined_at'  => now()->subYear(),
                ]
            );
        }

        // ── 9. INSTITUTION PROGRAMS ────────────────────────────────────
        $ipMap  = [];
        $ipData = [
            // [institution_slug, program_name, status, total_fee, seats]
            ['tribhuvan-university',       'Bachelor of Business Administration (BBA)', 'open',     120000, 80],
            ['tribhuvan-university',       'Bachelor of Science (BSc CSIT)',            'open',     140000, 60],
            ['tribhuvan-university',       'Bachelor of Laws (LLB)',                    'open',     100000, 50],
            ['ace-institute-of-management','Bachelor of Business Administration (BBA)', 'open',     180000, 60],
            ['ace-institute-of-management','Master of Business Administration (MBA)',   'open',     250000, 40],
            ['pu-college-of-science',      'Bachelor of Science (BSc CSIT)',            'open',     155000, 50],
            ['pu-college-of-science',      'Bachelor of Engineering (BE Computer)',     'open',     200000, 40],
            ['national-medical-college',   'Bachelor of Medicine (MBBS)',               'open',     850000, 30],
            ['national-medical-college',   'Bachelor of Nursing (BN)',                  'open',     280000, 40],
            ['edupath-consultancy',        'Bachelor of Business Studies (BBS)',        'open',      90000, 100],
        ];

        $instMap = collect($institutions)->keyBy('slug');
        foreach ($ipData as [$slug, $progName, $status, $fee, $seats]) {
            $inst = $instMap[$slug] ?? null;
            $prog = $programs[$progName] ?? null;
            if (! $inst || ! $prog) continue;

            $title = $inst->name . ' - ' . $prog->name;
            $ipSlug = Str::slug($inst->slug . '-' . $prog->slug);
            $ip = InstitutionProgram::firstOrCreate(
                ['institution_id' => $inst->id, 'program_id' => $prog->id],
                [
                    'title'               => $title,
                    'slug'                => $ipSlug,
                    'total_fee'           => $fee,
                    'admission_fee'       => 5000,
                    'semester_fee'        => $fee / 8,
                    'duration_months'     => 48,
                    'total_seats'         => $seats,
                    'available_seats'     => (int) ($seats * 0.6),
                    'minimum_gpa'         => 2.0,
                    'minimum_percentage'  => 50.0,
                    'admission_start_date'=> now()->subMonth()->toDateString(),
                    'admission_end_date'  => now()->addMonths(3)->toDateString(),
                    'status'              => $status,
                ]
            );
            $ipMap[$inst->id . '_' . $prog->id] = $ip;
        }

        // ── 10. INSTITUTION PROGRAM SUBJECTS ──────────────────────────
        $subjectSets = [
            'Bachelor of Business Administration (BBA)' => ['Business Mathematics', 'Microeconomics', 'Financial Accounting', 'Business Communication', 'Marketing Principles'],
            'Bachelor of Science (BSc CSIT)'            => ['C Programming', 'Discrete Mathematics', 'Digital Logic', 'Data Structures', 'Database Management'],
            'Bachelor of Medicine (MBBS)'               => ['Anatomy', 'Physiology', 'Biochemistry', 'Pathology', 'Pharmacology'],
        ];
        foreach ($ipMap as $ip) {
            $progName = $ip->program?->name ?? '';
            $subjects = $subjectSets[$progName] ?? ['Core Subject I', 'Core Subject II', 'Elective I'];
            foreach ($subjects as $subject) {
                InstitutionProgramSubject::firstOrCreate(
                    ['institution_program_id' => $ip->id, 'subject_name' => $subject],
                    ['is_optional' => false]
                );
            }
        }

        // ── 11. STUDENTS ───────────────────────────────────────────────
        $studentsData = [
            ['name' => 'Aarav Adhikari',  'email' => 'aarav@student.test',  'gender' => 'male',   'dob' => '2001-03-15'],
            ['name' => 'Priya Tamang',    'email' => 'priya@student.test',  'gender' => 'female', 'dob' => '2002-07-22'],
            ['name' => 'Roshan Paudel',   'email' => 'roshan@student.test', 'gender' => 'male',   'dob' => '2000-11-08'],
            ['name' => 'Nisha Gurung',    'email' => 'nisha@student.test',  'gender' => 'female', 'dob' => '2001-05-30'],
            ['name' => 'Dipesh Rai',      'email' => 'dipesh@student.test', 'gender' => 'male',   'dob' => '2003-01-14'],
            ['name' => 'Sanjana Koirala', 'email' => 'sanjana@student.test','gender' => 'female', 'dob' => '2002-09-18'],
        ];
        $students = [];
        foreach ($studentsData as $d) {
            $students[] = Student::firstOrCreate(['email' => $d['email']], [
                'name'              => $d['name'],
                'password'          => Hash::make('password'),
                'gender'            => $d['gender'],
                'date_of_birth'     => $d['dob'],
                'phone'             => '98' . rand(10000000, 99999999),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        }

        // ── 12. STUDENT PROFILES ───────────────────────────────────────
        $provinces = ['Bagmati', 'Gandaki', 'Lumbini', 'Koshi', 'Madhesh'];
        $districts  = ['Kathmandu', 'Kaski', 'Rupandehi', 'Morang', 'Parsa', 'Chitwan'];
        foreach ($students as $i => $st) {
            StudentProfile::firstOrCreate(['student_id' => $st->id], [
                'province'           => $provinces[$i % count($provinces)],
                'district'           => $districts[$i % count($districts)],
                'city'               => 'City ' . ($i + 1),
                'guardian_name'      => 'Guardian of ' . $st->name,
                'guardian_phone'     => '97' . rand(10000000, 99999999),
                'budget_min'         => 50000,
                'budget_max'         => 300000,
                'career_interests'   => ['Technology', 'Business'],
                'preferred_faculties'=> ['Management', 'Science & Technology'],
            ]);
        }

        // ── 13. STUDENT ACADEMIC RECORDS ──────────────────────────────
        $levels = ['slc_see', 'plus2'];
        foreach ($students as $i => $st) {
            foreach ($levels as $j => $level) {
                StudentAcademicRecord::firstOrCreate(
                    ['student_id' => $st->id, 'level' => $level],
                    [
                        'institution_name' => $level === 'slc_see' ? 'Janata Secondary School' : 'Galaxy Higher Secondary School',
                        'board'            => $level === 'slc_see' ? 'neb' : 'neb',
                        'passed_year'      => $level === 'slc_see' ? 2017 + $i : 2019 + $i,
                        'gpa'              => round(2.5 + ($i * 0.3), 2),
                        'percentage'       => round(65 + ($i * 3.5), 2),
                        'is_verified'      => $j === 0,
                    ]
                );
            }
        }

        // ── 14. STUDENT DOCUMENTS ──────────────────────────────────────
        foreach ($students as $st) {
            foreach (['citizenship', 'pp_photo'] as $docType) {
                StudentDocument::firstOrCreate(
                    ['student_id' => $st->id, 'document_type' => $docType],
                    [
                        'title'     => ucfirst(str_replace('_', ' ', $docType)),
                        'file_path' => 'student-docs/sample-' . $docType . '.pdf',
                        'status'    => 'active',
                    ]
                );
            }
        }

        // ── 15. SUBSCRIPTION PLANS ─────────────────────────────────────
        $plans = [];
        $plansData = [
            ['Basic',       'basic',       2999,  29990,  ['Up to 3 programs', 'Standard listing', 'Email support']],
            ['Professional','professional', 7999,  79990,  ['Up to 10 programs', 'Featured listing', 'Priority support', 'Analytics dashboard']],
            ['Enterprise',  'enterprise',  14999, 149990, ['Unlimited programs', 'Top spotlight', 'Dedicated manager', 'Full analytics', 'API access']],
        ];
        foreach ($plansData as [$name, $slug, $monthly, $yearly, $features]) {
            $plans[] = SubscriptionPlan::firstOrCreate(['slug' => $slug], [
                'name'          => $name,
                'price_monthly' => $monthly,
                'price_yearly'  => $yearly,
                'features'      => $features,
                'is_active'     => true,
            ]);
        }

        // ── 16. INSTITUTION SUBSCRIPTIONS ─────────────────────────────
        foreach ($institutions as $i => $inst) {
            InstitutionSubscription::firstOrCreate(
                ['institution_id' => $inst->id],
                [
                    'subscription_plan_id' => $plans[$i % count($plans)]->id,
                    'starts_at'            => now()->subMonths(3)->toDateString(),
                    'ends_at'              => now()->addMonths(9)->toDateString(),
                    'billing_cycle'        => 'yearly',
                    'amount'               => $plans[$i % count($plans)]->price_yearly,
                    'status'               => 'active',
                ]
            );
        }

        // ── 17. REFERRAL AGREEMENTS ────────────────────────────────────
        $agreements = [];
        foreach ($institutions as $inst) {
            $agreements[$inst->id] = ReferralAgreement::firstOrCreate(
                ['institution_id' => $inst->id],
                [
                    'commission_type'              => 'percentage',
                    'commission_value'             => 10.0,
                    'student_cashback_percentage'  => 0.02,
                    'platform_revenue_percentage'  => 0.05,
                    'start_date'                   => now()->subYear()->toDateString(),
                    'end_date'                     => now()->addYear()->toDateString(),
                    'status'                       => 'active',
                ]
            );
        }

        // ── 18. SCHOLARSHIPS ───────────────────────────────────────────
        $scholarships = [];
        $ipList = array_values($ipMap);
        foreach ($ipList as $i => $ip) {
            if ($i >= 4) break;
            $inst = $ip->institution;
            $scholarships[] = Scholarship::firstOrCreate(
                ['slug' => 'merit-scholarship-' . $inst->slug],
                [
                    'institution_id'         => $inst->id,
                    'institution_program_id' => $ip->id,
                    'type'                   => 'merit_based',
                    'title'                  => 'Merit Scholarship - ' . $inst->name,
                    'description'            => 'Awarded to students with outstanding academic performance.',
                    'minimum_gpa'            => 3.2,
                    'minimum_percentage'     => 75.0,
                    'benefit_type'           => 'percentage',
                    'benefit_value'          => 25.0,
                    'total_slots'            => 10,
                    'used_slots'             => 2,
                    'start_date'             => now()->subMonth()->toDateString(),
                    'end_date'               => now()->addMonths(5)->toDateString(),
                    'status'                 => 'active',
                ]
            );
        }

        // ── 19. APPLICATIONS ───────────────────────────────────────────
        $applications    = [];
        $statusProgression = [
            ['submitted', null, null, null],
            ['under_review', null, null, null],
            ['admitted', null, null, now()->subWeek()],
            ['rejected', null, null, null],
            ['submitted', null, null, null],
            ['under_review', null, null, null],
        ];
        foreach ($students as $i => $st) {
            $ip   = $ipList[$i % count($ipList)];
            $inst = $ip->institution;
            [$status, , , $admittedAt] = $statusProgression[$i];
            $app = Application::firstOrCreate(
                ['application_number' => 'APP-DEMO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'student_id'             => $st->id,
                    'institution_id'         => $inst->id,
                    'institution_program_id' => $ip->id,
                    'scholarship_id'         => $scholarships[$i % count($scholarships)]?->id ?? null,
                    'source'                 => 'direct',
                    'status'                 => $status,
                    'student_message'        => 'I am very interested in this program and believe I can excel here.',
                    'submitted_at'           => now()->subDays(30 - $i * 3),
                    'reviewed_at'            => $status !== 'submitted' ? now()->subDays(20 - $i * 2) : null,
                    'admitted_at'            => $admittedAt,
                ]
            );
            $applications[] = $app;
        }

        // ── 20. APPLICATION STATUS LOGS ────────────────────────────────
        foreach ($applications as $app) {
            ApplicationStatusLog::firstOrCreate(
                ['application_id' => $app->id, 'to_status' => 'submitted'],
                [
                    'from_status'      => null,
                    'changed_by_type'  => Student::class,
                    'changed_by_id'    => $app->student_id,
                ]
            );
            if ($app->status !== 'submitted') {
                ApplicationStatusLog::firstOrCreate(
                    ['application_id' => $app->id, 'to_status' => $app->status],
                    [
                        'from_status'      => 'submitted',
                        'changed_by_type'  => User::class,
                        'changed_by_id'    => $admin->id,
                        'remarks'          => 'Reviewed and updated by admin.',
                    ]
                );
            }
        }

        // ── 21. ADMISSIONS ─────────────────────────────────────────────
        $admissions = [];
        foreach ($applications as $app) {
            if ($app->status !== 'admitted') continue;
            $admission = Admission::firstOrCreate(
                ['application_id' => $app->id],
                [
                    'student_id'             => $app->student_id,
                    'institution_id'         => $app->institution_id,
                    'institution_program_id' => $app->institution_program_id,
                    'admission_number'       => 'ADM-DEMO-' . strtoupper(Str::random(6)),
                    'admission_date'         => now()->subDays(5)->toDateString(),
                    'paid_amount'            => 50000,
                    'verification_status'    => 'verified',
                    'verified_by'            => $admin->id,
                    'verified_at'            => now()->subDays(4),
                ]
            );
            $admissions[] = $admission;
        }

        // ── 22. COMMISSION INVOICES ────────────────────────────────────
        $invoices = [];
        foreach ($admissions as $i => $adm) {
            $agreement = $agreements[$adm->institution_id] ?? null;
            if (! $agreement) continue;
            $commissionAmount        = $adm->paid_amount * 0.10;
            $studentCashbackAmount   = $adm->paid_amount * 0.02;
            $platformRevenueAmount   = $adm->paid_amount * 0.05;
            $invoice = CommissionInvoice::firstOrCreate(
                ['admission_id' => $adm->id],
                [
                    'invoice_number'           => 'INV-DEMO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'institution_id'           => $adm->institution_id,
                    'referral_agreement_id'    => $agreement->id,
                    'admission_paid_amount'    => $adm->paid_amount,
                    'commission_type'          => 'percentage',
                    'commission_value'         => 10.0,
                    'commission_amount'        => $commissionAmount,
                    'student_cashback_amount'  => $studentCashbackAmount,
                    'platform_revenue_amount'  => $platformRevenueAmount,
                    'status'                   => 'paid',
                    'invoice_date'             => now()->subDays(4)->toDateString(),
                    'due_date'                 => now()->addDays(26)->toDateString(),
                    'paid_at'                  => now()->subDays(2),
                ]
            );
            $invoices[] = $invoice;
        }

        // ── 23. COMMISSION PAYMENTS ────────────────────────────────────
        foreach ($invoices as $invoice) {
            CommissionPayment::firstOrCreate(
                ['commission_invoice_id' => $invoice->id],
                [
                    'amount'                => $invoice->commission_amount,
                    'payment_method'        => 'bank_transfer',
                    'transaction_reference' => 'TXN-' . strtoupper(Str::random(8)),
                    'payment_date'          => now()->subDays(2)->toDateString(),
                    'remarks'               => 'Commission paid via bank transfer.',
                ]
            );
        }

        // ── 24. SCHOLARSHIP APPLICATIONS ──────────────────────────────
        foreach ($students as $i => $st) {
            $sc = $scholarships[$i % count($scholarships)] ?? null;
            if (! $sc) continue;
            ScholarshipApplication::firstOrCreate(
                ['scholarship_id' => $sc->id, 'student_id' => $st->id],
                [
                    'application_id'  => $applications[$i]?->id,
                    'status'          => ['pending', 'under_review', 'approved', 'rejected'][$i % 4],
                    'approved_amount' => $i % 4 === 2 ? 30000 : null,
                    'remarks'         => 'I have consistently scored above 80% in my academics.',
                ]
            );
        }

        // ── 25. SCHOLARSHIP CASHBACKS ──────────────────────────────────
        foreach ($invoices as $invoice) {
            ScholarshipCashback::firstOrCreate(
                ['commission_invoice_id' => $invoice->id],
                [
                    'student_id'                => $invoice->admission?->student_id ?? $students[0]->id,
                    'application_id'            => $invoice->admission_id ? null : null,
                    'commission_received_amount'=> $invoice->commission_amount,
                    'cashback_percentage'        => 0.02,
                    'cashback_amount'            => $invoice->student_cashback_amount,
                    'status'                    => 'paid',
                    'payment_method'            => 'bank_transfer',
                    'transaction_reference'     => 'CB-' . strtoupper(Str::random(8)),
                    'paid_at'                   => now()->subDay(),
                    'remarks'                   => 'Cashback processed successfully.',
                ]
            );
        }

        // ── 26. REFERRALS ──────────────────────────────────────────────
        foreach ($applications as $i => $app) {
            if ($i % 2 !== 0) continue;
            Referral::firstOrCreate(
                ['application_id' => $app->id],
                [
                    'referral_number' => 'REF-DEMO-' . strtoupper(Str::random(6)),
                    'student_id'      => $app->student_id,
                    'institution_id'  => $app->institution_id,
                    'referred_by'     => $admin->id,
                    'status'          => 'viewed',
                    'referred_at'     => now()->subDays(15),
                    'viewed_at'       => now()->subDays(10),
                ]
            );
        }

        // ── 27. INQUIRIES ──────────────────────────────────────────────
        $inquiries = [];
        foreach ($students as $i => $st) {
            $inst  = $institutions[$i % count($institutions)];
            $ip    = $ipList[$i % count($ipList)];
            $inq   = Inquiry::firstOrCreate(
                ['student_id' => $st->id, 'institution_id' => $inst->id],
                [
                    'institution_program_id' => $ip->id,
                    'name'                   => $st->name,
                    'email'                  => $st->email,
                    'phone'                  => $st->phone,
                    'message'                => 'I would like to know more about the admission process and available scholarships.',
                    'source'                 => 'website',
                    'status'                 => ['new', 'contacted', 'qualified', 'converted'][$i % 4],
                    'assigned_to'            => $staffUsers[$i % count($staffUsers)]->id,
                    'last_contacted_at'      => $i % 4 !== 0 ? now()->subDays($i + 1) : null,
                ]
            );
            $inquiries[] = $inq;
        }

        // ── 28. LEAD NOTES ─────────────────────────────────────────────
        foreach ($inquiries as $i => $inq) {
            LeadNote::firstOrCreate(
                ['inquiry_id' => $inq->id, 'user_id' => $staffUsers[$i % count($staffUsers)]->id],
                ['note' => 'Student is genuinely interested. Follow up scheduled for next week.']
            );
        }

        // ── 29. LEAD FOLLOW-UPS ────────────────────────────────────────
        foreach ($inquiries as $i => $inq) {
            LeadFollowUp::firstOrCreate(
                ['inquiry_id' => $inq->id],
                [
                    'assigned_to'  => $staffUsers[$i % count($staffUsers)]->id,
                    'follow_up_at' => now()->addDays($i + 1),
                    'status'       => $i % 2 === 0 ? 'pending' : 'completed',
                    'remarks'      => 'Discussed program details and fee structure.',
                ]
            );
        }

        // ── 30. INSTITUTION FOLLOWERS ──────────────────────────────────
        foreach ($students as $i => $st) {
            foreach (array_slice($institutions, 0, 2) as $inst) {
                InstitutionFollower::firstOrCreate(
                    ['student_id' => $st->id, 'institution_id' => $inst->id]
                );
            }
        }

        // ── 31. STUDENT FAVORITES ──────────────────────────────────────
        foreach ($students as $i => $st) {
            $inst = $institutions[$i % count($institutions)];
            StudentFavoriteInstitution::firstOrCreate(
                ['student_id' => $st->id, 'institution_id' => $inst->id]
            );
        }

        // ── 32. STUDENT COMPARE ITEMS ──────────────────────────────────
        foreach ($students as $i => $st) {
            foreach (array_slice($institutions, 0, 3) as $j => $inst) {
                $ip = $ipList[($i + $j) % count($ipList)];
                StudentCompareItem::firstOrCreate(
                    ['student_id' => $st->id, 'institution_id' => $inst->id],
                    ['institution_program_id' => $ip->id]
                );
            }
        }

        // ── 33. STUDENT RECOMMENDATIONS ───────────────────────────────
        foreach ($students as $i => $st) {
            foreach (array_slice($institutions, 0, 2) as $j => $inst) {
                $ip = $ipList[($i + $j) % count($ipList)];
                StudentRecommendation::firstOrCreate(
                    ['student_id' => $st->id, 'institution_id' => $inst->id],
                    [
                        'institution_program_id' => $ip->id,
                        'score'                  => round(70 + ($i * 5), 2),
                        'reasons'                => ['Matches career interests', 'Within budget', 'Good location'],
                        'is_viewed'              => $j === 0,
                    ]
                );
            }
        }

        // ── 34. COUNSELING SESSIONS ────────────────────────────────────
        foreach ($students as $i => $st) {
            $inst = $institutions[$i % count($institutions)];
            CounselingSession::firstOrCreate(
                ['student_id' => $st->id, 'institution_id' => $inst->id],
                [
                    'counselor_id'    => $staffUsers[$i % count($staffUsers)]->id,
                    'mode'            => ['online', 'in_person', 'phone'][$i % 3],
                    'scheduled_at'    => now()->addDays($i + 2),
                    'status'          => ['scheduled', 'completed', 'scheduled'][$i % 3],
                    'student_message' => 'Looking for guidance on which program suits my profile best.',
                    'counselor_notes' => $i % 3 === 1 ? 'Student is well prepared. Recommended BBA program.' : null,
                ]
            );
        }

        // ── 35. CONSULTANCY SERVICES ───────────────────────────────────
        $consultancy = $instMap['edupath-consultancy'] ?? null;
        if ($consultancy) {
            $serviceTypes = ['visa_assistance', 'document_preparation', 'application_support', 'career_counseling'];
            $serviceTitles = ['Visa Assistance', 'Document Preparation', 'Application Support', 'Career Counseling'];
            $fees = [5000, 3000, 4000, 2000];
            foreach ($serviceTypes as $k => $type) {
                ConsultancyService::firstOrCreate(
                    ['institution_id' => $consultancy->id, 'service_type' => $type],
                    [
                        'title'       => $serviceTitles[$k],
                        'description' => 'Professional ' . $serviceTitles[$k] . ' services for students.',
                        'service_fee' => $fees[$k],
                        'is_active'   => true,
                    ]
                );
            }

            // ── 36. CONSULTANCY DESTINATIONS ──────────────────────────
            foreach (['Australia', 'Canada', 'Japan', 'Germany'] as $country) {
                ConsultancyDestination::firstOrCreate(
                    ['institution_id' => $consultancy->id, 'country' => $country],
                    ['is_active' => true]
                );
            }
        }

        // ── 37. INSTITUTION REVIEWS ────────────────────────────────────
        foreach ($students as $i => $st) {
            $inst = $institutions[$i % count($institutions)];
            InstitutionReview::firstOrCreate(
                ['student_id' => $st->id, 'institution_id' => $inst->id],
                [
                    'rating'      => rand(3, 5),
                    'review'      => 'Great institution with excellent faculty and modern facilities. Highly recommended.',
                    'is_approved' => $i % 3 !== 0,
                ]
            );
        }

        // ── 38. POSTS ──────────────────────────────────────────────────
        $posts = [];
        $postData = [
            ['Admission Open 2081/82 - Apply Now',         'news',         true],
            ['How to Choose the Right Program for You',    'article',      true],
            ['Annual Science Exhibition Results',          'announcement', true],
            ['Top 10 Scholarships in Nepal for 2081',      'article',      true],
            ['Campus Event: Career Fair 2081',             'event_recap',  false],
        ];
        foreach ($postData as $i => [$title, $type, $isPublished]) {
            $inst = $institutions[$i % count($institutions)];
            $posts[] = Post::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'institution_id' => $inst->id,
                    'created_by'     => $staffUsers[$i % count($staffUsers)]->id,
                    'type'           => $type,
                    'title'          => $title,
                    'content'        => '<p>This is a demo post about: <strong>' . $title . '</strong>. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                    'is_published'   => $isPublished,
                    'is_featured'    => $i < 2,
                    'published_at'   => $isPublished ? now()->subDays($i + 1) : null,
                ]
            );
        }

        // ── 39. POST MEDIA ─────────────────────────────────────────────
        foreach ($posts as $post) {
            PostMedia::firstOrCreate(
                ['post_id' => $post->id, 'type' => 'image'],
                [
                    'file_path' => 'posts/sample-image.jpg',
                    'caption'   => 'Featured image for ' . $post->title,
                ]
            );
        }

        // ── 40. POST REACTIONS ─────────────────────────────────────────
        foreach ($posts as $i => $post) {
            $st = $students[$i % count($students)];
            PostReaction::firstOrCreate(
                ['post_id' => $post->id, 'reactable_type' => Student::class, 'reactable_id' => $st->id, 'reaction' => 'like'],
            );
        }

        // ── 41. POST COMMENTS ──────────────────────────────────────────
        foreach ($posts as $i => $post) {
            $st = $students[$i % count($students)];
            PostComment::firstOrCreate(
                ['post_id' => $post->id, 'commentable_type' => Student::class, 'commentable_id' => $st->id],
                [
                    'comment'   => 'Very helpful post! Thank you for sharing this information.',
                    'is_hidden' => false,
                ]
            );
        }

        // ── 42. CONVERSATIONS & MESSAGES ───────────────────────────────
        $conversations = [];
        foreach ($students as $i => $st) {
            $inst  = $institutions[$i % count($institutions)];
            $conv  = Conversation::firstOrCreate(
                ['student_id' => $st->id, 'institution_id' => $inst->id],
                ['type' => ['general', 'inquiry', 'application'][$i % 3]]
            );
            $conversations[] = $conv;

            Message::firstOrCreate(
                ['conversation_id' => $conv->id, 'sender_type' => Student::class, 'sender_id' => $st->id],
                [
                    'message' => 'Hello, I would like to inquire about the admission requirements for the upcoming semester.',
                    'read_at' => now()->subHour(),
                ]
            );
            $staff = $staffUsers[$i % count($staffUsers)];
            Message::firstOrCreate(
                ['conversation_id' => $conv->id, 'sender_type' => User::class, 'sender_id' => $staff->id],
                [
                    'message' => 'Thank you for your interest! Please visit our website or fill the inquiry form for detailed information.',
                    'read_at' => null,
                ]
            );
        }

        // ── 43. PROMOTIONS ─────────────────────────────────────────────
        $promoData = [
            ['banner',    'Early Bird Admission Discount',     'active'],
            ['spotlight', 'Featured Program: BBA at ACE',      'active'],
            ['cashback',  '5% Cashback on First Application',  'active'],
            ['event',     'Open Day - Visit Campus',           'paused'],
        ];
        foreach ($promoData as $i => [$type, $title, $status]) {
            $inst = $institutions[$i % count($institutions)];
            Promotion::firstOrCreate(
                ['institution_id' => $inst->id, 'title' => $title],
                [
                    'type'       => $type,
                    'status'     => $status,
                    'start_date' => now()->subDays(10)->toDateString(),
                    'end_date'   => now()->addDays(30)->toDateString(),
                    'amount'     => [5000, 10000, 2000, 0][$i],
                ]
            );
        }

        $this->command->info('DemoSeeder complete. Seeded: users, students, institutions, programs, applications, admissions, scholarships, commissions, messages, and more.');
    }
}
