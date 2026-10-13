@extends('layouts.app')
@section('title','Marksheet')
@section('content')
<div class="sheet card"><div class="card-body p-4">
<div class="sheet-head"><h2 class="h4 mb-0">Student Result and Marksheet</h2><div class="text-muted">Statement of marks</div></div>
<div class="row mb-3"><div class="col-sm-6"><strong>Name:</strong> {{ $student->name }}</div><div class="col-sm-3"><strong>Roll no.:</strong> {{ $student->roll_number }}</div><div class="col-sm-3"><strong>Class:</strong> {{ $student->schoolClass->class_name }}</div></div>
<div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Subject</th><th class="text-end">Max</th><th class="text-end">Passing</th><th class="text-end">Obtained</th><th>Result</th></tr></thead><tbody>
@forelse($r['rows'] as $row)
<tr><td>{{ $row['subject']->subject_name }}</td><td class="text-end">{{ $row['subject']->maximum_marks + 0 }}</td><td class="text-end">{{ $row['subject']->passing_marks + 0 }}</td>
<td class="text-end">{{ $row['obtained'] === null ? 'Not entered' : $row['obtained'] + 0 }}</td>
<td>@if($row['passed'] === null)<span class="text-muted">-</span>@elseif($row['passed'])<span class="text-success fw-semibold">Pass</span>@else<span class="text-danger fw-semibold">Fail</span>@endif</td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">No subjects defined for this class.</td></tr>@endforelse
</tbody></table></div>
<div class="row g-3 text-center">
<div class="col-6 col-md-3"><div class="border rounded p-2"><div class="small text-muted">Total</div><div class="h5 mb-0">{{ $r['total'] === null ? '-' : ($r['total'] + 0).' / '.($r['max'] + 0) }}</div></div></div>
<div class="col-6 col-md-3"><div class="border rounded p-2"><div class="small text-muted">Percentage</div><div class="h5 mb-0">{{ $r['percentage'] === null ? '-' : number_format($r['percentage'],2).'%' }}</div></div></div>
<div class="col-6 col-md-3"><div class="border rounded p-2"><div class="small text-muted">Grade</div><div class="h5 mb-0">{{ $r['grade'] ?? '-' }}</div></div></div>
<div class="col-6 col-md-3"><div class="border rounded p-2"><div class="small text-muted">Result</div><div class="h5 mb-0">@include('partials.badge',['s'=>$r['status']])</div></div></div></div>
@if($r['status']==='INCOMPLETE')<p class="text-warning-emphasis mt-3 mb-0">Some marks have not been entered, so no total, percentage or pass/fail result is shown.</p>@endif
<div class="mt-4 no-print d-flex gap-2"><button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button><a class="btn btn-outline-secondary" href="{{ route('marksheets.index',['class_id'=>$student->class_id]) }}">Back</a></div>
</div></div>
@endsection
