@extends('layouts.app')

@section('title', 'Community Forum')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="flex flex-col lg:flex-row gap-8">

    {{-- Sidebar Categories --}}
    <aside class="w-full lg:w-64">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Categories</h2>

            <nav class="space-y-1 text-sm">
                <a href="{{ route('forum.question.index', array_merge(request()->except('category'), ['category' => null])) }}"
                    class="flex items-center px-3 py-2 rounded-lg
                    {{ !$activeCategory ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    All Categories
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('forum.question.index', array_merge(request()->except('page'), ['category' => $cat->value])) }}"
                    class="flex items-center px-3 py-2 rounded-lg
                        {{ $activeCategory === $cat->value ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    {{ $cat->label() }}
                </a>
                @endforeach
            </nav>
        </div>
    </aside>

    {{-- Main content --}}
    <section class="flex-1 space-y-4">
        {{-- Header row with Ask Question button --}}
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold text-gray-900">Community Forum</h1>

            @auth
            <a href="{{ route('forum.question.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Ask Question
            </a>
            @else
            <a href="{{ route('forum.question.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-medium hover:bg-indigo-100">
                Log in to ask a question
            </a>
            @endauth
        </div>

        {{-- Tabs --}}
        <div class="flex items-center justify-between">
            <div class="flex space-x-4 text-sm font-medium">
                @php
                $tabs = [
                'latest' => 'Latest',
                'trending' => 'Trending',
                'my_posts' => 'My Posts',
                'my_drafts' => 'My Drafts',
                ];
                @endphp

                @foreach($tabs as $value => $label)
                <a href="{{ route('forum.question.index', array_merge(request()->except('page'), ['tab' => $value])) }}"
                    class="pb-2 border-b-2
                        {{ $activeTab === $value ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Question list --}}
        <div class="space-y-4">
            @forelse($questions as $question)
            <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex justify-between gap-4">
                    <div>
                        <a href="{{ route('forum.question.show', $question) }}"
                            class="text-lg font-semibold text-gray-900 hover:text-indigo-700">
                            {{ $question->title }}
                        </a>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ Str::limit($question->body, 160) }}
                        </p>

                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                                {{ $question->category->label() }}
                            </span>

                            <span>•</span>

                            <span>
                                @if($question->is_anonymous)
                                Anonymous User
                                @else
                                {{ $question->user->name ?? 'User' }}
                                @endif
                            </span>

                            <span>• {{ $question->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-end justify-between text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <span class="h-6 w-6 rounded-full border border-gray-300 flex items-center justify-center text-[10px] font-semibold">
                                {{ strtoupper(substr($question->is_anonymous
                                        ? 'A'
                                        : ($question->user->name ?? 'U'), 0, 1)) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4 mt-4 lg:mt-0">
                            <div class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                <span>{{ $question->views_count }} views</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                <span>{{ $question->replies_count }} replies</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">
                No questions found.
            </div>
            @endforelse
        </div>

        <div>
            {{ $questions->links() }}
        </div>
    </section>
</div>
@endsection