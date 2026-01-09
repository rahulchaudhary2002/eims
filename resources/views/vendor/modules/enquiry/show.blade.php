@extends('vendor.layouts.app')
@section('title', 'Enquiry Details')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200 max-w-2xl mx-auto">
    <div class="p-6 border-b flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Enquiry #{{ $enquiry->id }}</h1>
            <p class="text-gray-600 mt-1">Details and reply to this enquiry</p>
        </div>
        <a href="{{ route('vendor.enquiry.index') }}" class="text-blue-600 hover:underline text-sm">← Back to list</a>
    </div>
    <div class="p-6 space-y-4">
        <div>
            <span class="font-semibold text-gray-700">Full Name:</span>
            <span class="text-gray-900">{{ $enquiry->full_name }}</span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Email:</span>
            @if($enquiry->email)
            <a href="mailto:{{ $enquiry->email }}" class="text-blue-600 hover:text-blue-800">{{ $enquiry->email }}</a>
            @else
            <span class="text-gray-400">N/A</span>
            @endif
        </div>
        <div>
            <span class="font-semibold text-gray-700">Phone:</span>
            @if($enquiry->phone)
            <a href="tel:{{ $enquiry->phone }}" class="text-blue-600 hover:text-blue-800">{{ $enquiry->phone }}</a>
            @else
            <span class="text-gray-400">N/A</span>
            @endif
        </div>
        <div>
            <span class="font-semibold text-gray-700">Type:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ ucfirst($enquiry->type) }}
            </span>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Message:</span>
            <div class="bg-gray-50 rounded p-3 mt-1 text-gray-900">{{ $enquiry->message }}</div>
        </div>
        <div>
            <span class="font-semibold text-gray-700">Status:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $enquiry->status === 'read' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ ucfirst($enquiry->status) }}
            </span>
        </div>
        @if($enquiry->reply_message)
        <div>
            <span class="font-semibold text-gray-700">Your Reply:</span>
            <div class="bg-green-50 rounded p-2 mt-1 text-gray-900 no-tailwind">{!! $enquiry->reply_message !!}</div>
            <div class="text-xs text-gray-500 mt-1">Replied {{ $enquiry->replied_at ? \Carbon\Carbon::parse($enquiry->replied_at)->diffForHumans() : '' }}</div>
        </div>
        @endif
    </div>
    <div class="p-6 border-t">
        @if(!$enquiry->reply_message)
        <form method="POST" action="{{ route('vendor.enquiry.reply', $enquiry->id) }}">
            @csrf
            <div>
                <label for="reply_message" class="block text-sm font-medium text-gray-700">Reply Message</label>
                <textarea id="reply_message" name="reply_message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('reply_message') }}</textarea>
                @error('reply_message')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md font-medium shadow">Send Reply</button>
            </div>
        </form>
        @else
        <div class="text-green-700 font-medium">Reply already sent.</div>
        @endif
    </div>
    @if(session('success'))
    <div class="p-6">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg shadow-sm mt-2">
            <div class="flex items-center">
                <x-lucide-check-circle class="w-5 h-5 mr-2 text-green-600" />
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('page-specific-script')

<script>
    tinymce.init({
        selector: '#reply_message',
        height: 300,
        menubar: false,
        plugins: 'lists link image table code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
        setup: function(editor) {
            editor.on('change', function() {
                tinymce.triggerSave();
            });
        }
    });
</script>

@endsection