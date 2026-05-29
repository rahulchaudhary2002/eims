{{-- Inquiry --}}
<div>
    <label for="inquiry_id" class="form-label">Inquiry <span class="text-red-500">*</span></label>
    <select id="inquiry_id" name="inquiry_id" class="form-control @error('inquiry_id') is-invalid @enderror">
        <option value="">Select Inquiry</option>
        @foreach($inquiries as $inquiry)
            <option value="{{ $inquiry->id }}"
                {{ old('inquiry_id', $leadNote->inquiry_id ?? $selectedInquiryId ?? '') == $inquiry->id ? 'selected' : '' }}>
                {{ $inquiry->name }} — {{ $inquiry->email }}
            </option>
        @endforeach
    </select>
    @error('inquiry_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- User --}}
<div>
    <label for="user_id" class="form-label">Author <span class="text-red-500">*</span></label>
    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror">
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('user_id', $leadNote->user_id ?? $defaultUserId ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} — {{ $user->email }}
            </option>
        @endforeach
    </select>
    @error('user_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Note --}}
<div class="md:col-span-2">
    <label for="note" class="form-label">Note <span class="text-red-500">*</span></label>
    <textarea id="note" name="note" rows="5"
        class="form-control @error('note') is-invalid @enderror"
        placeholder="Write your note about this inquiry...">{{ old('note', $leadNote->note ?? '') }}</textarea>
    @error('note') <p class="form-error">{{ $message }}</p> @enderror
</div>
