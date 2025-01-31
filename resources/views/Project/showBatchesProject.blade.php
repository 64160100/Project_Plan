@extends('navbar.app')

@section('content')
<div class="container">
    <h1>{{ $project->Name_Project }}</h1>
    <p>{{ $project->Description }}</p>
    <!-- Add more project details here -->
</div>
@endsection