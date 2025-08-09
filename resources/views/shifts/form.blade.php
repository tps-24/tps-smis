<div class="mb-3">
    <label class="form-label">Shift ID</label>
    <input type="text" name="shift_id" class="form-control" value="{{ old('shift_id', $shift->shift_id ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Shift Date</label>
    <input type="date" name="shift_date" class="form-control" value="{{ old('shift_date', $shift->shift_date ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Start Time</label>
    <input type="time" name="shift_start_time" class="form-control" value="{{ old('shift_start_time', $shift->shift_start_time ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">End Time</label>
    <input type="time" name="shift_end_time" class="form-control" value="{{ old('shift_end_time', $shift->shift_end_time ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Officer In-Charge</label>
    <select name="officer_in_charge_id" class="form-control">
        @foreach($officers as $officer)
            <option value="{{ $officer->id }}" {{ old('officer_in_charge_id', $shift->officer_in_charge_id ?? '') == $officer->id ? 'selected' : '' }}>
                {{ $officer->full_name }} ({{ $officer->rank }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Secondary Officer (optional)</label>
    <select name="secondary_officer_id" class="form-control">
        <option value="">-- None --</option>
        @foreach($officers as $officer)
            <option value="{{ $officer->id }}" {{ old('secondary_officer_id', $shift->secondary_officer_id ?? '') == $officer->id ? 'selected' : '' }}>
                {{ $officer->full_name }} ({{ $officer->rank }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks', $shift->remarks ?? '') }}</textarea>
</div>
