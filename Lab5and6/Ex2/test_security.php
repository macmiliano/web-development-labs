<?php
session_start();
require_once 'config.php';
require_once 'auth_check.php';
require_once 'csrf_token.php';

// Check if user is authenticated
auth_check();

// Initialize variables
$sql_injection_test = "";
$xss_test = "";
$csrf_test = "";
$csrf_debug = ""; // Add debug information

// Make sure we have a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// SQL Injection Test
if (isset($_POST['test_sql_injection'])) {
    $input = $_POST['sql_input'];

    // Unsafe way (for demonstration only)
    $unsafe_query = "SELECT * FROM Books WHERE title = '$input'";
    $sql_injection_test .= "Unsafe query: " . htmlspecialchars($unsafe_query, ENT_QUOTES, 'UTF-8') . "<br>";

    // Safe way using prepared statements
    $stmt = $conn->prepare("SELECT * FROM Books WHERE title = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $sql_injection_test .= "Number of books found (safely): " . $result->num_rows;
}

// XSS Test
if (isset($_POST['test_xss'])) {
    $input = $_POST['xss_input'];

    // Store the raw input for demonstration
    $unsafe_input = $input;

    // Safe way using htmlspecialchars
    $safe_input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    // Build the result HTML safely
    $xss_test = "<strong>Unsafe output (would be vulnerable):</strong> <code>" . htmlspecialchars($unsafe_input, ENT_QUOTES, 'UTF-8') . "</code><br><br>";
    $xss_test .= "<strong>Safe output (properly escaped):</strong> " . $safe_input;

    // For demonstration purposes only - DO NOT use this in real code!
    // This creates a separate div to show what would happen in a vulnerable app
    $xss_demo = "<div class='warning' style='margin-top: 10px;'>";
    $xss_demo .= "<strong>Demonstration of vulnerability:</strong> The text below shows what would happen if we didn't escape the input.<br>";
    $xss_demo .= "<div style='background-color: #f8f9fa; padding: 10px; margin-top: 5px; border: 1px dashed #dc3545;'>";
    $xss_demo .= "Unsafe rendering (for demo only): <span id='xss-demo'></span>";
    $xss_demo .= "</div></div>";
    $xss_demo .= "<script>document.getElementById('xss-demo').innerHTML = '" . 
                 str_replace("'", "\\'", $unsafe_input) . "';</script>";

    $xss_test .= $xss_demo;
}

// CSRF Test
if (isset($_POST['test_csrf'])) {
    // Add debug information
    $csrf_debug = "Session token: " . $_SESSION['csrf_token'] . "<br>";
    $csrf_debug .= "Submitted token: " . (isset($_POST['csrf_token']) ? $_POST['csrf_token'] : "No token") . "<br>";

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $csrf_test = "CSRF token validation failed! This would block a CSRF attack.";
    } else {
        $csrf_test = "CSRF token validated successfully! This form submission is legitimate.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Testing</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .test-result {
            margin-top: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #007bff;
        }
        .warning {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .debug-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #e2e3e5;
            border-left: 4px solid #6c757d;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Security Testing Page</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="library.php">Library</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="warning">
                <strong>Warning:</strong> This page is for educational purposes only. It demonstrates common web security vulnerabilities and how to protect against them.
            </div>

            <section class="test-section">
                <h2>SQL Injection Test</h2>
                <p>Try entering a SQL injection payload like: <code>' OR '1'='1</code></p>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="sql_input">Book Title:</label>
                        <input type="text" id="sql_input" name="sql_input" required>
                    </div>

                    <button type="submit" name="test_sql_injection" class="btn">Test SQL Injection</button>
                </form>

                <?php if (!empty($sql_injection_test)): ?>
                    <div class="test-result">
                        <h3>Result:</h3>
                        <p><?php echo $sql_injection_test; ?></p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="test-section">
                <h2>Cross-Site Scripting (XSS) Test</h2>
                <p>Try entering an XSS payload like: <code>&lt;script&gt;alert('XSS');&lt;/script&gt;</code></p>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="xss_input">Input:</label>
                        <input type="text" id="xss_input" name="xss_input" required>
                    </div>

                    <button type="submit" name="test_xss" class="btn">Test XSS</button>
                </form>

                <?php if (!empty($xss_test)): ?>
                    <div class="test-result">
                        <h3>Result:</h3>
                        <p><?php echo $xss_test; ?></p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="test-section">
                <h2>Cross-Site Request Forgery (CSRF) Test</h2>
                <p>This form demonstrates CSRF protection using tokens.</p>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <button type="submit" name="test_csrf" class="btn">Test With Valid CSRF Token</button>
                </form>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="margin-top: 10px;">
                    <input type="hidden" name="csrf_token" value="invalid_token">

                    <button type="submit" name="test_csrf" class="btn">Test With Invalid CSRF Token</button>
                </form>

                <?php if (!empty($csrf_test)): ?>
                    <div class="test-result">
                        <h3>Result:</h3>
                        <p><?php echo $csrf_test; ?></p>

                        <?php if (!empty($csrf_debug)): ?>
                            <div class="debug-info">
                                <h4>Debug Information:</h4>
                                <p><?php echo $csrf_debug; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Library Management System</p>
        </footer>
    </div>
</body>
</html>