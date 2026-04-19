@extends('layouts.app')

@section('content')

<div id="app"
    data-students='@json($students)'>
</div>

@endsection