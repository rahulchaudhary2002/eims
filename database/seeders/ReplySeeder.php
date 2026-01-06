<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;

class ReplySeeder extends Seeder
{
    public function run(): void
    {
        // Get questions and users
        $questions = Question::all();
        $users = User::all();

        if ($questions->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please run QuestionSeeder and UserSeeder first!');
            return;
        }

        $replies = [];

        foreach ($questions as $question) {
            // Each question gets 2-6 replies
            $replyCount = rand(2, 6);

            // Main (top-level) replies
            for ($i = 0; $i < $replyCount; $i++) {
                $mainReplyId = count($replies) + 1;

                // Get category as string (handle both enum and string)
                $category = $question->category;
                if (is_object($category) && method_exists($category, 'value')) {
                    $category = $category->value(); // For Enum
                } elseif (is_object($category) && isset($category->value)) {
                    $category = $category->value; // For Enum case
                } elseif (is_object($category)) {
                    $category = (string) $category; // Convert to string
                }

                // Create main reply
                $replies[] = [
                    'id' => $mainReplyId,
                    'question_id' => $question->id,
                    'user_id' => $users->random()->id,
                    'parent_id' => null,
                    'depth' => 1,
                    'body' => $this->generateReplyBody($category),
                    'is_anonymous' => rand(0, 1) == 1, // 50% chance anonymous
                    'created_at' => $question->published_at ?
                        $question->published_at->addMinutes(rand(30, 1440)) :
                        now()->subDays(rand(1, 30)),
                    'updated_at' => null,
                ];

                // Some main replies get nested replies (depth 2)
                if (rand(0, 1) == 1) { // 50% chance
                    $nestedCount = rand(1, 3);
                    for ($j = 0; $j < $nestedCount; $j++) {
                        $nestedReplyId = count($replies) + 1;

                        $replies[] = [
                            'id' => $nestedReplyId,
                            'question_id' => $question->id,
                            'user_id' => $users->random()->id,
                            'parent_id' => $mainReplyId,
                            'depth' => 2,
                            'body' => $this->generateNestedReplyBody(),
                            'is_anonymous' => rand(0, 1) == 1,
                            'created_at' => $replies[$mainReplyId - 1]['created_at']->addMinutes(rand(10, 240)), // 10 min to 4 hours after parent
                            'updated_at' => null,
                        ];

                        // Some nested replies get further nested replies (depth 3)
                        if (rand(0, 2) == 1) { // 33% chance
                            $deepNestedReplyId = count($replies) + 1;

                            $replies[] = [
                                'id' => $deepNestedReplyId,
                                'question_id' => $question->id,
                                'user_id' => $users->random()->id,
                                'parent_id' => $nestedReplyId,
                                'depth' => 3,
                                'body' => $this->generateDeepNestedReplyBody(),
                                'is_anonymous' => rand(0, 1) == 1,
                                'created_at' => $replies[$nestedReplyId - 1]['created_at']->addMinutes(rand(5, 120)), // 5 min to 2 hours after parent
                                'updated_at' => null,
                            ];
                        }
                    }
                }
            }
        }

        // Insert replies (without IDs for auto-increment)
        foreach ($replies as $reply) {
            unset($reply['id']); // Remove the temporary ID
            Reply::create($reply);
        }

        // Update questions with actual reply count
        foreach ($questions as $question) {
            $actualReplyCount = Reply::where('question_id', $question->id)->count();
            $question->update(['replies_count' => $actualReplyCount]);
        }
    }

    private function generateReplyBody(string $category): string
    {
        $responses = [
            'academics' => [
                "Based on my experience studying in Nepal, the grading system varies by university. Tribhuvan University uses both GPA and percentage, while some private universities use CGPA.",
                "I've found that most Nepali universities follow a percentage system for evaluation, but they convert it to GPA for transcripts.",
                "The grading system in Nepali universities typically ranges from A+ to E, with corresponding grade points."
            ],
            'admissions' => [
                "The admission process usually starts in July-August for most universities. You'll need your SEE marksheet, character certificate, and citizenship copy.",
                "I recommend starting your preparation early and keeping all documents ready. The competition is quite high for popular programs.",
                "Most colleges have online admission forms now. Make sure to check their official websites regularly for updates."
            ],
            'campus_life' => [
                "The campus life varies greatly between colleges. Some have excellent facilities while others are more basic.",
                "I suggest visiting the campus personally before making a decision. The atmosphere is important for your overall experience.",
                "Look for colleges with active student clubs and extracurricular activities. They really enhance the learning experience."
            ],
            'colleges' => [
                "Both are good colleges, but St. Xavier's has a slightly better reputation and placement record for BBA.",
                "It depends on what you're looking for. Trinity might have more modern facilities, but Xavier's has stronger alumni network.",
                "Consider factors like location, fees, faculty qualifications, and placement statistics before deciding."
            ],
            'entrance_exams' => [
                "The IOE entrance exam typically has 100 multiple choice questions covering Physics, Chemistry, Math, and English.",
                "Practice with previous years' question papers. They give you a good idea of the pattern and difficulty level.",
                "Time management is crucial in entrance exams. Practice solving questions within time limits."
            ],
            'events' => [
                "Most college fests happen between October and December. Check individual college Facebook pages for updates.",
                "The tech fest at KU and cultural fest at St. Xavier's are usually the biggest events of the year.",
                "Many events are announced just a few weeks in advance, so keep checking college notice boards."
            ],
            'exams' => [
                "TU usually allows backlog students to reappear in the next semester's exam. There's a separate form and fee for this.",
                "For practical exams, focus on understanding the concepts rather than memorizing procedures. Examiners appreciate genuine understanding.",
                "Cheating can lead to suspension or even expulsion. It's not worth risking your entire academic career."
            ],
            'general' => [
                "Many restaurants and cafes in Kathmandu offer student discounts. Just show your student ID card.",
                "Part-time tutoring is quite popular among college students. You can also look for internships in your field.",
                "The best study time varies by person. Some people are morning people, others work better at night. Find what works for you."
            ],
            'programs' => [
                "BBA is more focused on practical business skills while BBS is more theoretical. BBA generally has better placement opportunities.",
                "Computer engineering focuses more on hardware and low-level programming, while IT covers broader software applications.",
                "MBA specializations like Finance, Marketing, and HR are quite popular in Nepal. Digital marketing is emerging as a new trend."
            ],
            'scholarships' => [
                "Government scholarships are available through the local ward office. You'll need income certificate and academic documents.",
                "Some private colleges offer merit-based scholarships for top entrance exam scorers. Check with individual colleges.",
                "Full scholarships are rare but available for exceptionally talented students through various foundations and trusts."
            ]
        ];

        $categoryResponses = $responses[$category] ?? [
            "That's a good question. Based on my knowledge...",
            "I think the answer depends on several factors including...",
            "From what I've experienced in the Nepali education system..."
        ];

        return $categoryResponses[array_rand($categoryResponses)];
    }

    private function generateNestedReplyBody(): string
    {
        $responses = [
            "I agree with your point, but I'd like to add that...",
            "That's a good point. However, have you considered...",
            "I had a similar experience. In my case...",
            "Thanks for sharing this information. Based on my experience...",
            "Could you elaborate more on that point? I'm curious about...",
            "I disagree slightly. From what I've observed...",
            "That's interesting. I never thought about it that way.",
            "You make a valid point, but there's another perspective...",
            "I completely agree with you. This matches my experience exactly.",
            "This is helpful information. To build on what you said..."
        ];

        return $responses[array_rand($responses)];
    }

    private function generateDeepNestedReplyBody(): string
    {
        $responses = [
            "Exactly! That's what I was trying to say.",
            "I see your point now. Thanks for clarifying.",
            "I think we're saying the same thing in different ways.",
            "You're right, I didn't consider that aspect.",
            "Let me rephrase that to make it clearer...",
            "Sorry if I misunderstood your earlier point.",
            "To summarize what we've been discussing...",
            "This discussion has been really helpful, thanks!",
            "I think we've covered the main points now.",
            "Good point. Let's continue this discussion if needed."
        ];

        return $responses[array_rand($responses)];
    }
}
