@extends('layouts.app')
@section('title','Subjects')
@section('subtitle','Each class has its own subjects, maximum marks and passing marks.')
@section('content')
<form method="post" action="{{ route('subjects.store') }}" class="card card-body mb-3" data-busy>@csrf
<div class="row g-2 align-items-end">
<div class="col-md-3"><label class="form-label" for="c">Class</label><select id="c" name="class_id" class="form-select" required><option value="">Select class</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('class_id',request('class_id'))==$c->id)>{{ $c->class_name }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label" for="n">Subject name</label><input id="n" name="subject_name" class="form-control" value="{{ old('subject_name') }}" required></div>
<div class="col-6 col-md-2"><label class="form-label" for="mx">Maximum</label><input id="mx" name="maximum_marks" type="number" step="0.01" min="0.01" class="form-control" value="{{ old('maximum_marks',100) }}" required></div>
<div class="col-6 col-md-2"><label class="form-label" for="pm">Passing</label><input id="pm" name="passing_marks" type="number" step="0.01" min="0" class="form-control" value="{{ old('passing_marks',35) }}" required></div>
<div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Add subject</button></div></div></form>
<form method="get" class="mb-3"><select name="class_id" class="form-select" style="max-width:260px" onchange="this.form.submit()" aria-label="Filter by class"><option value="">All classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id')==$c->id)>{{ $c->class_name }}</option>@endforeach</select></form>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Subject</th><th>Class</th><th class="text-end">Max marks</th><th class="text-end">Passing marks</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($subjects as $s)
<tr><td class="fw-semibold">{{ $s->subject_name }}</td><td>{{ $s->schoolClass->class_name }}</td><td class="text-end">{{ $s->maximum_marks + 0 }}</td><td class="text-end">{{ $s->passing_marks + 0 }}</td>
<td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-secondary" href="{{ route('subjects.edit',$s) }}">Edit</a>
<form class="d-inline" method="post" action="{{ route('subjects.destroy',$s) }}" onsubmit="return confirm('Delete this subject?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="5"><div class="empty"><i class="bi bi-book"></i><p class="mb-0">No subjects yet. Add one above (create a class first).</p></div></td></tr>@endforelse
</tbody></table></div></div>
@endsection
