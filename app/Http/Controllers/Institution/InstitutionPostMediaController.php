<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionPostMediaController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = PostMedia::class;
        $this->routeBase = 'post-media';
        $this->title = 'Post Media';
        $this->fileFields = ['file_path' => 'post-media'];
        $this->relationships = ['post'];
        $this->selectOptions = ['type' => PostMedia::TYPES];
        $this->fields = [
            'post_id' => ['label' => 'Post', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:posts,id']],
            'type' => ['label' => 'Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'file_path' => ['label' => 'File', 'type' => 'file', 'rules' => ['nullable', 'file', 'max:20480']],
            'caption' => ['label' => 'Caption', 'rules' => ['nullable', 'string', 'max:255']],
        ];
    }

    protected function resourceQuery(): Builder
    {
        return PostMedia::query()->whereHas('post', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(Post::where('institution_id', $this->activeInstitutionId())->whereKey($data['post_id'] ?? null)->exists(), 403);

        return $data;
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'post_id' => Post::where('institution_id', $this->activeInstitutionId())->latest()->pluck('title', 'id')->all(),
        ]);
    }
}
