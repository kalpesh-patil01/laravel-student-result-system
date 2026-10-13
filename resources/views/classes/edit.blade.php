@extends('layouts.app')
@section('title','Edit Class')
@section('content')
<form method="post" data-busy action="{{ route('classes.update',$class) }}" class="card card-body" style="max-width:480px">@csrf @method('PUT')
<div class="mb-3"><label class="form-label" for="cn">Class name</label><input id="cn" name="class_name" class="form-control @error('class_name') is-invalid @enderror" value="{{ old('class_name',$class->class_name) }}" required></div>
<div class="d-flex gap-2"><button type="submit" class="btn btn-primary">Save class</button><a class="btn btn-outline-secondary" href="{{ route('classes.index') }}">Cancel</a></div></form>
@endsection
