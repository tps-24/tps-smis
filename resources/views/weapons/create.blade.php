@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Add New Weapon</h2>

    <form method="POST" action="{{ route('weapons.store') }}">
    @csrf

    <div class="form-group">
        <label>Serial Number</label>
        <input type="text" name="serial_number" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Specification</label>
        <textarea name="specification" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label>Category</label>
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <option value="Explosive">Explosive</option>
            <option value="Firearm">Firearm</option>
            <option value="Ammunition">Ammunition</option>
            <option value="Blade">Blade</option>
        </select>
    </div>

    <div class="form-group">
        <label>Weapon Model</label>
        <select name="weapon_model" class="form-control" required>
            <option value="">Select Model</option>
            <option value="AK-47">AK-47</option>
            <option value="Glock 17">Glock 17</option>
            <option value="F1 Grenade">F1 Grenade</option>
        </select>
    </div>

    <button class="btn btn-success">Save</button>
</form>

</div>

{{-- JavaScript to filter weapon models by selected category --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        const modelSelect = document.getElementById('weapon_model');

        const allOptions = Array.from(modelSelect.options);

        categorySelect.addEventListener('change', function () {
            const selectedCategory = this.value;

            modelSelect.innerHTML = '<option value="">-- Select Weapon Model --</option>';

            allOptions.forEach(option => {
                if (option.dataset.category === selectedCategory) {
                    modelSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection
