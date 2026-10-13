@extends('layouts.app')
@section('title','Students')
@section('subtitle','Add, search, edit and remove student records.')
@section('content')
<div class="card mb-3"><div class="card-body">
<form class="row g-2" method="get">
<div class="col-md-5"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search name or roll number" aria-label="Search"></div>
<div class="col-md-4"><select class="form-select" name="class_id" aria-label="Class"><option value="">All classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id')==$c->id)>{{ $c->class_name }}</option>@endforeach</select></div>
<div class="col-md-3 d-flex gap-2"><button class="btn btn-outline-secondary flex-fill">Filter</button><a class="btn btn-primary flex-fill" href="{{ route('students.create') }}"><i class="bi bi-plus-lg"></i> Add</a></div>
</form></div></div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>ID</th><th>Roll no.</th><th>Name</th><th>Class</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($students as $s)
<tr><td>{{ $s->id }}</td><td>{{ $s->roll_number }}</td><td>{{ $s->name }}</td><td>{{ $s->schoolClass->class_name }}</td>
<td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-secondary" href="{{ route('students.edit',$s) }}">Edit</a>
<form class="d-inline" method="post" action="{{ route('students.destroy',$s) }}" onsubmit="return confirm('Delete this student and all their marks?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty
<tr><td colspan="5"><div class="empty"><i class="bi bi-people"></i><p class="mb-2">No students found.</p><a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">Add a student</a></div></td></tr>
@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $students->links('pagination::bootstrap-5') }}</div>
@endsection
