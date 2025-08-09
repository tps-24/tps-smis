<div class="mb-3">
    <label class="form-label">Weapon ID</label>
    <input type="text" name="weapon_id" class="form-control" value="{{ old('weapon_id', $weapon->weapon_id ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Serial Number</label>
    <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $weapon->serial_number ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Weapon Type</label>
    <input type="text" name="weapon_type" class="form-control" value="{{ old('weapon_type', $weapon->weapon_type ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $weapon->category ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Make/Model</label>
    <input type="text" name="make_model" class="form-control" value="{{ old('make_model', $weapon->make_model ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Caliber/Gauge</label>
    <input type="text" name="caliber_gauge" class="form-control" value="{{ old('caliber_gauge', $weapon->caliber_gauge ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Acquisition Date</label>
    <input type="date" name="acquisition_date" class="form-control" value="{{ old('acquisition_date', $weapon->acquisition_date ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Condition</label>
    <input type="text" name="condition" class="form-control" value="{{ old('condition', $weapon->condition ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Current Status</label>
    <input type="text" name="current_status" class="form-control" value="{{ old('current_status', $weapon->current_status ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $weapon->location ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks', $weapon->remarks ?? '') }}</textarea>
</div>
