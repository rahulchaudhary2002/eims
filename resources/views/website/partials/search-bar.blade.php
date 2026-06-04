{{-- Hero Search Bar --}}
<div class="bg-white rounded-2xl shadow-lg p-2 flex flex-col sm:flex-row gap-2" x-data="{ type: 'institutions' }">
    <div class="flex items-center gap-2 px-3 py-2 border-r border-gray-200 hidden sm:flex">
        <select x-model="type"
                class="text-sm font-medium text-gray-700 bg-transparent border-none outline-none cursor-pointer pr-2">
            <option value="institutions">Institutions</option>
            <option value="programs">Programs</option>
            <option value="scholarships">Scholarships</option>
        </select>
    </div>
    <div class="flex-1 relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text"
               id="hero-search-input"
               placeholder="Search institutions, programs, scholarships..."
               class="w-full pl-9 pr-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-transparent border-none outline-none">
    </div>
    <button onclick="performHeroSearch()"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2">
        <i class="fas fa-search"></i>
        <span>Search</span>
    </button>
</div>

@push('scripts')
<script>
function performHeroSearch() {
    const input = document.getElementById('hero-search-input');
    const query = input?.value?.trim();
    const typeEl = document.querySelector('select[x-model="type"]');
    const type = typeEl?.value || 'institutions';

    const routes = {
        institutions: '{{ route("website.institutions.index") }}',
        programs: '{{ route("website.programs.index") }}',
        scholarships: '{{ route("website.scholarships.index") }}',
    };

    const url = new URL(routes[type] || routes.institutions);
    if (query) url.searchParams.set('search', query);
    window.location.href = url.toString();
}

document.getElementById('hero-search-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performHeroSearch();
});
</script>
@endpush
