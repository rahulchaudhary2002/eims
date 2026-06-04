<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class InstitutionPostController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Post::class;
        $this->routeBase = 'posts';
        $this->title = 'Post';
        $this->fileFields = ['thumbnail' => 'posts'];
        $this->selectOptions = ['type' => Post::TYPES];
        $this->readOnlyFields = ['created_by'];
        $this->fields = [
            'type' => ['label' => 'Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'title' => ['label' => 'Title', 'rules' => ['required', 'string', 'max:255']],
            'content' => ['label' => 'Content', 'type' => 'textarea', 'rules' => ['required', 'string']],
            'thumbnail' => ['label' => 'Thumbnail', 'type' => 'file', 'rules' => ['nullable', 'image', 'max:4096']],
            'is_published' => ['label' => 'Published', 'type' => 'checkbox', 'rules' => ['nullable']],
            'published_at' => ['label' => 'Published At', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ];
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        $data = parent::forceInstitutionScope($data, $record);
        $data['created_by'] = $record?->created_by ?: auth('web')->id();
        $data['is_featured'] = $record?->is_featured ?: false;

        return $data;
    }
}
