<?php
include 'db_connection_student.php';

// Check for success message
$success = isset($_GET['success']) ? $_GET['success'] : 0;
$deleted = isset($_GET['deleted']) ? $_GET['deleted'] : 0;
$updated = isset($_GET['updated']) ? $_GET['updated'] : 0;

// Query to get all students
$sql = "SELECT * FROM Students ORDER BY student_id DESC";
$result = $conn->query($sql);
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
            width: 90%;
            max-width: 1200px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:hover {
            background-color: #f1f3f5;
        }
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #6c757d;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
            animation: fadeOut 5s forwards;
        }
        .delete-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            animation: fadeOut 5s forwards;
        }
        .update-message {
            background-color: #e2f0fd;
            color: #0c5460;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #bee5eb;
            animation: fadeOut 5s forwards;
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
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }
            th, td {
                padding: 8px 10px;
            }
            .responsive-table {
                overflow-x: auto;
            }
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>
        
        <?php if ($success): ?>
        <div class="success-message">
            Student added successfully!
        </div>
        <?php endif; ?>
        
        <?php if ($deleted): ?>
        <div class="delete-message">
            Student record deleted successfully!
        </div>
        <?php endif; ?>
        
        <?php if ($updated): ?>
        <div class="update-message">
            Student information updated successfully!
        </div>
        <?php endif; ?>
        
        
        <div class="responsive-table">
            <?php if (!empty($students)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $student): ?>
                            <tr>
                                <td><?php echo $student['student_id']; ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="delete_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No students found. Please add some students.</p>
                </div>
            <?php endif; ?>
        </div>
        
            <a href="add_student.php" class="btn">Add Student</a>
    </div>
</body>
</html>