<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    <div>
        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Current Password</label>
        <input type="password" name="current_password"
               placeholder="Enter your current password" autocomplete="current-password"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('current_password', 'updatePassword') border-red-400 @enderror">
        @error('current_password', 'updatePassword')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">New Password</label>
        <input type="password" name="password"
               placeholder="Enter your new password" autocomplete="new-password"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('password', 'updatePassword') border-red-400 @enderror">
        @error('password', 'updatePassword')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
        <input type="password" name="password_confirmation"
               placeholder="Confirm your new password" autocomplete="new-password"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
            <i class="fas fa-lock"></i> Update Password
        </button>
        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-sm text-green-600 font-medium flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Saved.
            </p>
        @endif
    </div>
</form>
