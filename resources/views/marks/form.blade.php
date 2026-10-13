@extends('layouts.app')
@section('title','Enter Marks')
@section('subtitle',$student->name.' - Roll '.$student->roll_number.' - '.$student->schoolClass->class_name)
@section('content')
@if($student->schoolClass->subjects->isEmpty())
<div class="card"><div class="empty"><i class="bi bi-book"></i><p>This class has no subjects yet.</p><a class="btn btn-primary" href="{{ route('subjects.index',['class_id'=>$student->class_id]) }}">Add subjects</a></div></div>
@else
<form method="post" data-busy action="{{ route('marks.update',$student) }}" class="card">@csrf @method('PUT')
<div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Subject</th><th class="text-end">Max</th><th class="text-end">Passing</th><th style="width:170px">Marks obtained</th></tr></thead><tbody>
@foreach($student->schoolClass->subjects as $sub)
<tr><td>{{ $sub->subject_name }}</td><td class="text-end">{{ $sub->maximum_marks + 0 }}</td><td class="text-end">{{ $sub->passing_marks + 0 }}</td>
<td><input type="number" step="0.01" min="0" max="{{ $sub->maximum_marks }}" name="marks[{{ $sub->id }}]" aria-label="{{ $sub->subject_name }} marks" class="form-control @error('marks.'.$sub->id) is-invalid @enderror" value="{{ old('marks.'.$sub->id, isset($existing[$sub->id]) ? $existing[$sub->id] + 0 : '') }}"></td></tr>
@endforeach</tbody></table></div>
<div class="card-body d-flex gap-2 align-items-center"><button type="submit" class="btn btn-primary">Save marks</button><a class="btn btn-outline-secondary" href="{{ route('marks.index',['class_id'=>$student->class_id]) }}">Cancel</a>
<span class="text-muted small ms-2">Leave a box empty if the marks are not available yet - the result stays Incomplete.</span></div></form>
@endif
@endsection
