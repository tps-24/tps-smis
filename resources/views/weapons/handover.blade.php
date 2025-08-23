@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Handover Weapon: {{ $weapon->serial_number }}</h2>

    <!--<form method="POST" action="{{ url('/weapons/' . $weapon->id . '/handover') }}">
        @csrf-->

        <form method="POST" action="{{ route('weapons.handover.store', $weapon->id) }}">
    @csrf

        {{-- Staff Selection --}}
     <div class="mb-3">
    <label for="staff_id" class="form-label fw-semibold">👤 Select Staff</label>
    <select name="staff_id" id="staff_id" class="form-select select2" required>
        <option value="">-- Select Staff --</option>
        @foreach(App\Models\Staff::orderBy('firstName')->get() as $staff)
            <option value="{{ $staff->id }}">
                {{ $staff->firstName }}
                @if(!empty($staff->middleName)) {{ $staff->middleName }} @endif
                {{ $staff->lastName }} - {{ $staff->rank }}
            </option>
        @endforeach
    </select>
</div>




        {{-- Shift Start --}}
        <div class="form-group mb-3">
            <label><strong>Handover Date</strong></label>
            <input type="datetime-local" name="handover_date" class="form-control" required>
        </div>

          {{-- Expected Return Date --}}
        <div class="form-group mb-3">
            <label><strong>Expected Return Date & Time</strong></label>
            <input type="datetime-local" name="return_date" class="form-control" required>
        </div>

        {{-- Purpose (Staff) --}}
      <!--  <div class="form-group mb-3">
            <label><strong>Purpose (Staff)</strong></label>
            <textarea name="staff_purpose" class="form-control" required></textarea>
        </div>


      

        {{-- Return Condition --}}
        <div class="form-group mb-3">
            <label><strong>Condition When Returned</strong></label>
            <textarea name="return_condition" class="form-control"></textarea>
        </div>-->

        {{-- Remarks --}}
        <div class="form-group mb-3">
            <label><strong>Additional Remarks</strong></label>
            <textarea name="remarks" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
