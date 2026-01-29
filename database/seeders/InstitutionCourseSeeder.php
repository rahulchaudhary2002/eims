<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Institution;
use App\Models\Course;

class InstitutionCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pivot data
        DB::table('institution_course')->truncate();

        // Get all institutions and courses
        $institutions = Institution::all();
        $courses = Course::all();

        // Map institutions to specific courses based on type and name
        $pivotData = [];

        foreach ($institutions as $institution) {
            $assignedCourseIds = $this->getCoursesForInstitution($institution, $courses);

            foreach ($assignedCourseIds as $courseId) {
                $pivotData[] = [
                    'institution_id' => $institution->id,
                    'course_id' => $courseId,
                    'comission_amount' => rand(5000, 30000),
                ];
            }
        }

        // Insert data in chunks to avoid memory issues
        $chunks = array_chunk($pivotData, 50);
        foreach ($chunks as $chunk) {
            DB::table('institution_course')->insert($chunk);
        }

        $this->command->info('Institution-Course relationships seeded successfully!');
        $this->command->info('Total relationships created: ' . count($pivotData));
    }

    /**
     * Determine which courses should be assigned to an institution
     * based on institution type, name, and specialization
     */
    private function getCoursesForInstitution($institution, $courses): array
    {
        $courseIds = [];

        // === SCHOOLS (only offer some specific courses) ===
        if ($institution->type === 'school') {
            // Schools only offer basic courses
            $schoolCourseCodes = ['BBS', 'CHM']; // Business Studies and Hotel Management

            foreach ($schoolCourseCodes as $code) {
                $course = $courses->firstWhere('code', $code);
                if ($course) {
                    $courseIds[] = $course->id;
                }
            }

            // Special school-specific assignments
            if (str_contains(strtolower($institution->name), 'st. xavier')) {
                // St. Xavier's schools might offer special courses
                $course = $courses->firstWhere('code', 'DCTEVT');
                if ($course) {
                    $courseIds[] = $course->id;
                }
            }
        }

        // === COLLEGES (offer more extensive courses) ===
        if ($institution->type === 'college') {
            // All colleges get basic management courses
            $basicCollegeCodes = ['BBS', 'MBA'];
            foreach ($basicCollegeCodes as $code) {
                $course = $courses->firstWhere('code', $code);
                if ($course) {
                    $courseIds[] = $course->id;
                }
            }

            // College-specific assignments based on name/specialization
            $name = strtolower($institution->name);

            // Engineering/Technical Colleges
            if (
                str_contains($name, 'engineering') ||
                str_contains($name, 'computer') ||
                str_contains($name, 'information') ||
                str_contains($name, 'ncit')
            ) {

                $techCourseCodes = ['BCT', 'BCE', 'BIT', 'DCTEVT', 'MSC-CS'];
                foreach ($techCourseCodes as $code) {
                    $course = $courses->firstWhere('code', $code);
                    if ($course) {
                        $courseIds[] = $course->id;
                    }
                }
            }

            // Science Colleges
            if (str_contains($name, 'xavier') || str_contains($name, 'trinity')) {
                $scienceCourseCodes = ['BSC-PHYSICS', 'BSC-MATH'];
                foreach ($scienceCourseCodes as $code) {
                    $course = $courses->firstWhere('code', $code);
                    if ($course) {
                        $courseIds[] = $course->id;
                    }
                }
            }

            // General Colleges (like Prime College)
            if (
                !str_contains($name, 'engineering') &&
                !str_contains($name, 'computer') &&
                !str_contains($name, 'information') &&
                !str_contains($name, 'xavier')
            ) {

                // Add all remaining courses for general colleges
                $allCourseIds = $courses->pluck('id')->toArray();
                $assignedIds = array_merge($courseIds, $allCourseIds);
                $courseIds = array_unique($assignedIds);
            }
        }

        // Make sure we don't have duplicates
        $courseIds = array_unique($courseIds);

        // Limit the number of courses per institution (optional)
        // For schools: max 3 courses
        // For colleges: max 6 courses
        $maxCourses = ($institution->type === 'school') ? 3 : 6;
        if (count($courseIds) > $maxCourses) {
            $courseIds = array_slice($courseIds, 0, $maxCourses);
        }

        return $courseIds;
    }
}
