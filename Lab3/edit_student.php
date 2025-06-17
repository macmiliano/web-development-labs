<?php
include 'db_connection_student.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_students.php");
    exit();
}

$student_id = $_GET['id'];

// Fetch student data
$sql = "SELECT * FROM Students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if student exists
if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: view_students.php");
    exit();
}

$student = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0d6efd;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #0d6efd;
            outline: none;
        }
        button {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0b5ed7;
        }
        .cancel-btn {
            background-color: #6c757d;
            margin-left: 10px;
        }
        .cancel-btn:hover {
            background-color: #5a6268;
        }
        .btn {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .button-group {
            display: flex;
        }
        .nav-links {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .nav-links a {
            text-decoration: none;
            color: #0d6efd;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
            .cancel-btn {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        <form action="update_student.php" method="post">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" 
                       value="<?php echo htmlspecialchars($student['phone_number']); ?>" 
                       pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Update Student</button>
                <a href="view_students.php" class="btn btn-delete" style="text-decoration: none; color: white;">Cancel</a>
            </div>
        </form>
        
        <div class="nav-links">
            <a href="view_students.php">Back to Students List</a>
        </div>
    </div>
</body>
</html>