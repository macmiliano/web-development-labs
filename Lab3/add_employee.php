<?php
include 'db_connection_employee.php';

// Fetch all departments for dropdown
$sql = "SELECT dept_id, dept_name FROM Department";
$result = $conn->query($sql);
$departments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link rel="stylesheet" href="Employeestyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="container max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Employee</h1>
        <form action="process_employee.php" method="post">
            <div class="input-box form-group">
                <label for="emp_name" class="block text-sm font-medium text-gray-700">Employee Name:</label>
                <input type="text" id="emp_name" name="emp_name" required>
            </div>
            
            <div class="input-box form-group">
                <label for="emp_salary" class="block text-sm font-medium text-gray-700">Salary:</label>
                <div class="input-box relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" step="0.01" min="0.01" id="emp_salary" name="emp_salary" required
                        class="in block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                </div>
            </div>
            
            <div class="input-box form-group">
                <label for="emp_dept" class="block text-sm font-medium text-gray-700">Department:</label>
                <select id="emp_dept" name="emp_dept" required>
                    <option value="">Select Department</option>
                    <?php foreach($departments as $department): ?>
                        <option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>         
            <button class="w-full bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 text-sm" type="submit">Add Employee</button>
        </form>
        
        <div class="nav-links mt-4 text-center">
            <a href="view_employees.php">View All Employees</a>
        </div>
    </div>

    <script>
        async function addNewAuthor() {
            const authorName = document.getElementById('new_author').value.trim();
            if (!authorName) {
                alert('Please enter an author name');
                return;
            }

            try {
                const response = await fetch('process_author.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'author_name=' + encodeURIComponent(authorName)
                });

                const result = await response.json();
                
                if (result.success) {
                    const select = document.getElementById('author_id');
                    const option = new Option(authorName, result.author_id);
                    select.add(option);
                    select.value = result.author_id;
                    document.getElementById('new_author').value = '';
                } else {
                    alert(result.message || 'Error adding author');
                }
            } catch (error) {
                alert('Error adding author');
            }
        }
    </script>
</body>
</html>