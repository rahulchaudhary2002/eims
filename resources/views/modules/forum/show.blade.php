@extends('layouts.app')

@section('title', $question->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    {{-- Left sidebar: categories --}}
    <aside class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-800 mb-3 uppercase tracking-wide">Categories</h2>
            <div class="space-y-1 text-sm">
                @foreach($categories as $cat)
                <a href="{{ route('forum.question.index', ['category' => $cat->value]) }}"
                    class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                    {{ $cat->label() }}
                </a>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="lg:col-span-3 space-y-6">
        {{-- Header row with Ask Question button --}}
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold text-gray-900">Community Forum</h1>
        </div>

        {{-- Question Card --}}
        <article class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $question->title }}</h1>

                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 font-medium">
                            {{ $question->category->label() }}
                        </span>
                        <span>•</span>
                        <span>{{ $question->is_anonymous ? 'Anonymous User' : $question->user->name ?? 'User' }}</span>
                        <span>• {{ $question->created_at->diffForHumans() }}</span>
                        <span>• {{ $question->views_count }} views</span>
                        <span>• {{ $question->replies_count }} replies</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-gray-800 text-sm leading-relaxed whitespace-pre-line">
                {{ $question->body }}
            </div>
        </article>

        {{-- Reply Form --}}
        @auth
        <section class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-3 uppercase tracking-wide">Add a Reply</h2>
            <form method="POST" action="{{ route('forum.reply.store', $question) }}" class="space-y-3">
                @csrf
                <textarea name="body" rows="4"
                    class="block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-3"
                    placeholder="Write your reply..." required>{{ old('body') }}</textarea>
                @error('body')
                <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror

                <label class="inline-flex items-center mt-1">
                    <input type="checkbox" name="is_anonymous" value="1"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        {{ old('is_anonymous') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Reply as anonymous</span>
                </label>

                <div class="flex justify-end">
                    <button class="inline-flex items-center px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shadow-md">
                        Post Reply
                    </button>
                </div>
            </form>
        </section>
        @endauth

        {{-- Replies List --}}
        <section class="space-y-4">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Replies</h2>

            @include('modules.forum.partials.reply', [
            'replies' => $question->replies->whereNull('parent_id'),
            'level' => 1,
            'question' => $question
            ])

            @if($question->replies->whereNull('parent_id')->isEmpty())
            <p class="text-sm text-gray-500">No replies yet. Be the first to reply.</p>
            @endif
        </section>
    </div>
</div>
@endsection