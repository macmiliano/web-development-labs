<?php
include 'db_connection_employee.php';

// Check for success message
$success = isset($_GET['success']) ? $_GET['success'] : 0;

// Query to get employees with their department details using INNER JOIN
$sql = "SELECT e.emp_id, e.emp_name, e.emp_salary, d.dept_name, d.dept_location 
        FROM Employee e
        INNER JOIN Department d ON e.emp_dept_id = d.dept_id
        ORDER BY e.emp_id DESC";
$result = $conn->query($sql);
$employees = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Details</h1>
        
        <?php if ($success): ?>
        <div class="success-message">
            Employee added successfully!
        </div>
        <?php endif; ?>
        
        <a href="add_employee.php" class="btn">Add New Employee</a>
        
        <div class="responsive-table">
            <?php if (!empty($employees)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Department</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $employee): ?>
                            <tr>
                                <td><?php echo $employee['emp_id']; ?></td>
                                <td><?php echo htmlspecialchars($employee['emp_name']); ?></td>
                                <td>$<?php echo number_format($employee['emp_salary'], 2); ?></td>
                                <td><?php echo htmlspecialchars($employee['dept_name']); ?></td>
                                <td><?php echo htmlspecialchars($employee['dept_location']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No employees found. Please add some employees.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="nav-links">
            <a href="add_employee.php">Add Employee</a>
            <a href="employee_db_setup.php">Initialize Database</a>
        </div>
    </div>
</body>
</html>