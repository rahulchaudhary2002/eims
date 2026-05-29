<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostMediaRequest;
use App\Http\Requests\Admin\UpdatePostMediaRequest;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostMediaController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = PostMedia::with(['post.institution']);
        $this->applyPostInstitutionScope($query);

        if ($postId = $request->input('post_id')) {
            $query->where('post_id', $postId);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $media = $query->latest()->paginate(20)->withQueryString();
        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $types = PostMedia::TYPES;

        return view('admin.modules.post-media.index', compact('media', 'posts', 'types'));
    }

    public function create(Request $request): View
    {
        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $types = PostMedia::TYPES;
        $selectedPostId = $request->input('post_id');

        if ($selectedPostId) {
            $post = Post::findOrFail($selectedPostId);
            $this->authorizePost($post);
        }

        return view('admin.modules.post-media.create', compact('posts', 'types', 'selectedPostId'));
    }

    public function store(StorePostMediaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = Post::findOrFail($data['post_id']);
        $this->authorizePost($post);

        $data['file_path'] = $request->file('file_path')
            ->store('post-media/' . $data['post_id'], 'public');

        $mediaItem = PostMedia::create($data);

        return redirect()->route('admin.post-media.show', $mediaItem)
            ->with('success', 'Media uploaded successfully.');
    }

    public function show(PostMedia $postMedium): View
    {
        $this->authorizeMediaAccess($postMedium);
        $postMedium->load('post.institution');

        return view('admin.modules.post-media.show', compact('postMedium'));
    }

    public function edit(PostMedia $postMedium): View
    {
        $this->authorizeMediaAccess($postMedium);
        $postMedium->load('post');

        $posts = $this->postDropdownQuery()->get(['id', 'title', 'institution_id']);
        $types = PostMedia::TYPES;

        return view('admin.modules.post-media.edit', compact('postMedium', 'posts', 'types'));
    }

    public function update(UpdatePostMediaRequest $request, PostMedia $postMedium): RedirectResponse
    {
        $this->authorizeMediaAccess($postMedium);
        $data = $request->validated();
        $post = Post::findOrFail($data['post_id']);
        $this->authorizePost($post);

        if ($request->hasFile('file_path')) {
            Storage::disk('public')->delete($postMedium->file_path);
            $data['file_path'] = $request->file('file_path')
                ->store('post-media/' . $data['post_id'], 'public');
        } else {
            unset($data['file_path']);
        }

        $postMedium->update($data);

        return redirect()->route('admin.post-media.show', $postMedium)
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(PostMedia $postMedium): RedirectResponse
    {
        $this->authorizeMediaAccess($postMedium);
        $postId = $postMedium->post_id;
        Storage::disk('public')->delete($postMedium->file_path);
        $postMedium->delete();

        return redirect()->route('admin.post-media.index', ['post_id' => $postId])
            ->with('success', 'Media deleted successfully.');
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

    private function authorizeMediaAccess(PostMedia $media): void
    {
        $media->loadMissing('post');
        $this->authorizePost($media->post);
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
