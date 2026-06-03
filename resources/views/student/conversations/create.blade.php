@extends('layouts.student')

@section('title', 'New Conversation')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.conversations.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Start New Conversation</h1>
                <p class="text-white/70 text-sm mt-1">Send a message to an institution</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                <div class="mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Start Conversation</h2>
                    <p class="text-gray-600 text-[0.95rem]">Open a direct conversation with an institution so you can ask specific questions and continue the discussion inside your student dashboard.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.conversations.store') }}" class="space-y-5">
                    @csrf

                    @if($selected)
                        <div class="flex items-center gap-3 bg-[#4299e1]/10 border border-[#4299e1]/20 rounded-xl px-4 py-3 text-sm text-[#2c5aa0]">
                            <i class="fas fa-university"></i>
                            <span>Conversation with: <strong>{{ $selected->name }}</strong></span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-white focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('institution_id') border-red-400 @enderror">
                            <option value="">Select institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ old('institution_id', $selected?->id) == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Topic <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-white focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('type') border-red-400 @enderror">
                            @foreach(\App\Models\Conversation::TYPES as $val => $label)
                                <option value="{{ $val }}" {{ old('type', 'general') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-[#4299e1]/10 border border-[#4299e1]/20 rounded-xl p-4 text-sm text-[#2c5aa0] flex items-start gap-3">
                        <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                        <span>If a conversation with the same institution and topic already exists, you will be redirected to that thread instead of creating a duplicate one.</span>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-1">
                        <a href="{{ route('student.conversations.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                            <i class="fas fa-comment-dots"></i> Start Conversation
                        </button>
                    </div>
                </form>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">What Happens Next?</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-comment"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Thread created</h4>
                                <p class="text-sm text-gray-600 mt-1">Your conversation opens immediately so you can continue messaging in one place.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-reply"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Institution replies</h4>
                                <p class="text-sm text-gray-600 mt-1">The institution team can respond directly inside the same chat thread.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-history"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Conversation saved</h4>
                                <p class="text-sm text-gray-600 mt-1">You can return anytime from the Messages page to review updates and send more details.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Best Use of Conversation</h3>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Use conversations for follow-up questions, admission clarifications, and institution-specific communication after you find a school you are interested in.</p>
                    <a href="{{ route('website.institutions.index') }}"
                       class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-semibold px-5 py-3 rounded-xl hover:bg-gray-100 transition no-underline">
                        <i class="fas fa-university"></i> Browse Institutions
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
