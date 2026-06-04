@extends('website.layouts.app')

@section('meta-title', 'Blog & News - ' . config('app.name'))
@section('meta-description', 'Latest news, articles, and updates from educational institutions.')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5" style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>
    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">Latest Blogs & News</h1>
            <p class="text-[1.2rem] text-white/90 mb-8">Stay updated with institutional announcements, education news, and helpful academic guidance.</p>
            <a href="#post-list" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline"><i class="fas fa-newspaper"></i> Explore Posts</a>
        </div>
    </div>
</section>

<section id="post-list" class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="GET" action="{{ route('website.posts.index') }}">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">Find Updates<span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span></h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">Search posts by topic, type, or institution.</p>
        </div>
        <div class="grid grid-cols-4 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
            <select name="type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                <option value="">All Types</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="institution" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                <option value="">All Institutions</option>
                @foreach ($institutions as $inst)
                    <option value="{{ $inst->id }}" {{ request('institution') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                @endforeach
            </select>
            <button class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>
</section>

<section class="bg-[#f7fafc] py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="text-[1.1rem] text-[#2d3748] mb-8">Showing <strong class="text-[#2c5aa0]">{{ $posts->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $posts->total() }}</strong> posts</div>
        @if ($posts->isEmpty())
            <div class="text-center py-20"><h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No posts found.</h3></div>
        @else
            <div class="grid grid-cols-[repeat(auto-fill,minmax(330px,1fr))] gap-7 mb-12 max-sm:grid-cols-1">
                @foreach ($posts as $post)
                    @include('website.partials.post-card', ['post' => $post])
                @endforeach
            </div>
            @include('website.partials.pagination', ['paginator' => $posts])
        @endif
    </div>
</section>
@endsection
