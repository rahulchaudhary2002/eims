<?php

namespace App\Http\Controllers;

use App\Enums\QuestionCategory;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $tab      = $request->query('tab', 'latest');
        $category = $request->query('category');

        $query = Question::query();

        // Drafts are visible only on "my_drafts"
        if ($tab !== 'my_drafts') {
            $query->where('is_draft', false);
        }

        if ($category) {
            $query->where('category', $category);
        }

        switch ($tab) {
            case 'trending':
                $query->trending();
                break;

            case 'my_posts':
                if (Auth::check()) {
                    $query->ownedBy(Auth::id())->latestFirst();
                } else {
                    $query->latestFirst();
                }
                break;

            case 'my_drafts':
                if (Auth::check()) {
                    $query->ownedBy(Auth::id())->where('is_draft', true)->latestFirst();
                } else {
                    $query->whereRaw('1 = 0'); // nothing
                }
                break;

            default: // latest
                $query->latestFirst();
        }

        $questions = $query->with('user')->paginate(10)->withQueryString();

        return view('modules.forum.index', [
            'questions'      => $questions,
            'categories'     => QuestionCategory::all(),
            'activeCategory' => $category,
            'activeTab'      => $tab,
        ]);
    }

    public function create()
    {
        return view('modules.forum.create', [
            'categories' => QuestionCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|string',
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'is_anonymous' => 'sometimes|boolean',
            'is_draft'     => 'sometimes|boolean',
        ]);

        $question = Question::create([
            'user_id'      => $request->user()->id,
            'category'     => $validated['category'],
            'title'        => $validated['title'],
            'body'         => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'is_draft'     => $validated['is_draft'] ?? false,
        ]);

        return redirect()
            ->route('forum.question.show', $question)
            ->with('status', 'Question created.');
    }

    public function show(Question $question)
    {
        if ($question->is_draft && Auth::id() !== $question->user_id) {
            abort(404);
        }

        $question->increment('views_count');

        $question->load([
            'user',
            'replies.user',
            'replies.children.user',
            'replies.children.children.user',
        ]);

        return view('modules.forum.show', [
            'question'   => $question,
            'categories' => QuestionCategory::all(),
        ]);
    }
}
