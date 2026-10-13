@extends('layouts.app')
@section('title','Class Results')
@section('subtitle','Computed from the saved marks. Pass % = passed / completed results.')
@section('content')
<div class="card">@include('partials.summary-table')</div>
<p class="text-muted small mt-2">Completed = every subject has marks. Incomplete students are never counted as passed or failed.</p>
@endsection
