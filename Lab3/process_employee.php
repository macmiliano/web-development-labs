<?php
include 'db_connection_employee.php';

// Initialize variables for form data and errors
$emp_name = $emp_salary = $emp_dept = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["emp_name"])) {
        $errors[] = "Employee name is required";
    } else {
        $emp_name = trim($_POST["emp_name"]);
        
        // Check if name contains only letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $emp_name)) {
            $errors[] = "Only letters and white space allowed in name";
        }
    }
    
    if (empty($_POST["emp_salary"])) {
        $errors[] = "Salary is required";
    } else {
        $emp_salary = trim($_POST["emp_salary"]);
        
        // Check if salary is a valid number
        if (!is_numeric($emp_salary) || $emp_salary <= 0) {
            $errors[] = "Salary must be a positive number";
        }
    }
    
    if (empty($_POST["emp_dept"])) {
        $errors[] = "Department is required";
    } else {
        $emp_dept = trim($_POST["emp_dept"]);
        
        // Check if the department exists in the database
        $sql = "SELECT dept_id FROM Department WHERE dept_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $emp_dept);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $errors[] = "Invalid department selected";
        }
        $stmt->close();
    }
    
    // If there are no errors, insert data into database
    if (empty($errors)) {
        $sql = "INSERT INTO Employee (emp_name, emp_salary, emp_dept_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $emp_name, $emp_salary, $emp_dept);
        
        if ($stmt->execute()) {
            // Redirect to view page after successful insertion
            header("Location: view_employees.php?success=1");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Employee Data</title>
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
            color: #dc3545;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        .error-list {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #721c24;
        }
        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($errors)): ?>
            <h1>Error Processing Form</h1>
            <div class="error-list">
                <p>Please correct the following errors:</p>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="add_employee.php" class="btn">Back to Form</a>
        <?php endif; ?>
    </div>
</body>
</html>