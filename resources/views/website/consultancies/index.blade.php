@extends('website.layouts.app')

@section('meta-title', 'Consultancies - ' . config('app.name'))
@section('meta-description', 'Find top education consultancies for visa assistance, study abroad guidance, and more.')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5" style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>
    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">Find Education Consultancies</h1>
            <p class="text-[1.2rem] text-white/90 mb-8">Connect with consultancies for visa assistance, study abroad guidance, test preparation, and admissions support.</p>
            <a href="#consultancy-list" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline"><i class="fas fa-handshake"></i> Explore Consultancies</a>
        </div>
    </div>
</section>

<section id="consultancy-list" class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="GET" action="{{ route('website.consultancies.index') }}">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">Find Your Consultancy<span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span></h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">Search by location, destination country, and service type.</p>
        </div>
        <div class="grid grid-cols-4 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or city..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
            <select name="province" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                <option value="">All Provinces</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                @endforeach
            </select>
            <select name="destination" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                <option value="">All Countries</option>
                @foreach ($destinations as $dest)
                    <option value="{{ $dest }}" {{ request('destination') == $dest ? 'selected' : '' }}>{{ $dest }}</option>
                @endforeach
            </select>
            <button class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>
</section>

<section class="bg-[#f7fafc] py-20">
<div class="max-w-[1200px] mx-auto px-5">

    <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">
        <aside>
            <form method="GET" action="{{ route('website.consultancies.index') }}" class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-200 h-fit sticky top-[120px]">
                <div class="p-6">
                <h3 class="text-[1.2rem] font-bold text-[#2c5aa0] mb-4">Services</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[0.95rem] font-semibold text-[#2d3748] mb-2">Service Type</label>
                        <select name="service" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                            <option value="">All Services</option>
                            @foreach ($serviceTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('service') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-5 rounded-b-xl">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">Apply Filters</button>
                </div>
            </form>
        </aside>

        <div class="flex-1 min-w-0">
            <div class="text-[1.1rem] text-[#2d3748] mb-8">Showing <strong class="text-[#2c5aa0]">{{ $consultancies->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $consultancies->total() }}</strong> consultancies</div>
            @if ($consultancies->isEmpty())
                <div class="text-center py-20"><h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No consultancies found.</h3></div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                    @foreach ($consultancies as $consultancy)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-7 hover:-translate-y-1.5 hover:shadow-2xl transition-all">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-16 h-16 rounded-xl border border-gray-200 bg-white flex items-center justify-center flex-shrink-0 overflow-hidden shadow-sm">
                                    @if ($consultancy->logo)
                                        <img src="{{ Storage::url($consultancy->logo) }}" alt="{{ $consultancy->name }}" class="w-full h-full object-contain p-1.5">
                                    @else
                                        <i class="fas fa-handshake text-[#4299e1] text-2xl"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="inline-block px-4 py-1 bg-teal-500/10 text-teal-500 rounded-full text-[0.85rem] font-semibold mb-2">
                                        Consultancy
                                    </span>
                                    <h3 class="text-[1.4rem] font-bold text-[#2c5aa0] mb-1 leading-snug">{{ $consultancy->name }}</h3>
                                    <p class="text-[0.95rem] text-gray-600 flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-[#4299e1]"></i>
                                        {{ $consultancy->city ?? $consultancy->province ?? 'Nepal' }}
                                    </p>
                                </div>
                            </div>

                            @if ($consultancy->short_description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $consultancy->short_description }}</p>
                            @endif

                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach ($consultancy->consultancyServices->take(3) as $svc)
                                    <span class="bg-[#4299e1]/10 text-[#4299e1] text-xs px-3 py-1 rounded-full font-semibold">
                                        {{ \App\Models\ConsultancyService::SERVICE_TYPES[$svc->service_type] ?? $svc->service_type }}
                                    </span>
                                @endforeach
                            </div>

                            @if ($consultancy->consultancy_destinations_count > 0)
                                <p class="text-sm text-gray-600 mb-4">
                                    <i class="fas fa-globe text-green-400 mr-1"></i>
                                    {{ $consultancy->consultancy_destinations_count }} destination{{ $consultancy->consultancy_destinations_count > 1 ? 's' : '' }}
                                </p>
                            @endif

                            <a href="{{ route('website.consultancies.show', $consultancy->slug) }}"
                               class="block text-center w-full px-4 py-2 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition no-underline">
                                View Details
                            </a>
                        </div>
                    @endforeach
                </div>
                @include('website.partials.pagination', ['paginator' => $consultancies])
            @endif
        </div>
    </div>
</div>
</section>
@endsection
