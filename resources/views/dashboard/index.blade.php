@extends('layouts.app')
@section('title','Dashboard')
@section('subtitle','Live figures from your students, classes and marks.')
@section('content')
@if($classes === 0)
<div class="card"><div class="empty"><i class="bi bi-rocket-takeoff"></i><h5 class="mt-2">Get started</h5>
<p>1. Create a class &nbsp;2. Add its subjects &nbsp;3. Add students &nbsp;4. Enter marks</p>
<a class="btn btn-primary" href="{{ route('classes.index') }}">Create your first class</a></div></div>
@else
<div class="row g-3 mb-4">
@foreach([['Students',$students,'bi-people'],['Classes',$classes,'bi-building'],['Subjects',$subjects,'bi-book'],['Completed results',$completed,'bi-check2-square'],['Passed',$passed,'bi-emoji-smile'],['Failed',$failed,'bi-emoji-frown'],['Pass % (of completed)',$passRate === null ? 'N/A' : $passRate.'%','bi-percent']] as [$l,$v,$i])
<div class="col-6 col-md-4 col-xl-3"><div class="card stat h-100"><div class="card-body"><i class="bi {{ $i }} text-secondary"></i><div class="num">{{ $v }}</div><div class="lbl">{{ $l }}</div></div></div></div>
@endforeach
</div>
<div class="card mb-4"><div class="card-header bg-white fw-semibold">Class-wise result overview</div>@include('partials.summary-table')</div>
<div class="card"><div class="card-header bg-white fw-semibold">Recent students</div>
<div class="table-responsive"><table class="table mb-0"><thead><tr><th>Roll</th><th>Name</th><th>Class</th></tr></thead><tbody>
@forelse($recent as $s)<tr><td>{{ $s->roll_number }}</td><td>{{ $s->name }}</td><td>{{ $s->schoolClass->class_name }}</td></tr>
@empty<tr><td colspan="3" class="empty">No students yet.</td></tr>@endforelse
</tbody></table></div></div>
@endif
@endsection
