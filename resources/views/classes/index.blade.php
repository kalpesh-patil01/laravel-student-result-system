@extends('layouts.app')
@section('title','Classes')
@section('subtitle','Create classes, then add subjects and students to them.')
@section('content')
<form method="post" action="{{ route('classes.store') }}" class="card card-body mb-3 row g-2 flex-row align-items-end" data-busy>@csrf
<div class="col-md-8"><label class="form-label" for="cn">New class name</label><input id="cn" name="class_name" class="form-control @error('class_name') is-invalid @enderror" placeholder="e.g. FY BCA" value="{{ old('class_name') }}" required></div>
<div class="col-md-4"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add class</button></div></form>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Class</th><th class="text-end">Students</th><th class="text-end">Subjects</th><th class="text-end">Actions</th></tr></thead><tbody>
@forelse($classes as $c)
<tr><td class="fw-semibold">{{ $c->class_name }}</td><td class="text-end">{{ $c->students_count }}</td><td class="text-end">{{ $c->subjects_count }}</td>
<td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-secondary" href="{{ route('classes.edit',$c) }}">Edit</a>
<form class="d-inline" method="post" action="{{ route('classes.destroy',$c) }}" onsubmit="return confirm('Delete this class?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="4"><div class="empty"><i class="bi bi-building"></i><p class="mb-0">No classes yet. Add one above.</p></div></td></tr>@endforelse
</tbody></table></div></div>
@endsection
