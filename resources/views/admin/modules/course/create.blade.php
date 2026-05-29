@extends('admin.layouts.app')
@section('title', 'Create Course')
@section('page-title', 'Create Course')

@section('page-specific-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    .choices { margin-bottom: 0; }
    .choices__inner { background-color: #ffffff !important; border-radius: var(--input-radius) !important; border: 1px solid var(--border) !important; min-height: 46px; }
    .choices__list--multiple .choices__item { background-color: var(--primary) !important; border: 1px solid var(--primary-dark) !important; border-radius: 6px !important; }
    .choices__list--multiple .choices__item.is-highlighted { background-color: var(--primary-dark) !important; }
    .choices__input { background-color: transparent !important; }
    .is-focused .choices__inner { border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--primary-light) !important; }
</style>
@endsection

@section('content')
<div class="space-y-5">
    <x-admin.page-header title="Create Course" subtitle="Add a new course with sections"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Courses','route'=>'admin.course.index'],['label'=>'Create']]">
        <x-slot:actions>
            <a href="{{ route('admin.course.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if($errors->any())
    <div class="alert alert-danger">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <x-admin.form-card title="Course Details">
        <form action="{{ route('admin.course.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-input name="name" label="Course Name" placeholder="Enter course name" required />
                <x-admin.form-input name="code" label="Course Code" placeholder="Enter course code" required />
                <div class="md:col-span-2">
                    <label class="form-label" for="program_ids">Programs <span class="text-danger-600">*</span></label>
                    <select name="program_ids[]" id="program_ids" multiple
                        class="form-control @error('program_ids') border-red-400 @enderror" required>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ in_array($program->id, old('program_ids', [])) ? 'selected' : '' }}>{{ $program->name }}</option>
                        @endforeach
                    </select>
                    @error('program_ids')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2 flex items-center gap-3 pt-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                    <label for="is_active" class="form-label mb-0 cursor-pointer">Mark as Active</label>
                </div>
            </div>

            {{-- Sections --}}
            <div class="mt-6" x-data="sectionsForm()" x-init="initEditors()">
                <label class="form-label mb-3">Course Sections</label>
                <template x-for="(section, index) in sections" :key="index">
                    <div class="bg-slate-50 p-4 rounded-input border border-slate-200 mb-4 flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-slate-700">Section <span x-text="index+1"></span></span>
                            <div class="flex gap-1.5">
                                <button type="button" @click="moveUp(index)" class="btn-icon" title="Move Up">↑</button>
                                <button type="button" @click="moveDown(index)" class="btn-icon" title="Move Down">↓</button>
                                <button type="button" @click="remove(index)" class="btn-icon btn-icon-delete" title="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <input type="text" :name="'sections['+index+'][title]'" x-model="section.title"
                            placeholder="Section Title" class="form-control">
                        <textarea :id="'content-'+index" :name="'sections['+index+'][content]'" x-model="section.content"
                            placeholder="Section Content" class="form-control"></textarea>
                    </div>
                </template>
                <button type="button" @click="add()" class="btn btn-secondary text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Section
                </button>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.course.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Create Course
                </button>
            </div>
        </form>
    </x-admin.form-card>
</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    function sectionsForm(initial = []) {
        return {
            sections: initial.length ? initial : [{
                title: '',
                content: '',
                order: 0
            }],
            add() {
                this.sections.push({
                    title: '',
                    content: '',
                    order: this.sections.length
                });
                this.$nextTick(() => this.initEditors());
            },
            remove(index) {
                // remove tinymce instance first
                const id = 'content-' + index;
                if (tinymce.get(id)) tinymce.get(id).remove();
                this.sections.splice(index, 1);
            },
            moveUp(index) {
                if (index === 0) return;
                [this.sections[index - 1], this.sections[index]] = [this.sections[index], this.sections[index - 1]];
                this.$nextTick(() => this.initEditors());
            },
            moveDown(index) {
                if (index === this.sections.length - 1) return;
                [this.sections[index + 1], this.sections[index]] = [this.sections[index], this.sections[index + 1]];
                this.$nextTick(() => this.initEditors());
            },
            initEditors() {
                // Destroy all existing TinyMCE editors first
                this.sections.forEach((_, index) => {
                    const id = 'content-' + index;
                    if (tinymce.get(id)) tinymce.get(id).remove();
                });

                // Initialize TinyMCE for each section
                this.$nextTick(() => {
                    this.sections.forEach((section, index) => {
                        const id = 'content-' + index;
                        if (!tinymce.get(id)) {
                            tinymce.init({
                                selector: '#' + id,
                                height: 500,
                                menubar: false,
                                plugins: 'lists link image table code',
                                toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
                                setup: (editor) => {
                                    editor.on('Change KeyUp', () => {
                                        section.content = editor.getContent();
                                    });
                                }
                            });
                        }
                    });
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const programIdsElement = document.getElementById('program_ids');
        if (programIdsElement) {
            new Choices(programIdsElement, {
                removeItemButton: true,
                placeholderValue: 'Select programs...',
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No programs found',
            });
        }
    });
</script>
@endsection
