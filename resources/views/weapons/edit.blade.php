@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Edit Weapon</h2>

    <form method="POST" action="{{ route('weapons.update', $weapon) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Serial Number</label>
            <input type="text" name="serial_number" class="form-control" value="{{ $weapon->serial_number }}" required>
        </div>

        <div class="form-group">
            <label>Specification</label>
            <textarea name="specification" class="form-control">{{ $weapon->specification }}</textarea>
        </div>

        <div class="form-group">
            <label>Weapon Model</label>
            <select name="weapon_model_id" class="form-control">
                @foreach($models as $model)
                    <option value="{{ $model->id }}" {{ $weapon->weapon_model_id == $model->id ? 'selected' : '' }}>
                        {{ $model->name }} - {{ $model->type->name }} ({{ $model->category->name }})
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
