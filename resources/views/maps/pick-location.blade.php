@extends('layouts.app')

@section('content')

<div id="map-app"
    data-student="{{ session('student_verified') }}">
</div>

@endsection