<!-- resources/views/employee/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Employee Details</title>
</head>
<body>
    <h1>Employee Details</h1>
    <p><strong>ID:</strong> {{ $employee->Id_Employee }}</p>
    <p><strong>Name:</strong> {{ $employee->Name_Employee }}</p>
    <p><strong>Email:</strong> {{ $employee->Email }}</p>
</body>
</html>