<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\CourseDescription;

class CourseDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('course_descriptions')->truncate();

        // Get all courses
        $courses = Course::all();

        $descriptionTemplates = [
            'Overview',
            'Course Objectives',
            'Eligibility Criteria',
            'Admission Process',
            'Course Structure',
            'Career Prospects',
            'Fee Structure',
            'Scholarship Opportunities',
        ];

        $sampleContent = [
            'Overview' => '<h2>Course Overview</h2>
                <p>This comprehensive course is designed to provide students with both theoretical knowledge and practical skills in the field. Through a combination of classroom lectures, laboratory sessions, and hands-on projects, students will develop the expertise needed to excel in their chosen career path.</p>
                <p>The curriculum is regularly updated to incorporate the latest industry trends and technological advancements, ensuring that graduates are well-prepared for the challenges of the modern workplace.</p>
                <ul>
                    <li><strong>Duration:</strong> As specified in course details</li>
                    <li><strong>Mode:</strong> Full-time/Regular</li>
                    <li><strong>Teaching Methodology:</strong> Interactive lectures, case studies, and practical sessions</li>
                </ul>',

                            'Course Objectives' => '<h2>Course Objectives</h2>
                <p>Upon successful completion of this course, students will be able to:</p>
                <ol>
                    <li>Demonstrate comprehensive understanding of fundamental concepts and principles</li>
                    <li>Apply theoretical knowledge to solve practical problems in real-world scenarios</li>
                    <li>Develop critical thinking and analytical skills through research and analysis</li>
                    <li>Demonstrate proficiency in using modern tools and technologies relevant to the field</li>
                    <li>Communicate effectively and work collaboratively in team environments</li>
                    <li>Understand and adhere to professional ethics and standards</li>
                </ol>',

            'Eligibility Criteria' => '<h2>Eligibility Criteria</h2>
                <div class="admission-criteria">
                    <h3>Academic Requirements</h3>
                    <ul>
                        <li>Successfully completed 10+2 or equivalent examination</li>
                        <li>Minimum aggregate score of <strong>45%</strong> or <strong>2.0 CGPA</strong></li>
                        <li>Must have studied relevant subjects in +2 level</li>
                    </ul>
                    
                    <h3>Additional Requirements</h3>
                    <ul>
                        <li>Valid entrance examination score (if applicable)</li>
                        <li>Personal interview/group discussion</li>
                        <li>Medical fitness certificate</li>
                        <li>Character certificate from previous institution</li>
                    </ul>
                    
                    <h3>Documents Required</h3>
                    <ul>
                        <li>Mark sheets and certificates of qualifying examinations</li>
                        <li>Transfer certificate/migration certificate</li>
                        <li>Citizenship certificate (photocopy)</li>
                        <li>Passport size photographs (4 copies)</li>
                    </ul>
                </div>',

            'Admission Process' => '<h2>Admission Process</h2>
                <div class="process-steps">
                    <div class="step">
                        <h3>Step 1: Application Submission</h3>
                        <p>Submit the completed application form along with required documents either online or at the admission office.</p>
                    </div>
                    
                    <div class="step">
                        <h3>Step 2: Entrance Examination</h3>
                        <p>Appear for the entrance examination conducted by the institution/university. The exam typically covers subjects relevant to the chosen course.</p>
                    </div>
                    
                    <div class="step">
                        <h3>Step 3: Interview/Group Discussion</h3>
                        <p>Shortlisted candidates will be called for personal interview and/or group discussion to assess communication skills and aptitude.</p>
                    </div>
                    
                    <div class="step">
                        <h3>Step 4: Document Verification</h3>
                        <p>Original documents will be verified for authenticity. Any discrepancy may lead to cancellation of admission.</p>
                    </div>
                    
                    <div class="step">
                        <h3>Step 5: Fee Payment & Enrollment</h3>
                        <p>Selected candidates must pay the admission fee within the specified deadline to secure their seat.</p>
                    </div>
                </div>',

            'Course Structure' => '<h2>Course Structure</h2>
                <div class="curriculum">
                    <p>The program is structured to provide a balanced combination of theoretical knowledge and practical experience. The curriculum includes:</p>
                    
                    <h3>Core Subjects</h3>
                    <ul>
                        <li>Fundamental concepts and principles</li>
                        <li>Advanced theoretical frameworks</li>
                        <li>Applied methodologies</li>
                        <li>Industry-specific knowledge</li>
                    </ul>
                    
                    <h3>Practical Components</h3>
                    <ul>
                        <li>Laboratory sessions</li>
                        <li>Workshops and seminars</li>
                        <li>Industry visits</li>
                        <li>Project work and internships</li>
                    </ul>
                    
                    <h3>Elective Specializations</h3>
                    <ul>
                        <li>Specialization tracks based on student interest</li>
                        <li>Interdisciplinary courses</li>
                        <li>Professional skill development</li>
                    </ul>
                    
                    <h3>Assessment Pattern</h3>
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Weightage</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Theory Examinations</td>
                                <td>60%</td>
                                <td>Semester-wise</td>
                            </tr>
                            <tr>
                                <td>Practical Assessments</td>
                                <td>25%</td>
                                <td>Continuous</td>
                            </tr>
                            <tr>
                                <td>Project Work</td>
                                <td>10%</td>
                                <td>Final Year</td>
                            </tr>
                            <tr>
                                <td>Attendance & Participation</td>
                                <td>5%</td>
                                <td>Continuous</td>
                            </tr>
                        </tbody>
                    </table>
                </div>',

            'Career Prospects' => '<h2>Career Prospects</h2>
                <div class="career-opportunities">
                    <p>Graduates of this program have excellent career opportunities in various sectors. The course prepares students for both employment and higher studies.</p>
                    
                    <h3>Employment Opportunities</h3>
                    <ul>
                        <li><strong>Private Sector:</strong> Software companies, consulting firms, multinational corporations</li>
                        <li><strong>Public Sector:</strong> Government organizations, public enterprises, regulatory bodies</li>
                        <li><strong>Academic Institutions:</strong> Teaching positions, research roles, administrative positions</li>
                        <li><strong>Entrepreneurship:</strong> Start-ups, consultancy services, independent practice</li>
                    </ul>
                    
                    <h3>Job Roles</h3>
                    <div class="job-roles">
                        <div class="role-category">
                            <h4>Entry Level Positions</h4>
                            <ul>
                                <li>Trainee Engineer</li>
                                <li>Junior Analyst</li>
                                <li>Assistant Manager</li>
                                <li>Research Assistant</li>
                            </ul>
                        </div>
                        
                        <div class="role-category">
                            <h4>Mid-Level Positions</h4>
                            <ul>
                                <li>Senior Engineer</li>
                                <li>Project Manager</li>
                                <li>Team Lead</li>
                                <li>Consultant</li>
                            </ul>
                        </div>
                        
                        <div class="role-category">
                            <h4>Senior Positions</h4>
                            <ul>
                                <li>Department Head</li>
                                <li>Chief Technology Officer</li>
                                <li>Director</li>
                                <li>Principal Consultant</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3>Higher Studies Options</h3>
                    <ul>
                        <li>Master\'s Degree in related field</li>
                        <li>PhD and research programs</li>
                        <li>Professional certifications</li>
                        <li>Specialized diploma courses</li>
                    </ul>
                </div>',

            'Fee Structure' => '<h2>Fee Structure</h2>
                <div class="fee-details">
                    <p>The following fee structure is applicable for the entire duration of the program. Fees are subject to revision as per institutional policies.</p>
                    
                    <h3>Tuition and Other Fees</h3>
                    <table class="fee-table">
                        <thead>
                            <tr>
                                <th>Fee Category</th>
                                <th>Amount (NPR)</th>
                                <th>Payment Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tuition Fee</td>
                                <td>80,000</td>
                                <td>Per Semester</td>
                            </tr>
                            <tr>
                                <td>Laboratory Fee</td>
                                <td>15,000</td>
                                <td>Per Semester</td>
                            </tr>
                            <tr>
                                <td>Library Fee</td>
                                <td>5,000</td>
                                <td>Per Semester</td>
                            </tr>
                            <tr>
                                <td>Examination Fee</td>
                                <td>3,000</td>
                                <td>Per Semester</td>
                            </tr>
                            <tr>
                                <td>Student Welfare Fee</td>
                                <td>2,000</td>
                                <td>Per Semester</td>
                            </tr>
                            <tr>
                                <td>Admission Fee (One-time)</td>
                                <td>10,000</td>
                                <td>At Admission</td>
                            </tr>
                            <tr>
                                <td>Security Deposit (Refundable)</td>
                                <td>5,000</td>
                                <td>At Admission</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3>Total Estimated Cost</h3>
                    <div class="total-cost">
                        <p><strong>Per Semester:</strong> Approximately NPR 105,000</p>
                        <p><strong>Full Program (4 years):</strong> Approximately NPR 840,000</p>
                    </div>
                    
                    <h3>Payment Options</h3>
                    <ul>
                        <li>Full payment at admission (discount available)</li>
                        <li>Semester-wise payment</li>
                        <li>Monthly installments (additional processing fee may apply)</li>
                        <li>Bank loan facilitation available</li>
                    </ul>
                </div>',

            'Scholarship Opportunities' => '<h2>Scholarship Opportunities</h2>
                <div class="scholarship-info">
                    <p>The institution offers various scholarship schemes to support deserving and meritorious students. Scholarships are awarded based on different criteria.</p>
                    
                    <h3>Available Scholarships</h3>
                    <div class="scholarship-types">
                        <div class="scholarship-category">
                            <h4>Merit-Based Scholarships</h4>
                            <ul>
                                <li><strong>Top Ranker Scholarship:</strong> Full tuition waiver for students securing top 3 positions in entrance exam</li>
                                <li><strong>Academic Excellence Scholarship:</strong> 50% tuition fee waiver for students with outstanding academic records</li>
                                <li><strong>Semester Topper Scholarship:</strong> 25% fee discount for semester toppers</li>
                            </ul>
                        </div>
                        
                        <div class="scholarship-category">
                            <h4>Need-Based Scholarships</h4>
                            <ul>
                                <li><strong>Economically Disadvantaged Scholarship:</strong> Up to 100% fee waiver based on family income</li>
                                <li><strong>Single Child Scholarship:</strong> Special consideration for single girl child</li>
                                <li><strong>Special Category Scholarship:</strong> For differently-abled students</li>
                            </ul>
                        </div>
                        
                        <div class="scholarship-category">
                            <h4>Other Scholarships</h4>
                            <ul>
                                <li><strong>Government Scholarships:</strong> As per government regulations and quotas</li>
                                <li><strong>Corporate Scholarships:</strong> Sponsored by industry partners</li>
                                <li><strong>Alumni Scholarships:</strong> Funded by alumni associations</li>
                                <li><strong>Sports & Cultural Scholarships:</strong> For exceptional achievements in extracurricular activities</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3>Application Process</h3>
                    <ol>
                        <li>Submit scholarship application form along with admission form</li>
                        <li>Provide supporting documents (income certificate, mark sheets, etc.)</li>
                        <li>Attend scholarship interview if required</li>
                        <li>Selection committee review and approval</li>
                    </ol>
                    
                    <div class="note">
                        <p><strong>Note:</strong> Scholarship renewal is subject to maintaining minimum academic performance and attendance criteria.</p>
                    </div>
                </div>',
        ];

        foreach ($courses as $course) {
            $order = 1;

            // Add 4-6 descriptions per course
            $selectedTemplates = array_rand($descriptionTemplates, rand(4, 6));
            if (!is_array($selectedTemplates)) {
                $selectedTemplates = [$selectedTemplates];
            }

            foreach ($selectedTemplates as $templateKey) {
                $title = $descriptionTemplates[$templateKey];

                CourseDescription::create([
                    'course_id' => $course->id,
                    'title' => $title,
                    'content' => $sampleContent[$title] ?? $this->generateContent($title),
                    'order' => $order++,
                ]);
            }
        }
    }

    private function generateContent(string $title): string
    {
        return '<h2>' . htmlspecialchars($title) . '</h2>
            <p>Detailed information about ' . htmlspecialchars(strtolower($title)) . ' will be provided here. This section contains comprehensive details that help students understand various aspects of the course.</p>
            <p>For more specific information, please contact the admission office or visit our website.</p>';
    }
}
