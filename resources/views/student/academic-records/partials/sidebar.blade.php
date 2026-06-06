<aside class="space-y-6">

    {{-- Latest Posts --}}
    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-newspaper text-[#4299e1]"></i> Latest Posts
            </h3>
            <a href="{{ route('website.posts.index') }}" class="text-xs text-[#4299e1] hover:underline font-semibold no-underline">View all</a>
        </div>
        @forelse($latestPosts as $post)
        <a href="{{ route('website.posts.show', $post) }}" class="flex gap-3 px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition no-underline group last:border-0">
            @if(storage_exists($post->thumbnail))
                <img src="{{ storage_url($post->thumbnail) }}" alt="" class="w-14 h-14 rounded-lg object-cover shrink-0">
            @else
                <div class="w-14 h-14 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <i class="fas fa-newspaper text-[#4299e1]"></i>
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800 group-hover:text-[#4299e1] transition line-clamp-2 leading-snug">{{ $post->title }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    <span class="capitalize">{{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}</span>
                    · {{ $post->published_at?->diffForHumans() ?? '-' }}
                </p>
            </div>
        </a>
        @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">No posts yet.</div>
        @endforelse
    </div>

    {{-- Featured Programs --}}
    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-book-open text-[#4299e1]"></i> Featured Programs
            </h3>
            <a href="{{ route('website.programs.index') }}" class="text-xs text-[#4299e1] hover:underline font-semibold no-underline">View all</a>
        </div>
        @forelse($openPrograms as $ip)
        <a href="{{ route('website.colleges.programs', ['institution' => $ip->institution, 'institutionProgram' => $ip]) }}" class="flex items-center gap-3 px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition no-underline group last:border-0">
            <div class="w-10 h-10 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                <i class="fas fa-book text-[#4299e1] text-sm"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-800 group-hover:text-[#4299e1] transition truncate">{{ $ip->title ?: $ip->program?->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $ip->institution?->name }}</p>
            </div>
            <span class="ml-auto text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full shrink-0">Featured</span>
        </a>
        @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">No featured programs.</div>
        @endforelse
    </div>

    {{-- Featured Institutions --}}
    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-star text-yellow-400"></i> Featured Institutions
            </h3>
            <a href="{{ route('website.institutions.index') }}" class="text-xs text-[#4299e1] hover:underline font-semibold no-underline">View all</a>
        </div>
        @forelse($featuredInstitutions as $institution)
        <a href="{{ route('website.institutions.show', $institution) }}" class="flex items-center gap-3 px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition no-underline group last:border-0">
            @if(storage_exists($institution->logo))
                <img src="{{ storage_url($institution->logo) }}" alt="" class="w-12 h-12 rounded-lg object-contain border border-gray-100 shrink-0 p-1">
            @else
                <div class="w-12 h-12 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <i class="fas fa-university text-[#4299e1]"></i>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-800 group-hover:text-[#4299e1] transition truncate">{{ $institution->name }}</p>
                @if($institution->city || $institution->district)
                <p class="text-xs text-gray-400 mt-0.5 truncate">
                    <i class="fas fa-map-marker-alt text-xs mr-1"></i>{{ $institution->city ?? $institution->district }}
                </p>
                @endif
            </div>
            <span class="ml-auto text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full shrink-0">Featured</span>
        </a>
        @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">No featured institutions.</div>
        @endforelse
    </div>

</aside>
