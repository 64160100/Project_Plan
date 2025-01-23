@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Employee</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .employee-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
            background-color: #fff;
        }
        .employee-card h2 {
            margin: 10px 0;
            font-size: 18px;
        }
        .employee-card p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>

<body>
    @section('content')
    <h1>Employee Details</h1>
    <div class="card-container">
        @foreach ($employees as $employee)
        <div class="employee-card">
            <h2>{{ $employee->Firstname_Employee }} {{ $employee->Lastname_Employee }}</h2>
            <p><strong>ID:</strong> {{ $employee->Id_Employee }}</p>
            <p><strong>Position:</strong> {{ $employee->position ? $employee->position->Name_Position : 'N/A' }}</p>
        </div>
        @endforeach
    </div>
    @endsection
</body>

</html>