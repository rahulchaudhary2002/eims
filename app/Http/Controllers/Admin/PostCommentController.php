<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostCommentRequest;
use App\Http\Requests\Admin\UpdatePostCommentRequest;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostCommentController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = PostComment::with(['post.institution', 'commentable', 'parent']);
        $this->applyPostInstitutionScope($query);

        if ($postId = $request->input('post_id')) {
            $query->where('post_id', $postId);
        }
        if ($commentableType = $request->input('commentable_type')) {
            $query->where('commentable_type', $commentableType);
        }
        if ($request->input('is_hidden') !== null && $request->input('is_hidden') !== '') {
            $query->where('is_hidden', (bool) $request->input('is_hidden'));
        }

        $comments = $query->latest()->paginate(20)->withQueryString();
        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $commentableTypes = PostComment::COMMENTABLE_TYPES;

        return view('admin.modules.post-comments.index', compact('comments', 'posts', 'commentableTypes'));
    }

    public function create(Request $request): View
    {
        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $commentableTypes = PostComment::COMMENTABLE_TYPES;
        $selectedPostId = $request->input('post_id');
        $selectedParentId = $request->input('parent_id');

        if ($selectedPostId) {
            $post = Post::findOrFail($selectedPostId);
            $this->authorizePost($post);
        }

        return view('admin.modules.post-comments.create', compact(
            'posts',
            'commentableTypes',
            'selectedPostId',
            'selectedParentId'
        ));
    }

    public function store(StorePostCommentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = Post::findOrFail($data['post_id']);
        $this->authorizePost($post);

        $comment = PostComment::create($data);

        return redirect()->route('admin.post-comments.show', $comment)
            ->with('success', 'Comment created successfully.');
    }

    public function show(PostComment $postComment): View
    {
        $this->authorizeCommentAccess($postComment);
        $postComment->load(['post.institution', 'commentable', 'parent.commentable', 'replies.commentable']);

        return view('admin.modules.post-comments.show', compact('postComment'));
    }

    public function edit(PostComment $postComment): View
    {
        $this->authorizeCommentAccess($postComment);
        $postComment->load(['post', 'commentable', 'parent']);

        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $commentableTypes = PostComment::COMMENTABLE_TYPES;

        return view('admin.modules.post-comments.edit', compact('postComment', 'posts', 'commentableTypes'));
    }

    public function update(UpdatePostCommentRequest $request, PostComment $postComment): RedirectResponse
    {
        $this->authorizeCommentAccess($postComment);
        $data = $request->validated();
        $post = Post::findOrFail($data['post_id']);
        $this->authorizePost($post);

        $postComment->update($data);

        return redirect()->route('admin.post-comments.show', $postComment)
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(PostComment $postComment): RedirectResponse
    {
        $this->authorizeCommentAccess($postComment);
        $postId = $postComment->post_id;
        $postComment->delete();

        return redirect()->route('admin.post-comments.index', ['post_id' => $postId])
            ->with('success', 'Comment deleted successfully.');
    }

    public function toggleHidden(PostComment $postComment): RedirectResponse
    {
        $this->authorizeCommentAccess($postComment);
        $postComment->update(['is_hidden' => ! $postComment->is_hidden]);

        $msg = $postComment->is_hidden ? 'Comment hidden.' : 'Comment visible.';

        return back()->with('success', $msg);
    }

    private function postDropdownQuery(): Builder
    {
        $query = Post::orderBy('title');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function applyPostInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('post', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function authorizeCommentAccess(PostComment $comment): void
    {
        $comment->loadMissing('post');
        $this->authorizePost($comment->post);
    }

    private function authorizePost(Post $post): void
    {
        if ($post->institution_id) {
            $this->authorizeInstitution((int) $post->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this post.');
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
