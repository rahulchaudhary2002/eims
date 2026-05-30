{{-- Usage: @include('website.partials.post-card', ['post' => $post]) --}}
<div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200 group">
    {{-- Thumbnail --}}
    <div class="relative h-48 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
        @if ($post->thumbnail)
            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
            </div>
        @endif
        <span class="absolute top-4 left-4 bg-[#4299e1] text-white text-xs font-semibold px-4 py-1.5 rounded-full capitalize">
            {{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}
        </span>
    </div>

    <div class="p-7">
        <h3 class="text-xl text-[#2c5aa0] mb-2.5 leading-tight font-bold line-clamp-2 group-hover:text-[#4299e1] transition-colors">
            {{ $post->title }}
        </h3>

        @if ($post->institution)
            <p class="text-xs text-gray-500 mb-2 flex items-center gap-1">
                <i class="fas fa-university text-gray-400"></i>
                {{ $post->institution->name }}
            </p>
        @endif

        @if ($post->content)
            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                {{ strip_tags(Str::limit($post->content, 100)) }}
            </p>
        @endif

        <div class="flex items-center justify-between pt-4 border-t border-gray-200 text-xs text-gray-600">
            <span>{{ $post->published_at?->format('M d, Y') }}</span>
            <a href="{{ route('website.posts.show', $post->slug) }}"
               class="text-[#4299e1] font-semibold hover:text-[#2c5aa0] no-underline">
                Read more →
            </a>
        </div>
    </div>
</div>
