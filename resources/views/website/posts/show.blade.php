@extends('website.layouts.app')

@section('meta-title', $post->title . ' - ' . config('app.name'))
@section('meta-description', strip_tags(Str::limit($post->content, 160)))
@if ($post->thumbnail)
    @section('og-image', Storage::url($post->thumbnail))
@endif

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Blog', 'url' => route('website.posts.index')],
                ['label' => Str::limit($post->title, 40)],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-10 items-center mt-12">
            <div>
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold capitalize">
                        <i class="fas fa-tag text-[#4299e1]"></i>
                        {{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}
                    </span>
                    @if ($post->published_at)
                        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="fas fa-calendar text-[#4299e1]"></i>
                            {{ $post->published_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>

                <h1 class="text-[2.2rem] md:text-[3rem] font-bold leading-[1.2] mb-5">{{ $post->title }}</h1>

                @if ($post->institution)
                    <a href="{{ route('website.institutions.show', $post->institution->slug) }}"
                       class="inline-flex items-center gap-3 mt-3 text-white hover:text-[#4299e1] transition no-underline">
                        <span class="h-10 w-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center">
                            <i class="fas fa-university text-[#4299e1]"></i>
                        </span>
                        <span>
                            <span class="block text-sm text-white/70">Posted by</span>
                            <span class="font-bold">{{ $post->institution->name }}</span>
                        </span>
                    </a>
                @endif
            </div>

            @if ($post->thumbnail)
                <div class="rounded-xl overflow-hidden shadow-[0_15px_40px_rgba(0,0,0,0.25)] border border-white/10">
                    <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}"
                         class="w-full h-56 md:h-72 object-cover">
                </div>
            @else
                <div class="bg-white/10 border border-white/20 rounded-xl p-10 flex items-center justify-center shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                    <i class="fas fa-newspaper text-white/30 text-7xl"></i>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_300px] gap-8 items-start">
            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-6">
                        <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Article</h2>
                    </div>
                    <div class="ck-content">
                        {!! $post->content !!}
                    </div>
                </div>

                {{-- Reactions Bar --}}
                <div class="bg-white rounded-xl shadow border border-gray-200 p-5">
                    <div class="flex items-center gap-3 flex-wrap">
                        @auth('student')
                        @php
                            $student = auth('student')->user();
                            $myReaction = $post->reactions->where('reactable_type', App\Models\Student::class)->where('reactable_id', $student->id)->first();
                        @endphp
                        @foreach(\App\Models\PostReaction::REACTIONS as $key => $label)
                        <form action="{{ route('student.posts.react', $post) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="reaction" value="{{ $key }}">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium border transition {{ ($myReaction && $myReaction->reaction === $key) ? 'bg-[#4299e1] text-white border-[#4299e1]' : 'bg-gray-50 text-gray-600 border-gray-200 hover:border-[#4299e1] hover:text-[#4299e1]' }}">
                                {{ ['like'=>'👍','love'=>'❤️','celebrate'=>'🎉','insightful'=>'💡','curious'=>'🤔'][$key] }} {{ $label }}
                            </button>
                        </form>
                        @endforeach
                        @else
                        <p class="text-sm text-gray-500"><a href="{{ route('student.login') }}" class="text-[#4299e1] font-semibold">Log in</a> to react</p>
                        @endauth
                        <span class="ml-auto text-sm text-gray-400">{{ $post->reactions->count() }} reaction{{ $post->reactions->count() !== 1 ? 's' : '' }}</span>
                    </div>
                </div>

                {{-- Share Bar --}}
                <div id="share-to-chat" class="bg-white rounded-xl shadow border border-gray-200 p-5" x-data="{ shareOpen: false }">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-sm font-semibold text-slate-600 flex items-center gap-1.5"><i class="fas fa-share-alt text-[#4299e1]"></i> Share</span>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('website.posts.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-[#25d366]/10 text-[#128c3e] border border-[#25d366]/30 hover:bg-[#25d366]/20 transition no-underline">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('website.posts.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-[#1877f2]/10 text-[#1877f2] border border-[#1877f2]/30 hover:bg-[#1877f2]/20 transition no-underline">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>

                        {{-- X / Twitter --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('website.posts.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 transition no-underline">
                            <i class="fab fa-x-twitter"></i> X
                        </a>

                        {{-- LinkedIn --}}
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('website.posts.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-[#0a66c2]/10 text-[#0a66c2] border border-[#0a66c2]/30 hover:bg-[#0a66c2]/20 transition no-underline">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>

                        {{-- Copy Link --}}
                        <button onclick="navigator.clipboard.writeText('{{ route('website.posts.show', $post->slug) }}').then(()=>{ this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'; setTimeout(()=>{ this.innerHTML='<i class=\'fas fa-link\'></i> Copy Link'; },2000); })"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 transition">
                            <i class="fas fa-link"></i> Copy Link
                        </button>

                        {{-- Share to Chat --}}
                        @auth('student')
                        <button @click="shareOpen = !shareOpen"
                                class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-[#4299e1]/10 text-[#2c5aa0] border border-[#4299e1]/30 hover:bg-[#4299e1]/20 transition">
                            <i class="fas fa-paper-plane"></i> Share to Chat
                        </button>
                        @endauth
                    </div>

                    {{-- Share to Chat panel --}}
                    @auth('student')
                    @php $studentConversations = auth('student')->user()->conversations()->with('institution')->get(); @endphp
                    <div x-show="shareOpen" x-cloak x-transition class="mt-4 border-t border-gray-100 pt-4">
                        @if($studentConversations->isEmpty())
                            <p class="text-sm text-gray-400">You have no active conversations. <a href="{{ route('student.conversations.index') }}" class="text-[#4299e1]">Start one</a>.</p>
                        @else
                        <form action="{{ route('student.posts.share-to-chat', $post) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Select Conversation</label>
                                <select name="conversation_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition" required>
                                    <option value="">Choose a conversation…</option>
                                    @foreach($studentConversations as $conv)
                                        <option value="{{ $conv->id }}">{{ $conv->institution?->name ?? 'Conversation #'.$conv->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Add a note (optional)</label>
                                <input type="text" name="note" placeholder="Hey, check this out!" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#4299e1] text-white font-semibold text-sm hover:bg-[#2c5aa0] transition">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>
                        @endif
                    </div>
                    @endauth
                </div>

                {{-- Comments Section --}}
                <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-[#2c5aa0] mb-5">Comments ({{ $post->comments->count() }})</h3>

                    @auth('student')
                    <form action="{{ route('student.posts.comments.store', $post) }}" method="POST" class="mb-6">
                        @csrf
                        <textarea name="comment" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition" placeholder="Write a comment...">{{ old('comment') }}</textarea>
                        @error('comment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        <button type="submit" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#4299e1] text-white font-semibold text-sm hover:bg-[#2c5aa0] transition">Post Comment</button>
                    </form>
                    @endauth

                    @forelse($post->comments->whereNull('parent_id') as $comment)
                    <div class="flex gap-3 mb-4">
                        <div class="w-9 h-9 rounded-full bg-[#4299e1]/10 flex items-center justify-center shrink-0 text-[#2c5aa0] font-bold text-sm">
                            {{ strtoupper(substr($comment->commentable?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-xl px-4 py-3">
                                <p class="text-sm font-semibold text-gray-800">{{ $comment->commentable?->name ?? 'User' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $comment->comment }}</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-1 ml-2">{{ $comment->created_at->diffForHumans() }}</p>
                            @auth('student')
                            @if($comment->commentable_type === App\Models\Student::class && $comment->commentable_id === auth('student')->id())
                            <form action="{{ route('student.comments.destroy', $comment) }}" method="POST" class="inline ml-2">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                            </form>
                            @endif
                            @endauth
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-6">No comments yet. Be the first!</p>
                    @endforelse
                </div>

                @if ($post->media->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Gallery</h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($post->media as $media)
                                @if (str_starts_with($media->mime_type ?? '', 'image') || isset($media->file_path))
                                    <div class="rounded-xl overflow-hidden h-36 border border-gray-100">
                                        <img src="{{ Storage::url($media->file_path) }}"
                                             alt="Media" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($related->isNotEmpty())
                    <div>
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Related Posts</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($related as $rel)
                                @include('website.partials.post-card', ['post' => $rel])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                @if ($post->institution)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">About Institution</h3>
                        <div class="flex items-start gap-3 mb-4">
                            <div class="h-12 w-12 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if ($post->institution->logo)
                                    <img src="{{ Storage::url($post->institution->logo) }}" alt="{{ $post->institution->name }}" class="h-full w-full object-contain p-1">
                                @else
                                    <i class="fas fa-university text-[#2c5aa0]"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $post->institution->name }}</h4>
                                @if ($post->institution->city)
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $post->institution->city }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('website.institutions.show', $post->institution->slug) }}"
                           class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition no-underline">
                            <i class="fas fa-university"></i> View Institution
                        </a>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Explore More</h3>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Discover programs, scholarships, and institutions tailored to your goals.</p>
                    <div class="space-y-3">
                        <a href="{{ route('website.programs.index') }}"
                           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl transition no-underline">
                            <i class="fas fa-book-open text-[#4299e1]"></i> Browse Programs
                        </a>
                        <a href="{{ route('website.scholarships.index') }}"
                           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl transition no-underline">
                            <i class="fas fa-award text-[#4299e1]"></i> Scholarships
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-3">Have Questions?</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-5">Our team is here to help with admissions, programs, or scholarships.</p>
                    <a href="{{ route('website.inquiry.create') }}"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-white border-2 border-[#4299e1] text-[#2c5aa0] font-bold rounded-xl hover:bg-[#4299e1]/10 transition no-underline">
                        <i class="fas fa-paper-plane"></i> Submit Inquiry
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
