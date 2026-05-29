<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostReaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostReactionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = PostReaction::with(['post.institution', 'reactable']);
        $this->applyPostInstitutionScope($query);

        if ($postId = $request->input('post_id')) {
            $query->where('post_id', $postId);
        }
        if ($reaction = $request->input('reaction')) {
            $query->where('reaction', $reaction);
        }
        if ($reactableType = $request->input('reactable_type')) {
            $query->where('reactable_type', $reactableType);
        }

        $reactions = $query->latest()->paginate(20)->withQueryString();
        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $reactionTypes = PostReaction::REACTIONS;
        $reactableTypes = PostReaction::REACTABLE_TYPES;

        return view('admin.post-reactions.index', compact(
            'reactions',
            'posts',
            'reactionTypes',
            'reactableTypes'
        ));
    }

    public function show(PostReaction $postReaction): View
    {
        $this->authorizeReactionAccess($postReaction);
        $postReaction->load(['post.institution', 'reactable']);

        return view('admin.post-reactions.show', compact('postReaction'));
    }

    public function destroy(PostReaction $postReaction): RedirectResponse
    {
        $this->authorizeReactionAccess($postReaction);
        $postId = $postReaction->post_id;
        $postReaction->delete();

        return redirect()->route('admin.post-reactions.index', ['post_id' => $postId])
            ->with('success', 'Reaction removed successfully.');
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

    private function authorizeReactionAccess(PostReaction $reaction): void
    {
        $reaction->loadMissing('post');
        $this->authorizePost($reaction->post);
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
