<?php

namespace App\Http\Controllers\Institution\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\Str;
use Illuminate\View\View;

trait HandlesInstitutionResources
{
    use UsesActiveInstitution;

    protected string $modelClass;

    protected string $routeBase;

    protected string $viewPath;

    protected string $title;

    protected array $fields = [];

    protected array $readOnlyFields = [];

    protected array $fileFields = [];

    protected array $selectOptions = [];

    protected array $relationships = [];

    public function index(Request $request): View
    {
        $records = $this->resourceQuery()
            ->with($this->indexRelationships())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view($this->resourceView('index'), $this->viewData(compact('records')));
    }

    public function create(): View
    {
        $record = new $this->modelClass;

        return view($this->resourceView('create'), $this->viewData(compact('record')));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data = $this->forceInstitutionScope($data);

        $record = $this->modelClass::query()->create($data);

        return redirect()->route("institution.{$this->routeBase}.show", $record)
            ->with('success', "{$this->title} created successfully.");
    }

    public function show($record): View
    {
        $record = $this->resolveRecord($record);

        return view($this->resourceView('show'), $this->viewData(compact('record')));
    }

    public function edit($record): View
    {
        $record = $this->resolveRecord($record);

        return view($this->resourceView('edit'), $this->viewData(compact('record')));
    }

    public function update(Request $request, $record): RedirectResponse
    {
        $record = $this->resolveRecord($record);
        $data = $this->validatedData($request, $record);
        $data = $this->forceInstitutionScope($data, $record);

        $record->update($data);

        return redirect()->route("institution.{$this->routeBase}.show", $record)
            ->with('success', "{$this->title} updated successfully.");
    }

    public function destroy($record): RedirectResponse
    {
        $record = $this->resolveRecord($record);
        $record->delete();

        return redirect()->route("institution.{$this->routeBase}.index")
            ->with('success', "{$this->title} deleted successfully.");
    }

    protected function resourceQuery(): Builder
    {
        return $this->scopedInstitutionQuery($this->modelClass);
    }

    protected function resolveRecord($record): Model
    {
        if ($record instanceof Model) {
            $record = $this->resourceQuery()->findOrFail($record->getKey());
        } else {
            $model = new $this->modelClass;
            $keyName = $model->getRouteKeyName();
            $record = $this->resourceQuery()->where($keyName, $record)->firstOrFail();
        }

        foreach ($this->relationships as $relationship) {
            $record->loadMissing($relationship);
        }

        return $record;
    }

    protected function indexRelationships(): array
    {
        $fieldRelationships = collect(array_keys($this->fields))
            ->filter(fn (string $field) => str_ends_with($field, '_id'))
            ->map(fn (string $field) => Str::camel(substr($field, 0, -3)))
            ->all();

        $relationships = array_values(array_intersect($this->relationships, $fieldRelationships));

        if (in_array('institutionProgram', $relationships, true)) {
            $relationships[] = 'institutionProgram.program';
        }

        return $relationships;
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        if (Schema::hasColumn((new $this->modelClass)->getTable(), 'institution_id')) {
            $data['institution_id'] = $this->activeInstitutionId();
        }

        return $data;
    }

    protected function validatedData(Request $request, ?Model $record = null): array
    {
        $rules = [];

        foreach ($this->fields as $field => $config) {
            if (in_array($field, $this->readOnlyFields, true) || $field === 'institution_id') {
                continue;
            }

            $rules[$field] = $config['rules'] ?? ['nullable'];
        }

        $data = $request->validate($rules);

        foreach ($this->fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($record && $record->{$field}) {
                    Storage::disk('public')->delete($record->{$field});
                }

                $data[$field] = $request->file($field)->store(
                    "institutions/{$this->activeInstitutionId()}/{$folder}",
                    'public'
                );
            } else {
                unset($data[$field]);
            }
        }

        foreach ($this->fields as $field => $config) {
            if (($config['type'] ?? null) === 'checkbox') {
                $data[$field] = $request->boolean($field);
            }
        }

        return Arr::except($data, ['institution_id']);
    }

    protected function viewData(array $extra = []): array
    {
        return array_merge([
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'fields' => $this->fields,
            'selectOptions' => $this->selectOptions(),
            'readOnlyFields' => $this->readOnlyFields,
            'relationships' => $this->relationships,
            'activeInstitution' => $this->activeInstitution(),
        ], $extra);
    }

    protected function selectOptions(): array
    {
        return $this->selectOptions;
    }

    protected function resourceView(string $view): string
    {
        $moduleView = "institution.modules.{$this->routeBase}.{$view}";

        if (ViewFactory::exists($moduleView)) {
            return $moduleView;
        }

        return match ($view) {
            'index' => 'institution.shared.resource-index',
            'show' => 'institution.shared.resource-show',
            default => 'institution.shared.resource-form',
        };
    }

    protected function simpleTitle(Model $record): string
    {
        foreach (['title', 'name', 'application_number', 'admission_number', 'invoice_number', 'referral_number'] as $field) {
            if ($record->{$field}) {
                return (string) $record->{$field};
            }
        }

        return Str::headline(class_basename($record)).' #'.$record->getKey();
    }
}
