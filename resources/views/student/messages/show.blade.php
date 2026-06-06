@extends('layouts.student')

@section('title', 'Message')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.messages.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <h1 class="text-2xl md:text-3xl font-bold">Message</h1>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                @if($message->message)
                <p class="text-sm text-gray-700 leading-relaxed">{{ $message->message }}</p>
                @endif
                @if(storage_exists($message->attachment))
                <a href="{{ storage_url($message->attachment) }}" target="_blank"
                   class="flex items-center gap-1.5 text-sm text-[#4299e1] font-medium hover:underline mt-3 no-underline">
                    <i class="fas fa-paperclip"></i> Download Attachment
                </a>
                @endif
                <p class="text-xs text-gray-400 mt-4">{{ $message->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</section>

@endsection
