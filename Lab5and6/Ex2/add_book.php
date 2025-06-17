<?php
// Include the CSRF token file
require_once 'csrf_token.php';

// At the beginning of form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    verify_csrf_token();

    // Rest of your form processing code
    // ...
}
?>

<!-- In your HTML form, add the hidden CSRF token field -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- Rest of your form fields -->
</form>
