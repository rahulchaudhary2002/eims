<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Institution;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Post::with(['institution', 'creator']);
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($createdBy = $request->input('created_by')) {
            $query->where('created_by', $createdBy);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($request->input('is_published') !== null && $request->input('is_published') !== '') {
            $query->where('is_published', (bool) $request->input('is_published'));
        }
        if ($request->input('is_featured') !== null && $request->input('is_featured') !== '') {
            $query->where('is_featured', (bool) $request->input('is_featured'));
        }
        if ($publishedFrom = $request->input('published_from')) {
            $query->whereDate('published_at', '>=', $publishedFrom);
        }
        if ($publishedTo = $request->input('published_to')) {
            $query->whereDate('published_at', '<=', $publishedTo);
        }

        $posts = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $types = Post::TYPES;

        return view('admin.modules.posts.index', compact('posts', 'institutions', 'users', 'types'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $types = Post::TYPES;
        $selectedInstitutionId = $request->input('institution_id');
        $defaultCreatedBy = auth('web')->id();

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.posts.create', compact(
            'institutions',
            'users',
            'types',
            'selectedInstitutionId',
            'defaultCreatedBy'
        ));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('posts/thumbnails', 'public');
        }

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $data['created_by'] = $data['created_by'] ?? auth('web')->id();

        $post = Post::create($data);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        $this->authorizePostAccess($post);
        $post->load([
            'institution',
            'creator',
            'media' => fn ($q) => $q->orderBy('created_at'),
            'reactions',
            'comments' => fn ($q) => $q->with(['commentable', 'replies.commentable'])->whereNull('parent_id')->latest(),
        ]);

        return view('admin.modules.posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        $this->authorizePostAccess($post);
        $post->load(['institution', 'creator']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $types = Post::TYPES;

        return view('admin.modules.posts.edit', compact('post', 'institutions', 'users', 'types'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('posts/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        if ($data['is_published'] && ! $post->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);

        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function publish(Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);

        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published && ! $post->published_at ? now() : $post->published_at,
        ]);

        $msg = $post->is_published ? 'Post published.' : 'Post unpublished.';

        return back()->with('success', $msg);
    }

    public function feature(Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);
        $post->update(['is_featured' => ! $post->is_featured]);

        $msg = $post->is_featured ? 'Post marked as featured.' : 'Post unfeatured.';

        return back()->with('success', $msg);
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

    private function authorizePostAccess(Post $post): void
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
