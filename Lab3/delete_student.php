<?php
include 'db_connection_student.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_students.php");
    exit();
}

$student_id = $_GET['id'];

// Verify student exists
$sql = "SELECT student_id FROM Students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Student not found
    $stmt->close();
    $conn->close();
    header("Location: view_students.php");
    exit();
}
$stmt->close();

// Delete the student
$sql = "DELETE FROM Students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    // Redirect to view page with delete success message
    header("Location: view_students.php?deleted=1");
} else {
    // Handle error
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>