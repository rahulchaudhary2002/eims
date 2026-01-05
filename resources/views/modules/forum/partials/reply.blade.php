@foreach($replies as $reply)
<div class="mt-3 pl-{{ $level * 4 }}">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

        {{-- Reply header --}}
        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
            <div class="flex items-center gap-2">
                <span class="font-medium">
                    {{ $reply->is_anonymous ? 'Anonymous' : ($reply->user->name ?? 'User') }}
                </span>
                <span>• {{ $reply->created_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Reply body --}}
        <div class="text-sm text-gray-800 whitespace-pre-line mb-2">
            {{ $reply->body }}
        </div>

        {{-- Nested reply form --}}
        @auth
            @if($reply->depth < 3)
            <form method="POST"
                action="{{ route('forum.reply.store', $question) }}"
                class="mt-2 space-y-2">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                <textarea name="body" rows="2"
                    class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                    placeholder="Reply to this comment..." required>{{ old('body') }}</textarea>
                <div class="flex justify-end">
                    <button class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200">
                        Reply
                    </button>
                </div>
            </form>
            @endif
        @endauth

        {{-- Render children INSIDE the same card --}}
        @if($reply->children->isNotEmpty())
            <div class="mt-3 space-y-2">
                @include('modules.forum.partials.reply', [
                    'replies' => $reply->children,
                    'level' => $level + 1,
                    'question' => $question
                ])
            </div>
        @endif

    </div> {{-- End of card --}}
</div>
@endforeach
