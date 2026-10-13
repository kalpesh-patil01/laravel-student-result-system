@extends('layouts.app')
@section('title','Edit Subject')
@section('content')
<form method="post" data-busy action="{{ route('subjects.update',$subject) }}" class="card card-body" style="max-width:520px">@csrf @method('PUT')
<div class="mb-3"><label class="form-label" for="c">Class</label><select id="c" name="class_id" class="form-select" required>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('class_id',$subject->class_id)==$c->id)>{{ $c->class_name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label" for="n">Subject name</label><input id="n" name="subject_name" class="form-control" value="{{ old('subject_name',$subject->subject_name) }}" required></div>
<div class="row"><div class="col mb-3"><label class="form-label" for="mx">Maximum marks</label><input id="mx" name="maximum_marks" type="number" step="0.01" class="form-control" value="{{ old('maximum_marks',$subject->maximum_marks+0) }}" required></div>
<div class="col mb-3"><label class="form-label" for="pm">Passing marks</label><input id="pm" name="passing_marks" type="number" step="0.01" class="form-control" value="{{ old('passing_marks',$subject->passing_marks+0) }}" required></div></div>
<div class="d-flex gap-2"><button type="submit" class="btn btn-primary">Save subject</button><a class="btn btn-outline-secondary" href="{{ route('subjects.index') }}">Cancel</a></div></form>
@endsection
