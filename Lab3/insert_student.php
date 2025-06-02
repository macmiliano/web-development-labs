<?php
include 'db_connection_student.php';

// Initialize variables for form data and errors
$name = $email = $phone_number = "";
$errors = [];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["name"])) {
        $errors[] = "Name is required";
    } else {
        $name = trim($_POST["name"]);
        
        // Check if name contains only letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors[] = "Only letters and white space allowed in name";
        }
    }
    
    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Check if email already exists
            $sql = "SELECT email FROM Students WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $errors[] = "Email already exists. Please use a different email.";
            }
            $stmt->close();
        }
    }
    
    if (empty($_POST["phone_number"])) {
        $errors[] = "Phone number is required";
    } else {
        $phone_number = trim($_POST["phone_number"]);
        
        // Check if phone number contains only digits and is 10 digits long
        if (!preg_match("/^[0-9]{9}$/", $phone_number)) {
            $errors[] = "Phone number must be 9 digits";
        }
    }
    
    // If there are no errors, insert data into database
    if (empty($errors)) {
        $sql = "INSERT INTO Students (name, email, phone_number) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $phone_number);
        
        if ($stmt->execute()) {
            // Redirect to view page after successful insertion
            header("Location: view_students.php?success=1");
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
    <title>Process Student Data</title>
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
            <a href="add_student.php" class="btn">Back to Form</a>
        <?php endif; ?>
    </div>
</body>
</html>