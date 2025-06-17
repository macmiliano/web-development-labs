<?php
include 'db_connection_student.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <link rel="stylesheet" href="Studentstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Student</h1>
        <form action="insert_student.php" method="post" class="space-y-4">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{9}" 
                       title="Please enter a valid 10-digit phone number" required>
            </div>
            
            <button class="w-full bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 text-sm" type="submit">Add Student</button>
        </form>
        
        <div class="nav-links">
            <a href="view_students.php">View All Students</a>
        </div>
    </div>
</body>
</html>