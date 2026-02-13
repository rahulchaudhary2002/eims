@if ($collections->hasPages())
<div class="flex items-center justify-center gap-2 mt-12">
    {{-- Previous Page Link --}}
    @if ($collections->onFirstPage())
        <button class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark opacity-50 cursor-not-allowed flex items-center justify-center">
            <i class="fas fa-chevron-left"></i>
        </button>
    @else
        <a href="{{ $collections->appends(request()->query())->previousPageUrl() }}"
           class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">
            <i class="fas fa-chevron-left"></i>
        </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($collections->getUrlRange(1, $collections->lastPage()) as $page => $url)
        @php
            $url = $collections->appends(request()->query())->url($page);
        @endphp

        @if ($collections->lastPage() > 5)
            @if ($page == 1 || $page == $collections->lastPage() || abs($page - $collections->currentPage()) <= 1)
                @if ($page == $collections->currentPage())
                    <button class="w-10 h-10 rounded-full border border-[#4299e1] bg-[#4299e1] text-white flex items-center justify-center">{{ $page }}</button>
                @else
                    <a href="{{ $url }}"
                       class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">{{ $page }}</a>
                @endif

            @elseif ($page == 2 && $collections->currentPage() > 3)
                <span class="w-10 h-10 flex items-center justify-center px-2 text-grayx">...</span>

            @elseif ($page == $collections->lastPage() - 1 && $collections->currentPage() < $collections->lastPage() - 2)
                <span class="w-10 h-10 flex items-center justify-center px-2 text-grayx">...</span>
            @endif
        @else
            @if ($page == $collections->currentPage())
                <button class="w-10 h-10 rounded-full border border-[#4299e1] bg-[#4299e1] text-white flex items-center justify-center">{{ $page }}</button>
            @else
                <a href="{{ $url }}"
                   class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">{{ $page }}</a>
            @endif
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($collections->hasMorePages())
        <a href="{{ $collections->appends(request()->query())->nextPageUrl() }}"
           class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">
            <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <button class="w-10 h-10 rounded-full border border-lightgray bg-white text-dark opacity-50 cursor-not-allowed flex items-center justify-center">
            <i class="fas fa-chevron-right"></i>
        </button>
    @endif
</div>
@endif
