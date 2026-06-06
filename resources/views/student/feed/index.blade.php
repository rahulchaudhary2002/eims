@extends('layouts.student')

@section('title', 'My Feed')

@section('content')

@php
    $reactionEmojis = ['like' => '👍', 'love' => '❤️', 'celebrate' => '🎉', 'insightful' => '💡', 'curious' => '🤔'];
@endphp

{{-- Hero --}}
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-newspaper text-[#4299e1]"></i> My Feed
                </h1>
                <p class="text-white/70 text-sm mt-1">Latest updates from institutions you follow</p>
            </div>
            <a href="{{ route('website.institutions.index') }}"
               class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm no-underline shrink-0">
                <i class="fas fa-search"></i> Browse Institutions
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2 mb-6">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="grid lg:grid-cols-[minmax(0,1fr)_300px] gap-8 items-start">

            {{-- Feed Posts --}}
            <div class="space-y-6">

                @if($followedIds->isEmpty())
                {{-- Empty: not following anyone --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-[#ebf8ff] flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-rss text-4xl text-[#4299e1]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Your feed is empty</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Follow institutions to see their latest news, events, admission notices, and more right here.</p>
                    <a href="{{ route('website.institutions.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white font-bold rounded-xl hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                        <i class="fas fa-search"></i> Browse Institutions
                    </a>
                </div>

                @elseif($posts->isEmpty())
                {{-- Following institutions but no posts --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-[#ebf8ff] flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-inbox text-4xl text-[#4299e1]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No posts yet</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">The institutions you follow haven't published any posts yet. Check back soon!</p>
                    <a href="{{ route('website.institutions.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white font-bold rounded-xl hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                        <i class="fas fa-plus-circle"></i> Follow More Institutions
                    </a>
                </div>

                @else

                @foreach($posts as $post)
                @php
                    $typeColors = [
                        'article'           => 'bg-blue-100 text-blue-700',
                        'news'              => 'bg-indigo-100 text-indigo-700',
                        'announcement'      => 'bg-orange-100 text-orange-700',
                        'event_recap'       => 'bg-pink-100 text-pink-700',
                        'blog'              => 'bg-teal-100 text-teal-700',
                        'event'             => 'bg-purple-100 text-purple-700',
                        'admission_notice'  => 'bg-green-100 text-green-700',
                        'scholarship_offer' => 'bg-yellow-100 text-yellow-700',
                        'seminar'           => 'bg-cyan-100 text-cyan-700',
                        'campus_update'     => 'bg-gray-100 text-gray-700',
                        'other'             => 'bg-gray-100 text-gray-600',
                    ];
                    $typeColor = $typeColors[$post->type] ?? 'bg-gray-100 text-gray-600';
                    $myReaction = $myReactions[$post->id] ?? null;
                @endphp

                <article class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 overflow-hidden hover:shadow-[0_8px_25px_rgba(0,0,0,0.1)] transition-shadow">

                    {{-- Thumbnail --}}
                    @php $coverImage = $post->thumbnail ? storage_url($post->thumbnail) : ($post->media->where('type', 'image')->first()?->file_path ? storage_url($post->media->where('type', 'image')->first()->file_path) : null); @endphp
                    @if($coverImage)
                    <div class="h-48 overflow-hidden">
                        <img src="{{ $coverImage }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif

                    <div class="p-6">

                        {{-- Institution + Meta --}}
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <a href="{{ route('website.institutions.show', $post->institution->slug) }}"
                               class="flex items-center gap-2.5 no-underline group">
                                @if(storage_exists($post->institution->logo))
                                    <img src="{{ storage_url($post->institution->logo) }}" alt="{{ $post->institution->name }}"
                                         class="w-9 h-9 rounded-lg object-contain border border-gray-100 p-0.5 bg-white">
                                @else
                                    <div class="w-9 h-9 rounded-lg bg-[#ebf8ff] flex items-center justify-center border border-[#bee3f8]">
                                        <span class="text-[#2c5aa0] text-sm font-bold">{{ strtoupper(substr($post->institution->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-[#2c5aa0] transition">{{ $post->institution->name }}</span>
                            </a>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $typeColor }}">
                                    {{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}
                                </span>
                                @if($post->published_at)
                                <span class="text-xs text-gray-400 hidden sm:block">{{ $post->published_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Title & Excerpt --}}
                        <h2 class="text-lg font-bold text-gray-900 mb-2 leading-snug">
                            <a href="{{ route('website.posts.show', $post->slug) }}" class="hover:text-[#2c5aa0] transition no-underline">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @php $excerpt = Str::limit(strip_tags($post->content), 180); @endphp
                        @if($excerpt)
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $excerpt }}</p>
                        @endif

                        {{-- Reaction Bar --}}
                        <div class="border-t border-gray-100 pt-4 mt-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach(\App\Models\PostReaction::REACTIONS as $key => $label)
                                <form action="{{ route('student.posts.react', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction" value="{{ $key }}">
                                    <button type="submit"
                                            title="{{ $label }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-xs font-medium border transition-all
                                                   {{ ($myReaction === $key) ? 'bg-[#4299e1] text-white border-[#4299e1] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:border-[#4299e1] hover:text-[#4299e1] hover:bg-[#ebf8ff]' }}">
                                        {{ $reactionEmojis[$key] }} <span class="hidden sm:inline">{{ $label }}</span>
                                    </button>
                                </form>
                                @endforeach

                                <div class="ml-auto flex items-center gap-2 text-xs text-gray-400 flex-wrap">
                                    @if($post->reactions_count)
                                    <span><i class="fas fa-smile mr-1"></i>{{ $post->reactions_count }}</span>
                                    @endif
                                    <span><i class="fas fa-comment mr-1"></i>{{ $post->comments_count }}</span>

                                    {{-- Share dropdown (fixed-position to escape overflow:hidden) --}}
                                    <div x-data="{
                                            open: false,
                                            x: 0, y: 0,
                                            toggle($el) {
                                                const r = $el.getBoundingClientRect();
                                                this.x = r.right - 208;
                                                this.y = r.top - 4;
                                                if (this.x < 8) this.x = 8;
                                                this.open = !this.open;
                                            }
                                        }">
                                        <button @click="toggle($el)" class="inline-flex items-center gap-1 text-gray-500 hover:text-[#4299e1] font-semibold transition">
                                            <i class="fas fa-share-alt"></i> Share
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="open" x-cloak
                                                 @click.outside="open = false"
                                                 :style="`position:fixed;left:${x}px;top:${y}px;transform:translateY(-100%);z-index:9999`"
                                                 class="w-52 bg-white border border-gray-200 rounded-xl shadow-xl py-2 text-sm">
                                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('website.posts.show', $post->slug)) }}"
                                                   target="_blank" class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-[#128c3e] no-underline">
                                                    <i class="fab fa-whatsapp w-4"></i> WhatsApp
                                                </a>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('website.posts.show', $post->slug)) }}"
                                                   target="_blank" class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-[#1877f2] no-underline">
                                                    <i class="fab fa-facebook w-4"></i> Facebook
                                                </a>
                                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('website.posts.show', $post->slug)) }}"
                                                   target="_blank" class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-gray-700 no-underline">
                                                    <i class="fab fa-x-twitter w-4"></i> X / Twitter
                                                </a>
                                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('website.posts.show', $post->slug)) }}"
                                                   target="_blank" class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-[#0a66c2] no-underline">
                                                    <i class="fab fa-linkedin w-4"></i> LinkedIn
                                                </a>
                                                <button onclick="navigator.clipboard.writeText('{{ route('website.posts.show', $post->slug) }}').then(()=>{ this.textContent='✓ Copied!'; setTimeout(()=>{ this.innerHTML='<i class=\'fas fa-link\'></i> Copy Link'; },2000); })"
                                                        class="flex w-full items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-gray-600 text-left">
                                                    <i class="fas fa-link w-4"></i> Copy Link
                                                </button>
                                                <div class="border-t border-gray-100 mx-3 my-1"></div>
                                                <a href="{{ route('website.posts.show', $post->slug) }}#share-to-chat"
                                                   class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 text-[#2c5aa0] font-semibold no-underline">
                                                    <i class="fas fa-paper-plane w-4"></i> Share to Chat
                                                </a>
                                            </div>
                                        </template>
                                    </div>

                                    <a href="{{ route('website.posts.show', $post->slug) }}"
                                       class="text-[#4299e1] font-semibold hover:text-[#2c5aa0] transition no-underline ml-1">
                                        Read More <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </article>
                @endforeach

                {{-- Pagination --}}
                @if($posts->hasPages())
                <div class="flex justify-center mt-4">
                    {{ $posts->links() }}
                </div>
                @endif

                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:sticky lg:top-28 space-y-6">

                {{-- Following count --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-[#ebf8ff] flex items-center justify-center">
                            <i class="fas fa-bell text-[#4299e1]"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#2c5aa0]">Following</h3>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $followedIds->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">institution{{ $followedIds->count() !== 1 ? 's' : '' }}</p>
                    <a href="{{ route('website.institutions.index') }}"
                       class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#4299e1] text-white font-semibold text-sm hover:bg-[#2c5aa0] transition no-underline">
                        <i class="fas fa-plus"></i> Follow More
                    </a>
                </div>

                {{-- Quick Tips --}}
                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-300"></i> Stay Updated
                    </h3>
                    <p class="text-sm text-white/80 leading-relaxed mb-4">Follow your favourite institutions to never miss admission notices, events, and scholarship offers.</p>
                    <div class="space-y-2">
                        <a href="{{ route('website.institutions.index') }}"
                           class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition no-underline">
                            <i class="fas fa-university text-[#4299e1]"></i> Browse Institutions
                        </a>
                        <a href="{{ route('website.programs.index') }}"
                           class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition no-underline">
                            <i class="fas fa-book-open text-[#4299e1]"></i> Browse Programs
                        </a>
                        <a href="{{ route('website.scholarships.index') }}"
                           class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition no-underline">
                            <i class="fas fa-award text-[#4299e1]"></i> Scholarships
                        </a>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

@endsection
