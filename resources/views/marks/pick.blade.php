@extends('layouts.app')
@section('title',$title)
@section('subtitle',$subtitle)
@section('content')
<div class="card mb-3"><div class="card-body"><form class="row g-2" method="get" action="{{ route($page) }}">
<div class="col-md-5"><select name="class_id" class="form-select" aria-label="Class" onchange="this.form.submit()"><option value="">All classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id')==$c->id)>{{ $c->class_name }}</option>@endforeach</select></div>
<div class="col-md-5"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search name or roll number" aria-label="Search"></div>
<div class="col-md-2"><button class="btn btn-outline-secondary w-100">Search</button></div></form></div></div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Roll</th><th>Student</th><th>Class</th><th>Result</th><th class="text-end"></th></tr></thead><tbody>
@forelse($students as $s)
<tr><td>{{ $s->roll_number }}</td><td>{{ $s->name }}</td><td>{{ $s->schoolClass->class_name }}</td><td>@include('partials.badge',['s'=>$s->result['status']])</td>
<td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route($route,$s) }}">{{ $label }}</a></td></tr>
@empty<tr><td colspan="5"><div class="empty"><i class="bi bi-people"></i><p class="mb-0">No students found.</p></div></td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $students->links('pagination::bootstrap-5') }}</div>
@endsection
