<div x-data="{ open: false }">
    <button type="button" @click="open = true"
            class="px-6 py-3 bg-red-50 border-2 border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-100 hover:border-red-300 transition">
        <i class="fas fa-trash-alt mr-2"></i> Delete My Account
    </button>

    {{-- Confirmation Modal --}}
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center gap-4 mb-5">
                <div class="h-12 w-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Delete Account?</h3>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed mb-6">
                This action is permanent and cannot be undone. All your applications, inquiries, and saved data will be erased. Enter your password to confirm.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Your Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required
                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-red-400 focus:ring-4 focus:ring-red-400/10 transition @error('password', 'userDeletion') border-red-400 @enderror">
                    @error('password', 'userDeletion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="open = false"
                            class="flex-1 px-5 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const comp = document.querySelector('[x-data]');
            if (comp && comp.__x) comp.__x.$data.open = true;
        });
    </script>
@endif
