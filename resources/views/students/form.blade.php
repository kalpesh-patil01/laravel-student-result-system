@extends('layouts.app')
@section('title', $student->exists ? 'Edit Student' : 'Add Student')
@section('content')
<form method="post" data-busy action="{{ $student->exists ? route('students.update',$student) : route('students.store') }}" class="card card-body" style="max-width:560px">
@csrf @if($student->exists) @method('PUT') @endif
<div class="mb-3"><label class="form-label" for="name">Student name</label><input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$student->name) }}" required></div>
<div class="mb-3"><label class="form-label" for="roll">Roll number</label><input id="roll" name="roll_number" class="form-control @error('roll_number') is-invalid @enderror" value="{{ old('roll_number',$student->roll_number) }}" required><div class="form-text">Must be unique within the class.</div></div>
<div class="mb-3"><label class="form-label" for="cls">Class</label><select id="cls" name="class_id" class="form-select @error('class_id') is-invalid @enderror" required><option value="">Select class</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('class_id',$student->class_id)==$c->id)>{{ $c->class_name }}</option>@endforeach</select></div>
<div class="d-flex gap-2"><button type="submit" class="btn btn-primary">Save student</button><a class="btn btn-outline-secondary" href="{{ route('students.index') }}">Cancel</a></div>
</form>
@endsection
