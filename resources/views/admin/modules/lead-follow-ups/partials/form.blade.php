{{-- Inquiry --}}
<div>
    <label for="inquiry_id" class="form-label">Inquiry <span class="text-red-500">*</span></label>
    <select id="inquiry_id" name="inquiry_id" class="form-control @error('inquiry_id') is-invalid @enderror">
        <option value="">Select Inquiry</option>
        @foreach($inquiries as $inquiry)
            <option value="{{ $inquiry->id }}"
                {{ old('inquiry_id', $leadFollowUp->inquiry_id ?? $selectedInquiryId ?? '') == $inquiry->id ? 'selected' : '' }}>
                {{ $inquiry->name }} - {{ $inquiry->email }}
            </option>
        @endforeach
    </select>
    @error('inquiry_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Assigned To --}}
<div>
    <label for="assigned_to" class="form-label">Assigned To</label>
    <select id="assigned_to" name="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror">
        <option value="">Unassigned</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('assigned_to', $leadFollowUp->assigned_to ?? $defaultAssignedTo ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} - {{ $user->email }}
            </option>
        @endforeach
    </select>
    @error('assigned_to') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Follow Up At --}}
<div>
    <label for="follow_up_at" class="form-label">Follow Up At <span class="text-red-500">*</span></label>
    <input type="datetime-local" id="follow_up_at" name="follow_up_at"
        value="{{ old('follow_up_at', isset($leadFollowUp) ? $leadFollowUp->follow_up_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('follow_up_at') is-invalid @enderror">
    @error('follow_up_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $leadFollowUp->status ?? 'pending') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Remarks --}}
<div class="md:col-span-2">
    <label for="remarks" class="form-label">Remarks</label>
    <textarea id="remarks" name="remarks" rows="3"
        class="form-control @error('remarks') is-invalid @enderror"
        placeholder="Notes about this follow-up (outcome, next steps, etc.)">{{ old('remarks', $leadFollowUp->remarks ?? '') }}</textarea>
    @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
</div>
